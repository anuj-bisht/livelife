@extends('layouts.app')

@section('content')

	<!-- Navigation -->
	@include('layouts.left')

	<div id="page-wrapper">
		<div class="container-fluid">


            <div class="row">
                    <div class="col-lg-10">
                        <h1 class="page-header">{{__('Create New Warmup')}}</h1>                        
                    </div>		                    
            </div>

            @include('layouts.flash')
            
            <form method="POST" action="{{ url('/') }}/admin/warmups/store" enctype="multipart/form-data">

            @csrf
            <div class="row">
            
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Category:</strong>
                        {!! Form::select('category_id', $categories, null, array('placeholder' => 'Category','class' => 'form-control')) !!}
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Name:</strong>
                        {!! Form::text('name', null, array('placeholder' => 'Warmup Name','class' => 'form-control')) !!}
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Youtube Link:</strong>
                        {!! Form::text('code', null, array('placeholder' => 'Youtube link' , 'class' => 'form-control')) !!}
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>description:</strong>
                        {!! Form::textarea('description', null, array('placeholder' => 'description','class' => 'form-control ckeditor')) !!}
                    </div>
                </div>
                
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Upload Warmup Image:</strong>
                        <input type="file" class="form-control" name="file">
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
            </form>
                        


		</div>		
	</div>
	<!-- /#page-wrapper -->
            
@endsection
