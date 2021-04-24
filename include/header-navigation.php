<style>
a.nav-link.notification {
    position: relative;
}

a.nav-link.notification span.number {
    position: absolute;
    height: 17px;
    width: 17px;
    font-size: 13px;
    top: 0;
    color: #fff;
    right: 5px;
    border-radius: 50%;
    background: #28a745;
    text-align: center;
    line-height: 17px;
}
</style>
<body class="hold-transition sidebar-mini sidebar-collapse layout-footer-fixed">
  <div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>
      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">
        <!-- Notifications Dropdown Menu -->
      <li class="nav-item">
        <a class="nav-link notification" href="notification_list.php">
          <i class="fas fa-bell" style="color: #007bff;"></i>
      <?php 
      // Notification count
      $notification_count_query = 'SELECT COUNT(*) as count FROM `notification` WHERE user_id = "'.$_SESSION['user_details']['id'].'" AND is_read = "0"';
      $notification_count_result = mysqli_query($conn, $notification_count_query);
      $notification_count_data = mysqli_fetch_assoc($notification_count_result);
      ?>
		  <span class="number"><?php echo $notification_count_data['count']; ?></span>
        </a>
      </li>
        <li class="nav-item">
          <a class="nav-link" title="Logout" data-widget="control-sidebar" data-slide="true" href="javascript:void(0);" onclick="return getPage('logout.php');" role="button"><i class="fas fa-power-off"></i></a>
        </li>
      </ul>
    </nav>
    <!-- /.navbar -->