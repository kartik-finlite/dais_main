<?php 
include 'include/conn.php';
if($_SESSION['user_details']['email_id'] == '' && $_SESSION['user_details']['password'] == ''){
  header('Location: index.php');
}
  $link = $_SERVER['PHP_SELF'];
  $link_array = explode('/',$link);
  $page = end($link_array);
  if($page != "change-password.php"){
    if($_SESSION['user_details']['change_password_flag'] == 0){
      header('Location: change-password.php');
    }
  }


// check status
$sql = 'SELECT `status` FROM `users`WHERE id = "'.$_SESSION['user_details']['id'].'"';
$result = mysqli_query($conn, $sql);
$user_id_data = mysqli_fetch_assoc($result);
if($user_id_data['status'] == 0){
  session_unset();
  session_destroy();
  session_start();
  $_SESSION['FLASH_ERROR_FLAG'] = "Your account is not activated, Please contact to admin.";
  header('Location: index.php');
}
?>