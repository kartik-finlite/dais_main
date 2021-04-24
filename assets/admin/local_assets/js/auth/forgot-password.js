function getPage(url){
  window.location = url;
}

function validatePage() {
			
  $("#customDiv").fadeIn();
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
          required : "Please enter email.",
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
      $("#customDiv").fadeIn();
       var url = base_url + "admin/Dashboard/requestForPassword"; // the script where you handle the form input.
       var login_url = base_url + "login"; // the script where you handle the form input.
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
            window.setTimeout(function () {
              window.location = base_url + "login";
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
      cache: false,
      contentType: false,
      processData: false
       });
    }
  });
  $("#customDiv").fadeOut();
}