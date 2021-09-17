<?php
class TestDB
{
  public function index()
  {
    require 'webapp/views/_templates/header.php';
    require 'webapp/views/testdb/index.php';
    require 'webapp/views/_templates/footer.php';
  }

  // 센서로 검색
  public function doSearchSensor()
  {
    // Request Params
    $sensorIdx = "";

    // Response Params
    $result = "";
    $message = "";
    $list = "";

    try
    {
      if($_SERVER["REQUEST_METHOD"] == "POST")
      {
        $sensorIdx = $_POST["sensorIdx"];

        // create new DB controller
        $dbController = new DBController();
        $sensorModel = $dbController->loadModel('HistoryModel');
        $sensorResult = $sensorModel->getHistoryInfoBySensor($sensorIdx);

        // is data not exist
        if($sensorResult == null)
        {
          $result = "false";
          $message = "해당 데이터가 없습니다.";
          $list = "\"\"";

          $this->makeResponse($result, $message, $list);
        }
        else
        {
          $result = "true";
          $message = "정상적으로 처리되었습니다.";
          $list = json_encode($sensorResult);

          $this->makeResponse($result, $message, $list);
        }
      }
    }
    catch(Exception $e)
    {
      echo $e;
    }
    finally
    {

    }
  }

  // mac으로 검색
  public function doSearchMac()
  {

    try
    {
      if($_SERVER["REQUEST_METHOD"] == "POST")
      {

      }
    }
    catch(Exception $e)
    {

    }
    finally
    {

    }
  }

  // 기간으로 검색
  public function doSearchPeriod()
  {
    // Request Params
    $fromDate = "";
    $toDate = "";

    // Response Params
    $result = "";
    $message = "";
    $list = "";

    try
    {
      if($_SERVER["REQUEST_METHOD"] == "POST")
      {
        $fromDate = $_POST["fromDate"];
        $toDate = $_POST["toDate"];

        // create new DB controller
        $dbController = new DBController();
        $periodModel = $dbController->loadModel('HistoryModel');
        $periodResult = $periodModel->getHistoryInfoByPeriod($fromDate, $toDate);

        // is data not exist
        if($periodResult == null)
        {
          $result = "false";
          $message = "해당 데이터가 없습니다.";
          $list = "\"\"";

          $this->makeResponse($result, $message, $list);
        }
        else
        {
          $result = "true";
          $message = "정상적으로 처리되었습니다.";
          $list = json_encode($periodResult);

          $this->makeResponse($result, $message, $list);
        }
      }
    }
    catch(Exception $e)
    {

    }
    finally
    {

    }
  }

  // 종류로 검색
  public function doSearchType()
  {

    try
    {
      if($_SERVER["REQUEST_METHOD"] == "POST")
      {

      }
    }
    catch(Exception $e)
    {

    }
    finally
    {

    }
  }

  // make response
  public function makeResponse($result, $message, $list)
  {
    $response = "{ \"result\" : " . $result . ", \"message\" : \"" . $message . "\", \"list\" : " . $list . " }";
    echo $response;
  }



}
