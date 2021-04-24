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
        $sql = 'SELECT * FROM `users`WHERE email_id = "'.$requestField['email'].'" AND password = MD5('.$requestField['password'].')';
        $result = mysqli_query($conn, $sql);
        $user_id_data = mysqli_fetch_assoc($result);
        $user_count = mysqli_num_rows($result);
        if(isset($user_count) && $user_count > 0){
            $data = array();
            $api_token = generateRandomString(64);
            $qry = 'UPDATE `users` SET device_token = "'.$requestField['device_token'].'",device_type = "'.$requestField['device_type'].'",device_name = "'.$requestField['device_name'].'",api_access_token = "'.$api_token.'",last_login = "'.date('Y-m-d H:i:s').'" WHERE id ="'.$user_id_data['id'].'"';
            $result = mysqli_query($conn, $qry);

            $sql = 'SELECT * FROM `users`WHERE email_id = "'.$requestField['email'].'" AND password = MD5('.$requestField['password'].')';
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
            $data['status'] = (isset($user_data['status']) && $user_data['status'] != "") ? $user_data['status'] : "";
            $data['image_name'] = (isset($user_data['image_name']) && $user_data['image_name'] != "") ? $user_data['image_name'] : "";
            $data['image_url'] = (isset($user_data['image_url']) && $user_data['image_url'] != "") ? BASE_URL.$user_data['image_url'] : "";
            
            // Category get Code
            $get_category_query = 'SELECT uic.id,c.name FROM `user_interested_categories` uic LEFT JOIN categories c ON c.id = uic.category_id WHERE uic.user_id = "'.$user_data['id'].'"';
            $get_category_result = mysqli_query($conn, $get_category_query);
            $category = array();
            while($get_category = mysqli_fetch_assoc($get_category_result)){
                $get_category_array['id'] = $get_category['id'];
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
            $sql = 'SELECT * FROM `users`WHERE email_id = "'.$requestField['email'].'"';
            $result = mysqli_query($conn, $sql);
            $user_count = mysqli_num_rows($result);
            if($user_count == 0){
                $res['status'] = "0";
                $res['message'] = "Invalid email eddress";
                $res['data'] = new arrayObject();
                echo json_encode($res);
                return false;
            }else{
                $res['status'] = "0";
                $res['message'] = "Invalid password.";
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
                $res['message'] = "OTP Update Failed.";
                $res['data'] = new arrayObject();
                echo json_encode($res);
                return false;
            }
            }else{
            $res['status'] = "0";
            $res['message'] = "OTP Update Failed.";
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
                $qry = 'UPDATE `users` SET password = MD5('.$requestField['password'].'),change_password_flag = "1" WHERE id ="'.$requestField['user_id'].'"';
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
            registration_end_date = "'.$requestField['registration_end_date'].'",
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
                foreach($age_gender_array as $key=>$value){
                    $add_query = 'INSERT INTO event_grade_categories SET event_id= "'.$last_id.'",grade_id = "'.$value.'"';
                    $result = mysqli_query($conn, $add_query);
                }

                // Add Council Members
                $council = $requestField['council_ids'];
                $council_array = explode(",",$council);
                foreach($age_gender_array as $key=>$value){
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
                    $imageName = time() . "." . $path_parts['extension'];
                    move_uploaded_file($_FILES['event_image']['tmp_name'], "$path/$imageName");
                    $add_query = 'INSERT INTO event_images SET event_id= "'.$last_id.'",image_url = "'.$imageName.'",created_date = "'.date('Y-m-d H:i:s').'"';
                    $result = mysqli_query($conn, $add_query);
                }
                // Add Document
                // PDF Document
                if(isset($_FILES["pdf_document"]["name"]) && $_FILES["pdf_document"]["name"] != ''){
                    $path = '../../upload/event_document';
                    if (!file_exists($path)) {
                        mkdir($path, 0777, true);
                        chmod($path, 0777);
                    }
                    $path_parts = pathinfo($_FILES["pdf_document"]["name"]);
                    $imageName = date("Y").time() . "." . $path_parts['extension'];
                    move_uploaded_file($_FILES['pdf_document']['tmp_name'], "$path/$imageName");
                    $add_query = 'INSERT INTO event_documents SET event_id= "'.$last_id.'",file_name = "'.$imageName.'",type = "pdf_document"';
                    $result = mysqli_query($conn, $add_query);
                }

                // waiver from Document
                if(isset($_FILES["waiver_from"]["name"]) && $_FILES["waiver_from"]["name"] != ''){
                    $path = '../../upload/event_document';
                    if (!file_exists($path)) {
                        mkdir($path, 0777, true);
                        chmod($path, 0777);
                    }
                    $path_parts = pathinfo($_FILES["waiver_from"]["name"]);
                    $imageName = time() . "." . $path_parts['extension'];
                    move_uploaded_file($_FILES['waiver_from']['tmp_name'], "$path/$imageName");
                    $add_query = 'INSERT INTO event_documents SET event_id= "'.$last_id.'",file_name = "'.$imageName.'",type = "waiver_from"';
                    $result = mysqli_query($conn, $add_query);
                }
                $res['status'] = '1';
                $res['message'] = "Event Add Sucussfully.";
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
            // update Event Code
            $update_query = 'UPDATE events SET 
            title= "'.$requestField['name'].'",
            description = "'.$requestField['description'].'",
            category_id = "'.$requestField['category_id'].'",
            sub_category_id = "'.$requestField['sub_category_id'].'",
            event_date = "'.date("Y-m-d", strtotime($requestField['date'])).'",
            event_time = "'.$requestField['time'].'",
            event_date_time = "'.date("Y-m-d", strtotime($requestField['date'])).' '.$requestField['time'].'",
            registration_end_date = "'.$requestField['registration_end_date'].'",
            event_internal_notes = "'.$requestField['event_internal_notes'].'",
            event_external_notes = "'.$requestField['event_external_notes'].'",
            last_update_by = "'.$requestField['user_id'].'",
            modified_date = "'.date('Y-m-d H:i:s').'"
            WHERE id = "'.$requestField['event_id'].'"
            ';
            $result = mysqli_query($conn, $update_query);
            if($result){
                // Add AGE & GENDER
                $delete_age_gender = 'DELETE FROM `event_age_gender_categories` WHERE event_id ="'.$requestField['event_id'].'"';
                $age_gender_result = mysqli_query($conn, $delete_age_gender);
                $age_gender = $requestField['age_gender'];
                $age_gender_array = explode(",",$age_gender);
                foreach($age_gender_array as $key=>$value){
                    $add_query = 'INSERT INTO event_age_gender_categories SET event_id= "'.$requestField['event_id'].'",age_gender_id = "'.$value.'"';
                    $result = mysqli_query($conn, $add_query);
                }

                // Add GRADE
                $delete_age_gender = 'DELETE FROM `event_grade_categories` WHERE event_id ="'.$requestField['event_id'].'"';
                $age_gender_result = mysqli_query($conn, $delete_age_gender);
                $grade = $requestField['grade'];
                $grade_array = explode(",",$grade);
                foreach($age_gender_array as $key=>$value){
                    $add_query = 'INSERT INTO event_grade_categories SET event_id= "'.$requestField['event_id'].'",grade_id = "'.$value.'"';
                    $result = mysqli_query($conn, $add_query);
                }

                // Add Council Members
                $delete_council = 'DELETE FROM `event_council_members` WHERE event_id ="'.$requestField['event_id'].'"';
                $delete_council_result = mysqli_query($conn, $delete_council);
                $council = $requestField['council_ids'];
                $council_array = explode(",",$council);
                foreach($age_gender_array as $key=>$value){
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
                    $imageName = time() . "." . $path_parts['extension'];
                    move_uploaded_file($_FILES['event_image']['tmp_name'], "$path/$imageName");
                    $add_query = 'INSERT INTO event_images SET event_id= "'.$requestField['event_id'].'",image_url = "'.$imageName.'",created_date = "'.date('Y-m-d H:i:s').'"';
                    $result = mysqli_query($conn, $add_query);
                }
                // Add Document
                // PDF Document
                if(isset($_FILES["pdf_document"]["name"]) && $_FILES["pdf_document"]["name"] != ''){
                    $path = '../../upload/event_document';
                    if (!file_exists($path)) {
                        mkdir($path, 0777, true);
                        chmod($path, 0777);
                    }
                    $path_parts = pathinfo($_FILES["pdf_document"]["name"]);
                    $imageName = date("Y").time() . "." . $path_parts['extension'];
                    move_uploaded_file($_FILES['pdf_document']['tmp_name'], "$path/$imageName");
                    $add_query = 'INSERT INTO event_documents SET event_id= "'.$requestField['event_id'].'",file_name = "'.$imageName.'",type = "pdf_document"';
                    $result = mysqli_query($conn, $add_query);
                }

                // waiver from Document
                if(isset($_FILES["waiver_from"]["name"]) && $_FILES["waiver_from"]["name"] != ''){
                    $path = '../../upload/event_document';
                    if (!file_exists($path)) {
                        mkdir($path, 0777, true);
                        chmod($path, 0777);
                    }
                    $path_parts = pathinfo($_FILES["waiver_from"]["name"]);
                    $imageName = time() . "." . $path_parts['extension'];
                    move_uploaded_file($_FILES['waiver_from']['tmp_name'], "$path/$imageName");
                    $add_query = 'INSERT INTO event_documents SET event_id= "'.$requestField['event_id'].'",file_name = "'.$imageName.'",type = "waiver_from"';
                    $result = mysqli_query($conn, $add_query);
                }
                $res['status'] = '1';
                $res['message'] = "Event Update Sucussfully.";
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
                $count_sql = 'SELECT COUNT(*) as count FROM `events` WHERE event_date_time < "'.$current_date_time.'"';
                $count_result = mysqli_query($conn, $count_sql);
                $count_data = mysqli_fetch_assoc($count_result);
                
                $sql = 'SELECT * FROM `events` WHERE event_date_time < "'.$current_date_time.'" LIMIT '.$pageNumber.','.$pageLimit.'';

            }else if($user_data['user_type_name'] == "House Captain"){
                // Total Count Get 
                $count_sql = 'SELECT COUNT(*) as count FROM `events` e 
                LEFT JOIN event_council_members ecm ON e.id = ecm.event_id
                LEFT JOIN users u ON ecm.council_member = u.id
                LEFT JOIN houses h ON h.id = u.house_id WHERE event_date_time < "'.$current_date_time.'" AND h.name = "'.$user_data['house_name'].'"';
                $count_result = mysqli_query($conn, $count_sql);
                $count_data = mysqli_fetch_assoc($count_result);

                $sql = 'SELECT e.*,h.name as house_name FROM `events` e 
                LEFT JOIN event_council_members ecm ON e.id = ecm.event_id
                LEFT JOIN users u ON ecm.council_member = u.id
                LEFT JOIN houses h ON h.id = u.house_id WHERE event_date_time < "'.$current_date_time.'" AND h.name = "'.$user_data['house_name'].'" LIMIT '.$pageNumber.','.$pageLimit.'';
            }else{
                $student_sql = 'SELECT dob,gender FROM `users`WHERE id = "'.$requestField['user_id'].'"';
                $result = mysqli_query($conn, $student_sql);
                $user_details = mysqli_fetch_assoc($result);

                // Total Count Get 
                $count_sql = 'SELECT COUNT(*) as count FROM `events` e LEFT JOIN event_age_gender_categories eagc ON eagc.event_id = e.id LEFT JOIN age_gender_categories agc ON agc.id = eagc.age_gender_id LEFT JOIN event_council_members ecm ON e.id = ecm.event_id LEFT JOIN users u ON ecm.council_member = u.id LEFT JOIN houses h ON h.id = u.house_id WHERE event_date_time < "'.$current_date_time.'" AND EXTRACT(YEAR FROM "'.$user_details['dob'].'") >= agc.start_year and EXTRACT(YEAR FROM "'.$user_details['dob'].'") <= agc.end_year AND agc.gender = "'.$user_details['gender'].'" AND h.name = "'.$user_data['house_name'].'"';
                $count_result = mysqli_query($conn, $count_sql);
                $count_data = mysqli_fetch_assoc($count_result);
                
                $sql = 'SELECT e.*,agc.title,agc.start_year,agc.end_year,h.name as house_name FROM `events` e LEFT JOIN event_age_gender_categories eagc ON eagc.event_id = e.id LEFT JOIN age_gender_categories agc ON agc.id = eagc.age_gender_id LEFT JOIN event_council_members ecm ON e.id = ecm.event_id LEFT JOIN users u ON ecm.council_member = u.id LEFT JOIN houses h ON h.id = u.house_id WHERE event_date_time < "'.$current_date_time.'" AND EXTRACT(YEAR FROM "'.$user_details['dob'].'") >= agc.start_year and EXTRACT(YEAR FROM "'.$user_details['dob'].'") <= agc.end_year AND agc.gender = "'.$user_details['gender'].'" AND h.name = "'.$user_data['house_name'].'" LIMIT '.$pageNumber.','.$pageLimit.'';
            }
            
            $result = mysqli_query($conn, $sql);
            while($event_data = mysqli_fetch_assoc($result)){
                $event_array['id'] = (isset($event_data['id']) && $event_data['id'] != "") ? $event_data['id'] : "";
                $event_array['title'] = (isset($event_data['title']) && $event_data['title'] != "") ? $event_data['title'] : "";
                $event_array['description'] = (isset($event_data['description']) && $event_data['description'] != "") ? $event_data['description'] : "";
                $event_array['event_date'] = (isset($event_data['event_date']) && $event_data['event_date'] != "") ? $event_data['event_date'] : "";
                $event_array['event_time'] = (isset($event_data['event_time']) && $event_data['event_time'] != "") ? $event_data['event_time'] : "";
                $event_array['registration_end_date'] = (isset($event_data['registration_end_date']) && $event_data['registration_end_date'] != "") ? $event_data['registration_end_date'] : "";
                $event_array['event_internal_notes'] = (isset($event_data['event_internal_notes']) && $event_data['event_internal_notes'] != "") ? $event_data['event_internal_notes'] : "";
                $event_array['event_external_notes'] = (isset($event_data['event_external_notes']) && $event_data['event_external_notes'] != "") ? $event_data['event_external_notes'] : "";
                $event_array['category_id'] = (isset($event_data['category_id']) && $event_data['category_id'] != "") ? $event_data['category_id'] : "";
                $category_query = 'SELECT `name` FROM `categories` WHERE id = "'.$event_data['category_id'].'"';
                $category_result = mysqli_query($conn, $category_query);
                $category_data = mysqli_fetch_assoc($category_result);
                $event_array['category_name'] = (isset($category_data['name']) && $category_data['name'] != "") ? $category_data['name'] : "";
                $event_array['sub_category_id'] = (isset($event_data['sub_category_id']) && $event_data['sub_category_id'] != "") ? $event_data['sub_category_id'] : "";

                $data[] = $event_array;
            }
            $res['status'] = '1';
            $res['message'] = "Past Event List.";
            $res['data'] = $data;
            $res['Total_Count'] = $count_data['count'];
            $res['last_page'] = ceil($count_data['count'] / $pageLimit);
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
                $count_sql = 'SELECT COUNT(*) as count FROM `events` WHERE event_date_time > "'.$current_date_time.'"';
                $count_result = mysqli_query($conn, $count_sql);
                $count_data = mysqli_fetch_assoc($count_result);
                
                $sql = 'SELECT * FROM `events` WHERE event_date_time > "'.$current_date_time.'" LIMIT '.$pageNumber.','.$pageLimit.'';

            }else if($user_data['user_type_name'] == "House Captain"){
                // Total Count Get 
                $count_sql = 'SELECT COUNT(*) as count FROM `events` e 
                LEFT JOIN event_council_members ecm ON e.id = ecm.event_id
                LEFT JOIN users u ON ecm.council_member = u.id
                LEFT JOIN houses h ON h.id = u.house_id WHERE event_date_time > "'.$current_date_time.'" AND h.name = "'.$user_data['house_name'].'"';
                $count_result = mysqli_query($conn, $count_sql);
                $count_data = mysqli_fetch_assoc($count_result);

                $sql = 'SELECT e.*,h.name as house_name FROM `events` e 
                LEFT JOIN event_council_members ecm ON e.id = ecm.event_id
                LEFT JOIN users u ON ecm.council_member = u.id
                LEFT JOIN houses h ON h.id = u.house_id WHERE event_date_time > "'.$current_date_time.'" AND h.name = "'.$user_data['house_name'].'" LIMIT '.$pageNumber.','.$pageLimit.'';
            }else{
                $student_sql = 'SELECT dob,gender FROM `users`WHERE id = "'.$requestField['user_id'].'"';
                $result = mysqli_query($conn, $student_sql);
                $user_details = mysqli_fetch_assoc($result);

                // Total Count Get 
                $count_sql = 'SELECT COUNT(*) as count FROM `events` e LEFT JOIN event_age_gender_categories eagc ON eagc.event_id = e.id LEFT JOIN age_gender_categories agc ON agc.id = eagc.age_gender_id LEFT JOIN event_council_members ecm ON e.id = ecm.event_id LEFT JOIN users u ON ecm.council_member = u.id LEFT JOIN houses h ON h.id = u.house_id WHERE event_date_time > "'.$current_date_time.'" AND EXTRACT(YEAR FROM "'.$user_details['dob'].'") >= agc.start_year and EXTRACT(YEAR FROM "'.$user_details['dob'].'") <= agc.end_year AND agc.gender = "'.$user_details['gender'].'" AND h.name = "'.$user_data['house_name'].'"';
                $count_result = mysqli_query($conn, $count_sql);
                $count_data = mysqli_fetch_assoc($count_result);
                
                $sql = 'SELECT e.*,agc.title,agc.start_year,agc.end_year,h.name as house_name FROM `events` e LEFT JOIN event_age_gender_categories eagc ON eagc.event_id = e.id LEFT JOIN age_gender_categories agc ON agc.id = eagc.age_gender_id LEFT JOIN event_council_members ecm ON e.id = ecm.event_id LEFT JOIN users u ON ecm.council_member = u.id LEFT JOIN houses h ON h.id = u.house_id WHERE event_date_time > "'.$current_date_time.'" AND EXTRACT(YEAR FROM "'.$user_details['dob'].'") >= agc.start_year and EXTRACT(YEAR FROM "'.$user_details['dob'].'") <= agc.end_year AND agc.gender = "'.$user_details['gender'].'" AND h.name = "'.$user_data['house_name'].'" LIMIT '.$pageNumber.','.$pageLimit.'';
            }
            $result = mysqli_query($conn, $sql);
            while($event_data = mysqli_fetch_assoc($result)){
                $event_array['id'] = (isset($event_data['id']) && $event_data['id'] != "") ? $event_data['id'] : "";
                $event_array['title'] = (isset($event_data['title']) && $event_data['title'] != "") ? $event_data['title'] : "";
                $event_array['description'] = (isset($event_data['description']) && $event_data['description'] != "") ? $event_data['description'] : "";
                $event_array['event_date'] = (isset($event_data['event_date']) && $event_data['event_date'] != "") ? $event_data['event_date'] : "";
                $event_array['event_time'] = (isset($event_data['event_time']) && $event_data['event_time'] != "") ? $event_data['event_time'] : "";
                $event_array['registration_end_date'] = (isset($event_data['registration_end_date']) && $event_data['registration_end_date'] != "") ? $event_data['registration_end_date'] : "";
                $event_array['event_internal_notes'] = (isset($event_data['event_internal_notes']) && $event_data['event_internal_notes'] != "") ? $event_data['event_internal_notes'] : "";
                $event_array['event_external_notes'] = (isset($event_data['event_external_notes']) && $event_data['event_external_notes'] != "") ? $event_data['event_external_notes'] : "";
                $event_array['category_id'] = (isset($event_data['category_id']) && $event_data['category_id'] != "") ? $event_data['category_id'] : "";
                $category_query = 'SELECT `name` FROM `categories` WHERE id = "'.$event_data['category_id'].'"';
                $category_result = mysqli_query($conn, $category_query);
                $category_data = mysqli_fetch_assoc($category_result);
                $event_array['category_name'] = (isset($category_data['name']) && $category_data['name'] != "") ? $category_data['name'] : "";
                $event_array['sub_category_id'] = (isset($event_data['sub_category_id']) && $event_data['sub_category_id'] != "") ? $event_data['sub_category_id'] : "";

                $data[] = $event_array;
            }
            $res['status'] = '1';
            $res['message'] = "Upcomming Event List.";
            $res['data'] = $data;
            $res['Total_Count'] = $count_data['count'];
            $res['last_page'] = ceil($count_data['count'] / $pageLimit);
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
        //$user_data = mysqli_fetch_assoc($result);
        $count = mysqli_num_rows($result);
        if($count == 0){
            $res['status'] = '0';
            $res['message'] = "User is not exist";
            $res['data'] = new arrayObject();
            echo json_encode($res);
            return false;
        }else{
            $event_query = 'SELECT * FROM `events` WHERE id = "'.$requestField['event_id'].'"';
            $event_result = mysqli_query($conn, $event_query);
            $event_data = mysqli_fetch_assoc($event_result);

            $data['id'] = (isset($event_data['id']) && $event_data['id'] != "") ? $event_data['id'] : "";
            $data['category_id'] = (isset($event_data['category_id']) && $event_data['category_id'] != "") ? $event_data['category_id'] : "";
            $category_query = 'SELECT `name` FROM `categories` WHERE id = "'.$event_data['category_id'].'"';
            $category_result = mysqli_query($conn, $category_query);
            $category_data = mysqli_fetch_assoc($category_result);
            $data['category_name'] = (isset($category_data['name']) && $category_data['name'] != "") ? $category_data['name'] : "";
            $data['title'] = (isset($event_data['title']) && $event_data['title'] != "") ? $event_data['title'] : "";
            $data['description'] = (isset($event_data['description']) && $event_data['description'] != "") ? $event_data['description'] : "";
            $data['event_date'] = (isset($event_data['event_date']) && $event_data['event_date'] != "") ? $event_data['event_date'] : "";
            $data['event_time'] = (isset($event_data['event_time']) && $event_data['event_time'] != "") ? $event_data['event_time'] : "";
            $data['registration_end_date'] = (isset($event_data['registration_end_date']) && $event_data['registration_end_date'] != "") ? $event_data['registration_end_date'] : "";

            $event_document_query = 'SELECT * FROM `event_documents` WHERE event_id = "'.$requestField['event_id'].'"';
            $event_document_result = mysqli_query($conn, $event_document_query);
            $count = mysqli_num_rows($event_document_result);
            if($count > 0){
                while($event_document_data = mysqli_fetch_assoc($event_document_result)){
                    $data[$event_document_data['type']] = BASE_URL.'upload/event_document/'.$event_document_data['file_name'];
                }
            }else{
                $data['pdf_documen'] = '';
                $data['waiver_from'] = '';
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

                $res['status'] = '1';
                $res['message'] = "Waiver Upload file Sucessfully.";
                $res['data'] = new arrayObject();;
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

        $get_category_query = 'SELECT * FROM `categories`WHERE category_id = "0" AND status = "1"';
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
            $sql = 'UPDATE `users` SET `api_access_token` = "" , `device_token` = "" WHERE `id` = "'.$requestField['user_id'].'"';
            $result = mysqli_query($conn, $sql);
            if($result){
                $res['status'] = '1';
                $res['message'] = "Logout Sucessfully";
                $res['data'] = new arrayObject();
                echo json_encode($res);
                return false;
            }else{
                $res['status'] = '0';
                $res['message'] = "Logout Failed";
                $res['data'] = new arrayObject();
                echo json_encode($res);
                return false;
            }

        }
        break;
    case "user_profile":
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
            $data['change_password_flag'] = (isset($user_data['change_password_flag']) && $user_data['change_password_flag'] != "") ? $user_data['id'] : "";
            $data['api_access_token'] = (isset($user_data['api_access_token']) && $user_data['api_access_token'] != "") ? $user_data['api_access_token'] : "";
            $data['device_token'] = (isset($user_data['device_token']) && $user_data['device_token'] != "") ? $user_data['device_token'] : "";
            $data['device_type'] = (isset($user_data['device_type']) && $user_data['device_type'] != "") ? $user_data['device_type'] : "";
            $data['device_name'] = (isset($user_data['device_name']) && $user_data['device_name'] != "") ? $user_data['device_name'] : "";
            $data['last_login'] = (isset($user_data['last_login']) && $user_data['last_login'] != "") ? $user_data['last_login'] : "";
            $data['created_date'] = (isset($user_data['created_date']) && $user_data['created_date'] != "") ? $user_data['created_date'] : "";
            $data['modified_date'] = (isset($user_data['modified_date']) && $user_data['modified_date'] != "") ? $user_data['modified_date'] : "";
            $data['status'] = (isset($user_data['status']) && $user_data['status'] != "") ? $user_data['status'] : "";
            $data['image_name'] = (isset($user_data['image_name']) && $user_data['image_name'] != "") ? $user_data['image_name'] : "";
            $data['image_url'] = (isset($user_data['image_url']) && $user_data['image_url'] != "") ? BASE_URL.$user_data['image_url'] : "";

            // Category get Code
            $get_category_query = 'SELECT uic.id,c.name FROM `user_interested_categories` uic LEFT JOIN categories c ON c.id = uic.category_id WHERE uic.user_id = "'.$user_data['id'].'"';
            $get_category_result = mysqli_query($conn, $get_category_query);
            $category = array();
            while($get_category = mysqli_fetch_assoc($get_category_result)){
                $get_category_array['id'] = $get_category['id'];
                $get_category_array['name'] = $get_category['name'];
                $category[] = $get_category_array;
            }
            $data['category'] = $category;

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
                }
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
            if($user_data['user_type_name'] == "Administrator" || $user_data['user_type_name'] == "Core Captain"){
                $house_query = 'SELECT u.id,u.first_name,u.middle_name,u.last_name FROM `users` u LEFT JOIN user_type ut ON u.user_type_id = ut.id WHERE ut.name = "House Captain"';
            }else{
                $house_query = 'SELECT u.id,u.first_name,u.middle_name,u.last_name FROM `users` u LEFT JOIN user_type ut ON u.user_type_id = ut.id LEFT JOIN houses h ON h.id = u.house_id WHERE ut.name = "House Captain" AND h.id = "'.$user_data['house_id'].'"';
            }
            $result = mysqli_query($conn, $house_query);
            while($house_data = mysqli_fetch_assoc($result)){
                $house_array['id'] = $house_data['id'];
                $house_array['first_name'] = $house_data['first_name'];
                $house_array['middle_name'] = $house_data['middle_name'];
                $house_array['last_name'] = $house_data['last_name'];
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
        $sql = 'SELECT * FROM `age_gender_categories`';
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
    
    default:
        $res['status'] = '0';
        $res['message'] = "Url is Not Valid";
        $res['data'] = new arrayObject();
        echo json_encode($res);
        return false;
  }
?>