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
					<h1 class="page-header">{{__('Schedule List')}}</h1>
				</div>            
			</div>  
      <div class="row">
        <div class="col-md-12">
          <a class="btn btn-success" href="{{ route('schedules.create') }}"> Add Manual</a>
        </div>
      </div>
      @include('layouts.flash')
      <br/>
      <ul class="nav nav-tabs">
          <li class="active"><a data-toggle="tab" href="#cal">Calendar</a></li>
          <li><a data-toggle="tab" href="#list">List</a></li>
      </ul>

      <div class="tab-content" id="nav-tabContent">
        <div id="cal" class="tab-pane fade active in">
          <div class="response"></div>
          <div id='calendar'></div>
        </div>
        <br/>

      <div class="row" >
        <div class="col-md-2">
          <select id="trainerFilter" class="form-control">
              <option value="0">Assign Trainers</option>
              @if(count($trainers)>0)
                @foreach($trainers as $v)
                  <option value="{{$v->id}}">{{$v->name}}</option>
                @endforeach
              @endif          
          </select>
        </div>
        <div class="col-md-2">
          <select id="trainerFilter" class="form-control">
              <option value="0">Assign Trainers</option>
              @if(count($trainers)>0)
                @foreach($trainers as $v)
                  <option value="{{$v->id}}">{{$v->name}}</option>
                @endforeach
              @endif          
          </select>
        </div>
        <div class="col-md-2">
          <input id="textRangeFrom" placeholder="Date From" type="text" class="form-control" value=""/><span class="calendarIcon">          
        </div>
        <div class="col-md-2">                    
          <input id="textRangeTo" placeholder="Date To" type="text"  class="form-control" value=""/><span class="calendarIcon">
        </div>
        <div class="col-md-2">             
          <button id="dateRangeBtn" class="btn btn-primary">Go</button>
        </div>
      </div>
      <br/>  
      <div class="row">        
        
        <div class="col-lg-3">
          <select id="userFilter" class="form-control">
              <option value="0">User Filter</option>
              @if(count($users)>0)
                @foreach($users as $v)
                  <option value="{{$v->id}}">{{$v->name}}</option>
                @endforeach
              @endif          
          </select>
        </div>
        <div class="col-lg-3">
          <select id="categoryFilter" class="form-control">
              <option value="0">Category Filter</option>
              @if(count($categories)>0)
                @foreach($categories as $v)
                  <option value="{{$v->id}}">{{$v->name}}</option>
                @endforeach
              @endif          
          </select>
        </div>

        
      </div>
        <br/>  
        <div id="list" class="tab-pane fade">                  
            <table id="tableData" class="table-responsive table table-striped table-bordered" style="font-size:12px;width:100% !important">              
                <thead>
                    <tr>                                                                            
                        <th>User</th>                      
                        <th>Category</th>      
                        <th>Title</th>      
                        <th>Start</th>      
                        <th>End</th>      
                        <th>Action</th> 
                    </tr>
                </thead>
                <tbody>
                              
                </tbody>
                <tfoot>
                    <tr>                                                                            
                        <th>User</th>                      
                        <th>Category</th>      
                        <th>Title</th>      
                        <th>Start</th>      
                        <th>End</th>      
                        <th>Action</th> 
                    </tr>
                </tfoot>
            </table>  
        </div>
      </div>

		</div>		
	</div>
	<!-- /#page-wrapper -->



