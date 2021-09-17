/* 초기화(집 관리가 첫 화면이므로 세팅) */
function init() {
  getHardwareInfo();
}
init();

/* 실시간 실내 데이터 */
// 센서 데이터 조회
function getSensorData() {
  $.ajax({
    type    : "POST",
    url     : "/home/doGetSensorVal",
    cache   : false,
    success : function(response) {
      var resJSON = $.parseJSON(response);

      if(resJSON.result) {
        var dataList  = resJSON.list;
        var time      = parseInt(resJSON.time);

        var series    = chart.series[0];
        var shift     = (series.data.length > 10); // 10개 단위로 데이터 표시

        var delay     = 3000;                      // 3초 단위로 데이터 조회

        // 센서 데이터 리스트를 순회하며 차트 point 추가
        $.each(dataList, function(i, data) {
          switch(data.type) {
            case "temperature":
              chart.series[0].addPoint([time, parseInt(data.sensorVal1)], false, shift);
              break;
            case "humidity":
              chart.series[1].addPoint([time, parseInt(data.sensorVal1)], false, shift);
              break;
            case "dust":
              chart.series[2].addPoint([time, parseInt(data.sensorVal1)], false, shift);
              break;
            case "gas":
              chart.series[3].addPoint([time, parseInt(data.sensorVal1)], true, shift);
              break;
          }
        });

        setTimeout(getSensorData, delay);
      } else {
        alert(resJSON.message);
      }
    }
  });
}

// 차트 설정
Highcharts.setOptions({         // x축 표시 시간 설정
  global: { useUTC: false }
});

chart = new Highcharts.Chart({  // 차트 렌더링
  chart: {
      renderTo: 'realtimeDataChart',
      type: 'spline',
      animation: Highcharts.svg, // don't animate in old IE
      marginLeft: 60,
      marginRight: 60,
      events: { load: getSensorData }
    },
  colors: [
      Highcharts.getOptions().colors[5],  // 온도
      '#2196F3',                          // 습도
      Highcharts.getOptions().colors[1],  // 미세먼지
      '#4CAF50'                           // 가스
    ],
  title: { text: '' },
  xAxis: {
      title: { text: '시간', margin: 5 },
      type: 'datetime',
      tickPixelInterval: 150
    },
  yAxis:
   [{ // 온도 y축
      labels: {
          format: '{value}',
          style: { color: Highcharts.getOptions().colors[5] }
        },
      title: {
          text: '온도(℃)/습도(rh)',
          style: { color: Highcharts.getOptions().colors[5] }
        }
    }, { // 습도 y축
      labels: {
          format: '{value}',
          style: { color: Highcharts.getOptions().colors[1] }
        },
      title: {
          text: '미세먼지(㎛)/가스(ppm)',
          style: { color: Highcharts.getOptions().colors[1] }
        },
      opposite: true
    }],
  tooltip: {
    formatter: function() {
        var unit; // 단위

        switch(this.series.name) {
          case "온도":
            unit = " ℃";
            break;
          case "습도":
            unit = " rh";
            break;
          case "미세먼지":
            unit = " ㎛";
            break;
          case "가스":
            unit = " ppm";
            break;
        }

        return '<b>' + this.series.name + '</b><br/>' +
                Highcharts.dateFormat('%Y년 %m월 %d일 %H:%M:%S', this.x) + '<br/>' +
                Highcharts.numberFormat(this.y, 0) + unit;
      }
    },
  legend: {
      layout: 'horizontal',
      verticalAlign: 'bottom',
      borderWidth: 0
    },
  exporting: { enabled: false },
  series: [
      { name: '온도', data: [], yAxis: 0 },
      { name: '습도', data: [], yAxis: 0 },
      { name: '미세먼지', data: [], yAxis: 1 },
      { name: '가스', data: [], yAxis: 1, }
    ]
  });

