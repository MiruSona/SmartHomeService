// 실시간 데이터 차트
var realtimeDataChart;

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
Highcharts.setOptions({ // x축 표시 시간 설정
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
  title: { text: '실시간 실내데이터' },
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
  series:
    [{
      name: '온도',
      data: [],
      yAxis: 0
    }, {
      name: '습도',
      data: [],
      yAxis: 0
    }, {
      name: '미세먼지',
      data: [],
      yAxis: 1
    }, {
      name: '가스',
      data: [],
      yAxis: 1,
    }]
  });
