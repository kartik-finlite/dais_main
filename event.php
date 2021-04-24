<?php
include 'include/session_check.php';
if(isset($_GET['id']) && $_GET['id'] != ''){
  $title = "Update Event";
}else{
$title = "Add Event";
}
// $pageTitle = "Add Event";
include 'include/header.php';
include 'include/header-navigation.php';
include 'include/navigation.php';
if(isset($_GET['id']) && $_GET['id'] != ''){
  $sql = 'SELECT * FROM `events`WHERE MD5(id) = "'.$_GET['id'].'"';
  $result = mysqli_query($conn, $sql);
  $data = mysqli_fetch_assoc($result);
}

?>
<style>
  .btn
  {
    padding-left:2.5rem;
    padding-right:2.5rem;
    text-transform:uppercase;
  }
  .image.p-image img {
    height: 2.1rem;
}
</style>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" href="assets/web/css/custom.css">
<style>
  .age-error {
      padding: 35px;
  }
  </style>
<style>
/*!
 * Timepicker Component for Twitter Bootstrap
 *
 * Copyright 2013 Joris de Wit
 *
 * Contributors https://github.com/jdewit/bootstrap-timepicker/graphs/contributors
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
.bootstrap-timepicker {
  position: relative;
}
.bootstrap-timepicker.pull-right .bootstrap-timepicker-widget.dropdown-menu {
  left: auto;
  right: 0;
}
.bootstrap-timepicker.pull-right .bootstrap-timepicker-widget.dropdown-menu:before {
  left: auto;
  right: 12px;
}
.bootstrap-timepicker.pull-right .bootstrap-timepicker-widget.dropdown-menu:after {
  left: auto;
  right: 13px;
}
.bootstrap-timepicker .input-group-addon {
  cursor: pointer;
}
.bootstrap-timepicker .input-group-addon i {
  display: inline-block;
  width: 16px;
  height: 16px;
}
.bootstrap-timepicker-widget.dropdown-menu {
  padding: 4px;
}
.bootstrap-timepicker-widget.dropdown-menu.open {
  display: inline-block;
}
.bootstrap-timepicker-widget.dropdown-menu:before {
  border-bottom: 7px solid rgba(0, 0, 0, 0.2);
  border-left: 7px solid transparent;
  border-right: 7px solid transparent;
  content: "";
  display: inline-block;
  position: absolute;
}
.bootstrap-timepicker-widget.dropdown-menu:after {
  border-bottom: 6px solid #FFFFFF;
  border-left: 6px solid transparent;
  border-right: 6px solid transparent;
  content: "";
  display: inline-block;
  position: absolute;
}
.bootstrap-timepicker-widget.timepicker-orient-left:before {
  left: 6px;
}
.bootstrap-timepicker-widget.timepicker-orient-left:after {
  left: 7px;
}
.bootstrap-timepicker-widget.timepicker-orient-right:before {
  right: 6px;
}
.bootstrap-timepicker-widget.timepicker-orient-right:after {
  right: 7px;
}
.bootstrap-timepicker-widget.timepicker-orient-top:before {
  top: -7px;
}
.bootstrap-timepicker-widget.timepicker-orient-top:after {
  top: -6px;
}
.bootstrap-timepicker-widget.timepicker-orient-bottom:before {
  bottom: -7px;
  border-bottom: 0;
  border-top: 7px solid #999;
}
.bootstrap-timepicker-widget.timepicker-orient-bottom:after {
  bottom: -6px;
  border-bottom: 0;
  border-top: 6px solid #ffffff;
}
.bootstrap-timepicker-widget table {
  width: 100%;
  margin: 0;
}
.bootstrap-timepicker-widget table td {
  text-align: center;
  height: 30px;
  margin: 0;
  padding: 2px;
}
.bootstrap-timepicker-widget table td:not(.separator) {
  min-width: 30px;
}
.bootstrap-timepicker-widget table td span {
  width: 100%;
}
.bootstrap-timepicker-widget table td a {
  border: 1px transparent solid;
  width: 100%;
  display: inline-block;
  margin: 0;
  padding: 8px 0;
  outline: 0;
  color: #333;
}
.bootstrap-timepicker-widget table td a:hover {
  text-decoration: none;
  background-color: #eee;
  -webkit-border-radius: 4px;
  -moz-border-radius: 4px;
  border-radius: 4px;
  border-color: #ddd;
}
.bootstrap-timepicker-widget table td a i {
  margin-top: 2px;
  font-size: 18px;
}
.bootstrap-timepicker-widget table td input {width: 25px;margin: 0;text-align: center;border:none}
.bootstrap-timepicker-widget .modal-content {
  padding: 4px;
}
@media (min-width: 767px) {
  .bootstrap-timepicker-widget.modal {
    width: 200px;
    margin-left: -100px;
  }
}
@media (max-width: 767px) {
  .bootstrap-timepicker {
    width: 100%;
  }
  .bootstrap-timepicker .dropdown-menu {
    width: 100%;
  }
}

</style>
<script src="assets/web/local_assets/js/event.js"></script>
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
                ?>
              <h3 class="card-title">Update Event</h3>
                <?php
              }else{
              ?>
              <h3 class="card-title">Add Event</h3>
              <?php } ?>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form id="add_event_details" name="add_event_details">
              <div class="card-body">
                <div class="row">
                  <div class="col-12 col-sm-12 col-md-4">
                    <div id="form-group-event_name" class="form-group">
                      <label for="event_name">Event Name&nbsp;<span class="is-required">*</span></label>
                      <input type="text" name="event_name" class="form-control" id="event_name" placeholder="Please enter event name" value="<?php echo (isset($data['title']) && $data['title'] != "") ? $data['title'] : ""; ?>">
                      <small id="event_name-error" class="form-text text-muted"></small>
                    </div>
                  </div>
                  <div class="col-12 col-sm-12 col-md-4">
                    <div id="form-group-category_name" class="form-group">
                      <label for="category_name">Event Category&nbsp;<span class="is-required">*</span></label>
                        <select class="custom-select" name="category_id" id="category_id" <?php if(isset($_GET['id']) && $_GET['id'] != ''){ echo "disabled"; }?>>
                          <option value="">Select category</option>
                          <?php 
                          $category_query = 'SELECT * FROM `categories`WHERE category_id = "0" AND status = "1"';
                          $category_result = mysqli_query($conn, $category_query);
                          while($category_data = mysqli_fetch_assoc($category_result)){
                          ?>
                          <option <?php echo (isset($data['category_id']) && $data['category_id'] != "" && $data['category_id'] == $category_data['id']) ? "selected" : ""; ?> value="<?php echo $category_data['id']; ?>"><?php echo $category_data['name']; ?></option>
                          <?php } ?>
                        </select>
                        <?php 
                        if(isset($_GET['id']) && $_GET['id'] != ""){
                          ?>
                        <input type="hidden" value="<?php echo $data['category_id']; ?>" name="category_id"/>
                        <?php
                        }
                        ?>
                    </div>
                  </div>
                  <div class="col-12 col-sm-12 col-md-4">
                    <div id="form-group-sub_category_id" class="form-group">
                      <label for="sub_category_id">Event Sub Category&nbsp;</label>
                        <select class="custom-select" name="sub_category_id" id="sub_category_id" <?php if(isset($_GET['id']) && $_GET['id'] != ''){ echo "disabled"; }?>>
                          <option value="">Select sub category</option>
                          <?php
                          if(isset($_GET['id']) && $_GET['id'] != ''){
                            $sub_category_query = 'SELECT * FROM `categories`WHERE  category_id != "0" AND category_id = "'.$data['category_id'].'"';
                            $sub_category_result = mysqli_query($conn, $sub_category_query);
                            $sub_category_data = mysqli_fetch_assoc($sub_category_result);
                            ?>
                            <option <?php echo (isset($data['sub_category_id']) && $data['sub_category_id'] != "" && $data['sub_category_id'] == $sub_category_data['id']) ? "selected" : ""; ?> value="<?php echo $sub_category_data['id']; ?>"><?php echo $sub_category_data['name']; ?></option>
                            <?php
                          }
                          ?>
                        </select>
                        <?php 
                        if(isset($_GET['id']) && $_GET['id'] != ""){
                          ?>
                        <input type="hidden" value="<?php echo $data['sub_category_id']; ?>" name="sub_category_id"/>
                        <?php
                        }
                        ?>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12 col-sm-12 col-md-4">
                    <div class="form-group">
                      <label>Event Date<span class="is-required">*</span></label>
                        <div class="input-group date" id="reservationdate" name="date" data-target-input="nearest">
                            <input type="text" autocomplete="off" id="datepicker" name="date" class="form-control datetimepicker-input" value="<?php echo (isset($data['event_date']) && $data['event_date'] != "") ? date("d/m/Y", strtotime($data['event_date'])) : ""; ?>" data-target="#reservationdate"/>
                            <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                  </div>
                  <div class="col-12 col-sm-12 col-md-4">
                    <div class="form-group">
                      <label>Arrival Time<span class="is-required">*</span></label>
                        <div class="input-group date" id="reservationdate" data-target-input="nearest">
                            <input type="text" autocomplete="off" class="form-control datetimepicker-input"  name="time"id="datepicker2" value="<?php echo (isset($data['event_time']) && $data['event_time'] != "") ? date("g:i a", strtotime($data['event_time'])) : ""; ?>" data-target="#reservationdate"/>
                            <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                  </div>
                  
                  
                </div>
                <div class="row">
                <div class="col-12 col-sm-12 col-md-4">
                    <div class="form-group">
                      <label>Registration End Date<span class="is-required">*</span></label>
                        <div class="input-group date" id="reservationdate" name="registration_end_date" data-target-input="nearest">
                            <input type="text" autocomplete="off" class="form-control datetimepicker-input" name="registeration_end_date" value="<?php echo (isset($data['registration_end_date']) && $data['registration_end_date'] != "") ? date("d/m/Y", strtotime($data['registration_end_date'])) : ""; ?>" id="datepicker3" data-target="#reservationdate"/>
                            <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-12 col-md-4">
                    <div class="form-group">
                      <label>Registration End Time<span class="is-required">*</span></label>
                        <div class="input-group date" id="reservationdate" data-target-input="nearest">
                            <input type="text" autocomplete="off" class="form-control datetimepicker-input"  name="register_end_time"id="datepicker5" value="<?php echo (isset($data['registration_end_time']) && $data['registration_end_time'] != "") ? date("g:i a", strtotime($data['registration_end_time'])) : ""; ?>" data-target="#reservationdate"/>
                            <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6">
                    <!-- checkbox -->
                    <?php 
                    if(isset($_GET['id']) && $_GET['id'] != ''){
                      // age gender get
                      $age_query = 'SELECT agc.id  FROM `event_age_gender_categories` eac LEFT JOIN age_gender_categories agc ON eac.age_gender_id = agc.id WHERE event_id = "'.$data['id'].'"';
                      $age_result = mysqli_query($conn, $age_query);
                      while($age_data = mysqli_fetch_assoc($age_result)){
                          $event_array[] = $age_data;
                      }
                    }
                    ?>
                    <label> Age & Gender</label>
                    <div class="form-group clearfix">
                      <?php 
                      $sql = 'SELECT * FROM `age_gender_categories` ORDER BY `age_gender_categories`.`title` ASC';
                      $result = mysqli_query($conn, $sql);
                      $i=1;
                      while($age_gender = mysqli_fetch_assoc($result)){
                      ?>
                      <div class="icheck-primary d-inline">
                        <input type="radio" name="age[]" <?php if(isset($_GET['id']) && $_GET['id'] != ''){ echo "disabled"; }?>  <?php if(!empty($event_array)){ if(isset($_GET['id']) && $_GET['id'] != ''){ if(array_search($age_gender['id'], array_column($event_array, 'id')) !== false) { echo 'checked';} } } ?> class="age_gender_check" value="<?php echo $age_gender['id']; ?>" id="age_gender_categories<?php echo $i; ?>">
                        <label for="age_gender_categories<?php echo $i; ?>">
                          <?php echo $age_gender['title']; ?>
                        </label>
                      </div>
                      <?php $i++; } ?>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6">
                  <?php 
                  if(isset($_GET['id']) && $_GET['id'] != ''){
                    // grade get
                    $grade_query = 'SELECT g.id FROM `event_grade_categories` egc LEFT JOIN grades g ON g.id = egc.grade_id WHERE event_id = "'.$data['id'].'"';
                    $grade_result = mysqli_query($conn, $grade_query);
                    while($grade_data = mysqli_fetch_assoc($grade_result)){
                        $grade_array[] = $grade_data;
                    }
                  }
                  ?>
                    <!-- checkbox -->
                    <label> Grade </label>
                    <div class="form-group clearfix">
                      <?php 
                      $sql = 'SELECT * FROM `grades`';
                      $result = mysqli_query($conn, $sql);
                      $i=1;
                      while($age_gender = mysqli_fetch_assoc($result)){
                      ?>
                      <div class="icheck-primary d-inline">
                        <input type="radio" class="grades_check" name="grades[]" <?php if(isset($_GET['id']) && $_GET['id'] != ''){ echo "disabled"; }?> <?php if(!empty($grade_array)){ if(isset($_GET['id']) && $_GET['id'] != ''){ if(array_search($age_gender['id'], array_column($grade_array, 'id')) !== false) { echo 'checked';} } } ?> value="<?php echo $age_gender['id']; ?>" id="grades<?php echo $i; ?>">
                        <label for="grades<?php echo $i; ?>">
                          <?php echo $age_gender['name']; ?>
                        </label>
                      </div>
                      <?php $i++; } ?>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12 col-sm-12 col-md-4">
                      <label for="category_name" class="label-text">Upload Important Event Details&nbsp;</label>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12 col-sm-12 col-md-4">
                    <label for="category_name" class="label-text">PDF Document&nbsp;</label>
                    <div class="custom-file">
                      <input type="file" accept="application/pdf" class="custom-file-input" name="pdf_document[]" multiple id="customFile">
                      <label class="custom-file-label" for="files">Choose file</label>
                      
                    </div>
                    <?php
                    // Document get
                    $document = array();
                    $document_query1 = 'SELECT * FROM `event_documents` WHERE event_id = '.$data['id'].' AND type = "pdf_document"';
                    $result = mysqli_query($conn, $document_query1);
                    while($document_data = mysqli_fetch_assoc($result)){
                        $document_id = $document_data['id'];
                        $document_name = BASE_URL.'upload/event_document/'.$document_data['file_name'];
                        $event_array['document'][$document_data['type']][] = $document;
                        //$event_array['document']['type'][] = $document_data['type'];
                   
                    ?>
                    <div class="pdf_bottom_box">
                      <ul>
                        <li>
                          <img src="assets/web/images/pdf.png">
                        </li>
                        <li class="max-lines">
                          <?php echo $document_data['file_name']; ?>
                        </li>
                        <li>
                          <img class="delete-icon pdf_delete" data-id="<?php echo $document_id; ?>" src="assets/web/images/trash.png">
                        </li>
                      </ul>
                    </div>
                  <?php } ?>
                  </div>
               
                </div>
                <div class="row" style="margin-top: 20px;margin-bottom:10px;">
                     <div class="col-12 col-sm-12 col-md-4">
                    <label for="category_name" class="label-text">Waiver Form&nbsp;</label>
                    <div class="custom-file">
                      <input type="file" accept="application/pdf" class="custom-file-input" name="waiver_from[]" multiple id="customFile">
                      <label class="custom-file-label" for="customFile">Choose file</label>
                    </div>
                    <?php
                    // Document get
                    $document = array();
                    $document_query1 = 'SELECT * FROM `event_documents` WHERE event_id = '.$data['id'].' AND type = "waiver_from"';
                    $result = mysqli_query($conn, $document_query1);
                    while($document_data = mysqli_fetch_assoc($result)){
                        $document_id = $document_data['id'];
                        $document_name = BASE_URL.'upload/event_document/'.$document_data['file_name'];
                        $event_array['document'][$document_data['type']][] = $document;
                        //$event_array['document']['type'][] = $document_data['type'];
                   
                    ?>
                    <div class="pdf_bottom_box">
                      <ul>
                        <li>
                          <img src="assets/web/images/pdf.png">
                        </li>
                        <li class="max-lines">
                          <?php echo $document_data['file_name']; ?>
                        </li>
                        <li>
                          <img class="delete-icon waiver_delete" data-id="<?php echo $document_id; ?>" src="assets/web/images/trash.png">
                        </li>
                      </ul>
                    </div>
                    <?php } ?>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12 col-sm-12 col-md-4">
                      <label for="category_name" class="label-text">Event Notes&nbsp;<span class="is-required">*</span></label>
                      <textarea class="form-control" rows="3" name="event_notes" placeholder="Write Event Notes ..."><?php echo (isset($data['event_external_notes']) && $data['event_external_notes'] != "") ? $data['event_external_notes'] : ""; ?></textarea>
                  </div>
                  <div class="col-12 col-sm-12 col-md-4">
                      <label for="category_name" class="label-text">Internal Notes&nbsp;<span class="is-required">*</span></label>
                      <textarea class="form-control" rows="3" name="internal_notes" placeholder="Write Internal Notes ..."><?php echo (isset($data['event_internal_notes']) && $data['event_internal_notes'] != "") ? $data['event_internal_notes'] : ""; ?></textarea>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6">
                    <?php
                    if(isset($_GET['id']) && $_GET['id'] != ''){
                      // council get
                      $council_query = 'SELECT u.id,u.first_name,u.middle_name,u.last_name FROM `event_council_members` ecm LEFT JOIN users u ON ecm.council_member = u.id WHERE ecm.event_id = "'.$data['id'].'"';
                      $council_result = mysqli_query($conn, $council_query);
                      while($council_data = mysqli_fetch_assoc($council_result)){
                          $council_array[] = $council_data;
                      }
                      //print_r($council_array);exit;
                    }
                    ?>
                    <!-- checkbox -->
                    <label class="label-text"> Select Council Members</label>
                    <div class="form-group clearfix">
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
                      <?php $i++; } ?>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12 col-sm-12 col-md-4">
                    <label for="category_name">Upload Event Image&nbsp;</label>
                    <div class="custom-file">
                      <input type="file"  class="custom-file-input" name="event_images" multiple id="files">
                      <label class="custom-file-label" for="customFile">Choose file</label>
                    </div>
                    <?php 
                    if(isset($_GET['id']) && $_GET['id'] != ''){
                      // Images get
                      $images = array();
                      $images_query = 'SELECT * FROM `event_images` WHERE event_id = "'.$data['id'].'"';
                      $images_result = mysqli_query($conn, $images_query);
                      $images_data = mysqli_fetch_assoc($images_result);
                      if(!empty($images_data)){
                    ?>
                    <div class="pdf_bottom_box">
                      <ul>
                        <li>
                          <img src="<?php echo BASE_URL.'upload/event_images/'.$images_data['image_url']; ?>">
                        </li>
                        <li class="max-lines">
                          <?php echo $images_data['image_url']; ?>
                        </li>
                        <li>
                          <img class="delete-icon image_delete" data-id="<?php echo $images_data['id']; ?>" src="assets/web/images/trash.png">
                        </li>
                      </ul>
                    </div>
                    <?php } } ?>
                  </div>
                </div>

                
                

              </div>

              <!-- /.card-body -->
              <div class="card-footer">
                <?php 
                if(isset($_GET['id']) && $_GET['id'] != ''){
                  ?>
                <input type="hidden" name="event_id" value = "<?php echo $data['id']; ?>" />
                  <input type="hidden" name = "update_id" id="update_id" value="<?php echo $_GET['id']; ?>" />
                <button type="submit" id="update_event_button" name="btn-admin-change-credentials" onclick="return update_validate();" class="btn btn-primary">UPDATE</button>
                  <?php
                }else{
                  ?>
                <button type="submit" id="add_event_button" name="btn-admin-change-credentials" onclick="return validate_add_event();" class="btn btn-primary">PUBLISH</button>
                  <?php
                }
                ?>
                
              </div>
            </form>
          </div>
          <!-- /.card -->
        </div>
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
<script src="https://jdewit.github.io/bootstrap-timepicker/js/bootstrap-timepicker.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>

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
   $(function() {
$( "#datepicker" ).datepicker({
  dateFormat: 'dd/mm/yy',
  minDate: 0
});
});
    $(function() {
$( "#datepicker2" ).timepicker({
  defaultTime: false
});
});
$(function() {
$( "#datepicker5" ).timepicker({
  defaultTime: false
});
});
     $(function() {
$( "#datepicker3" ).datepicker({
  dateFormat: 'dd/mm/yy',
  minDate: 0
});
});
</script>