/* 하드웨어 관리 */
// 하드웨어 정보 조회
function getHardwareInfo() {
  $.ajax({
    type    : "POST",
    url     : "/home/doGetHardwareInfo",
    success : function(response) {
      var resJSON = $.parseJSON(response);

      if(resJSON.result) {
        var piInfo      = resJSON.piInfo;
        var arduinoList = resJSON.arduinoList;
        var sensorList  = resJSON.sensorList;

        var text; // 생성할 HTML 텍스트

        // 각 정보 테이블 초기화
        $("#piInfo").empty();
        $("#arduinoInfo").empty();

        // 라즈베리파이 정보 테이블
        text  = "<table class='table table-bordered table-hover'>";
        text +=   "<thead>";
        text +=     "<tr>";
        text +=       "<td>시리얼번호</td>";
        text +=       "<td>위도</td>";
        text +=       "<td>경도</td>";
        text +=       "<td>반경</td>";
        text +=     "</tr>";
        text +=   "</thead>";
        text +=   "<tbody>";
        text +=     "<tr>";
        text +=       "<th scope='row'>"+piInfo.serialNo+"</th>";
        text +=       "<th scope='row'>"+Number(piInfo.y).toFixed(2)+"</th>";
        text +=       "<th scope='row'>"+Number(piInfo.x).toFixed(2)+"</th>";
        text +=       "<th scope='row'>"+piInfo.radius+"km</th>";
        text +=     "</tr>";
        text +=   "</tbody>";
        text += "</table>";

        // 파이 테이블 렌더링
        $("#piInfo").append(text);

        // 아두이노 정보 테이블
        text  = "<table class='table table-bordered table-hover'>";
        text +=   "<thead>";
        text +=     "<tr>";
        text +=       "<td>위치</td>";
        text +=       "<td>온도</td>";
        text +=       "<td>습도</td>";
        text +=       "<td>미세먼지</td>";
        text +=       "<td>가스</td>";
        text +=     "</tr>";
        text +=   "</thead>";
        text +=   "<tbody>";

        // 조회한 아두이노 리스트 조회
        $.each(arduinoList, function(i, arduino) {
          text +=   "<tr>";
          text +=     "<th scope='row'>"+arduino.location+"</th>";

          var temperatureYN = "OFF";
          var humidityYN    = "OFF";
          var dustYN        = "OFF";
          var gasYN         = "OFF";

          // 조회한 센서 리스트 조회
          for(var i=0; i<sensorList.length; i++) {
            var sensor = sensorList[i];

            // 조회한 센서 리스트에서 해당 아두이노에 연결된 센서만 추출
            if(arduino.idx == sensor.arduinoIdx) {
              ((sensor.type == "temperature") && (temperatureYN == "OFF"))  ? temperatureYN = "ON"  : false;
              ((sensor.type == "humidity") && (humidityYN == "OFF"))        ? humidityYN = "ON"     : false;
              ((sensor.type == "dust") && (dustYN == "OFF"))                ? dustYN = "ON"         : false;
              ((sensor.type == "gas") && (gasYN == "OFF"))                  ? gasYN = "ON"          : false;
            }
          }

          text +=     "<th scope='row'>"+temperatureYN+"</th>";
          text +=     "<th scope='row'>"+humidityYN+"</th>";
          text +=     "<th scope='row'>"+dustYN+"</th>";
          text +=     "<th scope='row'>"+gasYN+"</th>";
          text +=   "</tr>";
        });

        text +=   "</tbody>";
        text += "</table>";

        // 아두이노 테이블 렌더링
        $("#arduinoInfo").append(text);

        // ON/OFF 색상부여
        $("#arduinoInfo th").each(function(i) {
          if($(this).text() == "ON") {
            $(this).css('color', 'green');
          } else if($(this).text() == "OFF") {
            $(this).css('color', 'red');
          }
        });

        // 하드웨어 수정하기 Modal 데이터 세팅
        $("#piAddress").val(piInfo.address);
        $("#radius").text(piInfo.radius + " km");
        // $("p[id='radius']").text(piInfo.radius);


      } else {
        alert(resJSON.message);
      }
    }
  });
}

// 라즈베리파이 반경 드롭다운
$("#ddRadius li a").bind('click', function() {
  $("#radius").text($(this).text());
});

// 라즈베리파이 수정하기 버튼
$("#btnUpdateHW").bind('click', function() {
  var address = $("#piAddress").val();
  var radius  = $("#radius").text().slice(0,3);

  var data = {
    address : address,
    radius : radius
  };

  $.ajax({
    type : "POST",
    url : "/home/doUpdateHardware",
    data : data,
    success : function(response) {
      var resJSON = $.parseJSON(response);

      if(resJSON.result) {
        alert(resJSON.message);
        $("#btnCloseHW").click();
        getHardwareInfo();
      } else {
        alert(resJSON.message);
      }
    }
  });
});
