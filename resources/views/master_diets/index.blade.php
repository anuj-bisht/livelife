@extends('layouts.trainer.app')
<link href="{{ asset('lib/main.css') }}" rel="stylesheet">
@section('content')


	<div id="page-wrapper">
		<div class="container-fluid">
		
		 @include('layouts.flash')
		 
		 
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
						  <h3 class="mb-0">Diets List</h3>
						</div>
						<div class="col text-right">
						  <a href="javascript::void(0);" onClick='return create_diet();' class="btn btn-sm btn-primary">Create Diet</a>
						</div>
					  </div>
					</div>
					<div class="table-responsive">
					  <!-- Projects table -->
					  <table id="diet_table" class="table align-items-center table-flush">
						<thead class="thead-light">
						  <tr>
							<th scope="col">SN</th>
							<th scope="col">Name</th>
							<th scope="col">Level</th>
							<th scope="col">Type</th>
							<th scope="col">View</th>
							<th scope="col">Action</th>
						  </tr>
						</thead>
						<tbody class="list">
							@if(!$diets->isEmpty())
							@foreach($diets as $k=>$diet)
								<tr>
									<td>{{ $k+1 }}</td>
									<td>{{ $diet->name }}</td>
									<td>{{ $diet->level_name }}</td>
									<td>{{ $diet->type }}</td>
									<td>
										<a href="..{{ $diet->file_path }}" target="_blank" class="btn btn-sm btn-primary">View</a>
									</td>
									<td>
										<a href="javascript::void(0);" onClick="return edit_diet('{{ $diet->id }}');" class="btn btn-sm btn-primary">Edit</a>
										<a href="javascript::void(0);" onClick="return delete_diet('{{ $diet->id }}');" href="#" class="btn btn-sm btn-danger">Delete</a>
									</td>
								</tr>
								
							@endforeach
							@else	
							<tr>
								<td colspan='4'>No Diet Found</td>
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
	
	

@endsection
<div class="modal fade" id="diet_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id='diet_modal_body'>
        
      </div>
     
    </div>
  </div>
</div>
<script>
		function create_diet(){
				$.ajax({
				type: "get",
				url: "{{url('/')}}/trainer/master-diets/create",
				beforeSend: function() {
						$("#diet_modal").modal('show');
						$("#exampleModalLabel").html('Create Diet');
						$("#diet_modal_body").html('loading....');
					},
				success: function (view) {
						$("#diet_modal_body").html(view);
				}
			});
		}
		
		function edit_diet(id){
				$.ajax({
				type: "get",
				url: "{{url('/')}}/trainer/master-diets/"+id+"/edit",
				beforeSend: function() {
						$("#diet_modal").modal('show');
						$("#exampleModalLabel").html('Edit Diet');
						$("#diet_modal_body").html('loading....');
					},
				success: function (view) {
						$("#diet_modal_body").html(view);
				}
			});
		}
		
		function delete_diet(id){
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
					url: "{{url('/')}}/trainer/master-diets/destroy/"+id,
					success: function (view) {
							swal('','Diet deleted','success');
							location.reload(true);
					}
				}); 
			  } else {
			  }
			});
		}
</script>
