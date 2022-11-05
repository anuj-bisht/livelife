 <div class="table-responsive">
  <!-- Projects table -->
  <div>Regular Schedule</div>
  <table class="table align-items-center table-flush">
	<thead class="thead-light">
	  <tr>
		<th scope="col">Schedule date</th>
		<th scope="col">Start-end</th>
		<th scope="col">Total Clients</th>
	  </tr>
	</thead>
	<tbody>
	@if(!$schedules->isEmpty())
	  @foreach($schedules as $k=>$schedule)
			<tr>
				<td>{{ date('d/m/Y', strtotime($schedule->schedule_date)) }}</td>
				<td>{{ date('h:i A', strtotime($schedule->start_time)) }}  - {{  date('h:i A', strtotime($schedule->end_time)) }}</td>
				<td>{{ $schedule->count }}</td>
			</tr>
			
		@endforeach
		@else	
			<tr>
				<td colspan='3'>No regular schedule for selected date</td>
			</tr>
		@endif
	</tbody>
  </table>
</div>

<div class="table-responsive">
  <!-- Projects table -->
  <div>Test Request</div>
  <table class="table align-items-center table-flush">
	<thead class="thead-light">
	  <tr>
		<th scope="col">Client Name</th>
		<th scope="col">Level</th>
		<th scope="col">Slot</th>
	  </tr>
	</thead>
	<tbody>
	  @if(!$test_requests->isEmpty())
	  @foreach($test_requests as $k=>$test_req)
			<tr>
				<td>{{ $test_req->username }}</td>
				<td>{{ $test_req->level_name }}</td>
				<td> {{ date('h:i A', strtotime($test_req->assign_slot)) }}</td>
			</tr>
			
		@endforeach
		@else	
			<tr>
				<td colspan='3'>No test request for selected date</td>
			</tr>
		@endif
		
	</tbody>
  </table>
</div>

<div class="table-responsive">
  <!-- Projects table -->
  <div>Demo Request</div>
  <table class="table align-items-center table-flush">
	<thead class="thead-light">
	  <tr>
		<th scope="col">Client Name</th>
		<th scope="col">Category</th>
		<th scope="col">Slot</th>
	  </tr>
	</thead>
	<tbody>
	@if(!$demo_requests->isEmpty())
	  @foreach($demo_requests as $k=>$demo_req)
			<tr>
				<td>{{ $demo_req->username }}</td>
				<td>{{ $demo_req->category_name }}</td>
				<td> {{ date('h:i A', strtotime($demo_req->slot_time)) }} </td>
			</tr>
			
		@endforeach
		@else	
			<tr>
				<td colspan='3'>No demo request for selected date</td>
			</tr>
		@endif
	</tbody>
  </table>
</div>