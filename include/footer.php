

    <!-- Main Footer -->
    <script src="assets/web/local_assets/js/dashbord.js"></script>
    <div class="modal fade modal-center" id="myModal" style="outline:0" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header on-load-modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Choose Minimum 3 Categories</h5>
              <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button> -->
            </div>
            <form id="popup_from" name="popup_from">
              <?php 
              // Category get Code
              $get_category_query = 'SELECT uic.id as interested_id,c.id as category_id,c.name FROM `user_interested_categories` uic LEFT JOIN categories c ON c.id = uic.category_id WHERE uic.user_id = "'.$_SESSION['user_details']['id'].'"';
              $get_category_result = mysqli_query($conn, $get_category_query);
              $category = array();
              while($get_category = mysqli_fetch_assoc($get_category_result)){
                  $get_category_array['id'] = $get_category['category_id'];
                  $category[] = $get_category_array;
              }
              
              ?>
              <div class="modal-body modal-body-title cstm_scroll" id="model_scrolling">
              <div class="errorTxt"></div>
                  <ul class="ks-cboxtags">
                    <?php 
                    $category_query = 'SELECT * FROM `categories` WHERE category_id = "0" AND status = "1"';
                    $category_result = mysqli_query($conn, $category_query);
                    while($category_data = mysqli_fetch_assoc($category_result)){
                    ?>
                    <li><input type="checkbox" name="category_id[]" id="checkbox<?php echo $category_data['id']; ?>" <?php if(array_search($category_data['id'], array_column($category, 'id')) !== false) { echo 'checked';} ?> value="<?php echo $category_data['id']; ?>"><label for="checkbox<?php echo $category_data['id']; ?>"><?php echo $category_data['name'] ?></label></li>
                    <?php } ?>
                  </ul>
              </div>
              <div class="modal-footer modal-footer-btn">
                <button type="submit" id="popup_submit" onclick="return validate();" class="btn btn-primary">SUBMIT</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    <footer class="main-footer">
      <strong>Copyright &copy; <?php echo date('Y'); ?> <a href="#">DAIS +</a>.</strong>
      All rights reserved.
      <div class="float-right d-none d-sm-inline-block">
        <b>Version</b> 1.0
      </div>
    </footer>
  </div>
  <!-- ./wrapper -->
</body>

</html>