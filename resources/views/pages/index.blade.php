
@extends('layouts.app')

@section('content')

	<!-- Navigation -->
	@include('layouts.left')

	<div id="page-wrapper">
		<div class="container-fluid">
      <div class="row">
				<div class="col-lg-12">
					<h1 class="page-header">{{__('Page List')}}</h1>
				</div>
        @include('layouts/flash')
				<!-- /.col-lg-12 -->
			</div>             
  <div class="row pull-right">
    <div class="col-md-12 ">
      <button class="btn btn-primary" onclick='location.href="{{url("/")}}/admin/pages/add"'>Add Page</button>
    </div>
  </div>
  <div class="x_panel">
      <div class="x_title">
        <h2>Pages List</h2>

        <div class="clearfix"></div>
      </div>
      <div class="x_content">     
          {{ csrf_field() }}             
          <table id="pageData" class="table-responsive table table-striped table-bordered" style="font-size:12px;width:100% !important">
              <thead>
                  <tr>
                      
                      <th>Title</th>                      
                      <th>Created Date</th>                      
                      <th>Status</th>
                      <th>Action</th>  
                  </tr>
              </thead>
              <tbody>
                            
              </tbody>
              <tfoot>
                    <tr>                              
                      <th>Title</th>                      
                      <th>Created Date</th> 
                      <th>Status</th>
                      <th>Action</th>  
                  </tr>
              </tfoot>
          </table>                              
        </div>
</div>
      

<div id="addPages" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Page</h4>
      </div>
      <div class="modal-body">
      <div id="messageData"></div>
      <form method="post" name="addPageForm" id="addPageForm">
      
      <div class="form-group">
          <label for="content" class="col-sm-12 control-label">Page Title</label>
      </div>

      <div class="form-group">        
        <div class="col-md-12 col-sm-12 col-xs-12">
          <input class="form-control" placeholder="Title" type="text" name="title">
        </div>
      </div>
        <div class="form-group">
          <label for="content" class="col-sm-12 control-label">Page Description</label>
        </div>
        <div class="form-group">
          <div class="col-md-12 col-sm-12 col-xs-12">
            <textarea class="form-control ckeditor" id="body" name="description" required  rows="5" placeholder="Floor Content"></textarea>
          </div>
        </div>
        <div class="form-group">
          <div class="col-md-12 col-sm-12 col-xs-12">
            <button type="submit" class="btn btn-primary" onClick="return addPage('addPageForm')">Submit</button>
          </div>
        </div>
      </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

          
