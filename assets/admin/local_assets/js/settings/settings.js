function validatePage() {
  $("#customDiv").fadeIn();
  jQuery.validator.addMethod("email_custome", function (value, element) {
      if (/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(value)) {
          return true;
      } else {
          return false;
      }
      ;
  }, "wrong nic number");
  $("#settings_details").validate({
      rules: {
          trialPackValidity: {
              required: true,
              digits: true,
              min: 1
          },
          threshold: {
              required: true,
              digits: true,
              min: 1
          },
          perUserPrice: {
              required: true,
              digits: true,
              min: 1
          },
          gst: {
              required: true,
              number: true,
              range: [0, 100]
          },
          transactionChargeInPer: {
              required: true,
              number: true,
              range: [0, 100]
          },
          noOfDLicence: {
              required: true,
              digits: true,
              min: 1
          },
          onEveryDLicence: {
              required: true,
              digits: true,
              min: 1
          },
          noOfLicence: {
              required: true,
              digits: true,
              min: 1
          },
          onEveryLicence: {
              required: true,
              digits: true,
              min: 1
          },
          noOfMonths: {
              required: true,
              digits: true,
              min: 1
          },
          onEveryMonths: {
              required: true,
              digits: true,
              min: 1
          }

      },
      messages: {
          trialPackValidity: {
              required: "Please enter trial pack validity in days.",
              digits: "Trial pack validity should contain only numaric value.",
              min: "Please enter a value greater than zero."
          },
          threshold: {
              required: "Please enter threshold price.",
              digits: "Threshold should contain only numaric value.",
              min: "Please enter a value greater than zero."
          },
          noOfDLicence: {
              required: "Please enter no of licence.",
              digits: "No of licence contain only numaric value.",
              min: "Please enter a value greater than zero."
          },
          onEveryDLicence: {
              required: "Please enter no of licence.",
              digits: "No of licence contain only numaric value.",
              min: "Please enter a value greater than zero."
          },
          noOfLicence: {
              required: "Please enter no of licence.",
              digits: "No of licence contain only numaric value.",
              min: "Please enter a value greater than zero."
          },
          onEveryLicence: {
              required: "Please enter on every licence.",
              digits: "On every licence contain only numaric value.",
              min: "Please enter a value greater than zero."
          },
          noOfMonths: {
              required: "Please enter on every licence.",
              digits: "On every licence contain only numaric value.",
              min: "Please enter a value greater than zero."
          },
          onEveryMonths: {
              required: "Please enter on every licence.",
              digits: "On every licence contain only numaric value.",
              min: "Please enter a value greater than zero."
          },
          gst: {
              required: "Please enter GST.",
              number: "GST contain only numaric value.",
              range: "GST should be between 0 to 100"
          },
          transactionChargeInPer: {
              required: "Please enter transaction charge.",
              number: "Transaction charge contain only numaric value.",
              range: "Transaction charge should be between 0 to 100"
          },
          perUserPrice: {
              required: "Please enter per user price.",
              digits: "Per user price should contain only numaric value.",
              min: "Please enter a value greater than zero."
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
      submitHandler: function (form) {

          $('#submitFormDetails').prop('disabled', true);
          var url = base_url + "settings"; // the script where you handle the form input.

          // var url = ""; // the script where you handle the form input.
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
              success: function (data)
              {
                var Toast = Swal.mixin({
                  toast: true,
                  position: 'top-end',
                  showConfirmButton: false,
                  timer: 6000
                });
                  $("#customDiv").fadeOut();
                  $('#submitFormDetails').prop('disabled', false);
                  var res = data;
                  var result_arr = jQuery.parseJSON(data);
                  // console.log(result_arr.data.code);
                  var code = result_arr.status;
                  var message = result_arr.message;
                  var details = result_arr.data;
                  var html = "";
                  if (code == "0") {
                    html += code
                    + ` - `
                    + message;
                    Toast.fire({
                      icon: 'success',
                      title: html
                    });
                    window.setTimeout(function () {
                      window.location = base_url + "settings";
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
  $("#customDiv").fadeOut();
}

$('body').on('click', '#save', function() {
  $("#settings_details").submit();
});