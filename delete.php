<?php 
include 'include/conn.php';
include 'include/function.php';
if(isset($_GET['action']) && $_GET['action'] == "categories"){
  $id = (isset($_GET['id']) && $_GET['id'] != "") ? $_GET['id'] : "";
  $check = category_delete_check($id);
  $check_count = count($check);
  if($check_count > 0){
    $_SESSION['FLASH_ERROR_FLAG'] = "Category Reference exists Can't Delete it ";
  }else{
    $sql = 'DELETE FROM `categories` WHERE MD5(id) = "'.$id.'"';
    $result = mysqli_query($conn, $sql);
    if($result){
      $_SESSION['FLASH_SUCCESS_FLAG'] = "Category Delete Sucssfully.";
    }else{
      $_SESSION['FLASH_ERROR_FLAG'] = "Category Delete Failed.";
    }
  }
  header("Location: category_list.php");
}
if(isset($_GET['action']) && $_GET['action'] == "sub_categories"){
  $id = (isset($_GET['id']) && $_GET['id'] != "") ? $_GET['id'] : "";
  $check = category_delete_check($id);
  $check_count = count($check);
  if($check_count > 0){
    $_SESSION['FLASH_ERROR_FLAG'] = "Sub Category Reference exists Can't Delete it ";
  }else{
    $sql = 'DELETE FROM `categories` WHERE MD5(id) = "'.$id.'"';
    $result = mysqli_query($conn, $sql);
    if($result){
      $_SESSION['FLASH_SUCCESS_FLAG'] = "Sub Category Delete Sucssfully.";
    }else{
      $_SESSION['FLASH_ERROR_FLAG'] = "Sub Category Delete Failed.";
    }
  }
  header("Location: sub_category_list.php");
}
?>