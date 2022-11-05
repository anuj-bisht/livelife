@extends('layouts.app')

@section('content')

	<!-- Navigation -->
	@include('layouts.left')

	<div id="page-wrapper">
		<div class="container-fluid">


      <div class="row">
				<div class="col-lg-12">
					<h1 class="page-header">{{__('New Bookings')}}</h1>
				</div>
				<!-- /.col-lg-12 -->
			</div>  

      <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>&nbsp;</h2>
            </div>
            
        </div>
      </div>

      @include('layouts.flash')
            
      <table id="tableData" class="table-responsive table table-striped table-bordered" style="font-size:12px;width:100% !important">
          
          <thead>
              <tr>                                                                            
                  <th>OrderId</th>                                          
                  <th>User</th>                                                                                               
                  <th>Plan</th>
                  <th>Category</th>                             
                  <th>Amount</th>                                          
                  <th>Action</th> 
              </tr>
          </thead>
          <tbody>
                        
          </tbody>
          <tfoot>
              <tr>                                                                            
                  <th>OrderId</th>                                          
                  <th>User</th>                                                                                                    
                  <th>Plan</th>
                  <th>Category</th>                             
                  <th>Amount</th>                                          
                  <th>Action</th> 
              </tr>
          </tfoot>
      </table>  


		</div>		
	</div>
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
                columns: [0, 1]//"thead th:not(.noExport)"
              },
              className: 'btn btn-default',
                init: function(api, node, config) {
                  $(node).removeClass('dt-button')
                },
            },
            {extend: 'pdfHtml5',
              exportOptions: {
                columns: [0, 1] //"thead th:not(.noExport)"
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
              'url': '{{ url("/") }}/admin/orders/ajaxData',
              'headers': {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
              },
              'type': 'post',
              'data': function(d) {
                //d.statusFilter = jQuery('#statusFilter').val();
                d.parent = jQuery('#parentFilter option:selected').val();
                //d.search = jQuery("#msds-select option:selected").val();
              },
            },          

            'columns': [
                              
              {
                  'data': 'order_id',
                  'className': 'col-md-2',
                  'render': function(data,type,row){
                    
                    return row.order_id;
                  }
              },
              {
                  'data': 'user',
                  'className': 'col-md-2',
                  'render': function(data,type,row){
                    
                    return row.username;
                  }
              },
              {
                  'data': 'plan',
                  'className': 'col-md-2',
                  'render': function(data,type,row){
                    
                    return row.plan_name;
                  }
              },
              {
                  'data': 'category_name',
                  'className': 'col-md-2',
                  'render': function(data,type,row){
                    
                    return row.category_name;
                  }
              },
              {
                  'data': 'amount',
                  'className': 'col-md-2',
                  'render': function(data,type,row){
                    
                    return row.amount;
                  }
              },            
              {
                'data': 'Action',
                'orderable': false,
                'className': 'col-md-2',
                'render': function(data, type, row) {
                  var buttonHtml = '<a class="btn btn-danger" data-id="'+row.id+'" data-toggle="modal" onclick="openModal('+row.id+')">Assign</a>';
                  return buttonHtml;
                }
              }
            ]
          });   



          $("#make_schedule_form").submit(function(e) {
                
                e.preventDefault(); 

                var form = $(this);
                var url = "{{url('/')}}/admin/orders/makeSchedule";
                $.ajax({
                      type: "POST",
                      url: url,
                      headers: {
                          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                      },
                      data: form.serialize(),
                      success: function(data){
                        if(data.status){
                          $.alert({
                              title: 'Success!',
                              content: data.message,
                          });
                          $('#exampleModal').modal('hide');
                          table.draw();
                        }else{
                          $.alert({
                              title: 'Alert!',
                              content: data.message,
                          });
                        }
                      }
              });
          });      

  });



function deleteData(id){

  $.confirm({
      title: 'Confirm!',
      content: 'Are you sure want to delete?',
      buttons: {
          confirm: function () {
            $('#deleteRow').attr('action', "{{ url('/') }}/admin/tips/").submit();  
          },
          cancel: function () {
              return true;
          }
      }
  });

}        

function openModal(id){
  
  $('#exampleModal').modal('toggle');  
  $.ajax({
    method: "POST",
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    url: "{{url('/')}}/admin/orders/getorderbyid",
    data: { id: id}
  })
  .done(function( msg ) {
    var resdata = msg.data.order;
    //console.log(msg.data.order);
    $('#username_table').html(resdata.username);
    $('#category_table').html(resdata.category_name);
    $('#slot_table').html(resdata.slot_start_time + ' - '+resdata.slot_end_time);
    $('#form_category_id').val(resdata.category_id);
    $('#form_plan_id').val(resdata.plan_id);
    $('#form_order_id').val(resdata.id);
    $('#form_user_id').val(resdata.user_id);
    
    // console.log(msg.data.order.slot_id);
    var slot_data = resdata = msg.data.slots;
    if(slot_data){
      var option = '<option value="0">Select Slot</option>';
      
      $.each(slot_data, function( i, item ){
      
        // console.log(item.id);
        // console.log(msg.data.order.slot_id);
        var selected = '';
        if(msg.data.order.slot_id == item.id){
          var weeks = item.batch.split(",");
          $.each(weeks,function(index,el){
            // console.log(el);
            $('#'+el).attr ( "checked" ,"checked" );
            // $('#'+index).checked()
          });
          // console.log(item.id);
          selected = 'selected="true"';
        }
        option += '<option value="'+item.id+'" '+selected+'>'+item.start_time + ' - '+ item.end_time +'</option>';
      });
      $('#slot_id').empty().append(option);
    }

    var trainers = msg.data.trainers;
    if(trainers){
      var option = '<option value="0">Select Trainer</option>';
      var selected = '';
      $.each(trainers, function( i, item ){        
        option += '<option value="'+item.id+'">'+item.name + ' - '+ item.email +'</option>';
      });
      $('#trainer_id').empty().append(option);
    }
    
    
  });
}

