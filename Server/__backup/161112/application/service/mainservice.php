<?php
include("application/util/codeex.php");
include("application/util/util.php");

class MainService
{
  public $util;

  // 생성자
  function __construct()
  {
    $this->util = new Util();
  }

  // 로그인
  public function doLogin()
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
    $id       = $_POST["id"];
    $pwd      = $_POST["pwd"];
    $remember = $_POST["remember"];

    try
    {
      // DB커넥터 및 사용자모델 생성
      $dbConnector = new DBConnector();
      $userModel = $dbConnector->loadModel('UserModel');

      // ID 확인
      $idCheck = $userModel->doCheckID($id);

      // 해당 id의 사용자 존재 여부에 따른 처리
      if($idCheck->total == 0)
      {
        $response->result   = FAIL;
        $response->message  = MSG_NOT_EXIST_USER;
        $this->makeResponse($response);
        return;
      }

      // PWD 확인
      $pwdCheck = $userModel->doCheckPWD($id, $pwd);

      // 입력 비밀번호 일치여부에 따른 처리
      if($pwdCheck->total == 0)
      {
        $response->result   = FAIL;
        $response->message  = MSG_NOT_MATCH_PWD;
        $this->util->makeResponse($response);
        return;
      }

      // 세션에 로그인 정보 저장
      @session_start();
      $_SESSION["id"]   = $id;
      $_SESSION["pwd"]  = $pwd;

      // 아이디 기억하기 체크여부에 따른 처리
      if($remember == "true")
      {
        // 쿠키에 아이디 저장
        setcookie(COOKIE_ID, $id, time() + COOKIE_LIMIT, "/");
      }
      else
      {
        // 쿠키정보 삭제
        setcookie(COOKIE_ID, "", time() - COOKIE_LIMIT, "/");
      }

      $response->result   = SUCCESS;
      $response->message  = MSG_RES_SUCCESS;
      $this->util->makeResponse($response);
    }
    catch (Exception $e)
    {
      $response->result   = FAIL;
      $response->message  = MSG_SERVER_ERR;
      $this->util->makeResponse($response);
    }
    finally
    {

    }
  }

  // 계정 찾기
  public function doFindAccount()
  {
    // response
    $result   = "";
    $message  = "";

    // POST 요청 여부에 따른 처리
    if(($_SERVER["REQUEST_METHOD"] != "POST"))
    {
      $result = FAIL;
      $message = MSG_UNVALID_REQ;
      $this->util->makeResponse($result, $message, null);
    }
    else
    {
      // request
      $phoneNo = $_POST["phoneNo"];
      echo "차후 추가";
    }
  }



}
