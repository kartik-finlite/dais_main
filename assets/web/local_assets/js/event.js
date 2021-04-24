
function validate_add_event() {
  $("#customDiv").fadeIn();
  $('#add_category_button').prop('disabled', false);
  $.validator.addMethod("greaterThan", function(value, element) {
     var startDate = $('#datepicker').val();
    // var EndDate = value;
    // console.log(new Date(startDate));


    var newdate = startDate.split("/").reverse().join("-");
    var value1 = value;
    var newdate1 = value1.split("/").reverse().join("-");
    var startDate = new Date(newdate);
    var endDate = new Date(newdate1);
    console.log(startDate >= endDate);
    return startDate >= endDate;
  }, "* End date must be after start date");
  $("#add_event_details").validate({
    rules: {
      event_name: {
        required : true
      },
      category_id: {
        required : true
      },
      date: {
        required : true
      },
      time: {
        required : true
      },
      registeration_end_date: {
        required : true,
        greaterThan: "#date"
      },
      'event_notes': {
        required: true
      },
      'internal_notes': {
        required: true
      },
      'council_members[]': {
        required: true,
        minlength: 1
      },
      'event_images' : {
        required: true
      }
    },
    messages: {
      event_name: {
        required : "Please enter event name"
      },
      category_id: {
        required : "Please select event category"
      },
      date: {
        required : "Please select event date"
      },
      time: {
        required : "Please select arrival time"
      },
      registeration_end_date: {
        required : "Please select registration end date",
        greaterThan : "Registration end date can not be greater than event date"
      },
      'event_notes': {
        required : "Please enter event notes"
      },
      'internal_notes': {
        required : "Please enter internal notes."
      },
      'council_members[]' : {
        required: "Please select at least one council member",
        maxlength: "Check no more than {0} boxes"
      },
      'event_images' : {
        required: "Please upload image"
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
      $('#add_category_button').prop('disabled', true);
       var url = "ajax.php"; // the script where you handle the form input.
    var formData = new FormData($(form)[0]);
    // console.log(formData);
    formData.append("action", "add-event");
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
          $('#add_category_button').prop('disabled', false);
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
function update_validate() {
  $("#customDiv").fadeIn();
  $('#update_event_button').prop('disabled', false);
  jQuery.validator.addMethod("greaterThan", 
  function(value, element, params) {

      if (!/Invalid|NaN/.test(new Date(value))) {
          return new Date(value) > new Date($(params).val());
      }

      return isNaN(value) && isNaN($(params).val()) 
          || (Number(value) > Number($(params).val())); 
  },'Must be greater than {0}.');
  $("#add_event_details").validate({
    rules: {
      event_name: {
        required : true
      },
      category_id: {
        required : true
      },
      date: {
        required : true
      },
      time: {
        required : true
      },
      registeration_end_date: {
        required : true,
        greaterThan: "#date"
      },
      'event_notes': {
        required: true
      },
      'internal_notes': {
        required: true
      },
      'council_members[]': {
        required: true,
        minlength: 1
      },
      'event_images[]' : {
        required: true
      }
    },
    messages: {
      event_name: {
        required : "Please enter event name"
      },
      category_id: {
        required : "Please select category"
      },
      date: {
        required : "Please select date"
      },
      time: {
        required : "Please select time"
      },
      registeration_end_date: {
        required : "Please select registration end date",
        greaterThan : "Registration end date cannot be greater than event date"
      },
      'event_notes': {
        required : "Please enter event notes"
      },
      'internal_notes': {
        required : "Please enter internal notes."
      },
      'council_members[]' : {
        required: "Please select at least one council members",
        maxlength: "Check no more than {0} boxes"
      },
      'event_images[]' : {
        required: "Please upload images"
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
      $('#update_event_button').prop('disabled', true);
       var url = "ajax.php"; // the script where you handle the form input.
    var formData = new FormData($(form)[0]);
    // console.log(formData);
    formData.append("action", "update-event");
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
          $('#update_event_button').prop('disabled', false);
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

$(document).on('click', '.age_gender_check,.grades_check', function() {      
  $('.age_gender_check,.grades_check').not(this).prop('checked', false);      
});

$('body').on('change', '#category_id', function() {
  var category_id = $("#category_id").val();
  var url = "ajax.php"; // the script where you handle the form input.
  $.ajax({
    type: "POST",
    url: url,
    data: {"category_id":category_id,"action":"get_sub_category"}, 
    success: function(data)
    { 
      $('#sub_category_id').html('<option value="">Select Sub Category</option>');
      var result_arr = jQuery.parseJSON(data);
      $.each(result_arr, function(key, value) {   
        $('#sub_category_id')
        .append($("<option></option>")
                   .attr("value", key)
                   .text(value)); 
      });
    }
  });
});

$('body').on('click', '.image_delete', function() {
  var id = $(this).attr("data-id");
  var url = "ajax.php"; // the script where you handle the form input.
  $.ajax({
    type: "POST",
    url: url,
    data: {"id":id,"action":"image_delete"}, 
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
$('body').on('click', '.pdf_delete', function() {
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