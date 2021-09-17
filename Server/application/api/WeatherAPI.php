<?php
class WeatherAPI
{
	//해당 좌표의 동네 날씨를  가져온다.
	function parserAreaWeatherXMLxml($x,$y){

		$array = array();

		$url1="www.kma.go.kr";
		$url2="GET /";
		$url2.="wid/queryDFS.jsp?gridx=".$x."&gridy=".$y;
		$url2.=" HTTP/1.0\r\nHost:www.kma.go.kr\r\n\r\n";

		$fp2 = fsockopen ($url1, 80, $errno, $errstr,30 );

		if (!$fp2)
		echo "?   $errstr ($errno)<br />n";
		else
		{
			fputs ($fp2, $url2);
			$i = 0; $j=0;
			while (!feof($fp2))
			{
				$line=fgets ($fp2,512);
				if(preg_match("/<tm>/",$line))$array[date("Ymd")]= trim(strip_tags($line));
				if($i==$j++ && preg_match("/<data/",$line))
				{
					$area=preg_split("/\"/",$line);
					$area=preg_split("/\"/",$area[1]);
					$number = $area[0];

					$array[$i]= $number;
				}

				//시간 18일 경우  15~18시
				if(preg_match("/<hour>/",$line))
				$array[$i]['hour']= trim(strip_tags($line));
				//날짜코드 0:오늘 1:내일 2:모레
				if(preg_match("/<day>/",$line))
				$array[$i]['day']= $this->convertWeatherCode("day", trim(strip_tags($line)));
				//현재시간온도
				if(preg_match("/<temp>/",$line))
				$array[$i]['temp']= trim(strip_tags($line));
				//강수상태코드  0:없음  1:비  2: 비/눈  3: 눈/비  4:눈
				if(preg_match("/<pty>/",$line))
				$array[$i]['pty']= $this->convertWeatherCode("pty", trim(strip_tags($line)));
				//날씨한국어
				if(preg_match("/<wfKor>/",$line))
				$array[$i]['wfkor']= trim(strip_tags($line));
				//강수확률%
				if(preg_match("/<pop>/",$line))
				$array[$i]['pop']= trim(strip_tags($line));

				if(preg_match("/<\/data>/",$line))$i++;
			}
		}
		fclose($fp2);

		return $array;
	}

	function convertWeatherCode($kind, $code){
		$send_data = "";

		//날짜코드 0:오늘 1:내일 2:모레
		if($kind == "day"){
			switch($code){
				case 0: return $send_data = "오늘";
				case 1:	return $send_data = "내일";
				case 2:	return $send_data = "모레";
			}
		}
		//강수상태코드  0:없음  1:비  2: 비/눈  3: 눈/비  4:눈
		if($kind == "pty"){
			switch($code){
				case 0: return $send_data = "없음";
				case 1:	return $send_data = "비";
				case 2:	return $send_data = "비/눈";
				case 3:	return $send_data = "눈/비";
				case 4:	return $send_data = "눈";
			}
		}

		return $send_data;
	}
}
