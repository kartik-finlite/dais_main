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
  $("#profile_details").validate({
    rules: {
      firstName: {
          required : true
      },
      lastName: {
          required : true
      },
      email: {
        required : true,
        email : true,
        email_custome : true
      },
      mobile : {
           required:true,
          minlength:9,
          maxlength:10,
          number: true
        },
    },
    messages: {
      firstName: {
          required : "Please enter firstname."
      },
      lastName: {
          required : "Please enter lastname."
      },
      lastName: {
          required : "Please enter lastname."
      },
      email: {
          required : "Please enter email.",
          email : "Email should be in proper format.",
          email_custome : "Email should be in proper format."
        },
       mobile : {
          required : "Please enter mobile number.",
          minlength:"Minimum 9 digits allowed.",
          maxlength :"Maximum 10 digits allowed.",
          number: "Please enter numbers only"
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
    submitHandler: function(form) {
        var url = base_url +"my-profile"; // the script where you handle the form input.
        var formData = new FormData($(form)[0]);
        console.log(formData);
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
                window.setTimeout(function () {
                  window.location = base_url + "my-profile";
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

function checkEmails(){
  $("#customDiv").fadeIn();
  $('#submitFormDetails').prop('disabled', false);
  $("#email-error").html("");
  var email = $("#email").val();
  var old_email = $("#old_email").val();
  if(email != "" && old_email != "" && email != old_email){
    $.ajax({
    url: base_url + "admin/Profile/checkEmails/",
    type: "POST",
    data: {'email':email},
    success: function (data) {
      $("#customDiv").fadeOut();
        // console.log(response);
        var result_arr = jQuery.parseJSON(data);
        // console.log(result_arr.data.code);
        var code = result_arr.status;
        var details = result_arr.data;
        var message = result_arr.message;
        if(code == "0")
        {
          $("#customDiv").fadeOut();
          $('#submitFormDetails').prop('disabled', true);
          $("#email-error").html("<font color='red'>Email is already exist. Take another one.</font>");
          $("#email").focus();
          return false;
        }
        else{
          $("#customDiv").fadeOut();
          $('#submitFormDetails').prop('disabled', false);
          $("#email-error").html("");
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


function checkMobile(){
	
  $("#customDiv").fadeIn();
  $('#submitFormDetails').prop('disabled', false);
  $("#mobile-error-1").html("");
  var mobile = $("#mobile").val();
  var mobile_old = $("#mobile_old").val();
  
  if(mobile != "" && mobile_old!="" && mobile_old != mobile){
    $.ajax({
    url: base_url+"admin/Profile/checkMobile/",
    type: "POST",
    data: {'mobile':mobile},
    success: function (data) {
        // console.log(response);
        $("#customDiv").fadeOut();
        var result_arr = jQuery.parseJSON(data);
        // console.log(result_arr.data.code);
        var code = result_arr.status;
        var details = result_arr.data;
        var message = result_arr.message;
        if(code == "1")
        {
          $('#submitFormDetails').prop('disabled', true);
          $("#mobile-error-1").html("<font color='red'>Mobile no. is already exist. Take another one.</font>");
          $("#mobile").focus();
          return false;
        }
        else{
          $('#submitFormDetails').prop('disabled', false);
          $("#mobile-error-1").html("");
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