<div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="schedule_form" action="">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="eventModalLabel">Add/Edit Schedule</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">        
            <div class="form-group">
              <label for="recipient-name" class="col-form-label">Title:</label>
              <input type="text" name="title" class="form-control" id="title">

              <input type="hidden" name="start_date" id="start_date">
              <input type="hidden" name="end_date" id="end_date">
            </div>
            <div class="form-group">
              <label for="message-text" class="col-form-label">Category:</label>
              {!! Form::select('catList', $catList,$catList, array('class' => 'form-control')) !!}
              
            </div>  
            <div class="form-group">
              <label for="message-text" class="col-form-label">Start Time:</label>
              <select name="start_hour" class="form-controll">
                @php
                $k = '';
                for($i=0;$i<=24;$i++){
                  if($i <= 9){
                    $k = '0'.$i;
                  }else{
                    $k = "$i"; 
                  }                
              
              @endphp
              <option value="{{$k}}">{{$k}}</option>              
              @php
                }                
                @endphp
                
              </select>
              <select name="start_minute">
                @php           
                $k = 0;   
                for($i=0;$i<=12;$i++){
                  
                  $k = $i*5;
                  if($i == 0 || $i==1){
                    $k = '0'.$k;
                  }

              @endphp
              <option value="{{$k}}">{{$k}}</option>              
              @php
                }                
                @endphp
                
              </select>
              <label for="message-text" class="col-form-label">End Time:</label>
              <select name="end_hour" class="form-controll">
                @php
                $k = '';
                for($i=0;$i<=24;$i++){
                  if($i <= 9){
                    $k = '0'.$i;
                  }else{
                    $k = "$i"; 
                  }                
              
              @endphp
              <option value="{{$k}}">{{$k}}</option>              
              @php
                }                
                @endphp
                
              </select>
              <select name="end_minute">
                @php           
                $k = 0;   
                for($i=0;$i<=12;$i++){
                  
                  $k = $i*5;
                  if($i == 0 || $i==1){
                    $k = '0'.$k;
                  }

              @endphp
              <option value="{{$k}}">{{$k}}</option>              
              @php
                }                
                @endphp
                
              </select>
            </div>        
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

    $(document).ready(function(){
        // $('#eventModal').on('show.bs.modal', function (event) {
        //   var button = $(event.relatedTarget) // Button that triggered the modal
        //   var recipient = button.data('whatever') // Extract info from data-* attributes
        //   // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
        //   // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
        //   var modal = $(this)
        //   modal.find('.modal-title').text('New message to ' + recipient)
        //   modal.find('.modal-body input').val(recipient)
        // })

        $("#schedule_form").submit(function(event){
         // alert('dadsfasdf')
            event.preventDefault();

            $.ajax({
                    url:'{{url('/')}}/admin/schedules/addevent',
                    type:'POST',
                    headers: {
                      'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                    },
                    data:$(this).serialize(),
                    success:function(result){
                        console.log(result);
                        if(result.status){
                          $('#eventModal').modal('hide');     
                          window.location.reload();
                        }else{
                          $.alert({
                              title: 'Alert!',
                              content: result.message,
                          });
                        }                        
                    }
            });
        });

    });

      
    
    
  document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
      height: '100%',
      expandRows: true,
      //slotMinTime: '08:00',
      //slotMaxTime: '20:00',
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
      },
      initialView: 'dayGridMonth',
      initialDate: '{{date("Y-m-d")}}',
      select: function (start, end, allDay) {
        //console.log(start);
        //start.endStr
        $('#start_date').val(start.startStr);
        $('#end_date').val(start.endStr);
        $('#eventModal').modal('show');        
        //alert('sdsdf');
      },
      eventDrop: function (event, delta) {
          $.ajax({
              type: "POST",
              headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
              },
              url: "{{url('/')}}/admin/schedules/drop",
              data: 'title=' + event.event.title + '&start=' + event.event.startStr + '&end=' + event.event.endStr + '&id=' + event.event.id,
              success: function (response) {
                  if(response.status == 1) {
                      //alert('asdf=='+event.event.id)
                      //$('#calendar').fullCalendar('removeEvents', event.event.id);
                      //displayMessage("Deleted Successfully");
                      window.location.reload();
                  }
              }
          });
        },
        eventClick: function (event) {
          //console.log(event.event.id); return false;
          $.confirm({
              title: 'Confirm!',
              content: 'Are you sure want to delete?',
              buttons: {
                  confirm: function () {
                    $.ajax({
                        type: "POST",
                        headers: {
                          'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                        },
                        url: "{{url('/')}}/admin/schedules/delete",
                        data: "&id=" + event.event.id,
                        success: function (response) {
                            if(response.status == 1) {
                                //alert('asdf=='+event.event.id)
                                //$('#calendar').fullCalendar('removeEvents', event.event.id);
                                //displayMessage("Deleted Successfully");
                                window.location.reload();
                            }
                        }
                    });
                  },
                  cancel: function () {
                      return true;
                  }
              }
          });          
      },
      navLinks: true, // can click day/week names to navigate views
      editable: true,
      selectable: true,
      nowIndicator: true,
      dayMaxEvents: true, // allow "more" link when too many events
      events: "{{url('/')}}/admin/schedules/getschedule"
    });

    calendar.render();
  });


  function displayMessage(message) {
        $(".response").html("<div class='success'>"+message+"</div>");
      setInterval(function() { $(".success").fadeOut(); }, 1000);
  }



function deleteData(id){

  $.confirm({
      title: 'Confirm!',
      content: 'Are you sure want to delete?',
      buttons: {
          confirm: function () {
            $('#deleteRow').attr('action', url+"/admin/plans/").submit();  
          },
          cancel: function () {
              return true;
          }
      }
  });

}        


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
              'url': '{{ url("/") }}/admin/schedules/ajaxData',
              'headers': {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
              },
              'type': 'post',
              'data': function(d) {
                //d.statusFilter = jQuery('#statusFilter').val();
                d.category = jQuery('#categoryFilter option:selected').val();
                d.user = jQuery("#userFilter option:selected").val();
              },
            },          

            'columns': [
                              
              {
                  'data': 'username',
                  'className': 'col-md-3',
                  'render': function(data,type,row){
                    
                    return row.username+'('+row.useremail+')';
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
                  'data': 'title',
                  'className': 'col-md-3',
                  'render': function(data,type,row){
                    
                    return row.title;
                  }
              },
              {
                  'data': 'start',
                  'className': 'col-md-1',
                  'render': function(data,type,row){
                    
                    return row.start;
                  }
              },
              {
                  'data': 'end',
                  'className': 'col-md-1',
                  'render': function(data,type,row){
                    
                    return row.end;
                  }
              },            
              {
                'data': 'Action',
                'orderable': false,
                'className': 'col-md-2',
                'render': function(data, type, row) {
                  var buttonHtml = '<a class="btn btn-primary" href="'+url+'/admin/schedules/'+row.id+'/edit">Edit</a>&nbsp;&nbsp;<a class="btn btn-danger" href="javascript:void(0);" onclick="deleteData('+row.id+')">Delete</a>';
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
          

  });



function deleteData(id){

  $.confirm({
      title: 'Confirm!',
      content: 'Are you sure want to delete?',
      buttons: {
          confirm: function () {
            $('#deleteRow').attr('action', url+"/admin/recipes/").submit();  
          },
          cancel: function () {
              return true;
          }
      }
  });

}        



 
</script>
<style>
#calendar-container {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
  }

  .fc-header-toolbar {
    /*
    the calendar will be butting up against the edges,
    but let's scoot in the header's buttons
    */
    padding-top: 1em;
    padding-left: 1em;
    padding-right: 1em;
  }
  </style>

@endsection
