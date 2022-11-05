@extends('layouts.client.app')
<!-- <link href="{{ asset('css/fullcalendar.min.css') }}" rel="stylesheet"> -->
<link href="{{ asset('lib/main.css') }}" rel="stylesheet">
@section('content')

	<!-- Navigation -->
	


	<div id="page-wrapper">
		<div class="container-fluid"> 
      
      @include('layouts.client.flash')
	  
	  <br/>
    <div class='row'>
      <div id="media-div">
      </div>
    </div>
      <div class="row" style='min-height:65%;'>
        <div class="col-xl-6">
          
              <div class="response"></div>
				<div id='calendar'></div>
        </div>
        <div class="col-xl-6">
          <div class="card">
            <div class="card-header border-0">
              <div class="row align-items-center">
                <div class="col">
                  <h3 class="mb-0">Regular Schedule</h3>
                </div>
                <div class="col text-right">
                  <a href="javascript::void(0);" class="btn btn-sm btn-magento" id='today_schedule' onClick="return filter_trainer_jobs('today','schedule','schedule_table_body');">Today's</a>
				   <a href="javascript::void(0);" class="btn btn-sm" id='all_schedule' onClick="return filter_trainer_jobs('all','schedule','schedule_table_body');">All Schedule</a>
                </div>
              </div>
            </div>
            <div class="table-responsive">
              <!-- Projects table -->
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">Schedule Date</th>
                    <th scope="col">Start - End</th>
                    <th scope="col">Total Users</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody id="schedule_table_body">
				@if(!$slots->isEmpty())
					@foreach($slots as $k=>$slot)
          @php
            $enable = false;
            $s_date = date('Y-m-d', strtotime($slot->schedule_date));
            if($s_date==date('Y-m-d')){
              
              $dbtime = strtotime($slot->start_time);              
              $currtime = time();
              $mins = abs(($currtime - $dbtime) / 60);                            
              if($mins <= 15){
                $enable = true;
              }
            } 
          @endphp
						<tr>
							<td>{{ date('Y-m-d', strtotime($slot->schedule_date)) }}</td>
							<td>{{ date('h:i A', strtotime($slot->start_time)) }}  - {{  date('h:i A', strtotime($slot->end_time)) }}</td>
							<td>{{ $slot->count }} Client</td>
              <td>
								<a href="{{ route('joinRegularConfress',['slug'=>$slot->room_name]) }}" target="_blank" class="@if(!$enable) disabled @endif btn btn-sm btn-primary">Join</a>
							</td>
						</tr>
						
					@endforeach
					@else	
					<tr>
						<td colspan='4'>No regular schedule found</td>
					</tr>
				@endif
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
	  <br/>
      <div class="row">
        <div class="col-xl-6">
          <div class="card">
            <div class="card-header border-0">
              <div class="row align-items-center">
                <div class="col">
                  <h3 class="mb-0">Test Requests</h3>
                </div>
                <div class="col text-right">
                  <a href="javascript::void(0);" class="btn btn-sm btn-magento" id='today_test' onClick="return filter_trainer_jobs('today','test','test_table_body');">Today's</a>
				   <a href="javascript::void(0);" class="btn btn-sm" id='all_test' onClick="return filter_trainer_jobs('all','test','test_table_body');">All Schedule</a>
                </div>
              </div>
            </div>
            <div class="table-responsive">
              <!-- Projects table -->
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">Client Name</th>
                    <th scope="col">Level</th>
                    <th scope="col">Slot</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody id="test_table_body">
				@if(!$testrequest->isEmpty())
                  @foreach($testrequest as $k=>$testreques)

                  @php
                   $s_date = strtotime($testreques->assign_slot); 
                   $todayDate = time();
                   $enable = false;
                   $mins = abs(($s_date - $todayDate) / 60);                            
                   if($mins <= 15){
                    $enable = true;
                   }
                   
                  @endphp

						<tr>
							<td>{{ $testreques->username }}</td>
							<td>{{ $testreques->level_name }}</td>
							<td>@if(!empty($testreques->assign_slot)) 
								{{  date('h:i A', strtotime($testreques->assign_slot)) }}
								@endif
							</td>
							<td>
								<a href="{{ route('joinTestConfress',['slug'=>$testreques->room_name]) }}" target="_blank" class="@if(!$enable) disabled @endif btn btn-sm btn-primary">Start</a>
							</td>
						</tr>
						
					@endforeach
					@else	
						<tr>
							<td colspan='4'>No test request found</td>
						</tr>
					@endif
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="col-xl-6">
          <div class="card">
            <div class="card-header border-0">
              <div class="row align-items-center">
                <div class="col">
                  <h3 class="mb-0">Request Demo</h3>
                </div>
                <div class="col text-right">
                  <a href="javascript::void(0);" class="btn btn-sm btn-magento" id='today_demo' onClick="return filter_trainer_jobs('today','demo','demo_table_body');">Today's</a>
				   <a href="javascript::void(0);" class="btn btn-sm" id='all_demo' onClick="return filter_trainer_jobs('all','demo','demo_table_body');">All Schedule</a>
                </div>
              </div>
            </div>
            <div class="table-responsive">
              <!-- Projects table -->
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">Client Name</th>
                    <th scope="col">Category</th>
                    <th scope="col">Slot</th>
					<th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody id="demo_table_body">
				@if(!$demorequest->isEmpty())
                  @foreach($demorequest as $k=>$demo_request)

                  @php
                   $s_date = strtotime($demo_request->slot_time); 
                   $todayDate = time();
                   $enable = false;
                   $mins = abs(($s_date - $todayDate) / 60);                            
                   if($mins <= 15){
                    $enable = true;
                   }
                   
                  @endphp
						<tr>
							<td>{{ $demo_request->username }}</td>
							<td>{{ $demo_request->category_name }}</td>
							<td> {{ date('h:i A', strtotime($demo_request->slot_time)) }} </td>
							<td>
								<a href="{{ route('joinDemoConfress',['slug'=>$demo_request->room_name]) }}" target="_blank" class="@if(!$enable) disabled @endif btn btn-sm btn-primary">Join</a>
							</td>
						</tr>
						
					@endforeach
					@else	
						<tr>
							<td colspan='4'>No demo request found</td>
						</tr>
					@endif
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

		</div>		
	</div>
	


	<!-- /#page-wrapper -->

  <script src="{{ asset('lib/main.js') }}"></script>      
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>    
  
  <!-- <script src="{{ asset('js/fullcalendar.min.js') }}"></script>       -->
 

  
  <script>
        
    var url = "{{url('/')}}";


    var current_user_id = "{{Auth::user()->id}}";

    var trainer_id =   "{{ Auth::user()->id }}";
	var events = <?php echo $sc_array; ?>;
    
    
  document.addEventListener('DOMContentLoaded', function() {
  var calendarEl = document.getElementById('calendar');


  var calendar = new FullCalendar.Calendar(calendarEl, {
    selectable: true,
	height: '100%',
	expandRows: true,
    headerToolbar: {
      left: 'prev,next',
      center: 'title',
      //right: 'dayGridMonth,timeGridWeek,timeGridDay'
      right: ''
    },
    dateClick: function(info) {
	   $.ajax({
			type: "POST",
			headers: {
			  'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
			},
			url: "{{url('/')}}/trainer/schedules/trainer",
			data: {date:info.dateStr,trainer_id:trainer_id},
			beforeSend: function() {
					$("#trainer_schedule_modal").modal('show');
					$("#trainer_schedule_modal_body").html('loading....');
				},
			success: function (view) {
					$("#trainer_schedule_modal_body").html(view);
			}
		});
    },
    /* select: function(info) {
      alert('selected ' + info.startStr + ' to ' + info.endStr);
    }, */
	  //navLinks: true, // can click day/week names to navigate views
      editable: true,
      selectable: true,
      nowIndicator: true,
      dayMaxEvents: false, // allow "more" link when too many events
      //events: "{{url('/')}}/trainer/schedules/trainer_month_schedule"
      events: events,
	  eventClick: function(info) {
		$.ajax({
			type: "POST",
			headers: {
			  'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
			},
			url: "{{url('/')}}/trainer/schedules/trainer",
			data: {date:info.event.start,trainer_id:trainer_id},
			beforeSend: function() {
					$("#trainer_schedule_modal").modal('show');
					$("#trainer_schedule_modal_body").html('loading....');
				},
			success: function (view) {
					$("#trainer_schedule_modal_body").html(view);
			}
		});
	  }
  });

  calendar.render();
});


