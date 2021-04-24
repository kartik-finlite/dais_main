function validatePage(actioonURL) {
  $('#btn-celebrities-details').prop('disabled', false);
  jQuery.validator.addMethod("email_custome", function (value, element) {
    if (/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(value)) {
      return true;
    } else {
      return false;
    }
  }, "wrong nic number");
  $("#frm-celebrities-details").validate({
    rules: {
      name: {
        required: true,
      },
      description: {
        required: true,
      },
      note: {
        required: true,
      },
      theme: {
        required: true,
      },
    },
    messages: {
      name: {
        required: "Please enter name.",
      },
      description: {
        required: "Please enter description.",
      },
      note: {
        required: "Please enter note.",
      },
      theme: {
        required: "Please select the theme.",
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
      $('#btn-celebrities-details').prop('disabled', true);
      var url = actioonURL; // the script where you handle the form input.
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


          $('#btn-celebrities-details').prop('disabled', false);
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
              window.location = base_url + "admin/celebrities";
            }, 2000);
          } else {
            html += code
              + ` - `
              + message
              + JSON.stringify(details);
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


$(document).ready(function () {
  // Summernote
  $('#description').summernote();
  $('#note').summernote();
});


function performClick(elemId) {
  var elem = document.getElementById(elemId);
  if (elem && document.createEvent) {
    var evt = document.createEvent("MouseEvents");
    evt.initEvent("click", true, false);
    elem.dispatchEvent(evt);

  }
  $('#theFile').change(function (event) {
    // console.log(event);
    $("#celebrityImage").fadeIn("fast").attr('src', URL.createObjectURL(event.target.files[0]));
  });
}

function checkExtansion() {
  $('#btn-celebrities-details').prop('disabled', false);
  $("#theFile").removeClass('is-invalid');
  $("#fileName-error-1").removeClass('invalid-feedback');
  $("#fileName-error-1").html('');
  var fileExtension = ['jpeg', 'jpg', 'png'];
  // var fileExtension = ['jpeg', 'jpg'];
  if ($.inArray($("#theFile").val().split('.').pop().toLowerCase(), fileExtension) == -1) {
    $("#fileName-error-1").html("Only formats are allowed : " + fileExtension.join(', '));
    $("#fileName-error-1").addClass('invalid-feedback');
    $("#theFile").val('');
    $("#theFile").addClass('is-invalid');
    $('#btn-celebrities-details').prop('disabled', true);
    return false;
  }
}



$(document).ready(function () {
  $('#theme').select2();
  $('#problems').select2();
});




$(document).ready(function () {
  document.getElementById('photos').addEventListener('change', readImage, false);

  $(".preview-images-zone").sortable();

  $(document).on('click', '.image-cancel', function () {
    let no = $(this).data('no');
    if (window.confirm("Are you sure? you want to remove this record?") === true) {
      var main_id = $(this).attr('data-main-id');
      var image_id = $(this).attr('data-img-id');
      var data = {
        _id: main_id,
        image_id: image_id,
      };
      $.ajax({
        type: "POST",
        url: base_url + "admin/celebrities/delete-image",
        data: JSON.stringify(data),
        headers: {
          'Content-Type': 'application/json',
          'x-api-key': 'e10adc3949ba59abbe56e057f20f883e'
        },
        beforeSend: function () {
          // do code here.
        },
        success: function (data) {
          // console.log(data);
          var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 6000
          });

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
              window.location.reload(true);
            }, 2000);
            return false;
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
        }
      });
    }

    $(".preview-image.preview-show-" + no).remove();
  });
});




var num = 0;
function readImage() {
  num = parseInt($("#imgIndex").val());
  if (window.File && window.FileList && window.FileReader) {
    var files = event.target.files; //FileList object
    var output = $(".preview-images-zone");

    for (let i = 0; i < files.length; i++) {
      var file = files[i];
      if (!file.type.match('image')) continue;

      var picReader = new FileReader();

      picReader.addEventListener('load', function (event) {
        var picFile = event.target;
        var html = '<div class="preview-image preview-show-' + num + '">' +
          '<div class="image-zone"><img id="pro-img-' + num + '" src="' + picFile.result + '"></div>' +
          '</div>';

        output.append(html);
        num = num + 1;
        $("#imgIndex").val(num);
      });

      picReader.readAsDataURL(file);
    }
    // $("#photos").val('');
  } else {
    console.log('Browser not support');
  }
}

