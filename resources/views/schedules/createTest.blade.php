@extends('layouts.app')

@section('content')

	<!-- Navigation -->
	@include('layouts.left')

	<div id="page-wrapper">
		<div class="container-fluid">


        <div class="row">
                    <div class="col-lg-10">
                        <h1 class="page-header">{{__('Create Schedule')}}</h1>                        
                    </div>		                    
            </div>

     

      @include('layouts.flash')

      <form method="POST" name="createSchedule" id="createSchedule" action="{{ url('/') }}/admin/schedule/store">

            @csrf
            <div class="row">
                

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Users:</strong>
                        <select name="user_id" class="form-control">
                            <option value="0">Select User</option>
                            @foreach($userlist as $k => $v)
                                <option value="{{$v->id}}">{{$v->name}}-{{$v->email}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Category:</strong>
                        {!! Form::select('category_id',$categories, null, array('placeholder' => 'Category','id'=>'categorydd','class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Slots:</label>
                        <select name="slot_id" id="slot_id" class="form-control">
                            <option value="0">Select Slot</option>
                        </select>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Trainers:</strong>
                        <select name="trainer_id" class="form-control">
                            <option value="0">Select Trainer</option>
                            @foreach($trainers as $k => $v)
                                <option value="{{$v->id}}">{{$v->name}}-{{$v->email}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Schedule type:</strong>
                        {!! Form::checkbox('type[]', 1) !!} Mon &nbsp;
                        {!! Form::checkbox('type[]', 2) !!} Tue &nbsp;
                        {!! Form::checkbox('type[]', 3) !!} Wed &nbsp;
                        {!! Form::checkbox('type[]', 4) !!} Thu &nbsp;
                        {!! Form::checkbox('type[]', 5) !!} Fri &nbsp;
                        {!! Form::checkbox('type[]', 6) !!} Sat &nbsp;
                        {!! Form::checkbox('type[]', 7) !!} Sun &nbsp;
                    </div>
                </div>
                
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Start Date:</strong>
                        {!! Form::text('start', null, array('placeholder' => 'Start Date','class' => 'datetimepicker form-control','readonly'=>'readonly')) !!}
                    </div>
                    <div class="form-group">
                        <strong>End Date:</strong>
                        {!! Form::text('end', null, array('placeholder' => 'End Date','class' => 'datetimepicker form-control','readonly'=>'readonly')) !!}
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
  





  <script>
        
    var url = "{{url('/')}}";
    var table = '';

    


    


  


$.datetimepicker.setLocale('en');

$('.datetimepicker').datetimepicker({
  dayOfWeekStart : 1,
  lang:'en',
  format:'Y-m-d H:i',
  allowTimes:[
    '5:00', '5:30', '6:00', '6:30', '7:00', '7:30', '8:00',
      '8:30', '9:30', '10:00', '10:30', '11:00', '11:30', '12:00',
      '12:30','13:00','13:30','14:00','14:30','15:00','15:30','16:00','16:30',
      '17:00', '17:30', '18:00', '18:30', '19:00', '19:30', '20:00',
      '20:30', '21:00', '21:30', '22:00', '22:30','23:00','23:30'
  ],
  //disabledDates:['1986/01/08','1986/01/09','1986/01/10'],
  startDate:	'2010-12-08'
});

 

$("#createSchedule").submit(function(event){
      event.preventDefault(); 
      var post_url = "{{url('/')}}/admin/schedules/store"; 
      var request_method = 'POST';
      var form_data = $(this).serialize();
      
      $.ajax({
        url : post_url,
        type: request_method,
        headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        },
        data : form_data
      }).done(function(response){ //
        if(response.status==1){
            $.alert({
              title: 'Success',
              content: 'Schedule added successfully',              
          });        
        }else{
          $.alert({
              title: 'Error!',
              content: response.message,              
          });
        }
      });
  });  

  $('#categorydd').on('change',function(){
    var post_url = "{{url('/')}}/admin/categories/getCategoryById"; 
    var id = $(this).val();
    
    $.ajax({
        url : post_url,
        type: 'POST',
        headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        },
        data : {category_id:id},
        success:function(response){
            if(response.status==1){
                var html = '';
                $.each(response.data, function(index, value) {
                    html += '<option value="'+value.id+'">'+value["start_time"]+'</option>';
                });
                $('#slot_id').empty().append(html);      
            }else{
                $.alert({
                    title: 'Error!',
                    content: 'Error on insert',              
                });
            }
        }
      });
  })
</script>
<style type="text/css">

.custom-date-style {
	background-color: red !important;
}

.input{	
}
.input-wide{
	width: 500px;
}

</style>


@endsection
