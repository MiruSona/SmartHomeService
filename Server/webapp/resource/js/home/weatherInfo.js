$(document).ready(function() {

});

// 날씨정보 조회
function getWeatherInfo() {
  $.ajax({
    type    : "POST",
    url     : "/home/doGetWeatherInfo",
    success : function(response) {
      var resJSON = $.parseJSON(response);

      if(resJSON.result) {
        var weatherInfoList = resJSON.list;

        var xAxisArr  = []; // 예보시각 배열
        var tempArr   = []; // 온도 배열
        var popArr    = []; // 강수확률 배열

        // 날씨정보 리스트 순회
        $.each(weatherInfoList, function(i, info) {

          // 가져온 리스트 10개만 사용
          if(i < 10) {
            xAxisArr.push(info.day + " " + info.hour + "시");
            tempArr.push(Number(info.temp));
            popArr.push(Number(info.pop));
          }
        });

        $("#weatherChart").highcharts({
          chart: { zoomType: 'xy' },
          title: { text: '' },
          colors: [
              Highcharts.getOptions().colors[5],  // 온도
              Highcharts.getOptions().colors[0]   // 강수확률
            ],

          xAxis: [{
              categories: xAxisArr,
              crosshair: true
            }],
          yAxis: [{ // 온도
              gridLineWidth: 0,
              labels: {
                  format: '{value}°C',
                  style: { color: Highcharts.getOptions().colors[5] }
                },
              title: {
                  text: '온도(℃)',
                  style: { color: Highcharts.getOptions().colors[5] }
                }
            }, { // 강수확률
              gridLineWidth: 0,
              labels: {
                  format: '{value}%',
                  style: { color: Highcharts.getOptions().colors[0] }
                },
              title: {
                  text: '강수확률(%)',
                  style: { color: Highcharts.getOptions().colors[0] }
                },
              opposite: true
            }],
          tooltip: { shared: true },
          legend: {
              layout: 'vertical',
              align: 'left',
              x: 80,
              verticalAlign: 'top',
              y: 25,
              floating: true,
              backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
            },
          exporting: { enabled: false },
          series: [
              { name: '온도', type: 'spline', yAxis: 0, data: tempArr, tooltip: { valueSuffix: ' ℃' }, zIndex: 2 },
              { name: '강수확률', type: 'column', yAxis: 1, data: popArr, tooltip: { valueSuffix: ' %' }, zIndex: 1 }
            ]
        });
      } else {
        alert(resJSON.message);
      }
    }
  });
}
