<?php
	header("Content-Type: text/html; charset=UTF-8");
	
	$db_host = "localhost"; 
	$db_user = "root"; 
	$db_passwd = "6264";
	$db_name = "roptop"; 
	$conn = mysqli_connect($db_host, $db_user, $db_passwd, $db_name);
	if (mysqli_connect_errno($conn)) 
	{
	  echo "failed to connect to database: " . mysqli_connect_error(); //DB 연결실패인지 아닌지 검사
	}
	
	$query = 
	"SELECT a.type, b.sensor_val1 " . 
	"FROM sensor_tbl a INNER JOIN history_tbl b " .
	"ON a.idx = b.sensor_idx " .
	"WHERE a.type = '" . $_POST["SENSOR_NAME"] . "'";
	
	$result = mysqli_query($conn, $query);
	
	if($result)
	{
		while($result_array = mysqli_fetch_assoc($result)) 
		{	
			echo json_encode($result_array);
		}
	}
	
	mysqli_close($conn); // close
?>