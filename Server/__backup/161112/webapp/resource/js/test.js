

/**
* Request data from the server, add it to the graph and set a timeout to request again
*/


$(document).ready(function() {
  $("#btnGetPiSerialNo").bind('click', function() {
    $.ajax({
      type: "POST",
      url: "/test/doGetPiSerialNo",
      success: function(response) {
        console.log(response);
      }
    });
  });

  $("#btnTest").bind('click', function() {
    $.ajax({
      type: "GET",
      dataType: "xml",
      url: "http://openapi.gbis.go.kr/ws/rest/baseinfoservice?serviceKey=NoDqsCghIdBYA7NqkU2%2F0z1Eb%2BPUwsGB2vnDoDYLHKx4ugPH4H70ROU%2FwUp%2FFqrcBjTtAbBOxeROTUUGYhN2AA%3D%3D",
      success: function(response) {
        console.log(response);
      }
    });
  });

  $("#btnUpdateBusList").bind('click', function() {
    $.ajax({
      type:"POST",
      url:"/nodered/doUpdateBusDB",
      success:function(response) {
        console.log(response);
      }
    })
  });



  // var chart; // global
  // function requestData() {
  //   $.ajax({
  //     url: '/test/chartTest',
  //     success: function(point) {
  //       var series = chart.series[0],
  //       shift = series.data.length > 20; // shift if the series is longer than 20
  //
  //       // add the point
  //       chart.series[0].addPoint(eval(point), true, shift);
  //
  //       // call it again after one second
  //       setTimeout(requestData, 1000);
  //     },
  //     cache: false
  //   });
  // }
  //
  // chart = new Highcharts.Chart({
  //   chart: {
  //     renderTo: 'container',
  //     defaultSeriesType: 'spline',
  //     events: {
  //       load: requestData
  //     }
  //   },
  //   title: {
  //     text: 'Live random data'
  //   },
  //   xAxis: {
  //     type: 'datetime',
  //     tickPixelInterval: 150,
  //     maxZoom: 20 * 1000
  //   },
  //   yAxis: {
  //     minPadding: 0.2,
  //     maxPadding: 0.2,
  //     title: {
  //       text: 'Value',
  //       margin: 80
  //     }
  //   },
  //   series: [{
  //     name: 'Random data',
  //     data: []
  //   }]
  // });

  $.getJSON('/test/chartTest2', function(data) {
    console.log(data);

    // Create the chart
    $('#container').highcharts({

      title : { text : 'Temperature' },
      yAxis: [{
                title: { text: 'A' }
              },
              {
                title: { text: 'B' }
              },
              {
                title: { text: 'C' }
              },
              {
                title: { text: 'D' }
              }],
      series: [{
                  type: 'line',
                  name: 'data1',
                  yAxis: 0,
                  data: data[0].data
               },
               {
                  type: 'line',
                  name: 'data2',
                  yAxis: 1,
                  data: data[1].data
               },
               {
                  type: 'line',
                  name: 'data3',
                  yAxis: 2,
                  data: data[2].data
               },
               {
                  type: 'line',
                  name: 'data4',
                  yAxis: 3,
                  data: data[3].data
               }]
    });
  });
});
