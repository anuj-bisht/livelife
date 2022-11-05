@extends('layouts.app')

@section('content')

	<!-- Navigation -->
	@include('layouts.left')

	<div id="page-wrapper">
		<div class="container-fluid">


      <div class="row">
				<div class="col-lg-12">
					<h1 class="page-header">{{__('Chats')}}</h1>
				</div>
				<!-- /.col-lg-12 -->
			</div>  

      <div class="row">
				<div class="col-md-3">
          <table id="chatuserlist" class="table-responsive table table-striped table-bordered">
            @if(count($chatuser))
              @foreach($chatuser as $k=>$v)
              <tr>
                <td onclick="getChatById({{$v->id}})" style="cursor:pointer; line-height:2.5; border:1.5px solid #f4f4f4; background: #bc0e83; color: white;">{{$v->role_name}} - {{$v->name}}
                  @if($v->read_count)
                    <span id="chatspan_{{$v->id}}" class="blink_me" ></span>
                  @endif
                </td>
              </tr>    
              @endforeach
            @endif            
          </table>
				</div>
        <div class="col-md-9" style="overflow-y: scroll; overflow-x: hidden; height: 450px;">
          <table id="chatdatalist" class="table-responsive table table-striped" style="border:4px solid #f1f1f1; background: #ffffff; background-image: url(http://livefitlife.in/LFL//resources/views/chats/logo-lfl.jpg); overflow-y: auto; ">
                     
          </table></div>
          <form action="" id="messageSentForm" style="display:none;">
            <div class="form-group">
              <input type="hidden" name="user_id" value="" id="inputUserId">
              <textarea class="form-control" id="msgtxtbox" name="message" cols="5" rows="3"></textarea>
            </div>
            <div class="form-group">  
              <button type="submit" class="btn btn-success" style="float:right">Send</button>
            </div>
          </form>
				
				<!-- /.col-lg-12 -->
			</div>  
       
     
		</div>		
	</div>
	<!-- /#page-wrapper -->
  

  <script>

  $(document).ready(function(){
      $('#messageSentForm').submit(function(e){
        e.preventDefault();
        var user_id = $('#inputUserId').val();
        
        $.ajax({
            type: "POST",
            url: "{{url('/')}}/admin/chats/addChat",    
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: $('#messageSentForm').serialize(),           
            success: function(res) {
                if(res.status){
                  $('#msgtxtbox').val('');
                  $('#chatspan_'+user_id).html('');
                  getChatById(user_id);
                }else{
                  jQuery.alert({
                      title: 'Alert!',
                      content: res.message,
                  });
                }          
                
                //$('#vehicleModal').modal('show'); 
                          
            },
            error:function(request, status, error) {
                console.log("ajax call went wrong:" + request.responseText);
            }
        });
        
      });

      setInterval(function(){ 
        
        $.ajax({
            type: "POST",
            url: "{{url('/')}}/admin/chats/getUnReadChat",    
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {},           
            success: function(res) {
              var str = '';
                if(res.status==1){
                  $.each(res.data, function( index, value) {
                    $('#chatspan_'+value.id).html('('+value.read_count+')');                    
                  });                  
                }            
            },
            error:function(request, status, error) {
                console.log("ajax call went wrong:" + request.responseText);
            }
        });
      }, 5000);

  })
  function getChatById(id){
    $('#inputUserId').val(id);
    $('#messageSentForm').css('display','block');
    $.ajax({
        type: "POST",
        url: "{{url('/')}}/admin/chats/getChatById",    
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {"id":id},           
        success: function(res) {
          var str = '';
            if(res.status==1){
              $('#chatspan_'+id).html(''); 
              $.each(res.data, function( index, value) {
                if(value.from_id==id){
                  str += '<tr><td style="float:left;"><p class="chat-s" style="margin-left: 10px; margin-top: 10px; background: #BC0E83;color: white; border-radius:0px 20px 20px 20px;">'+value.message+'</p></td></tr>';  
                }else{
                  str += '<tr><td style="float:right;"><p class="chat-r" style="margin-right: 10px; margin-top: 10px; background: #dcf8c6;color: black; border-radius:0px 20px 0px 20px;">'+value.message+'</p></td></tr>';
                }
                
              });
              $('#chatdatalist').html(str);
            }            
        },
        error:function(request, status, error) {
            console.log("ajax call went wrong:" + request.responseText);
        }
    });
  }
  </script>
<style>
.chat-s{
  padding:10px;
  background:#E6E4E4;
  border-radius:5px 0px 5px 5px;
}

.chat-r{
  padding:10px;
  background:#E9D2D2;
  border-radius:5px 0px 5px 5px;
}

.blink_me {
  animation: blinker 1s linear infinite;
}

@keyframes blinker {
  50% {
    opacity: 0;
  }
}
</style>
@endsection
