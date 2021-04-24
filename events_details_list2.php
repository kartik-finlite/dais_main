<?php
include 'include/session_check.php';
$title = "Event Details List";
// $pageTitle = "Event Details";
include 'include/header.php';
include 'include/header-navigation.php';
include 'include/navigation.php';
if(isset($_GET['id']) && $_GET['id'] != ''){
  $sql = 'SELECT e.*,usd.id as submit_id,usd.file_name FROM `events` e LEFT JOIN user_submitted_docs usd ON e.id = usd.event_id AND usd.user_id = "'.$_SESSION['user_details']['id'].'" WHERE MD5(e.id) = "'.$_GET['id'].'"';
  //$sql = 'SELECT e.*,c.name as cat_name FROM `events` e LEFT JOIN categories c ON e.category_id = c.id WHERE MD5(e.id) = "'.$_GET['id'].'"';
  $result = mysqli_query($conn, $sql);
  $event_data = mysqli_fetch_assoc($result);

  $check_query = 'SELECT * FROM `event_signups` WHERE event_id = "'.$event_data['id'].'"  AND user_id = "'.$_SESSION['user_details']['id'].'"';
  $check_result = mysqli_query($conn, $check_query);
  $check_data = mysqli_fetch_assoc($check_result);
}


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

$sql = 'SELECT u.id as user_ID,h.name as house_name,h.id as house_id,ut.name as user_type_name FROM `users` u LEFT JOIN houses h ON u.house_id = h.id LEFT JOIN user_type ut ON ut.id = u.user_type_id WHERE u.id = "'.$_SESSION['user_details']['id'].'"';
$result = mysqli_query($conn, $sql);
$user_data = mysqli_fetch_assoc($result);


?>
<style>
  .custom-file-input:lang(en)~.custom-file-label::after
  {
    content:"Upload";
    background: #007bff;
    color: #fff;
  }
  #myModal
  {
    display: none !important;
  }
   .btn
  {
    padding-left:2.5rem;
    padding-right:2.5rem;
    text-transform:uppercase;
  }
  /******************* start event details list css ******************/
