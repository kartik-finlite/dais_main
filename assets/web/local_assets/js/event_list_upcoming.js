$(document).ready(function () {
  $('#tbl-event-listing').DataTable({
    "bProcessing": true,
 	  "serverSide": true,
    "ajax":{
          url :"ajax.php",
          data: {"action":"event_upcomming_list"}, 
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

$(document).on('click', '.delete_event', function() {   
  var delete_event_id = $(this).attr("data-id");
  const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
      confirmButton: 'btn btn-success',
      cancelButton: 'btn btn-danger'
    },
    buttonsStyling: false
  });   
  swalWithBootstrapButtons.fire({
    title: 'Are you sure you want to delete this event?',
    text: "",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Yes',
    cancelButtonText: 'No',
    reverseButtons: true
  }).then((result) => {
    if (result.isConfirmed) {
      var url = "ajax.php";
      
      $.ajax({
        type: "POST",
        url: url,
        data: {"delete_event_id":delete_event_id,"action":"delete_event"}, 
        async: false,
        success: function(data)
        { 
        }
      });
      // swalWithBootstrapButtons.fire(
      //   'Deleted!',
      //   'Your event has been deleted.',
      //   'success'
      // )
      location.reload();
    } else if (
      /* Read more about handling dismissals below */
      result.dismiss === Swal.DismissReason.cancel
    ) {
      swalWithBootstrapButtons.fire(
        'Cancelled',
        'Your event is safe :)',
        'error'
      )
    }
  })  
});