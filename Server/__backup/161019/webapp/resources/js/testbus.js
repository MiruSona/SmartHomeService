$(document).ready(function() {

  // Update based information
  $("#btnAreaInfo").bind('click', function() {
    $.ajax({
      type : "POST",
      url : "/testbus/getAreaInfo",
      success : function(response) {
        console.log(response);
      }
    })
  });
});
