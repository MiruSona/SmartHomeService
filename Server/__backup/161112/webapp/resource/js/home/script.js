$(document).ready(function() {
  /* Sidebar */
  // 사이드바 보이기
  $("#btnOpenSidebar").bind('click', function(e) {
    e.preventDefault();
    $("#wrapper").toggleClass("toggled");
    $("#main-content-wrapper").css({
      'pointer-events': 'none',
      'opacity': 0.5
    });
  });

  // 사이드바 감추기
  $("#btnCloseSidebar").bind('click', function(e) {
    e.preventDefault();
    $("#wrapper").toggleClass("toggled");
    $("#main-content-wrapper").css({
      'pointer-events': 'all',
      'opacity': 1
    });
  });

  // 사이드바 메뉴
  $(".sidebar-nav > li").bind('click', function() {
    var menu = $(this).attr('menu');

    switch(menu) {
      // 집 관리
      case "homeManage":
      $("#btnHomeManage").click();

      if($(this).attr('dropdown') == "hide") {
        $("li[menu='bus']").css('border-top', '1px solid #757575');
        $(this).attr('dropdown', 'show');
        $(this).addClass('active');
        $(".sidebar-home-manage").slideDown();
      }
      else if($(this).attr('dropdown') == "show") {
        $(this).attr('dropdown', 'hide');
        $(this).removeClass('active');
        $(".sidebar-home-manage").slideUp(function(){
          $("li[menu='bus']").css('border-top', '');
        });
      }
      break;

      // 버스
      case "bus":
      $("#btnBusArrivalInfo").click();
      break;

      // 사용자 관리
      case "userManage":
      $("#btnUserManage").click();
      break;

      // 정보 수정
      case "updateProfile":
      $("#btnUpdateProfile").click();
      break;

      // 로그아웃
      case "logout":
      $("#modalLogout").modal('show');
      break;
    }
  });

  // 로그아웃
  $("#btnLogout").bind('click', function() {
    $.ajax({
      type    : "POST",
      url     : "/home/doLogout",
      success : function(response) {
        var resJSON = $.parseJSON(response);

        if(resJSON.result) {
          alert(resJSON.message);
          window.location.href = "/";
        } else {
          alert(resJSON.message);
        }
      }
    });
  });

  /* Option */
  $("#btnOption").bind('click', function() {
    alert("옵션 > 차후 추가개발");
  });


  /* Responsible Slider */
  var timer;
  $('.responsive-slider').responsiveSlider({
    autoplay: false,
    touch: true,
    onSlideChange: function() {
      var menu = this.slide;
      var contentName = $(".titlebar-nav .current-content-name");


      switch(menu) {
        // 집 관리
        case 1:
        contentName.text("집 관리");
        break;

        // 버스 도착정보
        case 2:
        contentName.text("버스 도착정보");
        getBusStationList(); // 위치 : home/busArrivalInfo.js
        break;

        // 사용자 관리
        case 3:
        contentName.text("사용자 관리");
        break;

        // 정보 수정
        case 4:
        contentName.text("정보 수정");
        getUserInfo(); // 위치 : home/userProfile.js
        break;
      }
    }
  });



  // Test Area
  // Touch enable/disable => 개발 시간 부족으로 차후 추가여부 결정
  // $("#btnTouchOn").bind('click', function() {
  //   flag = true;
  // });
  // $("#btnTouchOff").bind('click', function() {
  //   // console.log($('.responsive-slider'));
  //   // $('.responsive-slider').attr('data-touch', 'false');
  //   //
  //   // $('.responsive-slider')
  //   //
  //   // $element.find('[data-group="slides"] ul');
  //   //
  //   // this.$rel.removeClass('drag');
  //
  //   // $('.responsive-slider').draggable('disable');
  // });


});
