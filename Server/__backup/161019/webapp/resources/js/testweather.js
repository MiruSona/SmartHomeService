$(document).ready(function() {

  // Get Location Info
  $("#btnLocation").bind('click', function() {
    $.ajax({
      type : "POST",
      url : "/testweather/getLocation",
      success : function(response) {
        var resJSON = $.parseJSON(response);

        $("#axisX").val(resJSON.x);
        $("#axisY").val(resJSON.y);
      }
    });
  });

  // Get Weather Info
  $("#btnWeather").bind('click', function() {

    if($("#axisX").val() == "" || $("#axisY").val() == "")
    {
      alert("날씨정보부터 가져오실까?");
    } else {
      $.ajax({
        type : "POST",
        data : {
          x : $("#axisX").val(),
          y : $("#axisY").val()
        },
        url : "/testweather/getWeather",
        success : function(response) {
          var resJSON = $.parseJSON(response);

          console.log(resJSON);
        }
      });

    }


  });
});
