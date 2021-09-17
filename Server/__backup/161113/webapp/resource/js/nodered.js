$(document).ready(function() {
  $("#btnnodered").bind('click', function() {
    $.ajax({
      type:"POST",
      url:"/nodered/doUpdateBusDB",
      success:function(response) {
        console.log(response);
      }
    })
  });
});
