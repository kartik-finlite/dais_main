<?php
include 'include/session_check.php';
$title = "Past Event List";
$pageTitle = "Past Event List";
include 'include/header.php';
include 'include/header-navigation.php';
include 'include/navigation.php';
function convert_multi_array($arrays)
{
  $imploded = array();
  foreach ($arrays as $array) {
    $imploded[] = implode('', $array);
  }
  return implode(" , ", $imploded);
}
?>
<style>
  .btn {
    padding-left: 2.5rem;
    padding-right: 2.5rem;
    text-transform: uppercase;
  }

  .image.p-image img {
    height: 2.1rem;
  }

  button.swal2-cancel.btn.btn-danger {
    margin-right: 10px;
  }
</style>
<!-- LOCAL PAGE LAVEL JS -->
<script src="assets/web/local_assets/js/event_list_past.js"></script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <!-- <h1 class="m-0 text-dark"><?php //echo (isset($pageTitle) && $pageTitle != null) ? $pageTitle : ''; 
                                          ?></h1> -->
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
              <?php unset($_SESSION['FLASH_SUCCESS_FLAG']); ?>
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
              <?php unset($_SESSION['FLASH_ERROR_FLAG']); ?>
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
                <div class="col-6 col-md-6 col-xs-6">
                  <h3 class="card-title">Past Events</h3>
                </div>
                <?php
                $sql = 'SELECT u.id as user_ID,h.name as house_name,h.id as house_id,ut.name as user_type_name FROM `users` u LEFT JOIN houses h ON u.house_id = h.id LEFT JOIN user_type ut ON ut.id = u.user_type_id WHERE u.id = "' . $_SESSION['user_details']['id'] . '"';
                $result = mysqli_query($conn, $sql);
                $user_data = mysqli_fetch_assoc($result);
                if ($user_data['user_type_name'] == "Administrator" || $user_data['user_type_name'] == "Core Captain") {
                ?>

                  <div class="col-6 col-md-6 col-xs-6 text-right">
                    <!--<button type="button" onclick="return getPage('event.php');" class="pull-right btn btn-primary">Add</button>-->
                  </div>
                <?php } ?>
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <!-- <table id="tbl-event-listing" class="table table-bordered table-striped"> -->
              <table class="table table-bordered table-striped datatable">

                <thead>
                  <tr>
                    <th>Name</th>
                    <th>Category Name</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Registration End Date</th>
                    <th>Registration End Time</th>
                    <?php
                    if ($user_data['user_type_name'] == "Administrator" || $user_data['user_type_name'] == "Core Captain") {
                    ?>
                      <th>Age & Gender</th>
                      <th>Grade</th>
                      <th>Council Members</th>
                    <?php } ?>
                    <th>Event Notes</th>
                    <?php
                    if ($user_data['user_type_name'] == "Administrator" || $user_data['user_type_name'] == "Core Captain") {
                    ?>
                    <th>Internal Notes</th>
                    <th>Action</th>
                    <?php } ?>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  date_default_timezone_set('Asia/Kolkata');
                  $current_date_time = date('Y-m-d H:i:s');
                  if ($user_data['user_type_name'] == "Administrator" || $user_data['user_type_name'] == "Core Captain") {
                    // Total Count Get 
                    $count_sql = 'SELECT COUNT(*) as count FROM `events` WHERE event_date_time < "' . $current_date_time . '"';
                    $count_result = mysqli_query($conn, $count_sql);
                    $count_data = mysqli_fetch_assoc($count_result);

                    $sql_query = 'SELECT e.*,es.status as is_signup,es.reject_by FROM `events` e LEFT JOIN event_signups es ON e.id = es.event_id WHERE e.event_date_time < "' . $current_date_time . '" GROUP BY e.id';
                  } else if ($user_data['user_type_name'] == "House Captain") {
                    // Total Count Get 
                    $count_sql = 'SELECT COUNT(*) as count FROM `events` WHERE event_date_time < "' . $current_date_time . '"';
                    $count_result = mysqli_query($conn, $count_sql);
                    $count_data = mysqli_fetch_assoc($count_result);

                    $sql_query = 'SELECT e.*,es.status as is_signup,es.reject_by FROM `events` e LEFT JOIN event_signups es ON e.id = es.event_id WHERE e.event_date_time < "' . $current_date_time . '" GROUP BY e.id';
                  } else {
                    $student_sql = 'SELECT dob,gender,class as grade FROM `users`WHERE id = "' . $_SESSION['user_details']['id'] . '"';
                    $result = mysqli_query($conn, $student_sql);
                    $user_details = mysqli_fetch_assoc($result);
                    $user_year = date("Y", strtotime($user_details['dob']));

                    // Total Count Get 
                    // $count_sql = 'SELECT COUNT(*) as count FROM `events` e 
                    // LEFT JOIN event_age_gender_categories eagc ON eagc.event_id = e.id 
                    // LEFT JOIN age_gender_categories agc ON agc.id =eagc.age_gender_id WHERE event_date_time < "'.$current_date_time.'" AND EXTRACT(YEAR FROM "'.$user_details['dob'].'") >= agc.start_year and EXTRACT(YEAR FROM "'.$user_details['dob'].'") <= agc.end_year AND agc.gender = "'.$user_details['gender'].'" AND e.title LIKE "%'.$requestField['search'].'%"';
                    // $count_result = mysqli_query($conn, $count_sql);
                    // $count_data = mysqli_fetch_assoc($count_result);

                    // $sql_query = 'SELECT e.*,es.status as is_signup,es.reject_by FROM `events` e 
                    // LEFT JOIN event_age_gender_categories eagc ON eagc.event_id = e.id 
                    // LEFT JOIN event_signups es ON e.id = es.event_id and es.user_id = "'.$requestField['user_id'].'"
                    // LEFT JOIN age_gender_categories agc ON agc.id =eagc.age_gender_id WHERE event_date_time < "'.$current_date_time.'" AND EXTRACT(YEAR FROM "'.$user_details['dob'].'") >= agc.start_year and EXTRACT(YEAR FROM "'.$user_details['dob'].'") <= agc.end_year AND agc.gender = "'.$user_details['gender'].'"';

                    // Total Count Get
                    $count_sql = '(SELECT e.*,es.status as is_signup,es.reject_by,es.approved_by FROM events e
                INNER JOIN event_age_gender_categories eagc ON e.id = eagc.event_id
                INNER JOIN age_gender_categories agc ON agc.id = eagc.age_gender_id
                LEFT JOIN event_signups es ON e.id = es.event_id AND es.user_id = "' . $_SESSION['user_details']['id'] . '"
                WHERE event_date_time < "' . $current_date_time . '" AND (agc.start_year = "' . $user_year . '" OR agc.end_year = "' . $user_year . '") AND e.is_delete = "0" AND agc.gender = "' . $user_details['gender'] . '" GROUP BY e.id 
                )UNION ALL
                (
                SELECT e.*,es.status as is_signup,es.reject_by,es.approved_by FROM events e
                INNER JOIN event_grade_categories egc ON egc.event_id = e.id
                INNER JOIN grades g ON g.id = egc.grade_id
                LEFT JOIN event_signups es ON e.id = es.event_id AND es.user_id = "' . $_SESSION['user_details']['id'] . '"
                WHERE event_date_time < "' . $current_date_time . '" AND e.is_delete = "0" AND "' . $user_details['grade'] . '" between g.start_grade and g.end_grade GROUP BY e.id) ORDER BY event_date_time ASC ';
                    $count_result = mysqli_query($conn, $count_sql);
                    $count_data = mysqli_num_rows($count_result);
                    $count_data_value = $count_data;

                    $sql_query = '(SELECT e.*,es.status as is_signup,es.reject_by,es.approved_by FROM events e
                INNER JOIN event_age_gender_categories eagc ON e.id = eagc.event_id
                INNER JOIN age_gender_categories agc ON agc.id = eagc.age_gender_id
                LEFT JOIN event_signups es ON e.id = es.event_id AND es.user_id = "' . $_SESSION['user_details']['id'] . '"
                WHERE event_date_time < "' . $current_date_time . '" AND e.is_delete = "0" AND (agc.start_year = "' . $user_year . '" OR agc.end_year = "' . $user_year . '") AND agc.gender = "' . $user_details['gender'] . '" GROUP BY e.id 
                )UNION ALL
                (
                SELECT e.*,es.status as is_signup,es.reject_by,es.approved_by FROM events e
                INNER JOIN event_grade_categories egc ON egc.event_id = e.id
                INNER JOIN grades g ON g.id = egc.grade_id
                LEFT JOIN event_signups es ON e.id = es.event_id AND es.user_id = "' . $_SESSION['user_details']['id'] . '"
                WHERE event_date_time < "' . $current_date_time . '" AND e.is_delete = "0" AND "' . $user_details['grade'] . '" between g.start_grade and g.end_grade GROUP BY e.id) ORDER BY event_date_time';
                  }
                  $result = mysqli_query($conn, $sql_query);
                  while ($row = mysqli_fetch_assoc($result)) {
                    if ($row['is_delete'] == 1) {
                      $style = "background-color: #ff9a9a;";
                    } else {
                      $style = "";
                    }
                  ?>
                    <tr style="<?php echo $style; ?>">
                      <?php
                      if ($user_data['user_type_name'] == "Administrator" || $user_data['user_type_name'] == "Core Captain" || $user_data['user_type_name'] == "House Captain") {
                      ?>
                        <?php
                        if ($row['is_delete'] == 1) {
                        ?>
                          <td><?php echo $row['title']; ?></td>
                        <?php } else { ?>
                          <td><a href="events_details_list.php?id=<?php echo MD5($row['id']); ?>"><?php echo $row['title']; ?></a></td>
                        <?php } ?>
                      <?php
                      } else {
                      ?>
                        <td><a href="event-details.php?id=<?php echo MD5($row['id']); ?>"><?php echo $row['title']; ?></a></td>
                      <?php } ?>
                      <?php
                      $sql1 = 'SELECT * FROM `categories` WHERE category_id = "0" AND id = "' . $row['category_id'] . '"';
                      $result1 = mysqli_query($conn, $sql1);
                      $category_data = mysqli_fetch_assoc($result1);
                      ?>
                      <td><?php echo $category_data['name']; ?></td>
                      <td><?php echo date("d, F Y", strtotime($row['event_date'])) ?></td>
                      <td><?php echo date("g:i A", strtotime($row['event_time'])); ?></td>
                      <td><?php echo date("d, F Y", strtotime($row['registration_end_date'])) ?></td>
                      <td><?php echo date("g:i A", strtotime($row['registration_end_time'])); ?></td>
                      <?php

                      if($user_data['user_type_name'] == "Administrator" || $user_data['user_type_name'] == "Core Captain"){

                      // age gender get
                      $age_query = 'SELECT agc.title FROM `event_age_gender_categories` eac LEFT JOIN age_gender_categories agc ON eac.age_gender_id = agc.id WHERE event_id = "' . $row['id'] . '"';
                      $age_result = mysqli_query($conn, $age_query);
                      $age_count = mysqli_num_rows($age_result);
                      $age_gender = array();
                      if ($age_count > 0) {
                        while ($age_data = mysqli_fetch_assoc($age_result)) {
                          $age_gender[] = $age_data;
                        }
                      } else {
                        $age_gender = array();
                      }
                      ?>
                      <td><?php echo convert_multi_array($age_gender); ?></td>
                      <?php
                      // grade get
                      $grade_query = 'SELECT g.name FROM `event_grade_categories` egc LEFT JOIN grades g ON g.id = egc.grade_id WHERE event_id = "' . $row['id'] . '"';
                      $grade_result = mysqli_query($conn, $grade_query);
                      $grade_count = mysqli_num_rows($grade_result);
                      $grade = array();
                      if ($grade_count > 0) {
                        while ($grade_data = mysqli_fetch_assoc($grade_result)) {
                          $grade[] = $grade_data;
                        }
                      } else {
                        $grade = array();
                      }
                      ?>
                      <td><?php echo convert_multi_array($grade); ?></td>
                      
                      <?php
                      // council get
                      $council_query = 'SELECT u.first_name FROM `event_council_members` ecm LEFT JOIN users u ON ecm.council_member = u.id WHERE ecm.event_id = "' . $row['id'] . '"';
                      $council_result = mysqli_query($conn, $council_query);
                      $council_count = mysqli_num_rows($council_result);
                      $council = array();
                      if ($council_count > 0) {
                        while ($council_data = mysqli_fetch_assoc($council_result)) {
                          $council[] = $council_data;
                        }
                      } else {
                        $council = array();
                      }
                      ?>
                      <td><?php echo convert_multi_array($council); ?></td>
                      <?php } ?>
                      <td><?php echo $row['event_external_notes']; ?></td>
                      <?php
                      if ($user_data['user_type_name'] == "Administrator" || $user_data['user_type_name'] == "Core Captain") {
                      ?>
                      <td><?php echo $row['event_internal_notes']; ?></td>
                      
                        <!-- <td><a href="event.php?id=<?php //echo MD5($row['id']); 
                                                        ?>"><i class="fas fa-edit" aria-hidden="true"></i></a></td> -->


                        <?php
                        if ($row['is_delete'] == 0) {
                        ?>
                          <td><a href="javascript:void(0)" data-id="<?php echo MD5($row['id']); ?>" class="delete_event"><i class="fas fa-trash"></i></a></td>
                        <?php } else { ?>
                          <td></td>
                        <?php } ?>
                      <?php } ?>
                    </tr>
                  <?php } ?>
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
  $('.datatable').DataTable({
    "paging": true,
    "lengthChange": false,
    "searching": true,
    "ordering": true,
    "info": true,
    "autoWidth": false,
    "responsive": true,
  });
</script>