<?php
include("application/util/codeex.php");
include("application/util/util.php");

class HomeService
{
  public $util;

  // 생성자
  function __construct()
  {
    $this->util = new Util();
  }

  // 집 관리
  // 센서 데이터 조회
  public function doGetSensorVal()
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

    try
    {
      // DB커넥터 및 실시간 데이터 모델 생성
      $dbConnector = new DBConnector();
      $realtimeModel = $dbConnector->loadModel('RealtimeDataModel');
      $sensorData = $realtimeModel->getSensorData();

      // 센서 데이터 존재 여부에 따른 처리
      if($sensorData == null)
      {
        $response->result   = FAIL;
        $response->message  = MSG_TEMPORARY_ERR;
        $this->util->makeResponse($response);
        return;
      }

      $response->result   = SUCCESS;
      $response->message  = MSG_RES_SUCCESS;
      $response->time     = time() * 1000; // [JavaScript Time] = [Unix Time] * 1000;
      $response->list     = $sensorData;
      $this->util->makeResponse($response);
    }
    catch(Exception $e)
    {
      $response->result = FAIL;
      $response->message = MSG_SERVER_ERR;
      $this->util->makeResponse($response);
    }
    finally
    {

    }
  }

  // 버스 도착정보
  // 버스 정류소 조회
  public function doGetBusStnList()
  {
    // Response
    $response = new stdClass();

    // POST 요청 여부에 따른 처리
    if(($_SERVER["REQUEST_METHOD"] != "POST"))
    {
      $response->result = FAIL;
      $response->message = MSG_UNVALID_REQ;
      $this->util->makeResponse($response);
      return;
    }

    // Response
    $result;
    $message;
    $list;

    // DB커넥터 및 파이모델 생성
    $dbConnector = new DBConnector();
    $piModel = $dbConnector->loadModel('PiModel');

    // 반경값 조회
    $radius = $piModel->getRadius();
    $radius = $radius->radius;

    // 반경값 존재 여부에 따른 처리
    if($radius == 0)
    {
      $response->result = FAIL;
      $response->message = MSG_NOT_EXIST_RADIUS;
      $this->util->makeResponse($response);
      return;
    }

    // 좌표 조회
    $coord = $piModel->getCoord();
    $x = $coord->x;
    $y = $coord->y;

    // 좌표값 존재 여부에 따른 처리
    if(($x == "") && ($y == ""))
    {
      $response->result = FAIL;
      $response->message = MSG_NOT_EXIST_COORD;
      $this->util->makeResponse($response);
      return;
    }

    // 반경 내에 존재하는 버스 정류소 List 가져오기
    $obj = new stdClass();
    $obj->x = $x;
    $obj->y = $y;
    $obj->radius = $radius;
    $busStnList = $this->util->getBusStnList($obj);

    // list 조회 성공 여부에 따른 처리
    if($busStnList == null)
    {
      $response->result = FAIL;
      $response->message = MSG_NOT_EXIST_BUS_STN;
      $this->util->makeResponse($response);
      return;
    }

    $response->result = SUCCESS;
    $response->message = MSG_RES_SUCCESS;
    $response->list = $busStnList;
    $this->util->makeResponse($response);
  }

  // 해당 정류소 버스 조회
  public function doGetBusList()
  {
    // Response
    $response = new stdClass();

    // POST 요청 여부에 따른 처리
    if(($_SERVER["REQUEST_METHOD"] != "POST"))
    {
      $response->result = FAIL;
      $response->message = MSG_UNVALID_REQ;
      $this->util->makeResponse($response);
      return;
    }

    // Request
    $stationId = $_POST["stationId"];

    try
    {
      // DB커넥터 및 정류소 모델 생성
      $dbConnector = new DBConnector();
      $busStationModel = $dbConnector->loadModel("BusStationModel");

      // 공공데이터를 통해 버스 도착정보 조회
      $url = "http://openapi.gbis.go.kr/ws/rest/busarrivalservice/station?".
              "serviceKey=".GOV_DATA_KEY.
              "&stationId=".$stationId;
      $resXML = file_get_contents($url);
      $resJSON = simplexml_load_string($resXML);
      $busList = $resJSON->msgBody->busArrivalList;

      // 공공데이터 요청에 대한 응답 코드에 따른 처리
      if($resJSON->msgHeader->resultCode != "0")
      {
        $response->result   = FAIL;
        $response->message  = $resJSON->msgHeader->resultMessage;
        $this->util->makeResponse($response);
        return;
      }

      // 버스 모델 생성
      $busModel = $dbConnector->loadModel("BusModel");

      // 버스 리스트를 순회하며 노선번호 정보 추가
      $list = array();

      for($i=0; $i<sizeof($busList); $i++)
      {
        // 노선 ID를 통해 노선번호 조회
        $routeId = $busList[$i]->routeId; // 노선ID
        $routeNMData = $busModel->doSearchRouteNM($routeId);

        // 리스트에 노선번호 정보 추가
        $busList[$i]->routeNM = $routeNMData->routeNM;

        // 결과 리스트에 push
        array_push($list, $busList[$i]);
      }

      $response->result = SUCCESS;
      $response->message = MSG_RES_SUCCESS;
      $response->list = $list;
      $this->util->makeResponse($response);
    }
    catch(Exception $e)
    {
      $response->result = FAIL;
      $response->message = MSG_SERVER_ERR;
      $this->util->makeResponse($response);
    }
    finally
    {

    }
  }


  // 사용자 관리


  // 정보 수정
  // 현재 로그인 사용자 정보 조회
  public function doGetUserInfo()
  {
    // Response
    $response = new stdClass();

    // POST 요청 여부에 따른 처리
    if(($_SERVER["REQUEST_METHOD"] != "POST"))
    {
      $response->result = FAIL;
      $response->message = MSG_UNVALID_REQ;
      $this->util->makeResponse($response);
      return;
    }

    try
    {
      // DB커넥터 및 사용자모델 생성
      $dbConnector = new DBConnector();
      $userModel = $dbConnector->loadModel('UserModel');

      // 세션정보를 통해 사용자 ID 조회
      @session_start();
      $userInfo = $userModel->doGetUserInfo($_SESSION["id"]);

      $response->result = SUCCESS;
      $response->message = MSG_RES_SUCCESS;
      $response->list = $userInfo;
      $this->util->makeResponse($response);
    }
    catch(Exception $e)
    {
      $response->result = FAIL;
      $response->message = MSG_SERVER_ERR;
      $this->util->makeResponse($response);
    }
    finally
    {

    }
  }
  // 현재 로그인 사용자 정보 수정
  public function doUpdateProfile()
  {
    // Response
    $response = new stdClass();

    // POST 요청 여부에 따른 처리
    if(($_SERVER["REQUEST_METHOD"] != "POST"))
    {
      $response->result = FAIL;
      $response->message = MSG_UNVALID_REQ;
      $this->util->makeResponse($response);
      return;
    }

    // Request
    $pwd = $_POST["pwd"];
    $name = $_POST["name"];
    $address = $_POST["address"];
    $email = $_POST["email"];
    $phoneNo = $_POST["phoneNo"];

    try
    {
      // DB커넥터 및 사용자 모델 생성
      $dbConnector = new DBConnector();
      $userModel = $dbConnector->loadModel("UserModel");

      // 입력 비밀번호 값에 따른 처리
      @session_start();
      $id = $_SESSION["id"];
      $pwd = ($pwd == "") ? $_SESSION["pwd"] : $pwd;

      // 사용자 정보 수정
      $updateProfile = $userModel->updateUserInfo($id, $pwd, $name, $address, $email, $phoneNo);

      $response->result = SUCCESS;
      $response->message = MSG_RES_SUCCESS;
      $this->util->makeResponse($response);
    }
    catch(Exception $e)
    {
      $response->result = FAIL;
      $response->message = MSG_SERVER_ERR;
      $this->util->makeResponse($response);
    }
    finally
    {

    }


  }







  // 로그아웃
  public function doLogout()
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

    try
    {
      // 세션 종료
      @session_start();
      @session_destroy();

      $response->result   = SUCCESS;
      $response->message  = MSG_LOGOUT_SUCCESS;
      $this->util->makeResponse($response);
    }
    catch(Exception $e)
    {
      $response->result = FAIL;
      $response->message = MSG_SERVER_ERR;
      $this->util->makeResponse($response);
    }
    finally
    {

    }
  }




  // try
  // {
  //
  // }
  // catch(Exception $e)
  // {
  //   $result = FAIL;
  //   $message = MSG_SERVER_ERR;
  //   $list = null;
  //   $this->util->makeResponse($result, $message, $list);
  // }
  // finally
  // {
  //
  // }



}
