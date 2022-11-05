@extends('layouts.app')

@section('content')

	<!-- Navigation -->
	@include('layouts.left')

	<div id="page-wrapper">
		<div class="container-fluid">
			
			<div class="row">
				<div class="col-lg-12">
					<h1 class="page-header" id="heading_page"></h1>
				</div>
				<!-- /.col-lg-12 -->
			</div>
			
			<div class="row">
			  <div class="col-md-9">
				<div class="thumbnail">				  
					<div id="local-media"></div>									  
				</div>
			 </div>
			 <div class="col-md-3">
				<div class="thumbnail">
				  
					<div id="remote-media-div"></div>
									  
				</div>
			 </div>
			</div>
		</div>		
	</div>

		
<script src="//media.twiliocdn.com/sdk/js/video/releases/2.11.0/twilio-video.min.js"></script>
<script>

$(document).ready(function(){
  
	const Video = Twilio.Video;
  var roomUrl = $(location).attr('href').split('/').pop();
	var localTrack;
	
	$('#heading_page').html("Class: "+roomUrl);
	//console.log("curr==>"+currUrl);
	$.ajax({
        type: "POST",
        url: "{{url('/')}}/admin/rooms/getAccessToken",    
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {'room_name':roomUrl},           
        success: function(res) {
				if(res.status != 1){
					$.alert({
							title: 'Error!',
							content: res.message,
					});
					return false;
				}
				Video.connect(res.data,{ audio: true, name: roomUrl, video: { width: 640 }})	
				.then(room => {
					console.log('Connected to Room "%s"', room.name);	  
					room.on('participantConnected', participant => {
					console.log(`Participant "${participant.identity}" connected`);
					
					

				  participant.on('trackSubscribed', track => {
					//const remoteval = document.getElementById('remote-media-div'); 
					//console.log(track);
					//document.getElementById('remote-media-div').innerHTML += '<div class="caption"><p><button class="btn btn-error" style="font-size:15px;color:maroon"><i class="fa fa-times-circle"></i></button><button class="btn btn-success" style="font-size:15px;color:green"><i class="fa fa-microphone"></i></button></p></div>';	
					//console.log(track);
					document.getElementById('remote-media-div').appendChild(track.attach());
				  });
					
				});

				const localParticipant = room.localParticipant;
				console.log(`Connected to the Room as LocalParticipant "${localParticipant.identity}"`);

				Video.createLocalVideoTrack().then(track => {						
					localTrack = track;
					const localMediaContainer = document.getElementById('local-media');
					localMediaContainer.innerHTML = '<div class="caption"><p><button class="btn btn-error" style="font-size:30px;color:maroon" onclick="disableLocaVideo()"><i class="fa fa-times-circle"></i></button><button class="btn btn-success" style="font-size:30px;color:green"><i class="fa fa-microphone"></i></button></p></div>';
					localMediaContainer.appendChild(track.attach());
											
				});				

				//room.on('participantDisconnected', participantDisconnected);
				room.once('disconnected', error => room.participants.forEach(participantDisconnected));
				
				});                 
        },
        error:function(request, status, error) {
            console.log("ajax call went wrong:" + request.responseText);
        }
				
  });
	
	
	
})

</script>
	
<style>
video{
	text-align:center;
}
#remote-media-div video{
	max-width:150px;
	max-height:160px;
	text-align:center;
}
.caption p {
	text-align:center
}
</style>
@endsection

