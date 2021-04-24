<?php
include 'include/conn.php';
if(isset($_POST['action']) && $_POST['action'] == "admin-login-attempt"){
  $email = (isset($_POST['email']) && $_POST['email'] != "") ? $_POST['email'] : "";
  $password = (isset($_POST['password']) && $_POST['password'] != "") ? $_POST['password'] : "";
  $sql = 'SELECT * FROM `users`WHERE email_id = "'.$email.'" AND password = MD5("'.$password.'") AND status = "1" ';
  $result = mysqli_query($conn, $sql);
  $user_data = mysqli_fetch_assoc($result);
  if(isset($user_data) && $user_data > 0){
    if($user_data['change_password_flag'] == "0"){
      $qry = 'UPDATE `users` SET last_login = "'.date('Y-m-d H:i:s').'" , first_date_time = "'.date('Y-m-d H:i:s').'",first_login_by = "website" WHERE id ="'.$user_data['id'].'"';
    }else{
      $qry = 'UPDATE `users` SET last_login = "'.date('Y-m-d H:i:s').'" WHERE id ="'.$user_data['id'].'"';
    }
    
    $result = mysqli_query($conn, $qry);
    $_SESSION['user_details'] = $user_data;
    $res['status'] = "1";
    $res['message'] = "Login into Admin panel.";
    $res['data'] = $user_data;
    $_SESSION['FLASH_SUCCESS_FLAG'] = "Login into Admin panel.";
    echo json_encode($res);
    return false;
  }else{
    
    // check email 
    $sql = 'SELECT * FROM `users`WHERE email_id = "'.$email.'"';
    $result = mysqli_query($conn, $sql);
    $user_count = mysqli_num_rows($result);
    $user_data = mysqli_fetch_assoc($result);
    if($user_count == 0){
        $res['status'] = "0";
        $res['message'] = "Invalid email eddress";
        //$_SESSION['FLASH_ERROR_FLAG'] = "Invalid email eddress";
        $res['data'] = new arrayObject();
        echo json_encode($res);
        return false;
    }
    // password check
    $password_check = 'SELECT * FROM `users`WHERE password = MD5("'.$password.'") AND id="'.$user_data['id'].'"';
    $password_check_result = mysqli_query($conn, $password_check);
    $password_check_count = mysqli_num_rows($password_check_result);
    if($password_check_count == 0){
        $res['status'] = "0";
        $res['message'] = "Invalid password.";
        //$_SESSION['FLASH_ERROR_FLAG'] = "Invalid password.";
        $res['data'] = new arrayObject();
        echo json_encode($res);
        return false;
    }

    // status check
    $status_check = 'SELECT * FROM `users`WHERE AND status = "1" AND id = "'.$user_data['id'].'"';
    $status_check_result = mysqli_query($conn, $status_check);
    $status_check_count = mysqli_num_rows($status_check_result);
    if($status_check_count == 0){
        $res['status'] = "0";
        $res['message'] = "Your account is not activated, Please contact to admin.";
        //$_SESSION['FLASH_ERROR_FLAG'] = "Your account is not activated, Please contact to admin.";
        $res['data'] = new arrayObject();
        echo json_encode($res);
        return false;
    }
  }
}
?>