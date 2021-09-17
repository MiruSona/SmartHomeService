<?php
	header("Content-Type: text/html; charset=UTF-8");
	
	//API
	include("LocationAPI.php");
	include("WeatherAPI.php");
	
	//위치 구하기
	$ip = "";
	$ip = $_POST["IP_ADDRESS"];
	if($ip != ""){
		$location_ll = getLocation($ip);
		$location_xy = getWeatherXY($location_ll['latitude'], $location_ll['longitude']);
	
		$result = parserAreaWeatherXMLxml($location_xy['x'], $location_xy['y']);
		echo json_encode($result);
	}
?>