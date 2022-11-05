@extends('layouts.app')

@section('content')

	<!-- Navigation -->
	@include('layouts.left')

	<div id="page-wrapper">
		<div class="container-fluid">


            <div class="row">
                    <div class="col-lg-10">
                        <h1 class="page-header">{{__('Edit Warmup')}}</h1>                        
                    </div>		                    
            </div>

            @include('layouts.flash')

            
            <form method="POST" action="{{ url('/') }}/admin/warmups/update/{{$warmup->id}}" enctype="multipart/form-data">

            @csrf
            <div class="row">

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Category:</strong>
                        {!! Form::select('category_id', $categories, $warmup->category_id, array('placeholder' => 'Category','class' => 'form-control')) !!}
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Name:</strong>
                        {!! Form::text('name', $warmup->name, array('placeholder' => 'Warmup Name' , 'class' => 'form-control')) !!}
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Youtube Link:</strong>
                        {!! Form::text('code', $warmup->code, array('placeholder' => 'Youtube link' , 'class' => 'form-control')) !!}
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>description:</strong>
                        {!! Form::textarea('description', $warmup->description, array('placeholder' => 'description','class' => 'form-control ckeditor')) !!}
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Upload Warmup Image:</strong>
                        <input type="file" class="form-control" name="file">
                    </div>
                </div>

                @if(file_exists($warmup->file_path))
                
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <img src="{{$warmup->image}}" style="max-width:200px; max-height:200px">
                </div>
                

                
                @endif
                                
                <div class="col-xs-12 col-sm-12 col-md-12">
                    
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
            {!! Form::close() !!}
                        


		</div>		
	</div>
	<!-- /#page-wrapper -->
            
@endsection
