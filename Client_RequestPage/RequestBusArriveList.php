<?php
	header("Content-Type: text/html; charset=UTF-8");
	
	$service_key = "NoDqsCghIdBYA7NqkU2%2F0z1Eb%2BPUwsGB2vnDoDYLHKx4ugPH4H70ROU%2FwUp%2FFqrcBjTtAbBOxeROTUUGYhN2AA%3D%3D";
	$station_id = 204000111;
	$url = 
	"http://openapi.gbis.go.kr/ws/rest/busarrivalservice/station?"
	. "serviceKey=" . $service_key
	. "&stationId=" . $station_id;
	
	$response = file_get_contents($url);
	$json_data=simplexml_load_string($response) or die("Error: Cannot create object");
	$bus_list = $json_data->msgBody->busArrivalList;
	
	for($i = 0; $i < sizeof($bus_list); $i++){
		echo json_encode($bus_list[$i]);
	}
?>