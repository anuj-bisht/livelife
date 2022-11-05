@if(!$data->isEmpty())
	
	@if($filter_type_job == 'schedule')
		@foreach($data as $k=>$schedule)
			<tr>
				<td>{{ date('d/m/Y', strtotime($schedule->schedule_date)) }}</td>
				<td>{{ date('h:i A', strtotime($schedule->start_time)) }}  - {{  date('h:i A', strtotime($schedule->end_time)) }}</td>
				<td>{{ $schedule->count }} Client</td>
				<td>
					<a href="{{ route('joinRegularConfress',['slug'=>$schedule->room_name]) }}" target="_blank" class="btn btn-sm btn-primary">Join</a>
				</td>
			</tr>
			
		@endforeach
	@endif
	
	@if($filter_type_job == 'test')
		@foreach($data as $k=>$testreques)
			<tr>
				<td>{{ $testreques->username }}</td>
				<td>{{ $testreques->level_name }}</td>
				<td>@if(!empty($testreques->assign_slot)) 
					@if($type == 'all') {{ date('d/m/Y', strtotime($testreques->assign_slot)) }} <br/> @endif {{ date('h:i A', strtotime($testreques->assign_slot)) }}
					@endif
				</td>
				<td>
					<a href="{{ route('joinTestConfress',['slug'=>$testreques->room_name]) }}" target="_blank" class="btn btn-sm btn-primary">Start</a>
				</td>
			</tr>
			
		@endforeach
	@endif
	
	@if($filter_type_job == 'demo')
		@foreach($data as $k=>$demo_request)
			<tr>
				<td>{{ $demo_request->username }}</td>
				<td>{{ $demo_request->category_name }}</td>
				<td> @if($type == 'all') {{ date('d/m/Y', strtotime($demo_request->slot_time)) }} <br/> @endif {{ date('h:i A', strtotime($demo_request->slot_time)) }} </td>
				<td>
					<a href="{{ route('joinDemoConfress',['slug'=>$demo_request->room_name]) }}" target="_blank" class="btn btn-sm btn-primary">Join</a>
				</td>
			</tr>
			
		@endforeach
	@endif
	
	
@else
	
	@if($filter_type_job == 'demo')
		<tr>
			<td colspan='4'>No demo request found</td>
		</tr>
	@elseif($filter_type_job == 'test')
		<tr>
			<td colspan='4'>No test request found</td>
		</tr>
	@elseif($filter_type_job == 'schedule')
		<tr>
			<td colspan='4'>No regular schedule found</td>
		</tr>
	@else
		<tr>
			<td colspan='4'></td>
		</tr>
	@endif
	
	
	
@endif