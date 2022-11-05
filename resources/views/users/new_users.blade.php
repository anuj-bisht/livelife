@extends('layouts.app')

@section('content')

	<!-- Navigation -->
	@include('layouts.left')

	<div id="page-wrapper">
		<div class="container-fluid">


      <div class="row">
				<div class="col-lg-12">
					<h1 class="page-header">{{__('New User List')}}</h1>
				</div>
				<!-- /.col-lg-12 -->
			</div>  

     

      @include('layouts.flash')
            
      <table id="tableData" class="table-responsive table table-striped table-bordered" style="font-size:12px;width:100% !important">
          
          <thead>
            <tr>         
              <th>Name</th>              
              <th>Email</th>
              <th>Phone</th>
              <th>Roles</th>              
              <th width="280px">Action</th>
            </tr>
          </thead>
          <tbody>
                        
          </tbody>
          <tfoot>
              <tr>       
                <th>Name</th>                
                <th>Email</th>
                <th>Phone</th>
                <th>Roles</th>                
                <th width="280px">Action</th>
                </tr>
          </tfoot>
      </table>  


		</div>		
	</div>

  {!! Form::open(['method' => 'DELETE','route' => ['users.destroy', 1],'id'=>'deleteRow','style'=>'display:inline']) !!}
      
  {!! Form::close() !!}

	<!-- /#page-wrapper -->
 

  <script>
        
    var url = "{{url('/')}}";
    var table = '';

    jQuery(document).ready(function() {
          
					
          table = jQuery('#tableData').DataTable({
            'processing': true,
            'serverSide': true,                        
            'lengthMenu': [
              [10, 25, 50, -1], [10, 25, 50, "All"]
            ],
            dom: 'Bfrtip',
            buttons: [                        
            {extend:'csvHtml5',
              exportOptions: {
                columns: [0, 1,2,3,4]//"thead th:not(.noExport)"
              },
              className: 'btn btn-default',
                init: function(api, node, config) {
                  $(node).removeClass('dt-button')
                },
            },
            {extend: 'pdfHtml5',
              exportOptions: {
                columns: [0, 1,2,3,4] //"thead th:not(.noExport)"
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
            
            }
            ],
            'sPaginationType': "simple_numbers",
            'searching': true,
            "bSort": true,
            "fnDrawCallback": function (oSettings) {
              
            },
            'fnRowCallback': function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
              //if (aData["status"] == "1") {
                //jQuery('td', nRow).css('background-color', '#6fdc6f');
              //} else if (aData["status"] == "0") {
                //jQuery('td', nRow).css('background-color', '#ff7f7f');
              //}
              //jQuery('.popoverData').popover();
            },
						"initComplete": function(settings, json) {						
              //jQuery('.popoverData').popover();
					  },
            'ajax': {
              'url': '{{ url("/") }}/admin/users/ajaxDataNew',
              'headers': {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
              },
              'type': 'post',
              'data': function(d) {
                //d.statusFilter = jQuery('#statusFilter').val();
                d.parent = jQuery('#parentFilter option:selected').val();
                //d.search = jQuery("#msds-select option:selected").val();
                d.filterRole = "{{$filterRole}}";
              },
            },          

            'columns': [
                              
              
              {
                  'data': 'name',                  
                  'className': 'col-md-2',
                  'render': function(data,type,row){
                    
                    return row.name;
                  }
              },
              {
                  'data': 'email',
                  'className': 'col-md-2',                  
                  'render': function(data,type,row){
                    
                    return row.email;
                  }
              },
              {
                  'data': 'phone',
                  'className': 'col-md-1',                  
                  'render': function(data,type,row){
                    
                    return row.phone;
                  }
              },
              {
                  'data': 'roles',
                  'className': 'col-md-2',                  
                  'render': function(data,type,row){
                    
                    return row.rolename;
                  }
              },
              {
                'data': 'Action',
                'orderable': false,
                'className': 'col-md-3',
                'render': function(data, type, row) {
                  var buttonHtml = '<a class="btn btn-info" onclick="contacted('+row.id+')">Connected</a>&nbsp;&nbsp;';
                  return buttonHtml;
                }
              }
            ]
          });    
          
          

          $('#tableData tbody').on('click', 'td.details-control', function () {
              var tr = $(this).closest('tr');
              var row = table.row( tr );
      
              if ( row.child.isShown() ) {
                  // This row is already open - close it
                  row.child.hide();
                  tr.removeClass('shown');
              }
              else {
                  // Open this row
                  row.child( format(row.data()) ).show();
                  tr.addClass('shown');
              }
          }); 


          
        
  });


  $('#tableData').on('click','.email_notification',function(){
    var id = $(this).val();
    var ntype = 'email';
    var val = false;
    if($(this).is(":checked")){
      val = true;
    }
    addRemoveNotification(ntype,id,val);
  })

  $('#tableData').on('click','.sms_notification',function(){
    var id = $(this).val();
    var ntype = 'sms';
    var val = false;
    if($(this).is(":checked")){
      val = true;
    }
    addRemoveNotification(ntype,id,val);
  })    
  

  
  function contacted(id){
    $.confirm({
        title: 'Confirm!',
        content: 'Are you sure want to delete?',
        buttons: {
            confirm: function () {
              $('#deleteRow').attr('action', url+"/admin/user/contact/"+id).submit();  
            },
            cancel: function () {
                return true;
            }
        }
    });
  }    


  function format ( d ) {
    
    var strd = '<table class="table-responsive table table-striped table-bordered" style="font-size:10px;">';
    strd +=      '<tr>';
    strd +=          '<th>Name:</th>';
    strd +=          '<th>Email</th>';
    strd +=          '<th>Age</th>';
    strd +=          '<th>Address</th>';
    strd +=          '<th>Height</th>';
    strd +=          '<th>Weight</th>';
    strd +=          '<th>Phone</th>';
    strd +=          '<th>Image</th>';
    strd +=          '<th>Front Image</th>';
    strd +=          '<th>Back Image</th>';
    strd +=          '<th>Side Image</th>';
    strd +=          '<th>Diet type</th>';
    strd +=          '<th>Gender</th>';
    strd +=      '</tr>';
    if(d.archive.length > 0){
      var archiveData = d.archive;
      for(i=0;i<archiveData.length;i++){
        strd +=      '<tr>';
        strd +=          '<td>'+archiveData[i].name+'</td>';
        strd +=          '<td>'+archiveData[i].email+'</td>';
        strd +=          '<td>'+archiveData[i].age+'</td>';
        strd +=          '<td>'+archiveData[i].address+'</td>';
        strd +=          '<td>'+archiveData[i].height+'</td>';
        strd +=          '<td>'+archiveData[i].weight+'</td>';
        strd +=          '<td>'+archiveData[i].phone+'</td>';
        strd +=          '<td><a id="single_image" target="_blank" href="'+archiveData[i].image+'"><img src="'+archiveData[i].image+'" style="max-width:100px;max-heigth:100px;"></a></td>';
        strd +=          '<td><a id="single_image" target="_blank" href="'+archiveData[i].image_front+'"><img src="'+archiveData[i].image_front+'" style="max-width:100px;max-heigth:100px;"></a></td>';
        strd +=          '<td><a id="single_image" target="_blank" href="'+archiveData[i].image_back+'"><img src="'+archiveData[i].image_back+'" style="max-width:100px;max-heigth:100px;"></a></td>';
        strd +=          '<td><a id="single_image" target="_blank" href="'+archiveData[i].image_side+'"><img src="'+archiveData[i].image_side+'" style="max-width:100px;max-heigth:100px;"></a></td>';
        strd +=          '<td>'+archiveData[i].diet_type+'</td>';
        strd +=          '<td>'+archiveData[i].gender+'</td>';
        strd +=      '</tr>';
      }
      
    }else{
      
      strd +=      '<tr>';
      strd +=          '<td colspan="13">No record found</td>';      
      strd +=      '</tr>';
    }
        
    strd +=    '</table>';

    $("a#single_image").fancybox();

    return strd;
}

</script>

<style>
td.details-control {
    background: url('../images/details_open.png') no-repeat center center;
    cursor: pointer;
}
tr.shown td.details-control {
    background: url('../images/details_close.png') no-repeat center center;
}
</style>
@endsection
