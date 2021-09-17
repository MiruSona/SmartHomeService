$(document).ready(function() {
  // alert("할룽")

  /* 1. 버스정류소 List 가져오기 */
  $("#btnStnList").bind('click', function() {
    var x = $("#axisX").val();
    var y = $("#axisY").val();
    var rad = $("#rad").val();

    var data = {
      x : x,
      y : y,
      rad : rad
    }

    $.ajax({
      type : "POST",
      url : "/mobile/doGetBusStationList",
      data : data,
      success : function(response) {
        console.log(response);
        var resJSON = $.parseJSON(response);
        console.log(resJSON);
      }
    });
  });

  /* 2. 버스 List 가져오기 */
  $("#btnBusList").bind('click', function() {
    var stationId = $("#stnID").val();

    var data = { stationId : stationId };

    $.ajax({
      type : "POST",
      url : "/mobile/doGetBusList",
      data : data,
      success : function(response) {
        console.log(response);
        var resJSON = $.parseJSON(response);
        console.log(resJSON);
      }
    });
  });

  /* 3. 버스 Info 가져오기 */
  $("#btnBusInfo").bind('click', function() {
    var stationId = $("#stnID2").val();
    var routeId = $("#busID").val();

    var data = {
      stationId : stationId,
      routeId : routeId
    }

    $.ajax({
      type : "POST",
      url : "/mobile/doGetBusInfo",
      data : data,
      success : function(response) {
        console.log(response);
      }
    });
  });

  /* 4. 날씨 Info 가져오기 */
  $("#btnWeatherInfo").bind('click', function() {

    var x = $("#wAxisX").val();
    var y = $("#wAxisY").val();

    var data = {
      x : x,
      y : y
    };

    $.ajax({
      type:"POST",
      url:"/mobile/doGetWeatherInfo",
      data:data,
      success:function(response) {
        console.log(response);
        var resJSON = $.parseJSON(response);
        console.log(resJSON);
      }
    });
  });

  /* 5. 센서데이터 가져오기 */
  $("#btnSensorData").bind('click', function() {
    var type = $("#type").val();
    var data = { type: type };

    $.ajax({
      type:"POST",
      url:"/mobile/doGetSensorData",
      data:data,
      success:function(response) {
        console.log(response);
      }
    })
  });
});
