<?php
include 'include/session_check.php';
$title = "User Data Upload";
$pageTitle = "User Data Upload";
include 'include/header.php';
include 'include/header-navigation.php';
include 'include/navigation.php';
require('include/XLSXReader.php');
require_once('Classes/PHPExcel.php');
if(isset($_POST['submit_button'])){
  $maxsize    = 5242880;
  if(($_FILES['filename']['size'] >= $maxsize) || ($_FILES["filename"]["size"] == 0)) {
      $_SESSION["FLASH_ERROR_FLAG"] = "File too large. File must be less than 5 MB";
  }else{
    $xlsx = new XLSXReader($_FILES['filename']['tmp_name']);
    $sheetNames = $xlsx->getSheetNames();
    $data = $xlsx->getSheetData('Sheet1');
    // echo"<pre>";
    // print_r($data);exit;

    // Verification check

    // Email Verification
    if(!empty($data)){
      $error_flag = 0;
      $count = count($data);
      $email_check = array();
      $data[$i][14] = "";
      for ($i=1; $i < $count ; $i++) {
        // email duplication check
        if(in_array($data[$i][8],$email_check)){
          $error_flag = "1";
          $data[$i][14] .= "Duplicate email address"."\n";
        }else{
          $email_check[] = $data[$i][8];
        }

        // first name empty check
        if(empty($data[$i][5])){
          $error_flag = "1";
          $data[$i][14] .= "First Name field is requied"."\n";
        }

        // house empty check 
        if(empty($data[$i][11])){
          $error_flag = "1";
          $data[$i][14] .= "House field is requied"."\n";
        }else{
          // house match check 
          $house_id = house_check($data[$i][11]);
          if(empty($house_id)){
            $error_flag = "1";
            $data[$i][14] .= "Hosuse name does not match"."\n";
          }
        }

        // Access Level empty check 
        if(empty($data[$i][12])){
          $error_flag = "1";
          $data[$i][14] .= "Access Level field is requied"."\n";
        }else{
          // user_type_check match check 
          $user_type_id = user_type_check($data[$i][12]);
          if(empty($user_type_id)){
            $error_flag = "1";
            $data[$i][14] .= "User type does not match"."\n";
          }
        }

        // birth date check
        //$var = (string)$data[$i][9];
        $birth_date = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($data[$i][9]));
        //$birth_date1 = str_replace('/', '-', $var); 
        //$birth_date = date("Y-m-d", strtotime($birth_date1));
        if($birth_date == "1970-01-01"){
          $error_flag = "1";
          $data[$i][14] .= "Invalid date format (Ex. dd/mm/yyyy)"."\n";
        }

        // check gender name
        if($data[$i][7] == "Male" || $data[$i][7] == "Female"){
          
        }else{
          $error_flag = "1";
          $data[$i][14] .= "Invalid gender value (Ex. Male or Female)"."\n";
        }


      }

      if($error_flag == "1"){
        // require_once('Classes/PHPExcel.php');
        // $doc = new PHPExcel();
        // print_r($doc);exit;
        // $doc->setActiveSheetIndex(0);
        // Excel file name for download 
        $objPHPExcel = new PHPExcel();
        //$fileName = time().".xls";
        $fileName = $_FILES['filename']['name'];
        $i = 1;
        $blueBold = array("font" => array("bold" => true, "color" => array("rgb" => "FF0000"),),);
        foreach($data as $key => $value){
          $objPHPExcel->getActiveSheet()->setCellValue("A".$i, $value[0]);
          $objPHPExcel->getActiveSheet()->setCellValue("B".$i, $value[1]);
          $objPHPExcel->getActiveSheet()->setCellValue("C".$i, $value[2]);
          $objPHPExcel->getActiveSheet()->setCellValue("D".$i, $value[3]);
          $objPHPExcel->getActiveSheet()->setCellValue("E".$i, $value[4]);
          $objPHPExcel->getActiveSheet()->setCellValue("F".$i, $value[5]);
          $objPHPExcel->getActiveSheet()->setCellValue("G".$i, $value[6]);
          $objPHPExcel->getActiveSheet()->setCellValue("H".$i, $value[7]);
          $objPHPExcel->getActiveSheet()->setCellValue("I".$i, $value[8]);
          $objPHPExcel->getActiveSheet()->setCellValue("J".$i, $value[9]);
          $objPHPExcel->getActiveSheet()->setCellValue("K".$i, $value[10]);
          $objPHPExcel->getActiveSheet()->setCellValue("L".$i, $value[11]);
          $objPHPExcel->getActiveSheet()->setCellValue("M".$i, $value[12]);
          $objPHPExcel->getActiveSheet()->setCellValue("N".$i, $value[13]);
          $objPHPExcel->getActiveSheet()->setCellValue("O".$i, $value[14]);
          $objPHPExcel->getActiveSheet()->getStyle("O".$i)->applyFromArray($blueBold);
          $i++;
        }
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save("upload/xls_error/".$fileName);
        header("Content-Type: application/vnd.ms-excel");
        $_SESSION["FLASH_ERROR_FLAG"] = "<a href='upload/xls_error/".$fileName."' style='color:#2d2d2d !important' download>Error in excel sheet, Click here to download.</a>";

      }else{
        $count = count($data);
        $counter = 0;
        $errors = array();
        for ($i=1; $i < $count ; $i++) { 
          $phone_number = $data[$i][10];
          $email = $data[$i][8];
          $house = $data[$i][11];
          $house_id = house_check($house);
          $access_level = $data[$i][12];
          $user_type_id = user_type_check($access_level);
          $check_data_id = check_data($phone_number,$email);
          // $var = (string)$data[$i][9];
          // $birth_date1 = str_replace('/', '-', $var); 
          //$birth_date = date("Y-m-d", strtotime($birth_date1));
          $birth_date = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($data[$i][9]));
          if(!empty($check_data_id)){
            $query = 'UPDATE users SET 
                `class_sr_no` = "'.$data[$i][1].'",
                `class` = "'.$data[$i][2].'",
                `div` = "'.$data[$i][3].'",
                `last_name` = "'.$data[$i][4].'",
                `first_name` = "'.$data[$i][5].'",
                `middle_name` = "'.$data[$i][6].'",
                `gender` = "'.$data[$i][7].'",
                `email_id` = "'.$data[$i][8].'",
                `dob` = "'.$birth_date.'",
                `phone` = "'.$data[$i][10].'",
                `house_id` = "'.$house_id.'",
                `user_type_id`  = "'.$user_type_id.'",
                `parent_phone`  = "'.$data[$i][13].'",
                `modified_date` = "'.date('Y-m-d H:i:s').'",
                `password` = "'.MD5("123456").'"
                WHERE `id` = "'.$check_data_id.'"
                ';
          }else{
            $query = 'INSERT INTO users SET 
                `class_sr_no` = "'.$data[$i][1].'",
                `class` = "'.$data[$i][2].'",
                `div` = "'.$data[$i][3].'",
                `last_name` = "'.$data[$i][4].'",
                `first_name` = "'.$data[$i][5].'",
                `middle_name` = "'.$data[$i][6].'",
                `gender` = "'.$data[$i][7].'",
                `email_id` = "'.$data[$i][8].'",
                `dob` = "'.$birth_date.'",
                `phone` = "'.$data[$i][10].'",
                `house_id` = "'.$house_id.'",
                `user_type_id`  = "'.$user_type_id.'",
                `parent_phone`  = "'.$data[$i][13].'",
                `password` = "'.MD5("123456").'",
                `status` = "1",
                `created_date` = "'.date('Y-m-d H:i:s').'"
                ';
                
          }
          $result = mysqli_query($conn, $query);
          
        }
        $_SESSION['FLASH_SUCCESS_FLAG'] = "Xls updated successfully.";
      }
    }
  }

  // if(!empty($data)){
  //   $count = count($data);
  //   $counter = 0;
  //   $errors = array();
  //   for ($i=1; $i < $count ; $i++) { 
  //     $phone_number = $data[$i][10];
  //     $email = $data[$i][8];
  //     $house = $data[$i][11];
  //     $house_id = house_check($house);
  //     $access_level = $data[$i][12];
  //     $user_type_id = user_type_check($access_level);
  //     $check_data_id = check_data($phone_number,$email);

  //     if(!empty($check_data_id)){
  //       $query = 'UPDATE users SET 
  //           `class_sr_no` = "'.$data[$i][1].'",
  //           `class` = "'.$data[$i][2].'",
  //           `div` = "'.$data[$i][3].'",
  //           `last_name` = "'.$data[$i][4].'",
  //           `first_name` = "'.$data[$i][5].'",
  //           `middle_name` = "'.$data[$i][6].'",
  //           `gender` = "'.$data[$i][7].'",
  //           `email_id` = "'.$data[$i][8].'",
  //           `dob` = "'.date("Y-m-d", strtotime($data[$i][9])).'",
  //           `phone` = "'.$data[$i][10].'",
  //           `house_id` = "'.$house_id.'",
  //           `user_type_id`  = "'.$user_type_id.'",
  //           `parent_phone`  = "'.$data[$i][13].'",
  //           `modified_date` = "'.date('Y-m-d H:i:s').'",
  //           `password` = "'.MD5($data[$i][14]).'"
  //           WHERE `id` = "'.$check_data_id.'"
  //           ';
  //     }else{
  //       $query = 'INSERT INTO users SET 
  //           `class_sr_no` = "'.$data[$i][1].'",
  //           `class` = "'.$data[$i][2].'",
  //           `div` = "'.$data[$i][3].'",
  //           `last_name` = "'.$data[$i][4].'",
  //           `first_name` = "'.$data[$i][5].'",
  //           `middle_name` = "'.$data[$i][6].'",
  //           `gender` = "'.$data[$i][7].'",
  //           `email_id` = "'.$data[$i][8].'",
  //           `dob` = "'.date("Y-m-d", strtotime($data[$i][9])).'",
  //           `phone` = "'.$data[$i][10].'",
  //           `house_id` = "'.$house_id.'",
  //           `user_type_id`  = "'.$user_type_id.'",
  //           `parent_phone`  = "'.$data[$i][13].'",
  //           `password` = "'.MD5($data[$i][14]).'",
  //           `status` = "1",
  //           `created_date` = "'.date('Y-m-d H:i:s').'"
  //           ';
            
  //     }
  //     $result = mysqli_query($conn, $query);
  //     if($result){
  //       $_SESSION['FLASH_SUCCESS_FLAG'] = "Xls updated successfully.";
  //     }else{
  //       $error[] = "Line Number : ".($i+1)."";
  //     }
  //   }
    
  // }
  
}

