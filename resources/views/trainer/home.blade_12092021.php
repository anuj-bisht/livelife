@extends('layouts.trainer.app')
<!-- <link href="{{ asset('css/fullcalendar.min.css') }}" rel="stylesheet"> -->
<link href="{{ asset('lib/main.css') }}" rel="stylesheet">
@section('content')

	<!-- Navigation -->
	


	<div id="page-wrapper">
		<div class="container-fluid"> 
      
      @include('layouts.trainer.flash')
	  <!--<div class='row'>
					<ul class="nav nav-tabs">
			  <li class="active"><a data-toggle="tab" href="#cal">Calendar</a></li>
		  </ul>
		  <div class="tab-content" id="nav-tabContent">
			<div class="response"></div>
			<div id='calendar'></div>
			</div>
	  </div>-->
	  <br/>
    <div class='row'>
      <div id="media-div">
      </div>
    </div>
      <div class="row" style='min-height:65%;'>
        <div class="col-xl-6">
          <!--<div class="card bg-default">
            <div class="card-header bg-transparent">
              <div class="row align-items-center">
                <div class="col">
                  <h6 class="text-light text-uppercase ls-1 mb-1">Overview</h6>
                  <h5 class="h3 text-white mb-0">Sales value</h5>
                </div>
                <div class="col">
                  <ul class="nav nav-pills justify-content-end">
                    <li class="nav-item mr-2 mr-md-0" data-toggle="chart" data-target="#chart-sales-dark" data-update='{"data":{"datasets":[{"data":[0, 20, 10, 30, 15, 40, 20, 60, 60]}]}}' data-prefix="$" data-suffix="k">
                      <a href="#" class="nav-link py-2 px-3 active" data-toggle="tab">
                        <span class="d-none d-md-block">Month</span>
                        <span class="d-md-none">M</span>
                      </a>
                    </li>
                    <li class="nav-item" data-toggle="chart" data-target="#chart-sales-dark" data-update='{"data":{"datasets":[{"data":[0, 20, 5, 25, 10, 30, 15, 40, 40]}]}}' data-prefix="$" data-suffix="k">
                      <a href="#" class="nav-link py-2 px-3" data-toggle="tab">
                        <span class="d-none d-md-block">Week</span>
                        <span class="d-md-none">W</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </div
            </div>
            <div class="card-body">
			</div>>-->
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
                  <a href="javascript::void(0);" class="btn btn-sm btn-primary" id='today_schedule' onClick="return filter_trainer_jobs('today','schedule','schedule_table_body');">Today's</a>
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
                    <th scope="col">Total Clients</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody id="schedule_table_body">
				@if(!$slots->isEmpty())
					@foreach($slots as $k=>$slot)
						<tr>
							<td>{{ date('d/m/Y', strtotime($slot->schedule_date)) }}</td>
							<td>{{ date('h:i A', strtotime($slot->start_time)) }}  - {{  date('h:i A', strtotime($slot->end_time)) }}</td>
							<td>{{ $slot->count }} Client</td>
              <td>
								<a href="{{ route('joinRegularConfress',['slug'=>$slot->room_name]) }}" target="_blank" class="btn btn-sm btn-primary">Join</a>
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
                  <a href="javascript::void(0);" class="btn btn-sm btn-primary" id='today_test' onClick="return filter_trainer_jobs('today','test','test_table_body');">Today's</a>
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
						<tr>
							<td>{{ $testreques->username }}</td>
							<td>{{ $testreques->level_name }}</td>
							<td>@if(!empty($testreques->assign_slot)) 
								{{  date('h:i A', strtotime($testreques->assign_slot)) }}
								@endif
							</td>
							<td>
              @if(isset($testreques->room_name))
								<a href="{{ route('joinTestConfress',['slug'=>$testreques->room_name]) }}?uid={{$testreques->id}}" target="_blank" class="btn btn-sm btn-primary">Start</a>
              @endif  
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
                  <a href="javascript::void(0);" class="btn btn-sm btn-primary" id='today_demo' onClick="return filter_trainer_jobs('today','demo','demo_table_body');">Today's</a>
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
						<tr>
							<td>{{ $demo_request->username }}</td>
							<td>{{ $demo_request->category_name }}</td>
							<td> {{ date('h:i A', strtotime($demo_request->slot_time)) }} </td>
							<td>
								<a href="{{ route('joinDemoConfress',['slug'=>$demo_request->room_name]) }}" target="_blank" class="btn btn-sm btn-primary">Join</a>
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
  <script src="//media.twiliocdn.com/sdk/js/video/v1/twilio-video.min.js"></script>     
  <!-- <script src="{{ asset('js/fullcalendar.min.js') }}"></script>       -->
 

  
  <script>
        
    var url = "{{url('/')}}";


    var trainer_id =   "{{ Auth::user()->id }}";
	var events = <?php echo $sc_array; ?>;
    /* var events = [{
    title: 'event1',
    start: '2021-02-12'
}, {
    title: 'event2',
    start: '2013-12-05',
    end: '2013-12-07'
}, {
    title: 'event3',
    start: '2013-12-15',
}]; */
    
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
				
				$("#"+remove+"_"+filter_type_job).removeClass('btn-primary');
				$("#"+type+"_"+filter_type_job).addClass('btn-primary');
				$("#"+filter_type_job_table_id).html(view);
			}
		});
}

 
</script>

<style>
.hasEvent {
    background-color: blue;
}
.fc-button{
	//background-color: cornflowerblue !important;
	background-color: #bc0e83!important;
}
	/* .fc-toolbar-title
	{
		font-size:12px !important;
	} */
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
