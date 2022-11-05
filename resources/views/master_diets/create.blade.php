
            
<form method="POST" action="{{ url('/') }}/trainer/master-diets/store" enctype="multipart/form-data">

@csrf
<div class="row">
	

	<div class="col-xs-12 col-sm-12 col-md-12">
		<div class="form-group">
			{!! Form::text('name', null, array('placeholder' => 'Diet Name','class' => 'form-control','id' => 'name')) !!}
		</div>
	</div>

					
	<div class="col-xs-12 col-sm-12 col-md-12">
		<div class="form-group">
			{!! Form::select('level_id', $level_list,[], array('placeholder' => 'Select Level','class' => 'form-control','id' => 'level_id')) !!}
		</div>
	</div>
	
	<div class="col-xs-12 col-sm-12 col-md-12">
		<div class="form-group">
			{!! Form::select('type',$diet_type, [], array('placeholder' => 'Select Diet Type','class' => 'form-control','id' => 'type')) !!}
		</div>
	</div>

	<div class="col-xs-12 col-sm-12 col-md-12">
		<div class="form-group">
		Upload Diet : {!! Form::file('file_type',null,['class'=>'form-control', 'placeholder'=>'Upload diet','id' => 'file_type']) !!}
		</div>
	</div>
	
	<div class="col-xs-12 col-sm-12 col-md-12">
		<div class="form-group">
		{!! Form::textarea('description', null, ['class'=>'form-control', 'placeholder'=>'Write Description','id' => 'description', 'rows' => 4, 'cols' => 54, 'style' => 'resize:none']) !!}
		</div>
	</div>


	<div class="col-xs-12 col-sm-12 col-md-12">
		
		<center>
			<button type="submit" class="btn btn-primary">Save Diet</button>
			<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
		</center>
	</div>
</div>

</form>

