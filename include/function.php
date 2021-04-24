<?php 
include 'include/class.phpmailer.php';
include 'include/class.smtp.php';
function sendMail($email = "", $subject = "", $message = ""){
    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->Host = "mail.daisplus.com";  /* SMTP server */
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = "tls";  // prefix for secure protocol to connect to the server
    $mail->Port = 587;
    $mail->Username = "info@daisplus.com";  /* Username */
    $mail->Password = "info123!@#";    /*         * Password* */

    $mail->From = "info@daisplus.com";    /* From address required */
    $mail->FromName = "DAIS+";
    $mail->AddAddress(trim($email));
    $mail->IsHTML(true);
    $mail->Subject = $subject;
    $mail->Body = $message;
    return $mail->Send();
}
function category_delete_check($id = null){
    global $conn;
    $data = array();
    // sub category check
    $sub_query = 'SELECT * FROM `categories`WHERE MD5(category_id) = "'.$id.'"';
    $sub_result = mysqli_query($conn, $sub_query);
    $count = mysqli_num_rows($sub_result);
    if($count > 0){
        $data[] = "Sub Category Table";
    }

    // Event Check
    $event_query = 'SELECT * FROM `events`WHERE MD5(category_id) = "'.$id.'"';
    $event_result = mysqli_query($conn, $event_query);
    $event_count = mysqli_num_rows($event_result);
    if($event_count > 0){
        $data[] = "Event Table";
    }

    // User interested Check 
    $user_query = 'SELECT * FROM `user_interested_categories`WHERE MD5(category_id) = "'.$id.'"';
    $user_result = mysqli_query($conn, $user_query);
    $user_count = mysqli_num_rows($user_result);
    if($user_count > 0){
        $data[] = "User interested Table";
    }
    return $data;
}

function sendSingleNotification($deviceToken = array(), $message = array()) {
    global $conn;
	$data['condition'] = array();

	$tokens = (isset($deviceTokens) && count($deviceTokens) > 0) ? $deviceTokens : array();

	//API URL of FCM
	$url = NOTIFICATION_BASE_URL;

	$serverKey = NOTIFICATION_SERVER_KEY;

	

	$msg = array(
		'icon' => (isset($message['icon']) && $message['icon'] != '') ? $message['icon'] : '',
		'alert' => (isset($message['alert']) && $message['alert'] != '') ? $message['alert'] : '',
		'title' => (isset($message['title']) && $message['title'] != '') ? $message['title'] : '',
		'body' => (isset($message['body']) && $message['body'] != '') ? $message['body'] : '',
		'sound' => (isset($message['sound']) && $message['sound'] != '') ? $message['sound'] : 'default',
		'android_channel_id' => (isset($message['android_channel_id']) && $message['android_channel_id'] != '') ? $message['android_channel_id'] : '1',
		'vibrate' => (isset($message['vibrate']) && $message['vibrate'] === true) ? true : false,
		'largeIcon' => (isset($message['largeIcon']) && $message['largeIcon'] != '') ? $message['largeIcon'] : '',
		'smallIcon' => (isset($message['smallIcon']) && $message['smallIcon'] != '') ? $message['smallIcon'] : '',
		'image' => (isset($message['image']) && $message['image'] != '') ? $message['image'] : '',
		'priority' => (isset($message['priority']) && $message['priority'] != '') ? $message['priority'] : 'normal',
		'badge' => (isset($message['badge']) && $message['badge'] != '') ? $message['badge'] : '1',
		'colour' => (isset($message['colour']) && $message['colour'] != '') ? $message['colour'] : '#FF0000',
		'channel' => (isset($message['channel']) && $message['channel'] != '') ? $message['channel'] : '',
		'backgroundImage' => (isset($message['backgroundImage']) && $message['backgroundImage'] != '') ? $message['backgroundImage'] : '',
		'backgroundImageTextColour' => (isset($message['backgroundImageTextColour']) && $message['backgroundImageTextColour'] != '') ? $message['backgroundImageTextColour'] : '#FFFFFF',
		'style' => array(
			'type' => (isset($message['style']['type']) && $message['style']['type'] != '') ? $message['style']['type'] : '',
			'text' => (isset($message['style']['text']) && $message['style']['text'] != '') ? $message['style']['text'] : '',
			'lines' => (isset($message['style']['lines']) && count($message['style']['lines']) > 0) ? $message['style']['lines'] : array(),
			'image' => (isset($message['style']['image']) && $message['style']['image'] != '') ? $message['style']['image'] : '',
		),
		'groupIcon' => (isset($message['groupIcon']) && $message['groupIcon'] != '') ? $message['groupIcon'] : '',
		'groupKey' => (isset($message['groupKey']) && $message['groupKey'] != '') ? $message['groupKey'] : 'groupKey',
		'groupTitle' => (isset($message['groupTitle']) && $message['groupTitle'] != '') ? $message['groupTitle'] : 'You have %d notifications',
		'groupSummary' => (isset($message['groupSummary']) && $message['groupSummary'] != '') ? $message['groupSummary'] : 'Message displayed at bottom of notifications',
		'category' => (isset($message['category']) && $message['category'] != '') ? $message['category'] : 'INVITE_CATEGORY',
		'action' => (isset($message['action']) && $message['action'] != '') ? $message['action'] : 'dashboard_activity'
	);

	$headers = array(
		'Authorization: key=' . $serverKey,
		'Content-Type: application/json'
	);

	// $fields = array
	// 	(
	// 	'to' => $deviceToken,
	// 	"mutable_content" => true,
	// 	'notification' => $msg,
	// 	"data" => array(
	// 		"body" => "Body of Your Notification in Data",
	// 		"title" => "Title of Your Notification in Title",
	// 		"key_1" => "Value for key_1",
	// 		"key_2" => "Value for key_2"
	// 	),
	// 	'priority' => 'high'
	// );
	$fields = array
                (
                'registration_ids' => $deviceToken,
                "mutable_content" => true,
                'notification' => $msg,
                'priority' => 'high'
            );



	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
	$result = curl_exec($ch);
	curl_close($ch);
	return json_decode($result);
}

