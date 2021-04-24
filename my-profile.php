<?php
include 'include/session_check.php';
$title = "My Profile";
$pageTitle = "My Profile";
include 'include/header.php';
include 'include/header-navigation.php';
include 'include/navigation.php';
if(isset($_GET['id']) && $_GET['id'] != ''){
  $sql = 'SELECT u.*,h.name as house_name FROM `users` u LEFT JOIN houses h ON u.house_id = h.id WHERE u.id = "'.$_GET['id'].'"';
}else{
  $sql = 'SELECT u.*,h.name as house_name FROM `users` u LEFT JOIN houses h ON u.house_id = h.id WHERE u.id = "'.$_SESSION['user_details']['id'].'"';
}
//$sql = 'SELECT u.*,h.name as house_name FROM `users` u LEFT JOIN houses h ON u.house_id = h.id WHERE u.id = "'.$_SESSION['user_details']['id'].'"';
$result = mysqli_query($conn, $sql);
$user_data = mysqli_fetch_assoc($result);
date_default_timezone_set(date_default_timezone_get ());
function facebook_time_ago($timestamp)
{
    $time_ago = strtotime($timestamp);
    $current_time = time();

    if ($current_time > $time_ago) {
          $time_difference = $current_time - $time_ago;  
        // $time_difference = $time_ago - $current_time;
        $seconds = $time_difference;
        $minutes      = round($seconds / 60);           // value 60 is seconds  
        $hours           = round($seconds / 3600);           //value 3600 is 60 minutes * 60 sec  
        $days          = round($seconds / 86400);          //86400 = 24 * 60 * 60;  
        $weeks          = round($seconds / 604800);          // 7*24*60*60;  
        $months          = round($seconds / 2629440);     //((365+365+365+365+366)/5/12)*24*60*60  
        $years          = round($seconds / 31553280);     //(365+365+365+365+366)/5 * 24 * 60 * 60  
        if ($seconds <= 60) {
            return "Just Now";
        } else if ($minutes <= 60) {
            if ($minutes == 1) {
                return "one minute ago";
            } else {
                return "$minutes minutes ago";
            }
        } else if ($hours <= 24) {
            if ($hours == 1) {
                return "an hour ago";
            } else {
                return "$hours hrs ago";
            }
        } else if ($days <= 7) {
            if ($days == 1) {
                return "yesterday";
            } else {
                return "$days days ago";
            }
        } else if ($weeks <= 4.3) //4.3 == 52/12  
        {
            if ($weeks == 1) {
                return "a week ago";
            } else {
                return "$weeks weeks ago";
            }
        } else if ($months <= 12) {
            if ($months == 1) {
                return "a month ago";
            } else {
                return "$months months ago";
            }
        } else {
            if ($years == 1) {
                return "one year ago";
            } else {
                return "$years years ago";
            }
        }
    } else {
        //   $time_difference = $current_time - $time_ago;  
        $time_difference = $time_ago - $current_time;
        $seconds = $time_difference;
        $minutes      = round($seconds / 60);           // value 60 is seconds  
        $hours           = round($seconds / 3600);           //value 3600 is 60 minutes * 60 sec  
        $days          = round($seconds / 86400);          //86400 = 24 * 60 * 60;  
        $weeks          = round($seconds / 604800);          // 7*24*60*60;  
        $months          = round($seconds / 2629440);     //((365+365+365+365+366)/5/12)*24*60*60  
        $years          = round($seconds / 31553280);     //(365+365+365+365+366)/5 * 24 * 60 * 60  
        if ($seconds <= 60) {
            return "Just Now";
        } else if ($minutes <= 60) {
            if ($minutes == 1) {
                return "Starts in a minute";
            } else {
                return "Starts in $minutes minutes";
            }
        } else if ($hours <= 24) {
            if ($hours == 1) {
                return "Starts in an hour";
            } else {
                return "Starts in $hours hrs";
            }
        } else if ($days <= 7) {
            if ($days == 1) {
                return "Starting tomorrow";
            } else {
                return "Starting after $days days";
            }
        } else if ($weeks <= 4.3) //4.3 == 52/12  
        {
            if ($weeks == 1) {
                return "Starting after a week";
            } else {
                return "Starting after $weeks weeks";
            }
        } else if ($months <= 12) {
            if ($months == 1) {
                return "Starting after a month";
            } else {
                return "Starting after $months months";
            }
        } else {
            if ($years == 1) {
                return "Starting after one year";
            } else {
                return "Starting after $years year";
            }
        }
    }
}


