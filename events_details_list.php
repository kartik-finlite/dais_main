<?php
include 'include/session_check.php';
$title = "Event Details List";
// $pageTitle = "Event Details";
include 'include/header.php';
include 'include/header-navigation.php';
include 'include/navigation.php';
include 'include/function.php';
if (isset($_POST['signup_button'])) {
  $limit_event_check = limit_event_check($_SESSION['user_details']['id']);
  if (!empty($limit_event_check)) {
    $_SESSION['FLASH_SUCCESS_FLAG'] = $limit_event_check;
  } else {
    $event_query = 'SELECT id FROM `events` WHERE MD5(id) = "' . $_GET['id'] . '"';
    $event_result = mysqli_query($conn, $event_query);
    $event_id = mysqli_fetch_assoc($event_result);
    if (isset($_FILES["waiver_from"]["name"]) && $_FILES["waiver_from"]["name"] != '') {
      $path = 'upload/waiver_form/' . $_SESSION['user_details']['id'];
      if (!file_exists($path)) {
        mkdir($path, 0777, true);
        chmod($path, 0777);
      }
      $path_parts = pathinfo($_FILES["waiver_from"]["name"]);
      $imageName = time() . "." . $path_parts['extension'];
      move_uploaded_file($_FILES['waiver_from']['tmp_name'], "$path/$imageName");
      $add_query = 'INSERT INTO user_submitted_docs SET event_id= "' . $event_id['id'] . '",user_id = "' . $_SESSION['user_details']['id'] . '",file_name="' . $imageName . '",created_date = "' . date('Y-m-d H:i:s') . '"';
      $result = mysqli_query($conn, $add_query);
      if ($result) {
        $add_query = 'INSERT INTO event_signups SET event_id= "' . $event_id['id'] . '",user_id = "' . $_SESSION['user_details']['id'] . '",status = "0",created_date = "' . date('Y-m-d H:i:s') . '"';
        $result = mysqli_query($conn, $add_query);

        $sql = 'SELECT * FROM `users`WHERE id = "'.$_SESSION['user_details']['id'].'"';
        $result = mysqli_query($conn, $sql);
        $user_data = mysqli_fetch_assoc($result);
        Changes 
        get event name
        $event_name_query = 'SELECT * FROM `events` WHERE id="'.$event_id['id'].'"';
        $event_name_result = mysqli_query($conn, $event_name_query);
        $event_name_data = mysqli_fetch_assoc($event_name_result);

        //$title3 ="Hello has requested to withdraw their name from ".$event_name_data['title'].". Click here to approve or deny this request.";
        //$title3 = $user_data['first_name']." ".$user_data['last_name']." has requested to Sign up in ".$event_name_data['title'].".";
        $title3 = $user_data['first_name']." ".$user_data['last_name']." has requested to sign up for ".$event_name_data['title'].".";
        $message3['title'] = 'DAIS Events';
        $message3['sound'] = 'default';
        // $message['body'] = "Event Date:".date("Y-m-d", strtotime($requestField['date']))." "."Event Time:".date("g:i a", strtotime($requestField['time']));
        $message3['body'] = $title3;
        $message3['android_channel_id'] = '1';
        $message3['image'] = '';
        $message3['priority'] = 'high';
        $message3['badge'] = '1';
        $message3['colour'] = '#FF0000';
        $message3['channel'] = '';
        $user_notification = array();
        // house wise house captain
        // get token array

        $house_notification_query = 'SELECT dt.device_token,u.id FROM `users` u LEFT JOIN device_token dt ON dt.user_id = u.id WHERE house_id = "'.$user_data['house_id'].'" AND user_type_id = "3" GROUP BY dt.id';
        $house_notification_result = mysqli_query($conn, $house_notification_query);
        while($house_notification_data = mysqli_fetch_assoc($house_notification_result)){
            $user_notification[] = $house_notification_data['device_token'];
        }

        // user array
        $house_notification_user_query = 'SELECT dt.device_token,u.id FROM `users` u LEFT JOIN device_token dt ON dt.user_id = u.id WHERE house_id = "'.$user_data['house_id'].'" AND user_type_id = "3" GROUP BY u.id';
        $house_notification_user_result = mysqli_query($conn, $house_notification_user_query);
        while($house_notification_user_data = mysqli_fetch_assoc($house_notification_user_result)){
            $notification_query3_user = 'INSERT INTO notification SET 
                    user_id= "'.$house_notification_user_data['id'].'",
                    event_id= "'.$event_id['id'].'",
                    message = "'.$title3.'",
                    notification_type = "singup_notification",
                    created_by = "'.$_SESSION['user_details']['id'].'",
                    created_date = "'.date('Y-m-d H:i:s').'"
                    ';
            $notification_result3 = mysqli_query($conn, $notification_query3_user);
        }


        $successers = sendSingleNotification($user_notification, $message3);
        $_SESSION['FLASH_SUCCESS_FLAG'] = "Sign up successfully";
        echo "<script>window.location.href='events_details_list.php?id=" . $_GET['id'] . "&success=true';</script>";
        exit;
        //header('Location: events_details_list.php?id='.$_GET['id'].'&success=true');
?>
        <script type="text/javascript">
          $('#signed-success-modal').modal('show');
        </script>
<?php
        //header('Location: change-password.php');
      } else {
        $_SESSION['FLASH_SUCCESS_FLAG'] = "Sign up problem";
        echo "<script>window.location.href='events_details_list.php?id=" . $_GET['id'] . "&success=true';</script>";
        exit;
        //header('Location: events_details_list.php?id='.$_GET['id'].'&success=true');
        //header('Location: change-password.php');
      }
    } else {
      $add_query = 'INSERT INTO event_signups SET event_id= "' . $event_id['id'] . '",user_id = "' . $_SESSION['user_details']['id'] . '",status = "0",created_date = "' . date('Y-m-d H:i:s') . '"';
      $result = mysqli_query($conn, $add_query);
      if ($result) {
        $_SESSION['FLASH_SUCCESS_FLAG'] = "Sign up successfully";
        echo "<script>window.location.href='events_details_list.php?id=" . $_GET['id'] . "&success=true';</script>";
        exit;
        //header('Location: events_details_list.php?id='.$_GET['id'].'&success=true');
        //header('Location: event_upcoming.php');
      } else {
        $_SESSION['FLASH_SUCCESS_FLAG'] = "Sign up problem";
        echo "<script>window.location.href='events_details_list.php?id=" . $_GET['id'] . "&success=true';</script>";
        exit;
        //header('Location: events_details_list.php?id='.$_GET['id'].'&success=true');
        //header('Location: event_upcoming.php');
      }
    }
  }
}