function check_data($phone = null,$email = null){
  global $conn;
  $sql = 'SELECT * FROM `users` WHERE email_id ="'.$email.'"';
  $result = mysqli_query($conn, $sql);
  $user_data = mysqli_fetch_assoc($result);
  $count = mysqli_num_rows($result);
  if($count > 0){
    return $user_data['id'];
  }
}

function house_check($house = null){
  global $conn;
  $sql = 'SELECT * FROM `houses` WHERE `name` LIKE "'.$house.'"';
  $result = mysqli_query($conn, $sql);
  $house_data = mysqli_fetch_assoc($result);
  $count = mysqli_num_rows($result);
  if($count > 0){
    return $house_data['id'];
  }
}

function user_type_check($user_type = null){
  global $conn;
  $sql = 'SELECT * FROM `user_type` WHERE `name` LIKE "'.$user_type.'"';
  $result = mysqli_query($conn, $sql);
  $user_type_data = mysqli_fetch_assoc($result);
  $count = mysqli_num_rows($result);
  if($count > 0){
    return $user_type_data['id'];
  }
}

?>

<script src="assets/web/local_assets/js/change-password.js"></script>
<!-- Content Wrapper. Contains page content -->
<style>
  .alert-danger {
    color: #2d2d2d !important;
    background-color: #ff949f !important;
    border-color: #ff949f !important;
}
</style>
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
          <?php
          if (isset($_SESSION['FLASH_ERROR_FLAG']) && $_SESSION['FLASH_ERROR_FLAG'] != '') {
          ?>
            <div class="alert alert-danger alert-dismissible fade show error-for-upload">
                <?php echo $_SESSION['FLASH_ERROR_FLAG']; ?>
                <?php unset ($_SESSION['FLASH_ERROR_FLAG']); ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
            <!-- <div class="alert alert-error alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              <h5><i class="icon fas fa-check"></i> Alert!</h5>
              <?php //echo $_SESSION['FLASH_ERROR_FLAG']; ?>
              <?php //unset ($_SESSION['FLASH_ERROR_FLAG']); ?>
            </div> -->
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
              <h3 class="card-title">User Data Upload</h3>
            </div>
            <?php 
            foreach($error as $key=>$value){
              echo $value;
              echo "<br>";
            }
            ?>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="" method="POST" enctype="multipart/form-data">
              <div class="card-body">
                <div class="row">
                  <div class="col-md-12">
                  <?php 
                  if(!empty($_SESSION['email_error'])){
                  ?>
                   <?php
                      $txt = "Email Duplecation found for below Ids:";
                      //$myfile = fopen("upload/xls_error/newfile.txt", "wb") or die("Unable to open file!");
                      foreach($_SESSION['email_error'] as $key => $value){
                        $txt .= $value."\n";
                      }
                      
                      $file_name = time().".txt";
                      $myfile = file_put_contents('upload/xls_error/'.$file_name, $txt.PHP_EOL , FILE_APPEND | LOCK_EX);
                      ?>
                  <div class="alert alert-danger alert-dismissible fade show error-for-upload">
                      Email Duplecation found for below Ids:
                      <button type="button" class="close" data-dismiss="alert">&times;</button>
                  </div>
                     
                    <?php  } unset($_SESSION['email_error']); ?>
                  </div>
                  <div class="col-12 col-sm-12 col-md-6">
                    <div id="form-group-password" class="form-group">
                      <label for="password">User Upload XLS&nbsp;<span class="is-required">*</span></label><br>
                      <input type="file" id="myFile" name="filename" required>
                      <small id="password-error" class="form-text text-muted"></small>
                    </div>
                  </div>
                </div>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                <button type="submit" id="upload" name="submit_button" class="btn btn-primary">SUBMIT</button>
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
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <div class="row">
                <div class="col-12 col-md-6 col-xs-12">
                  <h3 class="card-title">User Listing</h3>
                </div>
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="tbl-celebrities-listing" class="table table-bordered table-striped table-responsive">
                <thead>
                  <tr>
                    <th>First Name</th>
                    <th>Middle Name</th>
                    <th>Last Name</th>
                    <th>Class Sr No</th>
                    <th>Class</th>
                    <th>Div</th>
                    <th>Gender</th>
                    <th>Date Of Birth</th>
                    <th>Email Address</th>
                    <th>Phone</th>
                    <th>Parent Phone</th>
                    <th>House</th>
                    <th>User Type</th>
                    <th>Login Time</th>
                    <th>Login From</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                <?php 
                $sql = 'SELECT u.*,h.name as house_name,ut.name as user_type_name FROM `users` u LEFT JOIN houses h ON u.house_id = h.id LEFT JOIN user_type ut ON ut.id = u.user_type_id';
                $result = mysqli_query($conn, $sql);
                while($user_data = mysqli_fetch_assoc($result)){
                  // echo"<pre>";
                  // print_r($user_data['first_date_time']);exit;
                ?>
                  <tr>
                  <td><?php echo (isset($user_data['first_name']) && $user_data['first_name'] != "") ? $user_data['first_name'] : ""; ?></td>
                  <td><?php echo (isset($user_data['middle_name']) && $user_data['middle_name'] != "") ? $user_data['middle_name'] : ""; ?></td>
                  <td><?php echo (isset($user_data['last_name']) && $user_data['last_name'] != "") ? $user_data['last_name'] : ""; ?></td>
                  <td><?php echo (isset($user_data['class_sr_no']) && $user_data['class_sr_no'] != "") ? $user_data['class_sr_no'] : ""; ?></td>
                  <td><?php echo (isset($user_data['class']) && $user_data['class'] != "") ? $user_data['class'] : ""; ?></td>
                  <td><?php echo (isset($user_data['div']) && $user_data['div'] != "") ? $user_data['div'] : ""; ?></td>
                  <td><?php echo (isset($user_data['gender']) && $user_data['gender'] != "") ? $user_data['gender'] : ""; ?></td>
                  <td><?php echo (isset($user_data['dob']) && $user_data['dob'] != "") ?date("d-m-Y", strtotime($user_data['dob'])) : ""; ?></td>
                  <td><?php echo (isset($user_data['email_id']) && $user_data['email_id'] != "") ? $user_data['email_id'] : ""; ?></td>
                  <td><?php echo (isset($user_data['phone']) && $user_data['phone'] != "") ? $user_data['phone'] : ""; ?></td>
                  <td><?php echo (isset($user_data['parent_phone']) && $user_data['parent_phone'] != "") ? $user_data['parent_phone'] : ""; ?></td>
                  <td><?php echo (isset($user_data['house_name']) && $user_data['house_name'] != "") ? $user_data['house_name'] : ""; ?></td>
                  <td><?php echo (isset($user_data['user_type_name']) && $user_data['user_type_name'] != "") ? $user_data['user_type_name'] : ""; ?></td>
                  <td><?php echo (isset($user_data['first_date_time']) && $user_data['first_date_time'] != "") ?date("d-m-Y H:i:s", strtotime($user_data['first_date_time'])) : ""; ?></td>
                  <td><?php echo (isset($user_data['first_login_by']) && $user_data['first_login_by'] != "") ? $user_data['first_login_by'] : ""; ?></td>
                  <td><a href="user_details.php?id=<?php echo MD5($user_data['id']); ?>"><i class="fas fa-edit" aria-hidden="true"></i></a></td>
                  
                  </tr>
                <?php } ?>
                </tbody>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
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
<script>
$('#tbl-celebrities-listing').DataTable();
</script>
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