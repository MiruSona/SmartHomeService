<?php
include("application/service/mainservice.php");

class Main
{
  // Main 페이지
  public function index()
  {
    require 'webapp/view/template/header.php';
    require 'webapp/view/main/index.php';
    require 'webapp/view/template/footer.php';

    // 아이디 기억하기 여부에 따른 처리
    if(isset($_COOKIE[COOKIE_ID]))
    {
      $id = $_COOKIE[COOKIE_ID];
      echo "<script>".
              "$('#loginID').val(\"" . $id . "\");".      // 쿠키에 저장된 ID 입력
              "$('#rememberID').prop('checked', true);".  // 체크박스 on
            "</script>";
    }
  }

  // 로그인
  public function doLogin()
  {
    $service = new MainService();
    $service->doLogin();
  }

  // 계정 찾기
  public function doFindAccount()
  {
    $service = new MainService();
    $service->doFindAccount();
  }


}
