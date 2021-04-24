<?php
include 'include/session_check.php';
$page_title = "Login";
include 'include/conn.php';
require('include/XLSXReader.php');
// $xlsx = new XLSXReader('Admin Master Sheet.xlsx');
// $sheetNames = $xlsx->getSheetNames();
// $data = $xlsx->getSheetData('Sheet1');
// echo "<pre>";
// print_r($data);exit;
if(isset($_POST['submit_button'])){
  $xlsx = new XLSXReader($_FILES['filename']['tmp_name']);
  $sheetNames = $xlsx->getSheetNames();
  $data = $xlsx->getSheetData('Sheet1');
  // echo "<pre>";
  // print_r($data);exit;
  if(!empty($data)){
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
            `dob` = "'.date("Y-m-d", strtotime($data[$i][9])).'",
            `phone` = "'.$data[$i][10].'",
            `house_id` = "'.$house_id.'",
            `user_type_id`  = "'.$user_type_id.'",
            `parent_phone`  = "'.$data[$i][13].'",
            `modified_date` = "'.date('Y-m-d H:i:s').'",
            `password` = "'.MD5($data[$i][14]).'"
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
            `dob` = "'.date("Y-m-d", strtotime($data[$i][9])).'",
            `phone` = "'.$data[$i][10].'",
            `house_id` = "'.$house_id.'",
            `user_type_id`  = "'.$user_type_id.'",
            `parent_phone`  = "'.$data[$i][13].'",
            `password` = "'.MD5($data[$i][14]).'",
            `status` = "1",
            `created_date` = "'.date('Y-m-d H:i:s').'"
            ';
      }
      $result = mysqli_query($conn, $query);
      if($result){
        
      }else{
        $error[] = "Line Number : ".($i+1)."";
      }
    }
    foreach($error as $key=>$value){
      echo $value;
    }
  }
  
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

<form action="" method="POST" enctype="multipart/form-data">
  <input type="file" id="myFile" name="filename">
  <input type="submit" name="submit_button">
</form>

