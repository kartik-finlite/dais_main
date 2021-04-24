$(document).ready(function () {

  $('#tbl-celebrities-listing').dataTable({
    "bProcessing": true,
    "sAjaxSource": base_url + "admin/celebrities/ajax-list",
    "iDisplayLength": 20,
    "bLengthChange": true,
    "bServerSide": true,
    // "responsive": true,
    "stateSave": true,
    "lengthMenu": [[20, 40, 60, 80, 100], [20, 40, 60, 80, 100]],
    "sDom": "<'row-fluid'<'span12'f>r>t<'row-fluid'<'span4'l><'span8'p>>",
    "language": {
      "lengthMenu": "_MENU_ per page",
      "zeroRecords": "No Records"
    },
    "aoColumns": [
      {
        "sortable": false,
        "sWidth": "5%",
        "sClass": "center",
        "render": function (data, type, row) {
          return row.Id;
        }

      },
      {

        "sortable": false,
        "render": function (data, type, row) {


          var html = '<img class="img-responsive" id="preview" src="' + row.photo + '" height="25" width="25"/>';


          return html;
        }

      },
      {

        "sortable": true,
        "render": function (data, type, row) {


          var html = row.name;
          return html;
        }

      },
      {

        "sortable": true,
        "render": function (data, type, row) {
          var html = row.description;
          return html;
        }

      },
      {

        "sortable": true,
        "render": function (data, type, row) {
          var html = row.note;
          return html;
        }

      },
      {

        "sortable": true,
        "render": function (data, type, row) {
          var html = '';
          var checked = (row.isActive === true) ? 'checked' : '';
          html = `<div class="icheck-success d-inline">`
            + `<input type="checkbox" ` + checked + ` id="statusCheck` + row._id + `" data-id="` + row._id + `" name="statusCheck` + row._id + `" value="` + row.isActive + `">`
            + `<label for="statusCheck` + row._id + `">`
            + `</label>`;
          +`</div>`;
          return html;
        }

      },
      {

        "sortable": true,
        "sWidth": "10%",
        "render": function (data, type, row) {
          var html = row.addedDate;
          return html;
        }

      }, {

        "sortable": false,
        "sWidth": "10%",
        "render": function (data, type, row) {
          var _id = row._id;
          var html = '';
          html = `<div id="btn-group-action-` + row._id + `" class="btn-group">`
            + `<button type="button" class="btn btn-block btn-default btn-xs dropdown-toggle dropdown-hover dropdown-icon" data-toggle="dropdown">`
            + `<span class="sr-only">Toggle Dropdown</span>`
            + `</button>`
            + `<div class="dropdown-menu" role="menu">`
            + `<a id="btn-action-update-` + row._id + `" data-id="` + row._id + `"  class="dropdown-item" href="javascript:void(0);">Update</a>`
            + `<div class="dropdown-divider"></div>`
            + `<a id="btn-action-delete-` + row._id + `" data-id="` + row._id + `" class="dropdown-item" href="javascript:void(0);">Delete</a>`
            + `</div>`
            + `</div>`;
          return html;
        }

      }
    ],
    "rowCallback": function (row, data) {
      var status = $(row).find('#statusCheck' + data._id);
      status.on("change", function () {
        var curCtrl = $(this);
        var id = curCtrl.attr('data-id');
        var status = ($(curCtrl).prop("checked") == true) ? true : false;
        var data = {
          _id: id,
          isActive: status,
        };
        $.ajax({
          type: "POST",
          url: base_url + "admin/celebrities/update-status",
          data: JSON.stringify(data),
          headers: {
            'Content-Type': 'application/json',
            'x-api-key': 'e10adc3949ba59abbe56e057f20f883e'
          },
          beforeSend: function () {
            // do code here.
          },
          success: function (data) {
            // console.log(data);
            var Toast = Swal.mixin({
              toast: true,
              position: 'top-end',
              showConfirmButton: false,
              timer: 6000
            });

            var res = data;
            var result_arr = jQuery.parseJSON(data);
            // console.log(result_arr.data.code);
            var code = result_arr.status;
            var message = result_arr.message;
            var details = result_arr.data;
            var html = "";
            if (code == 0) {
              // $("#customDiv").fadeOut();
              html += code
                + ` - `
                + message;
              Toast.fire({
                icon: 'success',
                title: html
              });
              return false;
            } else {
              html += code
                + ` - `
                + message;
              Toast.fire({
                icon: 'error',
                title: html
              });
              return false;
            }
          }
        });
      });


      var deleteAction = $(row).find('#btn-action-delete-' + data._id);
      deleteAction.on("click", function () {
        if (window.confirm("Are you sure? you want to remove this record?") === true) {
          var curCtrl = $(this);
          var id = curCtrl.attr('data-id');
          var data = {
            _id: id,
            isDeleted: true,
          };
          $.ajax({
            type: "POST",
            url: base_url + "admin/celebrities/delete-row",
            data: JSON.stringify(data),
            headers: {
              'Content-Type': 'application/json',
              'x-api-key': 'e10adc3949ba59abbe56e057f20f883e'
            },
            beforeSend: function () {
              // do code here.
            },
            success: function (data) {
              // console.log(data);
              var Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 6000
              });

              var res = data;
              var result_arr = jQuery.parseJSON(data);
              // console.log(result_arr.data.code);
              var code = result_arr.status;
              var message = result_arr.message;
              var details = result_arr.data;
              var html = "";
              if (code == 0) {
                // $("#customDiv").fadeOut();
                html += code
                  + ` - `
                  + message;
                Toast.fire({
                  icon: 'success',
                  title: html
                });
                window.setTimeout(function () {
                  window.location.reload(true);
                }, 2000);
                return false;
              } else {
                html += code
                  + ` - `
                  + message;
                Toast.fire({
                  icon: 'error',
                  title: html
                });
                return false;
              }
            }
          });
        }
      });



      var updateAction = $(row).find('#btn-action-update-' + data._id);
      updateAction.on("click", function () {
        var curCtrl = $(this);
        var id = curCtrl.attr('data-id');
        window.location = base_url + "admin/celebrities/update-details/" + id;
      });
    },
    "bAutoWidth": false
  });
});





