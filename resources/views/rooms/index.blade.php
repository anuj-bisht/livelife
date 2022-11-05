@extends('layouts.app')

@section('content')

	<!-- Navigation -->
	@include('layouts.left')

	<div id="page-wrapper">
		<div class="container-fluid">


      <div class="row">
				<div class="col-lg-12">
					<h1 class="page-header">{{__('Room List')}}</h1>
				</div>
				<!-- /.col-lg-12 -->
			</div>  

      <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>&nbsp;</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ url('/') }}/admin/plans/create"> Create New Room</a>
            </div>
        </div>
      </div>

      @include('layouts.flash')

      <div class="row">
        <div class="col-lg-12 margin-tb">
            
        </div>
      </div>
      <div class="row">
        {!! Form::open(['url' => 'admin/rooms/create']) !!}
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{!! Form::label('roomName', 'Create or Join a Video Chat Room') !!}</strong>
                {!! Form::text('roomName', null, array('placeholder' => 'room Name','class' => 'form-control')) !!}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                {!! Form::submit('Submit',array('class'=>'btn btn-primary')) !!}
            </div>
        </div>
        {!! Form::close() !!}
      </div>
      

      <table id="tableData" class="table-responsive table table-striped table-bordered" style="font-size:12px;width:100% !important">
          
          <thead>
              <tr>                                                                            
                  <th>Room Name</th>                      
              </tr>
          </thead>
          <tbody>
                        
          </tbody>
          <tfoot>
          @if($rooms)
             @foreach ($rooms as $room)
             <tr>
               <td><a href="{{ url('/admin/rooms/join/'.$room) }}">{{ $room }}</a><td>
             </tr>
            @endforeach
          @else 
              <tr>                                                                            
                  <td>No Room available</td>  
              </tr>
          @endif
              
          </tfoot>
      </table>  


		</div>		
	</div>

@endsection

