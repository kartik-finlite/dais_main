function validate(){
  $("#popup_from").validate({
    rules: {
      'category_id[]': {
        required: true,
        minlength: 3
      }
    },
    messages: {
      'category_id[]' : {
        required: "You must check at least 3 box",
        minlength: "Check no more than {0} boxes"
      },
    },errorElement : 'div',
    errorLabelContainer: '.errorTxt',
    submitHandler: function(form) {
      $('#popup_submit').prop('disabled', true);
       var url = "ajax.php"; // the script where you handle the form input.
    var formData = new FormData($(form)[0]);
    // console.log(formData);
    formData.append("action", "interested-category");
    $("#messsge-success").text("");
    $("#messsge-error").text("");
    $.ajax({
         type: "POST",
         url: url,
         data: formData, // serializes the form's elements.
         async: false,
          beforeSend: function() {
        $("#customDiv").fadeIn();
        },
         success: function(data)
         { 
          var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 6000
          });
          // console.log(data);
          $("#customDiv").fadeIn();
          // console.log(data);
          $('#popup_submit').prop('disabled', false);
          var res = data;
          var result_arr = jQuery.parseJSON(data);
          // console.log(result_arr.data.code);
          var code = result_arr.status;
          var message = result_arr.message;
          var details = result_arr.data;
          var html = "";
          if(code == "1"){
            $("#customDiv").fadeOut();
            html += code
              + ` - `
              + message;
            Toast.fire({
              icon: 'success',
              title: html
            });
            window.setTimeout(function () {
              location.reload();
            }, 2000);
            $('#myModal').modal('hide');
          }
          else{
            $("#customDiv").fadeOut();
            html += code
            + ` - `
            + message;
            Toast.fire({
              icon: 'error',
              title: html
            });
            return false;
          }
         },
      cache: false,
      contentType: false,
      processData: false
       });
    }
  });
}