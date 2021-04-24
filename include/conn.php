<?php
//error_reporting(0);
session_start();
$conn = new mysqli("localhost","daisplus_dais","dais123!@#","daisplus_dais");

// Check connection
if ($conn -> connect_errno) {
  echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
  exit();
}
define('BASE_URL','https://www.daisplus.com/');
defined('DEFAULT_IMAGE')      OR define('DEFAULT_IMAGE','assets/admin/local_assets/images/no-image.png');

/*URL*/
define('NOTIFICATION_BASE_URL', "https://fcm.googleapis.com/fcm/send");

/*SERVER KEY*/
define('NOTIFICATION_SERVER_KEY', "AAAALh_kmGk:APA91bHBsMM5MMAkLPjdvraOlewa2N-sk9kD6Noy7wa21O0I-zCQFwF_EIBbC0_Az_y4_vxYsKCyhfBmx1xNJ5fW0vM6YPt9BJxNCyOw944P3llwP7ozyGaNhLPZTpIiu7hE9yUpoFEL");
?>