@extends('layouts.app')

@section('content')

	<!-- Navigation -->
	@include('layouts.left')

	<div id="page-wrapper">
		<div class="container-fluid">


            <div class="row">
                    <div class="col-lg-10">
                        <h1 class="page-header">{{__('Show User')}}</h1>                        
                    </div>		                    
            </div>
            
            @include('layouts.flash')
            
            <div class="row">
                <div class="col-xs-6 col-sm-6 col-md-6">
                    <div class="form-group">
                        <strong>Name:</strong>
                        {{ $user->name }}
                    </div>
                </div>
                
                <div class="col-xs-6 col-sm-6 col-md-6">
                    <div class="form-group">
                        <strong>Email:</strong>
                        {{ $user->email }}
                    </div>
                </div>
                <div class="col-xs-6 col-sm-6 col-md-6">
                    <div class="form-group">
                        <strong>Gender:</strong>
                        @if($user->gender=='M')
                            Male
                        @elseif($user->gender=='F')    
                            Female
                        @else
                            Other                            
                        @endif
                        
                    </div>
                </div>
                <div class="col-xs-6 col-sm-6 col-md-6">
                    <div class="form-group">
                        <strong>Roles:</strong>
                        @if(!empty($user->getRoleNames()))
                            @foreach($user->getRoleNames() as $v)
                                <label class="badge badge-success">{{ $v }}</label>
                            @endforeach
                        @endif
                    </div>
                </div>

                <div class="col-xs-6 col-sm-6 col-md-6">
                    <div class="form-group">
                        <strong>Age:</strong>
                        {{ $user->age }}
                    </div>
                </div>

                <div class="col-xs-6 col-sm-6 col-md-6">
                    <div class="form-group">
                        <strong>Phone:</strong>
                        {{ $user->phone }}
                    </div>
                </div>

                
                <div class="col-xs-6 col-sm-6 col-md-6">
                    <div class="form-group">
                        <strong>Diet Type:</strong>
                        {{ $user->diet_type }}
                    </div>
                </div>

                <div class="col-xs-6 col-sm-6 col-md-6">
                    <div class="form-group">
                        <strong>Initial Weight:</strong>
                        {{ $user->initial_weight }}
                    </div>
                </div>
                <div class="col-xs-6 col-sm-6 col-md-6">
                    <div class="form-group">
                        <strong>Goal Weight:</strong>
                        {{ $user->goal_weight }}
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Image Main:</strong>                      
                    </div>
                </div>
                <div class="col-xs-6 col-sm-6 col-md-6">
                    <div class="form-group">                        
                        @if(file_exists($user->file_path))
                            <img src="{{$user->image}}" alt="" style="max-width:200px;max-height:200px">
                        @else
                            <img src="{{url('/')}}/images/noimage.png" alt="" style="max-width:50px;max-height:50px">
                        @endif
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Image Front:</strong>                      
                    </div>
                </div>

                <div class="col-xs-6 col-sm-6 col-md-6">
                    <div class="form-group">                        
                        @if(file_exists($user->file_path_front))
                            <img src="{{$user->image_front}}" alt="" style="max-width:200px;max-height:200px">
                        @else
                            <img src="{{url('/')}}/images/noimage.png" alt="" style="max-width:50px;max-height:50px">
                        @endif
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Image Back:</strong>                      
                    </div>
                </div>
                <div class="col-xs-6 col-sm-6 col-md-6">
                    <div class="form-group">                        
                        @if(file_exists($user->file_path_back))
                            <img src="{{$user->image_back}}" alt="" style="max-width:200px;max-height:200px">
                        @else
                            <img src="{{url('/')}}/images/noimage.png" alt="" style="max-width:50px;max-height:50px">
                        @endif
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Image Side:</strong>                      
                    </div>
                </div>

                <div class="col-xs-6 col-sm-6 col-md-6">
                    <div class="form-group">                        
                        @if(file_exists($user->file_path_side))
                            <img src="{{$user->image_side}}" alt="" style="max-width:200px;max-height:200px">
                        @else
                            <img src="{{url('/')}}/images/noimage.png" alt="" style="max-width:50px;max-height:50px">
                        @endif
                    </div>
                </div>

                
                <div class="row">
                        <div class="col-lg-10">
                            <h1 class="page-header">{{__('Subscription List')}}</h1>                        
                        </div>		                    
                </div>
                <table id="tableData" class="table-responsive table table-striped table-bordered" style="font-size:12px;width:100% !important">
          
                    <thead>
                        <tr>               
                        <th>Category</th>              
                        <th>Plan</th>
                        <th>Price</th>
                        <th>Diet Type</th>
                        <th>Plan Type</th>
                        <th>Start Date</th>              
                        <th>Nex Bill Date</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if(count($subscription))
                          @foreach($subscription as $k=>$v)   
                            <tr>
                                <td>{{$v->category_name}}</td>
                                <td>{{$v->plan_name}}</td>
                                <td>{{$v->plan_price}}</td>
                                <td>{{$v->diet_type}}</td>
                                <td>{{$v->client_type}}</td>
                                <td>{{$v->subscription_start_date}}</td>
                                <td>{{$v->next_bill_date}}</td>
                            </tr>    
                          @endforeach   
                    @else
                            <tr>
                                <td colspan="7"><i>No Record Found</i></td>
                            </tr>
                    @endif
                    </tbody>
                    <tfoot>
                        <tr>               
                            <th>Category</th>              
                            <th>Plan</th>
                            <th>Price</th>
                            <th>Diet Type</th>
                            <th>Plan Type</th>
                            <th>Start Date</th>     
                            <th>Nex Bill Date</th>         
                       
                        </tr>
                    </tfoot>
                </table>  

                <div class="row">
                        <div class="col-lg-10">
                            <h1 class="page-header">{{__('Schedule List')}}</h1>                        
                        </div>		                    
                </div>
                <div class="row">
                        <div class="col-lg-10">
                        <a  href="#" data-toggle="modal" data-target="#exampleModal" class="btn btn-primary trainer-btn-action">Change Trainer</a>                
                        </div>		                    
                </div>
                <table id="tableData" class="table-responsive table table-striped table-bordered" style="font-size:12px;width:100% !important">
          
                    <thead>
                        <tr>      
                            <td><input type="checkbox" name="selectAll" id="selectAll"></td>         
                            <th>Category</th>              
                            <th>Start</th>
                            <th>End</th>
                            <th>Slot name</th>
                            <th>Trainer</th>
                            <th>Reschedule Status</th>              
                        </tr>
                    </thead>
                    <tbody>
                    @if(count($schedule))
                          @foreach($schedule as $k=>$v)   
                            <tr>
                                <td><input type="checkbox" class="checkBox" value="{{$v->id}}" name="scheduleids[]"></td>
                                <td>{{$v->category_name}}</td>
                                <td>{{$v->start_time}}</td>
                                <td>{{$v->end_time}}</td>
                                <td>{{$v->slot_name}}</td>
                                <td>{{$v->trainer_name}}</td>
                                <td>
                                @if($v->reschedule_status == 'Y')
                                    Yes
                                @else
                                    No
                                @endif
                                    
                                </td>
                            </tr>    
                          @endforeach   
                    @else
                            <tr>
                                <td colspan="7"><i>No Record Found</i></td>
                            </tr>
                    @endif
                    </tbody>
                    <tfoot>
                        <tr>               
                            <td>&nbsp;</td>         
                            <th>Category</th>              
                            <th>Start</th>
                            <th>End</th>
                            <th>Slote</th>
                            <th>Trainer</th>
                            <th>Reschedule Status</th>              
                        </tr>
                    </tfoot>
                </table>  

                <div class="col-xs-6 col-sm-6 col-md-6">
                    <a class="btn btn-primary" href="{{ route('users.index') }}"> Back</a>
                </div>                
            </div>
                        
                        


		</div>		
	</div>
	<!-- /#page-wrapper -->




