@extends('layouts.app')

@section('content')

	<!-- Navigation -->
	@include('layouts.left')

	<div id="page-wrapper">
		<div class="container-fluid">


            <div class="row">
                    <div class="col-lg-10">
                        <h1 class="page-header">{{__('Edit User')}}</h1>                        
                    </div>		                    
            </div>

            @include('layouts.flash')
            

            {!! Form::model($user, ['method' => 'PATCH','enctype'=>'multipart/form-data','route' => ['users.update', $user->id]]) !!}
            <div class="row">
                
                
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Name:</strong>
                        {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Email:</strong>
                        {!! Form::text('email', null, array('placeholder' => 'Email','class' => 'form-control')) !!}
                    </div>
                </div>
                
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Phone:</strong>
                        {!! Form::text('phone', null, array('placeholder' => 'Phone','class' => 'form-control')) !!}
                    </div>
                </div>
                
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Password:</strong>
                        {!! Form::password('password', array('placeholder' => 'Password','class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Confirm Password:</strong>
                        {!! Form::password('confirm-password', array('placeholder' => 'Confirm Password','class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Gender:</strong>
                        {!! Form::select('gender', ['M'=>'Male','F'=>'Female','O'=>'Other'],$user->gender, array('class' => 'form-control')) !!}
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Initial Weight:</strong>
                        {!! Form::text('initial_weight', null, array('placeholder' => 'Initial Weight','class' => 'form-control')) !!}
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Goal Weight:</strong>
                        {!! Form::text('goal_weight', null, array('placeholder' => 'Goal Weight','class' => 'form-control')) !!}
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Image Main:</strong>
                        <input type="file" class="form-control" name="file">
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Image Front:</strong>
                        <input type="file" class="form-control" name="front">
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Image Back:</strong>
                        <input type="file" class="form-control" name="back">
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Image Side:</strong>
                        <input type="file" class="form-control" name="side">
                    </div>
                </div>

                @if(file_exists($user->file_path))
                
                    <div class="col-xs-3 col-sm-3 col-md-3">
                        <div class="form-group">
                            <strong>Image Main:</strong>                                                   
                        </div>
                    </div>
                    <div class="col-xs-9 col-sm-9 col-md-9">
                        <div class="form-group">                                                 
                            <img src="{{$user->image}}" style="max-width:200px; max-height:200px">                        
                        </div>
                    </div>
                @endif
                

                @if(file_exists($user->file_path_front))
                    <div class="col-xs-3 col-sm-3 col-md-3">
                        <div class="form-group">
                            <strong>Image Front:</strong>                                                   
                        </div>
                    </div>
                    <div class="col-xs-9 col-sm-9 col-md-9">
                        <div class="form-group">                                                  
                            <img src="{{$user->image_front}}" style="max-width:200px; max-height:200px">                        
                        </div>
                    </div>
                @endif

                @if(file_exists($user->file_path_back))
                    <div class="col-xs-3 col-sm-3 col-md-3">
                        <div class="form-group">
                            <strong>Image Back:</strong>                                                   
                        </div>
                    </div>
                    <div class="col-xs-9 col-sm-9 col-md-9">
                        <div class="form-group">                                                 
                            <img src="{{$user->image_back}}" style="max-width:200px; max-height:200px">                        
                        </div>
                    </div>
                @endif

                @if(file_exists($user->file_path_side))
                    <div class="col-xs-3 col-sm-3 col-md-3">
                        <div class="form-group">
                            <strong>Image Side:</strong>                                                   
                        </div>
                    </div>
                    <div class="col-xs-9 col-sm-9 col-md-9">
                        <div class="form-group">                                                 
                            <img src="{{$user->image_side}}" style="max-width:200px; max-height:200px">                        
                        </div>
                    </div>
                @endif
                
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Categories:</strong>                        
                        <select name="categories[]" class="form-control">
                           @foreach($categories as $k=>$v)
                            <option value="{{$v->id}}" @if(in_array($v->id,$selectedCat)) selected @endif>{{$v->name}}</option>
                           @endforeach 
                        </select>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Role:</strong>
                        {!! Form::select('roles[]', $roles,$userRole, array('class' => 'form-control')) !!}                        
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <a class="btn btn-primary" href="{{ route('users.index') }}"> Back</a>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
            {!! Form::close() !!}
                        


		</div>		
	</div>
	<!-- /#page-wrapper -->
            
@endsection
