@extends('layouts.app')

@section('content')

	<!-- Navigation -->
	@include('layouts.left')

	<div id="page-wrapper">
		<div class="container-fluid">


            <div class="row">
                    <div class="col-lg-10">
                        <h1 class="page-header">{{__('Create New Slot')}}</h1>                        
                    </div>		                    
            </div>

            @include('layouts.flash')
            
            <form method="POST" action="{{ url('/') }}/admin/slots/store">

            @csrf
            <div class="row">
                

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Category:</strong>
                        {!! Form::select('category_id',$category, null, array('placeholder' => 'Category','class' => 'form-control')) !!}
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Availabe Batch:</strong>
                        {!! Form::checkbox('batch[]', 'Mon') !!} Mon &nbsp;
                        {!! Form::checkbox('batch[]', 'Tue') !!} Tue &nbsp;
                        {!! Form::checkbox('batch[]', 'Wed') !!} Wed &nbsp;
                        {!! Form::checkbox('batch[]', 'Thu') !!} Thu &nbsp;
                        {!! Form::checkbox('batch[]', 'Fri') !!} Fri &nbsp;
                        {!! Form::checkbox('batch[]', 'Sat') !!} Sat &nbsp;
                        {!! Form::checkbox('batch[]', 'Sun') !!} Sun &nbsp;                        
                    </div>
                </div>

                
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Name:</strong>
                        {!! Form::text('name', null, array('placeholder' => 'Plan Name','class' => 'form-control')) !!}
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Start Time:</strong>
                        {!! Form::text('start_time', null, array('placeholder' => 'Start time','class' => 'form-control')) !!}
                        <i>Pleas enter HH:MM:SS format ex : 13:00:00</i>
                    </div>
                </div>
                
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>End Time:</strong>
                        {!! Form::text('end_time', null, array('placeholder' => 'End Time','class' => 'form-control')) !!}
                        <i>Pleas enter HH:MM:SS format ex : 13:00:00</i>
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