function filter_trainer_jobs(type,filter_type_job,filter_type_job_table_id){
	$.ajax({
			type: "get",
			url: "{{url('/')}}/trainer/schedules/filter_trainer_jobs",
			data: {type:type,filter_type_job:filter_type_job},
			beforeSend: function() {
					$("#"+filter_type_job_table_id).html('<tr><td colspan="4">loading data please wait..</td></tr>');
				},
			success: function (view) {
				if(type == 'today')
					var remove = 'all';
				else
					var remove = 'today';
				
				$("#"+remove+"_"+filter_type_job).removeClass('btn-magento');
				$("#"+type+"_"+filter_type_job).addClass('btn-magento');
				$("#"+filter_type_job_table_id).html(view);
			}
		});
}


$('.disabled').click(function(e){
    e.preventDefault();
})

 setInterval(() => {
  location.reload();
 }, 60000);


 
</script>

<style>
.hasEvent {
    background-color: blue;
}
.fc-button{
  background-color: cornflowerblue !important;
}
	/* .fc-toolbar-title
	{
		font-size:12px !important;
	} */

  .disabled {
  pointer-events: none;
  cursor: default;
}



</style>
@endsection

<!-- Modal -->
<div id="trainer_schedule_modal" class="modal fade" role="dialog" style='opacity:1;'>
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <!--<button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Schedules</h4>-->
      </div>
      <div class="modal-body" id="trainer_schedule_modal_body">
      
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>






