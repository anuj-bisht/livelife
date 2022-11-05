<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- CSRF Token -->
		<meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'LiveFitLife.in - Admin') }}</title>
		
		<!-- Favicon -->
		  <link rel="icon" href="{{ asset('assets/img/brand/favicon.png') }}" type="image/png">
		  <!-- Fonts -->
		  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
		  <!-- Icons -->
		  <link rel="stylesheet" href="{{ asset('assets/vendor/nucleo/css/nucleo.css') }}" type="text/css">
		  <link rel="stylesheet" href="{{ asset('assets/vendor/@fortawesome/fontawesome-free/css/all.min.css') }}" type="text/css">
		  <!-- Page plugins -->
		  <!-- Argon CSS -->
		  <link rel="stylesheet" href="{{ asset('assets/css/argon.css?v=1.2.0') }}" type="text/css">
		  
		   <script src="{{ asset('js/jquery-3.5.1.js') }}"></script>
			<script src="{{ asset('js/jquery.datetimepicker.full.js') }}"></script>
			<script src="//cdn.ckeditor.com/4.9.2/standard/ckeditor.js"></script>
			<link href="{{ asset('css/dataTables/dataTables.bootstrap.css') }}" rel="stylesheet">

					
			
			
		</head>

		<body>
		
		
		  <!-- Main content -->
		  <div class="main-content" id="panel">
			<!-- Topnav -->
			<!-- Topnav -->
			
			<!-- Header -->
			<div id="wrapper">
		
					<div id="page-wrapper">
						<div class="container-fluid"> 
							<div class='row'>
					      <div id="media-div">
					      </div>
					    </div>
					</div>
					</div>
				</div>
			
			</div>
			  <!-- Core -->
			  <script src="{{ asset('assets/vendor/jquery/dist/jquery.min.js') }}"></script>
			  <script src="{{ asset('assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
			  <script src="{{ asset('assets/vendor/js-cookie/js.cookie.js') }}"></script>
			  <script src="{{ asset('assets/vendor/jquery.scrollbar/jquery.scrollbar.min.js') }}"></script>
			  <script src="{{ asset('assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js') }}"></script>
			  <!-- Optional JS -->
			  <script src="{{ asset('assets/vendor/chart.js/dist/Chart.min.js') }}"></script>
			  <script src="{{ asset('assets/vendor/chart.js/dist/Chart.extension.js') }}"></script>
			  <!-- Argon JS -->
			  <script src="{{ asset('assets/js/argon.js?v=1.2.0') }}"></script>
			  
				<!--<script src="{{ asset('js/bootstrap.min.js') }}"></script>-->

				<script src="{{ asset('js/dataTables/jquery.dataTables.min.js') }}"></script>
				<script src="{{ asset('js/dataTables/dataTables.bootstrap.min.js') }}"></script>
				<script src="{{ asset('js/dataTables/dataTables.buttons.min.js') }}"></script>    
				<script src="{{ asset('js/dataTables/pdfmake.min.js') }}"></script>  
				<script src="{{ asset('js/dataTables/vfs_fonts.js') }}"></script>      
				<script src="{{ asset('js/dataTables/buttons.html5.min.js') }}"></script>      
				   

	
				<script src="{{ asset('js/metisMenu.min.js') }}" defer></script>


				<script src="{{ asset('js/raphael.min.js') }}" defer></script>
				
				<script src="{{ asset('js/jquery-confirm.min.js') }}"></script>       


				<script src="{{ asset('js/startmin.js') }}" defer></script>
				<script src="{{ asset('js/multiselect.js') }}"></script>
				
				<script src="{{ asset('js/moment-with-locales.min.js') }}"></script>
				<script src="{{ asset('js/jquery.datePicker.js') }}"></script>
				<script src="{{ asset('js/jquery.fancybox.min.js') }}"></script>
				
				<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/3.0.0/select2.css" rel="stylesheet" />
				<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/3.0.0/select2.js"></script>
				<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
		
			  <script src="//media.twiliocdn.com/sdk/js/video/v1/twilio-video.min.js"></script> 
			   <script>
				$(document).ready(function(){
					var site_url = "{{ url('/') }}";
					var csrf_token = "{{ csrf_token() }}";
					setSiteURL(site_url,csrf_token);

					$("#menuTitleSlug").keyup(function(){
						var Text = $(this).val();        
						Text = Text.toLowerCase();
						Text = Text.replace(/[^a-zA-Z0-9]+/g,'-');
						$("#realSlug").val(Text);        
					});
					var room_name = '{{ $roomName }}';
					$.ajax({
				      type: "post",
				      headers: {
				        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
				      },
				      url: "{{url('/')}}/rooms/join_room",
				      data: {room_name:room_name},
				      dataType: 'json',
				      beforeSend: function() {
				        },
				      success: function (response) {
				        Twilio.Video.createLocalTracks({
				       audio: true,
				       controls:true,
				       video: { width: '1000' }
				    }).then(function(localTracks) {
				       return Twilio.Video.connect(response.accessToken, {
				         name: response.roomName,
				         tracks: localTracks,
				         video: { width: '1000' }
				       });
				    }).then(function(room) {
				       console.log('Successfully joined a Room: ', room.name);

				       room.participants.forEach(participantConnected);

				       var previewContainer = document.getElementById(room.localParticipant.sid);
				       if (!previewContainer || !previewContainer.querySelector('video')) {
				         participantConnected(room.localParticipant);
				       }

				       room.on('participantConnected', function(participant) {
				         console.log("Joining: '"+participant.identity+"'");
				         participantConnected(participant);
				       });

				       room.on('participantDisconnected', function(participant) {
				         console.log("Disconnected: '"+participant.identity+"'");
				         participantDisconnected(participant);
				       });
				       
				       function unmute_mute_audio() {
							var localParticipant = room.localParticipant;
							localParticipant.audioTracks.forEach(function (audioTrack) {
							if ( audioTrack.isEnabled == true ) {
									audioTrack.disable();
									$('#audioid').removeClass('fa-microphone');
									$('#audioid').addClass('fa-microphone-slash');
							} else {
								audioTrack.enable();
								$('#audioid').removeClass('fa-microphone-slash');
								$('#audioid').addClass('fa-microphone');
							}
							});
						}
					
						function unmute_mute_video() {
							var localParticipant = room.localParticipant;
							localParticipant.videoTracks.forEach(function (videoTrack) {
							if ( videoTrack.isEnabled == true ) {
									videoTrack.disable();
									$('#cameraid').removeClass('fa-camera');
									$('#cameraid').addClass('fa-pause-circle');
									
							} else {
								videoTrack.enable();
								$('#cameraid').removeClass('fa-pause-circle');
								$('#cameraid').addClass('fa-camera');							
							}
							});
						}
					
					
						$('#cameraid').on('click',function(){						
							unmute_mute_video();
							
						})
						
						$('#audioid').on('click',function(){						
							unmute_mute_audio();
							
						})
					
				    });
				    // additional functions will be added after this point
				    
				    
				    
				    
				    function participantConnected(participant) {
				       //console.log('Participant "%s" connected', participant.identity);

				       const div = document.createElement('div');
				       div.id = participant.sid;
				       div.setAttribute("style", "float: left; margin: 10px;");
				       div.innerHTML = "<div style='clear:both'>"+participant.identity+"</div>";

				       participant.tracks.forEach(function(track) {
				         trackAdded(div, track)
				       });

				       participant.on('trackAdded', function(track) {
				         trackAdded(div, track)
				       });
				       participant.on('trackRemoved', trackRemoved);

				       document.getElementById('media-div').appendChild(div);
				    }

				    function participantDisconnected(participant) {
				       console.log('Participant "%s" disconnected', participant.identity);

				       participant.tracks.forEach(trackRemoved);
				       document.getElementById(participant.sid).remove();
				    }
				    
				    function trackAdded(div, track) {
				       div.appendChild(track.attach());
				       var video = div.getElementsByTagName("video")[0];
				       if (video) {
				         video.setAttribute("style", "max-width:600px;");
				       }
				    }

				    function trackRemoved(track) {
				       track.detach().forEach( function(element) { element.remove() });
				    }
				      }
				    });

				});



				</script>
			  <script src="{{ asset('js/custom.js') }}"></script>
	</body>

</html>
