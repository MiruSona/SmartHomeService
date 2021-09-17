<?php
include("application/util/codeex.php");
include("application/util/util.php");

class NodeREDService
{
  public $util;

  // 생성자
  function __construct()
  {
    $this->util = new Util();
  }

  // 버스리스트 업데이트
  public function doUpdateBusDB()
  {
    // Response
    $response = new stdClass();

    if(($_SERVER["REQUEST_METHOD"] != "POST"))
    {
      $response->result = FAIL;
      $response->message = MSG_UNVALID_REQ;
      $this->util->makeResponse($response);
    }
    else
    {
      try
      {
        // DB 커넥터 생성, 버스 모델 생성
        $dbConnector = new DBConnector();
        $busModel = $dbConnector->loadModel("BusModel");

        // 현재 저장된 버스 정보 삭제
        $busModel->doRemove();

        // 공공데이터 기반정보를 통해 노선정보 url 조회
        $basedDataURL = "http://openapi.gbis.go.kr/ws/rest/baseinfoservice?".
                          "serviceKey=".GOV_DATA_KEY;
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

        $result = SUCCESS;
        $message = MSG_RES_SUCCESS;
        $this->util->makeResponse($result, $message, null);
      }
      catch (Exception $e)
      {
        $result = FAIL;
        $message = $e->getMessage();
        $this->util->makeResponse($result, $message, null);
      }
      finally
      {

      }
    }
  }

  // 버스정류소 업데이트
  public function doUpdateBusStnDB()
  {

  }
}
