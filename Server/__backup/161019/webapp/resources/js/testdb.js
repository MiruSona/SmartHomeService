$(document).ready(function() {

  // 센서로 검색
  $("#btnSensor").bind('click', function() {

    var sensor    = $("#iptSensor").val();

    $.ajax({
      type : "POST",
      data : { sensorIdx : sensor },
      url : "/testdb/doSearchSensor",
      success : function(response) {
        console.log(response);

        var resJSON = $.parseJSON(response);
        console.log(resJSON);
      }
    });

  });

  // Mac주소로 검색
  $("#btnMac").bind('click', function() {

    var mac       = $("#iptMac").val();

  });

  // 기간으로 검색
  $("#btnDate").bind('click', function() {

    var fromDate  = $("#iptFrom").val();
    var toDate    = $("#iptTo").val();

    $.ajax({
      type : "POST",
      data : {
        fromDate : fromDate,
        toDate : toDate
      },
      url : "/testdb/doSearchPeriod",
      success : function(response) {
        console.log($.parseJSON(response));

        var recordList = $.parseJSON(response).list;

        // recordList.forEach(function(record) {
        //   console.log(record.type + " : " + record.sensorVal1 + ", " + record.sensorVal2);
        // });
        for(var i=0; i<recordList.length; i++) {
          console.log(recordList[i].type + " : " + recordList[i].sensorVal1 + ", " + recordList[i].sensorVal2);
        }
      }
    });

  });

  // 종류로 검색
  $("#btnType").bind('click', function() {

    var type      = $("#iptType").val();

  });

});