function changeSlot(){
  var id = $('#form_order_id').val();
  var slotid = document.getElementById('slot_id').value;
  console.log(slotid);
  $.ajax({
    method: "POST",
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    url: "{{url('/')}}/admin/orders/getorderbyid",
    data: { id: id}
  })
  .done(function( msg ) {
    var resdata = msg.data.order;
    //console.log(msg.data.order);
    $('#username_table').html(resdata.username);
    $('#category_table').html(resdata.category_name);
    $('#slot_table').html(resdata.slot_start_time + ' - '+resdata.slot_end_time);
    $('#form_category_id').val(resdata.category_id);
    $('#form_plan_id').val(resdata.plan_id);
    $('#form_order_id').val(resdata.id);
    $('#form_user_id').val(resdata.user_id);
    

    var slot_data = resdata = msg.data.slots;
    if(slot_data){
      var option = '<option value="0">Select Slot</option>';
      
      $.each(slot_data, function( i, item ){
      
        var selected = '';
        if(slotid == item.id){
          $('.remove-attributes').removeAttr("checked" ,"checked");
          var weeks = item.batch.split(",");
          $.each(weeks,function(index,el){
            // console.log(el);
            $('#'+el).attr ( "checked" ,"checked" );
            // $('#'+index).checked()
          });
          selected = 'selected="true"';
        }
        option += '<option value="'+item.id+'" '+selected+'>'+item.start_time + ' - '+ item.end_time +'</option>';
      });
      // $('#slot_id').empty().append(option);
    }

    var trainers = msg.data.trainers;
    if(trainers){
      var option = '<option value="0">Select Trainer</option>';
      var selected = '';
      $.each(trainers, function( i, item ){        
        option += '<option value="'+item.id+'">'+item.name + ' - '+ item.email +'</option>';
      });
      $('#trainer_id').empty().append(option);
    }
    
    
  });

}

</script>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Make Schedule</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table cellspecing="5" cellpadding="5" width="100%" style="margin:15px">
          <tr>
            <th style="width:30%">Name:</th>
            <td style="width:70%" id="username_table" style="text-align:left"></td>
          </tr>
          <tr>
            <th>Category:</th>
            <td id="category_table"></td>
          </tr>
          <tr>
            <th>Selected Slot:</th>
            <td id="slot_table"></td>
          </tr>
        </table>
        <form id="make_schedule_form" action="" name="make_schedule_form">
          <input type="hidden" name="user_id" value="0" id="form_user_id">
          <input type="hidden" name="category_id" value="0" id="form_category_id">
          <input type="hidden" name="plan_id" value="0" id="form_plan_id">
          <input type="hidden" name="order_id" value="0" id="form_order_id">
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Trainers:</label>
            <select name="trainer_id" id="trainer_id" class="form-control">
                <option value="0">Select Trainer</option>
            </select>
          </div>

          

          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Slots:</label>
            <select name="slot_id" id="slot_id" onchange="changeSlot(this)" class="form-control">
                <option value="0">Select Slot</option>
            </select>
          </div>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Schedule type:</label>
            {!! Form::checkbox('type[]', 1,null,array('id'=>'Mon','class'=>'remove-attributes')) !!} Mon &nbsp;
            {!! Form::checkbox('type[]', 2,null,array('id'=>'Tue','class'=>'remove-attributes')) !!} Tue &nbsp;
            {!! Form::checkbox('type[]', 3,null,array('id'=>'Wed','class'=>'remove-attributes')) !!} Wed &nbsp;
            {!! Form::checkbox('type[]', 4,null,array('id'=>'Thu','class'=>'remove-attributes')) !!} Thu &nbsp;
            {!! Form::checkbox('type[]', 5,null,array('id'=>'Fri','class'=>'remove-attributes')) !!} Fri &nbsp;
            {!! Form::checkbox('type[]', 6,null,array('id'=>'Sat','class'=>'remove-attributes')) !!} Sat &nbsp;
            {!! Form::checkbox('type[]', 7,null,array('id'=>'Sun','class'=>'remove-attributes')) !!} Sun &nbsp;
           
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-danger">Assign</button>
            
          </div>
        </form>
      </div>
      
    </div>
  </div>
</div>


@endsection
