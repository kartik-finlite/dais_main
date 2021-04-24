<?php
$page_title = "Forget Password";
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
  <title><?php echo (isset($title) && $title != '') ? $title : 'FORGET PASSWORD'; ?></title>

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
  <!-- LOCAL JS -->
  <script src="assets/admin/local_assets/js/common/common.js"></script>

  <!-- LOCAL PAGE LEVEL JS -->
  <script src="assets/web/local_assets/js/forgot-password.js"></script>
  <style>
  .card-footer {
      background-color: none;
  }
    	
.page-loader{
		position:fixed;
		background:#ffffffe3;
		text-align:center;
		z-index:9999;
		height:100%;
		width:100%;
		left:0;
		right:0;
		bottom:0;
		top:0;
}
.loading {
		position:fixed;
		width: 150px;
		height: 150px;
		margin:auto;
		left:0;
		right:0;
		bottom:0;
		top:0;
}
.loading img{
	width:75px;
}
  </style>
</head>

<body class="hold-transition login-page">

<div class="page-loader" id="page_loader">
<div class="loading">
<img src="assets/web/images/loader3.gif" />
</div>
</div>

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
        <p class="login-box-msg">Forgot your password?</p>
        <!-- form start -->
        <form id="loginForm" name="loginForm">
          <div class="card-body">
            <div class="form-group">
              <label for="email">Enter Email Address</label>
              <input type="email" name="email" class="form-control" id="email" placeholder="Enter email address">
			</div>
			
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
<script>
$(document).ready(function(){
	$("#page_loader").fadeOut();
	
});
</script>
</body>

</html>