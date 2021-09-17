$(function() {
  var form = $("#formRegister");
  var submitActor = null;
  var $submitActors = form.find("input[type=submit]");

  var isIDChecked = false;

  $("#id").focusout(function(){
    




    console.log("ID Focus out.");
  });






  form.submit(function(){
    if(submitActor === null) {
      submitActor = $submitActors[0];
    }

    return false;
  });

  $submitActors.click(function() {
    submitActor = this;

    var id        = $("#id").val();
    var pwd       = $("#pwd").val();
    var pwd_chk   = $("#pwd_chk").val();
    var name      = $("#name").val();
    var birth     = $("#birth").val();
    var phone_no  = $("#phone_no").val();

    // 아이디 중복확인
    if(submitActor.id == "btnIDValid") {
      // 아이디가 공백일 경우
      if(id == "") {
        alert("아이디를 입력해 주세요.");
        $("#id").focus();
      } else {
        $.ajax({
          type: 'POST',
          url: 'idCheck.php',
          timeout: 3000,
          data: {'id': id},
          dataType: 'json',
          success: function(res, status, xhr) {
            console.log(res);
          },
          error: function(xhr, status, error) {
            if(status==="timeout") {
              alert("요청시간이 초과되었습니다.");
            } else {
              alert("not ok " + error);
            }
          }
        });
      }
    }

    if(submitActor.id == "btnRegister") {
      console.log("등록");
    }

    if(submitActor.id == "btnCancel") {
      console.log("취소");
    }
  });



});
