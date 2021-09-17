<?php
include("application/service/homeservice.php");

class Home
{
  // Home 페이지
  public function index() {
    // 세션 시작
    @session_start();

    // 세션을 이용한 로그인 여부에 따른 처리
    if(!isset($_SESSION["id"]) || !isset($_SESSION["pwd"])) {
      echo "<script>alert(\"로그인이 필요한 페이지입니다.\")</script>";  // 경고창 출력
      echo "<meta http-equiv='refresh' content='0; url=/'>";          // 메인페이지로 이동
    } else {
      require 'webapp/view/template/header.php';
      require 'webapp/view/home/index.php';
      require 'webapp/view/template/footer.php';
    }
  }


  /* 사이드바 메뉴 */
  // 사용자 이름 조회
  public function doGetUserName() {
    $service = new HomeService();
    $service->doGetUserName();
  }
  // 로그아웃
  public function doLogout() {
    $service = new HomeService();
    $service->doLogout();
  }


  /* 집 관리 */
  // 센서 데이터 조회
  public function doGetSensorVal() {
    $service = new HomeService();
    $service->doGetSensorVal();
  }
  // 하드웨어 조회
  public function doGetHardwareInfo() {
    $service = new HomeService();
    $service->doGetHardwareInfo();
  }
  // 하드웨어 수정
  public function doUpdateHardware() {
    $service = new HomeService();
    $service->doUpdateHardware();
  }


  /* 버스 도착정보 */
  // 버스 정류소 조회
  public function doGetBusStnList() {
    $service = new HomeService();
    $service->doGetBusStnList();
  }
  // 해당 정류소 버스 조회
  public function doGetBusList() {
    $service = new HomeService();
    $service->doGetBusList();
  }


  /* 날씨정보 */
  // 날씨정보 조회
  public function doGetWeatherInfo() {
    $service = new HomeService();
    $service->doGetWeatherInfo();
  }


  /* 정보 수정 */
  // 현재 로그인 사용자 정보 조회
  public function doGetUserInfo() {
    $service = new HomeService();
    $service->doGetUserInfo();
  }
  // 현재 로그인 사용자 정보 수정
  public function doUpdateProfile() {
    $service = new HomeService();
    $service->doUpdateProfile();
  }



































  /* 정보수정 - 비밀번호 확인 */
  public function doCheckPWD()
  {
    // Request
    $pwd = $_POST["pwd"];

    // response
    $result;
    $message;
    $list;

    // 세션을 통해 현재 계정의 비밀번호 확인
    @session_start();
    // 비밀번호가 일치하지 않으면
    if(!($pwd == $_SESSION["pwd"]))
    {
      $result = "false";
      $message = "비밀번호를 다시 확인하십시오.";
      $this->makeResponse($result, $message, null);
    }
    else // 일치하면
    {
      // 해당 유저정보 조회
      // DB커넥터 및 사용자모델 생성
      $dbConnector = new DBConnector();
      $userModel = $dbConnector->loadModel('UserModel');
      // 해당 사용자 정보 조회
      $userInfo = $userModel->doGetUserInfo($_SESSION["id"]);

      $result = "true";
      $message = "정상적으로 처리되었습니다.";
      $list = $userInfo;
      $this->makeResponse($result, $message, $list);
    }
  }


