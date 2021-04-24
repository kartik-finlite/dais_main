function validatePage() {
  $('#btn-admin-change-credentials').prop('disabled', false);
  jQuery.validator.addMethod("currentPassword_custome", function (value, element) {
    if (/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(value)) {
      return true;
    } else {
      return false;
    }
  }, "wrong nic number");
  $("#frm-admin-change-credentials").validate({
    rules: {
      currentPassword: {
        required: true,
        minlength: 6
      },
      password: {
        required: true,
        minlength: 6
      },
      confirmPassword: {
        required: true,
        equalTo: "#password",
        minlength: 6
      },
    },
    messages: {
      currentPassword: {
        required: "Please enter current password.",
        minlength: "Password must contain at least 6 characters."
      },
      password: {
        required: "Please enter password.",
        minlength: "Password must contain at least 6 characters."
      },
      confirmPassword: {
        required: "Please enter confirm password.",
        equalTo: "Confirm password should be matched with Password Field.",
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
      $('#btn-admin-change-credentials').prop('disabled', true);
      var url = base_url + "admin-change-credentials"; // the script where you handle the form input.
      var formData = new FormData($(form)[0]);
      $.ajax({
        type: "POST",
        url: url,
        data: formData, // serializes the form's elements.
        headers: {
          'x-api-key': 'e10adc3949ba59abbe56e057f20f883e'
        },
        async: false,
        beforeSend: function () {
          // do code here.
        },
        success: function (data) {
          // console.log(data);
          // console.log(data);
          var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 6000
          });


          $('#btn-admin-change-credentials').prop('disabled', false);
          var res = data;
          var result_arr = jQuery.parseJSON(data);
          // console.log(result_arr.data.code);
          var code = result_arr.status;
          var message = result_arr.message;
          var details = result_arr.data;
          var html = "";
          if (code == 0) {
            // $("#customDiv").fadeOut();
            html += code
              + ` - `
              + message;
            Toast.fire({
              icon: 'success',
              title: html
            });
            window.setTimeout(function () {
              window.location = base_url + "admin-change-credentials";
            }, 2000);
          } else {
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
  // $("#customDiv").fadeOut();
}

function verifyPassword() {
  var url = base_url + "admin-verify-current-password"; // the script where you handle the form input.
  var currentPassword = $("#currentPassword").val();
  $('#btn-admin-change-credentials').prop('disabled', false);
  if ($('#currentPassword-error').length == 0) {
    $("#form-group-currentPassword").append('<span id="currentPassword-error"></span>');
  }
  $("#currentPassword-error").removeClass('invalid-feedback');
  $("#currentPassword-error").html('');
  $("#currentPassword").removeClass('is-invalid');

  if (currentPassword != '') {
    $.ajax({
      type: "POST",
      url: url,
      headers: {
        'Content-Type': 'application/json',
        'x-api-key': 'e10adc3949ba59abbe56e057f20f883e'
      },
      data: JSON.stringify({ 'password': currentPassword }), // serializes the form's elements.
      beforeSend: function () {
        // do code here.
      },
      success: function (data) {
        // console.log(data);
        // console.log(data);
        var Toast = Swal.mixin({
          toast: true,
          position: 'top-end',
          showConfirmButton: false,
          timer: 6000
        });


        $('#btn-admin-change-credentials').prop('disabled', false);
        var res = data;
        var result_arr = jQuery.parseJSON(data);
        // console.log(result_arr.data.code);
        var code = result_arr.status;
        var message = result_arr.message;
        var details = result_arr.data;
        var html = "";
        if (code == 0) {
          // $("#customDiv").fadeOut();
          html += code
            + ` - `
            + message;
          Toast.fire({
            icon: 'success',
            title: html
          });
          if ($('#currentPassword-error').length == 0) {
            $("#form-group-currentPassword").append('<span id="currentPassword-error"></span>');
          }
          $("#currentPassword-error").removeClass('invalid-feedback');
          $("#currentPassword-error").html('');
          $("#currentPassword").removeClass('is-invalid');
          $('#btn-admin-change-credentials').prop('disabled', false);
          return false;
        } else {
          html += code
            + ` - `
            + message;
          Toast.fire({
            icon: 'error',
            title: html
          });
          if ($('#currentPassword-error').length == 0) {
            $("#form-group-currentPassword").append('<span id="currentPassword-error"></span>');
          }
          $("#currentPassword-error").addClass('invalid-feedback');
          $("#currentPassword-error").html(html);
          $("#currentPassword").addClass('is-invalid');
          $('#btn-admin-change-credentials').prop('disabled', true);
          return false;
        }
      },
      cache: false,
      contentType: false,
      processData: false
    });
  }
}

