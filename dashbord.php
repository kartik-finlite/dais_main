<?php
include 'include/session_check.php';
$title = "Dashboard";
$pageTitle = "Dashboard";
include 'include/header.php';
include 'include/header-navigation.php';
include 'include/navigation.php';
$sql = 'SELECT u.id as user_ID,h.name as house_name,h.id as house_id,ut.name as user_type_name FROM `users` u LEFT JOIN houses h ON u.house_id = h.id LEFT JOIN user_type ut ON ut.id = u.user_type_id WHERE u.id = "'.$_SESSION['user_details']['id'].'"';
$result = mysqli_query($conn, $sql);
$user_data = mysqli_fetch_assoc($result);
?>

<link rel="stylesheet" href="assets/web/css/custom.css">
<script src="assets/web/local_assets/js/dashbord.js"></script>

<style>
.error{
  color: red;
}
</style>

<!-- LOCAL PAGE LAVEL JS -->


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark"><?php echo (isset($pageTitle) && $pageTitle!= null) ? $pageTitle : ''; ?></h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <?php
            if (isset($mainBreadcumbLinks) && count($mainBreadcumbLinks) > 0) {
              foreach ($mainBreadcumbLinks as $index => $value) {
                if (isset($value['url']) && $value['url'] != null) {
            ?>
                  <li class="breadcrumb-item"><a href="javascript:void();" onclick="return getPage('<?php echo $value['url']; ?>');"><?php echo (isset($value['key']) && $value['key'] != null) ? $value['key'] : ''; ?></a></li>
                <?php
                }
                ?>
            <?php
              }
            }
            ?>
            <li class="breadcrumb-item active"><?php echo (isset($subBreadcumbLinks['key']) && $subBreadcumbLinks['key'] != null) ? $subBreadcumbLinks['key'] : ''; ?></li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
      <div class="row">
        <div calss="col-12 col-sm-12 col-md-12">

          <?php
          if (isset($_SESSION['FLASH_SUCCESS_FLAG']) && $_SESSION['FLASH_SUCCESS_FLAG'] != '') {
          ?>
            <div class="alert alert-success alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              <h5><i class="icon fas fa-check"></i> Alert!</h5>
              <?php echo $_SESSION['FLASH_SUCCESS_FLAG']?>
              <?php unset ($_SESSION['FLASH_SUCCESS_FLAG']);?>
            </div>
          <?php
          }
          ?>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <!-- Info boxes -->
      <div class="row">
        <?php 
         if($user_data['user_type_name'] == "Administrator" || $user_data['user_type_name'] == "Core Captain"){
        ?>
        <div class="col-12 col-sm-6 col-md-3">
          <div class="info-box">
            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-users"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Total Users</span>
              <?php
              $sql = 'SELECT COUNT(*) as user_count FROM `users`';
              $result = mysqli_query($conn, $sql);
              $user_count_data = mysqli_fetch_assoc($result); 
              ?>
              <span class="info-box-number"><?php echo (isset($user_count_data['user_count']) && $user_count_data['user_count'] != "") ? $user_count_data['user_count'] : ""; ?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-12 col-sm-6 col-md-3">
          <div class="info-box mb-3">
            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-copy"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Total Categories</span>
              <?php
              $sql = 'SELECT COUNT(*) as category_count FROM `categories` WHERE category_id = 0 AND status ="1"';
              $result = mysqli_query($conn, $sql);
              $category_count_data = mysqli_fetch_assoc($result); 
              ?>
              <span class="info-box-number"><?php echo (isset($category_count_data['category_count']) && $category_count_data['category_count'] != "") ? $category_count_data['category_count'] : ""; ?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <!-- fix for small devices only -->
        <div class="clearfix hidden-md-up"></div>
        

        <div class="col-12 col-sm-6 col-md-3">
          <div class="info-box mb-3">
            <span class="info-box-icon bg-dark elevation-1"><i class="fas fa-list-ul"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Total Sub Categories</span>
              <?php
              $sql = 'SELECT COUNT(*) as sub_category_count FROM `categories` WHERE category_id != 0 AND status ="1"';
              $result = mysqli_query($conn, $sql);
              $sub_category_count_data = mysqli_fetch_assoc($result); 
              ?>
              <span class="info-box-number"><?php echo (isset($sub_category_count_data['sub_category_count']) && $sub_category_count_data['sub_category_count'] != "") ? $sub_category_count_data['sub_category_count'] : ""; ?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>

        <?php } ?>

        <div class="clearfix hidden-md-up"></div>

        <div class="col-12 col-sm-6 col-md-3">
          <div class="info-box mb-3">
            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-calendar-alt"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Total Upcoming Events</span>
              <?php
             
              $current_date_time = date('Y-m-d H:i:s');
              if($user_data['user_type_name'] == "Administrator" || $user_data['user_type_name'] == "Core Captain"){
                  // Total Count Get 
                  $count_sql = 'SELECT COUNT(*) as count FROM `events` WHERE event_date_time > "'.$current_date_time.'" AND is_delete = "0" AND `title` LIKE "%'.$requestField['search'].'%"';
                  $count_result = mysqli_query($conn, $count_sql);
                  $count_data = mysqli_fetch_assoc($count_result);
                  $count_data = $count_data['count'];
  
              }else if($user_data['user_type_name'] == "House Captain"){
                   // Total Count Get 
                  $count_sql = 'SELECT COUNT(*) as count FROM `events` WHERE event_date_time > "'.$current_date_time.'" AND is_delete = "0" AND `title` LIKE "%'.$requestField['search'].'%"';
                  $count_result = mysqli_query($conn, $count_sql);
                  $count_data = mysqli_fetch_assoc($count_result);
                  $count_data = $count_data['count'];
              }else{
                 

                  $student_sql = 'SELECT dob,gender,class as grade FROM `users`WHERE id = "'.$_SESSION['user_details']['id'].'"';
                $result = mysqli_query($conn, $student_sql);
                $user_details = mysqli_fetch_assoc($result);
                $user_year = date("Y", strtotime($user_details['dob']));
  
                  // Total Count Get 
                  $count_sql = '(SELECT e.*,es.status as is_signup,es.reject_by,es.approved_by FROM events e
                INNER JOIN event_age_gender_categories eagc ON e.id = eagc.event_id
                INNER JOIN age_gender_categories agc ON agc.id = eagc.age_gender_id
                LEFT JOIN event_signups es ON e.id = es.event_id AND es.user_id = "'.$_SESSION['user_details']['id'].'"
                WHERE event_date_time > "'.$current_date_time.'" AND (agc.start_year = "'.$user_year.'" OR agc.end_year = "'.$user_year.'") AND e.is_delete = "0" AND agc.gender = "'.$user_details['gender'].'" GROUP BY e.id 
                )UNION ALL
                (
                SELECT e.*,es.status as is_signup,es.reject_by,es.approved_by FROM events e
                INNER JOIN event_grade_categories egc ON egc.event_id = e.id
                INNER JOIN grades g ON g.id = egc.grade_id
                LEFT JOIN event_signups es ON e.id = es.event_id AND es.user_id = "'.$_SESSION['user_details']['id'].'"
                WHERE event_date_time > "'.$current_date_time.'" AND e.is_delete = "0" AND "'.$user_details['grade'].'" between g.start_grade and g.end_grade GROUP BY e.id) ORDER BY event_date_time ASC ';
                $count_result = mysqli_query($conn, $count_sql);
                $count_data = mysqli_num_rows($count_result);
                 // $count_data = mysqli_fetch_assoc($count_result);
              }
              ?>
              <span class="info-box-number"><?php echo (isset($count_data) && $count_data != "") ? $count_data : "0"; ?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>

        <div class="clearfix hidden-md-up"></div>

        <div class="col-12 col-sm-6 col-md-3">
          <div class="info-box mb-3">
            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-calendar-check"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Total Past Events</span>
              <?php
              $current_date_time = date('Y-m-d H:i:s');
              if($user_data['user_type_name'] == "Administrator" || $user_data['user_type_name'] == "Core Captain"){
                  // Total Count Get 
                  $count_sql = 'SELECT COUNT(*) as count FROM `events` WHERE event_date_time < "'.$current_date_time.'" AND is_delete = "0" AND `title` LIKE "%'.$requestField['search'].'%"';
                  $count_result = mysqli_query($conn, $count_sql);
                  $count_data = mysqli_fetch_assoc($count_result);
                  $count_data1 = $count_data['count'];
  
              }else if($user_data['user_type_name'] == "House Captain"){
                   // Total Count Get 
                  $count_sql = 'SELECT COUNT(*) as count FROM `events` WHERE event_date_time < "'.$current_date_time.'" AND is_delete = "0" AND `title` LIKE "%'.$requestField['search'].'%"';
                  $count_result = mysqli_query($conn, $count_sql);
                  $count_data = mysqli_fetch_assoc($count_result);
                  $count_data1 = $count_data['count'];
              }else{
                $student_sql = 'SELECT dob,gender,class as grade FROM `users`WHERE id = "'.$_SESSION['user_details']['id'].'"';
                $result = mysqli_query($conn, $student_sql);
                $user_details = mysqli_fetch_assoc($result);
                $user_year = date("Y", strtotime($user_details['dob']));
  
                  // Total Count Get
                $count_sql = '(SELECT e.*,es.status as is_signup,es.reject_by,es.approved_by FROM events e
                INNER JOIN event_age_gender_categories eagc ON e.id = eagc.event_id
                INNER JOIN age_gender_categories agc ON agc.id = eagc.age_gender_id
                LEFT JOIN event_signups es ON e.id = es.event_id AND es.user_id = "'.$_SESSION['user_details']['id'].'"
                WHERE event_date_time < "'.$current_date_time.'" AND (agc.start_year = "'.$user_year.'" OR agc.end_year = "'.$user_year.'") AND e.is_delete = "0" AND agc.gender = "'.$user_details['gender'].'" GROUP BY e.id 
                )UNION ALL
                (
                SELECT e.*,es.status as is_signup,es.reject_by,es.approved_by FROM events e
                INNER JOIN event_grade_categories egc ON egc.event_id = e.id
                INNER JOIN grades g ON g.id = egc.grade_id
                LEFT JOIN event_signups es ON e.id = es.event_id AND es.user_id = "'.$_SESSION['user_details']['id'].'"
                WHERE event_date_time < "'.$current_date_time.'" AND e.is_delete = "0" AND "'.$user_details['grade'].'" between g.start_grade and g.end_grade GROUP BY e.id) ORDER BY event_date_time ASC ';
                $count_result = mysqli_query($conn, $count_sql);
                $count_data1 = mysqli_num_rows($count_result);
              }
              ?>
              <span class="info-box-number"><?php echo (isset($count_data1) && $count_data1 != "") ? $count_data1 : "0"; ?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <!-- /.row -->
      <!-- <div class="edit-btn">
        <a href="#" data-toggle="modal" data-target="#myModal">Edit</a>
      </div> -->
      
    </div>
    <!--/. container-fluid -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php 
include 'include/footer.php';
?>
<?php 
$sql = 'SELECT * FROM `user_interested_categories` WHERE user_id = "'.$_SESSION['user_details']['id'].'"';
$result = mysqli_query($conn, $sql);
$count = mysqli_num_rows($result);
if($count <= 0){

?>
<script>
$('#myModal').modal({
    backdrop: 'static',
    keyboard: false
});
</script>
<?php } ?>