.mb-0 > a {
  display: block;
  position: relative;
}
.mb-0 > a:after {
  content: "\f078"; /* fa-chevron-down */
  font-family: 'FontAwesome';
  position: absolute;
  right: 0;
  font-size:13px;
}
.mb-0 > a[aria-expanded="true"]:after {
  content: "\f077"; /* fa-chevron-up */
}
#approve-modal .modal-dialog {
    -webkit-transform: translate(0,-50%);
    -o-transform: translate(0,-50%);
    transform: translate(0,-50%);
    top: 50%;
    margin: 0px auto;
}
/******************* end event details list css ******************/
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
<link rel="stylesheet" href="assets/web/css/custom.css">
<script src="assets/web/local_assets/js/event_details.js"></script>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark"><?php echo (isset($pageTitle) && $pageTitle != null) ? $pageTitle : ''; ?></h1>
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
              <h3 class="card-title">Event Details List</h3>
            </div>
			<form>
			<div class="row p-2">
			<div class="col-md-6">
			      <label for="category_name" class="label-text">Enter Internal Notes&nbsp;<span class="is-required">*</span></label>
                  <textarea class="form-control" rows="3" name="event_notes" placeholder="Write Internal Notes ...">multiple pdf</textarea>
			</div>
			<div class="col-sm-6">
      <?php
        if(isset($_GET['id']) && $_GET['id'] != ''){
          // council get
          $council_query = 'SELECT u.id,u.first_name,u.middle_name,u.last_name FROM `event_council_members` ecm LEFT JOIN users u ON ecm.council_member = u.id WHERE ecm.event_id = "'.$event_data['id'].'"';
          $council_result = mysqli_query($conn, $council_query);
          while($council_data = mysqli_fetch_assoc($council_result)){
              $council_array[] = $council_data;
          }
          //print_r($council_array);exit;
        }
      ?>
          <!-- checkbox -->
          <label class="label-text"> Select Council Members</label>
          <div class="form-group clearfix label-with-checkbox">
          <?php 
        
            $sql = 'SELECT u.id as user_ID,h.name as house_name,h.id as house_id,ut.name as user_type_name FROM `users` u LEFT JOIN houses h ON u.house_id = h.id LEFT JOIN user_type ut ON ut.id = u.user_type_id WHERE u.id = "'.$_SESSION['user_details']['id'].'"';
            $result = mysqli_query($conn, $sql);
            $user_data = mysqli_fetch_assoc($result);
            if($user_data['user_type_name'] == "Administrator" || $user_data['user_type_name'] == "Core Captain"){
                $house_query = 'SELECT u.id,u.first_name,u.middle_name,u.last_name,h.name as house_name FROM `users` u LEFT JOIN user_type ut ON u.user_type_id = ut.id LEFT JOIN houses h ON h.id = u.house_id WHERE ut.name = "House Captain"';
            }else{
                $house_query = 'SELECT u.id,u.first_name,u.middle_name,u.last_name,h.name as house_name FROM `users` u LEFT JOIN user_type ut ON u.user_type_id = ut.id LEFT JOIN houses h ON h.id = u.house_id WHERE ut.name = "House Captain" AND h.id = "'.$user_data['house_id'].'"';
            }
            $result = mysqli_query($conn, $house_query);
            $i=1;
            while($house_data = mysqli_fetch_assoc($result)){
              
            ?>
            <div class="icheck-primary d-inline">
                <input type="checkbox" name="council_members[]" <?php if(isset($_GET['id']) && $_GET['id'] != ''){  if(!empty($council_array)){ if(array_search($house_data['id'], array_column($council_array, 'id')) !== false) { echo 'checked';} } } ?> value="<?php echo $house_data['id']; ?>" id="council_members<?php echo $i; ?>">
                <label for="council_members<?php echo $i; ?>">
                  <?php echo $house_data['first_name']."(".$house_data['house_name'].")"; ?>
                </label>
            </div>
            <?php } ?>
            </div>
        </div>
			</div>
			<div class="card-footer">
			<button type="submit" id="" class="btn btn-primary">UPDATE EVENT</button>
			</div>
			</form>
            <div id="accordion" class="multiple-accordion-box">
              <div class="row">
                <div class="col-md-6">
          <div class="card">
    <div class="card-header" id="heading-1">
      <h5 class="mb-0">
        <a role="button" data-toggle="collapse" href="#collapse-1" aria-expanded="true" aria-controls="collapse-1">
         TIGERS (1)
        </a>
      </h5>
    </div>
    <div id="collapse-1" class="collapse show" aria-labelledby="heading-1">
      <div class="card-body">

        <div id="accordion-1">
          <div class="card">
            <div class="card-header" id="heading-1-1">
              <h5 class="mb-0">
                <a class="collapsed" role="button" data-toggle="collapse" href="#collapse-1-1" aria-expanded="false" aria-controls="collapse-1-1">
                  CONFIRMED PARTICIPANTS (2)
                </a>
              </h5>
            </div>
            <div id="collapse-1-1" class="collapse" aria-labelledby="heading-1-1">
              <div class="card-body">
                <div class="comment-list-box" id="change-confirm-box">
				<ul class="comment-list">
				<li><a class="comment-user-name">Shubham Prajapati</a></li>
				<li class="float-right"><a class="comment-user-close" data-toggle="modal" data-target="#reject-modal" id="change-confirm"><i class="fa fa-times"></i></a></li>
				</ul>
				</div>
				 <div class="comment-list-box">
				<ul class="comment-list">
				<li><a class="comment-user-name">Nikunj Suthar</a></li>
				<li class="float-right"><a class="comment-user-close" data-toggle="modal" data-target="#reject-modal"><i class="fa fa-times"></i></a></li>
				</ul>
				</div>
				<div class="comment-list-box">
				<ul class="comment-list">
				<li><a class="comment-user-name">Hardik Khatri</a></li>
				<li class="float-right"><a class="comment-user-close" data-toggle="modal" data-target="#reject-modal"><i class="fa fa-times"></i></a></li>
				</ul>
				</div>
				<div class="comment-list-box">
				<ul class="comment-list">
				<li><a class="comment-user-name">Kaushik Patel</a></li>
				<li class="float-right"><a class="comment-user-close" data-toggle="modal" data-target="#reject-modal"><i class="fa fa-times"></i></a></li>
				</ul>
				</div>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-header" id="heading-1-2">
              <h5 class="mb-0">
                <a class="collapsed" role="button" data-toggle="collapse" href="#collapse-1-2" aria-expanded="false" aria-controls="collapse-1-2">
                  REJECTED PARTICIPANTS (0)
                </a>
              </h5>
            </div>
            <div id="collapse-1-2" class="collapse" aria-labelledby="heading-1-2">
              <div class="card-body">
                <div class="comment-list-box reject" id="change-reject-box">
				<ul class="comment-list">
				<li><a class="comment-user-name">Shubham Prajapati</a></li>
				<li class="float-right"><a class="comment-user-right" data-toggle="modal" data-target="#approve-modal" id="change-reject"><i class="fa fa-check"></i></a></li>
				</ul>
				</div>
				 <div class="comment-list-box reject">
				<ul class="comment-list">
				<li><a class="comment-user-name">Nikunj Suthar</a></li>
				<li class="float-right"><a class="comment-user-right" data-toggle="modal" data-target="#approve-modal"><i class="fa fa-check"></i></a></li>
				</ul>
				</div>
				<div class="comment-list-box reject">
				<ul class="comment-list">
				<li><a class="comment-user-name">Hardik Khatri</a></li>
				<li class="float-right"><a class="comment-user-right" data-toggle="modal" data-target="#approve-modal"><i class="fa fa-check"></i></a></li>
				</ul>
				</div>
				<div class="comment-list-box reject">
				<ul class="comment-list">
				<li><a class="comment-user-name">Kaushik Patel</a></li>
				<li class="float-right"><a class="comment-user-right" data-toggle="modal" data-target="#approve-modal"><i class="fa fa-check"></i></a></li>
				</ul>
				</div>
              </div>
            </div>
          </div>
        </div>      
      
      </div>
    </div>
          </div>
		       <div class="card">
    <div class="card-header" id="heading-3">
      <h5 class="mb-0">
        <a class="collapsed" role="button" data-toggle="collapse" href="#collapse-3" aria-expanded="false" aria-controls="collapse-3">
          TIGERS (3)
        </a>
      </h5>
    </div>
    <div id="collapse-3" class="collapse" aria-labelledby="heading-3">
      <div class="card-body">
         <div id="accordion-3">
          <div class="card">
            <div class="card-header" id="heading-3-1">
              <h5 class="mb-0">
                <a class="collapsed" role="button" data-toggle="collapse" href="#collapse-3-1" aria-expanded="false" aria-controls="collapse-3-1">
                  CONFIRMED PARTICIPANTS (2)
                </a>
              </h5>
            </div>
            <div id="collapse-3-1" class="collapse" aria-labelledby="heading-3-1">
              <div class="card-body">
                <div class="comment-list-box">
				<ul class="comment-list">
				<li><a class="comment-user-name">Shubham Prajapati</a></li>
				<li class="float-right"><a class="comment-user-close" data-toggle="modal" data-target="#reject-modal"><i class="fa fa-times"></i></a></li>
				</ul>
				</div>
				 <div class="comment-list-box">
				<ul class="comment-list">
				<li><a class="comment-user-name">Nikunj Suthar</a></li>
				<li class="float-right"><a class="comment-user-close" data-toggle="modal" data-target="#reject-modal"><i class="fa fa-times"></i></a></li>
				</ul>
				</div>
				<div class="comment-list-box">
				<ul class="comment-list">
				<li><a class="comment-user-name">Kaushik Patel</a></li>
				<li class="float-right"><a class="comment-user-close" data-toggle="modal" data-target="#reject-modal"><i class="fa fa-times"></i></a></li>
				</ul>
				</div>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-header" id="heading-3-2">
              <h5 class="mb-0">
                <a class="collapsed" role="button" data-toggle="collapse" href="#collapse-3-2" aria-expanded="false" aria-controls="collapse-3-2">
                  REJECTED PARTICIPANTS (0)
                </a>
              </h5>
            </div>
            <div id="collapse-3-2" class="collapse" aria-labelledby="heading-3-2">
              <div class="card-body">
                <div class="comment-list-box reject">
				<ul class="comment-list">
				<li><a class="comment-user-name">Shubham Prajapati</a></li>
				<li class="float-right"><a class="comment-user-right" data-toggle="modal" data-target="#approve-modal"><i class="fa fa-check"></i></a></li>
				</ul>
				</div>
				 <div class="comment-list-box reject">
				<ul class="comment-list">
				<li><a class="comment-user-name">Nikunj Suthar</a></li>
				<li class="float-right"><a class="comment-user-right" data-toggle="modal" data-target="#approve-modal"><i class="fa fa-check"></i></a></li>
				</ul>
				</div>
				<div class="comment-list-box reject">
				<ul class="comment-list">
				<li><a class="comment-user-name">Kaushik Patel</a></li>
				<li class="float-right"><a class="comment-user-right" data-toggle="modal" data-target="#approve-modal"><i class="fa fa-check"></i></a></li>
				</ul>
				</div>
              </div>
            </div>
          </div>
        </div> 
      </div>
    </div>
          </div>
		   <div class="card">
    <div class="card-header" id="heading-5">
      <h5 class="mb-0">
        <a class="collapsed" role="button" data-toggle="collapse" href="#collapse-5" aria-expanded="false" aria-controls="collapse-5">
          TIGERS (5)
        </a>
      </h5>
    </div>
    <div id="collapse-5" class="collapse" aria-labelledby="heading-5">
      <div class="card-body">
        <p>
		 It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
		</p>
      </div>
    </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card">
    <div class="card-header" id="heading-2">
      <h5 class="mb-0">
        <a class="collapsed" role="button" data-toggle="collapse" href="#collapse-2" aria-expanded="false" aria-controls="collapse-2">
          TIGERS (2)
        </a>
      </h5>
    </div>
    <div id="collapse-2" class="collapse" aria-labelledby="heading-2">
      <div class="card-body">
        <div id="accordion-2">
          <div class="card">
            <div class="card-header" id="heading-2-1">
              <h5 class="mb-0">
                <a class="collapsed" role="button" data-toggle="collapse" href="#collapse-2-1" aria-expanded="false" aria-controls="collapse-2-1">
                  CONFIRMED PARTICIPANTS (2)
                </a>
              </h5>
            </div>
            <div id="collapse-2-1" class="collapse" aria-labelledby="heading-2-1">
              <div class="card-body">
                <div class="comment-list-box">
				<ul class="comment-list">
				<li><a class="comment-user-name">Shubham Prajapati</a></li>
				<li class="float-right"><a class="comment-user-close" data-toggle="modal" data-target="#reject-modal"><i class="fa fa-times"></i></a></li>
				</ul>
				</div>
				 <div class="comment-list-box">
				<ul class="comment-list">
				<li><a class="comment-user-name">Nikunj Suthar</a></li>
				<li class="float-right"><a class="comment-user-close" data-toggle="modal" data-target="#reject-modal"><i class="fa fa-times"></i></a></li>
				</ul>
				</div>
				<div class="comment-list-box">
				<ul class="comment-list">
				<li><a class="comment-user-name">Hardik Khatri</a></li>
				<li class="float-right"><a class="comment-user-close" data-toggle="modal" data-target="#reject-modal"><i class="fa fa-times"></i></a></li>
				</ul>
				</div>
				<div class="comment-list-box">
				<ul class="comment-list">
				<li><a class="comment-user-name">Kaushik Patel</a></li>
				<li class="float-right"><a class="comment-user-close" data-toggle="modal" data-target="#reject-modal"><i class="fa fa-times"></i></a></li>
				</ul>
				</div>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-header" id="heading-2-2">
              <h5 class="mb-0">
                <a class="collapsed" role="button" data-toggle="collapse" href="#collapse-2-2" aria-expanded="false" aria-controls="collapse-2-2">
                  REJECTED PARTICIPANTS (0)
                </a>
              </h5>
            </div>
            <div id="collapse-2-2" class="collapse" aria-labelledby="heading-2-2">
              <div class="card-body">
                <div class="comment-list-box reject">
				<ul class="comment-list">
				<li><a class="comment-user-name">Shubham Prajapati</a></li>
				<li class="float-right"><a class="comment-user-right" data-toggle="modal" data-target="#approve-modal"><i class="fa fa-check"></i></a></li>
				</ul>
				</div>
				 <div class="comment-list-box reject">
				<ul class="comment-list">
				<li><a class="comment-user-name">Nikunj Suthar</a></li>
				<li class="float-right"><a class="comment-user-right" data-toggle="modal" data-target="#approve-modal"><i class="fa fa-check"></i></a></li>
				</ul>
				</div>
				<div class="comment-list-box reject">
				<ul class="comment-list">
				<li><a class="comment-user-name">Hardik Khatri</a></li>
				<li class="float-right"><a class="comment-user-right" data-toggle="modal" data-target="#approve-modal"><i class="fa fa-check"></i></a></li>
				</ul>
				</div>
				<div class="comment-list-box reject">
				<ul class="comment-list">
				<li><a class="comment-user-name">Kaushik Patel</a></li>
				<li class="float-right"><a class="comment-user-right" data-toggle="modal" data-target="#approve-modal"><i class="fa fa-check"></i></a></li>
				</ul>
				</div>
              </div>
            </div>
          </div>
        </div> 
      </div>
    </div>
          </div>
		   <div class="card">
    <div class="card-header" id="heading-4">
      <h5 class="mb-0">
        <a class="collapsed" role="button" data-toggle="collapse" href="#collapse-4" aria-expanded="false" aria-controls="collapse-4">
          TIGERS (4)
        </a>
      </h5>
    </div>
    <div id="collapse-4" class="collapse" aria-labelledby="heading-4">
      <div class="card-body">
               <div id="accordion-4">
          <div class="card">
            <div class="card-header" id="heading-4-1">
              <h5 class="mb-0">
                <a class="collapsed" role="button" data-toggle="collapse" href="#collapse-4-1" aria-expanded="false" aria-controls="collapse-4-1">
                  CONFIRMED PARTICIPANTS (2)
                </a>
              </h5>
            </div>
            <div id="collapse-4-1" class="collapse" aria-labelledby="heading-4-1">
              <div class="card-body">
                <div class="comment-list-box">
				<ul class="comment-list">
				<li><a class="comment-user-name">Shubham Prajapati</a></li>
				<li class="float-right"><a class="comment-user-close" data-toggle="modal" data-target="#reject-modal"><i class="fa fa-times"></i></a></li>
				</ul>
				</div>
				<div class="comment-list-box">
				<ul class="comment-list">
				<li><a class="comment-user-name">Kaushik Patel</a></li>
				<li class="float-right"><a class="comment-user-close" data-toggle="modal" data-target="#reject-modal"><i class="fa fa-times"></i></a></li>
				</ul>
				</div>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-header" id="heading-4-2">
              <h5 class="mb-0">
                <a class="collapsed" role="button" data-toggle="collapse" href="#collapse-4-2" aria-expanded="false" aria-controls="collapse-4-2">
                  REJECTED PARTICIPANTS (0)
                </a>
              </h5>
            </div>
            <div id="collapse-4-2" class="collapse" aria-labelledby="heading-4-2">
              <div class="card-body">
                <div class="comment-list-box reject">
				<ul class="comment-list">
				<li><a class="comment-user-name">Shubham Prajapati</a></li>
				<li class="float-right"><a class="comment-user-right" data-toggle="modal" data-target="#approve-modal"><i class="fa fa-check"></i></a></li>
				</ul>
				</div>
				<div class="comment-list-box reject">
				<ul class="comment-list">
				<li><a class="comment-user-name">Kaushik Patel</a></li>
				<li class="float-right"><a class="comment-user-right" data-toggle="modal" data-target="#approve-modal"><i class="fa fa-check"></i></a></li>
				</ul>
				</div>
              </div>
            </div>
          </div>
        </div> 
      </div>
    </div>
          </div>
		      <div class="card">
    <div class="card-header" id="heading-6">
      <h5 class="mb-0">
        <a class="collapsed" role="button" data-toggle="collapse" href="#collapse-6" aria-expanded="false" aria-controls="collapse-6">
          TIGERS (6)
        </a>
      </h5>
    </div>
    <div id="collapse-6" class="collapse" aria-labelledby="heading-6">
      <div class="card-body">
        <p>
		Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.
		</p>
		<p>
		 It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
		</p>
      </div>
    </div>
          </div>
        </div>
        
        </div>
