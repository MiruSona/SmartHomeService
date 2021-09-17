<?php
class Util
{
  public function __call($method, $params) {

    switch($method) {
      /* 버스 관련 Util */
      // 버스정류소 리스트 조회
      case "getBusStnList":
        $this->getBusStnList($params[0]);
        break;
      // 버스 리스트 조회
      case "getBusList":
        $this->getBusList($params[0]);
        break;
      // 버스 도착정보 조회
      case "busArrivalInfo":
        $this->busArrivalInfo($params[0]);
        break;

      /* 요청 타입 Util */
      // Post 요청
      case "isPostRequest":
        $this->isPostRequest();
        break;

      /* 결과값 반환 Util */
      case "makeResponse":
        $size = count((array)$params[0]);

        switch($size) {
          case 2:
            $this->makeResponse2($params[0]);
            break;
          case 3:
            $this->makeResponse3($params[0]);
            break;
          case 4:
            $this->makeResponse4($params[0]);
            break;
          case 5:
            $this->makeResponse5($params[0]);
            break;
        }

        break;
    }
  }

  /* 버스 관련 Util */
  // 버스 정류소 리스트 조회
  public function getBusStnList($params) {
    // Request
    $x      = $params->x;
    $y      = $params->y;
    $radius = $params->radius;

    // 공공데이터를 통해 버스정류소 조회
    $basedDataURL = "http://openapi.gbis.go.kr/ws/rest/baseinfoservice?serviceKey=".GOV_DATA_KEY;
    $basedDataResponse = file_get_contents($basedDataURL);
    $basedDataXML = simplexml_load_string($basedDataResponse) or die("Error: Cannot create object");
    $busStationURL = $basedDataXML->children()->msgBody->baseInfoItem->stationDownloadUrl[0];

    // Initialize
    $info   = parse_url($busStationURL);
    $req    = '';
    $data   = '';
    $line   = '';
    $agent  = 'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.0; Trident/5.0)';
    $linebreak  = "\r\n";
    $headPassed = false;

    // Setting Protocol
    switch($info['scheme'] = strtoupper($info['scheme'])) {
        case 'HTTP':
            $info['port']   = 80;
            break;

        case 'HTTPS':
            $info['ssl']    = 'ssl://';
            $info['port']   = 443;
            break;

        default:
            return false;
    }

    // Setting Path
    if(!$info['path']) {
      $info['path'] = '/';
    }

    if($info['query']) {
        $info['path'] .= '?' . $info['query'];
    }

    $req .= 'GET '.$info['path'].' HTTP/1.1'.$linebreak;
    $req .= 'Host: '.$info['host'].$linebreak;
    $req .= 'User-Agent: '.$agent.$linebreak;
    $req .= 'Referer: '.$busStationURL.$linebreak;
    $req .= 'Connection: Close'.$linebreak.$linebreak;

    $busStationData = "1";

    // Socket Open
    $fsock  = @fsockopen($info['ssl'].$info['host'], $info['port']);
    if ($fsock) {
      fwrite($fsock, $req);
      while(!feof($fsock)) {
        $line = fgets($fsock, 128);
        if($line == "\r\n" && !$headPassed) {
          $headPassed = true;
          continue;
        }
        if($headPassed) {
          $busStationData .= $line;
        }
      }

      fclose($fsock);
    }

    $busStationData = iconv("EUC-KR", "UTF-8", $busStationData); // UTF-8로 인코딩
    $busStationInfo = explode('^', $busStationData);
    $busStationResult = array();

    // 반경 ($rad)km 내의 정류소 탐색
    for($i=1; $i<sizeof($busStationInfo); $i++) {
      $field = explode('|', $busStationInfo[$i]);

      $earth_radius = 6371;
      $dLat = deg2rad($y - $field[5]);
      $dLon = deg2rad($x - $field[4]);
      $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($field[5])) * cos(deg2rad($y)) * sin($dLon/2) * sin($dLon/2);
      $c = 2 * asin(sqrt($a));
      $d = $earth_radius * $c;

      if($d <= $radius) {
        array_push($busStationResult, $busStationInfo[$i]);
      }
    }

