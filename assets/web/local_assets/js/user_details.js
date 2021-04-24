function user_details_validatePage() {
  $("#customDiv").fadeIn();
  $('#submitprofile').prop('disabled', false);
  $("#user_details").validate({
    rules: {
      first_name:{
        required : true
      },
      middle_name:{
        required : true
      },
      last_name:{
        required : true
      },
      class_sr_no:{
        required : true
      },
      class:{
        required : true
      },
      div:{
        required : true
      },
      house_id:{
        required : true
      },
      user_type_id:{
        required : true
      },
      email_id:{
        required : true
      },
      phone: {
        required : true
      },
      parent_phone : {
         required:true,
      },
      other_phone : {
        required : true
     }
    },
    messages: {
      first_name:{
        required : "Please enter first name"
      },
      middle_name:{
        required : "Please enter middle name"
      },
      last_name:{
        required : "Please enter last name"
      },
      class_sr_no:{
        required : "Please enter class sr no"
      },
      class:{
        required : "Please enter class"
      },
      div:{
        required : "Please enter div"
      },
      house_id:{
        required : "Please select house"
      },
      user_type_id:{
        required : "Please select user type"
      },
      email_id:{
        required : "Please enter email address"
      },
      phone: {
        required : "Please enter Phone"
      },
      parent_phone : {
         required : "Please enter parent phone"
      },
      other_phone : {
        required : "Please enter other phone"
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
      $('#submitprofile').prop('disabled', true);
       var url = "ajax.php"; // the script where you handle the form input.
    var formData = new FormData($(form)[0]);
    // console.log(formData);
    formData.append("action", "update_user_details");
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
          $('#submitprofile').prop('disabled', false);
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
              window.location = "upload_xls.php";
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

function performClick(elemId) {
  var elem = document.getElementById(elemId);
  if (elem && document.createEvent) {
    var evt = document.createEvent("MouseEvents");
    evt.initEvent("click", true, false);
    elem.dispatchEvent(evt);

  }
  $('#theFile').change(function (event) {
    // console.log(event);
    $("#profileImage").fadeIn("fast").attr('src', URL.createObjectURL(event.target.files[0]));
  });
}

function checkEmails() {
  $("#customDiv").fadeIn();
  $('#submitprofile').prop('disabled', false);
  $("#email-error-1").html("");
  var email = $("#email_id").val();
  var old_email = $("#old_email").val();
  if (email != "" && old_email != "" && email != old_email) {
      $.ajax({
            url: "ajax.php",
            type: "POST",
            data: {'email': email,"action":"email_check"},
            success: function (data) {
                // console.log(response);
                $("#customDiv").fadeOut();
                var result_arr = jQuery.parseJSON(data);
                // console.log(result_arr.data.code);
                var code = result_arr.status;
                var details = result_arr.data;
                var message = result_arr.message;
                if (code == "1")
                {
                    $('#submitprofile').prop('disabled', true);
                    $("#email-error-1").html("<font color='red'>Email is already exist. Take another one.</font>");
                    $("#email").focus();
                    return false;
                } else {
                    $('#submitprofile').prop('disabled', false);
                    $("#email-error-1").html("");
                    return true;
                }
                //window.location.reload();              

            },
            error: function (response) {
                console.log('something is not right !!');

            }
        });
  }
}
$('body').on('keyup', '#email_id', function () {
  $('#submitprofile').prop('disabled', true);
});