?>
<style>
.accordion-label label {
    padding-right: 50px;
}
</style>
<link rel="stylesheet" href="assets/web/css/custom.css">
<script src="assets/web/local_assets/js/my-profile.js"></script>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <!-- <h1 class="m-0 text-dark"><?php //echo (isset($pageTitle) && $pageTitle != null) ? $pageTitle : ''; ?></h1> -->
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
              <?php echo $_SESSION['FLASH_SUCCESS_FLAG']; ?>
              <?php unset ($_SESSION['FLASH_SUCCESS_FLAG']); ?>
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
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- jquery validation -->
          <div class="card card-primary">
            <div class="card-header">
            <?php 
            if(isset($_GET['id']) && $_GET['id'] != ''){
              $sql = 'SELECT * FROM `events`WHERE id = "'.$_GET['event_id'].'"';
              $result = mysqli_query($conn, $sql);
              $event_data = mysqli_fetch_assoc($result);
              ?>
            <h3 class="card-title"><?php echo $event_data['title']; ?></h3>
              <?php
            }else{
            ?>
              <h3 class="card-title">Profile</h3>
            <?php } ?>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form id="profile_details" name="profile_details">
              <div class="card-body">
                  <div class="form-group" id="image-group-div">
                    <label for="name">Image&nbsp;</label>
                    <a href="javascript:void(0);" onclick="performClick('theFile');">
                      <div class="col-12 col-md-12">
                        <?php
                        if (isset($user_data['image_url'])) {
                        ?>
                          <div class="celebrity-pic">
                            <img id="profileImage" class="profile-user-img img-fluid img-circle" src="<?php echo BASE_URL . $user_data['image_url']; ?>" alt="">
                          </div>
                        <?php
                        } else {
                        ?>
                          <div class="celebrity-pic">
                            <img id="profileImage" class="profile-user-img img-fluid img-circle" src="<?php echo DEFAULT_IMAGE; ?>" alt="">
                          </div>
                        <?php }
                        ?>

                      </div>
                    </a>
                    <input type="file" id="theFile" name="fileName" onchange="return checkExtansion();" style="display:none" />
                    <span id="fileName-error-1" class="form-text"></span>
                  </div>
                <div class="row">
                  <div class="col-12 col-sm-12 col-md-6">
                    <div id="form-group-grade" class="form-group">
                      <label for="grade">Grade&nbsp;</label>
                      <input type="text" name="grade" class="form-control" id="grade" placeholder="Please enter Grade" value="<?php echo (isset($user_data['class']) && $user_data['class'] != "") ? $user_data['class'] : ""; ?>" readonly>
                      
                    </div>
                  </div>
                  <div class="col-12 col-sm-12 col-md-6">
                    <div id="form-group-gender" class="form-group">
                      <label for="gender">Gender&nbsp;</label>
                      <input type="text" name="gender" class="form-control" id="gender" placeholder="Enter Gender"  value="<?php echo (isset($user_data['gender']) && $user_data['gender'] != "") ? $user_data['gender'] : ""; ?>" readonly>
                    
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12 col-sm-12 col-md-6">
                    <div id="form-group-house" class="form-group">
                      <label for="house">House&nbsp;</label>
                      <input type="text" name="house" class="form-control" id="house" placeholder="Please enter house" value="<?php echo (isset($user_data['house_name']) && $user_data['house_name'] != "") ? $user_data['house_name'] : ""; ?>" readonly>
                     
                    </div>
                  </div>
                  <div class="col-12 col-sm-12 col-md-6">
                    <div id="form-group-date_of_bith" class="form-group">
                      <label for="date_of_bith">Date Of Birth&nbsp;</label>
                      <input type="text" name="date_of_bith" class="form-control" id="date_of_bith" placeholder="Enter Date Of Birth"  value="<?php echo (isset($user_data['dob']) && $user_data['dob'] != "") ? date("d/m/Y", strtotime($user_data['dob'])) : ""; ?>" readonly>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12 col-sm-12 col-md-6">
                    <div id="form-group-phone" class="form-group">
                      <label for="phone">Phone&nbsp;<span class="is-required">*</span></label>
                      <input type="text" name="phone" class="form-control" id="phone" placeholder="Please enter phone" value="<?php echo (isset($user_data['phone']) && $user_data['phone'] != "") ? $user_data['phone'] : ""; ?>">
                      <small id="phone-error" class="form-text text-muted"></small>
                    </div>
                  </div>
                  <div class="col-12 col-sm-12 col-md-6">
                    <div id="form-group-p_phone" class="form-group">
                      <label for="p_phone">Parent Phone&nbsp;<span class="is-required">*</span></label>
                      <input type="text" name="p_phone" class="form-control" id="p_phone" placeholder="Enter Parent Phone"  value="<?php echo (isset($user_data['parent_phone']) && $user_data['parent_phone'] != "") ? $user_data['parent_phone'] : ""; ?>" >
                      <small id="p_phone-error" class="form-text text-muted"></small>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12 col-sm-12 col-md-6">
                    <div id="form-group-email" class="form-group">
                      <label for="email">Email Address&nbsp;</label>
                      <input type="text" name="email" class="form-control" id="email" placeholder="Please enter email" value="<?php echo (isset($user_data['email_id']) && $user_data['email_id'] != "") ? $user_data['email_id'] : ""; ?>" readonly>
                    </div>
                  </div>
                </div>

              </div>
              <!-- /.card-body -->
              <?php
              if(isset($_GET['id']) && $_GET['id'] != ''){}else{
              ?>
              <div class="card-footer">
                <button type="submit" id="submitprofile" name="btn-admin-change-credentials" onclick="return validatePage();" class="btn btn-primary">SUBMIT</button>
              </div>
              <?php } ?>
            </form>
            <br>
            <?php
              if(isset($_GET['id']) && $_GET['id'] != ''){}else{
            ?>
            <div class="row">
              <div class="col-12 col-sm-12 col-md-12">
              	<!-- <div class="edit-btn text-right p-4">
                  <a href="#" data-toggle="modal" data-target="#myModal">EDIT</a>
                </div> -->
            <form class="accordion">
              <div id="accordion" role="tablist" aria-multiselectable="true">

          <!-- Accordion Item 1 -->
          <div class="card">
            <div class="card-header" role="tab" id="accordionHeadingOne">
              <div class="mb-0 row">
                <div class="col-12 no-padding accordion-head">
                  <a data-toggle="collapse" data-parent="#accordion" href="#accordionBodyOne" aria-expanded="false" aria-controls="accordionBodyOne"
                    class="collapsed ">
                    <i class="fa fa-angle-up" aria-hidden="true"></i>
                    <h3 class="accordion-title">Events You Are Interested In</h3>
                  </a>
                </div>
              </div>
            </div>

            <div id="accordionBodyOne" class="collapse" role="tabpanel" aria-labelledby="accordionHeadingOne" aria-expanded="false" data-parent="accordion">
            <div class="edit-btn text-right pt-1">
                  <a href="#" data-toggle="modal" data-target="#myModal">EDIT</a>
                </div>
              <div class="card-block col-12">
                <div class="form-group accordion-label clearfix">
                <?php 
                // Category get Code
                $get_category_query = 'SELECT uic.id as interested_id,c.id as category_id,c.name FROM `user_interested_categories` uic LEFT JOIN categories c ON c.id = uic.category_id WHERE uic.user_id = "'.$_SESSION['user_details']['id'].'"';
                $get_category_result = mysqli_query($conn, $get_category_query);
                $category = array();
                while($get_category = mysqli_fetch_assoc($get_category_result)){
                    
              ?>
                  <label for="age_gender_categories1"><?php echo $get_category['name']; ?></label>
              <?php } ?>
                </div>
              </div>
            </div>
          </div>
          <?php 
            $sql = 'SELECT u.id as user_ID,h.name as house_name,h.id as house_id,ut.name as user_type_name FROM `users` u LEFT JOIN houses h ON u.house_id = h.id LEFT JOIN user_type ut ON ut.id = u.user_type_id WHERE u.id = "'.$_SESSION['user_details']['id'].'"';
            $result = mysqli_query($conn, $sql);
            $user_data = mysqli_fetch_assoc($result);
          ?>
          


        </div>
            </form>
          
          </div>
          </div>
          <?php 
          date_default_timezone_set('Asia/Kolkata');
          $current_date_time = date('Y-m-d H:i:s');
          $count_singup_event_query = 'SELECT COUNT(*) as count FROM `event_signups` es LEFT JOIN events e ON e.id = es.event_id LEFT JOIN categories c ON c.id = e.category_id WHERE es.user_id = "'.$_SESSION['user_details']['id'].'" AND e.is_delete = "0" AND e.event_date_time > "'.$current_date_time.'"';
          $count_singup_event_result = mysqli_query($conn, $count_singup_event_query);
          $count_event_data = mysqli_fetch_assoc($count_singup_event_result)
          ?>
          
          <div class="row">
          	<div class="col-md-12">
          		<div class="head-title mobile-head-title p-4">
          			<h4>UPCOMING EVENTS YOU ARE PARTICIPATING IN (<?php echo $count_event_data['count']; ?>)</h4>
          		</div>
          	</div>
            <?php 
          // Category get Code
            $singup_event_query = 'SELECT e.*,es.status as is_singup,es.reject_by,c.name as cat_name FROM `event_signups` es LEFT JOIN events e ON e.id = es.event_id LEFT JOIN categories c ON c.id = e.category_id WHERE user_id = "'.$_SESSION['user_details']['id'].'" AND e.is_delete = "0" AND e.event_date_time > "'.$current_date_time.'"';
            $singup_event_result = mysqli_query($conn, $singup_event_query);
            $singup_array = array();
            while($event_data = mysqli_fetch_assoc($singup_event_result)){
              // echo"<pre>";
              // print_r($event_data);exit;
          
          ?>

          	<div class="col-md-6">
          		<div class="list-view p-3">
          		<div class="list-title">
          			<h5><?php echo date('l, d M Y', strtotime($event_data['event_date'])); ?></h5>
          		</div>
				<a href="event-details.php?id=<?php echo MD5($event_data['id']); ?>">
          		<div class="list-box clearfix">
          			<div class="list-left float-left">
          				<h6><?php echo $event_data['title']; ?></h6>
          			</div>
          			<div class="list-right float-right">
          				<h3><?php echo date("g:i A", strtotime($event_data['event_time'])); ?></h3>
          			</div>
          		</div>
				</a>
          		<div class="bottom-list clearfix">
          			<div class="list-button my-profile-list float-left mobile-float-none">
          				<a><?php echo $event_data['cat_name'] ?></a>
          			</div>
                
          			<div class="list-caption mobile-lest-caption float-right mobile-float-none">
          				<p><?php echo facebook_time_ago($event_data['event_date_time']); ?></p>
          			</div>
              </div>
        <?php 
        if($event_data['is_singup'] == '0'){
        ?>
				<div class="bottom-green-text text-center">
				  <p>You Have Signed Up But Awaiting Approval</p>
        </div>
        <?php }else if($event_data['is_singup'] == '2'){ ?>
				<div class="bottom-green-text text-center">
          <?php 
          if($event_data['reject_by'] == $_SESSION['user_details']['id']){
          ?>
          <p style="color: red;">You have withdrawn your name from this event</p>
          <?php }else{ ?>
            <p style="color: red;">Rejected Your Approval.</p>
          <?php } ?>
        </div>
        <?php }else{ ?>
          <div class="bottom-green-text text-center">
            <p style="color: blue;">You Have Not Signed Up Yet.</p>
          </div>
        <?php } ?>
          	  </div>
          	</div>
          <?php } ?>
          		
          </div>
          </div>
          <!-- /.card -->
        </div>
        <?php  } ?>
        <!--/.col (left) -->
        <!-- right column -->
        <div class="col-md-6">

        </div>
        <!--/.col (right) -->
      </div>
      <!-- /.row -->
    </div>
    <!--/. container-fluid -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->


<?php 
include 'include/footer.php';
?>