if (isset($_GET['id']) && $_GET['id'] != '') {
  $sql = 'SELECT e.*,usd.id as submit_id,usd.file_name,ed.file_name as document_file FROM `events` e LEFT JOIN user_submitted_docs usd ON e.id = usd.event_id AND usd.user_id = "' . $_SESSION['user_details']['id'] . '" LEFT JOIN event_documents ed ON ed.event_id = e.id AND ed.type = "waiver_from" WHERE MD5(e.id) = "' . $_GET['id'] . '"';
  //$sql = 'SELECT e.*,c.name as cat_name FROM `events` e LEFT JOIN categories c ON e.category_id = c.id WHERE MD5(e.id) = "'.$_GET['id'].'"';
  $result = mysqli_query($conn, $sql);
  $event_data = mysqli_fetch_assoc($result);

  $get_event_query = 'SELECT agc.id,agc.title,agc.gender FROM `event_age_gender_categories` eac LEFT JOIN age_gender_categories agc ON eac.age_gender_id = agc.id WHERE MD5(event_id) = "' . $_GET['id'] . '"';
  $get_event_result = mysqli_query($conn, $get_event_query);
  $get_event_count = mysqli_num_rows($get_event_result);
  $get_event_data = mysqli_fetch_assoc($get_event_result);
  if ($get_event_count > 0) {
    $user_gender = $_SESSION['user_details']['gender'];
    if ($user_gender == $get_event_data['gender']) {
      $gender_check = "1";
    } else {
      $gender_check = "0";
    }
  } else {
    $gender_check = "1";
  }
  $check_query = 'SELECT * FROM `event_signups` WHERE event_id = "' . $event_data['id'] . '"  AND user_id = "' . $_SESSION['user_details']['id'] . '"';
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

$sql = 'SELECT u.id as user_ID,h.name as house_name,h.id as house_id,ut.name as user_type_name FROM `users` u LEFT JOIN houses h ON u.house_id = h.id LEFT JOIN user_type ut ON ut.id = u.user_type_id WHERE u.id = "' . $_SESSION['user_details']['id'] . '"';
$result = mysqli_query($conn, $sql);
$user_data = mysqli_fetch_assoc($result);


?>
<style>
  .custom-file-input:lang(en)~.custom-file-label::after {
    content: "Upload";
    background: #007bff;
    color: #fff;
  }

  #myModal {
    display: none !important;
  }

  .btn {
    padding-left: 2.5rem;
    padding-right: 2.5rem;
    text-transform: uppercase;
  }

  /******************* start event details list css ******************/
  .mb-0>a {
    display: block;
    position: relative;
  }

  .mb-0>a:after {
    content: "\f078";
    /* fa-chevron-down */
    font-family: 'FontAwesome';
    position: absolute;
    right: 0;
    font-size: 13px;
  }

  .mb-0>a[aria-expanded="true"]:after {
    content: "\f077";
    /* fa-chevron-up */
  }

  #approve-modal .modal-dialog {
    -webkit-transform: translate(0, -50%);
    -o-transform: translate(0, -50%);
    transform: translate(0, -50%);
    top: 50%;
    margin: 0px auto;
  }

  .update_event {
    width: 150px;
    padding-left: 0 !important;
    padding-right: 0 !important;
  }

  a.info-comment i {
    background: #44a7d8;
  }

  [type="checkbox"][readonly="readonly"]::before {
    background: rgba(255, 255, 255, .5);
    content: '';
    display: block;
    height: 100%;
    width: 100%;
  }

  /******************* end event details list css ******************/
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
<link rel="stylesheet" href="assets/web/css/custom.css">
<script src="assets/web/local_assets/js/event_details_list.js"></script>
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
          if (isset($_GET['success']) && $_GET['success'] == 'true' && isset($_SESSION['FLASH_SUCCESS_FLAG']) && $_SESSION['FLASH_SUCCESS_FLAG'] != '') {
          ?>
            <div class="alert alert-success alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              <h5><i class="icon fas fa-check"></i> Alert!</h5>
              <?php echo $_SESSION['FLASH_SUCCESS_FLAG']; ?>

            </div>
            <?php // 
            ?>
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

            <div class="event-details-top-box">
              <div class="event-details-top p-3">
                <div class="row">
                  <div class="col-md-6">
                    <div class="list-button">
                      <?php
                      $category_query = 'SELECT `name` FROM `categories` WHERE id = "' . $event_data['category_id'] . '"';
                      $category_result = mysqli_query($conn, $category_query);
                      $category_data = mysqli_fetch_assoc($category_result);
                      ?>
                      <a class="main-c-part"><?php echo $category_data['name'] ?></a>
                      <?php
                      $category_query = 'SELECT `name` FROM `categories` WHERE id = "' . $event_data['sub_category_id'] . '"';
                      $category_result = mysqli_query($conn, $category_query);
                      $category_data = mysqli_fetch_assoc($category_result);
                      if (!empty($category_data['name'])) {
                      ?>
                        <a class="subc-part"><?php echo $category_data['name'] ?></a>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="list-caption text-right mobile-text-left">
                      <p><?php echo facebook_time_ago($event_data['event_date_time']); ?></p>
                    </div>
                  </div>
                </div>
                <?php
                if (!empty($event_data['sub_category_id'])) {
                ?>
              </div>
            <?php } ?>
            <?php
            $age_gender_sql = 'SELECT agc.title FROM `event_age_gender_categories` eagc LEFT JOIN age_gender_categories agc ON eagc.age_gender_id = agc.id WHERE eagc.event_id = "' . $event_data['id'] . '"';
            $age_gender_result = mysqli_query($conn, $age_gender_sql);
            $age_gender_data = mysqli_fetch_assoc($age_gender_result);
            if (empty($age_gender_data)) {
              $grade_sql = 'SELECT g.name FROM `event_grade_categories` egc LEFT JOIN grades g ON egc.grade_id = g.id WHERE egc.event_id = "' . $event_data['id'] . '"';
              $grade_result = mysqli_query($conn, $grade_sql);
              $grade_data = mysqli_fetch_assoc($grade_result);
              $agc_grade = $grade_data['name'];
            } else {
              $agc_grade = $age_gender_data['title'];
            }
            ?>
            <div class="head-title p-0">
              <h4 class="pb-3 pl-0"><?php echo $event_data['title'] . "(" . $agc_grade . ")" ?></h4>
            </div>
            <div class="pt-3 pb-3 row">
              <div class="col-md-6">
                <div class="row">
                  <div class="col-md-10 clearfix event-date-box event-for-date">
                    <div class="date float-left mobile-float-none">
                      <h4>DATE</h4>
                    </div>
                    <div class="date-2 float-right mobile-float-none">
                      <h4><?php echo date('d M Y', strtotime($event_data['event_date'])); ?></h4>
                    </div>
                  </div>


                </div>
                <div class="row">
                  <div class="col-md-10 clearfix event-date-box event-for-date">
                    <div class="arrival-time float-left mobile-float-none">
                      <h4>ARRIVAL TIME</h4>
                    </div>
                    <div class="time float-right mobile-float-none">
                      <h4><?php echo date("g:i A", strtotime($event_data['event_time'])); ?></h4>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-10 clearfix event-date-box event-for-date">
                    <div class="date float-left mobile-float-none">
                      <h4>REGISTRATION END DATE</h4>
                    </div>
                    <div class="date-2 float-right mobile-float-none">
                      <h4><?php echo date("d M Y", strtotime($event_data['registration_end_date'])); ?></h4>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-10 clearfix event-date-box event-for-date">
                    <div class="date float-left mobile-float-none">
                      <h4>REGISTRATION END TIME</h4>
                    </div>
                    <div class="date-2 float-right mobile-float-none">
                      <h4><?php echo date("g:i A", strtotime($event_data['registration_end_time'])); ?></h4>
                    </div>
                  </div>
                </div>
              </div>
              
            </div>
            <?php
            // Document get
            $document = array();
            $document1 = array();
            $document_query = 'SELECT * FROM `event_documents` WHERE event_id = "' . $event_data['id'] . '"';
            $document_result = mysqli_query($conn, $document_query);
            $document_count = mysqli_num_rows($document_result);
            if ($document_count > 0) {
              while ($document_data = mysqli_fetch_assoc($document_result)) {
                $document['id'] = $document_data['id'];
                $document['name'] = BASE_URL . 'upload/event_document/' . $document_data['file_name'];
                $document1[$document_data['type']][] = $document;
                //$event_array['document']['type'][] = $document_data['type'];
              }
            } else {
              $document1 = array();
            }

            ?>
            <div class="">
              <div class="row">
                <?php
                $i = 1;
                foreach ($document1['pdf_document'] as $key => $value) {
                ?>
                  <div class="col-md-5">
                    <div class="download-file">
                      <ul>
                        <li class="pdf-image"><img src="assets/web/images/pdf.png"></li>
                        <li>
                          <h5>PDF DOCUMENT (<?php echo $i; ?>)</h5>
                          <?php
                          $link = $value['name'];
                          $link_array = explode('/', $link);
                          $pdf_file = end($link_array);
                          ?>
                          <p class="max-lines"><?php echo $pdf_file; ?></p>
                        </li>
                        <li class="download-image"><a href="<?php echo $value['name'] ?>" download><img src="assets/web/images/download-icon.png"></a></li>
                      </ul>

                    </div>
                  </div>
                <?php $i++;
                } ?>
                <?php
                $j = 1;
                foreach ($document1['waiver_from'] as $key_from => $value_from) {
                ?>
                  <div class="col-md-5">

                    <div class="download-file">
                      <ul>
                        <li class="pdf-image"><img src="assets/web/images/pdf.png"></li>
                        <li>
                          <h5>WAIVER FORM (<?php echo $j; ?>)</h5>
                          <?php
                          $link = $value_from['name'];
                          $link_array = explode('/', $link);
                          $waiver_file = end($link_array);
                          ?>
                          <p class="max-lines"><?php echo $waiver_file; ?></p>
                        </li>
                        <li class="download-image"><a href="<?php echo $value_from['name'] ?>" download><img src="assets/web/images/download-icon.png"></a></li>
                      </ul>

                    </div>


                  </div>
                <?php $j++;
                } ?>
              </div>
            </div>
            </div>
            <form action="" method="post" enctype="multipart/form-data">
              <?php if (empty($event_data['file_name'])) { ?>
                <div class="p-3">
                  <div class="row">
                    <?php if (!empty($event_data['document_file'])) { 
                      date_default_timezone_set('Asia/Kolkata');
                      $current_date_time = date('Y-m-d H:i:s');
                      if ($event_data['registration_end_datetime'] > $current_date_time) {
                      ?>
                      <div class="col-md-4">
                        <label for="category_name" class="label-text">Upload Waiver Form</label>
                        <div class="custom-file">
                          <input type="file" accept="application/pdf" required class="custom-file-input" name="waiver_from" id="customFile">
                          <label class="custom-file-label" for="files">Select Your Waiver Form</label>

                        </div>
                      </div>
                    <?php } } ?>
                  </div>
                </div>
              <?php } else {
                $path = BASE_URL . 'upload/waiver_form/' . $_SESSION['user_details']['id'];
                if (empty($event_data['file_name'])) {
                  $document = "";
                } else {

                  $document = $path . '/' . $event_data['file_name'];
                }
                
                
              ?>
                <div class="col-md-5">
                  <div class="download-file">
                    <ul>
                      <li class="pdf-image"><img src="assets/web/images/pdf.png"></li>
                      <li>
                        <h5>Upload Waiver Form</h5>
                      </li>
                      <li class="download-image"><a href="<?php echo $document; ?>" download><img src="assets/web/images/download-icon.png"></a></li>
                    </ul>

                  </div>
                  <?php
                
                }

                if (empty($check_data)) {
                  if ($event_data['registration_end_datetime'] > $current_date_time) {
                    if ($gender_check == "1") {
                  ?>
                      <div class="signup-btn p-3">
                        <button type="submit" name="signup_button" id="signup_button" value="signup_button" class="btn btn-primary">SIGN UP</button>
                      </div>
                    <?php } ?>
                  <?php } ?>
                <?php } else { ?>
                  <?php
                  if ($check_data['status'] == '1') {
                  ?>
                    <div class="signup-event m-pr-0 p-3">
                      <a data-toggle="modal" data-target="#signed-success-modal">You Have Signed up For This Events</a>

                    </div>
                    <div class="error-line pl-3 pb-2 pt-2">
                      <a href="#" data-toggle="modal" data-target="#reason-leave" style="color:red;">Do you wish to remove your name from the event?</a>
                    </div>
                  <?php } else if ($check_data['status'] == "2") { ?>
                    <div class="signup-event m-pr-0 p-3 pb-5">
                      <a data-toggle="modal" style="background: #f44236;" data-target="signed-success-modal">You have not been selected to take part in this event.</a>
                    </div>
                  <?php } else { ?>
                    <div class="signup-event m-pr-0 p-3 pb-5">
                      <a data-toggle="modal" data-target="signed-success-modal">You have signed up but are awaiting approval.</a>
                    </div>
                  <?php } ?>

                <?php } ?>

            </form>
            <form id="event_details_update" name="event_details_update">
              <div class="row p-3">
                <div class="col-md-6">
                  <label for="category_name" class="label-text">Enter Internal Notes&nbsp;<span class="is-required">*</span></label>
                  <textarea class="form-control" rows="3" name="internal_notes" placeholder="Write Internal Notes ..."><?php echo (isset($event_data['event_internal_notes']) && $event_data['event_internal_notes'] != "") ? $event_data['event_internal_notes'] : ""; ?></textarea>
                </div>
                <div class="col-md-6">
                  <label for="category_name" class="label-text">Enter External Notes&nbsp;<span class="is-required">*</span></label>
                  <textarea class="form-control" rows="3" name="external_notes" placeholder="Write External Notes ..."><?php echo (isset($event_data['event_external_notes']) && $event_data['event_external_notes'] != "") ? $event_data['event_external_notes'] : ""; ?></textarea>
                </div>
                <div class="col-sm-6">
                  <?php
                  if (isset($_GET['id']) && $_GET['id'] != '') {
                    // council get
                    $council_query = 'SELECT u.id,u.first_name,u.middle_name,u.last_name FROM `event_council_members` ecm LEFT JOIN users u ON ecm.council_member = u.id WHERE ecm.event_id = "' . $event_data['id'] . '"';
                    $council_result = mysqli_query($conn, $council_query);
                    while ($council_data = mysqli_fetch_assoc($council_result)) {
                      $council_array[] = $council_data;
                    }
                    //print_r($council_array);exit;
                  }
                  ?>
                  <!-- checkbox -->
                  <label class="label-text"> Select Council Members</label>
                  <div class="form-group clearfix label-with-checkbox">
                    <?php

                    $sql = 'SELECT u.id as user_ID,h.name as house_name,h.id as house_id,ut.name as user_type_name FROM `users` u LEFT JOIN houses h ON u.house_id = h.id LEFT JOIN user_type ut ON ut.id = u.user_type_id WHERE u.id = "' . $_SESSION['user_details']['id'] . '"';
                    $result = mysqli_query($conn, $sql);
                    $user_data = mysqli_fetch_assoc($result);
                    // if($user_data['user_type_name'] == "Administrator" || $user_data['user_type_name'] == "Core Captain"){
                    //     $house_query = 'SELECT u.id,u.first_name,u.middle_name,u.last_name,h.name as house_name,h.id as house_id FROM `users` u LEFT JOIN user_type ut ON u.user_type_id = ut.id LEFT JOIN houses h ON h.id = u.house_id WHERE ut.name = "House Captain"';
                    // }else{
                    //     $house_query = 'SELECT u.id,u.first_name,u.middle_name,u.last_name,h.name as house_name FROM `users` u LEFT JOIN user_type ut ON u.user_type_id = ut.id LEFT JOIN houses h ON h.id = u.house_id WHERE ut.name = "House Captain" AND h.id = "'.$user_data['house_id'].'"';
                    // }
                    $house_query = 'SELECT u.id,u.first_name,u.middle_name,u.last_name,h.name as house_name,h.id as house_id FROM `users` u LEFT JOIN user_type ut ON u.user_type_id = ut.id LEFT JOIN houses h ON h.id = u.house_id WHERE ut.name = "House Captain"';
                    $result = mysqli_query($conn, $house_query);
                    $i = 1;
                    while ($house_data = mysqli_fetch_assoc($result)) {
                    ?>
                      <div class="icheck-primary d-inline">
                        <?php
                        if ($user_data['user_type_name'] == "House Captain") {
                        ?>
                          <input type="checkbox" <?php if ($house_data['house_id'] != $_SESSION['user_details']['house_id']) { ?> readonly="readonly" <?php } ?> name="council_members[]" <?php if (isset($_GET['id']) && $_GET['id'] != '') {
                                                                                                                                                                                          if (!empty($council_array)) {
                                                                                                                                                                                            if (array_search($house_data['id'], array_column($council_array, 'id')) !== false) {
                                                                                                                                                                                              echo 'checked';
                                                                                                                                                                                            }
                                                                                                                                                                                          }
                                                                                                                                                                                        } ?> value="<?php echo $house_data['id']; ?>" id="council_members<?php echo $i; ?>">
                        <?php } else { ?>
                          <input type="checkbox" name="council_members[]" <?php if (isset($_GET['id']) && $_GET['id'] != '') {
                                                                            if (!empty($council_array)) {
                                                                              if (array_search($house_data['id'], array_column($council_array, 'id')) !== false) {
                                                                                echo 'checked';
                                                                              }
                                                                            }
                                                                          } ?> value="<?php echo $house_data['id']; ?>" id="council_members<?php echo $i; ?>">
                        <?php } ?>
                        <label for="council_members<?php echo $i; ?>">
                          <?php echo $house_data['first_name'] . "(" . $house_data['house_name'] . ")"; ?>
                        </label>
                      </div>
                    <?php $i++;
                    } ?>
                  </div>
                </div>
              </div>
              <input type="hidden" name="event_id" id="event_id" value="<?php echo $event_data['id']; ?>" <div class="card-footer">
              <div class="card-footer">
                <button type="submit" id="update_event" onclick="return validation_update();" class="btn btn-primary update_event">UPDATE EVENT</button>
              </div>
          </div>
          </form>
          <div id="accordion" class="multiple-accordion-box">
            <div class="row">
              <?php
              if ($user_data['user_type_name'] == "House Captain") {
                $house_list_query = 'SELECT h.name,COUNT(evt_snp.status) as count_status,evt_snp.status,h.id from houses h LEFT JOIN users usr on h.id = usr.house_id LEFT join event_signups evt_snp on usr.id = evt_snp.user_id AND evt_snp.event_id = "' . $event_data['id'] . '" AND evt_snp.status != "2" WHERE h.id = "' . $user_data['house_id'] . '" GROUP BY h.name';
              } else {
                $house_list_query = 'SELECT h.name,COUNT(evt_snp.status) as count_status,evt_snp.status,h.id from houses h LEFT JOIN users usr on h.id = usr.house_id LEFT join event_signups evt_snp on usr.id = evt_snp.user_id AND evt_snp.event_id = "' . $event_data['id'] . '" AND evt_snp.status != "2" GROUP BY h.name';
              }
              $house_list_result = mysqli_query($conn, $house_list_query);
              $i = 1;
              $Pending_count = 0;
              $Approved_count = 0;
              $Rejected_count = 0;
              while ($house_list_array = mysqli_fetch_assoc($house_list_result)) {
                $data = array();
                $Pending_count = 0;
                $Approved_count = 0;
                $Rejected_count = 0;
              ?>
                <div class="col-md-6">

                  <div class="card">
                    <div class="card-header" id="heading-1">
                      <h5 class="mb-0">
                        <a role="button" data-toggle="collapse" href="#collapse-<?php echo $house_list_array['id']; ?>" aria-expanded="false" aria-controls="collapse-<?php echo $house_list_array['id']; ?>">
                          <?php echo $house_list_array['name'] . " (" . $house_list_array['count_status'] . ")" ?>
                        </a>
                      </h5>
                    </div>
                    <div id="collapse-<?php echo $house_list_array['id']; ?>" class="collapse" aria-labelledby="heading-1">
                      <div class="card-body">
                        <?php

                        $house_query = 'SELECT u.first_name,u.last_name,u.id as user_id,es.status,es.event_id,h.name as hosue_name,u.phone,e.title,es.reject_commit FROM `event_signups` es LEFT JOIN users u ON es.user_id = u.id LEFT JOIN houses h ON u.house_id = h.id LEFT JOIN events e ON es.event_id = e.id WHERE event_id = "' . $event_data['id'] . '" AND h.id = "' . $house_list_array['id'] . '"';
                        // print_r($house_query);
                        $house_result = mysqli_query($conn, $house_query);

                        while ($house_list = mysqli_fetch_assoc($house_result)) {
                          if ($house_list['status'] == "0") {
                            $status_name = "Pending";
                          }
                          if ($house_list['status'] == "1") {
                            $status_name = "Approved";
                          }
                          if ($house_list['status'] == "2") {
                            $status_name = "Rejected";
                          }
                          $data['name'] = $house_list['hosue_name'];
                          $data[$status_name][] = $house_list;
                        }

                        $Pending_count = count($data['Pending']);
                        $Approved_count = count($data['Approved']);
                        $Rejected_count = count($data['Rejected']);
                        if ($Pending_count == "0") {
                          $data['Pending'] = array();
                        }
                        if ($Approved_count == "0") {
                          $data['Approved'] = array();
                        }
                        if ($Rejected_count == "0") {
                          $data['Rejected'] = array();
                        }
                        // echo"<pre>";
                        // print_r($data);exit;
                        // $counts = array_count_values(array_flip(array_column($data['Pending'], 'hosue_name')));
                        // print_r($counts);exit;
                        ?>

                        <div id="accordion-1">
                          <div class="card">
                            <div class="card-header" id="heading-1-<?php echo $house_list_array['name']; ?>">
                              <h5 class="mb-0">
                                <a class="collapsed" role="button" data-toggle="collapse" href="#collapse-1-<?php echo $house_list_array['name']; ?>" aria-expanded="false" aria-controls="collapse-1-<?php echo $house_list_array['name']; ?>">
                                  PENDING PARTICIPANTS (<?php echo $Pending_count; ?>)
                                </a>
                              </h5>
                            </div>
                            <div id="collapse-1-<?php echo $house_list_array['name']; ?>" class="collapse" aria-labelledby="heading-1-<?php echo $house_list_array['name']; ?>">
                              <div class="card-body">
                                <?php
                                foreach ($data['Pending'] as $pending_key => $pending_value) {
                                  if ($pending_value['hosue_name'] == $house_list_array['name']) {

                                    if ($pending_value['status'] == "1") {
                                ?>
                                      <div class="comment-list-box" id="change-confirm-box">
                                        <ul class="comment-list clearfix">
                                          <li><a href="user_details.php?id=<?php echo MD5($pending_value['user_id']); ?>&event_id=<?php echo $pending_value['event_id']; ?>" class="comment-user-name"><?php echo $pending_value['first_name'] . " " . $pending_value['last_name']; ?></a>
                                            <p class="comment-user-name comment-with-number"><?php echo $pending_value['phone']; ?></p>
                                          </li>
                                          <li class="float-right">
                                            <a class="comment-user-close close set-close" data-toggle="modal" data-target="#reject-modal" data-user_id="<?php echo $pending_value['user_id']; ?>" data-event_id="<?php echo $pending_value['event_id']; ?>" id="change-confirm"><i class="fa fa-times"></i></a>
                                          </li>
                                        </ul>
                                      </div>
                                    <?php } else { ?>
                                      <div class="comment-list-box approve-reject">
                                        <ul class="comment-list clearfix">
                                          <li><a class="comment-user-name" href="user_details.php?id=<?php echo MD5($pending_value['user_id']); ?>&event_id=<?php echo $pending_value['event_id']; ?>"><?php echo $pending_value['first_name'] . " " . $pending_value['last_name']; ?></a>
                                            <p class="comment-user-name comment-with-number"><?php echo $pending_value['phone']; ?></p>
                                          </li>
                                          <li class="float-right set-right">
                                            <a class="comment-user-right right" data-user_id="<?php echo $pending_value['user_id']; ?>" data-event_id="<?php echo $pending_value['event_id']; ?>" data-first_name="<?php echo $pending_value['first_name']; ?>" data-last_name="<?php echo $pending_value['last_name'] ?>" data-toggle="modal" data-target="#approve-modal" id="change-confirm"><i class="fa fa-check"></i></a>
                                            <a class="comment-user-close close" data-user_id="<?php echo $pending_value['user_id']; ?>" data-event_id="<?php echo $pending_value['event_id']; ?>" data-toggle="modal" data-target="#reject-modal" id="change-reject"><i class="fa fa-times"></i></a>
                                          </li>
                                        </ul>
                                      </div>
                                    <?php } ?>
                                  <?php } ?>
                                <?php } ?>

                              </div>
                            </div>
                          </div>
                          <div class="card">
                            <div class="card-header" id="heading-1-<?php echo $house_list_array['id']; ?>">
                              <h5 class="mb-0">
                                <a class="collapsed" role="button" data-toggle="collapse" href="#collapse-1-<?php echo $house_list_array['id']; ?>" aria-expanded="false" aria-controls="collapse-1-<?php echo $house_list_array['id']; ?>">
                                  CONFIRMED PARTICIPANTS (<?php echo $Approved_count; ?>)
                                </a>
                              </h5>
                            </div>
                            <div id="collapse-1-<?php echo $house_list_array['id']; ?>" class="collapse" aria-labelledby="heading-1-<?php echo $house_list_array['id']; ?>">
                              <div class="card-body">
                                <?php
                                foreach ($data['Approved'] as $pending_key => $pending_value) {
                                  if ($pending_value['hosue_name'] == $house_list_array['name']) {
                                    if ($pending_value['status'] == "1") {
                                ?>
                                      <div class="comment-list-box" id="change-confirm-box">
                                        <ul class="comment-list clearfix">
                                          <li><a class="comment-user-name" href="user_details.php?id=<?php echo MD5($pending_value['user_id']); ?>&event_id=<?php echo $pending_value['event_id']; ?>"><?php echo $pending_value['first_name'] . " " . $pending_value['last_name']; ?></a>
                                            <p class="comment-user-name comment-with-number"><?php echo $pending_value['phone']; ?></p>
                                          </li>
                                          <li class="float-right">
                                            <a class="comment-user-close close set-close" data-toggle="modal" data-target="#reject-modal" data-user_id="<?php echo $pending_value['user_id']; ?>" data-event_id="<?php echo $pending_value['event_id']; ?>" id="change-confirm"><i class="fa fa-times"></i></a>
                                          </li>
                                        </ul>
                                      </div>
                                    <?php } else { ?>
                                      <div class="comment-list-box approve-reject">
                                        <ul class="comment-list clearfix">
                                          <li><a class="comment-user-name" href="user_details.php?id=<?php echo MD5($pending_value['user_id']); ?>&event_id=<?php echo $pending_value['event_id']; ?>"><?php echo $pending_value['first_name'] . " " . $pending_value['last_name']; ?></a></li>
                                          <li class="float-right set-right">
                                            <a class="comment-user-right right " data-user_id="<?php echo $pending_value['user_id']; ?>" data-event_id="<?php echo $pending_value['event_id']; ?>" data-first_name="<?php echo $pending_value['first_name']; ?>" data-last_name="<?php echo $pending_value['last_name'] ?>" data-toggle="modal" data-target="#approve-modal" id="change-confirm"><i class="fa fa-check"></i></a>
                                            <a class="comment-user-close close" data-user_id="<?php echo $pending_value['user_id']; ?>" data-event_id="<?php echo $pending_value['event_id']; ?>" data-toggle="modal" data-target="#reject-modal" id="change-reject"><i class="fa fa-times"></i></a>
                                          </li>
                                        </ul>
                                      </div>
                                    <?php } ?>
                                  <?php } ?>
                                <?php } ?>

                              </div>
                            </div>
                          </div>
                          <div class="card">
                            <?php
                            $reject_id = rand();
                            ?>
                            <div class="card-header" id="heading-1-<?php echo $reject_id; ?>">
                              <h5 class="mb-0">
                                <a class="collapsed" role="button" data-toggle="collapse" href="#collapse-1-<?php echo $reject_id; ?>" aria-expanded="false" aria-controls="collapse-1-<?php echo $reject_id; ?>">
                                  REJECTED / WITHDRAWNS PARTICIPANTS (<?php echo $Rejected_count; ?>)
                                </a>
                              </h5>
                            </div>
                            <div id="collapse-1-<?php echo $reject_id; ?>" class="collapse" aria-labelledby="heading-1-<?php echo $reject_id; ?>">
                              <div class="card-body">
                                <?php
                                foreach ($data['Rejected'] as $rejected_key => $rejected_value) {
                                  if ($rejected_value['hosue_name'] == $house_list_array['name']) {
                                ?>
                                    <div class="comment-list-box reject" id="change-reject-box">
                                      <ul class="comment-list clearfix">
                                        <li><a class="comment-user-name " href="user_details.php?id=<?php echo MD5($rejected_value['user_id']); ?>&event_id=<?php echo $rejected_value['event_id']; ?>"><?php echo $rejected_value['first_name'] . " " . $rejected_value['last_name']; ?></a>
                                          <p class="comment-user-name comment-with-number"><?php echo $rejected_value['phone']; ?></p>
                                        </li>
                                        <li class="float-right set-right"><a class="comment-user-right right" data-user_id="<?php echo $rejected_value['user_id']; ?>" data-event_id="<?php echo $rejected_value['event_id']; ?>" data-first_name="<?php echo $rejected_value['first_name']; ?>" data-last_name="<?php echo $rejected_value['last_name'] ?>" data-toggle="modal" data-target="#approve-modal" id="change-reject"><i class="fa fa-check"></i></a>
                                          <?php
                                          if ($rejected_value['user_id'] == $_SESSION['user_details']['id']) {
                                          ?>
                                            <a class="info-comment" data-user_id="<?php echo $rejected_value['user_id']; ?>" data-event_id="<?php echo $rejected_value['event_id']; ?>" data-commit="<?php echo $rejected_value['reject_commit']; ?>" id="change-reject"><i class="fa fa-info"></i></a>
                                          <?php } ?>
                                        </li>
                                      </ul>
                                    </div>
                                  <?php } ?>
                                <?php } ?>
                              </div>
                            </div>
                          </div>

                        </div>

                      </div>
                    </div>
                  </div>


                </div>
              <?php $i++;
              } ?>
            </div>
            <div class="signup-event m-pr-0 p-3">
              <a href="javascript:void(0)" onclick="signup_data(<?php echo $event_data['id']; ?>)" id="download_data">DOWNLOAD SIGNUPS DATA <i class="fa fa-download" aria-hidden="true"></i>
              </a>
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
      <input type="hidden" id="reject_user_id" name="approve_user_id" />
      <input type="hidden" id="reject_event_id" name="approve_event_id" />
      <div class="modal-footer reject-footer">
        <button type="submit" id="reject_yes" class="btn btn-primary">YES</button>
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
        <h4 class="reject-text approve_text">Switch Kunjan bhavsar Back To Confirmed Participant?</h4>
      </div>
      <input type="hidden" id="approve_user_id" name="approve_user_id" />
      <input type="hidden" id="approve_event_id" name="approve_event_id" />
      <div class="modal-footer reject-footer">
        <button type="button" id="approve_yes" class="btn btn-primary">YES</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">NO</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade modal-center" id="reason-leave" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Reason For Leaving? </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body reason-body">
        <textarea class="form-control" id="reason_commit" rows="3" required name="reason_commit" placeholder="Write Reason For Leaving ..."></textarea>
        <input type="hidden" value=<?php echo $event_data['id'] ?> id="event_id" name="event_id" />
      </div>
      <div class="modal-footer modal-footer-btn">
        <button type="submit" id="reason_button" class="btn btn-primary">SUBMIT</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade modal-center" id="get-reason-leave" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="padding:10px 15px;">
        <h5 class="modal-title text-center" id="exampleModalLabel" style="margin:auto;">Rejection Reason </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin:-1rem -1rem -1rem;">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body reason-body">
        <div id="reason-commit"></div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade modal-center" id="signed-success-modal">
  <div class="modal-dialog" role="document">
    <div class="modal-content signed-success-m">
      <div class="modal-body signed-success clearfix">
        <div class="icon-box float-left">
          <i class="fas fa-check"></i>
        </div>
        <div class="success-caption float-right">
          <h4>SIGNED UP<br>SUCCESSFULLY</h4>
        </div>
      </div>
    </div>
  </div>
</div>
<?php
include 'include/footer.php';
?>

<script>
  $("input[data-bootstrap-switch]").each(function() {
    $(this).bootstrapSwitch('state', $(this).prop('checked'));
  });
</script>
<script>
  $(function() {
    bsCustomFileInput.init();
  });
</script>
<script>
  $(function() {

    $("#change-confirm").on("click", function(event) {

      event.preventDefault();

      $("#change-confirm-box").css({
        "background-color": "#83f683"
      });
    });
  });

  $(function() {

    $("#change-reject").on("click", function(event) {

      event.preventDefault();

      $("#change-reject-box").css({
        "background-color": "#1a55a1"
      });
    });
  });
</script>
<script>
  /*global window */
  (function($) {
    "use strict";
    $(document.body).delegate('[type="checkbox"][readonly="readonly"]', 'click', function(e) {
      e.preventDefault();
    });

    $('#test-form').submit(function(e) {
      $('.code').first().html($(this).serialize());
      return false;
    });
  }(window.jQuery));
</script>