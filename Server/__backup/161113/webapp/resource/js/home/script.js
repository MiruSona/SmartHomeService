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
  $("#menuSidebar > li").bind('click', function() {

    var menu = $(this).attr('menu'); // 현재 선택한 메뉴

    // 해당 메뉴 활성화
    if(menu != "logout") {
      $("#menuSidebar > li").removeClass('active');
      $(this).addClass('active');
    }

    switch($(this).attr('menu')) {
      // 집 관리
      case "homeManage":
        $("#btnHomeManage").click();

        // 하위 메뉴 스타일 적용
        if($(this).attr('dropdown') == "hide") {
          $("li[menu='busArrivalInfo']").css('border-top', '1px solid #757575');
          $(this).attr('dropdown', 'show');
          $(".sidebar-home-manage").slideDown();
        }
        else if($(this).attr('dropdown') == "show") {
          $(this).attr('dropdown', 'hide');
          $(".sidebar-home-manage").slideUp(function(){
            $("li[menu='busArrivalInfo']").css('border-top', '');
          });
        }
        break;
      // 버스 도착정보
      case "busArrivalInfo":
        $("#btnBusArrivalInfo").click();
        break;
      // 날씨정보
      case "weatherInfo":
        $("#btnWeatherInfo").click();
        break;
      // 정보 수정
      case "updateProfile":
        $("#btnUpdateProfile").click();
        break;
      // 로그아웃
      case "logout":
        $(this).removeClass('active');
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
  $('#responsiveSlider').responsiveSlider({
    autoplay: false,
    touch: true,
    onSlideChange: function() {
      var menu = this.slide;
      var contentName = $("#titlebarWrapper span:nth-child(2)");
      $("#menuSidebar > li").removeClass('active');

      switch(menu) {
        // 집 관리
        case 1:
          contentName.text("집 관리");
          $("li[menu='homeManage']").addClass('active');
          getHardwareInfo();    // 위치 : home/homeManage.js
          break;

        // 버스 도착정보
        case 2:
          contentName.text("버스 도착정보");
          $("li[menu='busArrivalInfo']").addClass('active');
          getBusStationList();  // 위치 : home/busArrivalInfo.js
          break;

        // 날씨 정보
        case 3:
          contentName.text("날씨정보");
          $("li[menu='weatherInfo']").addClass('active');
          getWeatherInfo();     // 위치 : home/weatherInfo.js
          break;

        // 정보 수정
        case 4:
          contentName.text("정보 수정");
          $("li[menu='updateProfile']").addClass('active');
          getUserInfo();        // 위치 : home/userProfile.js
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
