<?php
include 'include/session_check.php';
$title = "Event List";
$pageTitle = "Event List";
include 'include/header.php';
include 'include/header-navigation.php';
include 'include/navigation.php';

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
<!-- LOCAL PAGE LAVEL JS -->
<script src="assets/web/local_assets/js/event_list.js"></script>

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
          <?php
          if (isset($_SESSION['FLASH_ERROR_FLAG']) && $_SESSION['FLASH_ERROR_FLAG'] != '') {
          ?>
            <div class="alert alert-danger alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              <h5><i class="icon fas fa-check"></i> Alert!</h5>
              <?php echo $_SESSION['FLASH_ERROR_FLAG']; ?>
              <?php unset ($_SESSION['FLASH_ERROR_FLAG']); ?>
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
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <div class="row">
                <div class="col-12 col-md-6 col-xs-12">
                  <h3 class="card-title">Event Listing</h3>
                </div>
                <div class="col-12 col-md-6 col-xs-12 text-right">
                  <button type="button" onclick="return getPage('event.php');" class="pull-right btn btn-primary">Add</button>
                </div>
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="tbl-event-listing" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Event Date</th>
                    <th>Event Time</th>
                    <th>Registration End Date</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->


<?php 
include 'include/footer.php';
?>