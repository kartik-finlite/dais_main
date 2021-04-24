$( document ).ready(function() {
  $("#alert-id").delay(3200).fadeOut(300);
});

function validation_update() {
  $("#customDiv").fadeIn();
  $('#update_event').prop('disabled', false);
  $.validator.addMethod("greaterThan", function(value, element) {
    var startDate = $('#datepicker').val();
    return Date.parse(startDate) >= Date.parse(value) || value == "";
  }, "* End date must be after start date");
  $("#event_details_update").validate({
    rules: {
      'internal_notes': {
        required: true
      },
      'council_members[]': {
        required: true,
        minlength: 1
      }
    },
    messages: {
      'internal_notes': {
        required : "Please enter internal notes"
      },
      'council_members[]' : {
        required: "Please select at least one council members",
        maxlength: "Check no more than {0} boxes"
      }
    },
    errorElement: 'span',
    errorPlacement: function (error, element) {
      if(element.attr("name") == "age[]" ){
        error.addClass('age-error');
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error); 
      }else if(element.attr("name") == "council_members[]" ){
        error.addClass('council_members-error');
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error); 
      }else{
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error); 
      }
    },
    highlight: function (element, errorClass, validClass) {
      $(element).addClass('is-invalid');
    },
    unhighlight: function (element, errorClass, validClass) {
      $(element).removeClass('is-invalid');
    },
    submitHandler: function(form) {
      $('#update_event').prop('disabled', true);
       var url = "ajax.php"; // the script where you handle the form input.
    var formData = new FormData($(form)[0]);
    // console.log(formData);
    formData.append("action", "event_details_list");
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
          $('#update_event').prop('disabled', false);
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
              window.location = "event_upcoming.php";
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

$('body').on('click', '.waiver_delete', function() {
  var id = $(this).attr("data-id");
  var url = "ajax.php"; // the script where you handle the form input.
  $.ajax({
    type: "POST",
    url: url,
    data: {"id":id,"action":"pdf_delete"}, 
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
    if(code == "1"){
      $("#customDiv").fadeOut();
      html += code
        + ` - `
        + message;
      Toast.fire({
        icon: 'success',
        title: html
      });
      // window.setTimeout(function () {
      //   window.location = "change-password.php";
      // }, 2000);
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
  }
  });
  $(this).closest('.pdf_bottom_box').hide('slow');
});

$('body').on('click', '.right', function() {
  var user_id = $(this).attr('data-user_id');
  var event_id = $(this).attr('data-event_id');
  var first_name = $(this).attr('data-first_name');
  var last_name = $(this).attr('data-last_name');
  var text = "Switch "+first_name+" "+ last_name +" Back To Confirmed Participant?";
  $(".approve_text").html(text);
  $('#approve_user_id').val(user_id);
  $('#approve_event_id').val(event_id);
});

$('body').on('click', '#approve_yes', function() {
  var user_id = $("#approve_user_id").val();
  var event_id = $('#approve_event_id').val();
  var url = "ajax.php"; // the script where you handle the form input.
  $.ajax({
    type: "POST",
    url: url,
    data: {"user_id":user_id,"event_id" : event_id,"action":"approve_status"}, 
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
      if(code == "1"){
        $("#customDiv").fadeOut();
        html += code
          + ` - `
          + message;
        Toast.fire({
          icon: 'success',
          title: html
        });
        $('#approve-modal').modal('hide');
        window.setTimeout(function () {
          location.reload();
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
    }
    });
});


$('body').on('click', '.close', function() {
  var user_id = $(this).attr('data-user_id');
  var event_id = $(this).attr('data-event_id');
  $('#reject_user_id').val(user_id);
  $('#reject_event_id').val(event_id);
});

$('body').on('click', '#reject_yes', function() {
  var user_id = $("#reject_user_id").val();
  var event_id = $('#reject_event_id').val();
  var url = "ajax.php"; // the script where you handle the form input.
  $.ajax({
    type: "POST",
    url: url,
    data: {"user_id":user_id,"event_id" : event_id,"action":"reject_status"}, 
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
      if(code == "1"){
        $("#customDiv").fadeOut();
        html += code
          + ` - `
          + message;
        Toast.fire({
          icon: 'success',
          title: html
        });
        $('#reject-modal').modal('hide');
        window.setTimeout(function () {
          location.reload();
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
    }
    });
});

function signup_data(event_id){
    var url = "ajax.php"; // the script where you handle the form input.
    $.ajax({
      type: "POST",
      url: url,
      data: {"event_id":event_id,"action":"signup_data"}, 
      async: false,
       beforeSend: function() {
     $("#customDiv").fadeIn();
     },
    success: function(data)
    { 
      var res = data;
      var result_arr = jQuery.parseJSON(data);
      // console.log(result_arr.data.code);
      var code = result_arr.status;
      var message = result_arr.message;
      var details = result_arr.data;
      window.open(details.url, '_self');
    }
    });
}

$('body').on('click', '#reason_button', function() {
  //var data = $("#reason_commit").val();
  var message = $('textarea#reason_commit').val();
  var event_id = $('#event_id').val();
  var url = "ajax.php";
  $.ajax({
    type: "POST",
    url: url,
    data: {"message":message,"event_id" :event_id ,"action":"reason_leaving"}, 
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
      var res = data;
      var result_arr = jQuery.parseJSON(data);
      // console.log(result_arr.data.code);
      var code = result_arr.status;
      var message = result_arr.message;
      var details = result_arr.data;
      var html = "";
      if(code == "1"){
        html += code
          + ` - `
          + message;
        Toast.fire({
          icon: 'success',
          title: html
        });
        window.setTimeout(function () {
          window.location = "event_upcoming.php";
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
      }
  });
});

$('body').on('click', '.info-comment', function() {
  $('#reason-commit').html($(this).data('commit'));
  $('#get-reason-leave').modal('show');
});

$("#signup_button").show();
$('body').on('click', '#signup_button', function() {
  $("#signup_button").hide();
});