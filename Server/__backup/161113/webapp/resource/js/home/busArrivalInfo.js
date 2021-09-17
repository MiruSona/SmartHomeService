var updateTbl = 0;

$(document).ready(function() {
  // 버스 정류소 리스트 이벤트 연결
  $("#busInfoWrapper").delegate('div', 'click', function() {

    var showList = $(this).attr('showList');  // 버스 정류소 표시 여부 속성
    var glyphicon = $(this).find('span');     // 버스 정류소 glyphicon(up/down)

    // showList(Y/N) 값에 따른 처리
    switch(showList) {
      case "Y":
        $(this).attr('showList', 'N');
        glyphicon.attr('class', 'glyphicon glyphicon-chevron-down');
        $(this).next().remove(); // 표시했던 버스 리스트 영역 삭제
        clearTimeout(updateTbl); // 테이블 업데이트 중지
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
  $("#busInfoWrapper").empty(); // 버스 정류소 리스트 초기화

  $.ajax({
    type    : "POST",
    url     : "/home/doGetBusStnList",
    success : function(response) {
      var resJSON = $.parseJSON(response);
      console.log(response);

      if(resJSON.result) {
        var radius      = resJSON.radius;   // 파이에 설정된 정류소 검색 반경
        var busStnList  = resJSON.list;     // 버스 정류소 리스트

        // 검색 반경 표시
        $("#busArrivalInfo span").text(radius);

        // 조회한 버스 정류소 리스트만큼 조회
        $.each(busStnList, function(i, station) {
          var stnID       = station.STATION_ID; // 정류소 ID
          var stnName     = station.STATION_NM; // 정류소명
          var stnMobileNo = station.MOBILE_NO;  // 정류소 모바일 번호

          var text; // 생성할 HTML 텍스트

          text  = "<div class='row bus-info-header' stnid='" + stnID + "' showList='N' style='border: 1px solid #212121;'>";
          text +=   "<p>" + stnName + "(" + stnMobileNo + ")" + "</p>";
          text +=   "<span class='glyphicon glyphicon-chevron-down'></span>";
          text += "</div>";

          // 버스 정류소 리스트 렌더링
          $("#busInfoWrapper").append(text);
        });
      } else {
        alert(resJSON.message);
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
          var routeNM       = bus.routeNM;                                                  // 노선번호
          var predictTime   = (bus.predictTime1==1) ? "잠시후 도착" : bus.predictTime1+"분";  // 예상 도착시간(분)
          var lowPlate      = (bus.lowPlate1==0 ? "일반" : "저상");                          // 일반/저상 여부
          var remainSeatCnt = (bus.remainSeatCnt1 ? "-" : bus.remainSeatCnt1);              // 남은좌석

          (bus.predictTime1==1) ? text+="<tr class='info'>" : text+="<tr>";
          text +=       "<th scope='row'>" + (i+1) + "</th>";
          text +=       "<th scope='row'>" + routeNM + "</th>";
          text +=       "<th scope='row'>" + predictTime + "</th>";
          text +=       "<th scope='row'>" + lowPlate + "</th>";
          text +=       "<th scope='row'>" + remainSeatCnt + "</th>";
          text +=     "</tr>";
        });

        text +=     "</tbody>";
        text +=   "</table>";
        text += "</div>";

        // 버스 리스트 테이블 렌더링
        station.after($(text));

        // 10초마다 테이블 갱신
        var updateTbl = 0;

        if(station.showlist="Y") {
          updateTbl = setInterval(function() { getBusList(station); }, 3000);
        } else {
          clearInterval(updateTbl);
        }

      } else {
        alert(resJSON.message);
      }
    }
  });
}