</div>
      </div>
    </div>
</div>
</div>
  </section>
</div>
<!-- /.content-wrapper -->
   <div class="modal fade modal-center" id="reject-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog reject-modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header reject-header">
        <h5 class="modal-title" id="exampleModalLabel">Reject </h5>
        <!--<button type="button" class="close" data-dismiss="modal" aria-label="Close">
           <span aria-hidden="true">&times;</span>
        </button>-->
      </div>
      <div class="modal-body reject-body text-center">
        <h4 class="reject-text">Are You Sure You Want To Remove User?</h4>
      </div>
      <div class="modal-footer reject-footer">
        <button type="submit" id="" class="btn btn-primary">YES</button>
		<button type="submit" id="" class="btn btn-secondary" data-dismiss="modal">NO</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="approve-modal">
    <div class="modal-dialog reject-modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header reject-header">
                <h5 class="modal-title">Approve</h5>
                <!--<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>-->
            </div>
            <div class="modal-body reject-body text-center">
                <h4 class="reject-text">Switch Kunjan bhavsar Back To Confirmed Participant?</h4>
            </div>
            <div class="modal-footer reject-footer">
                <button type="button" class="btn btn-primary">YES</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">NO</button>
            </div>
        </div>
    </div>
</div>
<?php 
include 'include/footer.php';
?>

<script>
$("input[data-bootstrap-switch]").each(function(){
  $(this).bootstrapSwitch('state', $(this).prop('checked'));
});
</script>
<script>
$(function () {
  bsCustomFileInput.init();
});
</script>
<script>
$( function(){
  
  $( "#change-confirm" ).on( "click", function( event ){
     
    event.preventDefault();
   
    $( "#change-confirm-box" ).css( { "background-color" : "#83f683" } );
  } );
} );

$( function(){
  
  $( "#change-reject" ).on( "click", function( event ){
     
    event.preventDefault();
   
    $( "#change-reject-box" ).css( { "background-color" : "#1a55a1" } );
  } );
} );
</script>



