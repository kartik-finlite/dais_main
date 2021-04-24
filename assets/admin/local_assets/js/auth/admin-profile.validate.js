function validatePage() {
  $('#btn-admin-profile').prop('disabled', false);
  jQuery.validator.addMethod("email_custome", function (value, element) {
    if (/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(value)) {
      return true;
    } else {
      return false;
    }
  }, "wrong nic number");
  $("#frm-admin-profile").validate({
    rules: {
      firstName: {
        required: true,
      },
      lastName: {
        required: true,
      },
      phone: {
        required: true,
        number: true,
        minlength: 8,
        maxlength: 14,
      },
      email: {
        required: true,
        email: true,
        email_custome: true
      },
    },
    messages: {
      firstName: {
        required: "Please enter first name.",
      },
      lastName: {
        required: "Please enter last name.",
      },
      phone: {
        required: "Please enter phone.",
        number: "Numeric values allowed only.",
        minlength: "Maximum 8 digits allowd",
        maxlength: "Maximum 14 digits allowd",
      },
      email: {
        required: "Please enter email.",
        email: "Email should be in proper format.",
        email_custome: "Email should be in proper format."
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
      $('#btn-admin-profile').prop('disabled', true);
      var url = base_url + "admin-profile"; // the script where you handle the form input.
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


          $('#btn-admin-profile').prop('disabled', false);
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
              window.location = base_url + "admin-profile";
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

function verifyEmail() {
  var url = base_url + "admin-verify-email-address"; // the script where you handle the form input.
  var email = $("#email").val();
  var oldEmail = $("#oldEmail").val();
  $('#btn-admin-profile').prop('disabled', false);
  if ($('#email-error').length == 0) {
    $("#form-group-email").append('<span id="email-error"></span>');
  }
  $("#email-error").removeClass('invalid-feedback');
  $("#email-error").html('');
  $("#email").removeClass('is-invalid');

  if (email != '' && oldEmail != '' && email != oldEmail) {
    $.ajax({
      type: "POST",
      url: url,
      headers: {
        'Content-Type': 'application/json',
        'x-api-key': 'e10adc3949ba59abbe56e057f20f883e'
      },
      data: JSON.stringify({ 'email': email }), // serializes the form's elements.
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


        $('#btn-admin-profile').prop('disabled', false);
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
          if ($('#email-error').length == 0) {
            $("#form-group-email").append('<span id="email-error"></span>');
          }
          $("#email-error").removeClass('invalid-feedback');
          $("#email-error").html('');
          $("#email").removeClass('is-invalid');
          $('#btn-admin-profile').prop('disabled', false);
          return false;
        } else {
          html += code
            + ` - `
            + message;
          Toast.fire({
            icon: 'error',
            title: html
          });
          if ($('#email-error').length == 0) {
            $("#form-group-email").append('<span id="email-error"></span>');
          }
          $("#email-error").addClass('invalid-feedback');
          $("#email-error").html(html);
          $("#email").addClass('is-invalid');
          $('#btn-admin-profile').prop('disabled', true);
          return false;
        }
      },
      cache: false,
      contentType: false,
      processData: false
    });
  }
}

function verifyPhone() {
  var url = base_url + "admin-verify-phone"; // the script where you handle the form input.
  var phone = $("#phone").val();
  var oldPhone = $("#oldPhone").val();
  $('#btn-admin-profile').prop('disabled', false);
  if ($('#phone-error').length == 0) {
    $("#form-group-phone").append('<span id="phone-error"></span>');
  }
  $("#phone-error").removeClass('invalid-feedback');
  $("#phone-error").html('');
  $("#phone").removeClass('is-invalid');

  if (phone != '' && oldPhone != '' && phone != oldPhone) {
    $.ajax({
      type: "POST",
      url: url,
      headers: {
        'Content-Type': 'application/json',
        'x-api-key': 'e10adc3949ba59abbe56e057f20f883e'
      },
      data: JSON.stringify({ 'phone': phone }), // serializes the form's elements.
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


        $('#btn-admin-profile').prop('disabled', false);
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
          if ($('#phone-error').length == 0) {
            $("#form-group-phone").append('<span id="phone-error"></span>');
          }
          $("#phone-error").removeClass('invalid-feedback');
          $("#phone-error").html('');
          $("#phone").removeClass('is-invalid');
          $('#btn-admin-profile').prop('disabled', false);
          return false;
        } else {
          html += code
            + ` - `
            + message;
          Toast.fire({
            icon: 'error',
            title: html
          });
          if ($('#phone-error').length == 0) {
            $("#form-group-phone").append('<span id="phone-error"></span>');
          }
          $("#phone-error").addClass('invalid-feedback');
          $("#phone-error").html(html);
          $("#phone").addClass('is-invalid');
          $('#btn-admin-profile').prop('disabled', true);
          return false;
        }
      },
      cache: false,
      contentType: false,
      processData: false
    });
  }
}