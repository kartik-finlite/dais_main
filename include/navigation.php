<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a onclick="return getPage('dashbord.php');" href="javascript:void(0);" class="brand-link">
    <img src="assets/admin/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light"><b>DAIS+</b> ADMIN</span>
  </a>
  
  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image p-image">
      <?php 
      if(isset($_SESSION['user_details']['image_url']) && $_SESSION['user_details']['image_url'] != ''){
        ?>
      <img src="<?php echo BASE_URL.$_SESSION['user_details']['image_url']; ?>" class="img-circle elevation-2" alt="User Image">
        <?php 
      }else{
      ?>
        <img src="assets/admin/dist/img/dais-logo-small.png" class="img-circle elevation-2" alt="User Image">
      <?php } ?>
      </div>
      <div class="info">
        <a href="javascript:void();" onclick="return getPage('my-profile.php');" class="d-block">
          <?php
          $name = '';
          if (isset($_SESSION['user_details']['first_name']) && $_SESSION['user_details']['first_name'] != null) {
            $name .= $_SESSION['user_details']['first_name'];
          }

          if (isset($_SESSION['user_details']['last_name']) && $_SESSION['user_details']['last_name'] != null) {
            if (isset($name) && $name != null) {
              $name .= ' ';
            }
            $name .= $_SESSION['user_details']['last_name'];
          }

          echo $name;
          ?></a>
      </div>
    </div>
    <?php 
    $sql = 'SELECT u.id as user_ID,h.name as house_name,h.id as house_id,ut.name as user_type_name FROM `users` u LEFT JOIN houses h ON u.house_id = h.id LEFT JOIN user_type ut ON ut.id = u.user_type_id WHERE u.id = "'.$_SESSION['user_details']['id'].'"';
    $result = mysqli_query($conn, $sql);
    $user_data = mysqli_fetch_assoc($result);
    ?>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item">
          <a href="javascript:void(0);" onclick="return getPage('dashbord.php');" class="nav-link">
            <i class="nav-icon fa fa-home"></i>
            <p>Dashboard</p>
          </a>
        </li>
        
        <?php 
        if($user_data['user_type_name'] == "Administrator" || $user_data['user_type_name'] == "Core Captain"){
        ?>
        <li class="nav-item">
          <a href="javascript:void(0);" onclick="return getPage('upload_xls.php');" class="nav-link">
            <i class="nav-icon fas fa-user"></i>
            <p>Upload User Data</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="javascript:void(0);" onclick="return getPage('category_list.php');" class="nav-link">
            <i class="nav-icon fas fa-copy"></i>
            <p>Category</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="javascript:void(0);" onclick="return getPage('sub_category_list.php');" class="nav-link">
            <i class="nav-icon fas fa-list-ul"></i>
            <p>Sub Category</p>
          </a>
        </li>
        <?php } ?>
        <!-- <li class="nav-item">
          <a href="javascript:void(0);" onclick="return getPage('event_list.php');" class="nav-link">
            <i class="nav-icon far fa-calendar-alt"></i>
            <p>Event_old</p>
          </a>
        </li> -->
        <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
                Events
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview" style="display: none;">
            <?php 
            if($user_data['user_type_name'] == "Administrator" || $user_data['user_type_name'] == "Core Captain" || $user_data['user_type_name'] == "House Captain"){
            ?>
              <li class="nav-item">
                <a href="event.php" class="nav-link">
                  <i class="far fa-calendar-alt nav-icon"></i>
                  <p>Add Events</p>
                </a>
              </li>
            <?php } ?>
              <li class="nav-item">
                <a href="event_upcoming.php" class="nav-link">
                  <i class="far fa-calendar-alt nav-icon"></i>
                  <p>Upcoming Events</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="event_past.php" class="nav-link">
                  <i class="far fa-calendar-check nav-icon"></i>
                  <p>Past Events</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
          </a><a href="javascript:void(0);" onclick="return getPage('my-profile.php');" class="nav-link">
            <i class="nav-icon fas fa-user"></i>
            <p>User Profile</p> 
          </a>
        </li>
        <li class="nav-item">
            <a href="javascript:void(0);" onclick="return getPage('change-password.php');" class="nav-link">
            <i class="nav-icon fas fa-key"></i> 
            <p>Change Password</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="javascript:void(0);" onclick="return getPage('logout.php');" class="nav-link">
            <i class="nav-icon fa fa-cog"></i>
            <p>Logout</p>
          </a>
        </li>
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>