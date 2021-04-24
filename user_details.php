<?php
include 'include/session_check.php';
$title = "User Details";
$pageTitle = "User Details";
include 'include/header.php';
include 'include/header-navigation.php';
include 'include/navigation.php';

$sql = 'SELECT u.*,h.name as house_name FROM `users` u LEFT JOIN houses h ON u.house_id = h.id WHERE MD5(u.id) = "'.$_GET['id'].'"';

$result = mysqli_query($conn, $sql);
$user_data = mysqli_fetch_assoc($result);
?>
<style>
.accordion-label label {
    padding-right: 50px;
}
</style>
<link rel="stylesheet" href="assets/web/css/custom.css">
<link rel="stylesheet" href="https://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css">
<script src="assets/web/local_assets/js/user_details.js"></script>
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
              <h3 class="card-title">User Details</h3>
            <?php } ?>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form id="user_details" name="user_details">
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
                    <!-- <input type="file" id="theFile" name="fileName" onchange="return checkExtansion();" style="display:none" /> -->
                    <span id="fileName-error-1" class="form-text"></span>
                  </div>
                <div class="row">
                  <div class="col-12 col-sm-12 col-md-4">
                    <div id="form-group-Firstname" class="form-group">
                      <label for="Firstname">First Name&nbsp;<span class="is-required">*</span></label>
                      <input type="text" name="first_name" class="form-control" id="first_name" placeholder="Please enter Firstname" value="<?php echo (isset($user_data['first_name']) && $user_data['first_name'] != "") ? $user_data['first_name'] : ""; ?>">
                      
                    </div>
                  </div>
                  <div class="col-12 col-sm-12 col-md-4">
                    <div id="form-group-last_name" class="form-group">
                      <label for="middle_name">Middle Name&nbsp;<span class="is-required">*</span></label>
                      <input type="text" name="middle_name" class="form-control" id="middle_name" placeholder="Please enter Middlename"  value="<?php echo (isset($user_data['middle_name']) && $user_data['middle_name'] != "") ? $user_data['middle_name'] : ""; ?>">
                    
                    </div>
                  </div>
                  <div class="col-12 col-sm-12 col-md-4">
                    <div id="form-group-last_name" class="form-group">
                      <label for="last_name">Last Name&nbsp;<span class="is-required">*</span></label>
                      <input type="text" name="last_name" class="form-control" id="last_name" placeholder="Please enter Lastname"  value="<?php echo (isset($user_data['last_name']) && $user_data['last_name'] != "") ? $user_data['last_name'] : ""; ?>">
                    
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12 col-sm-12 col-md-4">
                    <div id="form-group-class_sr_no" class="form-group">
                      <label for="class_sr_no">Class Sr. No&nbsp;<span class="is-required">*</span></label>
                      <input type="text" name="class_sr_no" class="form-control" id="class_sr_no" placeholder="Please enter Class Sr. No" value="<?php echo (isset($user_data['class_sr_no']) && $user_data['class_sr_no'] != "") ? $user_data['class_sr_no'] : ""; ?>">
                    </div>
                  </div>
                  <div class="col-12 col-sm-12 col-md-4">
                    <div id="form-group-class" class="form-group">
                      <label for="class">Class&nbsp;<span class="is-required">*</span></label>
                      <input type="text" name="class" class="form-control" id="class" placeholder="Please enter Class" value="<?php echo (isset($user_data['class']) && $user_data['class'] != "") ? $user_data['class'] : ""; ?>">
                    </div>
                  </div>
                  <div class="col-12 col-sm-12 col-md-4">
                    <div id="form-group-div" class="form-group">
                      <label for="div">Div&nbsp;<span class="is-required">*</span></label>
                      <input type="text" name="div" class="form-control" id="div" placeholder="Please enter Div" value="<?php echo (isset($user_data['div']) && $user_data['div'] != "") ? $user_data['div'] : ""; ?>">
                    </div>
                  </div>
                  
                </div>
                <div class="row">
                  <div class="col-12 col-sm-12 col-md-4">
                    <div id="form-group-house" class="form-group">
                      <label for="house">House&nbsp;<span class="is-required">*</span></label>
                        <select class="custom-select" name="house_id" id="house_id">
                          <option value="">Select House</option>
                          <?php
                          $sql = 'SELECT * FROM `houses`';
                          $result = mysqli_query($conn, $sql);
                          while($house_data = mysqli_fetch_assoc($result)){
                            ?>
                            <option <?php echo (isset($user_data['house_id']) && $user_data['house_id'] != "" && $user_data['house_id'] == $house_data['id']) ? "selected" : ""; ?> value="<?php echo $house_data['id']; ?>"><?php echo $house_data['name']; ?></option>
                            <?php
                          }   
                          ?>          
                        </select>
                    </div>
                  </div>
                  <div class="col-12 col-sm-12 col-md-4">
                    <div id="form-group-house" class="form-group">
                      <label for="house">User Type&nbsp;<span class="is-required">*</span></label>
                        <select class="custom-select" name="user_type_id" id="user_type_id">
                          <option value="">Select UserType</option>
                          <?php
                          $sql = 'SELECT * FROM `user_type`';
                          $result = mysqli_query($conn, $sql);
                          while($user_type_data = mysqli_fetch_assoc($result)){
                            ?>
                            <option <?php echo (isset($user_data['user_type_id']) && $user_data['user_type_id'] != "" && $user_data['user_type_id'] == $user_type_data['id']) ? "selected" : ""; ?> value="<?php echo $user_type_data['id']; ?>"><?php echo $user_type_data['name']; ?></option>
                            <?php
                          }   
                          ?>          
                        </select>
                    </div>
                  </div>
                  <div class="col-12 col-sm-12 col-md-4">
                    <div id="form-group-email" class="form-group">
                      <label for="email">Email Address&nbsp;<span class="is-required">*</span></label>
                      <input type="text" name="email_id" class="form-control" id="email_id" placeholder="Please enter email ddress" onblur="return checkEmails();" value="<?php echo (isset($user_data['email_id']) && $user_data['email_id'] != "") ? $user_data['email_id'] : ""; ?>">
                      <small id="email-error-1" class="form-text text-muted"></small>
                      <input type="hidden" name="old_email" id="old_email" value="<?php echo (isset($user_data['email_id']) && $user_data['email_id'] != "") ? $user_data['email_id'] : ""; ?>">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12 col-sm-12 col-md-4">
                      <div id="form-group-phone" class="form-group">
                        <label for="phone">Phone&nbsp;<span class="is-required">*</span></label>
                        <input type="text" name="phone" class="form-control" id="phone" placeholder="Please enter Phone" value="<?php echo (isset($user_data['phone']) && $user_data['phone'] != "") ? $user_data['phone'] : ""; ?>" >
                      </div>
                  </div>
                  <div class="col-12 col-sm-12 col-md-4">
                      <div id="form-group-parent_phone" class="form-group">
                        <label for="parent_phone">Parent Phone&nbsp;<span class="is-required">*</span></label>
                        <input type="text" name="parent_phone" class="form-control" id="parent_phone" placeholder="Please enter Parent Phone" value="<?php echo (isset($user_data['parent_phone']) && $user_data['parent_phone'] != "") ? $user_data['parent_phone'] : ""; ?>">
                      </div>
                  </div>
                  <!-- <div class="col-12 col-sm-12 col-md-4">
                      <div id="form-group-other_phone" class="form-group">
                        <label for="other_phone">Other Phone&nbsp;<span class="is-required">*</span></label>
                        <input type="text" name="other_phone" class="form-control" id="other_phone" placeholder="Please enter Other Phone" value="<?php //echo (isset($user_data['other_phone']) && $user_data['other_phone'] != "") ? $user_data['other_phone'] : ""; ?>">
                      </div>
                  </div> -->
                  <input type="hidden" name="user_id" value="<?php echo $user_data['id']; ?>" />
                  
                </div>
                <div class="row">
                <div class="col-12 col-sm-12 col-md-2">
                    <label for="div">Gender&nbsp;<span class="is-required">*</span></label>
                    <div class="form-group clearfix">
                      <div class="icheck-primary d-inline">
                          <input type="radio" name="gender" <?php echo (isset($user_data['gender']) && $user_data['gender'] != "" && $user_data['gender'] == "Male") ? "checked" : ""; ?> class="age_gender_check" value="Male" id="age_gender_male">
                          <label for="age_gender_male">
                            Male
                          </label>
                      </div>
                      <div class="icheck-primary d-inline">
                          <input type="radio" name="gender" <?php echo (isset($user_data['gender']) && $user_data['gender'] != "" && $user_data['gender'] == "Female") ? "checked" : ""; ?> class="age_gender_check" value="Female" id="age_gender_female">
                          <label for="age_gender_female">
                          Female
                          </label>
                      </div>
                    </div>
                    
                  </div>
                  <div class="col-12 col-sm-12 col-md-4">
                    <div class="form-group">
                      <label>Birthdate<span class="is-required">*</span></label>
                        <div class="input-group date" id="reservationdate" name="date" data-target-input="nearest">
                            <input type="text" autocomplete="off" id="datepicker" name="date" class="form-control datetimepicker-input" value="<?php echo (isset($user_data['dob']) && $user_data['dob'] != "") ? date("d/m/Y", strtotime($user_data['dob'])) : ""; ?>" data-target="#reservationdate"/>
                            <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                  </div>
                 
                  <div class="col-12 col-sm-12 col-md-4">
                      <div id="form-group-other_phone" class="form-group">
                        <label for="other_phone" style="display:block;">Status&nbsp;</label>
                        <?php 
                        if(isset($_GET['id']) && $_GET['id'] != ''){
                        ?>
                          <input type="checkbox" name="status" <?php echo (isset($user_data['status']) && $user_data['status'] != "" && $user_data['status'] == '1') ? "checked" : ""; ?> data-bootstrap-switch data-off-color="danger" data-on-color="success" value="<?php echo (isset($user_data['status']) && $user_data['status'] != "" && $user_data['status'] == '1') ? "1" : "0"; ?>">
                        <?php }else{ ?>
                          <input type="checkbox" name="status" checked data-bootstrap-switch data-off-color="danger" data-on-color="success" value="0">
                        <?php } ?>
                      </div>
                  </div>
                  
                </div>

              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                <button type="submit" id="submitprofile" name="btn-admin-change-credentials" onclick="return user_details_validatePage();" class="btn btn-primary">Update</button>
              </div>
            </form>
            

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
<script src="https://jdewit.github.io/bootstrap-timepicker/js/bootstrap-timepicker.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
<script>
// $(function() {
// $( "#datepicker" ).datepicker({
//   dateFormat: 'dd/mm/yy'
// });
// });
</script>
<script>
$("input[data-bootstrap-switch]").each(function(){
  $(this).bootstrapSwitch('state', $(this).prop('checked'));
});
$(function() {
  $( "#datepicker" ).datepicker({
    dateFormat: 'dd/mm/yy'
  });
});
</script>
