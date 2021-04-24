<?php 
include 'include/conn.php';
include 'include/function.php';
date_default_timezone_set('Asia/Kolkata');
if(isset($_POST['action']) && $_POST['action'] == "change-password"){
  $password = (isset($_POST['password']) && $_POST['password'] != "") ? $_POST['password'] : "";
  $confirmPassword = (isset($_POST['confirmPassword']) && $_POST['confirmPassword'] != "") ? $_POST['confirmPassword'] : "";
  $email = (isset($_POST['email']) && $_POST['email'] != "") ? $_POST['email'] : "";
  if($password != '' && $confirmPassword != ''){
    if(isset($email) && $email != ''){
      $sql = 'SELECT * FROM `users`WHERE email_id = "'.$email.'"';
      $result = mysqli_query($conn, $sql);
      $user_data = mysqli_fetch_assoc($result);
        if(isset($user_data) && $user_data > 0){
          $qry = 'UPDATE `users` SET password = MD5("'.$password.'"),change_password_flag = "1" WHERE email_id ="'.$email.'"';
          $_SESSION['user_details']['change_password_flag'] = "1";
        }else{
          $res['status'] = "0";
          $res['message'] = "Email Id Not Match Try Again";
          $res['data'] = array();
          $_SESSION['FLASH_ERROR_FLAG'] = "Email Id Not Match Try Again";
          echo json_encode($res);
          return false;
        }
    }else{
      $user_id = $_SESSION['user_details']['id'];
      $qry = 'UPDATE `users` SET password = MD5("'.$password.'"),change_password_flag = "1" WHERE id ="'.$user_id.'"';
      $_SESSION['user_details']['change_password_flag'] = "1";
    }
    
    $result = mysqli_query($conn, $qry);
    if($result){
      $res['status'] = "1";
      $res['message'] = "Password updated successfully.";
      $res['data'] = array();
      $_SESSION['FLASH_SUCCESS_FLAG'] = "Password updated successfully.";
      $_SESSION['user_details']['password'] = md5($password);
      echo json_encode($res);
      return false;
    }else{
      $res['status'] = "0";
      $res['message'] = "Password updated Failed.";
      $res['data'] = array();
      $_SESSION['FLASH_ERROR_FLAG'] = "Password updated Failed";
      echo json_encode($res);
      return false;
    }
  }else{
    $res['status'] = "0";
    $res['message'] = "Password updated Failed.";
    $res['data'] = array();
    $_SESSION['FLASH_ERROR_FLAG'] = "Password updated Failed";
    echo json_encode($res);
    return false;
  }
}
if(isset($_POST['action']) && $_POST['action'] == "forgot-password"){
  $email = (isset($_POST['email']) && $_POST['email'] != "") ? $_POST['email'] : "";
  if($email != ''){
    $sql = 'SELECT * FROM `users`WHERE email_id = "'.$email.'"';
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
          $qry = 'UPDATE `users` SET otp = "'.$password.'" WHERE email_id ="'.$email.'"';
          $result = mysqli_query($conn, $qry);
          if($result){
            $data = array();
            $data['email'] = $email;
            $res['status'] = "1";
            $res['message'] = "Please check mail for reset password.";
            $res['data'] = $data;
            $_SESSION['FLASH_SUCCESS_FLAG'] = "Please check mail for reset password.";
            echo json_encode($res);
            return false;
          }else{
            $res['status'] = "0";
            $res['message'] = "OTP Update Failed.";
            $res['data'] = array();
            $_SESSION['FLASH_SUCCESS_FLAG'] = "OTP Update Failed.";
            echo json_encode($res);
            return false;
          }
        }else{
          $res['status'] = "0";
          $res['message'] = "OTP Update Failed.";
          $res['data'] = array();
          $_SESSION['FLASH_SUCCESS_FLAG'] = "OTP Update Failed.";
          echo json_encode($res);
          return false;
        }
    }else{
      $res['status'] = "0";
      $res['message'] = "Invalid email.";
      $res['data'] = array();
      $_SESSION['FLASH_SUCCESS_FLAG'] = "Invalid email.";
      echo json_encode($res);
      return false;
    }
  }else{
    $res['status'] = "0";
    $res['message'] = "Forgot Password Failed.";
    $res['data'] = array();
    $_SESSION['FLASH_ERROR_FLAG'] = "Forgot Password Failed.";
    echo json_encode($res);
    return false;
  }

}
if(isset($_POST['action']) && $_POST['action'] == "verify_otp"){
  $otp = $_POST['codeBox1'].$_POST['codeBox2'].$_POST['codeBox3'].$_POST['codeBox4'];
  $email = (isset($_POST['email']) && $_POST['email'] != "") ? $_POST['email'] : "";
  if($otp != ''){
    $sql = 'SELECT * FROM `users`WHERE email_id = "'.$email.'" AND otp ="'.$otp.'"';
    $result = mysqli_query($conn, $sql);
    $email_data = mysqli_fetch_assoc($result);
    if(isset($email_data) && $email_data > 0){
        $data = array();
        $data['email'] = $email;
        $res['status'] = "1";
        $res['message'] = "OTP Verify Sucessfully.";
        $res['data'] = $data;
        echo json_encode($res);
        return false;
    }else{
        $res['status'] = "0";
        $res['message'] = "Invalid OTP.";
        $res['data'] = array();
        echo json_encode($res);
        return false;
    }
  }
}
if(isset($_POST['action']) && $_POST['action'] == "category_list"){
  $params = $columns = $totalRecords = $data = array();

	$params = $_REQUEST;

	$columns = array(
		0 => 'name'
  );
  
  $where_condition = $sqlTot = $sqlRec = "";

	if( !empty($params['search']['value']) ) {
		//$where_condition .=	" WHERE ";
		$where_condition .= " AND name LIKE '%".$params['search']['value']."%' ";
  }
  $sql_query = " SELECT * FROM categories WHERE category_id ='0' AND status = '1'";
	$sqlTot .= $sql_query;
	$sqlRec .= $sql_query;
	
	if(isset($where_condition) && $where_condition != '') {

		$sqlTot .= $where_condition;
		$sqlRec .= $where_condition;
	}

   $sqlRec .=  " ORDER BY ". $columns[$params['order'][0]['column']]."   ".$params['order'][0]['dir']."  LIMIT ".$params['start']." ,".$params['length']." ";
   
   $queryTot = mysqli_query($conn, $sqlTot) or die("Database Error:". mysqli_error($con));

	$totalRecords = mysqli_num_rows($queryTot);
	$queryRecords = mysqli_query($conn, $sqlRec) or die("Error to Get the Post details.");
  $i = 1;
	while( $row = mysqli_fetch_assoc($queryRecords) ) {
    //$row[] = "<a href=''>Edit</a>";
    //$data_array['id'] = $i;
    $data_array['name'] = (isset($row['name']) && $row['name'] != "") ? $row['name'] : "";
    $data_array['created_date'] = (isset($row['created_date']) && $row['created_date'] != "") ? $row['created_date'] : "";
    $data_array['action'] = '<a href="category.php?id='.MD5($row['id']).'"><i class="fas fa-edit" aria-hidden="true"></i></a>';
    //$data[] = $row;
    $data[] = $data_array;
    $i++;
    
	}	

	$json_data = array(
		"draw"            => intval( $params['draw'] ),   
		"recordsTotal"    => intval( $totalRecords ),  
		"recordsFiltered" => intval($totalRecords),
		"data"            => $data
	);

	echo json_encode($json_data);
}
if(isset($_POST['action']) && $_POST['action'] == "add-category"){
  $category_name = (isset($_POST['category_name']) && $_POST['category_name'] != "") ? $_POST['category_name'] : "";
  $status = (isset($_POST['status']) && $_POST['status'] != "") ? $_POST['status'] : "1";
  $add_query = 'INSERT INTO categories SET name= "'.$category_name.'",status = "'.$status.'",created_date = "'.date('Y-m-d H:i:s').'"';
  $result = mysqli_query($conn, $add_query);
  if($result){
      $res['status'] = '1';
      $res['message'] = "Category Add Sucussfully.";
      $res['data'] = new arrayObject();
      echo json_encode($res);
      return false;
  }else{
      $res['status'] = '0';
      $res['message'] = "Category Add Problem.";
      $res['data'] = new arrayObject();
      echo json_encode($res);
      return false;
  }
}
if(isset($_POST['action']) && $_POST['action'] == "update-category"){
  $category_name = (isset($_POST['category_name']) && $_POST['category_name'] != "") ? $_POST['category_name'] : "";
  $status = (isset($_POST['status']) && $_POST['status'] != "") ? $_POST['status'] : "0";
  $update_id = (isset($_POST['update_id']) && $_POST['update_id'] != "") ? $_POST['update_id'] : "";
  $add_query = 'UPDATE categories SET name= "'.$category_name.'",status = "'.$status.'",modified_date = "'.date('Y-m-d H:i:s').'" WHERE MD5(id) ="'.$update_id.'" ';
  $result = mysqli_query($conn, $add_query);
  if($result){
      $res['status'] = '1';
      $res['message'] = "Category Edit Sucussfully.";
      $res['data'] = new arrayObject();
      echo json_encode($res);
      return false;
  }else{
      $res['status'] = '0';
      $res['message'] = "Category Edit Problem.";
      $res['data'] = new arrayObject();
      echo json_encode($res);
      return false;
  }
}
if(isset($_POST['action']) && $_POST['action'] == "sub_category_list"){
  $params = $columns = $totalRecords = $data = array();

	$params = $_REQUEST;

	$columns = array(
		0 => 'name'
  );
  
  $where_condition = $sqlTot = $sqlRec = "";

	if( !empty($params['search']['value']) ) {
		//$where_condition .=	" WHERE ";
		$where_condition .= " AND name LIKE '%".$params['search']['value']."%' ";
  }
  $sql_query = " SELECT * FROM categories WHERE category_id != '0' AND status = '1'";
	$sqlTot .= $sql_query;
	$sqlRec .= $sql_query;
	
	if(isset($where_condition) && $where_condition != '') {

		$sqlTot .= $where_condition;
		$sqlRec .= $where_condition;
	}

   $sqlRec .=  " ORDER BY ". $columns[$params['order'][0]['column']]."   ".$params['order'][0]['dir']."  LIMIT ".$params['start']." ,".$params['length']." ";
   
   $queryTot = mysqli_query($conn, $sqlTot) or die("Database Error:". mysqli_error($con));

	$totalRecords = mysqli_num_rows($queryTot);
	$queryRecords = mysqli_query($conn, $sqlRec) or die("Error to Get the Post details.");
  $i = 1;
	while( $row = mysqli_fetch_assoc($queryRecords) ) {
    //$row[] = "<a href=''>Edit</a>";
    //$data_array['id'] = $i;
    $data_array['id'] = (isset($row['id']) && $row['id'] != "") ? $row['id'] : "";
    $sql = 'SELECT * FROM `categories`WHERE id = "'.$row['category_id'].'"';
    $result = mysqli_query($conn, $sql);
    $category_data = mysqli_fetch_assoc($result);
    $data_array['category_name'] = (isset($category_data['name']) && $category_data['name'] != "") ? $category_data['name'] : "";
    $data_array['name'] = (isset($row['name']) && $row['name'] != "") ? $row['name'] : "";
    $data_array['created_date'] = (isset($row['created_date']) && $row['created_date'] != "") ? $row['created_date'] : "";
    $data_array['action'] = '<a href="sub_category.php?id='.MD5($row['id']).'"><i class="fas fa-edit" aria-hidden="true"></i></a>';
    //$data[] = $row;
    $data[] = $data_array;
    $i++;
	}	

	$json_data = array(
		"draw"            => intval( $params['draw'] ),   
		"recordsTotal"    => intval( $totalRecords ),  
		"recordsFiltered" => intval($totalRecords),
		"data"            => $data
	);

	echo json_encode($json_data);
}
if(isset($_POST['action']) && $_POST['action'] == "sub-category"){
  $category_id = (isset($_POST['category_id']) && $_POST['category_id'] != "") ? $_POST['category_id'] : "";
  $sub_category_name = (isset($_POST['sub_category_name']) && $_POST['sub_category_name'] != "") ? $_POST['sub_category_name'] : "";
  $status = (isset($_POST['status']) && $_POST['status'] != "") ? $_POST['status'] : "1";
  $add_query = 'INSERT INTO categories SET name= "'.$sub_category_name.'",category_id="'.$category_id.'",status = "'.$status.'",created_date = "'.date('Y-m-d H:i:s').'"';
  $result = mysqli_query($conn, $add_query);
  if($result){
      $res['status'] = '1';
      $res['message'] = "Sub Category Add Sucussfully.";
      $res['data'] = new arrayObject();
      echo json_encode($res);
      return false;
  }else{
      $res['status'] = '0';
      $res['message'] = "Sub Category Add Problem.";
      $res['data'] = new arrayObject();
      echo json_encode($res);
      return false;
  }
}
if(isset($_POST['action']) && $_POST['action'] == "update-sub-category"){
  $category_id = (isset($_POST['category_id']) && $_POST['category_id'] != "") ? $_POST['category_id'] : "";
  $sub_category_name = (isset($_POST['sub_category_name']) && $_POST['sub_category_name'] != "") ? $_POST['sub_category_name'] : "";
  $status = (isset($_POST['status']) && $_POST['status'] != "") ? $_POST['status'] : "0";
  $update_id = (isset($_POST['update_id']) && $_POST['update_id'] != "") ? $_POST['update_id'] : "";
  $add_query = 'UPDATE categories SET name= "'.$sub_category_name.'",category_id="'.$category_id.'",status = "'.$status.'",modified_date = "'.date('Y-m-d H:i:s').'" WHERE MD5(id) ="'.$update_id.'" ';
  $result = mysqli_query($conn, $add_query);
  if($result){
      $res['status'] = '1';
      $res['message'] = "Sub Category Edit Sucussfully.";
      $res['data'] = new arrayObject();
      echo json_encode($res);
      return false;
  }else{
      $res['status'] = '0';
      $res['message'] = "Sub Category Edit Problem.";
      $res['data'] = new arrayObject();
      echo json_encode($res);
      return false;
  }
}
if(isset($_POST['action']) && $_POST['action'] == "update_profile"){
  $phone = (isset($_POST['phone']) && $_POST['phone'] != "") ? $_POST['phone'] : "";
  $p_phone = (isset($_POST['p_phone']) && $_POST['p_phone'] != "") ? $_POST['p_phone'] : "";
  $add_query = 'UPDATE users SET phone= "'.$phone.'",parent_phone = "'.$p_phone.'",modified_date = "'.date('Y-m-d H:i:s').'" WHERE id ="'.$_SESSION['user_details']['id'].'" ';
  $result = mysqli_query($conn, $add_query);
  if($result){
    if(isset($_FILES["fileName"]["name"]) && $_FILES["fileName"]["name"] != ''){
      $path = 'upload/profile';
      if (!file_exists($path)) {
          mkdir($path, 0777, true);
          chmod($path, 0777);
      }
      $path_parts = pathinfo($_FILES["fileName"]["name"]);
      $imageName = time() . "." . $path_parts['extension'];
      $image_full_url = 'upload/profile/'.$imageName;
      move_uploaded_file($_FILES['fileName']['tmp_name'], "$path/$imageName");
      $add_query = 'UPDATE users SET image_name= "'.$imageName.'",image_url = "'.$image_full_url.'",modified_date = "'.date('Y-m-d H:i:s').'" WHERE id = "'.$_SESSION['user_details']['id'].'"';
      $result = mysqli_query($conn, $add_query);
      $_SESSION['user_details']['image_url'] = $image_full_url;
    }
    $res['status'] = '1';
    $res['message'] = "Profile Update Sucessfully.";
    $res['data'] = new arrayObject();
    echo json_encode($res);
    return false;

  }else{
      $res['status'] = '0';
      $res['message'] = "Profile Update Problem.";
      $res['data'] = new arrayObject();
      echo json_encode($res);
      return false;
  }

}
if(isset($_POST['action']) && $_POST['action'] == "update_user_details"){
  $first_name = (isset($_POST['first_name']) && $_POST['first_name'] != "") ? $_POST['first_name'] : "";
  $middle_name = (isset($_POST['middle_name']) && $_POST['middle_name'] != "") ? $_POST['middle_name'] : "";
  $last_name = (isset($_POST['last_name']) && $_POST['last_name'] != "") ? $_POST['last_name'] : "";
  $class_sr_no = (isset($_POST['class_sr_no']) && $_POST['class_sr_no'] != "") ? $_POST['class_sr_no'] : "";
  $class = (isset($_POST['class']) && $_POST['class'] != "") ? $_POST['class'] : "";
  $div = (isset($_POST['div']) && $_POST['div'] != "") ? $_POST['div'] : "";
  $house_id = (isset($_POST['house_id']) && $_POST['house_id'] != "") ? $_POST['house_id'] : "";
  $user_type_id = (isset($_POST['user_type_id']) && $_POST['user_type_id'] != "") ? $_POST['user_type_id'] : "";
  $email_id = (isset($_POST['email_id']) && $_POST['email_id'] != "") ? $_POST['email_id'] : "";
  $other_phone = (isset($_POST['other_phone']) && $_POST['other_phone'] != "") ? $_POST['other_phone'] : "";
  $gender = (isset($_POST['gender']) && $_POST['gender'] != "") ? $_POST['gender'] : "";
  $date = (isset($_POST['date']) && $_POST['date'] != "") ? date("Y-m-d", strtotime(str_replace('/', '-', $_POST['date']))) : "";
  $user_id = (isset($_POST['user_id']) && $_POST['user_id'] != "") ? $_POST['user_id'] : "";
  $status = (isset($_POST['status']) && $_POST['status'] != "" && $_POST['status'] == "0") ? "1" : "0";
  $phone = (isset($_POST['phone']) && $_POST['phone'] != "") ? $_POST['phone'] : "";
  $p_phone = (isset($_POST['parent_phone']) && $_POST['parent_phone'] != "") ? $_POST['parent_phone'] : "";
  $add_query = 'UPDATE users SET 
  first_name= "'.$first_name.'",
  middle_name= "'.$middle_name.'",
  last_name= "'.$last_name.'",
  class_sr_no= "'.$class_sr_no.'",
  class= "'.$class.'",
  `div`= "'.$div.'",
  gender= "'.$gender.'",
  house_id= "'.$house_id.'",
  user_type_id= "'.$user_type_id.'",
  dob= "'.$date.'",
  email_id= "'.$email_id.'",
  other_phone= "'.$other_phone.'",
  phone= "'.$phone.'",
  parent_phone = "'.$p_phone.'",
  modified_date = "'.date('Y-m-d H:i:s').'",
  status ="'.$status.'" 
  WHERE id ="'.$user_id.'" ';
  $result = mysqli_query($conn, $add_query);
  if($result){
    $res['status'] = '1';
    $res['message'] = "User Details Sucessfully.";
    $res['data'] = new arrayObject();
    echo json_encode($res);
    return false;

  }else{
      $res['status'] = '0';
      $res['message'] = "User Details Problem.";
      $res['data'] = new arrayObject();
      echo json_encode($res);
      return false;
  }

}
if(isset($_POST['action']) && $_POST['action'] == "event_list"){
  $params = $columns = $totalRecords = $data = array();

	$params = $_REQUEST;

	$columns = array(
		0 => 'title',
    1 => 'description',
    2 => 'event_date',
    3 => 'event_time',
    4 => 'registration_end_date'
  );
  
  $where_condition = $sqlTot = $sqlRec = "";

	if( !empty($params['search']['value']) ) {
		$where_condition .=	" WHERE ";
		$where_condition .= " name LIKE '%".$params['search']['value']."%' ";
  }
  $sql_query = " SELECT * FROM events";
	$sqlTot .= $sql_query;
	$sqlRec .= $sql_query;
	
	if(isset($where_condition) && $where_condition != '') {

		$sqlTot .= $where_condition;
		$sqlRec .= $where_condition;
	}

   $sqlRec .=  " ORDER BY ". $columns[$params['order'][0]['column']]."   ".$params['order'][0]['dir']."  LIMIT ".$params['start']." ,".$params['length']." ";
   
   $queryTot = mysqli_query($conn, $sqlTot) or die("Database Error:". mysqli_error($con));

	$totalRecords = mysqli_num_rows($queryTot);
	$queryRecords = mysqli_query($conn, $sqlRec) or die("Error to Get the Post details.");
  $i = 1;
	while( $row = mysqli_fetch_assoc($queryRecords) ) {
    $data_array['title'] = (isset($row['title']) && $row['title'] != "") ? $row['title'] : "";
    $data_array['description'] = (isset($row['description']) && $row['description'] != "") ? $row['description'] : "";
    $data_array['event_date'] = (isset($row['event_date']) && $row['event_date'] != "") ? date("d/m/Y", strtotime($row['event_date'])) : "";
    $data_array['event_time'] = (isset($row['event_time']) && $row['event_time'] != "") ? $row['event_time'] : "";
    $data_array['registration_end_date'] = (isset($row['registration_end_date']) && $row['registration_end_date'] != "") ? date("d/m/Y", strtotime($row['registration_end_date'])) : "";
    $data_array['action'] = '<a href="event.php?id='.MD5($row['id']).'"><i class="fas fa-edit" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;<a href="delete.php?id='.MD5($row['id']).'& action=event"><i class="fas fa-trash-alt"></i></a>';
    $data[] = $data_array;
    $i++;
    
	}	

	$json_data = array(
		"draw"            => intval( $params['draw'] ),   
		"recordsTotal"    => intval( $totalRecords ),  
		"recordsFiltered" => intval($totalRecords),
		"data"            => $data
	);

	echo json_encode($json_data);
}
if(isset($_POST['action']) && $_POST['action'] == "get_sub_category"){
  $data = array();
  $category_id = (isset($_POST['category_id']) && $_POST['category_id'] != "") ? $_POST['category_id'] : "";
  $sql = 'SELECT * FROM `categories` WHERE category_id = "'.$_POST['category_id'].'" AND category_id != "0" AND status = "1"';
  $result = mysqli_query($conn, $sql);
  while($sub_category_data = mysqli_fetch_assoc($result)){
    $sub_array[$sub_category_data['id']] = $sub_category_data['name'];
    $data = $sub_array;
  }
  echo json_encode($data);
  return false;
}
if(isset($_POST['action']) && $_POST['action'] == "add-event"){
  $event_name = (isset($_POST['event_name']) && $_POST['event_name'] != "") ? $_POST['event_name'] : "";
  $category_id = (isset($_POST['category_id']) && $_POST['category_id'] != "") ? $_POST['category_id'] : "";
  $sub_category_id = (isset($_POST['sub_category_id']) && $_POST['sub_category_id'] != "") ? $_POST['sub_category_id'] : "";
  
  $date = (isset($_POST['date']) && $_POST['date'] != "") ? date("Y-m-d", strtotime(str_replace('/', '-', $_POST['date']))) : "";
  $time = (isset($_POST['time']) && $_POST['time'] != "") ? date("H:i:s", strtotime($_POST['time'])) : "";
  $registeration_end_date = (isset($_POST['registeration_end_date']) && $_POST['registeration_end_date'] != "") ? date("Y-m-d", strtotime(str_replace('/', '-', $_POST['registeration_end_date']))) : "";
  $registeration_end_time = (isset($_POST['register_end_time']) && $_POST['register_end_time'] != "") ? date("H:i:s", strtotime($_POST['register_end_time'])) : "";
  $age = (isset($_POST['age']) && $_POST['age'] != "") ? $_POST['age'] : "";
  $grades = (isset($_POST['grades']) && $_POST['grades'] != "") ? $_POST['grades'] : "";
  $event_notes = (isset($_POST['event_notes']) && $_POST['event_notes'] != "") ? $_POST['event_notes'] : "";
  $internal_notes = (isset($_POST['internal_notes']) && $_POST['internal_notes'] != "") ? $_POST['internal_notes'] : "";
  $council_members = (isset($_POST['council_members']) && $_POST['council_members'] != "") ? $_POST['council_members'] : "";
  $user_id = $_SESSION['user_details']['id'];
  $maxsize    = 5242880;
  if(($_FILES['pdf_document']['size'][0] >= $maxsize)) {
    $res['status'] = "0";
    $res['message'] = "PDF Document file too large. File must be less than 5 MB";
    $res['data'] = new arrayObject();
    echo json_encode($res);
    return false;
  }
  if(($_FILES['waiver_from']['size'][0] >= $maxsize)) {
    $res['status'] = "0";
    $res['message'] = "Waiver from file too large. File must be less than 5 MB";
    $res['data'] = new arrayObject();
    echo json_encode($res);
    return false;
  }
  if(($_FILES['event_images']['size'] >= $maxsize)) {
    $res['status'] = "0";
    $res['message'] = "Event image too large. File must be less than 5 MB";
    $res['data'] = new arrayObject();
    echo json_encode($res);
    return false;
  }

  $check_event = check_event($requestField['sub_category_id'],$requestField['age_gender']);
  if(!empty($check_event)){
      $res['status'] = "0";
      $res['message'] = $check_event;
      $res['data'] = new arrayObject();
      echo json_encode($res);
      return false;
  }
  // Add Event Code
  $add_query = 'INSERT INTO events SET 
  title= "'.$event_name.'",
  description = "",
  category_id = "'.$category_id.'",
  sub_category_id = "'.$sub_category_id.'",
  event_date = "'.$date.'",
  event_time = "'.$time.'",
  event_date_time = "'.$date.' '.$time.'",
  registration_end_date = "'.$registeration_end_date.'",
  registration_end_time = "'.$registeration_end_time.'",
  registration_end_datetime = "'.$registeration_end_date.' '.$registeration_end_time.'",
  event_internal_notes = "'.$internal_notes.'",
  event_external_notes = "'.$event_notes.'",
  created_by = "'.$user_id.'",
  created_date = "'.date('Y-m-d H:i:s').'"
  ';
  $result = mysqli_query($conn, $add_query);
  if($result){
      $last_id = mysqli_insert_id($conn);
      // Add AGE & GENDER
      foreach($age as $key=>$value){
          $add_query = 'INSERT INTO event_age_gender_categories SET event_id= "'.$last_id.'",age_gender_id = "'.$value.'"';
          $result = mysqli_query($conn, $add_query);
      }

      // Add GRADE
      foreach($grades as $key=>$value){
          $add_query = 'INSERT INTO event_grade_categories SET event_id= "'.$last_id.'",grade_id = "'.$value.'"';
          $result = mysqli_query($conn, $add_query);
      }

      // Add Council Members
      foreach($council_members as $key=>$value){
          $add_query = 'INSERT INTO event_council_members SET event_id= "'.$last_id.'",council_member = "'.$value.'"';
          $result = mysqli_query($conn, $add_query);
      }

      // Add Images
      if(isset($_FILES["event_images"]["name"]) && $_FILES["event_images"]["name"] != ''){
          $path = 'upload/event_images';
          if (!file_exists($path)) {
              mkdir($path, 0777, true);
              chmod($path, 0777);
          }
          $path_parts = pathinfo($_FILES["event_images"]["name"]);
          $imageName = $path_parts['filename']."_".time() . "." . $path_parts['extension'];
          move_uploaded_file($_FILES['event_images']['tmp_name'], "$path/$imageName");
          $add_query = 'INSERT INTO event_images SET event_id= "'.$last_id.'",image_url = "'.$imageName.'",created_date = "'.date('Y-m-d H:i:s').'"';
          $result = mysqli_query($conn, $add_query);
      }
      // Add Document
      // PDF Document
      $pdf_count = count($_FILES["pdf_document"]["name"]);
      for ($i=0; $i < $pdf_count; $i++) { 
       
        if(isset($_FILES["pdf_document"]["name"][$i]) && $_FILES["pdf_document"]["name"][$i] != ''){
            $path = 'upload/event_document';
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
                chmod($path, 0777);
            }
            $path_parts = pathinfo($_FILES["pdf_document"]["name"][$i]);
            $imageName = $path_parts['filename']."_".time() . "." . $path_parts['extension'];
            move_uploaded_file($_FILES['pdf_document']['tmp_name'][$i], "$path/$imageName");
            $add_query = 'INSERT INTO event_documents SET event_id= "'.$last_id.'",file_name = "'.$imageName.'",type = "pdf_document"';
            $result = mysqli_query($conn, $add_query);
        }
      }
      // waiver from Document
      $waiver_count = count($_FILES["waiver_from"]["name"]);
      for ($i=0; $i < $waiver_count; $i++) { 
        if(isset($_FILES["waiver_from"]["name"][$i]) && $_FILES["waiver_from"]["name"][$i] != ''){
            $path = 'upload/event_document';
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
                chmod($path, 0777);
            }
            $path_parts = pathinfo($_FILES["waiver_from"]["name"][$i]);
            $imageName = $path_parts['filename']."_".time() . "." . $path_parts['extension'];
            move_uploaded_file($_FILES['waiver_from']['tmp_name'][$i], "$path/$imageName");
            $add_query = 'INSERT INTO event_documents SET event_id= "'.$last_id.'",file_name = "'.$imageName.'",type = "waiver_from"';
            $result = mysqli_query($conn, $add_query);
        }
      }

      // // All User send Notofication
      // $title = $event_name." has now been announced and will take part on ".date('l, d M Y', strtotime($date))." at ".date("g:i a", strtotime($time)).". Click here to sign up now!";
      // $message['title'] = 'DAIS+';
      // $message['sound'] = 'default';
      // // $message['body'] = "Event Date:".date("Y-m-d", strtotime($requestField['date']))." "."Event Time:".date("g:i a", strtotime($requestField['time']));
      // $message['body'] = $title;
      // $message['android_channel_id'] = '1';
      // $message['image'] = '';
      // $message['priority'] = 'high';
      // $message['badge'] = '1';
      // $message['colour'] = '#FF0000';
      // $message['channel'] = '';

      // // Notification Admin,Core,House 
      // $admin_notification_query = 'SELECT u.*,dt.device_token FROM `users` u INNER JOIN device_token dt ON dt.user_id = u.id WHERE u.user_type_id IN (1,2,3)';
      // $admin_notification_result = mysqli_query($conn, $admin_notification_query);
      // while($admin_notification_data = mysqli_fetch_assoc($admin_notification_result)){
      //     $token_array[] = $admin_notification_data['device_token'];
      // }

      // // Notification Admin,Core,House Stored
      // $stored_admin_notification_query = 'SELECT u.*,dt.device_token FROM `users` u LEFT JOIN device_token dt ON dt.user_id = u.id WHERE u.user_type_id IN (1,2,3) GROUP BY u.id';
      // $stored_admin_notification_result = mysqli_query($conn, $stored_admin_notification_query);
      // while($stored_admin_notification_data = mysqli_fetch_assoc($stored_admin_notification_result)){
      //     $nofication_query1 = 'INSERT INTO notification SET 
      //                 user_id= "'.$stored_admin_notification_data['id'].'",
      //                 event_id= "'.$last_id.'",
      //                 message = "'.$title.'",
      //                 notification_type = "event_notification",
      //                 created_by = "'.$_SESSION['user_details']['id'].'",
      //                 created_date = "'.date('Y-m-d H:i:s').'"
      //                 ';
      //     $nofication_result1 = mysqli_query($conn, $nofication_query1);
      // }

      // // Notification for student
      // $student_sql = 'SELECT dob,gender,class as grade FROM `users`WHERE id = "'.$_SESSION['user_details']['id'].'"';
      // $result = mysqli_query($conn, $student_sql);
      // $user_details = mysqli_fetch_assoc($result);
      // $user_year = date("Y", strtotime($user_details['dob']));
      
      // $token_sql = '(SELECT u.*,dt.device_token FROM `events` e
      // INNER JOIN event_age_gender_categories eagc ON e.id = eagc.event_id
      // INNER JOIN age_gender_categories agc ON agc.id = eagc.age_gender_id
      // INNER JOIN users u ON EXTRACT(YEAR FROM u.dob) = agc.start_year OR EXTRACT(YEAR FROM u.dob) = agc.end_year
      // LEFT JOIN device_token dt ON dt.user_id = u.id
      // WHERE e.id = "'.$last_id.'" AND u.user_type_id = "4" AND u.gender = agc.gender GROUP BY dt.id
      // )UNION ALL
      // (
      //     SELECT u.*,dt.device_token FROM `events` e
      //     INNER JOIN event_grade_categories egc ON egc.event_id = e.id
      //     INNER JOIN grades g ON g.id = egc.grade_id
      //     INNER JOIN users u ON u.class between g.start_grade and g.end_grade
      //     LEFT JOIN device_token dt ON dt.user_id = u.id
      //     WHERE e.id = "'.$last_id.'" AND u.user_type_id = "4" GROUP BY dt.id
      // ) ';
      // $token_result = mysqli_query($conn, $token_sql);
      // while($token_data = mysqli_fetch_assoc($token_result)){
      //     $token_array[] = $token_data['device_token'];
      //     //$successers = sendSingleNotification($token_data['device_token'], $message);
      // }
      // $successers = sendSingleNotification($token_array, $message);

      // // stored notification
      // $stored_sql = '(SELECT u.*,dt.device_token FROM `events` e
      // INNER JOIN event_age_gender_categories eagc ON e.id = eagc.event_id
      // INNER JOIN age_gender_categories agc ON agc.id = eagc.age_gender_id
      // INNER JOIN users u ON EXTRACT(YEAR FROM u.dob) = agc.start_year OR EXTRACT(YEAR FROM u.dob) = agc.end_year
      // LEFT JOIN device_token dt ON dt.user_id = u.id
      // WHERE e.id = "'.$last_id.'" AND u.user_type_id = "4" AND u.gender = agc.gender GROUP BY u.id
      // )UNION ALL
      // (
      //     SELECT u.*,dt.device_token FROM `events` e
      //     INNER JOIN event_grade_categories egc ON egc.event_id = e.id
      //     INNER JOIN grades g ON g.id = egc.grade_id
      //     INNER JOIN users u ON u.class between g.start_grade and g.end_grade
      //     LEFT JOIN device_token dt ON dt.user_id = u.id
      //     WHERE e.id = "'.$last_id.'" AND u.user_type_id = "4" GROUP BY u.id
      // ) ';
      // $stored_result = mysqli_query($conn, $stored_sql);
      // while($stored_data = mysqli_fetch_assoc($stored_result)){
      //     $nofication_query1_stored = 'INSERT INTO notification SET 
      //                 user_id= "'.$stored_data['id'].'",
      //                 event_id= "'.$last_id.'",
      //                 message = "'.$title.'",
      //                 notification_type = "event_notification",
      //                 created_by = "'.$_SESSION['user_details']['id'].'",
      //                 created_date = "'.date('Y-m-d H:i:s').'"
      //                 ';
      //     $nofication_query1_result = mysqli_query($conn, $nofication_query1_stored);
      // }

      // // All User send Notofication
      // $interested_title = "You showed interest in ".$event_name.", which has now been announced to take place on ".date('l, d M Y', strtotime($date))." at ".date("g:i a", strtotime($time)).". Click here to sign up now!";
      // $interested_message['title'] = 'DAIS+';
      // $interested_message['sound'] = 'default';
      // $interested_message['body'] = $interested_title;
      // $interested_message['android_channel_id'] = '1';
      // $interested_message['image'] = '';
      // $interested_message['priority'] = 'high';
      // $interested_message['badge'] = '1';
      // $interested_message['colour'] = '#FF0000';
      // $interested_message['channel'] = '';

      // // notification2 send 
      // $interested_token_sql = '(SELECT u.*,dt.device_token FROM `events` e
      // INNER JOIN event_age_gender_categories eagc ON e.id = eagc.event_id
      // INNER JOIN age_gender_categories agc ON agc.id = eagc.age_gender_id
      // INNER JOIN users u ON EXTRACT(YEAR FROM u.dob) = agc.start_year OR EXTRACT(YEAR FROM u.dob) = agc.end_year
      // LEFT JOIN device_token dt ON dt.user_id = u.id
      // INNER JOIN user_interested_categories uic ON u.id = uic.user_id
      // WHERE e.id = "'.$last_id.'" AND u.gender = agc.gender AND uic.category_id = "'.$category_id.'" GROUP BY dt.id
      // )UNION ALL
      // (
      //     SELECT u.*,dt.device_token FROM `events` e
      //     INNER JOIN event_grade_categories egc ON egc.event_id = e.id
      //     INNER JOIN grades g ON g.id = egc.grade_id
      //     INNER JOIN users u ON u.class between g.start_grade and g.end_grade
      //     LEFT JOIN device_token dt ON dt.user_id = u.id
      //     INNER JOIN user_interested_categories uic ON u.id = uic.user_id
      //     WHERE e.id = "'.$last_id.'" AND uic.category_id = "'.$category_id.'" GROUP BY dt.id
      // ) ';
      
      // // $interested_token_sql = 'SELECT u.*,dt.device_token FROM `user_interested_categories` uic INNER JOIN users u ON uic.user_id = u.id INNER JOIN device_token dt ON dt.user_id = u.id WHERE uic.category_id = "'.$requestField['category_id'].'" GROUP BY dt.id';
      // $interested_token_result = mysqli_query($conn, $interested_token_sql);
      // while($interested_token_data = mysqli_fetch_assoc($interested_token_result)){
      //     $interested_token_array[] = $interested_token_data['device_token'];
      //     //$successers = sendSingleNotification($token_data['device_token'], $message);
      // }
      // $successers = sendSingleNotification($interested_token_array, $interested_message);

      // // stored notification2 
      // $interested_token_stored_sql = '(SELECT u.*,dt.device_token FROM `events` e
      // INNER JOIN event_age_gender_categories eagc ON e.id = eagc.event_id
      // INNER JOIN age_gender_categories agc ON agc.id = eagc.age_gender_id
      // INNER JOIN users u ON EXTRACT(YEAR FROM u.dob) = agc.start_year OR EXTRACT(YEAR FROM u.dob) = agc.end_year
      // LEFT JOIN device_token dt ON dt.user_id = u.id
      // INNER JOIN user_interested_categories uic ON u.id = uic.user_id
      // WHERE e.id = "'.$last_id.'" AND u.gender = agc.gender AND uic.category_id = "'.$category_id.'" GROUP BY u.id
      // )UNION ALL
      // (
      //     SELECT u.*,dt.device_token FROM `events` e
      //     INNER JOIN event_grade_categories egc ON egc.event_id = e.id
      //     INNER JOIN grades g ON g.id = egc.grade_id
      //     INNER JOIN users u ON u.class between g.start_grade and g.end_grade
      //     LEFT JOIN device_token dt ON dt.user_id = u.id
      //     INNER JOIN user_interested_categories uic ON u.id = uic.user_id
      //     WHERE e.id = "'.$last_id.'" AND uic.category_id = "'.$category_id.'" GROUP BY u.id
      // ) ';
      // // $interested_token_stored_sql = 'SELECT u.*,dt.device_token FROM `user_interested_categories` uic INNER JOIN users u ON uic.user_id = u.id INNER JOIN device_token dt ON dt.user_id = u.id WHERE uic.category_id = "'.$requestField['category_id'].'" GROUP BY u.id';
      // $interested_token_stored_result = mysqli_query($conn, $interested_token_stored_sql);
      // while($interested_token_stored_data = mysqli_fetch_assoc($interested_token_stored_result)){
      //     $nofication_query2 = 'INSERT INTO notification SET 
      //                 user_id= "'.$interested_token_stored_data['id'].'",
      //                 event_id= "'.$last_id.'",
      //                 message = "'.$interested_title.'",
      //                 notification_type = "event_notification",
      //                 created_by = "'.$_SESSION['user_details']['id'].'",
      //                 created_date = "'.date('Y-m-d H:i:s').'"
      //                 ';
      //     $nofication_result2 = mysqli_query($conn, $nofication_query2);
      // }

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
if(isset($_POST['action']) && $_POST['action'] == "pdf_delete"){
  $id = (isset($_POST['id']) && $_POST['id'] != "") ? $_POST['id'] : "";
  $sql = 'DELETE FROM `event_documents` WHERE id = "'.$id.'"';
  $result = mysqli_query($conn, $sql);
  if($result){
    $res['status'] = '1';
    $res['message'] = "Event PDF Remove Sucussfully";
    $res['data'] = new arrayObject();
    echo json_encode($res);
    return false;
  }else{
    $res['status'] = '0';
    $res['message'] = "Event PDF Remove Problem Try again";
    $res['data'] = new arrayObject();
    echo json_encode($res);
    return false;
  }
}
if(isset($_POST['action']) && $_POST['action'] == "image_delete"){
  $id = (isset($_POST['id']) && $_POST['id'] != "") ? $_POST['id'] : "";
  $sql = 'DELETE FROM `event_images` WHERE id = "'.$id.'"';
  $result = mysqli_query($conn, $sql);
  if($result){
    $res['status'] = '1';
    $res['message'] = "event_images Remove Sucussfully";
    $res['data'] = new arrayObject();
    echo json_encode($res);
    return false;
  }else{
    $res['status'] = '0';
    $res['message'] = "event_images Remove Problem Try again";
    $res['data'] = new arrayObject();
    echo json_encode($res);
    return false;
  }
}
if(isset($_POST['action']) && $_POST['action'] == "update-event"){
  
    $event_name = (isset($_POST['event_name']) && $_POST['event_name'] != "") ? $_POST['event_name'] : "";
    $category_id = (isset($_POST['category_id']) && $_POST['category_id'] != "") ? $_POST['category_id'] : "";
    $sub_category_id = (isset($_POST['sub_category_id']) && $_POST['sub_category_id'] != "") ? $_POST['sub_category_id'] : "";
    $date = (isset($_POST['date']) && $_POST['date'] != "") ? date("Y-m-d", strtotime(str_replace('/', '-', $_POST['date']))) : "";
    $time = (isset($_POST['time']) && $_POST['time'] != "") ? date("H:i:s", strtotime($_POST['time'])) : "";
    $registeration_end_date = (isset($_POST['registeration_end_date']) && $_POST['registeration_end_date'] != "") ? date("Y-m-d", strtotime(str_replace('/', '-', $_POST['registeration_end_date']))) : "";
    $registeration_end_time = (isset($_POST['register_end_time']) && $_POST['register_end_time'] != "") ? date("H:i:s", strtotime($_POST['register_end_time'])) : "";
    $age = (isset($_POST['age']) && $_POST['age'] != "") ? $_POST['age'] : "";
    $grades = (isset($_POST['grades']) && $_POST['grades'] != "") ? $_POST['grades'] : "";
    $event_notes = (isset($_POST['event_notes']) && $_POST['event_notes'] != "") ? $_POST['event_notes'] : "";
    $internal_notes = (isset($_POST['internal_notes']) && $_POST['internal_notes'] != "") ? $_POST['internal_notes'] : "";
    $council_members = (isset($_POST['council_members']) && $_POST['council_members'] != "") ? $_POST['council_members'] : "";
    $event_id = (isset($_POST['event_id']) && $_POST['event_id'] != "") ? $_POST['event_id'] : "";
    $user_id = $_SESSION['user_details']['id'];
    $maxsize    = 5242880;
    if(($_FILES['pdf_document']['size'][0] >= $maxsize)) {
      $res['status'] = "0";
      $res['message'] = "PDF Document file too large. File must be less than 5 MB";
      $res['data'] = new arrayObject();
      echo json_encode($res);
      return false;
    }
    if(($_FILES['waiver_from']['size'][0] >= $maxsize)) {
      $res['status'] = "0";
      $res['message'] = "Waiver from file too large. File must be less than 5 MB";
      $res['data'] = new arrayObject();
      echo json_encode($res);
      return false;
    }
    if(($_FILES['event_images']['size'] >= $maxsize)) {
      $res['status'] = "0";
      $res['message'] = "Event image too large. File must be less than 5 MB";
      $res['data'] = new arrayObject();
      echo json_encode($res);
      return false;
    }

     $update_query = 'UPDATE events SET 
      title= "'.$event_name.'",
      description = "",
      category_id = "'.$category_id.'",
      sub_category_id = "'.$sub_category_id.'",
      event_date = "'.$date.'",
      event_time = "'.$time.'",
      event_date_time = "'.$date.' '.$time.'",
      registration_end_date = "'.$registeration_end_date.'",
      registration_end_time = "'.$registeration_end_time.'",
      registration_end_datetime = "'.$registeration_end_date.' '.$registeration_end_time.'",
      event_internal_notes = "'.$internal_notes.'",
      event_external_notes = "'.$event_notes.'",
      last_update_by = "'.$user_id.'",
      modified_date = "'.date('Y-m-d H:i:s').'"
      WHERE id = "'.$event_id.'"
      ';
      //print_r($update_query);exit;
      $result = mysqli_query($conn, $update_query);
      if($result){
          $last_id = mysqli_insert_id($conn);
          // Add AGE & GENDER
          // $delete_age_gender = 'DELETE FROM `event_age_gender_categories` WHERE event_id ="'.$event_id.'"';
          // $age_gender_result = mysqli_query($conn, $delete_age_gender);
          // foreach($age as $key=>$value){
          //     $add_query = 'INSERT INTO event_age_gender_categories SET event_id= "'.$event_id.'",age_gender_id = "'.$value.'"';
          //     $result = mysqli_query($conn, $add_query);
          // }
    
          // Add GRADE
          // $delete_age_gender = 'DELETE FROM `event_grade_categories` WHERE event_id ="'.$event_id.'"';
          // $age_gender_result = mysqli_query($conn, $delete_age_gender);
          // foreach($grades as $key=>$value){
          //     $add_query = 'INSERT INTO event_grade_categories SET event_id= "'.$event_id.'",grade_id = "'.$value.'"';
          //     $result = mysqli_query($conn, $add_query);
          // }
    
          // Add Council Members
          $delete_council = 'DELETE FROM `event_council_members` WHERE event_id ="'.$event_id.'"';
          $delete_council_result = mysqli_query($conn, $delete_council);
          foreach($council_members as $key=>$value){
              $add_query = 'INSERT INTO event_council_members SET event_id= "'.$event_id.'",council_member = "'.$value.'"';
              $result = mysqli_query($conn, $add_query);
          }
    
          // Add Images
          if(isset($_FILES["event_images"]["name"]) && $_FILES["event_images"]["name"] != ''){
              $path = 'upload/event_images';
              if (!file_exists($path)) {
                  mkdir($path, 0777, true);
                  chmod($path, 0777);
              }
              $path_parts = pathinfo($_FILES["event_images"]["name"]);

              $imageName = $path_parts['filename']."_".time(). "." . $path_parts['extension'];
              move_uploaded_file($_FILES['event_images']['tmp_name'], "$path/$imageName");
              $add_query = 'INSERT INTO event_images SET event_id= "'.$event_id.'",image_url = "'.$imageName.'",created_date = "'.date('Y-m-d H:i:s').'"';
              $result = mysqli_query($conn, $add_query);
          }
          // Add Document
          // PDF Document
          $pdf_count = count($_FILES["pdf_document"]["name"]);
          for ($i=0; $i < $pdf_count; $i++) { 
           
            if(isset($_FILES["pdf_document"]["name"][$i]) && $_FILES["pdf_document"]["name"][$i] != ''){
                $path = 'upload/event_document';
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                    chmod($path, 0777);
                }
                $path_parts = pathinfo($_FILES["pdf_document"]["name"][$i]);
                $imageName = $path_parts['filename']."_".time() . "." . $path_parts['extension'];
                move_uploaded_file($_FILES['pdf_document']['tmp_name'][$i], "$path/$imageName");
                $add_query = 'INSERT INTO event_documents SET event_id= "'.$event_id.'",file_name = "'.$imageName.'",type = "pdf_document"';
                $result = mysqli_query($conn, $add_query);
            }
          }
          // waiver from Document
          $waiver_count = count($_FILES["waiver_from"]["name"]);
          for ($i=0; $i < $waiver_count; $i++) { 
            if(isset($_FILES["waiver_from"]["name"][$i]) && $_FILES["waiver_from"]["name"][$i] != ''){
                $path = 'upload/event_document';
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                    chmod($path, 0777);
                }
                $path_parts = pathinfo($_FILES["waiver_from"]["name"][$i]);
                $imageName = $path_parts['filename']."_".time() . "." . $path_parts['extension'];
                move_uploaded_file($_FILES['waiver_from']['tmp_name'][$i], "$path/$imageName");
                $add_query = 'INSERT INTO event_documents SET event_id= "'.$event_id.'",file_name = "'.$imageName.'",type = "waiver_from"';
                $result = mysqli_query($conn, $add_query);
            }
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
if(isset($_POST['action']) && $_POST['action'] == "interested-category"){
  $category_id = (isset($_POST['category_id']) && $_POST['category_id'] != "") ? $_POST['category_id'] : "";
  if(!empty($category_id)){
    $delete_age_gender = 'DELETE FROM `user_interested_categories` WHERE user_id ="'.$_SESSION['user_details']['id'].'"';
    $age_gender_result = mysqli_query($conn, $delete_age_gender);
    foreach($category_id as $key=>$value){
        $add_query = 'INSERT INTO user_interested_categories SET user_id= "'.$_SESSION['user_details']['id'].'",category_id = "'.$value.'",created_date = "'.date('Y-m-d H:i:s').'"';
        $result = mysqli_query($conn, $add_query);
    }
    $res['status'] = '1';
    $res['message'] = "Updated event interested category";
    $res['data'] = new arrayObject();
    echo json_encode($res);
    return false;

  }else{
    $res['status'] = '0';
    $res['message'] = "Category Get Problem Try again";
    $res['data'] = new arrayObject();
    echo json_encode($res);
    return false;
  }
}
if(isset($_POST['action']) && $_POST['action'] == "event_past_list"){
  $params = $columns = $totalRecords = $data = array();

	$params = $_REQUEST;

	$columns = array(
		0 => 'title',
    1 => 'description',
    2 => 'event_date',
    3 => 'event_time',
    4 => 'registration_end_date'
  );
  
  $where_condition = $sqlTot = $sqlRec = "";

	if( !empty($params['search']['value']) ) {
		$where_condition .=	" WHERE ";
		$where_condition .= " e.title LIKE '%".$params['search']['value']."%' ";
  }
  
  $sql = 'SELECT u.id as user_ID,h.name as house_name,h.id as house_id,ut.name as user_type_name FROM `users` u LEFT JOIN houses h ON u.house_id = h.id LEFT JOIN user_type ut ON ut.id = u.user_type_id WHERE u.id = "'.$_SESSION['user_details']['id'].'"';
  $result = mysqli_query($conn, $sql);
  $user_data = mysqli_fetch_assoc($result);
  $current_date_time = date('Y-m-d H:i:s');
  if($user_data['user_type_name'] == "Administrator" || $user_data['user_type_name'] == "Core Captain"){
      // Total Count Get 
      $count_sql = 'SELECT COUNT(*) as count FROM `events` WHERE event_date_time < "'.$current_date_time.'"';
      $count_result = mysqli_query($conn, $count_sql);
      $count_data = mysqli_fetch_assoc($count_result);
      
      $sql_query = 'SELECT e.*,es.status as is_signup,es.reject_by,es.approved_by FROM `events` e LEFT JOIN event_signups es ON e.id = es.event_id AND es.user_id = "'.$_SESSION['user_details']['id'].'" WHERE e.event_date_time < "'.$current_date_time.'" ORDER BY `e`.`event_date_time';

  }else if($user_data['user_type_name'] == "House Captain"){
      // Total Count Get 
      $count_sql = 'SELECT COUNT(*) as count FROM `events` WHERE event_date_time < "'.$current_date_time.'"';
      $count_result = mysqli_query($conn, $count_sql);
      $count_data = mysqli_fetch_assoc($count_result);
      
      $sql_query = 'SELECT e.*,es.status as is_signup,es.reject_by,es.approved_by FROM `events` e LEFT JOIN event_signups es ON e.id = es.event_id AND es.user_id = "'.$_SESSION['user_details']['id'].'" WHERE e.event_date_time < "'.$current_date_time.'" ORDER BY `e`.`event_date_time';
  }else{
    $student_sql = 'SELECT dob,gender FROM `users`WHERE id = "'.$requestField['user_id'].'"';
    $result = mysqli_query($conn, $student_sql);
    $user_details = mysqli_fetch_assoc($result);

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
    LEFT JOIN event_signups es ON e.id = es.event_id AND es.user_id = "'.$_SESSION['user_details']['id'].'"
    WHERE event_date_time < "'.$current_date_time.'" AND (agc.start_year = "'.$user_year.'" OR agc.end_year = "'.$user_year.'") AND agc.gender = "'.$user_details['gender'].'" GROUP BY e.id 
    )UNION ALL
    (
    SELECT e.*,es.status as is_signup,es.reject_by,es.approved_by FROM events e
    INNER JOIN event_grade_categories egc ON egc.event_id = e.id
    INNER JOIN grades g ON g.id = egc.grade_id
    LEFT JOIN event_signups es ON e.id = es.event_id
    WHERE event_date_time > "'.$current_date_time.'" AND "'.$user_details['grade'].'" between g.start_grade and g.end_grade GROUP BY e.id) ORDER BY event_date_time ASC ';
    $count_result = mysqli_query($conn, $count_sql);
    $count_data = mysqli_num_rows($count_result);
    $count_data_value = $count_data;


    $sql_query = '(SELECT e.*,es.status as is_signup,es.reject_by,es.approved_by FROM events e
    INNER JOIN event_age_gender_categories eagc ON e.id = eagc.event_id
    INNER JOIN age_gender_categories agc ON agc.id = eagc.age_gender_id
    LEFT JOIN event_signups es ON e.id = es.event_id AND es.user_id = "'.$_SESSION['user_details']['id'].'"
    WHERE event_date_time < "'.$current_date_time.'" AND (agc.start_year = "'.$user_year.'" OR agc.end_year = "'.$user_year.'") AND agc.gender = "'.$user_details['gender'].'" GROUP BY e.id 
    )UNION ALL
    (
    SELECT e.*,es.status as is_signup,es.reject_by,es.approved_by FROM events e
    INNER JOIN event_grade_categories egc ON egc.event_id = e.id
    INNER JOIN grades g ON g.id = egc.grade_id
    LEFT JOIN event_signups es ON e.id = es.event_id
    WHERE event_date_time > "'.$current_date_time.'" AND "'.$user_details['grade'].'" between g.start_grade and g.end_grade GROUP BY e.id) ORDER BY event_date_time ASC';
  }
  
  //$sql_query = " SELECT * FROM events";
	$sqlTot .= $sql_query;
	$sqlRec .= $sql_query;
	
	if(isset($where_condition) && $where_condition != '') {

		$sqlTot .= $where_condition;
		$sqlRec .= $where_condition;
	}
  
   $sqlRec .=  " ORDER BY ". $columns[$params['order'][0]['column']]."   ".$params['order'][0]['dir']."  LIMIT ".$params['start']." ,".$params['length']." ";
   $queryTot = mysqli_query($conn, $sqlTot) or die("Database Error:". mysqli_error($con));

	$totalRecords = mysqli_num_rows($queryTot);
	$queryRecords = mysqli_query($conn, $sqlRec) or die("Error to Get the Post details.");
  $i = 1;
	while( $row = mysqli_fetch_assoc($queryRecords) ) {
    $data_array['title'] = (isset($row['title']) && $row['title'] != "") ? $row['title'] : "";
    $data_array['description'] = (isset($row['description']) && $row['description'] != "") ? $row['description'] : "";
    $data_array['event_date'] = (isset($row['event_date']) && $row['event_date'] != "") ? date("d/m/Y", strtotime($row['event_date'])) : "";
    $data_array['event_time'] = (isset($row['event_time']) && $row['event_time'] != "") ? $row['event_time'] : "";
    $data_array['registration_end_date'] = (isset($row['registration_end_date']) && $row['registration_end_date'] != "") ? date("d/m/Y", strtotime($row['registration_end_date'])) : "";
    $data_array['action'] = '<a href="event.php?id='.MD5($row['id']).'"><i class="fas fa-edit" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;<a href="delete.php?id='.MD5($row['id']).'& action=event"><i class="fas fa-trash-alt"></i></a>';
    $data[] = $data_array;
    $i++;
    
	}	

	$json_data = array(
		"draw"            => intval( $params['draw'] ),   
		"recordsTotal"    => intval( $totalRecords ),  
		"recordsFiltered" => intval($totalRecords),
		"data"            => $data
	);

	echo json_encode($json_data);
}
if(isset($_POST['action']) && $_POST['action'] == "event_upcomming_list"){
  $params = $columns = $totalRecords = $data = array();

	$params = $_REQUEST;

	$columns = array(
		0 => 'title',
    1 => 'description',
    2 => 'event_date',
    3 => 'event_time',
    4 => 'registration_end_date'
  );
  
  $where_condition = $sqlTot = $sqlRec = "";

	if( !empty($params['search']['value']) ) {
		$where_condition .=	" WHERE ";
		$where_condition .= " e.title LIKE '%".$params['search']['value']."%' ";
  }
  
  $sql = 'SELECT u.id as user_ID,h.name as house_name,h.id as house_id,ut.name as user_type_name FROM `users` u LEFT JOIN houses h ON u.house_id = h.id LEFT JOIN user_type ut ON ut.id = u.user_type_id WHERE u.id = "'.$_SESSION['user_details']['id'].'"';
  $result = mysqli_query($conn, $sql);
  $user_data = mysqli_fetch_assoc($result);
  $current_date_time = date('Y-m-d H:i:s');
  if($user_data['user_type_name'] == "Administrator" || $user_data['user_type_name'] == "Core Captain"){
      // Total Count Get 
      $count_sql = 'SELECT COUNT(*) as count FROM `events` WHERE event_date_time > "'.$current_date_time.'"';
      $count_result = mysqli_query($conn, $count_sql);
      $count_data = mysqli_fetch_assoc($count_result);
      
      $sql_query = 'SELECT e.*,es.status as is_signup,es.reject_by,es.approved_by FROM `events` e LEFT JOIN event_signups es ON e.id = es.event_id AND es.user_id = "'.$_SESSION['user_details']['id'].'" WHERE e.event_date_time > "'.$current_date_time.'" GROUP BY e.id ORDER BY `e`.`event_date_time`  ASC';

  }else if($user_data['user_type_name'] == "House Captain"){
      // Total Count Get 
      $count_sql = 'SELECT COUNT(*) as count FROM `events` WHERE event_date_time > "'.$current_date_time.'"';
      $count_result = mysqli_query($conn, $count_sql);
      $count_data = mysqli_fetch_assoc($count_result);
      
      $sql_query = 'SELECT e.*,es.status as is_signup,es.reject_by,es.approved_by FROM `events` e LEFT JOIN event_signups es ON e.id = es.event_id AND es.user_id = "'.$_SESSION['user_details']['id'].'" WHERE e.event_date_time > "'.$current_date_time.'" GROUP BY e.id ORDER BY `e`.`event_date_time`  ASC';
  }else{
    $student_sql = 'SELECT dob,gender,class as grade FROM `users`WHERE id = "'.$_SESSION['user_details']['id'].'"';
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
    LEFT JOIN event_signups es ON e.id = es.event_id AND es.user_id = "'.$_SESSION['user_details']['id'].'"
    WHERE event_date_time > "'.$current_date_time.'" AND (agc.start_year = "'.$user_year.'" OR agc.end_year = "'.$user_year.'") AND agc.gender = "'.$user_details['gender'].'" GROUP BY e.id 
    )UNION ALL
    (
    SELECT e.*,es.status as is_signup,es.reject_by,es.approved_by FROM events e
    INNER JOIN event_grade_categories egc ON egc.event_id = e.id
    INNER JOIN grades g ON g.id = egc.grade_id
    LEFT JOIN event_signups es ON e.id = es.event_id
    WHERE event_date_time > "'.$current_date_time.'" AND "'.$user_details['grade'].'" between g.start_grade and g.end_grade GROUP BY e.id) ORDER BY event_date_time ASC ';
    $count_result = mysqli_query($conn, $count_sql);
    $count_data = mysqli_num_rows($count_result);
    $count_data_value = $count_data;

    $sql_query = '(SELECT e.*,es.status as is_signup,es.reject_by,es.approved_by FROM events e
    INNER JOIN event_age_gender_categories eagc ON e.id = eagc.event_id
    INNER JOIN age_gender_categories agc ON agc.id = eagc.age_gender_id
    LEFT JOIN event_signups es ON e.id = es.event_id AND es.user_id = "'.$_SESSION['user_details']['id'].'"
    WHERE event_date_time > "'.$current_date_time.'" AND (agc.start_year = "'.$user_year.'" OR agc.end_year = "'.$user_year.'") AND agc.gender = "'.$user_details['gender'].'" GROUP BY e.id 
    )UNION ALL
    (
    SELECT e.*,es.status as is_signup,es.reject_by,es.approved_by FROM events e
    INNER JOIN event_grade_categories egc ON egc.event_id = e.id
    INNER JOIN grades g ON g.id = egc.grade_id
    LEFT JOIN event_signups es ON e.id = es.event_id
    WHERE event_date_time > "'.$current_date_time.'" AND "'.$user_details['grade'].'" between g.start_grade and g.end_grade GROUP BY e.id) ORDER BY event_date_time';
  }
  
  //$sql_query = " SELECT * FROM events";
	$sqlTot .= $sql_query;
	$sqlRec .= $sql_query;
	
	if(isset($where_condition) && $where_condition != '') {

		$sqlTot .= $where_condition;
		$sqlRec .= $where_condition;
	}
  
   $sqlRec .=  " ORDER BY ". $columns[$params['order'][0]['column']]."   ".$params['order'][0]['dir']."  LIMIT ".$params['start']." ,".$params['length']." ";
   $queryTot = mysqli_query($conn, $sqlTot) or die("Database Error:". mysqli_error($con));

	$totalRecords = mysqli_num_rows($queryTot);
	$queryRecords = mysqli_query($conn, $sqlRec) or die("Error to Get the Post details.");
  $i = 1;
	while( $row = mysqli_fetch_assoc($queryRecords) ) {
    $data_array['title'] = (isset($row['title']) && $row['title'] != "") ? $row['title'] : "";
    $data_array['description'] = (isset($row['description']) && $row['description'] != "") ? $row['description'] : "";
    $data_array['event_date'] = (isset($row['event_date']) && $row['event_date'] != "") ? date("d/m/Y", strtotime($row['event_date'])) : "";
    $data_array['event_time'] = (isset($row['event_time']) && $row['event_time'] != "") ? $row['event_time'] : "";
    $data_array['registration_end_date'] = (isset($row['registration_end_date']) && $row['registration_end_date'] != "") ? date("d/m/Y", strtotime($row['registration_end_date'])) : "";
    $data_array['action'] = '<a href="event.php?id='.MD5($row['id']).'"><i class="fas fa-edit" aria-hidden="true"></i></a>';
    $data[] = $data_array;
    $i++;
    
	}	

	$json_data = array(
		"draw"            => intval( $params['draw'] ),   
		"recordsTotal"    => intval( $totalRecords ),  
		"recordsFiltered" => intval($totalRecords),
		"data"            => $data
	);

	echo json_encode($json_data);
}
if(isset($_POST['action']) && $_POST['action'] == "reason_leaving"){
  $update_query = 'UPDATE event_signups SET 
  status= "2",
  reject_by = "'.$_SESSION['user_details']['id'].'",
  reject_commit = "'.$_POST['message'].'",
  modified_date = "'.date('Y-m-d H:i:s').'"
  WHERE event_id = "'.$_POST['event_id'].'" AND user_id = "'.$_SESSION['user_details']['id'].'"
  ';
  $result = mysqli_query($conn, $update_query);
  if($result){
      // Changes 
      // get event name
      $event_name_query = 'SELECT * FROM `events` WHERE id="'.$event_id.'"';
      $event_name_result = mysqli_query($conn, $event_name_query);
      $event_name_data = mysqli_fetch_assoc($event_name_result);

      $title3 = $_SESSION['user_details']['first_name']." has requested to withdraw their name from ".$event_name_data['title'].". Click here to approve or deny this request.";
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
      $get_all_admin_query = 'SELECT dt.device_token,u.id FROM `users` u LEFT JOIN device_token dt ON dt.user_id = u.id WHERE user_type_id IN (1,2) GROUP BY dt.id';
      $get_all_admin_result = mysqli_query($conn, $get_all_admin_query);
      while($get_all_admin_data = mysqli_fetch_assoc($get_all_admin_result)){
          $user_notification[] = $get_all_admin_data['device_token'];
      }
      //add user array 
      $get_all_admin_user_query = 'SELECT dt.device_token,u.id FROM `users` u LEFT JOIN device_token dt ON dt.user_id = u.id WHERE user_type_id IN (1,2) GROUP BY u.id';
      $get_all_admin_user_result = mysqli_query($conn, $get_all_admin_user_query);
      while($get_all_admin_user_data = mysqli_fetch_assoc($get_all_admin_user_result)){
          $notification_query3_user = 'INSERT INTO notification SET 
                  user_id= "'.$get_all_admin_user_data['id'].'",
                  event_id= "'.$_POST['event_id'].'",
                  message = "'.$title3.'",
                  notification_type = "reject_notification",
                  created_by = "'.$_SESSION['user_details']['id'].'",
                  created_date = "'.date('Y-m-d H:i:s').'"
                  ';
          $notification_result3 = mysqli_query($conn, $notification_query3_user);
      }

      // house wise house captain
      // get token array
      
      $house_notification_query = 'SELECT dt.device_token,u.id FROM `users` u LEFT JOIN device_token dt ON dt.user_id = u.id WHERE house_id = "'.$_SESSION['user_details']['house_id'].'" AND user_type_id = "3" GROUP BY dt.id';
      $house_notification_result = mysqli_query($conn, $house_notification_query);
      while($house_notification_data = mysqli_fetch_assoc($house_notification_result)){
          $user_notification[] = $house_notification_data['device_token'];
      }

      // user array
      $house_notification_user_query = 'SELECT dt.device_token,u.id FROM `users` u LEFT JOIN device_token dt ON dt.user_id = u.id WHERE house_id = "'.$_SESSION['user_details']['house_id'].'" AND user_type_id = "3" GROUP BY u.id';
      $house_notification_user_result = mysqli_query($conn, $house_notification_user_query);
      while($house_notification_user_data = mysqli_fetch_assoc($house_notification_user_result)){
          $notification_query3_user = 'INSERT INTO notification SET 
                  user_id= "'.$house_notification_user_data['id'].'",
                  event_id= "'.$_POST['event_id'].'",
                  message = "'.$title3.'",
                  notification_type = "reject_notification",
                  created_by = "'.$_SESSION['user_details']['id'].'",
                  created_date = "'.date('Y-m-d H:i:s').'"
                  ';
          $notification_result3 = mysqli_query($conn, $notification_query3_user);
      }
      $successers = sendSingleNotification($user_notification, $message3);
      $res['status'] = '1';
      $res['message'] = "User has been declined successfully";
      $res['data'] = new arrayObject();
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
if(isset($_POST['action']) && $_POST['action'] == "event_details_list"){
  $internal_notes = (isset($_POST['internal_notes']) && $_POST['internal_notes'] != "") ? $_POST['internal_notes'] : "";
  $external_notes = (isset($_POST['external_notes']) && $_POST['external_notes'] != "") ? $_POST['external_notes'] : "";
  $council_members = (isset($_POST['council_members']) && $_POST['council_members'] != "") ? $_POST['council_members'] : "";
  $event_id = (isset($_POST['event_id']) && $_POST['event_id'] != "") ? $_POST['event_id'] : "";
  $user_id = $_SESSION['user_details']['id'];
  $update_query = 'UPDATE events SET 
      event_external_notes = "'.$external_notes.'",
      event_internal_notes = "'.$internal_notes.'",
      last_update_by = "'.$user_id.'",
      modified_date = "'.date('Y-m-d H:i:s').'"
      WHERE id = "'.$event_id.'"
      ';
    
  $result = mysqli_query($conn, $update_query);
  if($result){
    //Add Council Members
    $delete_council = 'DELETE FROM `event_council_members` WHERE event_id ="'.$event_id.'"';
    $delete_council_result = mysqli_query($conn, $delete_council);
    foreach($council_members as $key=>$value){
        $add_query = 'INSERT INTO event_council_members SET event_id= "'.$event_id.'",council_member = "'.$value.'"';
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
if(isset($_POST['action']) && $_POST['action'] == "approve_status"){
  $event_id = (isset($_POST['event_id']) && $_POST['event_id'] != "") ? $_POST['event_id'] : "";
  $user_id = (isset($_POST['user_id']) && $_POST['user_id'] != "") ? $_POST['user_id'] : "";

  $update_query = 'UPDATE event_signups SET 
                    status= "1",
                    approved_by = "'.$_SESSION['user_details']['id'].'",
                    reject_by = "",
                    modified_date = "'.date('Y-m-d H:i:s').'"
                    WHERE event_id = "'.$event_id.'" AND user_id = "'.$user_id.'"
                    ';
  $result = mysqli_query($conn, $update_query);
  if($result){
      // Changes 
      // get event name
      $event_name_query = 'SELECT * FROM `events` WHERE id="'.$event_id.'"';
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
      $house_notification_query = 'SELECT dt.device_token,u.id FROM `users` u LEFT JOIN device_token dt ON dt.user_id = u.id WHERE u.id="'.$user_id.'" GROUP BY dt.id';
      $house_notification_result = mysqli_query($conn, $house_notification_query);
      while($house_notification_data = mysqli_fetch_assoc($house_notification_result)){
          $user_notification[] = $house_notification_data['device_token'];
      }

      // user array
      $house_notification_user_query = 'SELECT dt.device_token,u.id FROM `users` u LEFT JOIN device_token dt ON dt.user_id = u.id WHERE u.id="'.$user_id.'" GROUP BY u.id';
      $house_notification_user_result = mysqli_query($conn, $house_notification_user_query);
      while($house_notification_user_data = mysqli_fetch_assoc($house_notification_user_result)){
          $notification_query3_user = 'INSERT INTO notification SET 
                  user_id= "'.$house_notification_user_data['id'].'",
                  event_id= "'.$event_id.'",
                  message = "'.$title3.'",
                  notification_type = "approv_notification",
                  created_by = "'.$_SESSION['user_details']['id'].'",
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
if(isset($_POST['action']) && $_POST['action'] == "reject_status"){
  $event_id = (isset($_POST['event_id']) && $_POST['event_id'] != "") ? $_POST['event_id'] : "";
  $user_id = (isset($_POST['user_id']) && $_POST['user_id'] != "") ? $_POST['user_id'] : "";

  $update_query = 'UPDATE event_signups SET 
            status= "2",
            reject_by = "'.$_SESSION['user_details']['id'].'",
            reject_commit = "",
            approved_by = "",
            modified_date = "'.date('Y-m-d H:i:s').'"
            WHERE event_id = "'.$event_id.'" AND user_id = "'.$user_id.'"
            ';
  $result = mysqli_query($conn, $update_query);
  if($result){
      $res['status'] = '1';
      if($_SESSION['user_details']['id'] == $user_id ){
        $res['message'] = "You have withdrawn your name from this event";
        // Changes 
        // get event name
        $event_name_query = 'SELECT * FROM `events` WHERE id="'.$event_id.'"';
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
      }else{
          // Changes 
          // get event name
          
          $event_name_query = 'SELECT * FROM `events` WHERE id="'.$event_id.'"';
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
          $house_notification_query = 'SELECT dt.device_token,u.id FROM `users` u LEFT JOIN device_token dt ON dt.user_id = u.id WHERE u.id="'.$user_id.'" GROUP BY dt.id';
          $house_notification_result = mysqli_query($conn, $house_notification_query);
          while($house_notification_data = mysqli_fetch_assoc($house_notification_result)){
              $user_notification[] = $house_notification_data['device_token'];
          }

          // user array
          $house_notification_user_query = 'SELECT dt.device_token,u.id FROM `users` u LEFT JOIN device_token dt ON dt.user_id = u.id WHERE u.id="'.$user_id.'" GROUP BY u.id';
          $house_notification_user_result = mysqli_query($conn, $house_notification_user_query);
          while($house_notification_user_data = mysqli_fetch_assoc($house_notification_user_result)){
              $notification_query3_user = 'INSERT INTO notification SET 
                      user_id= "'.$house_notification_user_data['id'].'",
                      event_id= "'.$event_id.'",
                      message = "'.$title3.'",
                      notification_type = "approv_notification",
                      created_by = "'.$_SESSION['user_details']['id'].'",
                      created_date = "'.date('Y-m-d H:i:s').'"
                      ';
              $notification_result3 = mysqli_query($conn, $notification_query3_user);
          }


          $successers = sendSingleNotification($user_notification, $message3);
          $res['message'] = "Event request has been declined successfully.";
      }
      $res['data'] = new arrayObject();
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

if(isset($_POST['action']) && $_POST['action'] == "signup_data"){
  $event_id = (isset($_POST['event_id']) && $_POST['event_id'] != "") ? $_POST['event_id'] : "";
  $sql = 'SELECT u.id as user_ID,h.name as house_name,h.id as house_id,ut.name as user_type_name FROM `users` u LEFT JOIN houses h ON u.house_id = h.id LEFT JOIN user_type ut ON ut.id = u.user_type_id WHERE u.id = "'.$_SESSION['user_details']['id'].'"';
  $result = mysqli_query($conn, $sql);
  $user_data = mysqli_fetch_assoc($result);
  if($user_data['user_type_name'] == "Administrator" || $user_data['user_type_name'] == "Core Captain"){
      $sql = 'SELECT u.*,h.name as house_name,ut.name as user_type_name FROM `event_signups` es 
      LEFT JOIN users u ON es.user_id = u.id
      LEFT JOIN houses h ON u.house_id = h.id
      LEFT JOIN user_type ut ON ut.id = u.user_type_id
      WHERE es.event_id = "'.$event_id.'" AND es.status = "1"';
  }else{
      $sql = 'SELECT u.*,h.name as house_name,ut.name as user_type_name FROM `event_signups` es 
      LEFT JOIN users u ON es.user_id = u.id
      LEFT JOIN houses h ON u.house_id = h.id
      LEFT JOIN user_type ut ON ut.id = u.user_type_id
      WHERE es.event_id = "'.$event_id.'" AND h.id = "'.$user_data['house_id'].'" AND es.status = "1"';
  }

  $result = mysqli_query($conn, $sql);
            
    
  $csv = "Sr. No.,Class Sr. No.,Class(20-21),Div,Last Name,First Name,Middle Name,Gender,Teams ID,Date of Birth,Phone Number,House,Access Level,Parent Phone Number\n";//Column headers
  $i = 1;
  
  while($user_data = mysqli_fetch_assoc($result)){
      $csv.= $i.','.$user_data['class_sr_no'].','.$user_data['class'].','.$user_data['div'].','.$user_data['last_name'].','.$user_data['first_name'].','.$user_data['middle_name'].','.$user_data['gender'].','.$user_data['email_id'].','.date("d/m/Y", strtotime($user_data['dob'])).','.$user_data['phone'].','.$user_data['house_name'].','.$user_data['user_type_name'].','.$user_data['parent_phone']."\n"; //Append data to csv
      $i++;
  }
  $path = 'upload/signups_data/';
  if (!file_exists($path)) {
      mkdir($path, 0777, true);
      chmod($path, 0777);
  }
  ob_start();
  $event_sql = 'SELECT * FROM `events`WHERE id = "'.$event_id.'"';
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

if(isset($_POST['action']) && $_POST['action'] == "notification_status"){
  $notification_id = (isset($_POST['notification_id']) && $_POST['notification_id'] != "") ? $_POST['notification_id'] : "";
  $qry = 'UPDATE `notification` SET is_read = "1" WHERE id ="'.$notification_id.'"';
  $result = mysqli_query($conn, $qry);
  if($result){
    $res['status'] = '1';
    $res['message'] = "Notification Update";
    $res['data'] = new arrayObject();
    echo json_encode($res);
    return false;
  }else{
    $res['status'] = '0';
    $res['message'] = "Notification Update Problem.";
    $res['data'] = new arrayObject();
    echo json_encode($res);
    return false;
  }
}
if(isset($_POST['action']) && $_POST['action'] == "email_check"){
  $email = (isset($_POST['email']) && $_POST['email'] != "") ? $_POST['email'] : "";
  if(!empty($email)){
    $select_qry = 'SELECT * FROM `users` WHERE email_id = "'.$email.'"';
    $select_result = mysqli_query($conn, $select_qry);
    $rowcount=mysqli_num_rows($select_result);
    if($rowcount > 0){
      $res['status'] = '1';
      $res['message'] = "Duplicate Email";
      $res['data'] = array();
      echo json_encode($res);
      return false;
    }else{
      $res['status'] = '0';
      $res['message'] = "Problem";
      $res['data'] = array();
      echo json_encode($res);
      return false;
    }
  }
}

if(isset($_POST['action']) && $_POST['action'] == "delete_event"){
  $delete_event_id = (isset($_POST['delete_event_id']) && $_POST['delete_event_id'] != "") ? $_POST['delete_event_id'] : "";
  if(!empty($delete_event_id)){
    $qry = 'UPDATE `events` SET is_delete = "1" WHERE md5(id) ="'.$delete_event_id.'"';
    $result = mysqli_query($conn, $qry);
    if($result){
      $res['status'] = '1';
      $res['message'] = "Event Delete sucessfully";
      $res['data'] = new arrayObject();
      echo json_encode($res);
      return false;
    }else{
      $res['status'] = '0';
      $res['message'] = "Event Delete Problem.";
      $res['data'] = new arrayObject();
      echo json_encode($res);
      return false;
    }
  }
}

?>