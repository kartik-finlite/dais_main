var getUrlParameter = function getUrlParameter(sParam) {
  var sPageURL = window.location.search.substring(1),
      sURLVariables = sPageURL.split('&'),
      sParameterName,
      i;

  for (i = 0; i < sURLVariables.length; i++) {
      sParameterName = sURLVariables[i].split('=');

      if (sParameterName[0] === sParam) {
          return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
      }
  }
};

function validatePageForChangePassword() {
  var email = getUrlParameter('email');
  $('#btn-admin-auth-login').prop('disabled', false);
  $("#change_password_details").validate({
    rules: {
      password: {
        required : true,
        minlength: 6
      },
      confirmPassword : {
         required:true,
        equalTo : "#password"
      }
    },
    messages: {
      password: {
        required : "Please enter password.",
        minlength: "Password must contain at least 6 characters."
      },
      confirmPassword : {
         required : "Please enter confirm password.",
        equalTo : "Confirm password must match with new password.",
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
      $('#btn-admin-auth-login').prop('disabled', true);
       var url = "ajax.php"; // the script where you handle the form input.
    var formData = new FormData($(form)[0]);
    // console.log(formData);
    formData.append("action", "change-password");
    formData.append("email", email);
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
          $('#btn-admin-auth-login').prop('disabled', false);
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
              window.location = "index.php";
            }, 2000);
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
  $("#customDiv").fadeOut();
}