function sendSingleNotification1($deviceToken = "", $message = array()) {
    global $conn;
	$data['condition'] = array();

	$tokens = (isset($deviceTokens) && count($deviceTokens) > 0) ? $deviceTokens : array();

	//API URL of FCM
	$url = NOTIFICATION_BASE_URL;

	$serverKey = NOTIFICATION_SERVER_KEY;

	

	$msg = array(
		'icon' => (isset($message['icon']) && $message['icon'] != '') ? $message['icon'] : '',
		'alert' => (isset($message['alert']) && $message['alert'] != '') ? $message['alert'] : '',
		'title' => (isset($message['title']) && $message['title'] != '') ? $message['title'] : '',
		'body' => (isset($message['body']) && $message['body'] != '') ? $message['body'] : '',
		'sound' => (isset($message['sound']) && $message['sound'] != '') ? $message['sound'] : 'default',
		'android_channel_id' => (isset($message['android_channel_id']) && $message['android_channel_id'] != '') ? $message['android_channel_id'] : '1',
		'vibrate' => (isset($message['vibrate']) && $message['vibrate'] === true) ? true : false,
		'largeIcon' => (isset($message['largeIcon']) && $message['largeIcon'] != '') ? $message['largeIcon'] : '',
		'smallIcon' => (isset($message['smallIcon']) && $message['smallIcon'] != '') ? $message['smallIcon'] : '',
		'image' => (isset($message['image']) && $message['image'] != '') ? $message['image'] : '',
		'priority' => (isset($message['priority']) && $message['priority'] != '') ? $message['priority'] : 'normal',
		'badge' => (isset($message['badge']) && $message['badge'] != '') ? $message['badge'] : '1',
		'colour' => (isset($message['colour']) && $message['colour'] != '') ? $message['colour'] : '#FF0000',
		'channel' => (isset($message['channel']) && $message['channel'] != '') ? $message['channel'] : '',
		'backgroundImage' => (isset($message['backgroundImage']) && $message['backgroundImage'] != '') ? $message['backgroundImage'] : '',
		'backgroundImageTextColour' => (isset($message['backgroundImageTextColour']) && $message['backgroundImageTextColour'] != '') ? $message['backgroundImageTextColour'] : '#FFFFFF',
		'style' => array(
			'type' => (isset($message['style']['type']) && $message['style']['type'] != '') ? $message['style']['type'] : '',
			'text' => (isset($message['style']['text']) && $message['style']['text'] != '') ? $message['style']['text'] : '',
			'lines' => (isset($message['style']['lines']) && count($message['style']['lines']) > 0) ? $message['style']['lines'] : array(),
			'image' => (isset($message['style']['image']) && $message['style']['image'] != '') ? $message['style']['image'] : '',
		),
		'groupIcon' => (isset($message['groupIcon']) && $message['groupIcon'] != '') ? $message['groupIcon'] : '',
		'groupKey' => (isset($message['groupKey']) && $message['groupKey'] != '') ? $message['groupKey'] : 'groupKey',
		'groupTitle' => (isset($message['groupTitle']) && $message['groupTitle'] != '') ? $message['groupTitle'] : 'You have %d notifications',
		'groupSummary' => (isset($message['groupSummary']) && $message['groupSummary'] != '') ? $message['groupSummary'] : 'Message displayed at bottom of notifications',
		'category' => (isset($message['category']) && $message['category'] != '') ? $message['category'] : 'INVITE_CATEGORY',
		'action' => (isset($message['action']) && $message['action'] != '') ? $message['action'] : 'dashboard_activity'
	);

	$headers = array(
		'Authorization: key=' . $serverKey,
		'Content-Type: application/json'
	);

	$fields = array
		(
		'to' => $deviceToken,
		"mutable_content" => true,
		'notification' => $msg,
		"data" => array(
			"body" => "Body of Your Notification in Data",
			"title" => "Title of Your Notification in Title",
			"key_1" => "Value for key_1",
			"key_2" => "Value for key_2"
		),
		'priority' => 'high'
	);
	// $fields = array
    //             (
    //             'registration_ids' => $deviceToken,
    //             "mutable_content" => true,
    //             'notification' => $msg,
    //             'priority' => 'high'
    //         );



	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
	$result = curl_exec($ch);
	curl_close($ch);
	return json_decode($result);
}
function check_event($cat_id = "",$age_gender = ""){
	global $conn;
	$status = "";
	$age_gender_query = 'SELECT * FROM `age_gender_categories` WHERE id = "'.$age_gender.'"';
    $age_gender_result = mysqli_query($conn, $age_gender_query);
    $age_gender_data = mysqli_fetch_assoc($age_gender_result);
	//50m run (only for under 12 girls and boys)
	if($cat_id == "21"){
		if($age_gender == "1" || $age_gender == "3"){
			$status = "";
		}else{
			$status = "";
			$status = "50m run event only allowed For under 12 girls and boys";
		}
	}
	// 400m run (not for under 12 girls or boys)
	if($cat_id == "25"){
		if($age_gender == "1" || $age_gender == "3"){
			$status = "";
			$status = "400m run event not allowed For under 12 girls and boys";
		}
	}


	//600m run (only for under 14, under 16 and under 19 girls)
	if($cat_id == "26"){

		if($age_gender == "4" || $age_gender == "6" || $age_gender == "2" || $age_gender == "5" ){
			$status = "";
		}else{
			$status = "";
			$status = "600m run event not allowed For ".$age_gender_data['title'];
		}
	}
	//800m run (only for under 14, under 16 and under 19 boys)
	if($cat_id == "27"){
		if($age_gender == "4" || $age_gender == "6" || $age_gender == "7" || $age_gender == "8" ){
			$status = "";
		}else{
			$status = "";
			$status = "800m run event not allowed For ".$age_gender_data['title'];
		}
	}



	if($cat_id == "32"){
		if($age_gender == "1" || $age_gender == "3"){
			$status = "";
			$status = "Discuss throw event not allowed For under 12 girls & boys";
		}
	}
	return $status;
}

