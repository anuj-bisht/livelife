@extends('layouts.app')
<!-- <link href="{{ asset('css/fullcalendar.min.css') }}" rel="stylesheet"> -->
<link href="{{ asset('lib/main.css') }}" rel="stylesheet">
@section('content')

	<!-- Navigation -->
	@include('layouts.left')

	<div id="page-wrapper">
		<div class="container-fluid">


      <div class="row">
				<div class="col-md-8">
					<h1 class="page-header">{{__('Re-schedule Request')}}</h1>
				</div>            
			</div>  
      
      @include('layouts.flash')
      
                      
            <table id="tableData" class="table-responsive table table-striped table-bordered" style="font-size:12px;width:100% !important">              
                <thead>
                    <tr>                                                                            
                        <th>User</th>                      
                        <th>Category</th>      
                        <th>Trainer</th>      
                        <th>Date</th>                              
                        <th>slots</th>                              
                        <th>Action</th> 
                    </tr>
                </thead>
                <tbody>
                              
                </tbody>
                <tfoot>
                    <tr>                                                                            
                        <th>User</th>                      
                        <th>Category</th>      
                        <th>Trainer</th>      
                        <th>Date</th>                              
                        <th>slots</th>                              
                        <th>Action</th> 
                    </tr>
                </tfoot>
            </table>  
        </div>
      

		</div>		
	</div>
	<!-- /#page-wrapper -->



<div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="reschedule_form" action="">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="eventModalLabel">Re-Schedule</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">        
            <div class="form-group">
              <label for="recipient-name" class="col-form-label">Trainers:</label>              
              <select name="trainers" id="trainer_list" class="form-control">
                <option value="0">Choose Trainer</option>
              </select>
              <input type="hidden" name="schedule_id" value="" id="schedule_id">
            </div>
            <div class="form-group">
              <label for="recipient-name" class="col-form-label">Date:</label>
              {!! Form::text('date_start', '', array('placeholder' => 'select date' , 'class' => 'form-control','id'=>'date_start')) !!}
            </div>
            <div class="form-group">
              <label for="recipient-name" class="col-form-label">Slots:</label>
              <select name="slot_id" id="slot_id_dd" class="form-control">
                <option value="0">Choose slot</option>
              </select>              
            </div>
              
            
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Submit</button>
        </div>
      </div>
     </form>
  </div>
</div>

  <script src="{{ asset('lib/main.js') }}"></script>      
  <!-- <script src="{{ asset('js/fullcalendar.min.js') }}"></script>       -->

  
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
              'url': '{{ url("/") }}/admin/schedules/rescheduleAjax',
              'headers': {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
              },
              'type': 'post',
              'data': function(d) {
                //d.statusFilter = jQuery('#statusFilter').val();               
              },
            },          

            'columns': [
                              
              {
                  'data': 'username',
                  'className': 'col-md-2',
                  'render': function(data,type,row){
                    
                    return row.username;
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
                  'data': 'trainer',
                  'className': 'col-md-2',
                  'render': function(data,type,row){
                    
                    return row.trainer_name;
                  }
              },
              {
                  'data': 'date',
                  'className': 'col-md-2',
                  'render': function(data,type,row){
                    
                    return row.sstart;
                  }
              },
              {
                  'data': 'slot',
                  'className': 'col-md-2',
                  'render': function(data,type,row){
                    
                    return row.start_time+'-'+row.end_time;
                  }
              },            
              {
                'data': 'Action',
                'orderable': false,
                'className': 'col-md-2',
                'render': function(data, type, row) {
                  var buttonHtml = '<a class="btn btn-success" onclick="openpopup('+row.id+')">Reschedule</a>';
                  return buttonHtml;
                }
              }
            ]
          });       


          $('#textRangeFrom').datePicker(
            {               
              monthCount: 3, 
              range: '#textRangeTo',             
            }
          );    

          $('#dateRangeBtn').on('click',function(){
            table.draw();
          });     

          $('#userFilter').change(function () {	    
            table.draw();
          });    
          
          $('#date_start').datePicker({
			dateFormat: 'yyyy-mm-dd'	
		  });     
		  
		  
		  
		  $('#reschedule_form').submit(function(e){
			e.preventDefault();

			$.ajax({
				type: "POST",
				url: "{{url('/')}}/admin/schedules/reschedulesubmit",    
				headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				data: $('#reschedule_form').serialize(),           
				success: function(res) {
					$('#eventModal').modal('hide');
					table.draw();		  
				},
				error:function(request, status, error) {
					console.log("ajax call went wrong:" + request.responseText);
				}
			});
			
		  });
		  
		  
          

  });


function openpopup(id){

  var url = "{{url('/')}}/admin/schedules/getTrainerAndSlot"; 

  $.ajax({
      type: "POST",
      url: url,      
      data: {
        "_token": $('meta[name="csrf-token"]').attr('content'),
        "id": id            
      },
      success: function(res) {
        $('#eventModal').modal('show');  
        var str = '<option value="0">Choose Trainer</option>';
        var res1 = res.data.trainers;
        var selected = "";
        $.each(res1, function( index, value ) {
          
          if(value.id==res.data.schedule.trainer_id){
			  selected = "selected";
		  }else{
			  selected = "";
		  }
          str += '<option value="'+value.id+'" '+selected+'>'+value.name+'</option>';
        });
        $('#trainer_list').empty().append(str);
        
        str = '';
        $.each(res.data.slots, function( index, value ) {      		  	   
          str += '<option value="'+value.id+'">'+value.start_time+'-'+value.end_time+'</option>';
        });
        $('#slot_id_dd').empty().append(str);
		
		$('#date_start').val(res.data.schedule.start);
		$('#schedule_id').val(id);
                          
      },
      error:function(request, status, error) {
          console.log("ajax call went wrong:" + request.responseText);
      }
  });

}
</script>
<style>
.jqueryDatePicker{z-index: 10000 !important;}
</style>

@endsection
