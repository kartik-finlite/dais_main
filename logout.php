<?php 
session_start();
if($_SESSION['user_details']){
  session_unset();
  session_destroy();
  session_start();
  $_SESSION['FLASH_SUCCESS_FLAG'] = "Logout into Admin panel.";
  header('Location: index.php');
}
?>