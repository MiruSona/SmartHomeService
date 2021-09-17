<?php
include("application/util/codeex.php");
include("application/util/util.php");
include("application/api/WeatherAPI.php");

class HomeService {
  public $util;

  // 생성자
  function __construct() {
    $this->util = new Util();
  }

  /* 사이드바 메뉴 */
  // 사용자 이름 조회
  public function doGetUserName() {
    // Response
    $response = new stdClass();

    // POST 요청 여부에 따른 처리
    if(!($this->util->isPostRequest())) {
      $response->result   = FAIL;
      $response->message  = MSG_UNVALID_REQ;
      $this->util->makeResponse($response);
      return;
    }

    try {
      // DB커넥터 및 사용자 모델 생성
      $dbConnector    = new DBConnector();
      $userModel  = $dbConnector->loadModel('UserModel');

      // 현재 로그인 사용자 정보 조회
      @session_start();
      $userInfo = $userModel->getUserInfo($_SESSION["id"]);

      $response->result   = SUCCESS;
      $response->message  = MSG_RES_SUCCESS;
      $response->list     = $userInfo;
      $this->util->makeResponse($response);

    } catch(Exception $e) {
      $response->result = FAIL;
      $response->message = MSG_SERVER_ERR;
      $this->util->makeResponse($response);
    } finally {

    }
  }

  // 로그아웃
  public function doLogout() {
    // Response
    $response = new stdClass();

    // POST 요청 여부에 따른 처리
    if(!($this->util->isPostRequest())) {
      $response->result   = FAIL;
      $response->message  = MSG_UNVALID_REQ;
      $this->util->makeResponse($response);
      return;
    }

    try {
      // 세션 종료
      @session_start();
      @session_destroy();

      $response->result   = SUCCESS;
      $response->message  = MSG_LOGOUT_SUCCESS;
      $this->util->makeResponse($response);
    } catch(Exception $e) {
      $response->result = FAIL;
      $response->message = MSG_SERVER_ERR;
      $this->util->makeResponse($response);
    } finally {

    }
  }