function limit_event_check($user_id = "",$event_id = ""){
	global $conn;
	$current_date_time = date('Y-m-d H:i:s');
	$check_event_query = 'SELECT category_id FROM `events` WHERE MD5(id) = "'.$event_id.'"';
	$check_event_result = mysqli_query($conn, $check_event_query);
	$check_event_data = mysqli_fetch_assoc($check_event_result);

	if($check_event_data['category_id'] == "8" || $check_event_data['category_id'] == "17"){

		$student_sql = 'SELECT dob,gender,class as grade FROM `users`WHERE id = "'.$user_id.'"';
		$result = mysqli_query($conn, $student_sql);
		$user_details = mysqli_fetch_assoc($result);
		$user_year = date("Y", strtotime($user_details['dob']));

		$user_data_get_query = 'SELECT agc.* FROM `users` u LEFT JOIN age_gender_categories agc ON (agc.start_year = "'.$user_year.'" OR agc.end_year = "'.$user_year.'") WHERE u.id = "'.$user_id.'" GROUP BY u.id';
		$user_data_get_result = mysqli_query($conn, $user_data_get_query);
		$user_data_get_data = mysqli_fetch_assoc($user_data_get_result);

		$error_message = "";
		// Track Events Limit Check 
		$track_event = 'SELECT COUNT(*) as count FROM `event_signups` es LEFT JOIN users u ON u.id = es.user_id LEFT JOIN events e ON es.event_id = e.id LEFT JOIN categories c ON e.category_id = c.id WHERE u.id = "'.$user_id.'" AND c.id = "8" AND e.event_date_time > "'.$current_date_time.'"';
		$track_event_result = mysqli_query($conn, $track_event);
		$track_event_data = mysqli_fetch_assoc($track_event_result);
		if($user_data_get_data['age'] == '12'){
			
			if($track_event_data['count'] >= '2'){
				return $error_message = "You can not sign up more then 2 track event.";
			}
		}else{
			if($track_event_data['count'] >= '3'){
				return $error_message = "You can not sign up more then 3 track event.";
			}
		}

		// Field Events Limit Check
		$field_event = 'SELECT COUNT(*) as count FROM `event_signups` es LEFT JOIN users u ON u.id = es.user_id LEFT JOIN events e ON es.event_id = e.id LEFT JOIN categories c ON e.category_id = c.id WHERE u.id = "'.$user_id.'" AND c.id = "17" AND e.event_date_time > "'.$current_date_time.'"';
		$field_event_result = mysqli_query($conn, $field_event);
		$field_event_data = mysqli_fetch_assoc($field_event_result);
		if($field_event_data['count'] >= '2'){
			return $error_message = "You can not sign up more then 2 field event.";
		}

		return $error_message;
	}else{
		$error_message = "";
		return $error_message;
	}

}

?>