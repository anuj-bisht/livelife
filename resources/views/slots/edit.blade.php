@extends('layouts.app')

@section('content')

	<!-- Navigation -->
	@include('layouts.left')

	<div id="page-wrapper">
		<div class="container-fluid">


            <div class="row">
                    <div class="col-lg-10">
                        <h1 class="page-header">{{__('Edit Slot')}}</h1>                        
                    </div>		                    
            </div>

            @include('layouts.flash')

            
            <form method="POST" action="{{ url('/') }}/admin/slots/update/{{$slot->id}}">

            @csrf
            <div class="row">

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Category:</strong>
                        {!! Form::select('category_id', $category,$slot->category_id, array('placeholder' => 'Category','class' => 'form-control')) !!}
                    </div>
                </div>
                @php
                    $batch = explode(",",$slot->batch);
                    $mon = false;
                    $tue = false;
                    $wed = false;
                    $thu = false;
                    $fri = false;
                    $sat = false;
                    $sun = false;

                    if(in_array('Mon',$batch)) 
                            $mon = true; 
                    if(in_array('Tue',$batch)) 
                            $tue = true; 

                    if(in_array('Wed',$batch)) 
                            $wed = true; 

                    if(in_array('Thu',$batch)) 
                            $thu = true;                             

                    if(in_array('Thu',$batch)) 
                            $thu = true;                             

                    if(in_array('Fri',$batch)) 
                            $fri = true;                             

                    if(in_array('Sat',$batch)) 
                            $sat = true;                             
                    
                    if(in_array('Sun',$batch)) 
                            $sun = true;                                                         

                @endphp
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Availabe Batch:</strong>
                        
                        {!! Form::checkbox('batch[]', 'Mon', $mon) !!} Mon &nbsp;
                        {!! Form::checkbox('batch[]', 'Tue', $tue) !!} Tue &nbsp;
                        {!! Form::checkbox('batch[]', 'Wed', $wed) !!} Wed &nbsp;
                        {!! Form::checkbox('batch[]', 'Thu', $thu) !!} Thu &nbsp;
                        {!! Form::checkbox('batch[]', 'Fri', $fri) !!} Fri &nbsp;
                        {!! Form::checkbox('batch[]', 'Sat', $sat) !!} Sat &nbsp;
                        {!! Form::checkbox('batch[]', 'Sun', $sun) !!} Sun &nbsp;  
                    </div>
                </div>
                                
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Name:</strong>
                        {!! Form::text('name', $slot->name, array('placeholder' => 'Slot Name','class' => 'form-control')) !!}
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Start Time:</strong>
                        {!! Form::text('start_time', $slot->start_time, array('placeholder' => 'Start time','class' => 'form-control')) !!}
                        <i>Pleas enter HH:MM:SS format ex : 13:00:00</i>
                    </div>
                </div>
                
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>End Time:</strong>
                        {!! Form::text('end_time', $slot->end_time, array('placeholder' => 'End Time','class' => 'form-control')) !!}
                        <i>Pleas enter HH:MM:SS format ex : 13:00:00</i>
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
