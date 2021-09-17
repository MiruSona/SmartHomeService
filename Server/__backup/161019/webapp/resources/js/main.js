$(document).ready(function() {

  // Test DB
  $("#btnTestDB").bind('click', function() {
    console.log("test DB")
    window.location.href = "/testdb";
  });

  // Test Weather API
  $("#btnTestWeather").bind('click', function() {
    window.location.href = "/testweather";
  });

  // Test Bus API
  $("#btnTestBus").bind('click', function() {
    window.location.href = "/testbus";
  })


  // Login
  $("#btnLogin").bind('click', function() {

    var id  = $("#loginID");
    var pwd = $("#loginPWD");

    if(id.val() == 0) {
      alert("아이디를 입력하세요.");
      id.focus();
    } else if(pwd.val() == 0) {
      alert("비밀번호를 입력하세요.");
      pwd.focus();
    } else {
      var data = { id: id.val(), pwd: pwd.val() };

      $.ajax({
        type: "POST",
        url: "/main/doLogin",
        data: data,
        success: function(response) {
          var resJSON = $.parseJSON(response);
          if(resJSON.result) {
            alert(resJSON.message);
          } else {
            alert(resJSON.message);
          }
        }
      });
    }
    return false;
  });

  $("#loginPWD").keydown(function(key) {
    if(key.keyCode == 13) {
      $("#btnLogin").click();
    }
  });


  // Register
  $("#btnRegister").bind('click', function() {
    // alert("회원가입");

    console.log($("#modalRegister button:nth-child(1)").text())
    console.log($("#modalRegister button:nth-child(2)").text())

    return false;
  });






});
