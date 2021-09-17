<?php
class Test
{
  public function index()
  {
    require 'webapp/view/template/header.php';
    require 'webapp/view/test/index.php';
    require 'webapp/view/template/footer.php';

    /*
    $file = file_get_contents("http://smart.gbis.go.kr/ws/download?route20161021.txt");
    $file = iconv("EUC-KR", "UTF-8", $file);
    $data = explode("^",$file);

    foreach($data as $ind=>$d){
      $data[$ind] = explode("|",$d);
      // echo $d."<br>";
      // print_r(explode("|",$d))."<br>";
    }
    // print_r($data);

    foreach($data as $ind=>$bus){
      if($ind>0){
        foreach($bus as $ind2=>$column){
          // echo $data[0][$ind2]." : ".$column."<br>";
        }
      }
      // echo "<br>====================================<br>";

    }

    // echo json_encode($data[0]);
    foreach($data[0] as $d){
      echo $d."<br>";
    }
    */
  }

  public function doGetPiSerialNo()
  {
    // 출처 : http://stackoverflow.com/questions/34811654/reading-serial-number-of-raspberrypi-device-by-php

    $output = shell_exec("cat /proc/cpuinfo");
    $find = "Serial";
    $pos = strpos($output, $find);
    $serial = substr($output, $pos+10, 17);

    echo $serial;
  }

  public function chartTest()
  {
    // The x value is the current JavaScript time, which is the Unix time multiplied
    // by 1000.
    $x = time() * 1000;
    // The y value is a random number
    $y = rand(24, 28);

    // Create a PHP array and echo it as JSON
    $ret = array($x, $y);
    echo json_encode($ret);
  }

  public function chartTest2()
  {
    echo "[{\"name\":\"data1\",\"data\":[[1361574000000,121201],[1362006000000,122019],[1363388400000,122788],[1363820400000,123740],[1364511600000,124703],[1365112800000,125618],[1365544800000,126553],[1366063200000,127496],[1366668000000,128500],[1367272800000,129433],[1368309600000,130277],[1368655200000,131267],[1369346400000,132191],[1369864800000,133143]]},{\"name\":\"data2\",\"data\":[[1361574000000,0],[1362006000000,40.6],[1363388400000,35.7],[1363820400000,41.24],[1364511600000,40.56],[1365112800000,38.96],[1365544800000,39.8],[1366063200000,40.58],[1366668000000,40.79],[1367272800000,38.06],[1368309600000,37.95],[1368655200000,41.31],[1369346400000,40.16],[1369864800000,38.79]]},{\"name\":\"data3\",\"data\":[[1361574000000,0],[1362006000000,1.46],[1363388400000,1.42],[1363820400000,1.42],[1364511600000,1.37],[1365112800000,1.41],[1365544800000,1.41],[1366063200000,1.35],[1366668000000,1.45],[1367272800000,1.36],[1368309600000,1.36],[1368655200000,1.36],[1369346400000,1.37],[1369864800000,1.359]]},{\"name\":\"data4\",\"data\":[[1361574000000,0],[1362006000000,59.276],[1363388400000,50.694],[1363820400000,58.5608],[1364511600000,55.5672],[1365112800000,54.9336],[1365544800000,56.118],[1366063200000,54.783],[1366668000000,59.1455],[1367272800000,51.7616],[1368309600000,51.612],[1368655200000,56.1816],[1369346400000,55.0192],[1369864800000,52.71561]]}]";
  }


}
