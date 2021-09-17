<?php
	
//기상청용 좌표변환(XY 구하기 / 위경도->좌표, latitude:위도, longitude:경도)
function getWeatherXY($latitude, $longitude) {
		
	// LCC DFS 좌표변환을 위한 기초 자료
	$RE = 6371.00877; // 지구 반경(km)
    $GRID = 5.0; // 격자 간격(km)
    $SLAT1 = 30.0; // 투영 위도1(degree)
    $SLAT2 = 60.0; // 투영 위도2(degree)
    $OLON = 126.0; // 기준점 경도(degree)
    $OLAT = 38.0; // 기준점 위도(degree)
    $XO = 43; // 기준점 X좌표(GRID)
    $YO = 136; // 기1준점 Y좌표(GRID)
	
    $DEGRAD = M_PI / 180.0;
    $RADDEG = 180.0 / M_PI;
 
    $re = $RE / $GRID;
    $slat1 = $SLAT1 * $DEGRAD;
    $slat2 = $SLAT2 * $DEGRAD;
    $olon = $OLON * $DEGRAD;
    $olat = $OLAT * $DEGRAD;
 
    $sn = tan(M_PI * 0.25 + $slat2 * 0.5) / tan(M_PI * 0.25 + $slat1 * 0.5);
    $sn = log(cos($slat1) / cos($slat2)) / log($sn);
    $sf = tan(M_PI * 0.25 + $slat1 * 0.5);
    $sf = pow($sf, $sn) * cos($slat1) / $sn;
    $ro = tan(M_PI * 0.25 + $olat * 0.5);
    $ro = $re * $sf / pow($ro, $sn);
	
    $ra = tan(M_PI * 0.25 + ($latitude) * $DEGRAD * 0.5);
    $ra = $re * $sf / pow($ra, $sn);
    $theta = $longitude * $DEGRAD - $olon;
    if ($theta > M_PI) $theta -= 2.0 * M_PI;
    if ($theta < -M_PI) $theta += 2.0 * M_PI;
    $theta *= $sn;
	
	$nx = floor($ra * sin($theta) + $XO + 0.5);
	$ny = floor($ro - $ra * cos($theta) + $YO + 0.5);
	
	$result = array(
		'x' => $nx,
		'y' => $ny,
	);
		
	return $result;
}

//위경도 구하기
function getLocation($ip){
	$response = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip=' . $ip));
	$result = array(
		'latitude' => $response['geoplugin_latitude'],
		'longitude' => $response['geoplugin_longitude'],
	);
	return $result;
}

?>