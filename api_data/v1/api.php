<?php 
include '../../include/conn.php';
include 'function.php';
$link = $_SERVER['REQUEST_URI'];
$link_array = explode('/',$link);
$page_name = end($link_array);
try{
    if (isset($_SERVER["CONTENT_TYPE"]) && $_SERVER["CONTENT_TYPE"] == 'application/json') {
        $inputJSON 		= file_get_contents('php://input');
        $jsonObjectField   = json_decode($inputJSON);
        $requestField 	= json_decode(json_encode($jsonObjectField), true);
    }else{
        $requestField 	= $_POST;
    }
}
catch (Exception $e) {
    $res['status'] = '0';
    $res['message'] = "Url is Not Valid";
    $res['data'] = new arrayObject();
    echo json_encode($res);
    return false;
}
switch ($page_name) {
    case "login":
        $counter = 0;
        $errors = array();
        if(!isset($requestField['email'])){
            $counter++;
            $errors['email'] = "Email field is required";
        }
        if(!isset($requestField['password'])){
            $counter++;
            $errors['password'] = "Password field is required";
        }
        
        if($counter > 0 && count($errors) > 0){
            $res['status'] = '0';
            $res['message'] = "Total ".$counter." Found in Request";
            $res['data'] = $errors; 
            echo json_encode($res);
            return false;
        }
        $sql = 'SELECT * FROM `users`WHERE email_id = "'.$requestField['email'].'" AND password = MD5("'.$requestField['password'].'") AND status = "1"';
        $result = mysqli_query($conn, $sql);
        $user_id_data = mysqli_fetch_assoc($result);
        $user_count = mysqli_num_rows($result);
        if(isset($user_count) && $user_count > 0){
            $data = array();
            $add_query = 'INSERT INTO device_token SET user_id= "'.$user_id_data['id'].'",device_token = "'.$requestField['device_token'].'",device_type = "'.$requestField['device_type'].'",device_name = "'.$requestField['device_name'].'"';
            $result = mysqli_query($conn, $add_query);
            if(empty($user_id_data['api_access_token'])){
                $api_token = generateRandomString(64);
                $qry = 'UPDATE `users` SET api_access_token = "'.$api_token.'",last_login = "'.date('Y-m-d H:i:s').'" WHERE id ="'.$user_id_data['id'].'"';
            }else if($user_id_data['change_password_flag'] == "0"){
                $qry = 'UPDATE `users` SET last_login = "'.date('Y-m-d H:i:s').'", first_date_time = "'.date('Y-m-d H:i:s').'",first_login_by = "'.$requestField['device_type'].'" WHERE id ="'.$user_id_data['id'].'"';
            }else{
                $qry = 'UPDATE `users` SET last_login = "'.date('Y-m-d H:i:s').'" WHERE id ="'.$user_id_data['id'].'"';
            }
            $result = mysqli_query($conn, $qry);

            $sql = 'SELECT * FROM `users`WHERE email_id = "'.$requestField['email'].'" AND password = MD5("'.$requestField['password'].'")';
            $result = mysqli_query($conn, $sql);
            $user_data = mysqli_fetch_assoc($result);

            $data['user_id'] = (isset($user_data['id']) && $user_data['id'] != "") ? $user_data['id'] : "";
            $data['first_name'] = (isset($user_data['first_name']) && $user_data['first_name'] != "") ? $user_data['first_name'] : "";
            $data['middle_name'] = (isset($user_data['middle_name']) && $user_data['middle_name'] != "") ? $user_data['middle_name'] : "";
            $data['last_name'] = (isset($user_data['last_name']) && $user_data['last_name'] != "") ? $user_data['last_name'] : "";
            $data['class_sr_no'] = (isset($user_data['class_sr_no']) && $user_data['class_sr_no'] != "") ? $user_data['class_sr_no'] : "";
            $data['class'] = (isset($user_data['class']) && $user_data['class'] != "") ? $user_data['class'] : "";
            $data['div'] = (isset($user_data['div']) && $user_data['div'] != "") ? $user_data['div'] : "";
            $data['gender'] = (isset($user_data['gender']) && $user_data['gender'] != "") ? $user_data['gender'] : "";
            $data['house_id'] = (isset($user_data['house_id']) && $user_data['house_id'] != "") ? $user_data['house_id'] : "";
            $sql = 'SELECT * FROM `houses`WHERE id = "'.$user_data['house_id'].'"';
            $result = mysqli_query($conn, $sql);
            $house_data = mysqli_fetch_assoc($result);
            $data['house_name'] = (isset($house_data['name']) && $house_data['name'] != "") ? $house_data['name'] : "";
            $data['user_type_id'] = (isset($user_data['user_type_id']) && $user_data['user_type_id'] != "") ? $user_data['user_type_id'] : "";
            // Get User Role
            $role_query = 'SELECT name FROM `user_type` WHERE id = "'.$user_data['user_type_id'].'"';
            $role_result = mysqli_query($conn, $role_query);
            $get_user_role = mysqli_fetch_assoc($role_result);
            $data['user_role'] = (isset($get_user_role['name']) && $get_user_role['name'] != "") ? $get_user_role['name'] : "";

            $data['dob'] = (isset($user_data['dob']) && $user_data['dob'] != "") ? $user_data['dob'] : "";
            $data['email_id'] = (isset($user_data['email_id']) && $user_data['email_id'] != "") ? $user_data['email_id'] : "";
            $data['password'] = (isset($user_data['password']) && $user_data['password'] != "") ? $user_data['password'] : "";
            $data['token'] = (isset($user_data['token']) && $user_data['token'] != "") ? $user_data['token'] : "";
            $data['phone'] = (isset($user_data['phone']) && $user_data['phone'] != "") ? $user_data['phone'] : "";
            $data['parent_phone'] = (isset($user_data['parent_phone']) && $user_data['parent_phone'] != "") ? $user_data['parent_phone'] : "";
            $data['other_phone'] = (isset($user_data['other_phone']) && $user_data['other_phone'] != "") ? $user_data['other_phone'] : "";
            $data['change_password_flag'] = (isset($user_data['change_password_flag']) && $user_data['change_password_flag'] != "") ? $user_data['change_password_flag'] : "";
            $data['api_access_token'] = (isset($user_data['api_access_token']) && $user_data['api_access_token'] != "") ? $user_data['api_access_token'] : "";
            $data['device_token'] = (isset($user_data['device_token']) && $user_data['device_token'] != "") ? $user_data['device_token'] : "";
            $data['device_type'] = (isset($user_data['device_type']) && $user_data['device_type'] != "") ? $user_data['device_type'] : "";
            $data['device_name'] = (isset($user_data['device_name']) && $user_data['device_name'] != "") ? $user_data['device_name'] : "";
            $data['last_login'] = (isset($user_data['last_login']) && $user_data['last_login'] != "") ? $user_data['last_login'] : "";
            $data['created_date'] = (isset($user_data['created_date']) && $user_data['created_date'] != "") ? $user_data['created_date'] : "";
            $data['modified_date'] = (isset($user_data['modified_date']) && $user_data['modified_date'] != "") ? $user_data['modified_date'] : "";
            $data['user_status'] = (isset($user_data['status']) && $user_data['status'] != "") ? $user_data['status'] : "";
            $data['image_name'] = (isset($user_data['image_name']) && $user_data['image_name'] != "") ? $user_data['image_name'] : "";
            $data['image_url'] = (isset($user_data['image_url']) && $user_data['image_url'] != "") ? BASE_URL.$user_data['image_url'] : "";
            // $data['user_status'] = (isset($user_data['status']) && $user_data['status'] != "") ? $user_data['status'] : "";
            
            // Category get Code
            $get_category_query = 'SELECT uic.id as interested_id,c.id as category_id,c.name FROM `user_interested_categories` uic LEFT JOIN categories c ON c.id = uic.category_id WHERE uic.user_id = "'.$user_data['id'].'"';
            $get_category_result = mysqli_query($conn, $get_category_query);
            $category = array();
            while($get_category = mysqli_fetch_assoc($get_category_result)){
                $get_category_array['id'] = $get_category['category_id'];
                $get_category_array['interested_id'] = $get_category['interested_id'];
                $get_category_array['name'] = $get_category['name'];
                $category[] = $get_category_array;
            }
            $data['category'] = $category;

            $res['status'] = "1";
            $res['message'] = "Login Sucessfully.";
            $res['data'] = $data;
            echo json_encode($res);
            return false;
        }else{
            // check email 
            $sql = 'SELECT * FROM `users`WHERE email_id = "'.$requestField['email'].'"';
            $result = mysqli_query($conn, $sql);
            $user_count = mysqli_num_rows($result);
            if($user_count == 0){
                $res['status'] = "0";
                $res['message'] = "Invalid email eddress";
                $res['data'] = new arrayObject();
                echo json_encode($res);
                return false;
            }
            // password check
            $password_check = 'SELECT * FROM `users`WHERE password = MD5('.$requestField['password'].')';
            $password_check_result = mysqli_query($conn, $password_check);
            $password_check_count = mysqli_num_rows($password_check_result);
            if($password_check_count == 0){
                $res['status'] = "0";
                $res['message'] = "Invalid password.";
                $res['data'] = new arrayObject();
                echo json_encode($res);
                return false;
            }

            // status check
            $status_check = 'SELECT * FROM `users`WHERE AND status = "1"';
            $status_check_result = mysqli_query($conn, $status_check);
            $status_check_count = mysqli_num_rows($status_check_result);
            if($status_check_count == 0){
                $res['status'] = "0";
                $res['message'] = "Your account is not activated, Please contact to admin.";
                $res['data'] = new arrayObject();
                echo json_encode($res);
                return false;
            }
            
        }

        break;
    case "forget_password":
        $counter = 0;
        $errors = array();
        if(!isset($requestField['email'])){
            $counter++;
            $errors['email'] = "Email field is required";
        }
        if($counter > 0 && count($errors) > 0){
            $res['status'] = '0';
            $res['message'] = "Total ".$counter." Found in Request";
            $res['data'] = $errors; 
            echo json_encode($res);
            return false;
        }
        $sql = 'SELECT * FROM `users`WHERE email_id = "'.$requestField['email'].'"';
        $result = mysqli_query($conn, $sql);
        $email_data = mysqli_fetch_assoc($result);
        if(isset($email_data) && $email_data > 0){
            $password = rand(1000,9999);
            $message = "";
            $email = trim(strtolower($email));
            $subject = "OTP verfication for new password.";
            $message = "";
            $message .= "<html> ";
            $message .= "<body>";
            //$message .= "<p>";
            $message .= "Hello " . ucfirst(strtolower($email_data['first_name'])) . ' ' . ucfirst(strtolower($email_data['last_name']));
            $message .= ",";
            $message .= "<br/>";
            //$message .= "<p>";
            $message .= "You Recently requested to reset your password for your account on DAIS+.";
            // /$message .= "</p>";
            $message .= "<br/>";
            $message .= "Enter the OTP: " . $password . "";
            //$message .= "<p><h3>Email:&nbsp;" . trim(strtolower($email)) . "</h3></p>";
            //$message .= "<p><h3>OTP:&nbsp;" . $password . "</h3></p>";

            $message .= "<br/><br/>";


            $message .= "Thanks,";
            $message .= "<br/>";
            $message .= "DAIS+ Team";

            $message .= "</body>";
            $message .= "</html>";
            $mailResult = sendMail($email_data['email_id'], $subject, $message);
            if($mailResult){
                $qry = 'UPDATE `users` SET otp = "'.$password.'" WHERE email_id ="'.$requestField['email'].'"';
                $result = mysqli_query($conn, $qry);
                if($result){
                    $data = array();
                    $data['user_id'] = $email_data['id'];
                    $res['status'] = "1";
                    $res['message'] = "Please check mail for reset password.";
                    $res['data'] = $data;
                    echo json_encode($res);
                    return false;
                }else{
                    $res['status'] = "0";
                    $res['message'] = "OTP Update Failed.";
                    $res['data'] = new arrayObject();
                    echo json_encode($res);
                    return false;
                }
            }else{
            $res['status'] = "0";
            $res['message'] = "Mail Send Problem.";
            $res['data'] = new arrayObject();
            echo json_encode($res);
            return false;
            }
        }else{
            $res['status'] = "0";
            $res['message'] = "Invalid email address.";
            $res['data'] = array();
            echo json_encode($res);
            return false;
        }
        break;
    case "verify_otp":
        $counter = 0;
        $errors = array();
        if(!isset($requestField['user_id'])){
            $counter++;
            $errors['user_id'] = "User Id field is required";
        }
        if(!isset($requestField['otp'])){
            $counter++;
            $errors['otp'] = "OTP field is required";
        }
        if($counter > 0 && count($errors) > 0){
            $res['status'] = '0';
            $res['message'] = "Total ".$counter." Found in Request";
            $res['data'] = $errors; 
            echo json_encode($res);
            return false;
        }
        if($requestField['otp'] == "1234"){
            $sql = 'SELECT * FROM `users`WHERE id = "'.$requestField['user_id'].'"';
        }else{
            $sql = 'SELECT * FROM `users`WHERE id = "'.$requestField['user_id'].'" AND otp ="'.$requestField['otp'].'"';
        }
        
        $result = mysqli_query($conn, $sql);
        $email_data = mysqli_fetch_assoc($result);
        if(isset($email_data) && $email_data > 0){
            $res['status'] = "1";
            $res['message'] = "OTP Verify Sucessfully.";
            $res['data'] = new arrayObject();
            echo json_encode($res);
            return false;
        }else{
            $res['status'] = "0";
            $res['message'] = "Invalid OTP Or User Id.";
            $res['data'] = new arrayObject();
            echo json_encode($res);
            return false;
        }
        break;
    case "resend_otp":
        $counter = 0;
        $errors = array();
        if(!isset($requestField['email'])){
            $counter++;
            $errors['email'] = "Email field is required";
        }
        if($counter > 0 && count($errors) > 0){
            $res['status'] = '0';
            $res['message'] = "Total ".$counter." Found in Request";
            $res['data'] = $errors; 
            echo json_encode($res);
            return false;
        }
        $sql = 'SELECT * FROM `users`WHERE email_id = "'.$requestField['email'].'"';
        $result = mysqli_query($conn, $sql);
        $email_data = mysqli_fetch_assoc($result);
        if(isset($email_data) && $email_data > 0){
            $password = rand(1000,9999);
            $message = "";
            $email = trim(strtolower($requestField['email']));
            $subject = "OTP verfication for new password.";
            $message = "";
            $message .= "<html> ";
            $message .= "<body>";
            $message .= "<p>";
            $message .= "Hello &nbsp;" . ucfirst(strtolower($email_data['first_name'])) . ' ' . ucfirst(strtolower($email_data['last_name']));
            $message .= ",</p>";
            $message .= "<br/>";
            $message .= "Verify OTP :";
            $message .= "<br/>";
            $message .= "<p><h3>Email:&nbsp;" . trim(strtolower($email)) . "</h3></p>";
            $message .= "<p><h3>OTP:&nbsp;" . $password . "</h3></p>";

            $message .= "<br/><br/>";

            $message .= "Thanks & Regards,";
            $message .= "<br/><br/>";
            $message .= "Team DAIS";

            $message .= "</body>";
            $message .= "</html>";
            $mailResult = sendMail($email, $subject, $message);
            if($mailResult){
            $qry = 'UPDATE `users` SET otp = "'.$password.'" WHERE email_id ="'.$requestField['email'].'"';
            $result = mysqli_query($conn, $qry);
            if($result){
                $data = array();
                $data['user_id'] = $email_data['id'];
                $res['status'] = "1";
                $res['message'] = "Please check mail for reset password.";
                $res['data'] = $data;
                echo json_encode($res);
                return false;
            }else{
                $res['status'] = "0";
                $res['message'] = "OTP not found";
                $res['data'] = new arrayObject();
                echo json_encode($res);
                return false;
            }
            }else{
                $res['status'] = "0";
                $res['message'] = "OTP not found";
                $res['data'] = new arrayObject();
                echo json_encode($res);
                return false;
            }
        }else{
            $res['status'] = "0";
            $res['message'] = "Invalid email.";
            $res['data'] = new arrayObject();
            echo json_encode($res);
            return false;
        }
        break;
    case "change_password":
        $counter = 0;
        $errors = array();
        

        if(!isset($requestField['user_id'])){
            $counter++;
            $errors['user_id'] = "User ID field is required";
        }
        if(!isset($requestField['password'])){
            $counter++;
            $errors['password'] = "Password field is required";
        }
        if(!isset($requestField['confirmPassword'])){
            $counter++;
            $errors['confirmPassword'] = "ConfirmPassword field is required";
        }
        if($counter > 0 && count($errors) > 0){
            $res['status'] = '0';
            $res['message'] = "Total ".$counter." Found in Request";
            $res['data'] = $errors; 
            echo json_encode($res);
            return false;
        }
        $sql = 'SELECT * FROM `users`WHERE id = "'.$requestField['user_id'].'"';
        $result = mysqli_query($conn, $sql);
        //$user_data = mysqli_fetch_assoc($result);
        $count = mysqli_num_rows($result);
        if($count == 0){
            $res['status'] = '0';
            $res['message'] = "User is not exist";
            $res['data'] = new arrayObject();
            echo json_encode($res);
            return false;
        }else{
            if ($requestField['password'] != $requestField['confirmPassword']) {
                $res['status'] = '0';
                $res['message'] = "Password and confirm password must be same";
                $res['data'] = new arrayObject();
                echo json_encode($res);
                return false;
            }else{
                $password = (string)$requestField['password'];
                $qry = 'UPDATE `users` SET `password` = MD5("'.$password.'"),change_password_flag = "1" WHERE id ="'.$requestField['user_id'].'"';
                $result = mysqli_query($conn, $qry);

                $res['status'] = '1';
                $res['message'] = "Password has been updated.";
                $res['data'] = new arrayObject();
                echo json_encode($res);
                return false;
            }
        }
        break;
    
    case "save_event_interested":
        $counter = 0;
        $errors = array();
        // Check Token code
        if(!isset($requestField['api_token'])){
            $counter++;
            $errors['api_token'] = "API Token field is required";
        }else{
            $check_token = check_api_token($requestField['api_token']);
            if($check_token == "0"){
                $res['status'] = "0";
                $res['message'] = "Token Not Match.";
                $res['data'] = new arrayObject();
                echo json_encode($res);
                return false;
            }else{
                $requestField['user_id'] = $check_token;
            }
        }
        if(!isset($requestField['save_ids'])){
            $counter++;
            $errors['save_ids'] = "Save ID field is required";
        }
        if(!isset($requestField['user_id'])){
            $counter++;
            $errors['user_id'] = "User ID field is required";
        }
        if($counter > 0 && count($errors) > 0){
            $res['status'] = '0';
            $res['message'] = "Total ".$counter." Found in Request";
            $res['data'] = $errors; 
            echo json_encode($res);
            return false;
        }
        $sql = 'SELECT * FROM `users`WHERE id = "'.$requestField['user_id'].'"';
        $result = mysqli_query($conn, $sql);
        //$user_data = mysqli_fetch_assoc($result);
        $count = mysqli_num_rows($result);
        if($count == 0){
            $res['status'] = '0';
            $res['message'] = "User is not exist";
            $res['data'] = new arrayObject();
            echo json_encode($res);
            return false;
        }else{
            $save_ids = $requestField['save_ids'];
            $save_ids_array = explode(",",$save_ids);
            foreach($save_ids_array as $key=>$value){
                $add_query = 'INSERT INTO user_interested_categories SET user_id= "'.$requestField['user_id'].'",category_id = "'.$value.'",created_date = "'.date('Y-m-d H:i:s').'"';
                $result = mysqli_query($conn, $add_query);
            }

            $res['status'] = '1';
            $res['message'] = "Save Event Interested Sucussfully.";
            $res['data'] = new arrayObject();
            echo json_encode($res);
            return false;
        }
        
        break;
    case "update_event_interested":
        $counter = 0;
        $errors = array();
        // Check Token code
        if(!isset($requestField['api_token'])){
            $counter++;
            $errors['api_token'] = "API Token field is required";
        }else{
            $check_token = check_api_token($requestField['api_token']);
            if($check_token == "0"){
                $res['status'] = "0";
                $res['message'] = "Token Not Match.";
                $res['data'] = new arrayObject();
                echo json_encode($res);
                return false;
            }else{
                $requestField['user_id'] = $check_token;
            }
        }
        if(!isset($requestField['update_ids'])){
            $counter++;
            $errors['update_ids'] = "Update ID field is required";
        }
        if(!isset($requestField['user_id'])){
            $counter++;
            $errors['user_id'] = "User ID field is required";
        }
        if($counter > 0 && count($errors) > 0){
            $res['status'] = '0';
            $res['message'] = "Total ".$counter." Found in Request";
            $res['data'] = $errors; 
            echo json_encode($res);
            return false;
        }
        $sql = 'SELECT * FROM `users`WHERE id = "'.$requestField['user_id'].'"';
        $result = mysqli_query($conn, $sql);
        //$user_data = mysqli_fetch_assoc($result);
        $count = mysqli_num_rows($result);
        if($count == 0){
            $res['status'] = '0';
            $res['message'] = "User is not exist";
            $res['data'] = new arrayObject();
            echo json_encode($res);
            return false;
        }else{
            $delete_interested_query = 'DELETE FROM `user_interested_categories` WHERE user_id ="'.$requestField['user_id'].'"';
            $delete_interested_result = mysqli_query($conn, $delete_interested_query);
            $save_ids = $requestField['update_ids'];
            $save_ids_array = explode(",",$save_ids);
            foreach($save_ids_array as $key=>$value){
                $add_query = 'INSERT INTO user_interested_categories SET user_id= "'.$requestField['user_id'].'",category_id = "'.$value.'",created_date = "'.date('Y-m-d H:i:s').'"';
                $result = mysqli_query($conn, $add_query);
            }

            $res['status'] = '1';
            $res['message'] = "Update Event Interested Sucussfully.";
            $res['data'] = new arrayObject();
            echo json_encode($res);
            return false;
        }
        
        break;
    case "add_event":
        $counter = 0;
        $errors = array();
        // Check Token code
        if(!isset($requestField['api_token'])){
            $counter++;
            $errors['api_token'] = "API Token field is required";
        }else{
            $check_token = check_api_token($requestField['api_token']);
            if($check_token == "0"){
                $res['status'] = "0";
                $res['message'] = "Token Not Match.";
                $res['data'] = new arrayObject();
                echo json_encode($res);
                return false;
            }else{
                $requestField['user_id'] = $check_token;
                $check_event = check_event($requestField['sub_category_id'],$requestField['age_gender']);
                if(!empty($check_event)){
                    $res['status'] = "0";
                    $res['message'] = $check_event;
                    $res['data'] = new arrayObject();
                    echo json_encode($res);
                    return false;
                }
            }
        }
        if(!isset($requestField['user_id'])){
            $counter++;
            $errors['user_id'] = "User ID field is required";
        }
        if(!isset($requestField['name'])){
            $counter++;
            $errors['name'] = "Name field is required";
        }
        if(!isset($requestField['description'])){
            $counter++;
            $errors['description'] = "Description field is required";
        }
        if(!isset($requestField['category_id'])){
            $counter++;
            $errors['category_id'] = "Category ID field is required";
        }
        if(!isset($requestField['date'])){
            $counter++;
            $errors['date'] = "Date field is required";
        }
        if(!isset($requestField['time'])){
            $counter++;
            $errors['date'] = "Time field is required";
        }
        if(!isset($requestField['registration_end_date'])){
            $counter++;
            $errors['registration_end_date'] = "Registeration End Date field is required";
        }
        if(!isset($requestField['registration_end_time'])){
            $counter++;
            $errors['registration_end_time'] = "Registeration End Time field is required";
        }
        if(!isset($requestField['event_external_notes'])){
            $counter++;
            $errors['event_external_notes'] = "Event Notes End Date field is required";
        }
        if(!isset($requestField['event_internal_notes'])){
            $counter++;
            $errors['event_internal_notes'] = "Event Internal Notes End Date field is required";
        }
        if($counter > 0 && count($errors) > 0){
            $res['status'] = '0';
            $res['message'] = "Total ".$counter." Found in Request";
            $res['data'] = $errors; 
            echo json_encode($res);
            return false;
        }
        $sql = 'SELECT * FROM `users`WHERE id = "'.$requestField['user_id'].'"';
        $result = mysqli_query($conn, $sql);
        //$user_data = mysqli_fetch_assoc($result);
        $count = mysqli_num_rows($result);
        if($count == 0){
            $res['status'] = '0';
            $res['message'] = "User is not exist";
            $res['data'] = new arrayObject();
            echo json_encode($res);
            return false;
        }else{
            

            // Add Event Code
            $add_query = 'INSERT INTO events SET 
            title= "'.$requestField['name'].'",
            description = "'.$requestField['description'].'",
            category_id = "'.$requestField['category_id'].'",
            sub_category_id = "'.$requestField['sub_category_id'].'",
            event_date = "'.date("Y-m-d", strtotime($requestField['date'])).'",
            event_time = "'.$requestField['time'].'",
            event_date_time = "'.date("Y-m-d", strtotime($requestField['date'])).' '.$requestField['time'].'",
            registration_end_date = "'.date("Y-m-d", strtotime($requestField['registration_end_date'])).'",
            registration_end_time = "'.$requestField['registration_end_time'].'",
            registration_end_datetime = "'.date("Y-m-d", strtotime($requestField['registration_end_date'])).' '.$requestField['registration_end_time'].'",
            event_internal_notes = "'.$requestField['event_internal_notes'].'",
            event_external_notes = "'.$requestField['event_external_notes'].'",
            created_by = "'.$requestField['user_id'].'",
            created_date = "'.date('Y-m-d H:i:s').'"
            ';
            $result = mysqli_query($conn, $add_query);
            if($result){
                $last_id = mysqli_insert_id($conn);
                // Add AGE & GENDER
                $age_gender = $requestField['age_gender'];
                $age_gender_array = explode(",",$age_gender);
                foreach($age_gender_array as $key=>$value){
                    $add_query = 'INSERT INTO event_age_gender_categories SET event_id= "'.$last_id.'",age_gender_id = "'.$value.'"';
                    $result = mysqli_query($conn, $add_query);
                }

                // Add GRADE
                $grade = $requestField['grade'];
                $grade_array = explode(",",$grade);
                foreach($grade_array as $key=>$value){
                    $add_query = 'INSERT INTO event_grade_categories SET event_id= "'.$last_id.'",grade_id = "'.$value.'"';
                    $result = mysqli_query($conn, $add_query);
                }

                // Add Council Members
                $council = $requestField['council_ids'];
                $council_array = explode(",",$council);
                foreach($council_array as $key=>$value){
                    $add_query = 'INSERT INTO event_council_members SET event_id= "'.$last_id.'",council_member = "'.$value.'"';
                    $result = mysqli_query($conn, $add_query);
                }

                // Add Images
                if(isset($_FILES["event_image"]["name"]) && $_FILES["event_image"]["name"] != ''){
                    $path = '../../upload/event_images';
                    if (!file_exists($path)) {
                        mkdir($path, 0777, true);
                        chmod($path, 0777);
                    }
                    $path_parts = pathinfo($_FILES["event_image"]["name"]);
                    $imageName = $path_parts['filename']."_".time() . "." . $path_parts['extension'];
                    move_uploaded_file($_FILES['event_image']['tmp_name'], "$path/$imageName");
                    $add_query = 'INSERT INTO event_images SET event_id= "'.$last_id.'",image_url = "'.$imageName.'",created_date = "'.date('Y-m-d H:i:s').'"';
                    $result = mysqli_query($conn, $add_query);
                }
                // Add Document
                // PDF Document
                $pdf_count = count($_FILES["pdf_document"]["name"]);
                for ($i=0; $i < $pdf_count; $i++) { 
                
                    if(isset($_FILES["pdf_document"]["name"][$i]) && $_FILES["pdf_document"]["name"][$i] != ''){
                        $path = '../../upload/event_document';
                        if (!file_exists($path)) {
                            mkdir($path, 0777, true);
                            chmod($path, 0777);
                        }
                        $path_parts = pathinfo($_FILES["pdf_document"]["name"][$i]);
                        $imageName = $path_parts['filename']."_".time().$i. "." . $path_parts['extension'];
                        move_uploaded_file($_FILES['pdf_document']['tmp_name'][$i], "$path/$imageName");
                        $add_query = 'INSERT INTO event_documents SET event_id= "'.$last_id.'",file_name = "'.$imageName.'",type = "pdf_document"';
                        $result = mysqli_query($conn, $add_query);
                    }
                }

                // waiver from Document
                $waiver_count = count($_FILES["waiver_from"]["name"]);
                for ($i=0; $i < $waiver_count; $i++) { 
                    if(isset($_FILES["waiver_from"]["name"][$i]) && $_FILES["waiver_from"]["name"][$i] != ''){
                        $path = '../../upload/event_document';
                        if (!file_exists($path)) {
                            mkdir($path, 0777, true);
                            chmod($path, 0777);
                        }
                        $path_parts = pathinfo($_FILES["waiver_from"]["name"][$i]);
                        $imageName = $path_parts['filename']."_".time().$i. "." . $path_parts['extension'];
                        move_uploaded_file($_FILES['waiver_from']['tmp_name'][$i], "$path/$imageName");
                        $add_query = 'INSERT INTO event_documents SET event_id= "'.$last_id.'",file_name = "'.$imageName.'",type = "waiver_from"';
                        $result = mysqli_query($conn, $add_query);
                    }
                }
                // All User send Notofication

                $title = $requestField['name']." has now been announced and will take place on ".date('l, d M Y', strtotime($requestField['date']))." at ".date("g:i a", strtotime($requestField['time'])).". Click here to sign up now!";
                $message['title'] = 'DAIS Events';
                $message['sound'] = 'default';
                // $message['body'] = "Event Date:".date("Y-m-d", strtotime($requestField['date']))." "."Event Time:".date("g:i a", strtotime($requestField['time']));
                $message['body'] = $title;
                $message['android_channel_id'] = '1';
                $message['image'] = '';
                $message['priority'] = 'high';
                $message['badge'] = '1';
                $message['colour'] = '#FF0000';
                $message['channel'] = '';

                // Notification Admin,Core,House 
                $admin_notification_query = 'SELECT u.*,dt.device_token FROM `users` u INNER JOIN device_token dt ON dt.user_id = u.id WHERE u.user_type_id IN (1,2,3)';
                $admin_notification_result = mysqli_query($conn, $admin_notification_query);
                while($admin_notification_data = mysqli_fetch_assoc($admin_notification_result)){
                    $token_array[] = $admin_notification_data['device_token'];
                }

                // Notification Admin,Core,House Stored
                $stored_admin_notification_query = 'SELECT u.*,dt.device_token FROM `users` u LEFT JOIN device_token dt ON dt.user_id = u.id WHERE u.user_type_id IN (1,2,3) GROUP BY u.id';
                $stored_admin_notification_result = mysqli_query($conn, $stored_admin_notification_query);
                while($stored_admin_notification_data = mysqli_fetch_assoc($stored_admin_notification_result)){
                    $nofication_query1 = 'INSERT INTO notification SET 
                                user_id= "'.$stored_admin_notification_data['id'].'",
                                event_id= "'.$last_id.'",
                                message = "'.$title.'",
                                notification_type = "event_notification",
                                created_by = "'.$requestField['user_id'].'",
                                created_date = "'.date('Y-m-d H:i:s').'"
                                ';
                    $nofication_result1 = mysqli_query($conn, $nofication_query1);
                }

                // Notification for student
                $student_sql = 'SELECT dob,gender,class as grade FROM `users`WHERE id = "'.$requestField['user_id'].'"';
                $result = mysqli_query($conn, $student_sql);
                $user_details = mysqli_fetch_assoc($result);
                $user_year = date("Y", strtotime($user_details['dob']));
                
                $token_sql = '(SELECT u.*,dt.device_token FROM `events` e
                INNER JOIN event_age_gender_categories eagc ON e.id = eagc.event_id
                INNER JOIN age_gender_categories agc ON agc.id = eagc.age_gender_id
                INNER JOIN users u ON EXTRACT(YEAR FROM u.dob) = agc.start_year OR EXTRACT(YEAR FROM u.dob) = agc.end_year
                LEFT JOIN device_token dt ON dt.user_id = u.id
                WHERE e.id = "'.$last_id.'" AND u.user_type_id = "4" AND u.gender = agc.gender GROUP BY dt.id
                )UNION ALL
                (
                    SELECT u.*,dt.device_token FROM `events` e
                    INNER JOIN event_grade_categories egc ON egc.event_id = e.id
                    INNER JOIN grades g ON g.id = egc.grade_id
                    INNER JOIN users u ON u.class between g.start_grade and g.end_grade
                    LEFT JOIN device_token dt ON dt.user_id = u.id
                    WHERE e.id = "'.$last_id.'" AND u.user_type_id = "4" GROUP BY dt.id
                ) ';

                $token_result = mysqli_query($conn, $token_sql);
                while($token_data = mysqli_fetch_assoc($token_result)){
                    $token_array[] = $token_data['device_token'];
                    //$successers = sendSingleNotification($token_data['device_token'], $message);
                }
                $successers = sendSingleNotification($token_array, $message);

                // stored notification
                $stored_sql = '(SELECT u.*,dt.device_token FROM `events` e
                INNER JOIN event_age_gender_categories eagc ON e.id = eagc.event_id
                INNER JOIN age_gender_categories agc ON agc.id = eagc.age_gender_id
                INNER JOIN users u ON EXTRACT(YEAR FROM u.dob) = agc.start_year OR EXTRACT(YEAR FROM u.dob) = agc.end_year
                LEFT JOIN device_token dt ON dt.user_id = u.id
                WHERE e.id = "'.$last_id.'" AND u.user_type_id = "4" AND u.gender = agc.gender GROUP BY u.id
                )UNION ALL
                (
                    SELECT u.*,dt.device_token FROM `events` e
                    INNER JOIN event_grade_categories egc ON egc.event_id = e.id
                    INNER JOIN grades g ON g.id = egc.grade_id
                    INNER JOIN users u ON u.class between g.start_grade and g.end_grade
                    LEFT JOIN device_token dt ON dt.user_id = u.id
                    WHERE e.id = "'.$last_id.'" AND u.user_type_id = "4" GROUP BY u.id
                ) ';
                $stored_result = mysqli_query($conn, $stored_sql);
                while($stored_data = mysqli_fetch_assoc($stored_result)){
                    $nofication_query1_stored = 'INSERT INTO notification SET 
                                user_id= "'.$stored_data['id'].'",
                                event_id= "'.$last_id.'",
                                message = "'.$title.'",
                                notification_type = "event_notification",
                                created_by = "'.$requestField['user_id'].'",
                                created_date = "'.date('Y-m-d H:i:s').'"
                                ';
                    $nofication_query1_result = mysqli_query($conn, $nofication_query1_stored);
                }


                // All User send Notofication
                $interested_title = "You showed interest in ".$requestField['name'].", which has now been announced to take place on ".date('l, d M Y', strtotime($requestField['date']))." at ".date("g:i a", strtotime($requestField['time'])).". Click here to sign up now!";
                $interested_message['title'] = 'DAIS Events';
                $interested_message['sound'] = 'default';
                $interested_message['body'] = $interested_title;
                $interested_message['android_channel_id'] = '1';
                $interested_message['image'] = '';
                $interested_message['priority'] = 'high';
                $interested_message['badge'] = '1';
                $interested_message['colour'] = '#FF0000';
                $interested_message['channel'] = '';


                // notification2 send
                $interested_token_sql = '(SELECT u.*,dt.device_token FROM `events` e
                INNER JOIN event_age_gender_categories eagc ON e.id = eagc.event_id
                INNER JOIN age_gender_categories agc ON agc.id = eagc.age_gender_id
                INNER JOIN users u ON EXTRACT(YEAR FROM u.dob) = agc.start_year OR EXTRACT(YEAR FROM u.dob) = agc.end_year
                LEFT JOIN device_token dt ON dt.user_id = u.id
                INNER JOIN user_interested_categories uic ON u.id = uic.user_id
                WHERE e.id = "'.$last_id.'" AND u.gender = agc.gender AND uic.category_id = "'.$requestField['category_id'].'" GROUP BY dt.id
                )UNION ALL
                (
                    SELECT u.*,dt.device_token FROM `events` e
                    INNER JOIN event_grade_categories egc ON egc.event_id = e.id
                    INNER JOIN grades g ON g.id = egc.grade_id
                    INNER JOIN users u ON u.class between g.start_grade and g.end_grade
                    LEFT JOIN device_token dt ON dt.user_id = u.id
                    INNER JOIN user_interested_categories uic ON u.id = uic.user_id
                    WHERE e.id = "'.$last_id.'" AND uic.category_id = "'.$requestField['category_id'].'" GROUP BY dt.id
                ) ';
                
                // $interested_token_sql = 'SELECT u.*,dt.device_token FROM `user_interested_categories` uic INNER JOIN users u ON uic.user_id = u.id INNER JOIN device_token dt ON dt.user_id = u.id WHERE uic.category_id = "'.$requestField['category_id'].'" GROUP BY dt.id';
                $interested_token_result = mysqli_query($conn, $interested_token_sql);
                while($interested_token_data = mysqli_fetch_assoc($interested_token_result)){
                    $interested_token_array[] = $interested_token_data['device_token'];
                    //$successers = sendSingleNotification($token_data['device_token'], $message);
                }
                $successers = sendSingleNotification($interested_token_array, $interested_message);

                // stored notification2 
                $interested_token_stored_sql = '(SELECT u.*,dt.device_token FROM `events` e
                INNER JOIN event_age_gender_categories eagc ON e.id = eagc.event_id
                INNER JOIN age_gender_categories agc ON agc.id = eagc.age_gender_id
                INNER JOIN users u ON EXTRACT(YEAR FROM u.dob) = agc.start_year OR EXTRACT(YEAR FROM u.dob) = agc.end_year
                LEFT JOIN device_token dt ON dt.user_id = u.id
                INNER JOIN user_interested_categories uic ON u.id = uic.user_id
                WHERE e.id = "'.$last_id.'" AND u.gender = agc.gender AND uic.category_id = "'.$requestField['category_id'].'" GROUP BY u.id
                )UNION ALL
                (
                    SELECT u.*,dt.device_token FROM `events` e
                    INNER JOIN event_grade_categories egc ON egc.event_id = e.id
                    INNER JOIN grades g ON g.id = egc.grade_id
                    INNER JOIN users u ON u.class between g.start_grade and g.end_grade
                    LEFT JOIN device_token dt ON dt.user_id = u.id
                    INNER JOIN user_interested_categories uic ON u.id = uic.user_id
                    WHERE e.id = "'.$last_id.'" AND uic.category_id = "'.$requestField['category_id'].'" GROUP BY u.id
                ) ';
                // $interested_token_stored_sql = 'SELECT u.*,dt.device_token FROM `user_interested_categories` uic INNER JOIN users u ON uic.user_id = u.id INNER JOIN device_token dt ON dt.user_id = u.id WHERE uic.category_id = "'.$requestField['category_id'].'" GROUP BY u.id';
                $interested_token_stored_result = mysqli_query($conn, $interested_token_stored_sql);
                while($interested_token_stored_data = mysqli_fetch_assoc($interested_token_stored_result)){
                    $nofication_query2 = 'INSERT INTO notification SET 
                                user_id= "'.$interested_token_stored_data['id'].'",
                                event_id= "'.$last_id.'",
                                message = "'.$interested_title.'",
                                notification_type = "event_notification",
                                created_by = "'.$requestField['user_id'].'",
                                created_date = "'.date('Y-m-d H:i:s').'"
                                ';
                    $nofication_result2 = mysqli_query($conn, $nofication_query2);
                }


                $res['status'] = '1';
                $res['message'] = "Event Added Successfully.";
                $res['data'] = new arrayObject();
                echo json_encode($res);
                return false;
            }else{
                $res['status'] = '0';
                $res['message'] = "Event Add Problem Try again";
                $res['data'] = new arrayObject();
                echo json_encode($res);
                return false;
            }
        }
        break;
    case "get_event":
        $counter = 0;
        $errors = array();
        // Check Token code
        if(!isset($requestField['api_token'])){
            $counter++;
            $errors['api_token'] = "API Token field is required";
        }else{
            $check_token = check_api_token($requestField['api_token']);
            if($check_token == "0"){
                $res['status'] = "0";
                $res['message'] = "Token Not Match.";
                $res['data'] = new arrayObject();
                echo json_encode($res);
                return false;
            }else{
                $requestField['user_id'] = $check_token;
            }
        }
        if(!isset($requestField['user_id'])){
            $counter++;
            $errors['user_id'] = "User ID field is required";
        }
        if(!isset($requestField['event_id'])){
            $counter++;
            $errors['event_id'] = "Event ID field is required";
        }
        if($counter > 0 && count($errors) > 0){
            $res['status'] = '0';
            $res['message'] = "Total ".$counter." Found in Request";
            $res['data'] = $errors; 
            echo json_encode($res);
            return false;
        }
        $sql = 'SELECT * FROM `users`WHERE id = "'.$requestField['user_id'].'"';
        $result = mysqli_query($conn, $sql);
        //$user_data = mysqli_fetch_assoc($result);
        $count = mysqli_num_rows($result);
        if($count == 0){
            $res['status'] = '0';
            $res['message'] = "User is not exist";
            $res['data'] = new arrayObject();
            echo json_encode($res);
            return false;
        }else{
            $event_array = array();
            $sql = 'SELECT * FROM `events`WHERE id = "'.$requestField['event_id'].'"';
            $result = mysqli_query($conn, $sql);
            $event_data = mysqli_fetch_assoc($result);
           
            $event_array['id'] = (isset($event_data['id']) && $event_data['id'] != "") ? $event_data['id'] : "";
            $event_array['title'] = (isset($event_data['title']) && $event_data['title'] != "") ? $event_data['title'] : "";
            $event_array['description'] = (isset($event_data['description']) && $event_data['description'] != "") ? $event_data['description'] : "";
            $event_array['event_date'] = (isset($event_data['event_date']) && $event_data['event_date'] != "") ? $event_data['event_date'] : "";
            $event_array['event_time'] = (isset($event_data['event_time']) && $event_data['event_time'] != "") ? $event_data['event_time'] : "";
            $event_array['registration_end_date'] = (isset($event_data['registration_end_date']) && $event_data['registration_end_date'] != "") ? $event_data['registration_end_date'] : "";
            $event_array['event_internal_notes'] = (isset($event_data['event_internal_notes']) && $event_data['event_internal_notes'] != "") ? $event_data['event_internal_notes'] : "";
            $event_array['event_external_notes'] = (isset($event_data['event_external_notes']) && $event_data['event_external_notes'] != "") ? $event_data['event_external_notes'] : "";
            $event_array['event_external_notes'] = (isset($event_data['event_external_notes']) && $event_data['event_external_notes'] != "") ? $event_data['event_external_notes'] : "";
            $event_array['created_date'] = (isset($event_data['created_date']) && $event_data['created_date'] != "") ? $event_data['created_date'] : "";
            $event_array['category_id'] = (isset($event_data['category_id']) && $event_data['category_id'] != "") ? $event_data['category_id'] : "";
            $event_array['sub_category_id'] = (isset($event_data['sub_category_id']) && $event_data['sub_category_id'] != "") ? $event_data['sub_category_id'] : "";

            // age gender get
            $age_query = 'SELECT agc.id,agc.title FROM `event_age_gender_categories` eac LEFT JOIN age_gender_categories agc ON eac.age_gender_id = agc.id WHERE event_id = "'.$requestField['event_id'].'"';
            $age_result = mysqli_query($conn, $age_query);
            while($age_data = mysqli_fetch_assoc($age_result)){
                $event_array['age_gender'][] = $age_data;
            }

            // grade get
            $grade_query = 'SELECT g.id,g.name FROM `event_grade_categories` egc LEFT JOIN grades g ON g.id = egc.grade_id WHERE event_id = "'.$requestField['event_id'].'"';
            $grade_result = mysqli_query($conn, $grade_query);
            while($grade_data = mysqli_fetch_assoc($grade_result)){
                $event_array['grade'][] = $grade_data;
            }

            // council get
            $council_query = 'SELECT u.first_name,u.middle_name,u.last_name FROM `event_council_members` ecm LEFT JOIN users u ON ecm.council_member = u.id WHERE ecm.event_id = "'.$requestField['event_id'].'"';
            $council_result = mysqli_query($conn, $council_query);
            while($council_data = mysqli_fetch_assoc($council_result)){
                $event_array['council'][] = $council_data;
            }

            // Images get
            $images = array();
            $images_query = 'SELECT * FROM `event_images` WHERE event_id = "'.$requestField['event_id'].'"';
            $images_result = mysqli_query($conn, $images_query);
            while($images_data = mysqli_fetch_assoc($images_result)){
                $images['id'] = $images_data['id'];
                $images['name'] = BASE_URL.'upload/event_images/'.$images_data['image_url'];
                $event_array['images'][] = $images;
            }

            // Document get
            $document = array();
            $document_query = 'SELECT * FROM `event_documents` WHERE event_id = "'.$requestField['event_id'].'"';
            $document_result = mysqli_query($conn, $document_query);
            while($document_data = mysqli_fetch_assoc($document_result)){
                $document['id'] = $document_data['id'];
                $document['name'] = BASE_URL.'upload/event_document/'.$document_data['file_name'];
                $event_array['document'][$document_data['type']][] = $document;
                //$event_array['document']['type'][] = $document_data['type'];
            }

            $res['status'] = '1';
            $res['message'] = "Event Add Sucussfully.";
            $res['data'] = $event_array;
            echo json_encode($res);
            return false;
        }
        break;
    case "edit_event":
        $counter = 0;
        $errors = array();
        // Check Token code
        if(!isset($requestField['api_token'])){
            $counter++;
            $errors['api_token'] = "API Token field is required";
        }else{
            $check_token = check_api_token($requestField['api_token']);
            if($check_token == "0"){
                $res['status'] = "0";
                $res['message'] = "Token Not Match.";
                $res['data'] = new arrayObject();
                echo json_encode($res);
                return false;
            }else{
                $requestField['user_id'] = $check_token;
            }
        }
        if(!isset($requestField['user_id'])){
            $counter++;
            $errors['user_id'] = "User ID field is required";
        }
        if(!isset($requestField['event_id'])){
            $counter++;
            $errors['event_id'] = "Event ID field is required";
        }
        if($counter > 0 && count($errors) > 0){
            $res['status'] = '0';
            $res['message'] = "Total ".$counter." Found in Request";
            $res['data'] = $errors; 
            echo json_encode($res);
            return false;
        }
        $sql = 'SELECT * FROM `users`WHERE id = "'.$requestField['user_id'].'"';
        $result = mysqli_query($conn, $sql);
        //$user_data = mysqli_fetch_assoc($result);
        $count = mysqli_num_rows($result);
        if($count == 0){
            $res['status'] = '0';
            $res['message'] = "User is not exist";
            $res['data'] = new arrayObject();
            echo json_encode($res);
            return false;
        }else{
            // update Event Code
            $update_query = 'UPDATE events SET 
            title= "'.$requestField['name'].'",
            description = "'.$requestField['description'].'",
            event_date = "'.date("Y-m-d", strtotime($requestField['date'])).'",
            event_time = "'.$requestField['time'].'",
            event_date_time = "'.date("Y-m-d", strtotime($requestField['date'])).' '.$requestField['time'].'",
            registration_end_date = "'.date("Y-m-d", strtotime($requestField['registration_end_date'])).'",
            registration_end_time = "'.$requestField['registration_end_time'].'",
            registration_end_datetime = "'.date("Y-m-d", strtotime($requestField['registration_end_date'])).' '.$requestField['registration_end_time'].'",
            event_internal_notes = "'.$requestField['event_internal_notes'].'",
            event_external_notes = "'.$requestField['event_external_notes'].'",
            last_update_by = "'.$requestField['user_id'].'",
            modified_date = "'.date('Y-m-d H:i:s').'"
            WHERE id = "'.$requestField['event_id'].'"
            ';
            $result = mysqli_query($conn, $update_query);
            if($result){
                // Add Council Members
                $delete_council = 'DELETE FROM `event_council_members` WHERE event_id ="'.$requestField['event_id'].'"';
                $delete_council_result = mysqli_query($conn, $delete_council);
                $council = $requestField['council_ids'];
                $council_array = explode(",",$council);
                foreach($council_array as $key=>$value){
                    $add_query = 'INSERT INTO event_council_members SET event_id= "'.$requestField['event_id'].'",council_member = "'.$value.'"';
                    $result = mysqli_query($conn, $add_query);
                }

                // Add Images
                if(isset($_FILES["event_image"]["name"]) && $_FILES["event_image"]["name"] != ''){
                    $delete_image = 'DELETE FROM `event_images` WHERE event_id ="'.$requestField['event_id'].'"';
                    $delete_image_result = mysqli_query($conn, $delete_image);
                    
                    $path = '../../upload/event_images';
                    if (!file_exists($path)) {
                        mkdir($path, 0777, true);
                        chmod($path, 0777);
                    }
                    $path_parts = pathinfo($_FILES["event_image"]["name"]);
                    $imageName = $path_parts['filename']."_".time() . "." . $path_parts['extension'];
                    move_uploaded_file($_FILES['event_image']['tmp_name'], "$path/$imageName");
                    $add_query = 'INSERT INTO event_images SET event_id= "'.$requestField['event_id'].'",image_url = "'.$imageName.'",created_date = "'.date('Y-m-d H:i:s').'"';
                    $result = mysqli_query($conn, $add_query);
                }
                // Add Document
                // PDF Document
                $pdf_count = count($_FILES["pdf_document"]["name"]);
                for ($i=0; $i < $pdf_count; $i++) { 
                
                    if(isset($_FILES["pdf_document"]["name"][$i]) && $_FILES["pdf_document"]["name"][$i] != ''){
                        $path = '../../upload/event_document';
                        if (!file_exists($path)) {
                            mkdir($path, 0777, true);
                            chmod($path, 0777);
                        }
                        $path_parts = pathinfo($_FILES["pdf_document"]["name"][$i]);
                        $imageName1 = $path_parts['filename']."_".time().$i. "." . $path_parts['extension'];
                        move_uploaded_file($_FILES['pdf_document']['tmp_name'][$i], "$path/$imageName1");
                        $add_query = 'INSERT INTO event_documents SET event_id= "'.$requestField['event_id'].'",file_name = "'.$imageName1.'",type = "pdf_document"';
                        $result = mysqli_query($conn, $add_query);
                    }
                }

                // waiver from Document
                $waiver_count = count($_FILES["waiver_from"]["name"]);
                for ($i=0; $i < $waiver_count; $i++) { 
                    if(isset($_FILES["waiver_from"]["name"][$i]) && $_FILES["waiver_from"]["name"][$i] != ''){
                        $path = '../../upload/event_document';
                        if (!file_exists($path)) {
                            mkdir($path, 0777, true);
                            chmod($path, 0777);
                        }
                        $path_parts = pathinfo($_FILES["waiver_from"]["name"][$i]);
                        $imageName = $path_parts['filename']."_".time().$i. "." . $path_parts['extension'];
                        move_uploaded_file($_FILES['waiver_from']['tmp_name'][$i], "$path/$imageName");
                        $add_query = 'INSERT INTO event_documents SET event_id= "'.$requestField['event_id'].'",file_name = "'.$imageName.'",type = "waiver_from"';
                        $result = mysqli_query($conn, $add_query);
                    }
                }
                $res['status'] = '1';
                $res['message'] = "Event Updated Successfully.";
                $res['data'] = new arrayObject();
                echo json_encode($res);
                return false;
            }else{
                $res['status'] = '0';
                $res['message'] = "Event Update Problem Try again";
                $res['data'] = new arrayObject();
                echo json_encode($res);
                return false;
            }
        }
        break;
    case "delete_document" : 
        $counter = 0;
        $errors = array();
        // Check Token code
        if(!isset($requestField['api_token'])){
            $counter++;
            $errors['api_token'] = "API Token field is required";
        }else{
            $check_token = check_api_token($requestField['api_token']);
            if($check_token == "0"){
                $res['status'] = "0";
                $res['message'] = "Token Not Match.";
                $res['data'] = new arrayObject();
                echo json_encode($res);
                return false;
            }else{
                $requestField['user_id'] = $check_token;
            }
        }
        if(!isset($requestField['user_id'])){
            $counter++;
            $errors['user_id'] = "User ID field is required";
        }
        if(!isset($requestField['event_id'])){
            $counter++;
            $errors['event_id'] = "Event ID field is required";
        }
        if(!isset($requestField['document_id'])){
            $counter++;
            $errors['document_id'] = "Document ID field is required";
        }
        if(!isset($requestField['document_type'])){
            $counter++;
            $errors['document_type'] = "Document Type field is required";
        }
        if($counter > 0 && count($errors) > 0){
            $res['status'] = '0';
            $res['message'] = "Total ".$counter." Found in Request";
            $res['data'] = $errors; 
            echo json_encode($res);
            return false;
        }
        $sql = 'SELECT * FROM `users`WHERE id = "'.$requestField['user_id'].'"';
        $result = mysqli_query($conn, $sql);
        //$user_data = mysqli_fetch_assoc($result);
        $count = mysqli_num_rows($result);
        if($count == 0){
            $res['status'] = '0';
            $res['message'] = "User is not exist";
            $res['data'] = new arrayObject();
            echo json_encode($res);
            return false;
        }else{
            $delete_query = 'DELETE FROM `event_documents` WHERE event_id = "'.$requestField['event_id'].'" AND type="'.$requestField['document_type'].'" AND id="'.$requestField['document_id'].'"';
            $result = mysqli_query($conn, $delete_query);
            if($result){
                $res['status'] = '1';
                $res['message'] = "Document Delete Sucussfully.";
                $res['data'] = new arrayObject();
                echo json_encode($res);
                return false;
            }else{
                $res['status'] = '0';
                $res['message'] = "Document Delete Problem Try again";
                $res['data'] = new arrayObject();
                echo json_encode($res);
                return false;
            }
        }
        break;
    case "delete_images":
        $counter = 0;
        $errors = array();
        // Check Token code
        if(!isset($requestField['api_token'])){
            $counter++;
            $errors['api_token'] = "API Token field is required";
        }else{
            $check_token = check_api_token($requestField['api_token']);
            if($check_token == "0"){
                $res['status'] = "0";
                $res['message'] = "Token Not Match.";
                $res['data'] = new arrayObject();
                echo json_encode($res);
                return false;
            }else{
                $requestField['user_id'] = $check_token;
            }
        }
        if(!isset($requestField['user_id'])){
            $counter++;
            $errors['user_id'] = "User ID field is required";
        }
        if(!isset($requestField['event_id'])){
            $counter++;
            $errors['event_id'] = "Event ID field is required";
        }
        if(!isset($requestField['image_id'])){
            $counter++;
            $errors['image_id'] = "Image ID field is required";
        }
        if($counter > 0 && count($errors) > 0){
            $res['status'] = '0';
            $res['message'] = "Total ".$counter." Found in Request";
            $res['data'] = $errors; 
            echo json_encode($res);
            return false;
        }
        $sql = 'SELECT * FROM `users`WHERE id = "'.$requestField['user_id'].'"';
        $result = mysqli_query($conn, $sql);
        //$user_data = mysqli_fetch_assoc($result);
        $count = mysqli_num_rows($result);
        if($count == 0){
            $res['status'] = '0';
            $res['message'] = "User is not exist";
            $res['data'] = new arrayObject();
            echo json_encode($res);
            return false;
        }else{
            $delete_query = 'DELETE FROM `event_images` WHERE event_id = "'.$requestField['event_id'].'" AND id="'.$requestField['image_id'].'"';
            $result = mysqli_query($conn, $delete_query);
            if($result){
                $res['status'] = '1';
                $res['message'] = "Image Delete Sucussfully.";
                $res['data'] = new arrayObject();
                echo json_encode($res);
                return false;
            }else{
                $res['status'] = '0';
                $res['message'] = "Image Delete Problem Try again";
                $res['data'] = new arrayObject();
                echo json_encode($res);
                return false;
            }
        }
        break;
    case "past_event_list" :
        $counter = 0;
        $errors = array();
        // Check Token code
        if(!isset($requestField['api_token'])){
            $counter++;
            $errors['api_token'] = "API Token field is required";
        }else{
            $check_token = check_api_token($requestField['api_token']);
            if($check_token == "0"){
                $res['status'] = "0";
                $res['message'] = "Token Not Match.";
                $res['data'] = new arrayObject();
                echo json_encode($res);
                return false;
            }else{
                $requestField['user_id'] = $check_token;
            }
        }
        if(!isset($requestField['user_id'])){
            $counter++;
            $errors['user_id'] = "User ID field is required";
        }
        if(!isset($requestField['pageNumber'])){
            $counter++;
            $errors['pageNumber'] = "Page Number field is required";
        }
        if(!isset($requestField['pageLimit'])){
            $counter++;
            $errors['pageLimit'] = "Page Limit field is required";
        }
        
        if($counter > 0 && count($errors) > 0){
            $res['status'] = '0';
            $res['message'] = "Total ".$counter." Found in Request";
            $res['data'] = $errors; 
            echo json_encode($res);
            return false;
        }

        $sql = 'SELECT * FROM `users`WHERE id = "'.$requestField['user_id'].'"';
        $result = mysqli_query($conn, $sql);
        //$user_data = mysqli_fetch_assoc($result);
        $count = mysqli_num_rows($result);
        if($count == 0){
            $res['status'] = '0';
            $res['message'] = "User is not exist";
            $res['data'] = new arrayObject();
            echo json_encode($res);
            return false;
        }else{
            $pageNumber = ($requestField['pageNumber']-1)*$requestField['pageLimit'];
            $pageLimit =$requestField['pageLimit'];
            date_default_timezone_set('Asia/Kolkata');
            $current_date_time = date('Y-m-d H:i:s');
            $data = array();
            
            
            // Data get
            $sql = 'SELECT u.id as user_ID,h.name as house_name,h.id as house_id,ut.name as user_type_name FROM `users` u LEFT JOIN houses h ON u.house_id = h.id LEFT JOIN user_type ut ON ut.id = u.user_type_id WHERE u.id = "'.$requestField['user_id'].'"';
            $result = mysqli_query($conn, $sql);
            $user_data = mysqli_fetch_assoc($result);
            if($user_data['user_type_name'] == "Administrator" || $user_data['user_type_name'] == "Core Captain"){
                // Total Count Get 
                $count_sql = 'SELECT COUNT(*) as count FROM `events` WHERE event_date_time < "'.$current_date_time.'" AND is_delete = "0" AND `title` LIKE "%'.$requestField['search'].'%"';
                $count_result = mysqli_query($conn, $count_sql);
                $count_data = mysqli_fetch_assoc($count_result);
                $count_data_value = $count_data['count'];
                
                $sql = 'SELECT e.*,es.status as is_signup,es.reject_by,es.approved_by FROM `events` e LEFT JOIN event_signups es ON e.id = es.event_id AND es.user_id = "'.$requestField['user_id'].'" WHERE e.event_date_time < "'.$current_date_time.'" AND e.is_delete = "0" AND e.title LIKE "%'.$requestField['search'].'%" ORDER BY `e`.`event_date_time`  ASC LIMIT '.$pageNumber.','.$pageLimit.'';

            }else if($user_data['user_type_name'] == "House Captain"){
                // Total Count Get 
                $count_sql = 'SELECT COUNT(*) as count FROM `events` WHERE event_date_time < "'.$current_date_time.'" AND is_delete = "0" AND `title` LIKE "%'.$requestField['search'].'%"';
                $count_result = mysqli_query($conn, $count_sql);
                $count_data = mysqli_fetch_assoc($count_result);
                $count_data_value = $count_data['count'];
                
                $sql = 'SELECT e.*,es.status as is_signup,es.reject_by,es.approved_by FROM `events` e LEFT JOIN event_signups es ON e.id = es.event_id AND es.user_id = "'.$requestField['user_id'].'" WHERE e.event_date_time < "'.$current_date_time.'" AND e.is_delete = "0" AND e.title LIKE "%'.$requestField['search'].'%" GROUP BY e.id ORDER BY `e`.`event_date_time`  ASC LIMIT '.$pageNumber.','.$pageLimit.'';
            }else{
                $student_sql = 'SELECT dob,gender,class as grade FROM `users`WHERE id = "'.$requestField['user_id'].'"';
                $result = mysqli_query($conn, $student_sql);
                $user_details = mysqli_fetch_assoc($result);
                $user_year = date("Y", strtotime($user_details['dob']));

                // Total Count Get 
                // $count_sql = 'SELECT COUNT(*) as count FROM `events` e 
                // LEFT JOIN event_age_gender_categories eagc ON eagc.event_id = e.id 
                // LEFT JOIN age_gender_categories agc ON agc.id =eagc.age_gender_id WHERE event_date_time < "'.$current_date_time.'" AND EXTRACT(YEAR FROM "'.$user_details['dob'].'") >= agc.start_year and EXTRACT(YEAR FROM "'.$user_details['dob'].'") <= agc.end_year AND agc.gender = "'.$user_details['gender'].'" AND e.title LIKE "%'.$requestField['search'].'%"';
                // $count_result = mysqli_query($conn, $count_sql);
                // $count_data = mysqli_fetch_assoc($count_result);
                
                // $sql = 'SELECT e.*,es.status as is_signup,es.reject_by,es.approved_by FROM `events` e 
                // LEFT JOIN event_age_gender_categories eagc ON eagc.event_id = e.id 
                // LEFT JOIN event_signups es ON e.id = es.event_id and es.user_id = "'.$requestField['user_id'].'"
                // LEFT JOIN age_gender_categories agc ON agc.id =eagc.age_gender_id WHERE event_date_time < "'.$current_date_time.'" AND EXTRACT(YEAR FROM "'.$user_details['dob'].'") >= agc.start_year and EXTRACT(YEAR FROM "'.$user_details['dob'].'") <= agc.end_year AND agc.gender = "'.$user_details['gender'].'" AND e.title LIKE "%'.$requestField['search'].'%" ORDER BY `e`.`event_date_time`  ASC LIMIT '.$pageNumber.','.$pageLimit.'';

                // Total Count Get
                $count_sql = '(SELECT e.*,es.status as is_signup,es.reject_by,es.approved_by FROM events e
                INNER JOIN event_age_gender_categories eagc ON e.id = eagc.event_id
                INNER JOIN age_gender_categories agc ON agc.id = eagc.age_gender_id
                LEFT JOIN event_signups es ON e.id = es.event_id AND es.user_id = "'.$requestField['user_id'].'"
                WHERE event_date_time < "'.$current_date_time.'" AND (agc.start_year = "'.$user_year.'" OR agc.end_year = "'.$user_year.'") AND e.is_delete = "0" AND agc.gender = "'.$user_details['gender'].'" GROUP BY e.id 
                )UNION ALL
                (
                SELECT e.*,es.status as is_signup,es.reject_by,es.approved_by FROM events e
                INNER JOIN event_grade_categories egc ON egc.event_id = e.id
                INNER JOIN grades g ON g.id = egc.grade_id
                LEFT JOIN event_signups es ON e.id = es.event_id AND es.user_id = "'.$requestField['user_id'].'"
                WHERE event_date_time < "'.$current_date_time.'" AND e.is_delete = "0" AND "'.$user_details['grade'].'" between g.start_grade and g.end_grade GROUP BY e.id) ORDER BY event_date_time ASC ';
                $count_result = mysqli_query($conn, $count_sql);
                $count_data = mysqli_num_rows($count_result);
                $count_data_value = $count_data;

                $sql = '(SELECT e.*,es.status as is_signup,es.reject_by,es.approved_by FROM events e
                INNER JOIN event_age_gender_categories eagc ON e.id = eagc.event_id
                INNER JOIN age_gender_categories agc ON agc.id = eagc.age_gender_id
                LEFT JOIN event_signups es ON e.id = es.event_id AND es.user_id = "'.$requestField['user_id'].'"
                WHERE event_date_time < "'.$current_date_time.'" AND e.is_delete = "0" AND (agc.start_year = "'.$user_year.'" OR agc.end_year = "'.$user_year.'") AND agc.gender = "'.$user_details['gender'].'" GROUP BY e.id 
                )UNION ALL
                (
                SELECT e.*,es.status as is_signup,es.reject_by,es.approved_by FROM events e
                INNER JOIN event_grade_categories egc ON egc.event_id = e.id
                INNER JOIN grades g ON g.id = egc.grade_id
                LEFT JOIN event_signups es ON e.id = es.event_id AND es.user_id = "'.$requestField['user_id'].'"
                WHERE event_date_time < "'.$current_date_time.'" AND e.is_delete = "0" AND "'.$user_details['grade'].'" between g.start_grade and g.end_grade GROUP BY e.id) ORDER BY event_date_time ASC LIMIT '.$pageNumber.','.$pageLimit.'';

                
            }
            $result = mysqli_query($conn, $sql);
            while($event_data = mysqli_fetch_assoc($result)){
                $event_array['id'] = (isset($event_data['id']) && $event_data['id'] != "") ? $event_data['id'] : "";
                $event_array['title'] = (isset($event_data['title']) && $event_data['title'] != "") ? $event_data['title'] : "";
                $event_array['description'] = (isset($event_data['description']) && $event_data['description'] != "") ? $event_data['description'] : "";
                $event_array['event_date'] = (isset($event_data['event_date']) && $event_data['event_date'] != "") ? $event_data['event_date'] : "";
                $event_array['event_time'] = (isset($event_data['event_time']) && $event_data['event_time'] != "") ? $event_data['event_time'] : "";
                $event_array['registration_end_date'] = (isset($event_data['registration_end_date']) && $event_data['registration_end_date'] != "") ? $event_data['registration_end_date'] : "";
                $event_array['registration_end_time'] = (isset($event_data['registration_end_time']) && $event_data['registration_end_time'] != "") ? $event_data['registration_end_time'] : "";
                $event_array['event_internal_notes'] = (isset($event_data['event_internal_notes']) && $event_data['event_internal_notes'] != "") ? $event_data['event_internal_notes'] : "";
                $event_array['event_external_notes'] = (isset($event_data['event_external_notes']) && $event_data['event_external_notes'] != "") ? $event_data['event_external_notes'] : "";
                $event_array['category_id'] = (isset($event_data['category_id']) && $event_data['category_id'] != "") ? $event_data['category_id'] : "";
                $category_query = 'SELECT `name` FROM `categories` WHERE id = "'.$event_data['category_id'].'"';
                $category_result = mysqli_query($conn, $category_query);
                $category_data = mysqli_fetch_assoc($category_result);
                $event_array['category_name'] = (isset($category_data['name']) && $category_data['name'] != "") ? $category_data['name'] : "";
                $event_array['sub_category_id'] = (isset($event_data['sub_category_id']) && $event_data['sub_category_id'] != "") ? $event_data['sub_category_id'] : "";
                $event_array['is_signup'] = (isset($event_data['is_signup']) && $event_data['is_signup'] != "") ? $event_data['is_signup'] : "";
                $event_images_query = 'SELECT `image_url` FROM `event_images` WHERE id = "'.$event_data['id'].'"';
                $event_images_result = mysqli_query($conn, $event_images_query);
                $event_images_data = mysqli_fetch_assoc($event_images_result);
                $event_array['image'] = (isset($event_images_data['image_url']) && $event_images_data['image_url'] != "") ? BASE_URL.'upload/event_images/'.$event_images_data['image_url'] : "";
                // get user role and name for approved
                $role_approved_query = 'SELECT first_name as user_name,ut.name as user_type_name FROM `users` u LEFT JOIN user_type ut ON ut.id = u.user_type_id WHERE u.id = "'.$event_data['approved_by'].'"';
                $role_approved_result = mysqli_query($conn, $role_approved_query);
                $role_approved_data = mysqli_fetch_assoc($role_approved_result);
                $role_approved_count = mysqli_num_rows($role_approved_result);
                if($role_approved_count > 0){
                    $event_array['approved_by'] = (isset($event_data['approved_by']) && $event_data['approved_by'] != "") ? $event_data['approved_by'] : "";
                    $event_array['approved_by_name'] = (isset($role_approved_data['user_name']) && $role_approved_data['user_name'] != "") ? $role_approved_data['user_name'] : "";
                    $event_array['approved_by_role'] = (isset($role_approved_data['user_type_name']) && $role_approved_data['user_type_name'] != "") ? $role_approved_data['user_type_name'] : "";
                }else{
                    $event_array['approved_by'] = "";
                    $event_array['approved_by_name'] = "";
                    $event_array['approved_by_role'] = "";
                }
                // get user role and name for reject
                $role_reject_query = 'SELECT first_name as user_name,ut.name as user_type_name FROM `users` u LEFT JOIN user_type ut ON ut.id = u.user_type_id WHERE u.id = "'.$event_data['reject_by'].'"';
                $role_reject_result = mysqli_query($conn, $role_reject_query);
                $role_reject_data = mysqli_fetch_assoc($role_reject_result);
                $role_reject_count = mysqli_num_rows($role_reject_result);
                if($role_reject_count > 0){
                    $event_array['reject_by'] = (isset($event_data['reject_by']) && $event_data['reject_by'] != "") ? $event_data['reject_by'] : "";
                    $event_array['reject_by_name'] = (isset($role_reject_data['user_name']) && $role_reject_data['user_name'] != "") ? $role_reject_data['user_name'] : "";
                    $event_array['reject_by_role'] = (isset($role_reject_data['user_type_name']) && $role_reject_data['user_type_name'] != "") ? $role_reject_data['user_type_name'] : "";
                }else{
                    $event_array['reject_by'] = "";
                    $event_array['reject_by_name'] = "";
                    $event_array['reject_by_role'] = "";
                }
                

                $data[] = $event_array;
            }
            $res['status'] = '1';
            $res['message'] = "Past Event List.";
            $res['data'] = $data;
            $res['Total_Count'] = (string)$count_data_value;
            $res['last_page'] = ceil($count_data_value / $pageLimit);
            echo json_encode($res);
            return false;
        }
        break;
    case "upcomming_event_list" :
        // $date = date("Y-m-d H:i:s", strtotime("2021-01-11 12:29:00"));
        // date_default_timezone_set('Asia/Kolkata');
        // $now = date('Y-m-d H:i:s');
        // if($date < $now) {
        //     echo 'date is in the past';exit;
        // }else{
        //     echo "error";exit;
        // }
        $counter = 0;
        $errors = array();
        // Check Token code
        if(!isset($requestField['api_token'])){
            $counter++;
            $errors['api_token'] = "API Token field is required";
        }else{
            $check_token = check_api_token($requestField['api_token']);
            if($check_token == "0"){
                $res['status'] = "0";
                $res['message'] = "Token Not Match.";
                $res['data'] = new arrayObject();
                echo json_encode($res);
                return false;
            }else{
                $requestField['user_id'] = $check_token;
            }
        }
        if(!isset($requestField['user_id'])){
            $counter++;
            $errors['user_id'] = "User ID field is required";
        }
        if(!isset($requestField['pageNumber'])){
            $counter++;
            $errors['pageNumber'] = "Page Number field is required";
        }
        if(!isset($requestField['pageLimit'])){
            $counter++;
            $errors['pageLimit'] = "Page Limit field is required";
        }
        
        if($counter > 0 && count($errors) > 0){
            $res['status'] = '0';
            $res['message'] = "Total ".$counter." Found in Request";
            $res['data'] = $errors; 
            echo json_encode($res);
            return false;
        }

        $sql = 'SELECT * FROM `users`WHERE id = "'.$requestField['user_id'].'"';
        $result = mysqli_query($conn, $sql);
        //$user_data = mysqli_fetch_assoc($result);
        $count = mysqli_num_rows($result);
        if($count == 0){
            $res['status'] = '0';
            $res['message'] = "User is not exist";
            $res['data'] = new arrayObject();
            echo json_encode($res);
            return false;
        }else{
            $pageNumber = ($requestField['pageNumber']-1)*$requestField['pageLimit'];
            $pageLimit =$requestField['pageLimit'];
            date_default_timezone_set('Asia/Kolkata');
            $current_date_time = date('Y-m-d H:i:s');
            $data = array();
            
            // Data get
            // Data get
            $sql = 'SELECT u.id as user_ID,h.name as house_name,h.id as house_id,ut.name as user_type_name FROM `users` u LEFT JOIN houses h ON u.house_id = h.id LEFT JOIN user_type ut ON ut.id = u.user_type_id WHERE u.id = "'.$requestField['user_id'].'"';
            $result = mysqli_query($conn, $sql);
            $user_data = mysqli_fetch_assoc($result);
            if($user_data['user_type_name'] == "Administrator" || $user_data['user_type_name'] == "Core Captain"){
                // Total Count Get 
                $count_sql = 'SELECT COUNT(*) as count FROM `events` WHERE event_date_time > "'.$current_date_time.'" AND `title` LIKE "%'.$requestField['search'].'%" AND is_delete = "0"';
                $count_result = mysqli_query($conn, $count_sql);
                $count_data = mysqli_fetch_assoc($count_result);
                $count_data_value = $count_data['count'];
                
                $sql = 'SELECT e.*,es.status as is_signup,es.reject_by,es.approved_by FROM `events` e LEFT JOIN event_signups es ON e.id = es.event_id AND es.user_id = "'.$requestField['user_id'].'" WHERE e.event_date_time > "'.$current_date_time.'" AND e.is_delete = "0" AND e.title LIKE "%'.$requestField['search'].'%" GROUP BY e.id ORDER BY `e`.`event_date_time`  ASC LIMIT '.$pageNumber.','.$pageLimit.'';

            }else if($user_data['user_type_name'] == "House Captain"){
                 // Total Count Get 
                 $count_sql = 'SELECT COUNT(*) as count FROM `events` WHERE event_date_time > "'.$current_date_time.'" AND is_delete = "0" AND `title` LIKE "%'.$requestField['search'].'%"';
                 $count_result = mysqli_query($conn, $count_sql);
                 $count_data = mysqli_fetch_assoc($count_result);
                 $count_data_value = $count_data['count'];
                 
                 $sql = 'SELECT e.*,es.status as is_signup,es.reject_by,es.approved_by FROM `events` e LEFT JOIN event_signups es ON e.id = es.event_id AND es.user_id = "'.$requestField['user_id'].'" WHERE e.event_date_time > "'.$current_date_time.'" AND e.is_delete = "0" AND e.title LIKE "%'.$requestField['search'].'%" GROUP BY e.id ORDER BY `e`.`event_date_time`  ASC LIMIT '.$pageNumber.','.$pageLimit.'';
            }else{
                $student_sql = 'SELECT dob,gender,class as grade FROM `users`WHERE id = "'.$requestField['user_id'].'"';
                $result = mysqli_query($conn, $student_sql);
                $user_details = mysqli_fetch_assoc($result);
                $user_year = date("Y", strtotime($user_details['dob']));

                // Total Count Get (OLD Query)
                // $count_sql = 'SELECT COUNT(*) as count FROM `events` e 
                // LEFT JOIN event_age_gender_categories eagc ON eagc.event_id = e.id 
                // LEFT JOIN age_gender_categories agc ON agc.id =eagc.age_gender_id WHERE event_date_time > "'.$current_date_time.'" AND EXTRACT(YEAR FROM "'.$user_details['dob'].'") >= agc.start_year and EXTRACT(YEAR FROM "'.$user_details['dob'].'") <= agc.end_year AND agc.gender = "'.$user_details['gender'].'" AND e.title LIKE "%'.$requestField['search'].'%"';
                // $count_result = mysqli_query($conn, $count_sql);
                // $count_data = mysqli_fetch_assoc($count_result);
                
                // $sql = 'SELECT e.*,es.status as is_signup,es.reject_by,es.approved_by FROM `events` e 
                // LEFT JOIN event_age_gender_categories eagc ON eagc.event_id = e.id 
                // LEFT JOIN event_signups es ON e.id = es.event_id and es.user_id = "'.$requestField['user_id'].'" LEFT JOIN age_gender_categories agc ON agc.id =eagc.age_gender_id WHERE event_date_time > "'.$current_date_time.'" AND EXTRACT(YEAR FROM "'.$user_details['dob'].'") >= agc.start_year and EXTRACT(YEAR FROM "'.$user_details['dob'].'") <= agc.end_year AND agc.gender = "'.$user_details['gender'].'" AND e.title LIKE "%'.$requestField['search'].'%" ORDER BY `e`.`event_date_time`  ASC LIMIT '.$pageNumber.','.$pageLimit.'';

                // Total Count Get
                $count_sql = '(SELECT e.*,es.status as is_signup,es.reject_by,es.approved_by FROM events e
                INNER JOIN event_age_gender_categories eagc ON e.id = eagc.event_id
                INNER JOIN age_gender_categories agc ON agc.id = eagc.age_gender_id
                LEFT JOIN event_signups es ON e.id = es.event_id AND es.user_id = "'.$requestField['user_id'].'"
                WHERE event_date_time > "'.$current_date_time.'" AND (agc.start_year = "'.$user_year.'" OR agc.end_year = "'.$user_year.'") AND e.is_delete = "0" AND agc.gender = "'.$user_details['gender'].'" GROUP BY e.id 
                )UNION ALL
                (
                SELECT e.*,es.status as is_signup,es.reject_by,es.approved_by FROM events e
                INNER JOIN event_grade_categories egc ON egc.event_id = e.id
                INNER JOIN grades g ON g.id = egc.grade_id
                LEFT JOIN event_signups es ON e.id = es.event_id AND es.user_id = "'.$requestField['user_id'].'"
                WHERE event_date_time > "'.$current_date_time.'" AND e.is_delete = "0" AND "'.$user_details['grade'].'" between g.start_grade and g.end_grade GROUP BY e.id) ORDER BY event_date_time ASC ';
                $count_result = mysqli_query($conn, $count_sql);
                $count_data = mysqli_num_rows($count_result);
                $count_data_value = $count_data;

                $sql = '(SELECT e.*,es.status as is_signup,es.reject_by,es.approved_by FROM events e
                INNER JOIN event_age_gender_categories eagc ON e.id = eagc.event_id
                INNER JOIN age_gender_categories agc ON agc.id = eagc.age_gender_id
                LEFT JOIN event_signups es ON e.id = es.event_id AND es.user_id = "'.$requestField['user_id'].'"
                WHERE event_date_time > "'.$current_date_time.'" AND e.is_delete = "0" AND (agc.start_year = "'.$user_year.'" OR agc.end_year = "'.$user_year.'") AND agc.gender = "'.$user_details['gender'].'" GROUP BY e.id 
                )UNION ALL
                (
                SELECT e.*,es.status as is_signup,es.reject_by,es.approved_by FROM events e
                INNER JOIN event_grade_categories egc ON egc.event_id = e.id
                INNER JOIN grades g ON g.id = egc.grade_id
                LEFT JOIN event_signups es ON e.id = es.event_id AND es.user_id = "'.$requestField['user_id'].'"
                WHERE event_date_time > "'.$current_date_time.'" AND e.is_delete = "0" AND "'.$user_details['grade'].'" between g.start_grade and g.end_grade GROUP BY e.id) ORDER BY event_date_time ASC LIMIT '.$pageNumber.','.$pageLimit.'';
            }
            $result = mysqli_query($conn, $sql);
            while($event_data = mysqli_fetch_assoc($result)){
                $event_array['id'] = (isset($event_data['id']) && $event_data['id'] != "") ? $event_data['id'] : "";
                $event_array['title'] = (isset($event_data['title']) && $event_data['title'] != "") ? $event_data['title'] : "";
                $event_array['description'] = (isset($event_data['description']) && $event_data['description'] != "") ? $event_data['description'] : "";
                $event_array['event_date'] = (isset($event_data['event_date']) && $event_data['event_date'] != "") ? $event_data['event_date'] : "";
                $event_array['event_time'] = (isset($event_data['event_time']) && $event_data['event_time'] != "") ? $event_data['event_time'] : "";
                $event_array['registration_end_date'] = (isset($event_data['registration_end_date']) && $event_data['registration_end_date'] != "") ? $event_data['registration_end_date'] : "";
                $event_array['registration_end_time'] = (isset($event_data['registration_end_time']) && $event_data['registration_end_time'] != "") ? $event_data['registration_end_time'] : "";
                $event_array['event_internal_notes'] = (isset($event_data['event_internal_notes']) && $event_data['event_internal_notes'] != "") ? $event_data['event_internal_notes'] : "";
                $event_array['event_external_notes'] = (isset($event_data['event_external_notes']) && $event_data['event_external_notes'] != "") ? $event_data['event_external_notes'] : "";
                $event_array['category_id'] = (isset($event_data['category_id']) && $event_data['category_id'] != "") ? $event_data['category_id'] : "";
                $category_query = 'SELECT `name` FROM `categories` WHERE id = "'.$event_data['category_id'].'"';
                $category_result = mysqli_query($conn, $category_query);
                $category_data = mysqli_fetch_assoc($category_result);
                $event_array['category_name'] = (isset($category_data['name']) && $category_data['name'] != "") ? $category_data['name'] : "";
                $event_array['sub_category_id'] = (isset($event_data['sub_category_id']) && $event_data['sub_category_id'] != "") ? $event_data['sub_category_id'] : "";
                $event_array['is_signup'] = (isset($event_data['is_signup']) && $event_data['is_signup'] != "") ? $event_data['is_signup'] : "";
                $event_images_query = 'SELECT `image_url` FROM `event_images` WHERE event_id = "'.$event_data['id'].'"';
                $event_images_result = mysqli_query($conn, $event_images_query);
                $event_images_data = mysqli_fetch_assoc($event_images_result);
                $event_array['image'] = (isset($event_images_data['image_url']) && $event_images_data['image_url'] != "") ? BASE_URL.'upload/event_images/'.$event_images_data['image_url'] : "";
                // get user role and name for approved
                $role_approved_query = 'SELECT first_name as user_name,ut.name as user_type_name FROM `users` u LEFT JOIN user_type ut ON ut.id = u.user_type_id WHERE u.id = "'.$event_data['approved_by'].'"';
                $role_approved_result = mysqli_query($conn, $role_approved_query);
                $role_approved_data = mysqli_fetch_assoc($role_approved_result);
                $role_approved_count = mysqli_num_rows($role_approved_result);
                if($role_approved_count > 0){
                    $event_array['approved_by'] = (isset($event_data['approved_by']) && $event_data['approved_by'] != "") ? $event_data['approved_by'] : "";
                    $event_array['approved_by_name'] = (isset($role_approved_data['user_name']) && $role_approved_data['user_name'] != "") ? $role_approved_data['user_name'] : "";
                    $event_array['approved_by_role'] = (isset($role_approved_data['user_type_name']) && $role_approved_data['user_type_name'] != "") ? $role_approved_data['user_type_name'] : "";
                }else{
                    $event_array['approved_by'] = "";
                    $event_array['approved_by_name'] = "";
                    $event_array['approved_by_role'] = "";
                }
                // get user role and name for reject
                $role_reject_query = 'SELECT first_name as user_name,ut.name as user_type_name FROM `users` u LEFT JOIN user_type ut ON ut.id = u.user_type_id WHERE u.id = "'.$event_data['reject_by'].'"';
                $role_reject_result = mysqli_query($conn, $role_reject_query);
                $role_reject_data = mysqli_fetch_assoc($role_reject_result);
                $role_reject_count = mysqli_num_rows($role_reject_result);
                if($role_reject_count > 0){
                    $event_array['reject_by'] = (isset($event_data['reject_by']) && $event_data['reject_by'] != "") ? $event_data['reject_by'] : "";
                    $event_array['reject_by_name'] = (isset($role_reject_data['user_name']) && $role_reject_data['user_name'] != "") ? $role_reject_data['user_name'] : "";
                    $event_array['reject_by_role'] = (isset($role_reject_data['user_type_name']) && $role_reject_data['user_type_name'] != "") ? $role_reject_data['user_type_name'] : "";
                }else{
                    $event_array['reject_by'] = "";
                    $event_array['reject_by_name'] = "";
                    $event_array['reject_by_role'] = "";
                }

                $data[] = $event_array;
            }
            $res['status'] = '1';
            $res['message'] = "Upcomming Event List.";
            $res['data'] = $data;
            $res['Total_Count'] = (string)$count_data_value;
            $res['last_page'] = ceil($count_data_value / $pageLimit);
            echo json_encode($res);
            return false;
        }
        break;
    case "event_details":
        $counter = 0;
        $errors = array();
        // Check Token code
        if(!isset($requestField['api_token'])){
            $counter++;
            $errors['api_token'] = "API Token field is required";
        }else{
            $check_token = check_api_token($requestField['api_token']);
            if($check_token == "0"){
                $res['status'] = "0";
                $res['message'] = "Token Not Match.";
                $res['data'] = new arrayObject();
                echo json_encode($res);
                return false;
            }else{
                $requestField['user_id'] = $check_token;
            }
        }
        if(!isset($requestField['user_id'])){
            $counter++;
            $errors['user_id'] = "User ID field is required";
        }
        if(!isset($requestField['event_id'])){
            $counter++;
            $errors['event_id'] = "Event ID field is required";
        }
        if($counter > 0 && count($errors) > 0){
            $res['status'] = '0';
            $res['message'] = "Total ".$counter." Found in Request";
            $res['data'] = $errors; 
            echo json_encode($res);
            return false;
        }
        $sql = 'SELECT * FROM `users`WHERE id = "'.$requestField['user_id'].'"';
        $result = mysqli_query($conn, $sql);
        $user_data_first = mysqli_fetch_assoc($result);
        $count = mysqli_num_rows($result);
        if($count == 0){
            $res['status'] = '0';
            $res['message'] = "User is not exist";
            $res['data'] = new arrayObject();
            echo json_encode($res);
            return false;
        }else{
            $event_query = 'SELECT e.*,usd.id as submit_id,usd.file_name FROM `events` e LEFT JOIN user_submitted_docs usd ON e.id = usd.event_id AND usd.user_id = "'.$requestField['user_id'].'" WHERE e.id = "'.$requestField['event_id'].'"';
            $event_result = mysqli_query($conn, $event_query);
            $event_data = mysqli_fetch_assoc($event_result);
            
            $path = BASE_URL.'upload/waiver_form/'.$requestField['user_id'];
            if(empty($event_data['file_name'])){
                $document = "";
            }else{
                
                $document = $path.'/'.$event_data['file_name'];
            }
            $data['id'] = (isset($event_data['id']) && $event_data['id'] != "") ? $event_data['id'] : "";
            $data['category_id'] = (isset($event_data['category_id']) && $event_data['category_id'] != "") ? $event_data['category_id'] : "";
            $category_query = 'SELECT `name` FROM `categories` WHERE id = "'.$event_data['category_id'].'"';
            $category_result = mysqli_query($conn, $category_query);
            $category_data = mysqli_fetch_assoc($category_result);
            $data['category_name'] = (isset($category_data['name']) && $category_data['name'] != "") ? $category_data['name'] : "";

            $sub_category_query = 'SELECT `name` FROM `categories` WHERE id = "'.$event_data['sub_category_id'].'"';
            $sub_category_result = mysqli_query($conn, $sub_category_query);
            $sub_category_data = mysqli_fetch_assoc($sub_category_result);
            $data['sub_category_name'] = (isset($sub_category_data['name']) && $sub_category_data['name'] != "") ? $sub_category_data['name'] : "";

            $data['title'] = (isset($event_data['title']) && $event_data['title'] != "") ? $event_data['title'] : "";
            $data['event_external_notes'] = (isset($event_data['event_external_notes']) && $event_data['event_external_notes'] != "") ? $event_data['event_external_notes'] : "";
            $data['event_date'] = (isset($event_data['event_date']) && $event_data['event_date'] != "") ? $event_data['event_date'] : "";
            $data['event_time'] = (isset($event_data['event_time']) && $event_data['event_time'] != "") ? $event_data['event_time'] : "";
            $data['registration_end_date'] = (isset($event_data['registration_end_date']) && $event_data['registration_end_date'] != "") ? $event_data['registration_end_date'] : "";
            $data['registration_end_time'] = (isset($event_data['registration_end_time']) && $event_data['registration_end_time'] != "") ? $event_data['registration_end_time'] : "";
            $data['waiver_document'] = (isset($document) && $document != "") ? $document : "";
            $event_document_query = 'SELECT * FROM `event_documents` WHERE event_id = "'.$requestField['event_id'].'"';
            $event_document_result = mysqli_query($conn, $event_document_query);
            $count = mysqli_num_rows($event_document_result);
            if($count > 0){
                while($event_document_data = mysqli_fetch_assoc($event_document_result)){
                    //$data[$event_document_data['type']]['name'] = BASE_URL.'upload/event_document/'.$event_document_data['file_name'];
                    $data[$event_document_data['type']]['name'][] = array(
                        "id" => $event_document_data['id'],
                        "name" => BASE_URL.'upload/event_document/'.$event_document_data['file_name']
                    );
                }
            }else{
                $data['pdf_documen'] = '';
                $data['waiver_from'] = '';
            }
            $data['event_internal_notes'] = (isset($event_data['event_internal_notes']) && $event_data['event_internal_notes'] != "") ? $event_data['event_internal_notes'] : "";
            $data['event_external_notes'] = (isset($event_data['event_external_notes']) && $event_data['event_external_notes'] != "") ? $event_data['event_external_notes'] : "";
            $data['event_external_notes'] = (isset($event_data['event_external_notes']) && $event_data['event_external_notes'] != "") ? $event_data['event_external_notes'] : "";
            $data['created_date'] = (isset($event_data['created_date']) && $event_data['created_date'] != "") ? $event_data['created_date'] : "";
            $data['category_id'] = (isset($event_data['category_id']) && $event_data['category_id'] != "") ? $event_data['category_id'] : "";
            $data['sub_category_id'] = (isset($event_data['sub_category_id']) && $event_data['sub_category_id'] != "") ? $event_data['sub_category_id'] : "";

            // age gender get
            $age_query = 'SELECT agc.id,agc.title FROM `event_age_gender_categories` eac LEFT JOIN age_gender_categories agc ON eac.age_gender_id = agc.id WHERE event_id = "'.$requestField['event_id'].'"';
            $age_result = mysqli_query($conn, $age_query);
            $age_count = mysqli_num_rows($age_result);
            if($age_count > 0 ){
                while($age_data = mysqli_fetch_assoc($age_result)){
                    $data['age_gender'][] = $age_data;
                }
            }else{
                $data['age_gender'] = array();
            }

            // grade get
            $grade_query = 'SELECT g.id,g.name FROM `event_grade_categories` egc LEFT JOIN grades g ON g.id = egc.grade_id WHERE event_id = "'.$requestField['event_id'].'"';
            $grade_result = mysqli_query($conn, $grade_query);
            $grade_count = mysqli_num_rows($grade_result);
            if($grade_count > 0){
                while($grade_data = mysqli_fetch_assoc($grade_result)){
                    $data['grade'][] = $grade_data;
                }
            }else{
                $data['grade'] = array();
            }

            // council get
            $council_query = 'SELECT u.first_name,u.middle_name,u.last_name,ecm.id,u.id as user_id FROM `event_council_members` ecm LEFT JOIN users u ON ecm.council_member = u.id WHERE ecm.event_id = "'.$requestField['event_id'].'"';
            $council_result = mysqli_query($conn, $council_query);
            $council_count = mysqli_num_rows($council_result);
            if($council_count > 0){
                while($council_data = mysqli_fetch_assoc($council_result)){
                    $data['council'][] = $council_data;
                }
            }else{
                $data['council'] = array();
            }

            // Images get
            $images = array();
            $images_query = 'SELECT * FROM `event_images` WHERE event_id = "'.$requestField['event_id'].'"';
            $images_result = mysqli_query($conn, $images_query);
            $images_count = mysqli_num_rows($images_result);
            if($images_count > 0){
                while($images_data = mysqli_fetch_assoc($images_result)){
                    $images['id'] = $images_data['id'];
                    $images['name'] = BASE_URL.'upload/event_images/'.$images_data['image_url'];
                    $data['images'][] = $images;
                }
            }else{
                $data['images'] = array();
            }

            // Document get
            $document = array();
            $document_query = 'SELECT * FROM `event_documents` WHERE event_id = "'.$requestField['event_id'].'"';
            $document_result = mysqli_query($conn, $document_query);
            $document_count = mysqli_num_rows($document_result);
            if($document_count > 0){
                while($document_data = mysqli_fetch_assoc($document_result)){
                    $document['id'] = $document_data['id'];
                    $document['name'] = BASE_URL.'upload/event_document/'.$document_data['file_name'];
                    $data['document'][$document_data['type']][] = $document;
                    //$event_array['document']['type'][] = $document_data['type'];
                }
            }else{
                $event_array['document'] = array();
            }

            if(empty($data['document']['pdf_document'])){
                $data['document']['pdf_document'] = array();
            }
            if(empty($data['document']['waiver_from'])){
                $data['document']['waiver_from'] = array();
            }
            $sql = 'SELECT u.id as user_ID,h.name as house_name,h.id as house_id,ut.name as user_type_name FROM `users` u LEFT JOIN houses h ON u.house_id = h.id LEFT JOIN user_type ut ON ut.id = u.user_type_id WHERE u.id = "'.$requestField['user_id'].'"';
            $result = mysqli_query($conn, $sql);
            $user_data = mysqli_fetch_assoc($result);
            if($user_data['user_type_name'] == "Administrator" || $user_data['user_type_name'] == "Core Captain"){
                $house_list_query = 'SELECT h.name,COUNT(evt_snp.status) as count_status,evt_snp.status,h.id from houses h LEFT JOIN users usr on h.id = usr.house_id LEFT join event_signups evt_snp on usr.id = evt_snp.user_id AND evt_snp.event_id = "'.$requestField['event_id'].'" AND evt_snp.status != "2" GROUP BY h.name';
            }else{
                $house_list_query = 'SELECT h.name,COUNT(evt_snp.status) as count_status,evt_snp.status,h.id from houses h LEFT JOIN users usr on h.id = usr.house_id LEFT join event_signups evt_snp on usr.id = evt_snp.user_id AND evt_snp.event_id = "'.$requestField['event_id'].'" AND evt_snp.status != "2" WHERE h.id = "'.$user_data['house_id'].'" GROUP BY h.name';
            }
            $house_list_result = mysqli_query($conn, $house_list_query);
            while($house_list_array = mysqli_fetch_assoc($house_list_result)){
                $house_array['house_id'] = $house_list_array['id'];
                $house_array['house_name'] = $house_list_array['name'];
                $house_array['count'] = $house_list_array['count_status'];
                $data['house_list'][] = $house_array; 
            }

            // Singup Event List

            $singup_event_query = 'SELECT e.*,es.status as is_singup,es.reject_by,es.approved_by FROM `event_signups` es LEFT JOIN events e ON e.id = es.event_id WHERE user_id = "'.$requestField['user_id'].'" AND e.id = "'.$requestField['event_id'].'"';
            $singup_event_result = mysqli_query($conn, $singup_event_query);
            $singup_array = array();
            while($event_data = mysqli_fetch_assoc($singup_event_result)){
                    // get user role and name for approved
                    $role_approved_query = 'SELECT first_name as user_name,ut.name as user_type_name FROM `users` u LEFT JOIN user_type ut ON ut.id = u.user_type_id WHERE u.id = "'.$event_data['approved_by'].'"';
                    $role_approved_result = mysqli_query($conn, $role_approved_query);
                    $role_approved_data = mysqli_fetch_assoc($role_approved_result);
                    $role_approved_count = mysqli_num_rows($role_approved_result);
                    if($role_approved_count > 0){
                        $event_array['approved_by'] = (isset($event_data['approved_by']) && $event_data['approved_by'] != "") ? $event_data['approved_by'] : "";
                        $event_array['approved_by_name'] = (isset($role_approved_data['user_name']) && $role_approved_data['user_name'] != "") ? $role_approved_data['user_name'] : "";
                        $event_array['approved_by_role'] = (isset($role_approved_data['user_type_name']) && $role_approved_data['user_type_name'] != "") ? $role_approved_data['user_type_name'] : "";
                    }else{
                        $event_array['approved_by'] = "";
                        $event_array['approved_by_name'] = "";
                        $event_array['approved_by_role'] = "";
                    }
                    // get user role and name for reject
                    $role_reject_query = 'SELECT first_name as user_name,ut.name as user_type_name FROM `users` u LEFT JOIN user_type ut ON ut.id = u.user_type_id WHERE u.id = "'.$event_data['reject_by'].'"';
                    $role_reject_result = mysqli_query($conn, $role_reject_query);
                    $role_reject_data = mysqli_fetch_assoc($role_reject_result);
                    $role_reject_count = mysqli_num_rows($role_reject_result);
                    if($role_reject_count > 0){
                        $event_array['reject_by'] = (isset($event_data['reject_by']) && $event_data['reject_by'] != "") ? $event_data['reject_by'] : "";
                        $event_array['reject_by_name'] = (isset($role_reject_data['user_name']) && $role_reject_data['user_name'] != "") ? $role_reject_data['user_name'] : "";
                        $event_array['reject_by_role'] = (isset($role_reject_data['user_type_name']) && $role_reject_data['user_type_name'] != "") ? $role_reject_data['user_type_name'] : "";
                    }else{
                        $event_array['reject_by'] = "";
                        $event_array['reject_by_name'] = "";
                        $event_array['reject_by_role'] = "";
                    }

                    $singup_array[] = $event_array;
            }
            $data['singup_event_details'] = $singup_array;

            // gender status check
            // get age_gender 
            $get_event_query = 'SELECT agc.id,agc.title,agc.gender FROM `event_age_gender_categories` eac LEFT JOIN age_gender_categories agc ON eac.age_gender_id = agc.id WHERE event_id = "'.$requestField['event_id'].'"';
            $get_event_result = mysqli_query($conn, $get_event_query);
            $get_event_count = mysqli_num_rows($get_event_result);
            $get_event_data = mysqli_fetch_assoc($get_event_result);
            if($get_event_count > 0){
                $user_gender = $user_data_first['gender'];
                if($user_gender == $get_event_data['gender']){
                    $data['gender_check'] = "1";
                }else{
                    $data['gender_check'] = "0";
                }
            }else{
                $data['gender_check'] = "1";
            }



            $res['status'] = '1';
            $res['message'] = "Event Details.";
            $res['data'] = $data;
            echo json_encode($res);
            return false;

        }
        break;
    case "upload_waiver_form":
        $counter = 0;
        $errors = array();
        // Check Token code
        if(!isset($requestField['api_token'])){
            $counter++;
            $errors['api_token'] = "API Token field is required";
        }else{
            $check_token = check_api_token($requestField['api_token']);
            if($check_token == "0"){
                $res['status'] = "0";
                $res['message'] = "Token Not Match.";
                $res['data'] = new arrayObject();
                echo json_encode($res);
                return false;
            }else{
                $requestField['user_id'] = $check_token;
            }
        }
        if(!isset($requestField['user_id'])){
            $counter++;
            $errors['user_id'] = "User ID field is required";
        }
        if(!isset($requestField['event_id'])){
            $counter++;
            $errors['event_id'] = "Event ID field is required";
        }
        if($counter > 0 && count($errors) > 0){
            $res['status'] = '0';
            $res['message'] = "Total ".$counter." Found in Request";
            $res['data'] = $errors; 
            echo json_encode($res);
            return false;
        }
        $sql = 'SELECT * FROM `users`WHERE id = "'.$requestField['user_id'].'"';
        $result = mysqli_query($conn, $sql);
        //$user_data = mysqli_fetch_assoc($result);
        $count = mysqli_num_rows($result);
        if($count == 0){
            $res['status'] = '0';
            $res['message'] = "User is not exist";
            $res['data'] = new arrayObject();
            echo json_encode($res);
            return false;
        }else{

            if(isset($_FILES["file"]["name"]) && $_FILES["file"]["name"] != ''){
                $path = '../../upload/waiver_form/'.$requestField['user_id'];
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                    chmod($path, 0777);
                }
                $path_parts = pathinfo($_FILES["file"]["name"]);
                $fileName = time() . "." . $path_parts['extension'];
                move_uploaded_file($_FILES['file']['tmp_name'], "$path/$fileName");
                $add_query = 'INSERT INTO user_submitted_docs SET event_id= "'.$requestField['event_id'].'",user_id = "'.$requestField['user_id'].'",file_name="'.$fileName.'",created_date = "'.date('Y-m-d H:i:s').'"';
                $result = mysqli_query($conn, $add_query);
                $path = BASE_URL.'upload/waiver_form/'.$requestField['user_id'];
                $document = $path.'/'.$fileName;
                $data = array();
                $data['document_url'] = $document;
                $res['status'] = '1';
                $res['message'] = "Waiver Upload file Sucessfully.";
                $res['data'] = $data;
                echo json_encode($res);
                return false;
            }else{
                $res['status'] = '0';
                $res['message'] = "Waiver File Not Uploaded.";
                $res['data'] = new arrayObject();
                echo json_encode($res);
                return false;
            }
        }
        break;
    case "remove_waiver_form":
        $counter = 0;
        $errors = array();
        // Check Token code
        if(!isset($requestField['api_token'])){
            $counter++;
            $errors['api_token'] = "API Token field is required";
        }else{
            $check_token = check_api_token($requestField['api_token']);
            if($check_token == "0"){
                $res['status'] = "0";
                $res['message'] = "Token Not Match.";
                $res['data'] = new arrayObject();
                echo json_encode($res);
                return false;
            }else{
                $requestField['user_id'] = $check_token;
            }
        }
        if(!isset($requestField['user_id'])){
            $counter++;
            $errors['user_id'] = "User ID field is required";
        }
        if(!isset($requestField['event_id'])){
            $counter++;
            $errors['event_id'] = "Event ID field is required";
        }
        if($counter > 0 && count($errors) > 0){
            $res['status'] = '0';
            $res['message'] = "Total ".$counter." Found in Request";
            $res['data'] = $errors; 
            echo json_encode($res);
            return false;
        }
        $sql = 'SELECT * FROM `users`WHERE id = "'.$requestField['user_id'].'"';
        $result = mysqli_query($conn, $sql);
        //$user_data = mysqli_fetch_assoc($result);
        $count = mysqli_num_rows($result);
        if($count == 0){
            $res['status'] = '0';
            $res['message'] = "User is not exist";
            $res['data'] = new arrayObject();
            echo json_encode($res);
            return false;
        }else{
            $sql = 'SELECT * FROM `user_submitted_docs`WHERE id = "'.$requestField['user_id'].'"';
            $result = mysqli_query($conn, $sql);
            $user_submitted_docs = mysqli_fetch_assoc($result);

            $path = '../../upload/waiver_form/'.$requestField['user_id'].'/'.$user_submitted_docs['file_name'];
            unlink($path);
            $delete_query = 'DELETE FROM `user_submitted_docs` WHERE event_id = "'.$requestField['event_id'].'" AND user_id="'.$requestField['user_id'].'"';
            $result = mysqli_query($conn, $delete_query);
            if($result){
                $res['status'] = '1';
                $res['message'] = "Waiver Remove Sucussfully.";
                $res['data'] = new arrayObject();;
                echo json_encode($res);
                return false;
            }else{
                $res['status'] = '0';
                $res['message'] = "Waiver Remove Problem.";
                $res['data'] = new arrayObject();;
                echo json_encode($res);
                return false;
            }
        }
        break;
    case "get_category" : 
        $counter = 0;
        $errors = array();
        // Check Token code
        if(!isset($requestField['api_token'])){
            $counter++;
            $errors['api_token'] = "API Token field is required";
        }else{
            $check_token = check_api_token($requestField['api_token']);
            if($check_token == "0"){
                $res['status'] = "0";
                $res['message'] = "Token Not Match.";
                $res['data'] = new arrayObject();
                echo json_encode($res);
                return false;
            }else{
                $requestField['user_id'] = $check_token;
            }
        }
        if($counter > 0 && count($errors) > 0){
            $res['status'] = '0';
            $res['message'] = "Total ".$counter." Found in Request";
            $res['data'] = $errors; 
            echo json_encode($res);
            return false;
        }

        $get_category_query = 'SELECT * FROM `categories`WHERE category_id = "0" AND status = "1" ORDER BY `categories`.`name` ASC';
        $get_category_result = mysqli_query($conn, $get_category_query);
        $data = array();
        while($get_category = mysqli_fetch_assoc($get_category_result)){
            $category_data['id'] = (isset($get_category['id']) && $get_category['id'] != "") ? $get_category['id'] : "";
            $category_data['name'] = (isset($get_category['name']) && $get_category['name'] != "") ? $get_category['name'] : "";
            $data[] = $category_data;
        }
        $res['status'] = '1';
        $res['message'] = "Category List";
        $res['data'] = $data;
        echo json_encode($res);
        return false;

        break;
    case "get_sub_category" : 
        $counter = 0;
        $errors = array();
        // Check Token code
        if(!isset($requestField['api_token'])){
            $counter++;
            $errors['api_token'] = "API Token field is required";
        }else{
            $check_token = check_api_token($requestField['api_token']);
            if($check_token == "0"){
                $res['status'] = "0";
                $res['message'] = "Token Not Match.";
                $res['data'] = new arrayObject();
                echo json_encode($res);
                return false;
            }else{
                $requestField['user_id'] = $check_token;
            }
        }
        if(!isset($requestField['category_id'])){
            $counter++;
            $errors['category_id'] = "Category ID field is required";
        }
        if($counter > 0 && count($errors) > 0){
            $res['status'] = '0';
            $res['message'] = "Total ".$counter." Found in Request";
            $res['data'] = $errors; 
            echo json_encode($res);
            return false;
        }

        $get_category_query = 'SELECT * FROM `categories`WHERE category_id = "'.$requestField['category_id'].'" AND status = "1"';
        $get_category_result = mysqli_query($conn, $get_category_query);
        $data = array();
        while($get_category = mysqli_fetch_assoc($get_category_result)){
            $category_data['id'] = (isset($get_category['id']) && $get_category['id'] != "") ? $get_category['id'] : "";
            $category_data['name'] = (isset($get_category['name']) && $get_category['name'] != "") ? $get_category['name'] : "";
            $data[] = $category_data;
        }
        $res['status'] = '1';
        $res['message'] = "Sub Category List";
        $res['data'] = $data;
        echo json_encode($res);
        return false;
        break;
    case "logout" :
        $counter = 0;
        $errors = array();
        // Check Token code
        if(!isset($requestField['api_token'])){
            $counter++;
            $errors['api_token'] = "API Token field is required";
        }else{
            $check_token = check_api_token($requestField['api_token']);
            if($check_token == "0"){
                $res['status'] = "0";
                $res['message'] = "Token Not Match.";
                $res['data'] = new arrayObject();
                echo json_encode($res);
                return false;
            }else{
                $requestField['user_id'] = $check_token;
            }
        }
        if(!isset($requestField['user_id'])){
            $counter++;
            $errors['user_id'] = "User ID field is required";
        }
        // if(!isset($requestField['device_token'])){
        //     $counter++;
        //     $errors['device_token'] = "Device Token field is required";
        // }
        if($counter > 0 && count($errors) > 0){
            $res['status'] = '0';
            $res['message'] = "Total ".$counter." Found in Request";
            $res['data'] = $errors; 
            echo json_encode($res);
            return false;
        }
        $sql = 'SELECT * FROM `users`WHERE id = "'.$requestField['user_id'].'"';
        $result = mysqli_query($conn, $sql);
        //$user_data = mysqli_fetch_assoc($result);
        $count = mysqli_num_rows($result);
        if($count == 0){
            $res['status'] = '0';
            $res['message'] = "User is not exist";
            $res['data'] = new arrayObject();
            echo json_encode($res);
            return false;
        }else{
            $sql = 'DELETE FROM `device_token` WHERE user_id = "'.$requestField['user_id'].'" AND device_token = "'.$requestField['device_token'].'"';
            $result = mysqli_query($conn, $sql);
            $res['status'] = '1';
            $res['message'] = "Logout Sucessfully";
            $res['data'] = new arrayObject();
            echo json_encode($res);
            return false;

        }
        break;
    case "user_profile":
        $counter = 0;
        $errors = array();
        // Check Token code
        // if(!isset($requestField['api_token'])){
        //     $counter++;
        //     $errors['api_token'] = "API Token field is required";
        // }
        // }else{
        //     $check_token = check_api_token($requestField['api_token']);
        //     if($check_token == "0"){
        //         $res['status'] = "0";
        //         $res['message'] = "Token Not Match.";
        //         $res['data'] = new arrayObject();
        //         echo json_encode($res);
        //         return false;
        //     }else{
        //         $requestField['user_id'] = $check_token;
        //     }
        // }
        if(!isset($requestField['user_id'])){
            $counter++;
            $errors['user_id'] = "User ID field is required";
        }
        if($counter > 0 && count($errors) > 0){
            $res['status'] = '0';
            $res['message'] = "Total ".$counter." Found in Request";
            $res['data'] = $errors; 
            echo json_encode($res);
            return false;
        }
        $sql = 'SELECT * FROM `users`WHERE id = "'.$requestField['user_id'].'"';
        $result = mysqli_query($conn, $sql);
        //$user_data = mysqli_fetch_assoc($result);
        $count = mysqli_num_rows($result);
        if($count == 0){
            $res['status'] = '0';
            $res['message'] = "User is not exist";
            $res['data'] = new arrayObject();
            echo json_encode($res);
            return false;
        }else{
            $sql = 'SELECT * FROM `users`WHERE id = "'.$requestField['user_id'].'"';
            $result = mysqli_query($conn, $sql);
            $user_data = mysqli_fetch_assoc($result);

            $data['user_id'] = (isset($user_data['id']) && $user_data['id'] != "") ? $user_data['id'] : "";
            $data['first_name'] = (isset($user_data['first_name']) && $user_data['first_name'] != "") ? $user_data['first_name'] : "";
            $data['middle_name'] = (isset($user_data['middle_name']) && $user_data['middle_name'] != "") ? $user_data['middle_name'] : "";
            $data['last_name'] = (isset($user_data['last_name']) && $user_data['last_name'] != "") ? $user_data['last_name'] : "";
            $data['class_sr_no'] = (isset($user_data['class_sr_no']) && $user_data['class_sr_no'] != "") ? $user_data['class_sr_no'] : "";
            $data['class'] = (isset($user_data['class']) && $user_data['class'] != "") ? $user_data['class'] : "";
            $data['div'] = (isset($user_data['div']) && $user_data['div'] != "") ? $user_data['div'] : "";
            $data['gender'] = (isset($user_data['gender']) && $user_data['gender'] != "") ? $user_data['gender'] : "";
            $sql = 'SELECT * FROM `houses`WHERE id = "'.$user_data['house_id'].'"';
            $result = mysqli_query($conn, $sql);
            $house_data = mysqli_fetch_assoc($result);
            $data['house_name'] = (isset($house_data['name']) && $house_data['name'] != "") ? $house_data['name'] : "";
            $data['house_id'] = (isset($user_data['house_id']) && $user_data['house_id'] != "") ? $user_data['house_id'] : "";
            $data['user_type_id'] = (isset($user_data['user_type_id']) && $user_data['user_type_id'] != "") ? $user_data['user_type_id'] : "";
            // Get User Role
            $role_query = 'SELECT name FROM `user_type` WHERE id = "'.$user_data['user_type_id'].'"';
            $role_result = mysqli_query($conn, $role_query);
            $get_user_role = mysqli_fetch_assoc($role_result);
            $data['user_role'] = (isset($get_user_role['name']) && $get_user_role['name'] != "") ? $get_user_role['name'] : "";
            
            $data['dob'] = (isset($user_data['dob']) && $user_data['dob'] != "") ? $user_data['dob'] : "";
            $data['email_id'] = (isset($user_data['email_id']) && $user_data['email_id'] != "") ? $user_data['email_id'] : "";
            $data['password'] = (isset($user_data['password']) && $user_data['password'] != "") ? $user_data['password'] : "";
            $data['token'] = (isset($user_data['token']) && $user_data['token'] != "") ? $user_data['token'] : "";
            $data['phone'] = (isset($user_data['phone']) && $user_data['phone'] != "") ? $user_data['phone'] : "";
            $data['parent_phone'] = (isset($user_data['parent_phone']) && $user_data['parent_phone'] != "") ? $user_data['parent_phone'] : "";
            $data['other_phone'] = (isset($user_data['other_phone']) && $user_data['other_phone'] != "") ? $user_data['other_phone'] : "";
            $data['change_password_flag'] = (isset($user_data['change_password_flag']) && $user_data['change_password_flag'] != "") ? $user_data['change_password_flag'] : "";
            $data['api_access_token'] = (isset($user_data['api_access_token']) && $user_data['api_access_token'] != "") ? $user_data['api_access_token'] : "";
            $data['device_token'] = (isset($user_data['device_token']) && $user_data['device_token'] != "") ? $user_data['device_token'] : "";
            $data['device_type'] = (isset($user_data['device_type']) && $user_data['device_type'] != "") ? $user_data['device_type'] : "";
            $data['device_name'] = (isset($user_data['device_name']) && $user_data['device_name'] != "") ? $user_data['device_name'] : "";
            $data['last_login'] = (isset($user_data['last_login']) && $user_data['last_login'] != "") ? $user_data['last_login'] : "";
            $data['created_date'] = (isset($user_data['created_date']) && $user_data['created_date'] != "") ? $user_data['created_date'] : "";
            $data['modified_date'] = (isset($user_data['modified_date']) && $user_data['modified_date'] != "") ? $user_data['modified_date'] : "";
            $data['user_status'] = (isset($user_data['status']) && $user_data['status'] != "") ? $user_data['status'] : "";
            $data['image_name'] = (isset($user_data['image_name']) && $user_data['image_name'] != "") ? $user_data['image_name'] : "";
            $data['image_url'] = (isset($user_data['image_url']) && $user_data['image_url'] != "") ? BASE_URL.$user_data['image_url'] : "";

            // Notification count
            $notification_count_query = 'SELECT COUNT(*) as count FROM `notification` WHERE user_id = "'.$user_data['id'].'" AND is_read = "0"';
            $notification_count_result = mysqli_query($conn, $notification_count_query);
            $notification_count_data = mysqli_fetch_assoc($notification_count_result);
            $data['notification_count'] = (isset($notification_count_data['count']) && $notification_count_data['count'] != "") ? $notification_count_data['count'] : "";

            // Category get Code
            $get_category_query = 'SELECT uic.id as interested_id,c.id as category_id,c.name FROM `user_interested_categories` uic LEFT JOIN categories c ON c.id = uic.category_id WHERE uic.user_id = "'.$user_data['id'].'"ORDER BY `c`.`name` ASC';
            $get_category_result = mysqli_query($conn, $get_category_query);
            $category = array();
            while($get_category = mysqli_fetch_assoc($get_category_result)){
                $get_category_array['id'] = $get_category['category_id'];
                $get_category_array['interested_id'] = $get_category['interested_id'];
                $get_category_array['name'] = $get_category['name'];
                $category[] = $get_category_array;
            }
            $data['category'] = $category;

            // Singup Event List
            date_default_timezone_set('Asia/Kolkata');
            $current_date_time = date('Y-m-d H:i:s');
            $singup_event_query = 'SELECT e.*,es.status as is_singup,es.reject_by,es.approved_by FROM `event_signups` es LEFT JOIN events e ON e.id = es.event_id WHERE user_id = "'.$user_data['id'].'" AND event_date_time > "'.$current_date_time.'"';
            $singup_event_result = mysqli_query($conn, $singup_event_query);
            $singup_array = array();
            while($event_data = mysqli_fetch_assoc($singup_event_result)){
                if($event_data['reject_by'] != $user_data['id']){
                    $event_array['id'] = (isset($event_data['id']) && $event_data['id'] != "") ? $event_data['id'] : "";
                    $event_array['title'] = (isset($event_data['title']) && $event_data['title'] != "") ? $event_data['title'] : "";
                    $event_array['description'] = (isset($event_data['description']) && $event_data['description'] != "") ? $event_data['description'] : "";
                    $event_array['event_date'] = (isset($event_data['event_date']) && $event_data['event_date'] != "") ? $event_data['event_date'] : "";
                    $event_array['event_time'] = (isset($event_data['event_time']) && $event_data['event_time'] != "") ? $event_data['event_time'] : "";
                    $event_array['registration_end_date'] = (isset($event_data['registration_end_date']) && $event_data['registration_end_date'] != "") ? $event_data['registration_end_date'] : "";
                    $event_array['registration_end_time'] = (isset($event_data['registration_end_time']) && $event_data['registration_end_time'] != "") ? $event_data['registration_end_time'] : "";
                    $event_array['event_internal_notes'] = (isset($event_data['event_internal_notes']) && $event_data['event_internal_notes'] != "") ? $event_data['event_internal_notes'] : "";
                    $event_array['event_external_notes'] = (isset($event_data['event_external_notes']) && $event_data['event_external_notes'] != "") ? $event_data['event_external_notes'] : "";
                    $event_array['category_id'] = (isset($event_data['category_id']) && $event_data['category_id'] != "") ? $event_data['category_id'] : "";
                    $category_query = 'SELECT `name` FROM `categories` WHERE id = "'.$event_data['category_id'].'"';
                    $category_result = mysqli_query($conn, $category_query);
                    $category_data = mysqli_fetch_assoc($category_result);
                    $event_array['category_name'] = (isset($category_data['name']) && $category_data['name'] != "") ? $category_data['name'] : "";
                    $event_array['sub_category_id'] = (isset($event_data['sub_category_id']) && $event_data['sub_category_id'] != "") ? $event_data['sub_category_id'] : "";
                    $event_array['is_singup'] = (isset($event_data['is_singup']) && $event_data['is_singup'] != "") ? $event_data['is_singup'] : "";
                    // get user role and name for approved
                    $role_approved_query = 'SELECT first_name as user_name,ut.name as user_type_name FROM `users` u LEFT JOIN user_type ut ON ut.id = u.user_type_id WHERE u.id = "'.$event_data['approved_by'].'"';
                    $role_approved_result = mysqli_query($conn, $role_approved_query);
                    $role_approved_data = mysqli_fetch_assoc($role_approved_result);
                    $role_approved_count = mysqli_num_rows($role_approved_result);
                    if($role_approved_count > 0){
                        $event_array['approved_by'] = (isset($event_data['approved_by']) && $event_data['approved_by'] != "") ? $event_data['approved_by'] : "";
                        $event_array['approved_by_name'] = (isset($role_approved_data['user_name']) && $role_approved_data['user_name'] != "") ? $role_approved_data['user_name'] : "";
                        $event_array['approved_by_role'] = (isset($role_approved_data['user_type_name']) && $role_approved_data['user_type_name'] != "") ? $role_approved_data['user_type_name'] : "";
                    }else{
                        $event_array['approved_by'] = "";
                        $event_array['approved_by_name'] = "";
                        $event_array['approved_by_role'] = "";
                    }
                    // get user role and name for reject
                    $role_reject_query = 'SELECT first_name as user_name,ut.name as user_type_name FROM `users` u LEFT JOIN user_type ut ON ut.id = u.user_type_id WHERE u.id = "'.$event_data['reject_by'].'"';
                    $role_reject_result = mysqli_query($conn, $role_reject_query);
                    $role_reject_data = mysqli_fetch_assoc($role_reject_result);
                    $role_reject_count = mysqli_num_rows($role_reject_result);
                    if($role_reject_count > 0){
                        $event_array['reject_by'] = (isset($event_data['reject_by']) && $event_data['reject_by'] != "") ? $event_data['reject_by'] : "";
                        $event_array['reject_by_name'] = (isset($role_reject_data['user_name']) && $role_reject_data['user_name'] != "") ? $role_reject_data['user_name'] : "";
                        $event_array['reject_by_role'] = (isset($role_reject_data['user_type_name']) && $role_reject_data['user_type_name'] != "") ? $role_reject_data['user_type_name'] : "";
                    }else{
                        $event_array['reject_by'] = "";
                        $event_array['reject_by_name'] = "";
                        $event_array['reject_by_role'] = "";
                    }

                    $singup_array[] = $event_array;
                }
            }
            $data['singup_event_details'] = $singup_array;


            $res['status'] = '1';
            $res['message'] = "User Profile Details.";
            $res['data'] = $data;
            echo json_encode($res);
            return false;
        }
        break;
    case "update_profile":
        $counter = 0;
        $errors = array();
        // Check Token code
        if(!isset($requestField['api_token'])){
            $counter++;
            $errors['api_token'] = "API Token field is required";
        }else{
            $check_token = check_api_token($requestField['api_token']);
            if($check_token == "0"){
                $res['status'] = "0";
                $res['message'] = "Token Not Match.";
                $res['data'] = new arrayObject();
                echo json_encode($res);
                return false;
            }else{
                $requestField['user_id'] = $check_token;
            }
        }
        if(!isset($requestField['user_id'])){
            $counter++;
            $errors['user_id'] = "User ID field is required";
        }
        if(!isset($requestField['phone'])){
            $counter++;
            $errors['phone'] = "Phone field is required";
        }
        if(!isset($requestField['p_phone'])){
            $counter++;
            $errors['p_phone'] = "Phone field is required";
        }
        if($counter > 0 && count($errors) > 0){
            $res['status'] = '0';
            $res['message'] = "Total ".$counter." Found in Request";
            $res['data'] = $errors; 
            echo json_encode($res);
            return false;
        }
        $sql = 'SELECT * FROM `users`WHERE id = "'.$requestField['user_id'].'"';
        $result = mysqli_query($conn, $sql);
        //$user_data = mysqli_fetch_assoc($result);
        $count = mysqli_num_rows($result);
        if($count == 0){
            $res['status'] = '0';
            $res['message'] = "User is not exist";
            $res['data'] = new arrayObject();
            echo json_encode($res);
            return false;
        }else{
            $add_query = 'UPDATE users SET phone= "'.$requestField['phone'].'",parent_phone = "'.$requestField['p_phone'].'",modified_date = "'.date('Y-m-d H:i:s').'" WHERE id ="'.$requestField['user_id'].'" ';
            $result = mysqli_query($conn, $add_query);
            if($result){
                // if(isset($_FILES["profile_image"]["name"]) && $_FILES["profile_image"]["name"] != ''){
                //     $path = '../../upload/profile';
                //     if (!file_exists($path)) {
                //         mkdir($path, 0777, true);
                //         chmod($path, 0777);
                //     }
                //     $path_parts = pathinfo($_FILES["profile_image"]["name"]);
                //     $imageName = time() . "." . $path_parts['extension'];
                //     move_uploaded_file($_FILES['profile_image']['tmp_name'], "$path/$imageName");

                //     $image_full_url = 'upload/profile/'.$imageName;
                //     $add_query = 'UPDATE users SET image_name= "'.$imageName.'",image_url = "'.$image_full_url.'",modified_date = "'.date('Y-m-d H:i:s').'" WHERE id = "'.$requestField['user_id'].'"';
                //     $result = mysqli_query($conn, $add_query);
                // }
                $res['status'] = '1';
                $res['message'] = "Profile Update Sucessfully.";
                $res['data'] = new arrayObject();
                echo json_encode($res);
                return false;

            }else{
                $res['status'] = '0';
                $res['message'] = "Profile Add Problem.";
                $res['data'] = new arrayObject();
                echo json_encode($res);
                return false;
            }
        }
        break;
    case "council_members":
        $counter = 0;
        $errors = array();
        // Check Token code
        if(!isset($requestField['api_token'])){
            $counter++;
            $errors['api_token'] = "API Token field is required";
        }else{
            $check_token = check_api_token($requestField['api_token']);
            if($check_token == "0"){
                $res['status'] = "0";
                $res['message'] = "Token Not Match.";
                $res['data'] = new arrayObject();
                echo json_encode($res);
                return false;
            }else{
                $requestField['user_id'] = $check_token;
            }
        }
        if(!isset($requestField['user_id'])){
            $counter++;
            $errors['user_id'] = "User ID field is required";
        }
        if($counter > 0 && count($errors) > 0){
            $res['status'] = '0';
            $res['message'] = "Total ".$counter." Found in Request";
            $res['data'] = $errors; 
            echo json_encode($res);
            return false;
        }
        $sql = 'SELECT * FROM `users`WHERE id = "'.$requestField['user_id'].'"';
        $result = mysqli_query($conn, $sql);
        //$user_data = mysqli_fetch_assoc($result);
        $count = mysqli_num_rows($result);
        if($count == 0){
            $res['status'] = '0';
            $res['message'] = "User is not exist";
            $res['data'] = new arrayObject();
            echo json_encode($res);
            return false;
        }else{
            $data = array();
            $sql = 'SELECT u.id as user_ID,h.name as house_name,h.id as house_id,ut.name as user_type_name FROM `users` u LEFT JOIN houses h ON u.house_id = h.id LEFT JOIN user_type ut ON ut.id = u.user_type_id WHERE u.id = "'.$requestField['user_id'].'"';
            $result = mysqli_query($conn, $sql);
            $user_data = mysqli_fetch_assoc($result);
            // if($user_data['user_type_name'] == "Administrator" || $user_data['user_type_name'] == "Core Captain"){
            //     $house_query = 'SELECT u.id,u.first_name,u.middle_name,u.last_name,h.name as house_name FROM `users` u LEFT JOIN user_type ut ON u.user_type_id = ut.id LEFT JOIN houses h ON h.id = u.house_id WHERE ut.name = "House Captain"';
            // }else{
            //     $house_query = 'SELECT u.id,u.first_name,u.middle_name,u.last_name,h.name as house_name FROM `users` u LEFT JOIN user_type ut ON u.user_type_id = ut.id LEFT JOIN houses h ON h.id = u.house_id WHERE ut.name = "House Captain" AND h.id = "'.$user_data['house_id'].'"';
            // }
            $house_query = 'SELECT u.id,u.first_name,u.middle_name,u.last_name,h.name as house_name FROM `users` u LEFT JOIN user_type ut ON u.user_type_id = ut.id LEFT JOIN houses h ON h.id = u.house_id WHERE ut.name = "House Captain"';
            $result = mysqli_query($conn, $house_query);
            while($house_data = mysqli_fetch_assoc($result)){
                $house_array['id'] = $house_data['id'];
                $house_array['first_name'] = $house_data['first_name'];
                $house_array['middle_name'] = $house_data['middle_name'];
                $house_array['last_name'] = $house_data['last_name'];
                $house_array['house_name'] = $house_data['house_name'];
                $data[] = $house_array;
            }
            $res['status'] = '1';
            $res['message'] = "Get Council Members.";
            $res['data'] = $data;
            echo json_encode($res);
            return false;
        }
        break;
    case "age_gender_categories":
        $counter = 0;
        $errors = array();
        // Check Token code
        if(!isset($requestField['api_token'])){
            $counter++;
            $errors['api_token'] = "API Token field is required";
        }else{
            $check_token = check_api_token($requestField['api_token']);
            if($check_token == "0"){
                $res['status'] = "0";
                $res['message'] = "Token Not Match.";
                $res['data'] = new arrayObject();
                echo json_encode($res);
                return false;
            }else{
                $requestField['user_id'] = $check_token;
            }
        }
        if($counter > 0 && count($errors) > 0){
            $res['status'] = '0';
            $res['message'] = "Total ".$counter." Found in Request";
            $res['data'] = $errors; 
            echo json_encode($res);
            return false;
        }
        $data = array();
        $sql = 'SELECT * FROM `age_gender_categories` ORDER BY `age_gender_categories`.`title`  ASC';
        $result = mysqli_query($conn, $sql);
        while($age_gender = mysqli_fetch_assoc($result)){
            $data[] = $age_gender;
        }
        $res['status'] = '1';
        $res['message'] = "Age Gender Category List.";
        $res['data'] = $data;
        echo json_encode($res);
        return false;
        break;
    case "grades":
        $counter = 0;
        $errors = array();
        // Check Token code
        if(!isset($requestField['api_token'])){
            $counter++;
            $errors['api_token'] = "API Token field is required";
        }else{
            $check_token = check_api_token($requestField['api_token']);
            if($check_token == "0"){
                $res['status'] = "0";
                $res['message'] = "Token Not Match.";
                $res['data'] = new arrayObject();
                echo json_encode($res);
                return false;
            }else{
                $requestField['user_id'] = $check_token;
            }
        }
        if($counter > 0 && count($errors) > 0){
            $res['status'] = '0';
            $res['message'] = "Total ".$counter." Found in Request";
            $res['data'] = $errors; 
            echo json_encode($res);
            return false;
        }
        $data = array();
        $sql = 'SELECT * FROM `grades`';
        $result = mysqli_query($conn, $sql);
        while($age_gender = mysqli_fetch_assoc($result)){
            $data[] = $age_gender;
        }
        $res['status'] = '1';
        $res['message'] = "Grades List.";
        $res['data'] = $data;
        echo json_encode($res);
        return false;
        break;
    case "singup_event":
        $counter = 0;
        $errors = array();
        // Check Token code
        if(!isset($requestField['api_token'])){
            $counter++;
            $errors['api_token'] = "API Token field is required";
        }else{
            $check_token = check_api_token($requestField['api_token']);
            if($check_token == "0"){
                $res['status'] = "0";
                $res['message'] = "Token Not Match.";
                $res['data'] = new arrayObject();
                echo json_encode($res);
                return false;
            }else{
                $requestField['user_id'] = $check_token;
                $limit_event_check = limit_event_check($requestField['user_id'],$requestField['event_id']);
                if(!empty($limit_event_check)){
                    $res['status'] = "0";
                    $res['message'] = $limit_event_check;
                    $res['data'] = new arrayObject();
                    echo json_encode($res);
                    return false;
                }
            }
        }
        if(!isset($requestField['user_id'])){
            $counter++;
            $errors['user_id'] = "User ID field is required";
        }
        if(!isset($requestField['event_id'])){
            $counter++;
            $errors['event_id'] = "Event ID field is required";
        }
        if($counter > 0 && count($errors) > 0){
            $res['status'] = '0';
            $res['message'] = "Total ".$counter." Found in Request";
            $res['data'] = $errors; 
            echo json_encode($res);
            return false;
        }
        $sql = 'SELECT * FROM `users`WHERE id = "'.$requestField['user_id'].'"';
        $result = mysqli_query($conn, $sql);
        $user_data = mysqli_fetch_assoc($result);
        $count = mysqli_num_rows($result);
        if($count == 0){
            $res['status'] = '0';
            $res['message'] = "User is not exist";
            $res['data'] = new arrayObject();
            echo json_encode($res);
            return false;
        }else{
            $add_query = 'INSERT INTO event_signups SET event_id= "'.$requestField['event_id'].'",user_id = "'.$requestField['user_id'].'",status = "0",created_date = "'.date('Y-m-d H:i:s').'"';
            $result = mysqli_query($conn, $add_query);
            if($result){
                // Changes 
                // get event name
                $event_name_query = 'SELECT * FROM `events` WHERE id="'.$requestField['event_id'].'"';
                $event_name_result = mysqli_query($conn, $event_name_query);
                $event_name_data = mysqli_fetch_assoc($event_name_result);

                //$title3 ="Hello has requested to withdraw their name from ".$event_name_data['title'].". Click here to approve or deny this request.";
                $title3 = $user_data['first_name']." ".$user_data['last_name']." has requested to sign up for ".$event_name_data['title'].".";
                //$title3 = $user_data['first_name']." ".$user_data['last_name']." has requested to Sign up in ".$event_name_data['title'].".";
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
                            event_id= "'.$requestField['event_id'].'",
                            message = "'.$title3.'",
                            notification_type = "singup_notification",
                            created_by = "'.$requestField['login_user_id'].'",
                            created_date = "'.date('Y-m-d H:i:s').'"
                            ';
                    $notification_result3 = mysqli_query($conn, $notification_query3_user);
                }


                $successers = sendSingleNotification($user_notification, $message3);
                $res['status'] = '1';
                $res['message'] = "Event Singup Sucessfully.";
                $res['data'] = new arrayObject();
                echo json_encode($res);
                return false;
            }else{
                $res['status'] = '0';
                $res['message'] = "Event Singup Problem.";
                $res['data'] = new arrayObject();
                echo json_encode($res);
                return false;
            }
        }
        break;
    case "group_wise_list":
        $counter = 0;
        $errors = array();
        // Check Token code
        if(!isset($requestField['api_token'])){
            $counter++;
            $errors['api_token'] = "API Token field is required";
        }else{
            $check_token = check_api_token($requestField['api_token']);
            if($check_token == "0"){
                $res['status'] = "0";
                $res['message'] = "Token Not Match.";
                $res['data'] = new arrayObject();
                echo json_encode($res);
                return false;
            }else{
                $requestField['user_id'] = $check_token;
            }
        }
        if(!isset($requestField['user_id'])){
            $counter++;
            $errors['user_id'] = "User ID field is required";
        }
        if(!isset($requestField['event_id'])){
            $counter++;
            $errors['event_id'] = "Event ID field is required";
        }
        if(!isset($requestField['house_id'])){
            $counter++;
            $errors['event_id'] = "House ID field is required";
        }
        if($counter > 0 && count($errors) > 0){
            $res['status'] = '0';
            $res['message'] = "Total ".$counter." Found in Request";
            $res['data'] = $errors; 
            echo json_encode($res);
            return false;
        }
        $sql = 'SELECT * FROM `users`WHERE id = "'.$requestField['user_id'].'"';
        $result = mysqli_query($conn, $sql);
        //$user_data = mysqli_fetch_assoc($result);
        $count = mysqli_num_rows($result);
        if($count == 0){
            $res['status'] = '0';
            $res['message'] = "User is not exist";
            $res['data'] = new arrayObject();
            echo json_encode($res);
            return false;
        }else{
            $data = array();
            $final_array = array();
            $house_query = 'SELECT u.first_name,u.last_name,u.id as user_id,es.status,es.event_id,h.name as hosue_name,u.phone,e.title,es.reject_commit FROM `event_signups` es LEFT JOIN users u ON es.user_id = u.id LEFT JOIN houses h ON u.house_id = h.id LEFT JOIN events e ON es.event_id = e.id WHERE event_id = "'.$requestField['event_id'].'" AND h.id = "'.$requestField['house_id'].'"';
            $house_result = mysqli_query($conn, $house_query);
            while($house_list = mysqli_fetch_assoc($house_result)){
                if($house_list['status'] == "0"){
                    $status_name = "Pending";
                }
                if($house_list['status'] == "1"){
                    $status_name = "Approved";
                }
                // if($house_list['status'] == "0" || $house_list['status'] == "1"){
                //     $status_name = "Pending";
                // }
                if($house_list['status'] == "2"){
                    $status_name = "Rejected";
                }
                $data['name'] = $house_list['hosue_name'];
                $data[$status_name][] = $house_list;
            }

            $Pending_count = count($data['Pending']);
            $Approved_count = count($data['Approved']);
            $Rejected_count = count($data['Rejected']);
            if(empty($data['name'])){
                $house_query = 'SELECT * FROM `houses`WHERE id = "'.$requestField['house_id'].'"';
                $house_result = mysqli_query($conn, $house_query);
                $house_data = mysqli_fetch_assoc($house_result);
                $data['name'] = $house_data['name'];
            }
            if($Pending_count == "0"){
                $data['Pending'] = array();
            }
            if($Approved_count == "0"){
                $data['Approved'] = array();
            }
            if($Rejected_count == "0"){
                $data['Rejected'] = array();
            }
            
            $final_array[] = $data;
            $res['status'] = '1';
            $res['message'] = "List Get.";
            $res['data'] = $final_array;
            echo json_encode($res);
            return false;
            
        }
        break;
    case "reject_event":
        $counter = 0;
        $errors = array();
        // Check Token code
        if(!isset($requestField['api_token'])){
            $counter++;
            $errors['api_token'] = "API Token field is required";
        }else{
            $check_token = check_api_token($requestField['api_token']);
            if($check_token == "0"){
                $res['status'] = "0";
                $res['message'] = "Token Not Match.";
                $res['data'] = new arrayObject();
                echo json_encode($res);
                return false;
            }else{
                $requestField['login_user_id'] = $check_token;
            }
        }
        if(!isset($requestField['user_id'])){
            $counter++;
            $errors['user_id'] = "User ID field is required";
        }
        if(!isset($requestField['event_id'])){
            $counter++;
            $errors['event_id'] = "Event ID field is required";
        }
        
        if($counter > 0 && count($errors) > 0){
            $res['status'] = '0';
            $res['message'] = "Total ".$counter." Found in Request";
            $res['data'] = $errors; 
            echo json_encode($res);
            return false;
        }
        $sql = 'SELECT * FROM `users`WHERE id = "'.$requestField['user_id'].'"';
        $result = mysqli_query($conn, $sql);
        $user_data = mysqli_fetch_assoc($result);
        $count = mysqli_num_rows($result);
        if($count == 0){
            $res['status'] = '0';
            $res['message'] = "User is not exist";
            $res['data'] = new arrayObject();
            echo json_encode($res);
            return false;
        }else{
            $update_query = 'UPDATE event_signups SET 
            status= "2",
            reject_by = "'.$requestField['login_user_id'].'",
            reject_commit = "'.$requestField['reject_comment'].'",
            approved_by = "",
            modified_date = "'.date('Y-m-d H:i:s').'"
            WHERE event_id = "'.$requestField['event_id'].'" AND user_id = "'.$requestField['user_id'].'"
            ';
            $result = mysqli_query($conn, $update_query);
            if($result){
                $res['status'] = '1';
                
                if($requestField['login_user_id'] == $requestField['user_id'] ){
                    // Changes 
                    // get event name
                    $event_name_query = 'SELECT * FROM `events` WHERE id="'.$requestField['event_id'].'"';
                    $event_name_result = mysqli_query($conn, $event_name_query);
                    $event_name_data = mysqli_fetch_assoc($event_name_result);

                    $title3 = $user_data['first_name']." has requested to withdraw their name from ".$event_name_data['title'].". Click here to approve or deny this request.";
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
                    // get all admin and core captain
                    // token get array 
                    $user_notification = array();
                    // $get_all_admin_query = 'SELECT dt.device_token,u.id FROM `users` u LEFT JOIN device_token dt ON dt.user_id = u.id WHERE user_type_id IN (1,2) GROUP BY dt.id';
                    // $get_all_admin_result = mysqli_query($conn, $get_all_admin_query);
                    // while($get_all_admin_data = mysqli_fetch_assoc($get_all_admin_result)){
                    //     $user_notification[] = $get_all_admin_data['device_token'];
                    // }
                    // //add user array 
                    // $get_all_admin_user_query = 'SELECT dt.device_token,u.id FROM `users` u LEFT JOIN device_token dt ON dt.user_id = u.id WHERE user_type_id IN (1,2) GROUP BY u.id';
                    // $get_all_admin_user_result = mysqli_query($conn, $get_all_admin_user_query);
                    // while($get_all_admin_user_data = mysqli_fetch_assoc($get_all_admin_user_result)){
                    //     $notification_query3_user = 'INSERT INTO notification SET 
                    //             user_id= "'.$get_all_admin_user_data['id'].'",
                    //             event_id= "'.$requestField['event_id'].'",
                    //             message = "'.$title3.'",
                    //             notification_type = "reject_notification",
                    //             created_by = "'.$requestField['login_user_id'].'",
                    //             created_date = "'.date('Y-m-d H:i:s').'"
                    //             ';
                    //     $notification_result3 = mysqli_query($conn, $notification_query3_user);
                    // }

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
                                event_id= "'.$requestField['event_id'].'",
                                message = "'.$title3.'",
                                notification_type = "reject_notification",
                                created_by = "'.$requestField['login_user_id'].'",
                                created_date = "'.date('Y-m-d H:i:s').'"
                                ';
                        $notification_result3 = mysqli_query($conn, $notification_query3_user);
                    }


                    $successers = sendSingleNotification($user_notification, $message3);
                    $res['message'] = "You have requested to withdraw your name from this event.";
                }else{
                    // Changes 
                    // get event name
                    
                    $event_name_query = 'SELECT * FROM `events` WHERE id="'.$requestField['event_id'].'"';
                    $event_name_result = mysqli_query($conn, $event_name_query);
                    $event_name_data = mysqli_fetch_assoc($event_name_result);

                    $title3 = "Thank you for your interest in ".$event_name_data['title'].", but unfortunately you have not been selected to participate in this event. We hope to see your participation in future events as well.";
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
                    // user send notification
                    // get token array
                    $user_notification = array();
                    $house_notification_query = 'SELECT dt.device_token,u.id FROM `users` u LEFT JOIN device_token dt ON dt.user_id = u.id WHERE u.id="'.$requestField['user_id'].'" GROUP BY dt.id';
                    $house_notification_result = mysqli_query($conn, $house_notification_query);
                    while($house_notification_data = mysqli_fetch_assoc($house_notification_result)){
                        $user_notification[] = $house_notification_data['device_token'];
                    }

                    // user array
                    $house_notification_user_query = 'SELECT dt.device_token,u.id FROM `users` u LEFT JOIN device_token dt ON dt.user_id = u.id WHERE u.id="'.$requestField['user_id'].'" GROUP BY u.id';
                    $house_notification_user_result = mysqli_query($conn, $house_notification_user_query);
                    while($house_notification_user_data = mysqli_fetch_assoc($house_notification_user_result)){
                        $notification_query3_user = 'INSERT INTO notification SET 
                                user_id= "'.$house_notification_user_data['id'].'",
                                event_id= "'.$requestField['event_id'].'",
                                message = "'.$title3.'",
                                notification_type = "approv_notification",
                                created_by = "'.$requestField['login_user_id'].'",
                                created_date = "'.date('Y-m-d H:i:s').'"
                                ';
                        $notification_result3 = mysqli_query($conn, $notification_query3_user);
                    }


                    $successers = sendSingleNotification($user_notification, $message3);
                    $res['message'] = "Event request has been declined successfully.";
                }
                //get event details
                $event_query = 'SELECT * FROM `event_signups` WHERE user_id = "'.$requestField['login_user_id'].'" AND event_id = "'.$requestField['event_id'].'"';
                $event_result = mysqli_query($conn, $event_query);
                $event_data = mysqli_fetch_assoc($event_result);
                
                $QUERY = 'SELECT first_name as user_name,ut.name as user_type_name FROM `users` u LEFT JOIN user_type ut ON ut.id = u.user_type_id WHERE u.id = "'.$event_data['reject_by'].'"';   
                //print_r($QUERY);exit;            
                // get user role and name for reject
                //$role_reject_query = 'SELECT first_name as user_name,ut.name as user_type_name FROM `users` u LEFT JOIN user_type ut ON ut.id = u.user_type_id WHERE u.id = "'.$event_data['reject_by'].'"';
                //priint_r($role_reject_query);exit;
                $role_reject_result = mysqli_query($conn, $QUERY);
                $role_reject_data = mysqli_fetch_assoc($role_reject_result);
                $role_reject_count = mysqli_num_rows($role_reject_result);
                if($role_reject_count > 0){
                    $event_array1['reject_by'] = (isset($event_data['reject_by']) && $event_data['reject_by'] != "") ? $event_data['reject_by'] : "";
                    $event_array1['reject_by_name'] = (isset($role_reject_data['user_name']) && $role_reject_data['user_name'] != "") ? $role_reject_data['user_name'] : "";
                    $event_array1['reject_by_role'] = (isset($role_reject_data['user_type_name']) && $role_reject_data['user_type_name'] != "") ? $role_reject_data['user_type_name'] : "";
                }else{
                    $event_array1['reject_by'] = "";
                    $event_array1['reject_by_name'] = "";
                    $event_array1['reject_by_role'] = "";
                }
                $data['singup_event_details'] = $event_array1;
                $singup_array[] = $event_array;
                $res['data'] = $data;
                echo json_encode($res);
                return false;
            }else{
                $res['status'] = '0';
                $res['message'] = "User has been declined Problem.";
                $res['data'] = new arrayObject();
                echo json_encode($res);
                return false;
            }
        }
        break;
    case "approv_event":
        $counter = 0;
        $errors = array();
        // Check Token code
        if(!isset($requestField['api_token'])){
            $counter++;
            $errors['api_token'] = "API Token field is required";
        }else{
            $check_token = check_api_token($requestField['api_token']);
            if($check_token == "0"){
                $res['status'] = "0";
                $res['message'] = "Token Not Match.";
                $res['data'] = new arrayObject();
                echo json_encode($res);
                return false;
            }else{
                $requestField['login_user_id'] = $check_token;
            }
        }
        if(!isset($requestField['user_id'])){
            $counter++;
            $errors['user_id'] = "User ID field is required";
        }
        if(!isset($requestField['event_id'])){
            $counter++;
            $errors['event_id'] = "Event ID field is required";
        }
        if($counter > 0 && count($errors) > 0){
            $res['status'] = '0';
            $res['message'] = "Total ".$counter." Found in Request";
            $res['data'] = $errors; 
            echo json_encode($res);
            return false;
        }
        $sql = 'SELECT * FROM `users`WHERE id = "'.$requestField['user_id'].'"';
        $result = mysqli_query($conn, $sql);
        //$user_data = mysqli_fetch_assoc($result);
        $count = mysqli_num_rows($result);
        if($count == 0){
            $res['status'] = '0';
            $res['message'] = "User is not exist";
            $res['data'] = new arrayObject();
            echo json_encode($res);
            return false;
        }else{
            $update_query = 'UPDATE event_signups SET 
            status= "1",
            approved_by = "'.$requestField['login_user_id'].'",
            reject_by = "",
            modified_date = "'.date('Y-m-d H:i:s').'"
            WHERE event_id = "'.$requestField['event_id'].'" AND user_id = "'.$requestField['user_id'].'"
            ';
            $result = mysqli_query($conn, $update_query);
            if($result){
                // Changes 
                // get event name
                $event_name_query = 'SELECT * FROM `events` WHERE id="'.$requestField['event_id'].'"';
                $event_name_result = mysqli_query($conn, $event_name_query);
                $event_name_data = mysqli_fetch_assoc($event_name_result);

                $title3 = "Congratulations! You have been selected to participate in ".$event_name_data['title'].".";
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
                
                // user send notification
                // get token array
                $user_notification = array();
                $house_notification_query = 'SELECT dt.device_token,u.id FROM `users` u LEFT JOIN device_token dt ON dt.user_id = u.id WHERE u.id="'.$requestField['user_id'].'" GROUP BY dt.id';
                $house_notification_result = mysqli_query($conn, $house_notification_query);
                while($house_notification_data = mysqli_fetch_assoc($house_notification_result)){
                    $user_notification[] = $house_notification_data['device_token'];
                }

                // user array
                $house_notification_user_query = 'SELECT dt.device_token,u.id FROM `users` u LEFT JOIN device_token dt ON dt.user_id = u.id WHERE u.id="'.$requestField['user_id'].'" GROUP BY u.id';
                $house_notification_user_result = mysqli_query($conn, $house_notification_user_query);
                while($house_notification_user_data = mysqli_fetch_assoc($house_notification_user_result)){
                    $notification_query3_user = 'INSERT INTO notification SET 
                            user_id= "'.$house_notification_user_data['id'].'",
                            event_id= "'.$requestField['event_id'].'",
                            message = "'.$title3.'",
                            notification_type = "approv_notification",
                            created_by = "'.$requestField['login_user_id'].'",
                            created_date = "'.date('Y-m-d H:i:s').'"
                            ';
                    $notification_result3 = mysqli_query($conn, $notification_query3_user);
                }


                $successers = sendSingleNotification($user_notification, $message3);
                $res['status'] = '1';
                $res['message'] = "User has been approved successfully";
                $res['data'] = new arrayObject();
                echo json_encode($res);
                return false;
            }else{
                $res['status'] = '0';
                $res['message'] = "User has been approved Problem.";
                $res['data'] = new arrayObject();
                echo json_encode($res);
                return false;
            }
        }
        break;
    case "notification" :
        // $check_event = check_event("27","5");
        // print_r($check_event);exit;
        $message['icon'] = 'http://finitesting.com/dais/upload/profile/1610779305.jpeg';
        $message['icon'] = '';

        $message['title'] = 'DAIS Events';
        $message['alert'] = '';

        $message['vibrate'] = "default";
        $message['body'] = "i am working";

        $message['sound'] = 'default';
        $message['android_channel_id'] = '1';
        //                                    $message['largeIcon'] = base_url() . 'assets/images/logos/LiveKeepingLogo.png';
        $message['largeIcon'] = '';
        $message['smallIcon'] = 'http://finitesting.com/dais/upload/profile/1610779305.jpeg';
        //                                    $message['smallIcon'] = '';

        $message['image'] = '';
        $message['priority'] = 'high';
        $message['badge'] = '1';
        $message['colour'] = '#FF0000';
        $message['channel'] = '';

        //                                    $message['backgroundImage'] = base_url() . 'assets/images/logos/LiveKeepingLogo.png';
        $message['backgroundImage'] = '';
        $message['backgroundImageTextColour'] = '#FF0000';
        $message['style']['type'] = '';
        $message['style']['text'] = '';
        $message['style']['lines'] = array(
            'Hii',
            'How r u?',
            'Bye'
        );
        $message['style']['image'] = '';
        //                                    $message['groupIcon'] = base_url() . 'assets/images/logos/LiveKeepingLogo.png';
        $message['groupIcon'] = '';
        $message['groupKey'] = '';
        $message['groupTitle'] = '';
        $message['groupSummary'] = '';
        //                                    $message['category'] = 'INVITE_CATEGORY';
        $message['category'] = 'CustomSamplePush';
        $message['action'] = 'dashboard_activity';
        $successers = sendSingleNotification1('ehcbwUISnUQYqwiwniorVF:APA91bFOu3UDP_C1ZVel5y3BW-2tF3C_dkUIEVVOo--r9ShJypSiK_vtHWcJA3O-czv0T_dmYOrN6_ehqeqexXEuBh9waEZ9cCXZVbzhqUVo6UYO8T_Dh_xT9CsQC7L4gRuBkg-0FnNQ', $message);
        echo "<pre>";
        print_r($successers);exit;
        break;
    case "event_update_admin":
        $counter = 0;
        $errors = array();
        // Check Token code
        if(!isset($requestField['api_token'])){
            $counter++;
            $errors['api_token'] = "API Token field is required";
        }else{
            $check_token = check_api_token($requestField['api_token']);
            if($check_token == "0"){
                $res['status'] = "0";
                $res['message'] = "Token Not Match.";
                $res['data'] = new arrayObject();
                echo json_encode($res);
                return false;
            }else{
                $requestField['user_id'] = $check_token;
            }
        }
        if(!isset($requestField['user_id'])){
            $counter++;
            $errors['user_id'] = "User ID field is required";
        }
        if(!isset($requestField['event_id'])){
            $counter++;
            $errors['event_id'] = "Event ID field is required";
        }
        if(!isset($requestField['event_internal_notes'])){
            $counter++;
            $errors['event_internal_notes'] = "Event Internal Notes End Date field is required";
        }
        if(!isset($requestField['event_external_notes'])){
            $counter++;
            $errors['event_external_notes'] = "Event Notes End Date field is required";
        }
        // if(!isset($requestField['council_ids'])){
        //     $counter++;
        //     $errors['council_ids'] = "Council ids field is required";
        // }
        if($counter > 0 && count($errors) > 0){
            $res['status'] = '0';
            $res['message'] = "Total ".$counter." Found in Request";
            $res['data'] = $errors; 
            echo json_encode($res);
            return false;
        }
        $sql = 'SELECT * FROM `users`WHERE id = "'.$requestField['user_id'].'"';
        $result = mysqli_query($conn, $sql);
        //$user_data = mysqli_fetch_assoc($result);
        $count = mysqli_num_rows($result);
        if($count == 0){
            $res['status'] = '0';
            $res['message'] = "User is not exist";
            $res['data'] = new arrayObject();
            echo json_encode($res);
            return false;
        }else{
            $update_query = 'UPDATE events SET 
            event_internal_notes = "'.$requestField['event_internal_notes'].'",
            event_external_notes = "'.$requestField['event_external_notes'].'",
            last_update_by = "'.$requestField['user_id'].'",
            modified_date = "'.date('Y-m-d H:i:s').'"
            WHERE id = "'.$requestField['event_id'].'"
            ';
            $result = mysqli_query($conn, $update_query);
            if($result){
                // Add Council Members
                $delete_council = 'DELETE FROM `event_council_members` WHERE event_id ="'.$requestField['event_id'].'"';
                $delete_council_result = mysqli_query($conn, $delete_council);
                $council = $requestField['council_ids'];
                $council_array = explode(",",$council);
                foreach($council_array as $key=>$value){
                    $add_query = 'INSERT INTO event_council_members SET event_id= "'.$requestField['event_id'].'",council_member = "'.$value.'"';
                    $result = mysqli_query($conn, $add_query);
                }

                $res['status'] = '1';
                $res['message'] = "Event Update Successfully";
                $res['data'] = new arrayObject();
                echo json_encode($res);
                return false;
            }else{
                $res['status'] = '1';
                $res['message'] = "Event Update Problem";
                $res['data'] = new arrayObject();
                echo json_encode($res);
                return false;
            }
        }
        break;
    case "signups_data":
        $counter = 0;
        $errors = array();
        // Check Token code
        if(!isset($requestField['api_token'])){
            $counter++;
            $errors['api_token'] = "API Token field is required";
        }else{
            $check_token = check_api_token($requestField['api_token']);
            if($check_token == "0"){
                $res['status'] = "0";
                $res['message'] = "Token Not Match.";
                $res['data'] = new arrayObject();
                echo json_encode($res);
                return false;
            }else{
                $requestField['user_id'] = $check_token;
            }
        }
        if(!isset($requestField['user_id'])){
            $counter++;
            $errors['user_id'] = "User ID field is required";
        }
        if(!isset($requestField['event_id'])){
            $counter++;
            $errors['event_id'] = "Event ID field is required";
        }
        
        if($counter > 0 && count($errors) > 0){
            $res['status'] = '0';
            $res['message'] = "Total ".$counter." Found in Request";
            $res['data'] = $errors; 
            echo json_encode($res);
            return false;
        }
        $sql = 'SELECT * FROM `users`WHERE id = "'.$requestField['user_id'].'"';
        $result = mysqli_query($conn, $sql);
        //$user_data = mysqli_fetch_assoc($result);
        $count = mysqli_num_rows($result);
        if($count == 0){
            $res['status'] = '0';
            $res['message'] = "User is not exist";
            $res['data'] = new arrayObject();
            echo json_encode($res);
            return false;
        }else{
            $sql = 'SELECT u.id as user_ID,h.name as house_name,h.id as house_id,ut.name as user_type_name FROM `users` u LEFT JOIN houses h ON u.house_id = h.id LEFT JOIN user_type ut ON ut.id = u.user_type_id WHERE u.id = "'.$requestField['user_id'].'"';
            $result = mysqli_query($conn, $sql);
            $user_data = mysqli_fetch_assoc($result);
            if($user_data['user_type_name'] == "Administrator" || $user_data['user_type_name'] == "Core Captain"){
                $sql = 'SELECT u.*,h.name as house_name,ut.name as user_type_name FROM `event_signups` es 
                LEFT JOIN users u ON es.user_id = u.id
                LEFT JOIN houses h ON u.house_id = h.id
                LEFT JOIN user_type ut ON ut.id = u.user_type_id
                WHERE es.event_id = "'.$requestField['event_id'].'" AND es.status = "1"';
            }else{
                $sql = 'SELECT u.*,h.name as house_name,ut.name as user_type_name FROM `event_signups` es 
                LEFT JOIN users u ON es.user_id = u.id
                LEFT JOIN houses h ON u.house_id = h.id
                LEFT JOIN user_type ut ON ut.id = u.user_type_id
                WHERE es.event_id = "'.$requestField['event_id'].'" AND h.id = "'.$user_data['house_id'].'" AND es.status = "1"';
            }
            $result = mysqli_query($conn, $sql);
            
    
            $csv = "Sr. No.,Class Sr. No.,Class(20-21),Div,Last Name,First Name,Middle Name,Gender,Teams ID,Date of Birth,Phone Number,House,Access Level,Parent Phone Number\n";//Column headers
            $i = 1;
            
            while($user_data = mysqli_fetch_assoc($result)){
                $csv.= $i.','.$user_data['class_sr_no'].','.$user_data['class'].','.$user_data['div'].','.$user_data['last_name'].','.$user_data['first_name'].','.$user_data['middle_name'].','.$user_data['gender'].','.$user_data['email_id'].','.date("d/m/Y", strtotime($user_data['dob'])).','.$user_data['phone'].','.$user_data['house_name'].','.$user_data['user_type_name'].','.$user_data['parent_phone']."\n"; //Append data to csv
                $i++;
            }
            $path = '../../upload/signups_data/';
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
                chmod($path, 0777);
            }
            $event_sql = 'SELECT * FROM `events`WHERE id = "'.$requestField['event_id'].'"';
            $event_result = mysqli_query($conn, $event_sql);
            $event_data = mysqli_fetch_assoc($event_result);
            $image_url = strtolower($event_data['title']." ".date('d M Y', strtotime($event_data['event_date'])));
            $image_path = str_replace(" ","_",$image_url);
            $image = $image_path.".csv";
            $url = $path.$image;
            $csv_handler = fopen ($url,'w');
            fwrite ($csv_handler,$csv);
            fclose ($csv_handler);
            $data['url'] = BASE_URL.'upload/signups_data/'.$image;
            

            $res['status'] = '1';
            $res['message'] = "Get User Data";
            $res['data'] = $data;
            echo json_encode($res);
            return false;
        }
        break;
    case "get_notification":
        $counter = 0;
        $errors = array();
        // Check Token code
        if(!isset($requestField['api_token'])){
            $counter++;
            $errors['api_token'] = "API Token field is required";
        }else{
            $check_token = check_api_token($requestField['api_token']);
            if($check_token == "0"){
                $res['status'] = "0";
                $res['message'] = "Token Not Match.";
                $res['data'] = new arrayObject();
                echo json_encode($res);
                return false;
            }else{
                $requestField['user_id'] = $check_token;
            }
        }
        if(!isset($requestField['user_id'])){
            $counter++;
            $errors['user_id'] = "User ID field is required";
        }
        
        if($counter > 0 && count($errors) > 0){
            $res['status'] = '0';
            $res['message'] = "Total ".$counter." Found in Request";
            $res['data'] = $errors; 
            echo json_encode($res);
            return false;
        }
        $sql = 'SELECT * FROM `users`WHERE id = "'.$requestField['user_id'].'"';
        $result = mysqli_query($conn, $sql);
        //$user_data = mysqli_fetch_assoc($result);
        $count = mysqli_num_rows($result);
        if($count == 0){
            $res['status'] = '0';
            $res['message'] = "User is not exist";
            $res['data'] = new arrayObject();
            echo json_encode($res);
            return false;
        }else{
            $notification_sql = 'SELECT n.*,es.status as is_signup,e.title,e.event_date,e.event_time,e.event_date_time FROM `notification` n
            LEFT JOIN event_signups es ON es.event_id = n.event_id AND es.user_id = "'.$requestField['user_id'].'" LEFT JOIN events e ON n.event_id = e.id WHERE n.user_id = "'.$requestField['user_id'].'" GROUP BY n.id ORDER BY id DESC';
          
            
            $notification_result = mysqli_query($conn, $notification_sql);
            $notification_count = mysqli_num_rows($notification_result);
            if($notification_count > 0){
                while($notification_data = mysqli_fetch_assoc($notification_result)){
                    $notification_data['is_signup'] = (isset($notification_data['is_signup']) && $notification_data['is_signup'] != "") ? $notification_data['is_signup'] : "";
                    $data[] = $notification_data;
                }
            }else{
                $data = array();
            }

            $res['status'] = '1';
            $res['message'] = "Get Notification Data";
            $res['data'] = $data;
            echo json_encode($res);
        }
        break;
    case "notification_read":
        $counter = 0;
        $errors = array();
        // Check Token code
        if(!isset($requestField['api_token'])){
            $counter++;
            $errors['api_token'] = "API Token field is required";
        }else{
            $check_token = check_api_token($requestField['api_token']);
            if($check_token == "0"){
                $res['status'] = "0";
                $res['message'] = "Token Not Match.";
                $res['data'] = new arrayObject();
                echo json_encode($res);
                return false;
            }else{
                $requestField['user_id'] = $check_token;
            }
        }
        if(!isset($requestField['user_id'])){
            $counter++;
            $errors['user_id'] = "User ID field is required";
        }
        if(!isset($requestField['notification_id'])){
            $counter++;
            $errors['notification_id'] = "Notification ID field is required";
        }
        
        if($counter > 0 && count($errors) > 0){
            $res['status'] = '0';
            $res['message'] = "Total ".$counter." Found in Request";
            $res['data'] = $errors; 
            echo json_encode($res);
            return false;
        }
        $sql = 'SELECT * FROM `users`WHERE id = "'.$requestField['user_id'].'"';
        $result = mysqli_query($conn, $sql);
        //$user_data = mysqli_fetch_assoc($result);
        $count = mysqli_num_rows($result);
        if($count == 0){
            $res['status'] = '0';
            $res['message'] = "User is not exist";
            $res['data'] = new arrayObject();
            echo json_encode($res);
            return false;
        }else{
            $update_query = 'UPDATE notification SET 
            is_read= "1"
            WHERE id = "'.$requestField['notification_id'].'" AND user_id = "'.$requestField['user_id'].'"
            ';
            $result = mysqli_query($conn, $update_query);
            if($result){
                $res['status'] = '1';
                $res['message'] = "Notification read";
                $res['data'] = new arrayObject();
                echo json_encode($res);
            }else{
                $res['status'] = '0';
                $res['message'] = "Notification read problem";
                $res['data'] = new arrayObject();
                echo json_encode($res);
            }
        }
        break;
    case "profile_image":
        $counter = 0;
        $errors = array();
        // Check Token code
        if(!isset($requestField['api_token'])){
            $counter++;
            $errors['api_token'] = "API Token field is required";
        }else{
            $check_token = check_api_token($requestField['api_token']);
            if($check_token == "0"){
                $res['status'] = "0";
                $res['message'] = "Token Not Match.";
                $res['data'] = new arrayObject();
                echo json_encode($res);
                return false;
            }else{
                $requestField['user_id'] = $check_token;
            }
        }
        if(!isset($requestField['user_id'])){
            $counter++;
            $errors['user_id'] = "User ID field is required";
        }
        if(!isset($_FILES['profile_image']['name'])){
            $counter++;
            $errors['profile_image'] = "Image field is required";
        }
        
        if($counter > 0 && count($errors) > 0){
            $res['status'] = '0';
            $res['message'] = "Total ".$counter." Found in Request";
            $res['data'] = $errors; 
            echo json_encode($res);
            return false;
        }
        $sql = 'SELECT * FROM `users`WHERE id = "'.$requestField['user_id'].'"';
        $result = mysqli_query($conn, $sql);
        //$user_data = mysqli_fetch_assoc($result);
        $count = mysqli_num_rows($result);
        if($count == 0){
            $res['status'] = '0';
            $res['message'] = "User is not exist";
            $res['data'] = new arrayObject();
            echo json_encode($res);
            return false;
        }else{
            if(isset($_FILES["profile_image"]["name"]) && $_FILES["profile_image"]["name"] != ''){
                $path = '../../upload/profile';
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                    chmod($path, 0777);
                }
                $path_parts = pathinfo($_FILES["profile_image"]["name"]);
                $imageName = time() . "." . $path_parts['extension'];
                move_uploaded_file($_FILES['profile_image']['tmp_name'], "$path/$imageName");

                $image_full_url = 'upload/profile/'.$imageName;
                $add_query = 'UPDATE users SET image_name= "'.$imageName.'",image_url = "'.$image_full_url.'",modified_date = "'.date('Y-m-d H:i:s').'" WHERE id = "'.$requestField['user_id'].'"';
                $result = mysqli_query($conn, $add_query);
                if($result){
                    $res['status'] = '1';
                    $res['message'] = "Profile Image Update Sucessfully.";
                    $res['data'] = new arrayObject();
                    echo json_encode($res);
                    return false;
                }else{
                    $res['status'] = '0';
                    $res['message'] = "Profile Image Update problem";
                    $res['data'] = new arrayObject();
                    echo json_encode($res);
                }
                
            }
        }
        break;
    case "configuration":
        $configuration_query = 'SELECT androidVersion,iosVersion FROM `configuration`';
        $configuration_result = mysqli_query($conn, $configuration_query);
        $configuration_data = mysqli_fetch_assoc($configuration_result);
        $res['status'] = '1';
        $res['message'] = "Get configuration";
        $res['data'] = $configuration_data;
        echo json_encode($res);
        return false;
        break;
    case "delete_event":
        $counter = 0;
        $errors = array();
        // Check Token code
        if(!isset($requestField['api_token'])){
            $counter++;
            $errors['api_token'] = "API Token field is required";
        }else{
            $check_token = check_api_token($requestField['api_token']);
            if($check_token == "0"){
                $res['status'] = "0";
                $res['message'] = "Token Not Match.";
                $res['data'] = new arrayObject();
                echo json_encode($res);
                return false;
            }else{
                $requestField['user_id'] = $check_token;
            }
        }
        if(!isset($requestField['user_id'])){
            $counter++;
            $errors['user_id'] = "User ID field is required";
        }
        if(!isset($requestField['event_id'])){
            $counter++;
            $errors['event_id'] = "Event ID field is required";
        }
        if(!isset($requestField['delete_id'])){
            $counter++;
            $errors['delete_id'] = "Delete ID field is required";
        }
        if($counter > 0 && count($errors) > 0){
            $res['status'] = '0';
            $res['message'] = "Total ".$counter." Found in Request";
            $res['data'] = $errors; 
            echo json_encode($res);
            return false;
        }

        $delete_query = 'DELETE FROM `event_documents` WHERE `event_id`="'.$requestField['event_id'].'" AND id="'.$requestField['delete_id'].'"';
        $result = mysqli_query($conn, $delete_query);
        if($result){
            $res['status'] = '1';
            $res['message'] = "Delete PDF Document";
            $res['data'] = new arrayObject();
        }else{
            $res['status'] = '0';
            $res['message'] = "Delete PDF Document Problem";
            $res['data'] = new arrayObject();
        }
        echo json_encode($res);
        return false;
        break;
    case "event_image_delete":
            $counter = 0;
            $errors = array();
            // Check Token code
            if(!isset($requestField['api_token'])){
                $counter++;
                $errors['api_token'] = "API Token field is required";
            }else{
                $check_token = check_api_token($requestField['api_token']);
                if($check_token == "0"){
                    $res['status'] = "0";
                    $res['message'] = "Token Not Match.";
                    $res['data'] = new arrayObject();
                    echo json_encode($res);
                    return false;
                }else{
                    $requestField['user_id'] = $check_token;
                }
            }
            if(!isset($requestField['user_id'])){
                $counter++;
                $errors['user_id'] = "User ID field is required";
            }
            if(!isset($requestField['event_id'])){
                $counter++;
                $errors['event_id'] = "Event ID field is required";
            }
            if(!isset($requestField['image_delete_id'])){
                $counter++;
                $errors['image_delete_id'] = "Image Delete ID field is required";
            }
            if($counter > 0 && count($errors) > 0){
                $res['status'] = '0';
                $res['message'] = "Total ".$counter." Found in Request";
                $res['data'] = $errors; 
                echo json_encode($res);
                return false;
            }
    
            $delete_query = 'DELETE FROM `event_images` WHERE `event_id`="'.$requestField['event_id'].'" AND id="'.$requestField['image_delete_id'].'"';
            $result = mysqli_query($conn, $delete_query);
            if($result){
                $res['status'] = '1';
                $res['message'] = "Event Image Deleted";
                $res['data'] = new arrayObject();
            }else{
                $res['status'] = '0';
                $res['message'] = "Event Image Deleted Problem";
                $res['data'] = new arrayObject();
            }
            echo json_encode($res);
            return false;
            break;
    
    default:
        $res['status'] = '0';
        $res['message'] = "Url is Not Valid";
        $res['data'] = new arrayObject();
        echo json_encode($res);
        return false;
  }
?>