  /* 버스 정류소 등록 */
  public function doRegisterBusStation()
  {
    // response
    $result   = "";
    $message  = "";

    // DB커넥터 및 사용자모델 생성
    $dbConnector = new DBConnector();
    $userModel = $dbConnector->loadModel('UserModel');

    // 세션 시작
    @session_start();

    // 로그인 id GET
    $id = $_SESSION["id"];

    // 주소 확인(현재 로그인 계정 사용)
    $addrResult = $userModel->doCheckAddr($id);

    // 등록된 주소가 없다면
    if($addrResult->address == "")
    {
      $result = "false";
      $message = "등록된 주소가 없습니다.";
      $this->makeResponse($result, $message, null);
    }
    else // 등록된 주소가 있다면
    {
      // 다음 API를 통해 해당 주소의 좌표정보 검색
      // Http Request
      $url = "https://apis.daum.net/local/geo/addr2coord?apikey=" . DAUM_MAP_KEY . "&q=" . urlencode($addrResult->address) . "&output=json";
      $response = file_get_contents($url);
      $resJSON = json_decode($response, true);

      $lat = $resJSON['channel']['item'][0]['lat'];
      $lng = $resJSON['channel']['item'][0]['lng'];

      // 공공데이터를 통해 버스정류소 조회
      $basedDataURL = "http://openapi.gbis.go.kr/ws/rest/baseinfoservice?serviceKey=" . GOV_DATA_KEY;
      $basedDataResponse = file_get_contents($basedDataURL);
      $basedDataXML = simplexml_load_string($basedDataResponse) or die("Error: Cannot create object");
      $busStationURL = $basedDataXML->children()->msgBody->baseInfoItem->stationDownloadUrl[0];

      // Initialize
  		$info   = parse_url($busStationURL);
  		$req    = '';
  		$data   = '';
  		$line   = '';
  		$agent  = 'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.0; Trident/5.0)';
  		$linebreak  = "\r\n";
  		$headPassed = false;

      // Setting Protocol
  		switch($info['scheme'] = strtoupper($info['scheme']))
  		{
          case 'HTTP':
              $info['port']   = 80;
              break;

          case 'HTTPS':
              $info['ssl']    = 'ssl://';
              $info['port']   = 443;
              break;

          default:
              return false;
  		}

  		// Setting Path
  		if(!$info['path'])
  		{
  			$info['path'] = '/';
  		}

      if($info['query'])
      {
          $info['path'] .= '?' . $info['query'];
      }

      $req .= 'GET ' . $info['path'] . ' HTTP/1.1' . $linebreak;
      $req .= 'Host: ' . $info['host'] . $linebreak;
      $req .= 'User-Agent: ' . $agent . $linebreak;
      $req .= 'Referer: ' . $busStationURL . $linebreak;
      $req .= 'Connection: Close' . $linebreak . $linebreak;

      $busStationData = "";

  		// Socket Open
  		$fsock  = @fsockopen($info['ssl'] . $info['host'], $info['port']);
  		if ($fsock)
  		{
  			fwrite($fsock, $req);
  			while(!feof($fsock))
  			{
  				$line = fgets($fsock, 128);
  				if($line == "\r\n" && !$headPassed)
  				{
  					$headPassed = true;
  					continue;
  				}
  				if($headPassed)
  				{
  					$busStationData .= $line;
  				}
  			}
          fclose($fsock);
  		}

      $busStationData = iconv("EUC-KR", "UTF-8", $busStationData); // UTF-8로 인코딩
      $busStationInfo = explode('^', $busStationData);
      $busStationResult = array();

      // 반경 1km 내의 정류소 탐색
      for($i=1; $i<count($busStationInfo); $i++)
      {
        $field = explode('|', $busStationInfo[$i]);

        $earth_radius = 6371;
        $dLat = deg2rad($lat - $field[5]);
        $dLon = deg2rad($lng - $field[4]);
        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($field[5])) * cos(deg2rad($lat)) * sin($dLon/2) * sin($dLon/2);
        $c = 2 * asin(sqrt($a));
        $d = $earth_radius * $c;

        if($d <= 0.5)
        {
          array_push($busStationResult, $busStationInfo[$i]);
        }
      }

      // DB에 정류소 정보 저장
      try
      {
        // 정류소 모델 생성
        $busStationModel = $dbConnector->loadModel('BusStationModel');

        // 현재 저장된 정류소 정보 삭제
        $busStationModel->doRemove();

        // 결과 리스트를 순회하며 정류소 정보 삽입
        foreach($busStationResult as $busStation)
        {
          // 데이터 파싱
          $busStationInfo = explode('|', $busStation);

          $stationID  = $busStationInfo[0]; // 정류소 ID
          $stationNM  = $busStationInfo[1]; // 정류소명
          $centerID   = $busStationInfo[2]; // 지자체ID
          $centerYN   = $busStationInfo[3]; // 중앙차로여부
          $x          = $busStationInfo[4]; // 위성 X좌표
          $y          = $busStationInfo[5]; // 위성 Y좌표
          $regionName = $busStationInfo[6]; // 지역명
          $mobileNO   = $busStationInfo[7]; // 정류소번호
          $districtDC = $busStationInfo[8]; // 지역코드

          $registerResult = $busStationModel->doRegister($stationID, $stationNM, $centerID, $centerYN,
                                                          $x, $y, $regionName, $mobileNO, $districtDC);
        }

        $result = "true";
        $message = "정상적으로 처리되었습니다.";
        $list = $busStationResult;
        $this->makeResponse($result, $message, null);
      }
      catch (Exception $e)
      {

      }
      finally
      {

      }
    }
  }

  /* 버스 등록 */
  public function doRegisterBus()
  {
    // response
    $result   = "";
    $message  = "";

    try
    {
      // DB 커넥터 생성, 버스 모델 생성
      $dbConnector = new DBConnector();
      $busModel = $dbConnector->loadModel("BusModel");

      // 현재 저장된 버스 정보 삭제
      $busModel->doRemove();

      // 공공데이터 기반정보를 통해 노선정보 url 조회
      $basedDataURL = "http://openapi.gbis.go.kr/ws/rest/baseinfoservice?serviceKey=" . GOV_DATA_KEY;
      $basedDataResponse = file_get_contents($basedDataURL);
      $basedDataXML = simplexml_load_string($basedDataResponse);
      $routeURL = $basedDataXML->children()->msgBody->baseInfoItem->routeDownloadUrl[0];

      // Http Request를 통해 노선정보 get
      $routeData = iconv("EUC-KR", "UTF-8", file_get_contents($routeURL)); // UTF-8로 인코딩

      // 데이터를 각 버스정보별로 구분
      $data = explode("^", $routeData);

      // 버스 정보 리스트 순회
      for($i=1; $i<sizeof($data); $i++)
      {
        // 각 버스의 속성별로 구분
        $routeInfo = explode("|", $data[$i]);

        // 데이터 파싱
        $routeID        = $routeInfo[0];  // 노선 ID
        $routeNM        = $routeInfo[1];  // 노선명
        $routeTP        = $routeInfo[2];  // 노선타입
        $stStaID        = $routeInfo[3];  // 기점 정류소 ID
        $stStaNM        = $routeInfo[4];  // 기점 정류소명
        $stStaNO        = $routeInfo[5];  // 기점 정류소 번호(모바일번호)
        $edStaID        = $routeInfo[6];  // 종점 정류소 ID
        $edStaNM        = $routeInfo[7];  // 종점 정류소명
        $edStaNO        = $routeInfo[8];  // 종점 정류소 번호(모바일번호)
        $upFirstTime    = $routeInfo[9];  // 상행 첫차 시간
        $upLastTime     = $routeInfo[10]; // 상행 막차 시간
        $downFirstTime  = $routeInfo[11]; // 하행 첫차 시간
        $downLastTime   = $routeInfo[12]; // 하행 막차 시간
        $peekAlloc      = $routeInfo[13]; // 첨두(출,퇴근) 시 배차간격(분)
        $nPeekAlloc     = $routeInfo[14]; // 비첨두 시 배차간격(분)
        $companyID      = $routeInfo[15]; // 운수사 ID
        $companyNM      = $routeInfo[16]; // 운수사 명
        $telNO          = $routeInfo[17]; // 전화번호
        $regionName     = $routeInfo[18]; // 운행지역
        $districtCD     = $routeInfo[19]; // 지역코드

        // 버스 등록
        $busModel->doRegister($routeID, $routeNM, $routeTP, $stStaID, $stStaNM,
                              $stStaNO, $edStaID, $edStaNM, $edStaNO, $upFirstTime,
                              $upLastTime, $downFirstTime, $downLastTime, $peekAlloc,
                              $nPeekAlloc, $companyID, $companyNM, $telNO, $regionName, $districtCD);
      }

      $result = "true";
      $message = "정상적으로 처리되었습니다.";
      $list = null;
      $this->makeResponse($result, $message, $list);
    }
    catch (Exception $e)
    {

    }
    finally
    {

    }
  }

  /* DB에 저장된 버스정류소 리스트 조회 */
  public function doGetBusStationList()
  {
    // DB 커넥터 및 정류소 모델 생성
    $dbConnector = new DBConnector();
    $busStationModel = $dbConnector->loadModel('BusStationModel');
    $busStationResult = $busStationModel->doSearchStationInfo();

    try
    {
      // 등록된 정류소가 없으면
      if($busStationResult == null)
      {
        $result = "false";
        $message = "등록된 정류소가 없습니다.";
        $list = null;
        $this->makeResponse($result, $message, $list);
      }
      else // 등록된 정류소가 있으면
      {
        $result = "true";
        $message = "정상적으로 처리되었습니다.";
        $list = $busStationResult;
        $this->makeResponse($result, $message, $list);
      }
    }
    catch (Exception $e)
    {

    }
    finally
    {

    }
  }

  /* 버스 도착 정보 조회 */
  public function doGetBusArrivalInfo()
  {
    // Request
    $id       = $_POST["id"];

    // Response
    $result   = "";
    $message  = "";
    $list     = array();

    try
    {
      // DB커넥터 및 정류소 모델 생성
      $dbConnector = new DBConnector();
      $busStationModel = $dbConnector->loadModel("BusStationModel");

      // id로 정류소 조회
      $busStationResult = $busStationModel->doSearchStationInfoById($id);

      // 해당 정류소가 없을 경우
      if($busStationResult->total == 0)
      {
        $result   = "false";
        $message  = "해당 정류소 정보가 존재하지 않습니다.";
        $list     = null;
        $this->makeResponse($result, $message, $list);
      }
      else // 해당 정류소가 있을 경우
      {
        // 공공데이터를 통해 버스 도착정보 조회
        // HTTP Request
        $url = "http://openapi.gbis.go.kr/ws/rest/busarrivalservice/station?serviceKey=" . GOV_DATA_KEY . "&stationId=" . $id;
        $response = file_get_contents($url);
        $resJSON = simplexml_load_string($response);
        $busList = $resJSON->msgBody->busArrivalList;

        // 공공데이터 반환간 에러 발생시
        if($resJSON->msgHeader->resultCode != "0")
        {
          $result   = "false";
          $message  = "해당 정류소 정보가 존재하지 않습니다.";
          $list     = $resJSON->msgHeader->resultMessage;
          $this->makeResponse($result, $message, $list);
        }
        else // 공공데이터 반환 성공시
        {
          // 버스 모델 생성
          $busModel = $dbConnector->loadModel("BusModel");

          // 도착 버스 리스트 순회
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

          $result = "true";
          $message = "정상적으로 처리되었습니다.";
          $list = $list;
          $this->makeResponse($result, $message, $list);
        }
      }
    }
    catch (Exception $e)
    {

    }
    finally
    {

    }
  }

  /* 결과값 반환 */
  public function makeResponse($result, $message, $list)
  {
    if($list == null)
    {
      echo "{ \"result\" : " . $result . ", \"message\" : \"" . $message . "\" }";
    }
    else
    {
      if(sizeof($list) == 1)
      {
        echo "{ \"result\" : " . $result . ", \"message\" : \"" . $message . "\", \"info\" : " . json_encode($list) . " }";
      }
      else
      {
        echo "{ \"result\" : " . $result . ", \"message\" : \"" . $message . "\", \"list\" : " . json_encode($list) . " }";
      }
    }
  }
}
