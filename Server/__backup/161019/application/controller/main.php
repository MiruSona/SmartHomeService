<?php
class Main
{
  // Main index Page
  public function index()
  {
    require 'webapp/views/_templates/header.php';
    require 'webapp/views/main/index.php';
    require 'webapp/views/_templates/footer.php';
  }

  // Login
  public function doLogin()
  {
    // Request Params
    $id = "";
    $pwd = "";

    // Response Params
    $result = "";
    $message = "";

    try
    {
      if($_SERVER["REQUEST_METHOD"] == "POST")
      {
        // id, pwd get (method : POST)
        $id = $_POST["id"];
        $pwd = $_POST["pwd"];

        // create new DB controller
        $dbController = new DBController();
        $loginModel = $dbController->loadModel('UserModel');
        $loginResult = $loginModel->getLoginInfo($id, $pwd);

        // is UnValid user
        if($loginResult == null)
        {
          $result = "false";
          $message = "사용자 정보를 잘못 입력하셨습니다.";

          $this->makeResponse($result, $message);
        }
        else
        {
          $result = "true";
          $message = "정상적으로 로그인 되었습니다.";

          $this->makeResponse($result, $message);
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

  // Register
  public function doRegister()
  {

  }



  // make response
  public function makeResponse($result, $message)
  {
    $response = "{ \"result\" : " . $result . ", \"message\" : \"" . $message . "\" }";
    echo $response;
  }


  // public function login()
  // {
  //   if($_SERVER["REQUEST_METHOD"] == "POST")
  //   {
  //     if(isset($_POST["btnLogin"]))
  //     {
  //       $this->doLogin();
  //     }
  //
  //     if(isset($_POST["btnRegister"]))
  //     {
  //       header('Location: /main/register');
  //     }
  //   }
  //
  //   require 'application/views/_templates/empty_header.php';
  //   require 'application/views/main/login.php';
  //   require 'application/views/_templates/empty_footer.php';
  // }
  //
  // public function register()
  // {
  //   $idErr = "";
  //   $pwdErr = "";
  //   $pwdChkErr = "";
  //
  //   if($_SERVER["REQUEST_METHOD"] == "POST")
  //   {
  //     $id = $_POST[id];
  //     echo "<script>
  //             alert(\"$id\"));
  //           </script>";
  //   }
  //
  //   // if($_SERVER["REQUEST_METHOD"] == "POST")
  //   // {
  //   //   // if(isset($_POST["btnRegister"]))
  //   //   // {
  //   //   //   return;
  //   //   //   $this->doRegister();
  //   //   // }
  //   //
  //   //   if(isset($_POST["btnCancel"]))
  //   //   {
  //   //     header('Location: /main/login');
  //   //   }
  //   // }
  //   require 'application/views/_templates/empty_header.php';
  //   require 'application/views/main/register.php';
  //   require 'application/views/_templates/empty_footer.php';
  // }
}
