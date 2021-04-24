<?php
include 'include/session_check.php';
if(isset($_GET['id']) && $_GET['id'] != ''){
  $title = "Update Sub Category";
}else{
  $title = "Add Sub Category";
}
// $pageTitle = "Sub Category";
include 'include/header.php';
include 'include/header-navigation.php';
include 'include/navigation.php';
if(isset($_GET['id']) && $_GET['id'] != ''){
  $sql = 'SELECT * FROM `categories`WHERE MD5(id) = "'.$_GET['id'].'"';
  $result = mysqli_query($conn, $sql);
  $data = mysqli_fetch_assoc($result);
}

?>
<style>
  .btn
  {
    padding-left:2.5rem;
    padding-right:2.5rem;
    text-transform:uppercase;
  }
  .image.p-image img {
    height: 2.1rem;
}
</style>
<script src="assets/web/local_assets/js/sub-category.js"></script>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <!-- <h1 class="m-0 text-dark"><?php //echo (isset($pageTitle) && $pageTitle != null) ? $pageTitle : ''; ?></h1> -->
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <?php
            if (isset($mainBreadcumbLinks) && count($mainBreadcumbLinks) > 0) {
              foreach ($mainBreadcumbLinks as $index => $value) {
                if (isset($value['url']) && $value['url'] != null) {
            ?>
                  <li class="breadcrumb-item"><a href="javascript:void();" onclick="return getPage('<?php echo $value['url']; ?>');"><?php echo (isset($value['key']) && $value['key'] != null) ? $value['key'] : ''; ?></a></li>
                <?php
                }
                ?>
            <?php
              }
            }
            ?>
            <li class="breadcrumb-item active"><?php echo (isset($subBreadcumbLinks['key']) && $subBreadcumbLinks['key'] != null) ? $subBreadcumbLinks['key'] : ''; ?></li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
      <div class="row">
        <div calss="col-12 col-sm-12 col-md-12">

          <?php
          if (isset($_SESSION['FLASH_SUCCESS_FLAG']) && $_SESSION['FLASH_SUCCESS_FLAG'] != '') {
          ?>
            <div class="alert alert-success alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              <h5><i class="icon fas fa-check"></i> Alert!</h5>
              <?php echo $_SESSION['FLASH_SUCCESS_FLAG']; ?>
              <?php unset ($_SESSION['FLASH_SUCCESS_FLAG']); ?>
            </div>
          <?php
          }
          ?>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- jquery validation -->
          <div class="card card-primary">
            <div class="card-header">
              <?php 
              if(isset($_GET['id']) && $_GET['id'] != ''){
                ?>
              <h3 class="card-title mt-2">Update Sub Category</h3>
                <?php
              }else{
                ?>
                <h3 class="card-title mt-2">Sub Category</h3>
                <?php
              }
              ?>
              
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form id="add_sub_category_details" name="add_sub_category_details">
              <div class="card-body">
              <div class="row">
                  <div class="col-12 col-sm-12 col-md-3">
                    <div id="form-group-category_name" class="form-group">
                      <label for="category_name">Category Name&nbsp;<span class="is-required">*</span></label>
                        <select class="custom-select" name="category_id" id="category_id">
                          <option>Select Category</option>
                          <?php 
                          $category_query = 'SELECT * FROM `categories`WHERE category_id = "0" AND status = "1"';
                          $category_result = mysqli_query($conn, $category_query);
                          while($category_data = mysqli_fetch_assoc($category_result)){
                          ?>
                          <option <?php echo (isset($data['category_id']) && $data['category_id'] != "" && $data['category_id'] == $category_data['id']) ? "selected" : ""; ?> value="<?php echo $category_data['id']; ?>"><?php echo $category_data['name']; ?></option>
                          <?php } ?>
                        </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12 col-sm-12 col-md-3">
                    <div id="form-group-sub_category_name" class="form-group">
                      <label for="sub_category_name">Sub Category Name&nbsp;<span class="is-required">*</span></label>
                      <input type="text" name="sub_category_name" class="form-control" id="sub_category_name" placeholder="Please enter sub category name" value="<?php echo (isset($data['name']) && $data['name'] != "") ? $data['name'] : ""; ?>">
                      <small id="sub_category_name-error" class="form-text text-muted"></small>
                    </div>
                  </div>
                </div>

                
                <div class="col-12 col-sm-12 col-md-6">
                  <div id="form-group-category_name" class="form-group">
                  <?php 
                  if(isset($_GET['id']) && $_GET['id'] != ''){
                  ?>
                    <input type="checkbox" name="status" <?php echo (isset($data['status']) && $data['status'] != "" && $data['status'] == '1') ? "checked" : ""; ?> data-bootstrap-switch data-off-color="danger" data-on-color="success" value="<?php echo (isset($data['status']) && $data['status'] != "" && $data['status'] == '1') ? "1" : "0"; ?>">
                  <?php }else{ ?>
                    <!--<input type="checkbox" name="status" checked data-bootstrap-switch data-off-color="danger" data-on-color="success" value="1">-->
                  <?php } ?>
                  </div>
                </div>

              </div>

              <!-- /.card-body -->
              <div class="card-footer">
                <?php 
                if(isset($_GET['id']) && $_GET['id'] != ''){
                  ?>
                  <input type="hidden" name = "update_id" id="update_id" value="<?php echo $_GET['id']; ?>" />
                <button type="submit" id="update_category_button" name="btn-admin-change-credentials" onclick="return sub_update_validate();" class="btn btn-primary">Update</button>
                  <?php
                }else{
                  ?>
                <button type="submit" id="add_category_button" name="btn-admin-change-credentials" onclick="return validate_sub_category();" class="btn btn-primary">SUBMIT</button>
                  <?php
                }
                ?>
                
              </div>
            </form>
          </div>
          <!-- /.card -->
        </div>
        <!--/.col (left) -->
        <!-- right column -->
        <div class="col-md-6">

        </div>
        <!--/.col (right) -->
      </div>
      <!-- /.row -->
    </div>
    <!--/. container-fluid -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->


<?php 
include 'include/footer.php';
?>
<script>
$("input[data-bootstrap-switch]").each(function(){
  $(this).bootstrapSwitch('state', $(this).prop('checked'));
});
</script>