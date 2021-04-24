function validatePage() {
  $('#btn-admin-auth-login').prop('disabled', false);
  jQuery.validator.addMethod("email_custome", function (value, element) {
    if (/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(value)) {
      return true;
    } else {
      return false;
    }
  }, "wrong nic number");
  $("#frm-admin-auth-login").validate({
    rules: {
      email: {
        required: true,
        email: true,
        email_custome: true
      },
      password: {
        required: true,
        minlength: 6
      },
    },
    messages: {
      email: {
        required: "Please enter email.",
        email: "Email should be in proper format.",
        email_custome: "Email should be in proper format."
      },
      password: {
        required: "Please enter password.",
        minlength: "Password must contain at least 6 characters."
      },
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
    submitHandler: function (form) {
      $('#btn-admin-auth-login').prop('disabled', true);
      var url = "admin-login-attempt.php"; // the script where you handle the form input.
      var formData = new FormData($(form)[0]);
      formData.append("action", "admin-login-attempt");
      $.ajax({
        type: "POST",
        url: url,
        data: formData, // serializes the form's elements.
        async: false,
        beforeSend: function () {
          // do code here.
        },
        success: function (data) {
          // console.log(data);
          // console.log(data);
          var Toast = Swal.mixin({
            toast: true,
            position: 'bottom',
            showConfirmButton: false,
            timer: 6000
          });


          $('#btn-admin-auth-login').prop('disabled', false);
          var res = data;
          var result_arr = jQuery.parseJSON(data);
          // console.log(result_arr.data.code);
          var code = result_arr.status;
          var message = '&nbsp'+result_arr.message;
          var details = result_arr.data;
          var html = "";
          if (code == 1) {
            // $("#customDiv").fadeOut();
            html += '&nbsp&nbsp&nbsp&nbsp&nbsp'+message;
            Toast.fire({
              icon: 'success',
              title: html
            });

            window.setTimeout(function () {
              window.location = "dashbord.php";
            }, 2000);
          } else {
            html +=  '&nbsp&nbsp&nbsp&nbsp&nbsp'
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
  // $("#customDiv").fadeOut();
}
function getPage(url){
  window.location = url;
}