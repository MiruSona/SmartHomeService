$(document).ready(function() {
  // 버스 정류소 리스트 이벤트 연결
  $(".bus-info-wrapper").delegate('.bus-info-header', 'click', function() {

    var showList = $(this).attr('showList');  // 버스 정류소 표시 여부 속성
    var glyphicon = $(this).find('span');     // 버스 정류소 glyphicon(up/down)

    // showList(Y/N) 값에 따른 처리
    switch(showList) {
      case "Y":
        $(this).attr('showList', 'N');
        glyphicon.attr('class', 'glyphicon glyphicon-chevron-down');
        $(this).next().remove(); // 표시했던 버스 리스트 영역 삭제
        break;
      case "N":
        $(this).attr('showList', 'Y');
        glyphicon.attr('class', 'glyphicon glyphicon-chevron-up');
        getBusList($(this)); // 버스 리스트 조회
        break;
    }
  });
});

// 버스 정류소 리스트 조회
function getBusStationList() {
  $(".bus-info-wrapper").empty(); // 버스 정류소 리스트 초기화

  $.ajax({
    type    : "POST",
    url     : "/home/doGetBusStnList",
    success : function(response) {
      var resJSON = $.parseJSON(response);

      if(resJSON.result) {
        var busStnList = resJSON.list; // 버스 정류소 리스트

        // 조회한 버스 정류소 리스트만큼 조회
        $.each(busStnList, function(i, station) {
          var stationInfo = station.split('|');
          var stnID = stationInfo[0];       // 정류소 ID
          var stnName = stationInfo[1];     // 정류소명
          var stnMobileNo = stationInfo[7]; // 정류소 모바일 번호

          var text; // 생성할 HTML 텍스트

          text  = "<div class='row bus-info-header' stnid='" + stnID + "' showList='N' style='border: 1px solid #212121;'>";
          text +=   "<p>" + stnName + "(" + stnMobileNo + ")" + "</p>";
          text +=   "<span class='glyphicon glyphicon-chevron-down'></span>";
          text += "</div>";

          // 버스 정류소 리스트 렌더링
          $(".bus-arrival-info .container").append(text);
        });
      } else {
        alert(resJSON.messagE);
      }
    }
  });
}

// 해당 정류소 버스 리스트 조회
function getBusList(station) {
  var stationId = station.attr('stnid');      // 요청 정류소 ID
  var data      = { stationId : stationId };  // 요청 데이터

  $.ajax({
    type    : "POST",
    url     : "/home/doGetBusList",
    data    : data,
    success : function(response) {
      var resJSON = $.parseJSON(response);

      if(resJSON.result) {
        var busList = resJSON.list; // 버스 리스트

        var text;                   // 생성할 HTML 텍스트

        text  = "<div class='row bus-info-container'>";
        text +=   "<table class='table table-bordered table-hover'>";
        text +=     "<thead>";
        text +=       "<tr>";
        text +=         "<td>#</td>";
        text +=         "<td>노선번호</td>";
        text +=         "<td>도착시간</td>";
        text +=         "<td>종류</td>";
        text +=         "<td>남은좌석</td>";
        text +=       "</tr>";
        text +=     "</thead>";
        text +=     "<tbody>";

        // 조회한 버스 리스트만큼 조회
        $.each(busList, function(i, bus) {
          var routeNM       = bus.routeNM;                                      // 노선번호
          var predictTime   = bus.predictTime1;                                 // 예상 도착시간(분)
          var lowPlate      = (bus.lowPlate1==0 ? "일반" : "저상");              // 일반/저상 여부
          var remainSeatCnt = (bus.remainSeatCnt1 ? "-" : bus.remainSeatCnt1);  // 남은좌석

          text +=     "<tr>";
          text +=       "<th scope='row'>" + (i+1) + "</th>";
          text +=       "<th scope='row'>" + routeNM + "</th>";
          text +=       "<th scope='row'>" + predictTime + "분</th>";
          text +=       "<th scope='row'>" + lowPlate + "</th>";
          text +=       "<th scope='row'>" + remainSeatCnt + "</th>";
          text +=     "</tr>";
        });

        text +=     "</tbody>";
        text +=   "</table>";
        text += "</div>";

        // 버스 리스트 테이블 렌더링
        station.after($(text));
      } else {
        alert(resJSON.message);
      }
    }
  });
}
