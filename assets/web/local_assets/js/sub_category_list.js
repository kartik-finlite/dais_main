$(document).ready(function () {
  $('#tbl-celebrities-listing').DataTable({
    "bProcessing": true,
 	  "serverSide": true,
    "ajax":{
          url :"ajax.php",
          data: {"action":"sub_category_list"}, 
          type: "POST",
          error: function(){
            $("#post_list_processing").css("display","none");
          }
      },
    "aoColumns": [
        { mData: 'category_name' },
        { mData: 'name' },
        { mData: 'action' }
    ]
  });
});  