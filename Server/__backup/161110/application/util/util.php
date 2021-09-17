<?php
class Util
{
  public function __call($method, $params)
  {
    switch($method)
    {
      // 버스정류소 리스트 조회
      case "getBusStnList":
        $this->getBusStnList($params[0]);
        break;

      // 결과값 반환
      case "makeResponse":
        $size = count((array)$params[0]);
        switch($size)
        {
          case 2:
            $this->makeResponse2($params[0]);
            break;
          case 3:
            $this->makeResponse3($params[0]);
            break;
          case 4:
            $this->makeResponse4($params[0]);
            break;
        }
        break;
    }
  }


  /**
  *
  * @param : x, y, radius
  * @return :
  *
  */
  public function getBusStnList($params)
  {
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
    switch($info['scheme'] = strtoupper($info['scheme']))
    {
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
    if(!$info['path'])
    {
      $info['path'] = '/';
    }

    if($info['query'])
    {
        $info['path'] .= '?' . $info['query'];
    }

    $req .= 'GET '.$info['path'].' HTTP/1.1'.$linebreak;
    $req .= 'Host: '.$info['host'].$linebreak;
    $req .= 'User-Agent: '.$agent.$linebreak;
    $req .= 'Referer: '.$busStationURL.$linebreak;
    $req .= 'Connection: Close'.$linebreak.$linebreak;

    $busStationData = "";

    // Socket Open
    $fsock  = @fsockopen($info['ssl'].$info['host'], $info['port']);
    if ($fsock)
    {
      fwrite($fsock, $req);
      while(!feof($fsock))
      {
        $line = fgets($fsock, 128);
        if($line == "\r\n" && !$headPassed)
        {
          $headPassed = true;
          continue;
        }
        if($headPassed)
        {
          $busStationData .= $line;
        }
      }
        fclose($fsock);
    }

    $busStationData = iconv("EUC-KR", "UTF-8", $busStationData); // UTF-8로 인코딩
    $busStationInfo = explode('^', $busStationData);
    $busStationResult = array();

    // 반경 ($rad)km 내의 정류소 탐색
    for($i=1; $i<sizeof($busStationInfo); $i++)
    {
      $field = explode('|', $busStationInfo[$i]);

      $earth_radius = 6371;
      $dLat = deg2rad($y - $field[5]);
      $dLon = deg2rad($x - $field[4]);
      $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($field[5])) * cos(deg2rad($y)) * sin($dLon/2) * sin($dLon/2);
      $c = 2 * asin(sqrt($a));
      $d = $earth_radius * $c;

      if($d <= $radius)
      {
        array_push($busStationResult, $busStationInfo[$i]);
      }
    }

    return $busStationResult;
  }

  /**
  *
  * @param : result, message
  * @return :
  *
  */
  public function makeResponse2($params)
  {
    // Request
    $result   = $params->result;
    $message  = $params->message;

    echo "{ \"result\" : " . $result . ", \"message\" : \"" . $message . "\" }";
  }

  /**
  *
  * @param : result, message, list
  * @return :
  *
  */
  public function makeResponse3($params)
  {
    // Request
    $result   = $params->result;
    $message  = $params->message;
    $list     = $params->list;

    if(count($list) == 1)
    {
      echo "{ \"result\" : " . $result . ", \"message\" : \"" . $message . "\", \"info\" : " . json_encode($list) . " }";
    }
    else
    {
      echo "{ \"result\" : " . $result . ", \"message\" : \"" . $message . "\", \"list\" : " . json_encode($list) . " }";
    }
  }

  /**
  *
  * @param : result, message, (variable), list
  * @return :
  *
  */
  public function makeResponse4($params)
  {
    // Request
    $result   = $params->result;
    $message  = $params->message;
    $list     = $params->list;

    if($params->time)
    {
      $time = $params->time;

      if(count($list) == 1)
      {
        echo "{ \"result\" : " . $result . ", \"message\" : \"" . $message . "\", \"time\" : \"" . $time . "\", \"info\" : " . json_encode($list) . " }";
      }
      else
      {
        echo "{ \"result\" : " . $result . ", \"message\" : \"" . $message . "\", \"time\" : \"" . $time . "\", \"list\" : " . json_encode($list) . " }";
      }
    }
  }
}
