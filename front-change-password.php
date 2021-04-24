<?php
$page_title = "Login";
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
  <title><?php echo $page_title; ?></title>

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

 
  <!-- LOCAL JS -->
  <script src="assets/admin/local_assets/js/common/common.js"></script>

  <!-- LOCAL PAGE LEVEL JS -->
  <script src="assets/web/local_assets/js/font-change-password.js"></script>
</head>
<body class="hold-transition login-page">
  <div class="login-box">
  <div class="row mt-2">
    <img src="assets/admin/dist/img/dais-logo-small.png" alt="AdminLTE Logo" class="brand-image elevation-3" style="opacity: .8;margin: auto;height: 170px;">
    </div>
    <div class="login-logo mt-3 mb-3">
      <a href="javascript:void(0);"><b>DAIS+</b> ADMIN</a>
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
        <p class="login-box-msg">Change Password</p>
        <!-- form start -->
        <form id="change_password_details" name="change_password_details">
          <div class="card-body">
            <div class="form-group">
              <label for="password">New Password</label>
              <input type="password" name="password" class="form-control" id="password" placeholder="Please enter new password">
            </div>
            <div class="form-group">
              <label for="confirmPassword">Confirm Password</label>
              <input type="password" name="confirmPassword" class="form-control" id="confirmPassword" placeholder="Enter confirm password">
            </div>
            <!--<div class="form-group mb-0">
              <div class="custom-control custom-checkbox">
                <input type="checkbox" name="terms" class="custom-control-input" id="exampleCheck1">
                <label class="custom-control-label" for="exampleCheck1">I agree to the <a href="#">terms of service</a>.</label>
              </div>
            </div>-->
          
          <!-- /.card-body -->
          
          </div>
          <div class="card-footer text-center" style="background:none;">
            <button type="submit" id="btn-admin-auth-login" onclick="return validatePageForChangePassword();" name="btn-admin-auth-login" class="btn btn-primary pl-5 pr-5">SUBMIT</button>
          </div>
          <div class="form-group text-center">
            <a href="javascript:void(0);" onclick="return getPage('index.php');" class="forgot-password text-secondary mb-4">Back To Login</a>
          </div>
        </form>
      </div>
      <!-- /.login-card-body -->
    </div>
  </div>
  <!-- /.login-box -->

</body>
</html>
