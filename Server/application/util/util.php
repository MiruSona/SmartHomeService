<?php
class Util {
  public function __call($method, $params) {

    switch($method) {
      /* 계정 관련 Util */
      // 메일 전송
      case "sendEmail":
        $this->sendEmail($params[0]);
        break;

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

  /* 계정 관련 Util */
  // 메일 전송
  public function sendEmail($params) {
    // // Request
    // $id = "admin";
    // $pwd = "roptop";
    // $email = $params->email;
    //
    // // 메일전송 Setting
    // ini_set('SMTP','myserver');
    // ini_set('smtp_port',SMTP_PORT);
    // // $to = $email;
    // $to = "dlwnsrb2010@gmail.com";
    // $subject = EMAIL_SUBJECT;
    // $txt = "요청 계정 정보입니다.\n".
    //         "id:".$id.
    //         "pwd:".$pwd;
    // $headers = "From: RopTop 라즈베리파이";
    //
    // // 메일 전송
    // mail($to, $subject, $txt, $headers);

    // // 메세지
    // $message = "Line 1\nLine 2\nLine 3";
    //
    // // 한 줄이 70 문자를 넘어갈 때를 위하여, wordwrap()을 사용해야 합니다.
    // $message = wordwrap($message, 70);
    //
    // // 전송
    // mail('dlwnsrb2010@gmail.com', 'My Subject', $message);
    // mail('dlwnsrb2010@naver.com', 'My Subject', $message);

    // $to = '"Somename Lastname" <<a href="mailto:dlwnsrb2010@gmail.com">dlwnsrb2010@gmail.com</a>>';
    // $subject = 'PHP mail tester';
    // $message = 'This message was sent via PHP!' . PHP_EOL .
    //            'Some other message text.' . PHP_EOL . PHP_EOL .
    //            '-- signature' . PHP_EOL;
    // $headers = 'From: "From Name" <<a href="mailto:dlwnsrb2010@naver.com">dlwnsrb2010@naver.com</a>>' . PHP_EOL .
    //            'Reply-To: <a href="mailto:dlwnsrb1213@gachon.co.kr">reply@email.com</a>' . PHP_EOL .
    //           //  'Cc: "CC Name" <<a href="mailto:cc@email.dom">cc@email.dom</a>>' . PHP_EOL .
    //            'X-Mailer: PHP/' . phpversion();
    //
    // if (mail($to, $subject, $message, $headers)) {
    //   echo 'mail() Success!';
    // }
    // else {
    //   echo 'mail() Failed!';
    // }
    //
    // $to = 'dlwnsrb2010@gmail.com';
    // $subject = 'What more tests';
    // $message = 'I suck at this.';
    // $headers = 'From: server@mydomain.co.uk' . "\r\n" .
    //           'Reply-To: server@mydomain.co.uk';
    //
    // if (mail($to, $subject, $message, $headers)) {
    //   echo 'mail() Success!';
    // }
    // else {
    //   echo 'mail() Failed!';
    // }

    $test = $this->sendMail('dlwnsrb2010@naver.com', 'pi', 'dlwnsrb2010@naver.com', '테스트', '테스트입니다.');

    echo "test:".$test;
  }

  function sendMail($EMAIL, $NAME, $mailto, $SUBJECT, $CONTENT){
    //$EMAIL : 답장받을 메일주소
    //$NAME : 보낸이
    //$mailto : 보낼 메일주소
    //$SUBJECT : 메일 제목
    //$CONTENT : 메일 내용
    $admin_email = $EMAIL;
    $admin_name = $NAME;

    $header = "Return-Path: ".$admin_email."\n";
    $header .= "From: =?EUC-KR?B?".base64_encode($admin_name)."?= <".$admin_email.">\n";
    $header .= "MIME-Version: 1.0\n";
    $header .= "X-Priority: 3\n";
    $header .= "X-MSMail-Priority: Normal\n";
    $header .= "X-Mailer: FormMailer\n";
    $header .= "Content-Transfer-Encoding: base64\n";
    $header .= "Content-Type: text/html;\n \tcharset=euc-kr\n";

    $subject = "=?EUC-KR?B?".base64_encode($SUBJECT)."?=\n";
    $contents = $CONTENT;

    $message = base64_encode($contents);
    flush();
    return mail($mailto, $subject, $message, $header);
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
        $obj = new stdClass();
        $obj->STATION_ID = $field[0];
        $obj->STATION_NM = $field[1];
        $obj->CENTER_ID = $field[2];
        $obj->CENTER_YN = $field[3];
        $obj->X = $field[4];
        $obj->Y = $field[5];
        $obj->REGION_NAME = $field[6];
        $obj->MOBILE_NO = $field[7];
        $obj->DISTRICT_CD = $field[8];

        $busStationResult[] = $obj;
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

    // 도착시간 오름차순으로 정렬
    $sort_proxy = array();
    foreach($list as $key=>$value) {
      $sort_proxy[$key] = (string) $value->predictTime1;
    }
    array_multisort($sort_proxy, SORT_ASC, $list);

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
