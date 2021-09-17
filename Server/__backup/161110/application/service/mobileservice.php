<?php
include("application/util/codeex.php");
include("application/util/util.php");
include("application/api/WeatherAPI.php");

class MobileService
{
  public $util;

  // 생성자
  function __construct()
  {
    $this->util = new Util();
  }

  // 버스정류소 List 가져오기
  public function doGetBusStationList()
  {
    // response
    $result;
    $message;
    $list;

    // Response
    $response = new stdClass();

    // POST 요청 여부에 따른 처리
    if(($_SERVER["REQUEST_METHOD"] != "POST"))
    {
      $response->result   = FAIL;
      $response->message  = MSG_UNVALID_REQ;
      $this->util->makeResponse($response);
    }
    else
    {
      // Request
      $x  = $_POST["x"];
      $y    = $_POST["y"];
      $rad  = $_POST["rad"];

      // 반경 내에 존재하는 버스 정류소 List 가져오기
      $obj = new stdClass();
      $obj->x = $x;
      $obj->y = $y;
      $obj->radius = $radius;
      $busStnList = $this->util->getBusStnList($obj);

      // list 조회 성공 여부에 따른 처리
      if($busStnList == null)
      {
        $response->result   = FAIL;
        $response->message  = MSG_NOT_EXIST_BUS_STN;
        $this->util->makeResponse($response);
        return;
      }

      $response->result   = SUCCESS;
      $response->message  = MSG_RES_SUCCESS;
      $response->list     = $busStnList;
      $this->util->makeResponse($response);
    }
  }

  // 버스 List 가져오기
  public function doGetBusList()
  {
    // Response
    $response = new stdClass();
    $result;
    $message;
    $list = array();

    // POST 요청 여부에 따른 처리
    if(($_SERVER["REQUEST_METHOD"] != "POST"))
    {
      $response->result   = FAIL;
      $response->message  = MSG_UNVALID_REQ;
      $this->util->makeResponse($response);
      return;
    }

    // Request
    $stationId = $_POST["stationId"];

    // 해당 정류소에 도착하는 버스 List 가져오기
    $busList = $this->util->getBusList($stationId);

    // list 조회 성공 여부에 따른 처리
    if($busList == null)
    {
      $response->result   = FAIL;
      $response->message  = MSG_NOT_EXIST_BUS_STN;
      $this->util->makeResponse($response);
      return;
    }

    $response->result   = SUCCESS;
    $response->message  = MSG_RES_SUCCESS;
    $response->list     = $busList;
    $this->util->makeResponse($response);
  }

  // 버스 Info 가져오기
  public function doGetBusInfo()
  {
    // Response
    $response = new stdClass();

    // POST 요청 여부에 따른 처리
    if(($_SERVER["REQUEST_METHOD"] != "POST"))
    {
      $response->result   = FAIL;
      $response->message  = MSG_UNVALID_REQ;
      $this->util->makeResponse($response);
      return;
    }

    // Request
    $stationId  = $_POST["stationId"];
    $routeId    = $_POST["routeId"];

    // 정류소, 버스 정보를 통해 버스 도착정보 조회
    $obj = new stdClass();
    $obj->stationId = $stationId;
    $obj->routeId   = $routeId;

    $busInfo = $this->util->busArrivalInfo($obj);

    // list 조회 성공 여부에 따른 처리
    if($busInfo == null)
    {
      $response->result   = FAIL;
      $response->message  = MSG_NOT_EXIST_BUS_STN;
      $this->util->makeResponse($response);
      return;
    }

    $response->result   = SUCCESS;
    $response->message  = MSG_RES_SUCCESS;
    $response->list     = $busInfo;
    $this->util->makeResponse($response);
  }

  // 날씨 Info 가져오기
  public function doGetWeatherInfo()
  {
    // Response
    $response = new stdClass();

    // POST 요청 여부에 따른 처리
    if(($_SERVER["REQUEST_METHOD"] != "POST"))
    {
      $response->result   = FAIL;
      $response->message  = MSG_UNVALID_REQ;
      $this->util->makeResponse($response);
      return;
    }

    // Request
    $x = $_POST["x"];
    $y = $_POST["y"];

    // 날씨정보 Get
    $weatherAPI   = new WeatherAPI();
    $weatherInfo  = $weatherAPI->parserAreaWeatherXMLxml($x, $y);

    $response->result   = SUCCESS;
    $response->message  = MSG_RES_SUCCESS;
    $response->list     = $weatherInfo;
    $this->util->makeResponse($response);
  }

  // 센서 데이터 가져오기
  public function doGetSensorData()
  {
    // Response
    $response = new stdClass();

    // POST 요청 여부에 따른 처리
    if(($_SERVER["REQUEST_METHOD"] != "POST"))
    {
      $response->result   = FAIL;
      $response->message  = MSG_UNVALID_REQ;
      $this->util->makeResponse($response);
      return;
    }

    // Request
    $type = ucfirst($_POST["type"]); // 카멜표기법으로 변경

    // DB커넥터 및 실시간 데이터 모델 생성
    $dbConnector = new DBConnector();
    $realTimeModel = $dbConnector->loadModel("RealtimeDataModel");

    // type을 통한 조회
    $dataList = $realTimeModel->getSensorDataByType($type);

    // list 조회 성공 여부에 따른 처리
    if($dataList == null)
    {
      $response->result   = FAIL;
      $response->message  = MSG_TEMPORARY_ERR;
      $this->util->makeResponse($response);
      return;
    }

    $response->result   = SUCCESS;
    $response->message  = MSG_RES_SUCCESS;
    $response->list     = $dataList;
    $this->util->makeResponse($response);
  }
}
