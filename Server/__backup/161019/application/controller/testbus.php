<?php
class TestBus
{
  public function index()
  {
    require 'webapp/views/_templates/header.php';
    require 'webapp/views/testbus/index.php';
    require 'webapp/views/_templates/footer.php';
  }

  public function getAreaInfo()
  {
    $basedDataURL = "http://openapi.gbis.go.kr/ws/rest/baseinfoservice?serviceKey=" . SERVICE_KEY;
    $basedDataResponse = file_get_contents($basedDataURL);
    $basedDataXML = simplexml_load_string($basedDataResponse);

    foreach($basedDataXML->children()->msgBody->baseInfoItem as $Info) {
			$basedDataVersion = $Info->areaVersion;
			$basedDataDownloadURL = $Info->areaDownloadUrl;
		}

    // Initialize
		$info   = parse_url($basedDataDownloadURL);
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

		// Setting Request Header
		switch($method = strtoupper($method))
		{
			case 'GET':
			if($info['query'])
			{
				$info['path'] .= '?' . $info['query'];
			}

			$req .= 'GET ' . $info['path'] . ' HTTP/1.1' . $linebreak;
			$req .= 'Host: ' . $info['host'] . $linebreak;
			$req .= 'User-Agent: ' . $agent . $linebreak;
			$req .= 'Referer: ' . $url . $linebreak;
			$req .= 'Connection: Close' . $linebreak . $linebreak;
			break;

			case 'POST':
			$req .= 'POST ' . $info['path'] . ' HTTP/1.1' . $linebreak;
			$req .= 'Host: ' . $info['host'] . $linebreak;
			$req .= 'User-Agent: ' . $agent . $linebreak;
			$req .= 'Referer: ' . $url . $linebreak;
			$req .= 'Content-Type: application/x-www-form-urlencoded'.$linebreak;
			$req .= 'Content-Length: '. strlen($info['query']) . $linebreak;
			$req .= 'Connection: Close' . $linebreak . $linebreak;
			$req .= $info['query'];
			break;
		}

		// Socket Open
		$fsock  = @fsockopen($info['ssl'] . $info['host'], $info['port']);
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
					echo $line;
				}
			}
			fclose($fsock);
		}
  }
}
