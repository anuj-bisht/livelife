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
		  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha512-SfTiTlX6kk+qitfevl/7LibUOeJWlt9rbyDn92a1DqWOw9vWG2MFoays0sgObmWazO5BQPiFucnnEAjpAB+/Sw==" crossorigin="anonymous" />
		   <script src="{{ asset('js/jquery-3.5.1.js') }}"></script>
			<script src="{{ asset('js/jquery.datetimepicker.full.js') }}"></script>
			<script src="//cdn.ckeditor.com/4.9.2/standard/ckeditor.js"></script>
			<link href="{{ asset('css/dataTables/dataTables.bootstrap.css') }}" rel="stylesheet">

			
			<!--<link href="{{ asset('css/bootstrap3.min.css') }}" rel="stylesheet">

			<link href="{{ asset('css/dataTables/dataTables.bootstrap.css') }}" rel="stylesheet">
			<link href="{{ asset('css/dataTables/buttons.dataTables.min.css') }}" rel="stylesheet">        
			<link href="{{ asset('css/dataTables/dataTables.responsive.css') }}" rel="stylesheet">-->
			<style>
		.main-container{
			padding:20px;
			display:flex;
			height:100%;
			justify-content:space-between;
			position: relative;
		}

		.row{

width:80%;


		}
		.row1{
width:20%;
height:550px;
overflow-y:scroll
		}

			#topText {
				background-color:black;
				border-radius:20px;
				padding-left:10px;
				padding-right:10px;
				color: pink;
				font-size: 20px;
				align-self: center;
			}

				body {
				background-color: #eaeaea;
				/* display: flex;
				align-items: center;
				justify-content: center;
				flex-direction: column; */
				}

				.scrolling-box {
					background-color: #eaeaea;
					display: block;
					/* height: 500px; */
					width:100%;
					/* width: 200px;
					height: 200px; */
					/* padding: 1em; */
					overflow-x: scroll;
					text-align: center;
				}

				#trainer-media-div video{
					width:100% !important; 
				}
				#media-div video{
					width:100% !important; 
					
				}
				.wrap{
					display: block;
					height: 200px;
					width:100%;
					text-align: center;
				}

				section {
				display: flex;
				align-items: center;
				justify-content: center;
				height: 100%;
				}

				.action:hover{
					opacity:0.5;
				}

				
			</style>
			
			
		</head>

		<body >
		
		
		<nav class="navbar navbar-expand-sm p-1" style="background-color:black">
			<!-- Links --> 
			<ul class="navbar-nav">
				<li class="nav-item">
					<button class="btn btn-success mr-2" style="background-color:black;color:white">Demo</button>
				</li>
			</ul>
			<ul class="navbar-nav ml-auto mr-4">
				<li class="nav-item action" onclick="closeTab()">
					<i class="fa fa-phone" aria-hidden="true" style="cursor:pointer;background-color:red;color:white;border-radius:20px;padding:9px;font-size:18px;margin:5px;"></i>
				</li>
				<li class="nav-item action">
					<i class="fa fa-microphone" id="audioid" style="cursor:pointer;background-color:grey;color:white;border-radius:20px;padding:9px;font-size:18px;margin:5px;" aria-hidden="true"></i>
				</li>
				<li class="nav-item action">
					<i class="fa fa-camera" id="cameraid" style="cursor:pointer;background-color:grey;color:white;border-radius:20px;padding:9px;font-size:18px;margin:5px;" aria-hidden="true"></i>
				</li>
			</ul>
		</nav>

		  <!-- Main content -->
		  
			<!-- Topnav -->
			<!-- Topnav -->
			
			<!-- Header -->
			<div id="container" class='main-container'>
				<div class="row m-0">
					<div class="col-md-12 justify-content-center" style="margin:auto;padding:auto;">
						
						<div id="trainer-media-div" class="d-flex justify-content-center">
					    </div>
					</div>
				</div><div class="row1" style='background-color:#80808029;'>
						<div class="col-md-12" style="overflow: auto;
						white-space: nowrap">
							{{-- <div class="scrolling-box justify-content-center"> --}}
								
								
									{{-- <div class="row" style="height:200px">
										<div class="col-md-12"> --}}
											<span id="media-div"  style="height:auto;display:flex;flex-direction:column"></span>
										{{-- </div>
									</div> --}}
								
								<!-- <div class="wrap">
									<div id="media-div"  style="height:auto;">
									</div>
								</div> -->
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
				      url: "{{url('/')}}/rooms/join_demo_room",
				      data: {room_name:room_name},
				      dataType: 'json',
				      beforeSend: function() {
				        },
				      success: function (response) {
						  console.log(response);
				        Twilio.Video.createLocalTracks({
				       audio: true,
				       controls:true,
				       video: { width: '800' }
				    }).then(function(localTracks) {
						console.log("localTracks=",localTracks);
				       return Twilio.Video.connect(response.accessToken, {
				         name: response.roomName,
				         tracks: localTracks,
				         video: { width: '800' }
				       });
				    }).then(function(room) {
				       console.log('Successfully joined a Room: ', room.name);

				       room.participants.forEach(participantConnected);
				       

					   if(participantConnected.length>0){
							var previewContainer = document.getElementById(room.localParticipant.sid);
							if (!previewContainer || !previewContainer.querySelector('video')) {
								participantConnected(room.localParticipant);
							}

							room.on('participantConnected', function(participant) {
								console.log("Joining: '"+participant+"'");
								participantConnected(participant);
								
							});
						}else{
							var previewContainer = document.getElementById(room.localParticipant.sid);
							if (!previewContainer || !previewContainer.querySelector('video')) {
								TrainerConnected(room.localParticipant);
							}
						}
				   

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
						
					});
					
					$('#audioid').on('click',function(){						
						unmute_mute_audio();
						
					});
					
					
					});
				    
				    
				    function participantConnected(participant) {
				     // console.log('Participant "%s" connected', participant.tracks);

				       const div = document.createElement('div');
				       const div1 = document.createElement('span');
				       
						div.id = participant.sid;
						div1.id = participant.sid;
				        div.setAttribute("style", "margin: 10px;width:100%");
					    div.setAttribute("class", "justify-content-center;");
					    var part_id=div1.id;
					    div1.setAttribute("data-id", part_id);
					    div1.setAttribute("onclick",'changeRoom(this)');
				    	//    div.innerHTML = "<div style='clear:both'>"+participant.identity+"</div>";
						div.innerHTML = "<div class='d-flex justify-content-center' style='position:relative;top:100%;'><p id='topText_"+div.id+"' style='position: absolute;background-color: black;opacity: 0.6;color: white;'>"+participant.identity+"</p></div>";
						div1.innerHTML = "<span style='display:flex' id='topText_"+div1.id+"'><span  class='testing' style='position: relative;top: 2%;left: 10%;background-color: black;opacity: 0.6;color: white;'>"+participant.identity+"</span></span>";
						

				       participant.tracks.forEach(function(track) {
				         trackAdded(div, track)
				       });
					 // console.log(participant);
					   if(participant._instanceId == '1'){
							participant.on('traintrackAdded', function(track) {
								trackAddedTrainer(div, track)
							});
							participant.on('trackRemoved', trackRemoved);
							document.getElementById('trainer-media-div').appendChild(div);
					   }else{
							participant.on('trackAdded', function(track) {
								trackAdded(div1, track)
							});
							participant.on('trackRemoved', trackRemoved);
							document.getElementById('media-div').appendChild(div1);
					   }
					   
				    }

				    function participantDisconnected(participant) {
				    //    console.log('Participant "%s" disconnected', participant.identity);

				       participant.tracks.forEach(trackRemoved);
				       document.getElementById(participant.sid).remove();
					   document.getElementById('topText_'+participant.sid).remove();
				    }
				    
				    function trackAdded(div1, track) {
				       div1.appendChild(track.attach());
				       var video = div1.getElementsByTagName("video")[0];
				       if (video) {
				        //  video.setAttribute("style", "width:100px;");
						//  video.setAttribute("style", "height:100%");
						//  video.setAttribute("style", "");
				       }
				    }

					function trackAddedTrainer(div, track) {
				       div.appendChild(track.attach());
				       var video = div.getElementsByTagName("video")[0];
				       if (video) {
				        //  video.setAttribute("style", "width:100px;");
						//  video.setAttribute("style", "height:100%");
						//  video.setAttribute("style", "width:200px !important");
				       }
					   
				    }
					// console.log(getElementsByTagName("video"));

					function traintrackAdded(div, track) {
				       div.appendChild(track.attach());
				       var video = div.getElementsByTagName("video")[0];
				       if (video) {
				         //video.setAttribute("style", "width:auto;");
						 video.setAttribute("style", "height:100%");
						 video.setAttribute("style", "border-radius:20px;");
						 video.setAttribute("class", "justify-content-center");

				       }
				    }

				    function trackRemoved(track) {
				       track.detach().forEach( function(element) { element.remove() });
				    }
				      }
				    });

				});
				function changeRoom(obj)
				{

					var  part_id = $(obj).data().id;
					
				
					 console.log("partdatadata",part_id);
					 $('#trainer-media-div').html(document.getElementById(part_id));
					// $('#part_id').html(document.getElementById(trainer-media-div));
					// $('#exampleM').click();

					//document.getElementById('trainer-media-div').appendChild(document.getElementById(part_id));
				}
				
                function closeTab()
                {
                    window.close();
                }
                
                
                
                
                
                

				</script>
			  <script src="{{ asset('js/custom.js') }}"></script>
	</body>

</html>
