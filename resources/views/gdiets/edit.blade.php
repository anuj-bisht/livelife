@extends('layouts.app')

@section('content')

	<!-- Navigation -->
	@include('layouts.left')

	<div id="page-wrapper">
		<div class="container-fluid">


            <div class="row">
                    <div class="col-lg-10">
                        <h1 class="page-header">{{__('Edit Generic Diet')}}</h1>                        
                    </div>		                    
            </div>

            @include('layouts.flash')

            
            <form method="POST" action="{{ url('/') }}/admin/gdiets/update/{{$gdiet->id}}" enctype="multipart/form-data">

            @csrf
            <div class="row">

                
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Name:</strong>
                        {!! Form::text('name', $gdiet->name, array('placeholder' => 'Diet Name','class' => 'form-control')) !!}
                    </div>
                </div>

                @if($gdiet->image != "")
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">                        
                       <a href="{{$gdiet->image}}" target="_blank">Document Link</a>
                    </div>
                </div>
                @endif

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Image:</strong>
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
