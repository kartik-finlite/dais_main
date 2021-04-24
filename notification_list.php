<?php
include 'include/session_check.php';
$title = "Notification List";
$pageTitle = "Notification List";
include 'include/header.php';
include 'include/header-navigation.php';
include 'include/navigation.php';


$sql = 'SELECT u.id as user_ID,h.name as house_name,h.id as house_id,ut.name as user_type_name FROM `users` u LEFT JOIN houses h ON u.house_id = h.id LEFT JOIN user_type ut ON ut.id = u.user_type_id WHERE u.id = "' . $_SESSION['user_details']['id'] . '"';
$result = mysqli_query($conn, $sql);
$user_data = mysqli_fetch_assoc($result);

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark"><?php echo (isset($pageTitle) && $pageTitle != null) ? $pageTitle : ''; ?></h1>
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
                  <h3 class="card-title">Notification Listing</h3>
                </div>
                <div class="col-12 col-md-6 col-xs-12 text-right">
                </div>
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="tbl-celebrities-listing" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Message</th>
                  </tr>
                </thead>
                <tbody>
                <?php 
                $i= 1;
                $notification_sql = 'SELECT n.*,es.status as is_signup,e.title FROM `notification` n
                LEFT JOIN event_signups es ON es.user_id = n.user_id AND es.user_id = "'.$_SESSION['user_details']['id'].'" LEFT JOIN events e ON n.event_id = e.id WHERE n.user_id = "'.$_SESSION['user_details']['id'].'" ORDER BY id DESC';
                $notification_result = mysqli_query($conn, $notification_sql);
                $notification_count = mysqli_num_rows($notification_result);
                while($notification_data = mysqli_fetch_assoc($notification_result)){
                  if($notification_data['is_read'] == "0"){
                    $b_colour = "#ffffff";
                  }else{
                    $b_colour = "#F9E9E9E";
                  }
                ?>
                  <tr class="tr_click" data-id="<?php echo MD5($notification_data['event_id']); ?>" data-status_id = "<?php echo $notification_data['id']; ?>" style="background-color : <?php echo $b_colour; ?>;cursor: pointer;">
                    <td>
                    <p><?php echo $notification_data['message']; ?></p>
                    <p><?php echo date("d F, Y", strtotime($notification_data['created_date'])); ?></p>
                    </td>
                  </tr>
                <?php $i++; } ?>
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
<script>
$('#tbl-celebrities-listing').DataTable({
        /* Disable initial sort */
        "aaSorting": []
    });;
</script>
<script>
$('body').on('click', '.tr_click', function() {
    //code
    var id = $(this).attr("data-id");
    var notification_id = $(this).attr("data-status_id");
    //window.location.href = "events_details_list.php?id="+id;
    var url = "ajax.php";
    $.ajax({
    type: "POST",
    url: url,
    data: {"notification_id":notification_id,"action":"notification_status"}, 
    async: false,
    success: function(data)
    {
      <?php 
      if ($user_data['user_type_name'] == "Administrator" || $user_data['user_type_name'] == "Core Captain") {
      ?> 
        window.location.href = "events_details_list.php?id="+id;
      <?php }else{ ?>
        window.location.href = "event-details.php?id="+id;
      <?php } ?>
    }
  });
});
</script>