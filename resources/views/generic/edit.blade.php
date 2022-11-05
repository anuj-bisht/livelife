@extends('layouts.app')

@section('content')

	<!-- Navigation -->
	@include('layouts.left')

	<div id="page-wrapper">
		<div class="container-fluid">


            <div class="row">
                    <div class="col-lg-10">
                        <h1 class="page-header">{{__('Edit Generic Doc')}}</h1>                        
                    </div>		                    
            </div>

            @include('layouts.flash')

            
            <form method="POST" action="{{ url('/') }}/admin/generic/update/{{$generic->id}}" enctype="multipart/form-data">

            @csrf
            <div class="row">

                
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Name:</strong>
                        {!! Form::text('name', $generic->name, array('placeholder' => 'Generic Name','class' => 'form-control')) !!}
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Youtube link:</strong>
                        {!! Form::text('youtube_link', $generic->youtube_link, array('placeholder' => 'Youtube link','class' => 'form-control')) !!}
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Doc Type:</strong>
                        {!! Form::select('gtype', ['General'=>'General','Workout'=>'Workout'],$generic->gtype, array('placeholder' => 'generic type','class' => 'form-control')) !!}
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Description:</strong>
                        {!! Form::textarea('description', $generic->description, array('class' => 'form-control ckeditor')) !!}
                    </div>
                </div>

                @if($generic->image != "")
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">                        
                        <a href="{{$generic->image}}" target="_blank">Document Link</a>
                    </div>
                </div>
                @endif

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Image/PDF:</strong>
                        <input type="file" class="form-control" name="file">
                    </div>
                </div>
                
                <div class="col-xs-12 col-sm-12 col-md-12">
                    
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
            {!! Form::close() !!}
                        


		</div>		
	</div>
	<!-- /#page-wrapper -->
            
@endsection
