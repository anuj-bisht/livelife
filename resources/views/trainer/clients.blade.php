@extends('layouts.trainer.app')
<!-- <link href="{{ asset('css/fullcalendar.min.css') }}" rel="stylesheet"> -->
<link href="{{ asset('lib/main.css') }}" rel="stylesheet">

@section('content')

<div id="page-wrapper">
		<div class="container-fluid"> 

@include('layouts.trainer.flash')

		<br/>
		@if(Session::has('message'))
		<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
		@endif
		
			<div class='row'>
				<div class='col-xl-12'>
					  <div class="card">
					<div class="card-header border-0">
					  <div class="row align-items-center">
						<div class="col">
						  <h3 class="mb-0">Clients</h3>
						</div>
						<div class="col text-right">
						  
						</div>
					  </div>
					</div>
					<div class="table-responsive">
					  <!-- Projects table -->
					  <table class="table align-items-center table-flush">
						<thead class="thead-light">
						  <tr>
							<th scope="col">SN</th>
							<th scope="col">Client name</th>
							<th scope="col">Email</th>
							<th scope="col">Age</th>
							<th scope="col">Gender</th>
							<th scope="col">Level</th>
							<!--<th scope="col">Action</th>-->
						  </tr>
						</thead>
						<tbody id="schedule_table_body">
						@if(!$clients->isEmpty())
							@foreach($clients as $k=>$client)
								<tr>
									<td>{{ $k+1 }}</td>
									<td>{{ $client->client_name }}</td>
									<td>{{ $client->email }}</td>
									<td>{{ $client->age }}</td>
									<td>{{ $client->gender }}</td>
									<td>{{ $client->level_name }}</td>
									<!--<td>
										<a href="#!" class="btn btn-sm btn-success" onclick="return view_assign_diet('{{ $client->client_id }}','{{ $client->level }}')">View Assigned Diet</a>
										<a href="#!" class="btn btn-sm btn-primary" onclick="return assign_user_diet('{{ $client->client_id }}','{{ $client->level }}')">Assign Diet</a>
									</td>-->
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
			</div>
		</div>
		<!-- Button trigger modal -->
<!-- Button trigger modal -->

<!-- Modal -->
<div class="modal fade" id="assign_diet_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id='assign_diet_modal_body'>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<script>
	function assign_user_diet(client_id,client_level){
		$.ajax({
			type: "get",
			url: "{{url('/')}}/trainer/clients/assign_diet",
			data: {client_id:client_id,client_level:client_level},
			beforeSend: function() {
					$("#exampleModalLabel").html('Assign Diet');
					$("#assign_diet_modal").modal('show');
					$("#assign_diet_modal_body").html('Loading....');
				},
			success: function (view) {
					$("#assign_diet_modal_body").html(view);
			}
		});
	}
	
	
	function view_assign_diet(client_id,client_level){
		$.ajax({
			type: "get",
			url: "{{url('/')}}/trainer/clients/assigned_diet",
			data: {client_id:client_id,client_level:client_level},
			beforeSend: function() {
					$("#exampleModalLabel").html('Assigned Diet');
					$("#assign_diet_modal").modal('show');
					$("#assign_diet_modal_body").html('Loading....');
				},
			success: function (view) {
					$("#assign_diet_modal_body").html(view);
			}
		});
	}
	
	function unassign_diet(id){
		swal({
            title: "Are you sure ??",
            text: '', 
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
           $.ajax({
				type: "DELETE",
				headers: {
				  'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
				},
				url: "{{url('/')}}/trainer/clients/unassign_diet/"+id,
				success: function (view) {
						swal('','Diet unassigned','success');
						location.reload(true);
				}
			});  
          } else {
          }
        });
	}

</script>
@endsection


