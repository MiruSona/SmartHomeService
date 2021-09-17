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
            alert(resJSON.message);
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

  /* 계정 찾기 */
  // 전화번호 체크
  $("#findPhoneNo").bind('focusout', function() {
    var phoneNo         = $("#findPhoneNo");
    var txtChkPhoneNo  = $("#txtChkPhoneNo");

    // 전화번호를 입력하지 않았으면
    if(phoneNo.val() == "") {
      txtChkPhoneNo.text("* 전화번호를 입력해주세요.").css('color', 'red');
    } else {
      // 정규식 체크
      var regEx = /^01+[0|1|6|7|8|9]+[0-9]{7,8}$/g;

      if(!regEx.test(phoneNo.val())) {
        phoneNo.val("");
        txtChkPhoneNo.text("* 전화번호는 10~11자리 숫자입니다.").css('color', 'red');
      } else {
        txtChkPhoneNo.text("");
      }
    }
  });

  // 계정찾기 요청
  $("#btnFind").bind('click', function() {
    var phoneNo = $("#findPhoneNo");

    // 전화번호 값을 입력하지 않았으면
    if(phoneNo.val() == "") {
      alert("입력하지 않은 값이 있습니다.");
    } else { // 값을 전부 입력했으면,
      var data = { phoneNo : phoneNo.val() };

      $.ajax({
        type : "POST",
        url : "/main/doFindAccount",
        data : data,
        success : function(response) {
          console.log(response);
        }
      });
    }

  });

});