<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
  <form id="assign_trainer_form">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Assign Trainer</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">        
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Choose Trainer:</label>
            <select name="assign_to" id="assign_to" class="form-control">
              <option value="0">Choose Trainer</option>
            </select>
            <input type="hidden" id="trainers" name="trainers">
          </div>          
                  
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Assign</button>
      </div>
    </div>
    </form>
  </div>
</div>

<script>
$('#selectAll').click(function(){
    if($(this).prop("checked")) {
        $(".checkBox").prop("checked", true);
    } else {
        $(".checkBox").prop("checked", false);
    }                
});


$('#exampleModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); 
    var id = button.data('id');
    $('#assign_slot').val(button.data('date'));
    $('#description').val(button.data('description'));
    $('#req_id').val(button.data('id'));
    
    $.ajax({
      method: "POST",
      headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
      },
      url: "{{url('/')}}/admin/users/getTrainerList",
      data: { id: id}
    }).done(function(res) {
        if(res.data){
          var str = '<option value="0">Choose Trainer</option>';
          $.each(res.data,function(index,value){
            var selected = '';
            if(id==value.id)
              selected='selected';
            str += '<option value="'+value.id+'" '+selected+'>'+value.name+'('+value.email+')</option>';
          });
          $('#assign_to').empty().append(str);
        }else{
          $.alert({
              title: 'Error!',
              content: res.msg,              
          });
        } 
    });
    // modal.find('.modal-title').text('New message to ' + recipient)
    // modal.find('.modal-body input').val(recipient)
  })



$("#assign_trainer_form").submit(function(event){
      event.preventDefault(); 
      var trainers = isChkBxChecked();
      
      if(trainers.length==0){
        jQuery.alert({
            title: 'Alert!',
            content: 'Please select a schedule to change trainer!',
        });
        return false;
      }

      $('#trainers').val(trainers);

      var post_url = "{{url('/')}}/admin/schedules/changeTrainer"; 
      var request_method = 'POST';
      var form_data = $(this).serialize();
      
      $.ajax({
        url : post_url,
        type: request_method,
        headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        },
        data : form_data,
        success: function(res){
		  window.location.reload();
		}
      })
  });   


  function isChkBxChecked(){
    
    var chkbxArr = [];
    $(".checkBox:checked").each(function(){
      chkbxArr.push($(this).val());
    });
    return chkbxArr;
} 

</script>            
@endsection