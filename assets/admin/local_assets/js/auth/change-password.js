function validatePageForChangePassword() {
  $("#customDiv").fadeIn();
  $('#submitFormDetails').prop('disabled', false);
  $("#change_password_details").validate({
    rules: {
      old_password: {
        required : true,
      },
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
      old_password: {
        required : "Please enter old password.",
      },
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
       var url = base_url + "admin/Profile/changePassword"; // the script where you handle the form input.
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
            $("#customDiv").fadeOut();
            html += code
              + ` - `
              + message;
            Toast.fire({
              icon: 'success',
              title: html
            });
            window.setTimeout(function () {
              window.location = base_url + "change-password";
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

function checkPassword(){
  $("#customDiv").fadeIn();
  $('#submitFormDetailsForPassword').prop('disabled', false);
  $("#old_password-error").html("");
  var old_password = $("#old_password").val();
  var profileIdForChangePassword = $("#profileIdForChangePassword").val();
  if(old_password != ""){
    $.ajax({
    url: base_url+"admin/Profile/checkPassword/",
    type: "POST",
    data: {'password':old_password,'_id':profileIdForChangePassword},
    headers: {
      'x-api-key': 'e10adc3949ba59abbe56e057f20f883e'
    },
    success: function (data) {
      $("#customDiv").fadeOut();
        // console.log(response);
        var result_arr = jQuery.parseJSON(data);
        // console.log(result_arr.data.code);
        var code = result_arr.status;
        var details = result_arr.data;
        var message = result_arr.message;
        if(code == "1")
        {
          $("#customDiv").fadeOut();
          $('#submitFormDetailsForPassword').prop('disabled', true);
          $("#old_password-error").html("<span style='color:red'>Password not match.</span>");
          $("#old_password").focus();
          return false;
        }
        else{
          $("#customDiv").fadeOut();
          $('#submitFormDetailsForPassword').prop('disabled', false);
          $("#old_password-error").html("<span>Password match found.</span>");
          return true;
        }
         //window.location.reload();              

      },
      error: function (response) {
          console.log('something is not right !!');
           
      }
    });
  }
  $("#customDiv").fadeOut();
}