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
				<td>{{ $diet->diet_name }}</td>
				<td>{{ $diet->level_name }}</td>
				<td>{{ $diet->diet_type }}</td>
				<td>
					<a href="..{{ $diet->file_path }}" target="_blank" class="btn btn-sm btn-primary">View</a>
				</td>
				<td>
					<a href="javascript::void(0);" onClick="return unassign_diet('{{ $diet->id }}');" class="btn btn-sm btn-danger">Unassign</a>
				</td>
			</tr>
			
		@endforeach
		@else	
		<tr>
			<td colspan='6'>No Assigned Diet Found</td>
		</tr>
	@endif
	</tbody>
  </table>
</div>