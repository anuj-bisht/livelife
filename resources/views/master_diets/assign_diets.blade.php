<form method="POST" action="{{ url('/') }}/trainer/clients/{{ $client_id }}/assign_diet" enctype="multipart/form-data">

@csrf
<div class="row">

					
	<div class="col-xs-12 col-sm-12 col-md-12">
		
			<div class="form-group"> 
			<select class="form-control select2 select2-hidden-accessible" id='master_diet_id' name='master_diet_id[]' multiple="" data-placeholder="Select diets" aria-hidden="true">
                    @foreach($diets as $k=>$diet)
					<option value='{{ $diet->id }}'>{{ $diet->name }}</option>
					@endforeach
             </select>
			</div>
	</div>

	
					
	<div class="col-xs-12 col-sm-12 col-md-12">
		
		<center><button type="submit" class="btn btn-primary">Assign</button>
		</center>
	</div>
</div>
</form>

<script>
$('#master_diet_id').select2({
  closeOnSelect: true
});
</script>

                        

						