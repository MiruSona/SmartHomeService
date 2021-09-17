$(document).ready(function() {
  // 수정하기 버튼 이벤트 연결
  $("#btnModifyProfile").bind('click', function() {
    alert("정보 수정이 가능해집니다.");

    // 정보입력 input 활성화
    $(".user-profile-wrapper input").removeAttr('disabled');
    $(".user-profile-wrapper input[name='profileID']").prop('disabled', true);

    // 버튼 체인지
    $("button[id='btnModifyProfile']").css('display', 'none');
    $("button[id='btnCancelProfile']").css('display', 'inline-block');
    $("button[id='btnChangeProfile']").css('display', 'inline-block');
  });

  // 취소 버튼 이벤트 연결
  $("#btnCancelProfile").bind('click', function() {
    alert("정보 수정이 불가능해집니다.");

    // 정보입력 input 비활성화
    $(".user-profile-wrapper input").prop('disabled', true);

    // 체크 텍스트 삭제
    $(".user-profile-wrapper p").text("");

    // 사용자 정보 조회
    getUserInfo();

    // 버튼 체인지
    $("button[id='btnModifyProfile']").css('display', 'inline-block');
    $("button[id='btnCancelProfile']").css('display', 'none');
    $("button[id='btnChangeProfile']").css('display', 'none');
  });

  // 수정완료 버튼 이벤트 연결
  $("#btnChangeProfile").bind('click', function() {
    var regEx;                                                  // 정규식

    // Responsible Slider 라이브러리 영향으로 중복생성되기 때문에 이렇게 따로 값을 빼와야 함
    var pwd         = $("input[name='profilePWD']:eq(1)");      // 비밀번호
    var txtPwd      = $("p[check='profilePWD']:eq(1)");         // 비밀번호 체크 텍스트
    var pwdCheck    = $("input[name='profilePWDCheck']:eq(1)"); // 비밀번호 체크
    var txtPwdCheck = $("p[check='profilePWDCheck']:eq(1)");    // 비밀번호 체크 체크 텍스트
    var name        = $("input[name='profileName']:eq(1)");     // 이름
    var txtName     = $("p[check='profileName']:eq(1)");        // 이름 체크 텍스트
    var address     = $("input[name='profileAddress']:eq(1)");  // 주소
    var txtAddress  = $("p[check='profileAddress']:eq(1)");     // 주소 체크 텍스트
    var email       = $("input[name='profileEmail']:eq(1)");    // 이메일
    var txtEmail    = $("p[check='profileEmail']:eq(1)");       // 이메일 체크 텍스트
    var phoneNo     = $("input[name='profilePhoneNo']:eq(1)");  // 전화번호
    var txtPhoneNo  = $("p[check='profilePhoneNo']:eq(1)");     // 전화번호 체크 텍스트

    // 비밀번호 체크
    if(pwd.val() != "") {
      if(pwd.val().length < 4 || pwd.val().length > 8) {
        pwd.val("");
        txtPwd.text("* 비밀번호는 4~8자리입니다.").addClass('text-danger');
      } else {
        txtPwd.text("");
      }
      if(pwd.val() != pwdCheck.val()) {
        pwdCheck.val("");
        txtPwdCheck.text("* 입력한 비밀번호를 다시 확인해주세요.").addClass('text-danger');
      } else {
        txtPwdCheck.text("");
      }
    }

    // 이름 체크
    if(name.val().length > 16) {
      name.val("");
      txtName.text("* 이름은 최대 16자까지 입력가능합니다.").addClass('text-danger');
    } else { // 이름 길이가 적절하면
      // 정규식 체크
      regEx = /^[가-힣]{2,16}|[A-z]{2,16}$/g;

      if(!regEx.test(name.val())) {
        name.val("");
        txtName.text("* 이름은 한글, 영문으로 2~16자입니다.").addClass('text-danger');
      } else {
        txtName.text("");
      }
    }

    // 주소 체크
    if(address.val() == "") {
      txtAddress.text("* 주소를 입력해주세요.").addClass('text-danger');
    } else {
      txtAddress.text("");
    }

    // 이메일 체크
    if(email.val()  == "") {
      txtEmail.text("* 이메일을 입력해주세요.").addClass('text-danger');
    } else {
      regEx = /([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;

      if(!regEx.test(email.val())) {
        email.val("");
        txtEmail.text("* 입력한 이메일이 유효하지 않습니다.").addClass('text-danger');
      } else {
        txtEmail.text("");
      }
    }
    // console.log(email.val());

    // 전화번호 체크
    if(phoneNo.val() == "") {
      txtPhoneNo.text("* 전화번호를 입력해주세요.").addClass('text-danger');
    } else {
      regEx = /^01+[0|1|6|7|8|9]+[0-9]{7,8}$/g;

      if(!regEx.test(phoneNo.val())) {
        phoneNo.val("");
        txtPhoneNo.text("* 전화번호는 10~11자리 숫자입니다.").addClass('text-danger');
      } else {
        txtPhoneNo.text("");
      }
    }

    // 수정 정보에 따른 처리
    if(txtPwd.text() != "" || txtPwdCheck.text() != "" || txtName.text() != "" ||
        txtAddress.text() != "" ||  txtEmail.text() != "" ||  txtPhoneNo.text() != "") {
      alert("입력값을 확인해주세요.");
    } else {
      // Request Data
      var data = {
        pwd     : pwd.val(),
        name    : name.val(),
        address : address.val(),
        email   : email.val(),
        phoneNo : phoneNo.val()
      };

      $.ajax({
        type    : "POST",
        url     : "/home/doUpdateProfile",
        data    : data,
        success : function(response) {
          var resJSON = $.parseJSON(response);

          if(resJSON.result) {
            alert(resJSON.message);

            // 정보입력 input 비활성화
            $(".user-profile-wrapper input").prop('disabled', true);
            // 체크 텍스트 삭제
            $(".user-profile-wrapper p").text("");

            // 사용자 정보 조회
            getUserInfo();
            getUserName();

            // 버튼 체인지
            $("button[id='btnModifyProfile']").css('display', 'inline-block');
            $("button[id='btnCancelProfile']").css('display', 'none');
            $("button[id='btnChangeProfile']").css('display', 'none');
          } else {
            alert(resJSON.message);
          }
        }
      });
    }
  });
});

// 현재 사용자 정보 조회
function getUserInfo() {
  $.ajax({
    type    : "POST",
    url     : "/home/doGetUserInfo",
    success : function(response) {
      var resJSON = $.parseJSON(response);

      if(resJSON.result) {
        // 현재 정보 입력
        $("input[name='profileID']").val(resJSON.info.id);
        $("input[name='profileName']").val(resJSON.info.name);
        $("input[name='profileAddress']").val(resJSON.info.address);
        $("input[name='profileEmail']").val(resJSON.info.email);
        $("input[name='profilePhoneNo']").val(resJSON.info.phone_no);
      } else {
        alert(resJSON.message);
      }
    }
  });
}