  /* 집 관리 */
  // 센서 데이터 조회
  public function doGetSensorVal() {
    // Response
    $response = new stdClass();

    // POST 요청 여부에 따른 처리
    if(!($this->util->isPostRequest())) {
      $response->result   = FAIL;
      $response->message  = MSG_UNVALID_REQ;
      $this->util->makeResponse($response);
      return;
    }

    try {
      // DB커넥터 및 실시간 데이터 모델 생성
      $dbConnector    = new DBConnector();
      $realtimeModel  = $dbConnector->loadModel('RealtimeDataModel');
      $sensorData     = $realtimeModel->getSensorData();

      // 센서 데이터 존재 여부에 따른 처리
      if($sensorData == null) {
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
    } catch(Exception $e) {
      $response->result = FAIL;
      $response->message = MSG_SERVER_ERR;
      $this->util->makeResponse($response);
    } finally {

    }
  }

  // 하드웨어 조회
  public function doGetHardwareInfo() {
    // Response
    $response = new stdClass();

    // POST 요청 여부에 따른 처리
    if(!($this->util->isPostRequest())) {
      $response->result   = FAIL;
      $response->message  = MSG_UNVALID_REQ;
      $this->util->makeResponse($response);
      return;
    }

    try {
      // DB커넥터 및 하드웨어 관련 모델 생성
      $dbConnector = new DBConnector();
      $piModel = $dbConnector->loadModel('PiModel');
      $arduinoModel = $dbConnector->loadModel('ArduinoModel');
      $sensorModel = $dbConnector->loadModel('SensorModel');

      // 파이 정보 조회
      $piInfo = $piModel->getPiInfo();

      // 파이 정보 존재 여부에 따른 처리
      if($piInfo == null) {
        $response->result   = FAIL;
        $response->message  = MSG_NOT_EXIST_PI_INFO;
        $this->util->makeResponse($response);
        return;
      }
      // 아두이노 정보 조회
      $arduinoInfo = $arduinoModel->getArduinoInfo();

      // 아두이노 정보 존재 여부에 따른 처리
      if($arduinoInfo == null) {
        $response->result   = FAIL;
        $response->message  = MSG_NOT_EXIST_ARDUINO_INFO;
        $this->util->makeResponse($response);
        return;
      }

      // 센서 정보 조회
      $sensorInfo = $sensorModel->getSensorInfo();

      // 센서 정보 존재 여부에 따른 처리
      if($sensorInfo == null) {
        $response->result   = FAIL;
        $response->message  = MSG_NOT_EXIST_SENSOR_INFO;
        $this->util->makeResponse($response);
        return;
      }

      $response->result       = SUCCESS;
      $response->message      = MSG_RES_SUCCESS;
      $response->piInfo       = $piInfo;
      $response->arduinoInfo  = $arduinoInfo;
      $response->sensorInfo   = $sensorInfo;
      $this->util->makeResponse($response);
    } catch (Exception $e) {

    } finally {

    }
  }

  // 하드웨어 수정
  public function doUpdateHardware() {
    // Response
    $response = new stdClass();

    // POST 요청 여부에 따른 처리
    if(!($this->util->isPostRequest())) {
      $response->result   = FAIL;
      $response->message  = MSG_UNVALID_REQ;
      $this->util->makeResponse($response);
      return;
    }

    // Response
    $address  = $_POST["address"];
    $radius   = $_POST["radius"];

    try {
      // 다음 API를 통해 해당 주소의 좌표정보 검색
      $url = "https://apis.daum.net/local/geo/addr2coord?".
              "apikey=".DAUM_MAP_KEY.
              "&q=".urlencode($address).
              "&output=json";
      $locationInfo = file_get_contents($url);
      $locationJSON = json_decode($locationInfo, true);

      $x = $locationJSON['channel']['item'][0]['point_x'];
      $y = $locationJSON['channel']['item'][0]['point_y'];

      // shell 명령어를 통해 pi 시리얼번호 조회
      $output = shell_exec("cat /proc/cpuinfo");
      $find   = "Serial";
      $pos    = strpos($output, $find);
      $serial = substr($output, $pos+10, 17);

      // DB커넥터 및 파이모델 생성
      $dbConnector = new DBConnector();
      $piModel = $dbConnector->loadModel('PiModel');

      // 라즈베리파이 정보 수정
      $piModel->updatePiInfo($serial, $x, $y, $radius);

      // 자주가는 버스정류소 모델 생성
      $busStnModel = $dbConnector->loadModel('BusStationModel');

      // 버스 정류소 조회
      $obj = new stdClass();
      $obj->x       = $x;
      $obj->y       = $y;
      $obj->radius  = $radius;
      $busStnList = $this->util->getBusStnList($obj);

      // 자주가는 버스정류소 갱신
      $busStnModel->remove();

      for($i=0; $i<sizeof($busStnList); $i++) {
        $busStnModel->register($busStnList[$i]->STATION_ID,
                               $busStnList[$i]->STATION_NM,
                               $busStnList[$i]->CENTER_ID,
                               $busStnList[$i]->CENTER_YN,
                               $busStnList[$i]->X,
                               $busStnList[$i]->Y,
                               $busStnList[$i]->REGION_NAME,
                               $busStnList[$i]->MOBILE_NO,
                               $busStnList[$i]->DISTRICT_CD,
                               $serial);
      }

      $response->result   = SUCCESS;
      $response->message  = MSG_RES_SUCCESS;
      $this->util->makeResponse($response);
    } catch(Exception $e) {
      $response->result   = FAIL;
      $response->message  = MSG_NOT_EXIST_SENSOR_INFO;
      $this->util->makeResponse($response);
      return;
    } finally {

    }
  }


  /* 버스 도착정보 */
  // 버스 정류소 조회
  public function doGetBusStnList() {
    // Response
    $response = new stdClass();

    // POST 요청 여부에 따른 처리
    if(!($this->util->isPostRequest())) {
      $response->result   = FAIL;
      $response->message  = MSG_UNVALID_REQ;
      $this->util->makeResponse($response);
      return;
    }

    try {
      // DB커넥터 및 버스정류소모델, 파이모델 생성
      $dbConnector  = new DBConnector();
      $busStnModel  = $dbConnector->loadModel('BusStationModel');
      $piModel      = $dbConnector->loadModel('PiModel');

      // 반경값 조회
      $radius = $piModel->getRadius();
      $radius = $radius->radius;

      // 반경값 존재 여부에 따른 처리
      if($radius == 0) {
        $response->result   = FAIL;
        $response->message  = MSG_NOT_EXIST_RADIUS;
        $this->util->makeResponse($response);
        return;
      }

      // 등록된 정류소 조회
      $busStnList = $busStnModel->getBusStnInfo();

      // 등록된 정류소 존재여부에 따른 처리
      if($busStnList == null) {
        // 좌표 조회
        $coord  = $piModel->getCoord();
        $x      = $coord->x;
        $y      = $coord->y;

        // 좌표값 존재 여부에 따른 처리
        if(($x == "") && ($y == "")) {
          $response->result   = FAIL;
          $response->message  = MSG_NOT_EXIST_COORD;
          $this->util->makeResponse($response);
          return;
        }

        // 반경 내에 존재하는 버스 정류소 List 가져오기
        $obj = new stdClass();
        $obj->x       = $x;
        $obj->y       = $y;
        $obj->radius  = $radius;
        $busStnList   = $this->util->getBusStnList($obj);

        // list 조회 성공 여부에 따른 처리
        if($busStnList == null) {
          $response->result   = FAIL;
          $response->message  = MSG_NOT_EXIST_BUS_STN;
          $this->util->makeResponse($response);
          return;
        }
      }

      $response->result   = SUCCESS;
      $response->message  = MSG_RES_SUCCESS;
      $response->radius   = $radius;
      $response->list     = $busStnList;
      $this->util->makeResponse($response);
    } catch(Exception $e) {
      $response->result = FAIL;
      $response->message = MSG_SERVER_ERR;
      $this->util->makeResponse($response);
    }
  }

  // 해당 정류소 버스 조회
  public function doGetBusList() {
    // Response
    $response = new stdClass();

    // POST 요청 여부에 따른 처리
    if(!($this->util->isPostRequest())) {
      $response->result   = FAIL;
      $response->message  = MSG_UNVALID_REQ;
      $this->util->makeResponse($response);
      return;
    }

    // Request
    $stationId = $_POST["stationId"];

    try {
      // 해당 정류소에 도착하는 버스 List 가져오기
      $busList = $this->util->getBusList($stationId);

      // list 조회 성공 여부에 따른 처리
      if($busList == null) {
        $response->result   = FAIL;
        $response->message  = MSG_NOT_EXIST_BUS;
        $this->util->makeResponse($response);
        return;
      }

      $response->result = SUCCESS;
      $response->message = MSG_RES_SUCCESS;
      $response->list = $busList;
      $this->util->makeResponse($response);
    } catch(Exception $e) {
      $response->result = FAIL;
      $response->message = MSG_SERVER_ERR;
      $this->util->makeResponse($response);
    } finally {

    }
  }


  /* 날씨정보 */
  // 날씨정보 조회
  public function doGetWeatherInfo() {
    // Response
    $response = new stdClass();

    // POST 요청 여부에 따른 처리
    if(!($this->util->isPostRequest())) {
      $response->result   = FAIL;
      $response->message  = MSG_UNVALID_REQ;
      $this->util->makeResponse($response);
      return;
    }

    try {
      // DB커넥터 및 파이 모델 생성
      $dbConnector = new DBConnector();
      $piModel = $dbConnector->loadModel("PiModel");

      // 좌표 조회
      $coord  = $piModel->getCoord();
      $x      = $coord->x;
      $y      = $coord->y;

      // 날씨 API를 통해 날씨정보 조회
      $weatherAPI   = new WeatherAPI();
      $weatherInfo  = $weatherAPI->parserAreaWeatherXMLxml($x, $y);

      // 날씨정보 조회 성공 여부에 따른 처리
      if($weatherInfo == null) {
        $response->result   = FAIL;
        $response->message  = MSG_NOT_EXIST_WEATHER_INFO;
        $this->util->makeResponse($response);
        return;
      }

      $response->result   = SUCCESS;
      $response->message  = MSG_RES_SUCCESS;
      $response->list     = $weatherInfo;
      $this->util->makeResponse($response);
    } catch(Exception $e) {
      $response->result = FAIL;
      $response->message = MSG_SERVER_ERR;
      $this->util->makeResponse($response);
    } finally {

    }
  }


  /* 정보 수정 */
  // 현재 로그인 사용자 정보 조회
  public function doGetUserInfo() {
    // Response
    $response = new stdClass();

    // POST 요청 여부에 따른 처리
    if(!($this->util->isPostRequest())) {
      $response->result   = FAIL;
      $response->message  = MSG_UNVALID_REQ;
      $this->util->makeResponse($response);
      return;
    }

    try {
      // DB커넥터 및 사용자모델 생성
      $dbConnector = new DBConnector();
      $userModel = $dbConnector->loadModel('UserModel');

      // 세션정보를 통해 사용자 ID 조회
      @session_start();
      $userInfo = $userModel->getUserInfo($_SESSION["id"]);

      $response->result = SUCCESS;
      $response->message = MSG_RES_SUCCESS;
      $response->list = $userInfo;
      $this->util->makeResponse($response);
    } catch(Exception $e) {
      $response->result = FAIL;
      $response->message = MSG_SERVER_ERR;
      $this->util->makeResponse($response);
    } finally {

    }
  }
  // 현재 로그인 사용자 정보 수정
  public function doUpdateProfile() {
    // Response
    $response = new stdClass();

    // POST 요청 여부에 따른 처리
    if(($_SERVER["REQUEST_METHOD"] != "POST")) {
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

    try {
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
    } catch(Exception $e) {
      $response->result = FAIL;
      $response->message = MSG_SERVER_ERR;
      $this->util->makeResponse($response);
    } finally {

    }
  }













}
