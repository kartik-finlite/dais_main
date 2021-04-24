$('body').on('click', '#reason_button', function() {
  //var data = $("#reason_commit").val();
  var message = $('textarea#reason_commit').val();
  var event_id = $('#event_id').val();
  var url = "ajax.php";
  $.ajax({
    type: "POST",
    url: url,
    data: {"message":message,"event_id" :event_id ,"action":"reason_leaving"}, 
    success: function(data)
      { 
      var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 6000
      });
      $("#customDiv").fadeIn();
      // console.log(data);
      var res = data;
      var result_arr = jQuery.parseJSON(data);
      // console.log(result_arr.data.code);
      var code = result_arr.status;
      var message = result_arr.message;
      var details = result_arr.data;
      var html = "";
      if(code == "1"){
        html += code
          + ` - `
          + message;
        Toast.fire({
          icon: 'success',
          title: html
        });
        window.setTimeout(function () {
          window.location = "event_upcoming.php";
        }, 2000);
      }else{
        html += code
          + ` - `
          + message;
        Toast.fire({
          icon: 'error',
          title: html
        });
        return false;
      }
      }
  });
});

$("#signup_button").show();
// $('body').on('click', '#signup_button', function() {
//   $("#signup_button").hide();
// });