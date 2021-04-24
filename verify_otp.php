<?php
$page_title = "Verify OTP";
include 'include/conn.php';

if(isset($_SESSION['user_details']['email_id']) && $_SESSION['user_details']['password']){
	header('Location: dashbord.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo (isset($title) && $title != '') ? $title : 'Verify OTP'; ?></title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="assets/admin/plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="assets/admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="assets/admin/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <!-- Toastr -->
  <link rel="stylesheet" href="assets/admin/plugins/toastr/toastr.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="assets/admin/dist/css/adminlte.min.css">

  <!-- LOCAL CSS -->
  <link rel="stylesheet" href="assets/admin/local_assets/css/common/common.css">

  <!-- jQuery -->
  <script src="assets/admin/plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="assets/admin/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="assets/admin/dist/js/adminlte.min.js"></script>


  <!-- jquery-validation -->
  <script src="assets/admin/plugins/jquery-validation/jquery.validate.min.js"></script>
  <script src="assets/admin/plugins/jquery-validation/additional-methods.min.js"></script>

  <!-- SweetAlert2 -->
  <script src="assets/admin/plugins/sweetalert2/sweetalert2.min.js"></script>
  <!-- Toastr -->
  <script src="assets/admin/plugins/toastr/toastr.min.js"></script>

  <!-- LOCAL PUBLIC JS -->
  <script>
    var base_url = "";
  </script>
  <!-- LOCAL JS -->
  <script src="assets/admin/local_assets/js/common/common.js"></script>

  <!-- LOCAL PAGE LEVEL JS -->
  
  <style>
    .pass-all-input input {
        display: inline-block;
        width: 35px;
        height: 35px;
        border-radius: 0;
        margin-right: 15px;
        background-color: #eee;
        color: #000;
        font-size: 18px;
        padding: 0;
    }
    button#resend {
        margin-top: 5px;
        color: #E42C3E !important;
        font-weight: 400;
        background-color: #fff !important;
        padding: 0;
        margin-bottom: 10px;
        font-weight: 500;
    }
    .h4_below {
        color: #656565;
        font-weight: bold;
        letter-spacing: 1.5px;
    }
    .form-control.is-invalid-1, .was-validated .form-control:invalid {
        border-color: #dc3545;
        padding-right: 2.25rem;
        /* background-image: url(data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='none' stroke='%23dc3545' viewBox='0 0 12 12'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e); */
        background-repeat: no-repeat;
        background-position: right calc(.375em + .1875rem) center;
        background-size: calc(.75em + .375rem) calc(.75em + .375rem);
    }
  </style>
</head>

<body class="hold-transition login-page">
  <div class="login-box">
  <div class="row mt-2">
    <img src="assets/admin/dist/img/dais-logo-small.png" alt="AdminLTE Logo" class="brand-image elevation-3" style="opacity: .8;margin: auto;height: 170px;">
    </div>
    <div class="login-logo mt-3 mb-3">
      <a href="javascript:void(0);" onclick="return getPage('admin-home');"><b>DAIS+</b> ADMIN</a>
    </div>
    <!-- /.login-logo -->
    <div class="card">
      <div class="card-body login-card-body">
        <?php
          if (isset($_SESSION['FLASH_SUCCESS_FLAG']) && $_SESSION['FLASH_SUCCESS_FLAG'] != '') {
          ?>
            <div class="alert alert-success alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              <h5><i class="icon fas fa-check"></i> Alert!</h5>
                  <?php echo $_SESSION['FLASH_SUCCESS_FLAG']; ?>
                  <?php unset ($_SESSION['FLASH_SUCCESS_FLAG']);?>
            </div>
          <?php
        }
        ?>
        <p class="login-box-msg">We have sent an OTP on your email.</p>
        <!-- form start -->
        <form id="loginForm" name="loginForm">
          <div class="card-body text-center">
            <div class="form-group">

              <label for="pwd" class="box-label for-otp">Enter OTP</label>
              <div class="pass-all-input">

                  <input type="tel" class="form-control text-center cst-lbl" id="codeBox1" name="codeBox1" maxlength="1" onkeyup="onKeyUpEvent(1, event)" onfocus="onFocusEvent(1)" />

                  <input type="tel" class="form-control text-center cst-lbl" id="codeBox2" name="codeBox2" maxlength="1" onkeyup="onKeyUpEvent(2, event)" onfocus="onFocusEvent(2)" />

                  <input type="tel" class="form-control text-center cst-lbl" id="codeBox3" name="codeBox3" maxlength="1" onkeyup="onKeyUpEvent(3, event)" onfocus="onFocusEvent(3)" />

                  <input type="tel" class="form-control text-center cst-lbl" id="codeBox4" name="codeBox4" maxlength="1" onkeyup="onKeyUpEvent(4, event)" onfocus="onFocusEvent(4)"/>

              </div>

              <div class="otp-error" style="display: none;"><span>Please enter OTP</span></div>

          </div>
          <div class="from-group text-center">
              <button type="button" style="display: none;"  id="resend" class="btn btn-default cst-verify-btn Resend_otp">Resend  OTP</button>
          </div>
          <div class="text-center">Resend OTP in <span id="timer"><b></b></span></div>
			
		      </div>
		  
          <!-- /.card-body -->
          <div class="card-footer text-center" style="background:none;">
            <button type="submit" id="submitFormDetails" onclick="return validatePage();" name="btn-admin-auth-login" class="btn btn-primary pl-5 pr-5">SUBMIT</button>
		  </div>
		  <div class="card-body">
		  	<div class="form-group text-center">
				<a href="javascript:void(0);" onclick="return getPage('index.php');" class="forgot-password text-secondary mb-4">Back To Login</a>
			</div>
		  </div>
        </form>
      </div>
      <!-- /.login-card-body -->
    </div>
  </div>
  <!-- /.login-box -->

</body>
<script src="assets/web/local_assets/js/verify_otp.js"></script>
</html>