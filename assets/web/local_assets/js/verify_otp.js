let timerOn = true;
function timer(remaining) {
    var m = Math.floor(remaining / 60);
    var s = remaining % 60;
    
    m = m < 10 ? '0' + m : m;
    s = s < 10 ? '0' + s : s;
    document.getElementById('timer').innerHTML = m + ':' + s;
    remaining -= 1;
    
    if(remaining >= 0 && timerOn) {
        setTimeout(function() {
            timer(remaining);
        }, 1000);
        return;
    }

    if(!timerOn) {
        // Do validate stuff here
        return;
    }
    
    // Do timeout stuff here
    $("#resend").show();
}
timer(60);

var getUrlParameter = function getUrlParameter(sParam) {
  var sPageURL = window.location.search.substring(1),
      sURLVariables = sPageURL.split('&'),
      sParameterName,
      i;

  for (i = 0; i < sURLVariables.length; i++) {
      sParameterName = sURLVariables[i].split('=');

      if (sParameterName[0] === sParam) {
          return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
      }
  }
};

function getCodeBoxElement(index) {
  return document.getElementById('codeBox' + index);
}
function onKeyUpEvent(index, event) {
  const eventCode = event.which || event.keyCode;
  if (getCodeBoxElement(index).value.length === 1) {
  if (index !== 4) {
      getCodeBoxElement(index+ 1).focus();
  } else {
      getCodeBoxElement(index).blur();
      // Submit code
      console.log('submit code ');
  }
  }
  if (eventCode === 8 && index !== 1) {
  getCodeBoxElement(index - 1).focus();
  }
}
function onFocusEvent(index) {
  for (item = 1; item < index; item++) {
  const currentElement = getCodeBoxElement(item);
  if (!currentElement.value) {
      currentElement.focus();
      break;
  }
  }
}

$('#resend').on('click', function() {
  var email = getUrlParameter('email');
  var url = "ajax.php";
  $.ajax({
    type: "POST",
    url: url,
    data: {"email":email,"action":"forgot-password"}, 
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
          window.location = "verify_otp.php?email=" + details.email;
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

function validatePage() {
  $("#loginForm").validate({
    rules: {
      codeBox1: {
          required : true
        },
      codeBox2: {
          required : true
        },
      codeBox3: {
          required : true
        },
      codeBox4: {
          required : true
        }
    },
      messages: {
          "codeBox1": "",
          "codeBox2": "",
          "codeBox3": "",
          "codeBox4": "",
      },
    errorElement: 'span',
    errorPlacement: function (error, element) {
      error.addClass('invalid-feedback');
      element.closest('.form-group').append(error);
    },
    highlight: function (element, errorClass, validClass) {
      $(element).addClass('is-invalid-1');
    },
    unhighlight: function (element, errorClass, validClass) {
      $(element).removeClass('is-invalid-1');
    },
    submitHandler: function(form) {
      
      $('#submitFormDetails').prop('disabled', true);
      $("#customDiv").fadeIn();
      var url = "ajax.php"; // the script where you handle the form input.
      var email = getUrlParameter('email');
      var formData = new FormData($(form)[0]);
      formData.append("action", "verify_otp");
      formData.append("email", email);
      // console.log(formData);
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
            html += code
              + ` - `
              + message;
            Toast.fire({
              icon: 'success',
              title: html
            });
            window.setTimeout(function () {
              window.location = "front-change-password.php?email=" + details.email;
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