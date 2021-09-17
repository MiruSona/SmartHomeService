$(document).ready(function() {

  /* 로그인 */
  // 엔터키 이벤트 부여
  $("section input").bind('keypress', function(e) {
    // 엔터키를 눌렀으면
    if(e.which == 13) {
      // 로그인 요청
      $("#btnLogin").click();
    }
  });

  // 로그인 요청
  $("#btnLogin").bind('click', function() {
    var id        = $("#loginID");
    var pwd       = $("#loginPWD");
    var remember  = $("input:checkbox[id='rememberID']").is(":checked");

    // ID를 입력하지 않았으면
    if(id.val() == "") {
      alert("아이디를 입력해주세요.");
      id.focus();
    }
    // 비밀번호를 입력하지 않았으면
    else if(pwd.val() == "") {
      alert("비밀번호를 입력해주세요.");
      pwd.focus();
    } else {
      // Request Data
      var data = {
        id        : id.val(),
        pwd       : pwd.val(),
        remember  : remember
      };

      $.ajax({
        type    : "POST",
        url     : "/main/doLogin",
        data    : data,
        success : function(response) {
          var resJSON = $.parseJSON(response);

          if(resJSON.result) {
            window.location.href = "/home";
          } else {
            alert(resJSON.message);
          }
        },
        error   : function(response) {
          alert("시스템 장애입니다.");
        }
      });
    }
  });

  /* 계정 초기화 */
  $("#btnReset").bind('click', function() {
    $.ajax({
      type : "POST",
      url : "/main/doResetAccount",
      success : function(response) {
        var resJSON = $.parseJSON(response);

        if(resJSON.result) {
          alert(resJSON.message);
          location.reload(); // 페이지 새로고침
        } else {
          alert(resJSON.message);
        }
      }
    });
  });
});