    return $busStationResult;
  }

  // 버스 리스트 조회
  public function getBusList($params) {
    // Request
    $stationId = $params;

    // Response
    $list = array();

    // 공공데이터를 통해 버스 도착정보 조회
    $url = "http://openapi.gbis.go.kr/ws/rest/busarrivalservice/station?"
      ."serviceKey=".GOV_DATA_KEY
      ."&stationId=".$stationId;
    $response = file_get_contents($url);
    $resJSON = simplexml_load_string($response);
    $busList = $resJSON->msgBody->busArrivalList;

    // DB커넥터 및 버스 모델 생성
    $dbConnector = new DBConnector();
    $busModel = $dbConnector->loadModel("BusModel");

    // 도착 버스 리스트 순회
    for($i=0; $i<sizeof($busList); $i++) {
      // 노선 ID를 통해 노선번호 조회
      $routeId = $busList[$i]->routeId; // 노선ID
      $routeNMData = $busModel->doSearchRouteNM($routeId);

      // 리스트에 노선번호 정보 추가
      $busList[$i]->routeNM = $routeNMData->routeNM;

      // 결과 리스트에 push
      array_push($list, $busList[$i]);
    }

    return $list;
  }

  // 버스 도착정보 조회
  public function busArrivalInfo($params) {
    // Request
    $stationId  = $params->stationId;
    $routeId    = $params->routeId;

    // 공공데이터를 통해 버스 도착정보 조회
    $url = "http://openapi.gbis.go.kr/ws/rest/busarrivalservice?"
      ."serviceKey=".GOV_DATA_KEY
      ."&routeId=".$routeId
      ."&stationId=".$stationId;
    $response = file_get_contents($url);
    $resJSON = simplexml_load_string($response);
    $busInfo = $resJSON->msgBody->busArrivalItem;

    return $busInfo;
  }


  /* 요청 타입 Util */
  // Post 요청
  public function isPostRequest() {
    return ($_SERVER["REQUEST_METHOD"] != "POST") ? false : true;
  }


  /**
  *
  * @param : result, message
  * @return :
  *
  */
  public function makeResponse2($params)
  {
    echo "{\"result\":".$params->result.",".
          "\"message\":\"".$params->message."\"}";
  }

  /**
  *
  * @param : result, message, list
  * @return :
  *
  */
  public function makeResponse3($params) {
    if(count($params->list) == 1) {
      echo "{\"result\":".$params->result.",".
            "\"message\":\"".$params->message."\",".
            "\"info\":".json_encode($params->list)."}";
    } else {
      echo "{\"result\":".$params->result.",".
            "\"message\":\"".$params->message."\",".
            "\"list\":".json_encode($params->list)."}";
    }
  }

  /**
  *
  * @param : result, message, (variable), list
  * @return :
  *
  */
  public function makeResponse4($params) {
    if(isset($params->time))
    {
      if(count($params->list) == 1) {
        echo "{\"result\":".$params->result.",".
              "\"message\":\"".$params->message."\",".
              "\"time\":\"".$params->time."\",".
              "\"info\":".json_encode($params->list)."}";
      } else {
        echo "{\"result\":".$params->result.",".
              "\"message\":\"".$params->message."\",".
              "\"time\":\"".$params->time."\",".
              "\"list\":".json_encode($params->list)."}";
      }
    }
    if(isset($params->radius))
    {
      if(count($params->list) == 1) {
        echo "{\"result\":".$params->result.",".
              "\"message\":\"".$params->message."\",".
              "\"radius\":\"".$params->radius."\",".
              "\"info\":".json_encode($params->list)."}";
      } else {
        echo "{\"result\":".$params->result.",".
              "\"message\":\"".$params->message."\",".
              "\"radius\":\"".$params->radius."\",".
              "\"list\":".json_encode($params->list)."}";
      }
    }
  }

  public function makeResponse5($params) {
    if(isset($params->piInfo) && isset($params->arduinoInfo) && isset($params->sensorInfo)) {
      echo "{\"result\":".$params->result.",".
            "\"message\":\"".$params->message."\",".
            "\"piInfo\":".json_encode($params->piInfo).",".
            "\"arduinoList\":".json_encode($params->arduinoInfo).",".
            "\"sensorList\":".json_encode($params->sensorInfo)."}";
    }
  }
}