<script>
        
        var table = '';

        jQuery(document).ready(function() {
          
					//var permissonObj = '<%-JSON.stringify(permission)%>';
					//permissonObj = JSON.parse(permissonObj);


          table = jQuery('#pageData').DataTable({
            'processing': true,
            'serverSide': true,                        
            'lengthMenu': [
              [10, 25, 50, -1], [10, 25, 50, "All"]
            ],
            dom: 'Bfrtip',
            buttons: [                        
            {extend:'csvHtml5',
              exportOptions: {
                columns: [0, 1, 2, 3,4,5,7]//"thead th:not(.noExport)"
              },
              className: 'btn btn-default',
                init: function(api, node, config) {
                  $(node).removeClass('dt-button')
                },
            },
            {extend: 'pdfHtml5',
              exportOptions: {
                columns: [0, 1, 2, 3,4,5,7] //"thead th:not(.noExport)"
              },
              className: 'btn btn-default',
                init: function(api, node, config) {
                  $(node).removeClass('dt-button')
                },
              customize : function(doc){
                    var colCount = new Array();
                    var length = $('#reports_show tbody tr:first-child td').length;
                    //console.log('length / number of td in report one record = '+length);
                    $('#reports_show').find('tbody tr:first-child td').each(function(){
                        if($(this).attr('colspan')){
                            for(var i=1;i<=$(this).attr('colspan');$i++){
                                colCount.push('*');
                            }
                        }else{ colCount.push(parseFloat(100 / length)+'%'); }
                    });
              }
            },
            {
            extend:'pageLength',
            className: 'btn btn-default',
                init: function(api, node, config) {
                  $(node).removeClass('dt-button')
                },
            
            },
            {
                text: 'Add Pages',
                className: 'btn btn-primary',
                init: function(api, node, config) {
                  $(node).removeClass('dt-button')
                },
                action: function ( e, dt, node, config ) {
                  $('#addPages').modal('toggle');
                }
            }
            ],
            'sPaginationType': "simple_numbers",
            'searching': true,
            "bSort": false,
            "fnDrawCallback": function (oSettings) {
              jQuery('.popoverData').popover();
              // if(jQuery("#userTabButton").parent('li').hasClass('active')){
              //   jQuery("#userTabButton").trigger("click");
              // }
              // jQuery("#userListTable_wrapper").removeClass( "form-inline" );
            },
            'fnRowCallback': function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
              //if (aData["status"] == "1") {
                //jQuery('td', nRow).css('background-color', '#6fdc6f');
              //} else if (aData["status"] == "0") {
                //jQuery('td', nRow).css('background-color', '#ff7f7f');
              //}
              //jQuery('.popoverData').popover();
            },
            'ajax': {
              'url': '{{ url("/") }}/admin/pages/pageIndexAjax',
              'headers': {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
              },
              'type': 'post',
              'data': function(d) {
                //d.userFilter = jQuery('#userFilter option:selected').text();
                //d.search = jQuery("#userListTable_filter input").val();
              },
            },          

            'columns': [
                
              {
                  'data': 'title',
                  'className': 'col-md-6',
                  'render': function(data,type,row){
                    var title = (row.title!=null && row.title.length > 30) ? row.title.substring(0,30)+'...' : row.title;
                    return '<a class="popoverData" data-content="'+row.title+'" rel="popover" data-placement="bottom" data-original-title="Name" data-trigger="hover">'+title+'</a>';
                  }
              },
              {
                  'data': 'Created Date',
                  'className': 'col-md-2',
                  'render': function(data,type,row,meta){
                    return row.created_at;
                    
                  }
              },
              {
                'data': 'Status',
                'className': 'col-md-2',
                'render': function(data,type,row){
                    var html = '';
                    if(row.status=='1'){
                      html = '<i class="fa fa-toggle-on" style="color:green; font-size:18px;" ></i>';
                    }else{
                      html = '<i class="fa fa-toggle-off" style="color:red;font-size:18px;"></i>';
                    }                    
                    return html;
                  }  
              },            
              {
                'data': 'Action',
                'className': 'col-md-2',
                'render': function(data, type, row) {
                  var buttonHtml = '<button type="button" data-id="' + row.id + '" class="btn btn-success" onclick="editpage('+row.id+')"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button> <button type="button" id="' + row.id + '" class="btn btn-danger delete_node roleActionHTML user_deleteUser"><i class="fa fa-trash" aria-hidden="true"></i></button>';
                  return buttonHtml;
                }
              }
            ]
          });   
              
          
        });

        

        jQuery("body").on("click", ".delete_node", function() {
          var id = jQuery(this).attr("id");

          $.confirm({
              title: '',
              content: 'Are you sure want to delete this?',
              buttons: {
                  confirm: function () {
                    jQuery.ajax({
                      type: "post",
                      url: '{{ url("/") }}/admin/pages/destroy',
                      headers: {
                        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                      },
                      data: {
                        "id": id
                      },
                      success: function(response) {
                        var response = JSON.parse(response);  
                        if (response.success == 1) {
                          //jQuery("#userFilter").trigger("change");
                          table.draw();
                        } else {
                          jQuery.alert({
                            title: "",
                            content: 'Problem in deleted',
                          });
                        }
                        return false;
                      },
                      error: function() {
                        jQuery.alert({
                          title: "",
                          content: 'Technical error',
                        });
                      }
                    });
                  },
                  cancel: function () {
                      return true;
                  }
              }
          });
        });


        function addPage(formName){

            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }

            var fromdata = $('#'+formName).serialize();
            jQuery.ajax({        
                type : 'POST',
                url : "{{url('/')}}/admin/pages/create",
                data : fromdata,
                beforeSend: function(xhr){xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));},
                success: function (data) {
                var output = JSON.parse(data);
                //console.log(output);
                //alert(output.success); 
                if(output.success){
                    jQuery('#addPages').modal('hide');
                    table.ajax.reload();
                    return false;
                }else{
                    jQuery('#messageData').html('<span style="color:red">Unable to add</span>');
                }
                },
                error: function(data){
                var output = JSON.parse(data)
                jQuery('#messageData').html('<span style="color:red">'+output['message']+'</span>');
                }
            })

            return false;
        }


        function editpage(id){
          window.location.href = "{{url('/')}}/admin/pages/edit/"+id
        }

        function ValidateEmail(email){
          if(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email)) {
            return true;
          }
          return false;
        }


        
      </script>
      <style>
        .dataTables_paginate a {
          background-color:#fff !important;
        }
        .dataTables_paginate .pagination>.active>a{
          color: #fff !important;
          background-color: #337ab7 !important;
        }
        .form-group input,.form-group textarea,.form-group button {
            margin: 10px 0px 10px 0px;
        }
      </style>

@endsection
