function getPage(url){
  window.location = url;
}

function validatePage() {
  $("#customDiv").fadeIn();
  $('#submitFormDetails').prop('disabled', false);
  jQuery.validator.addMethod("email_custome", function (value, element) {
    if (/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(value)) {
      return true;
    } else {
      return false;
    };
  }, "wrong nic number");
  $("#loginForm").validate({
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
      $('#submitFormDetails').prop('disabled', true);
      var tmp_session = $("#tmp_session").val();
      $("#customDiv").fadeIn();
       var url = base_url + "reset-my-password/" + tmp_session;
    var formData = new FormData($(form)[0]);
    // console.log(formData);
    $("#messsge-success").text("");
    $("#messsge-error").text("");
    $.ajax({
         type: "POST",
         url: url,
         data: formData, // serializes the form's elements.
         headers: {
            'x-api-key': 'e10adc3949ba59abbe56e057f20f883e'
          },
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
          $('#submitFormDetails').prop('disabled', false);
          var res = data;
          var result_arr = jQuery.parseJSON(data);
          // console.log(result_arr.data.code);
          var code = result_arr.status;
          var message = result_arr.message;
          var details = result_arr.data;
          var html = "";
          if(code == "0"){
            html += code
              + ` - `
              + message;
            Toast.fire({
              icon: 'success',
              title: html
            });
            // window.setTimeout(function () {
            //   window.location = base_url + "Dashboard/thankyou";
            // }, 2000);
          }
          else if(code == "1001"){
            html += code
              + ` - `
              + message;
            Toast.fire({
              icon: 'error',
              title: html
            });
            window.setTimeout(function () {
              window.location = base_url + "login";
            }, 2000);
          }
          else{
            html += code
              + ` - `
              + message;
            Toast.fire({
              icon: 'error',
              title: html
            });
            window.setTimeout(function () {
              window.location = base_url + "login";
            }, 2000);
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