<?php
include 'include/session_check.php';
$title = "Event Details";
// $pageTitle = "Event Details";
include 'include/header.php';
include 'include/header-navigation.php';
include 'include/navigation.php';

if (isset($_POST['signup_button'])) {
  ?>
  $("#signup_button").hide();
  <?php
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
      $_SESSION['FLASH_SUCCESS_FLAG'] = "Sign up successfully";
      echo "<script>window.location.href='event-details.php?id=" . $_GET['id'] . "&success=true';</script>";
      exit;
      //header('Location: events_details.php?id='.$_GET['id'].'&success=true');
      //header('Location: change-password.php');
    } else {
      $_SESSION['FLASH_SUCCESS_FLAG'] = "Sign up problem";
      echo "<script>window.location.href='event-details.php?id=" . $_GET['id'] . "&success=true';</script>";
      exit;
      //header('Location: events_details.php?id='.$_GET['id'].'&success=true');
      //header('Location: change-password.php');
    }
  } else {
    $add_query = 'INSERT INTO event_signups SET event_id= "' . $event_id['id'] . '",user_id = "' . $_SESSION['user_details']['id'] . '",status = "0",created_date = "' . date('Y-m-d H:i:s') . '"';
    $result = mysqli_query($conn, $add_query);
    if ($result) {
      $_SESSION['FLASH_SUCCESS_FLAG'] = "Sign up successfully";
      //header('Location: events_details.php?id='.$_GET['id'].'&success=true');
      echo "<script>window.location.href='event-details.php?id=" . $_GET['id'] . "&success=true';</script>";
      exit;
    } else {
      $_SESSION['FLASH_SUCCESS_FLAG'] = "Sign up problem";
      //header('Location: events_details.php?id='.$_GET['id'].'&success=true');
      echo "<script>window.location.href='event-details.php?id=" . $_GET['id'] . "&success=true';</script>";
      exit;
    }
  }
}

if (isset($_GET['id']) && $_GET['id'] != '') {
  $sql = 'SELECT e.*,usd.id as submit_id,usd.file_name,ed.file_name as document_file FROM `events` e LEFT JOIN user_submitted_docs usd ON e.id = usd.event_id AND usd.user_id = "' . $_SESSION['user_details']['id'] . '" LEFT JOIN event_documents ed ON ed.event_id = e.id AND ed.type = "waiver_from" WHERE MD5(e.id) = "' . $_GET['id'] . '"';
  //$sql = 'SELECT e.*,c.name as cat_name FROM `events` e LEFT JOIN categories c ON e.category_id = c.id WHERE MD5(e.id) = "'.$_GET['id'].'"';
  $result = mysqli_query($conn, $sql);
  $event_data = mysqli_fetch_assoc($result);
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
</style>

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
              <h3 class="card-title">Event Details</h3>
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
                      <a><?php echo $category_data['name'] ?></a>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="list-caption text-right mobile-text-left">
                      <p><?php echo facebook_time_ago($event_data['event_date_time']); ?></p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="head-title pl-3 pr-3">
                <h4 class="pb-3 pl-0"><?php echo $event_data['title'] ?></h4>
              </div>
              <div class="p-3 row">
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
                <div class="col-md-6">
                  <div class="mobile-ml-0">
                    <div class="row">
                      <div class="col-md-12">
                        <div class="event-notes">
                          <h4>
                            Event Notes
                          </h4>
                        </div>
                        <div class="event-notes-box">
                          <p><?php echo $event_data['event_external_notes']; ?></p>
                        </div>
                      </div>
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
            <div class="pl-3 pr-3">
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
                  date_default_timezone_set('Asia/Kolkata');
                  $current_date_time = date('Y-m-d H:i:s');
                  if ($event_data['registration_end_datetime'] > $current_date_time) {
                  ?>
                    <div class="signup-btn p-3">
                      <button type="submit" name="signup_button" id="signup_button" value="signup_button" class="btn btn-primary">SIGN UP</button>
                    </div>
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
                    <?php if($_SESSION['user_details']['id'] == $check_data['reject_by']) { ?>
                      <div class="signup-event m-pr-0 p-3 pb-5">
                      <a data-toggle="modal" style="background: #f44236;" data-target="signed-success-modal">You have withdrawn your name from this event.</a>
                    </div>
                    <?php }else{ ?>
                    <div class="signup-event m-pr-0 p-3 pb-5">
                      <a data-toggle="modal" style="background: #f44236;" data-target="signed-success-modal">You have not been selected to take part in this event.</a>
                    </div>
                    <?php } ?>
                  <?php } else { ?>
                    <div class="signup-event m-pr-0 p-3 pb-5">
                      <a data-toggle="modal" data-target="signed-success-modal">You have signed up but are awaiting approval.</a>
                    </div>
                  <?php } ?>

                <?php } ?>

            </form>
            <!-- <div class="signup-event p-3 pb-5">
                        <a href="#" data-toggle="modal" data-target="#reason-leave">You Have Signed up For This Events</a>
                      </div> -->
          </div>
          <!-- /.card -->
        </div>
      </div>
      <!-- /.row -->
    </div>
    <!--/. container-fluid -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<!--start reason leave modal-->
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
<!--end reason leave modal-->
<!--start signup successfully modal-->
<!-- <div class="modal fade modal-center" id="signed-success-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
</div>-->
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
<!--end signup successfully modal-->

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