function getPage(url){
  window.location = url;
}
function validatePage() {
  jQuery.validator.addMethod("email_custome", function (value, element) {
    if (/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(value)) {
      return true;
    } else {
      return false;
    };
  }, "wrong nic number");
  $("#loginForm").validate({
    rules: {
        email: {
          required : true,
          email : true,
          email_custome : true
        }
    },
    messages: {
      email: {
          required : "Please enter email address.",
          email : "Email should be in proper format.",
          email_custome : "Email should be in proper format."
        }
    },
    errorElement: 'span',
    errorPlacement: function (error, element) {
      error.addClass('invalid-feedback');
      element.closest('.form-group').append(error);
    },
    highlight: function (element, errorClass, validClass) {
      $(element).addClass('is-invalid');
    },
    unhighlight: function (element, errorClass, validClass) {
      $(element).removeClass('is-invalid');
    },
    submitHandler: function(form) {
      $('#submitFormDetails').prop('disabled', true);
      
      
      var url = "ajax.php"; // the script where you handle the form input.
      var formData = new FormData($(form)[0]);
      formData.append("action", "forgot-password");
      // console.log(formData);
      $("#messsge-success").text("");
      $("#messsge-error").text("");
      $("#page_loader").fadeIn();
      $("#page_loader").hide();
      $.ajax({
         type: "POST",
         url: url,
         cache: false,
         data: formData, // serializes the form's elements.
         success: function(data)
         { 
          var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 6000
          });
          // console.log(data);
          $('#submitFormDetails').prop('disabled', false);
          var res = data;
          var result_arr = jQuery.parseJSON(data);
          // console.log(result_arr.data.code);
          var code = result_arr.status;
          var message = result_arr.message;
          var details = result_arr.data;
          var html = "";
          if(code == "1"){
            $("#page_loader").hide();
            html += code
              + ` - `
              + message;
            Toast.fire({
              icon: 'success',
              title: html
            });
            window.setTimeout(function () {
              window.location = "verify_otp.php?email=" + details.email;
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
         },
      contentType: false,
      processData: false
       });
    }
  });
  $("#page_loader").fadeOut();
}

$(document).ajaxStart(function(){
  $("#page_loader").fadeIn();
});
$(document).ajaxComplete(function(){
  $("#page_loader").fadeOut();
});