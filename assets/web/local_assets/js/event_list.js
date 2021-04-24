$(document).ready(function () {
  $('#tbl-event-listing').DataTable({
    "bProcessing": true,
 	  "serverSide": true,
    "ajax":{
          url :"ajax.php",
          data: {"action":"event_list"}, 
          type: "POST",
          error: function(){
            $("#post_list_processing").css("display","none");
          }
      },
    "aoColumns": [
        { mData: 'title' } ,
        { mData: 'description' },
        { mData: 'event_date' },
        { mData: 'event_time' },
        { mData: 'registration_end_date' },
        { mData: 'action' }
    ]
  });
});  