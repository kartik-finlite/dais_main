$(document).ready(function () {
  $('#tbl-celebrities-listing').DataTable({
    "bProcessing": true,
 	  "serverSide": true,
    "ajax":{
          url :"ajax.php",
          data: {"action":"category_list"}, 
          type: "POST",
          error: function(){
            $("#post_list_processing").css("display","none");
          }
      },
    "aoColumns": [
        { mData: 'name' },
        { mData: 'action' }
    ]
  });
});  