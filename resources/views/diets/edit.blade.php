@extends('layouts.app')

@section('content')

	<!-- Navigation -->
	@include('layouts.left')

	<div id="page-wrapper">
		<div class="container-fluid">


            <div class="row">
                    <div class="col-lg-10">
                        <h1 class="page-header">{{__('Edit Diet')}}</h1>                        
                    </div>		                    
            </div>

            @include('layouts.flash')

            
            <form method="POST" action="{{ url('/') }}/admin/diets/update/{{$diet->id}}" enctype="multipart/form-data">

            @csrf
            <div class="row">

                
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Name:</strong>
                        {!! Form::text('name', $diet->name, array('placeholder' => 'Plan Name','class' => 'form-control')) !!}
                    </div>
                </div>

                                
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>User:</strong>
                        <select name="user_id" class="form-control">
                            @foreach($user_list as $k=>$v)
                                <option value="{{$v->id}}" @if($v->id==$diet->user_id) selected @endif>{{$v->name}}-{{$v->email}}</option>
                            @endforeach
                        </select>        
                    </div>
                </div>

                

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Upload Vegeterian PDF:</strong>
                        <input type="file" class="form-control" name="file_veg" accept="application/pdf">
                    </div>
                </div>

                @if($diet->vegeterian != "")
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">                        
                        <a href="{{$diet->vegeterian}}" target="_blank">PDF Link</a>
                    </div>
                </div>
                @endif

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Upload Non Vegeterian PDF:</strong>
                        <input type="file" class="form-control" name="file_nonveg" accept="application/pdf">
                    </div>
                </div>

                @if($diet->non_vegeterian != "")
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">                        
                        <a href="{{$diet->non_vegeterian}}" target="_blank">PDF Link</a>
                    </div>
                </div>
                @endif

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Upload Eggeterian Veg PDF:</strong>
                        <input type="file" class="form-control" name="file_egg" accept="application/pdf">
                    </div>
                </div>

                @if($diet->eggeterian != "")
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">                        
                        <a href="{{$diet->eggeterian}}" target="_blank">PDF Link</a>
                    </div>
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
