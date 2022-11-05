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
		

			#topText {
				background-color:black;
				border-radius:20px;
				padding-left:10px;
				color: pink;
				font-size: 20px;
				align-self: center;
			}
			
			.action:hover{
					opacity:0.5;
				}

				.row{
					margin-left:-255px;
					
				}


				#trainer-media-div{
					height:600px;
					max-width: 900px;
				}

				#media-div{
					height:600px;
					max-width: 900px;
				}

				#trainer-media-div video{
					height:550px;
					max-width: 900px;
				}

			</style>
			
			
		</head>

		<body  style="background-color:black">
		
		<!-- Sidenav -->
		 <!--<nav class="sidenav navbar navbar-vertical  fixed-left  navbar-expand-xs navbar-light bg-white" id="sidenav-main">
			<div class="scrollbar-inner">
			  <div class="sidenav-header  align-items-center">
				<a class="navbar-brand" href="javascript:void(0)">
				  LiveFitLife
				</a>
			  </div>
			  <div class="navbar-inner">
				<div class="collapse navbar-collapse" id="sidenav-collapse-main">

				  <ul class="navbar-nav">
					<li class="nav-item">
					  <a class="nav-link active" href="{{ route('home') }}">
						<i class="ni ni-tv-2 text-primary"></i>
						<span class="nav-link-text">Dashboard</span>
					  </a>
					</li>
					<li class="nav-item">
					  <a class="nav-link active" href="{{ route('trainer_clients') }}">
						<i class="ni ni-tv-2 text-primary"></i>
						<span class="nav-link-text">Clients</span>
					  </a>
					</li>
					<li class="nav-item">
					  <a class="nav-link active" href="{{ route('master_diets') }}">
						<i class="ni ni-tv-2 text-primary"></i>
						<span class="nav-link-text">Diets</span>
					  </a>
					</li>
				  </ul>
				</div>
			  </div>
			</div>
		  </nav>-->
		  <nav class="navbar navbar-expand-sm p-1" style="background-color:black">

			<!-- Links -->
			<ul class="navbar-nav">
				<li class="nav-item">
					<button class="btn btn-success mr-2" style="background-color:black;color:white" onclick="countdown(1)">Start Timer</button>
				</li>
				<li class="nav-item">
					<button class="btn btn-danger mr-2" style="display:none;background-color:black;color:white" onclick="stoptimer()">Stop Timer</button>
				</li>
				<li class="nav-item">
					<div id="timerdata" class="mr-2 ml-2 p-2" style="background-color:#DD4777; color:white; border-radius:10px;"></div>
				</li>
			</ul>
			<ul class="navbar-nav ml-auto mr-4">
				<li class="nav-item action">
					<i class="fa fa-phone" onclick="closeTab()" aria-hidden="true" style="cursor:pointer;background-color:red;color:white;border-radius:20px;padding:9px;font-size:18px;margin:5px;"></i>
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
		  <div class="main-content" id="panel">
			<!-- Topnav -->
			<!-- Topnav -->
			
			<!-- Header -->
			<div id="container">
					<!-- <div class="row" style="margin-left:3px;margin-top:5px;">
						<div class="col-md-12">
							<button class="btn" style="background-color:black;color:white" onclick="countdown(1)">Start Timer</button>
							<button class="btn btn-danger" style="display:none" onclick="stoptimer()">Stop Timer</button>
							<span id="timerdata"></span>
						</div>
					</div> -->
					
					<div class="row" style='display:flex;flex-direction: row-reverse;'>
						<div class="col-md-5">
							<div id="trainer-media-div">
					    	</div>
						</div>
						<div class="col-md-5">
							<div id="media-div">
					    	</div>
						</div>
					</div>


					
					<!-- <div id="page-wrapper">
						<div class="container-fluid"> 
							<div class='row'>
								<div id="media-div">
								</div>
					    	</div>
						</div>
					</div> -->
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
				      url: "{{url('/')}}/rooms/join_test_room",
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
					   console.log("PARTICIPATS CONNECTED: ",participantConnected.length);

						if(participantConnected.length>0){
							var previewContainer = document.getElementById(room.localParticipant.sid);
							if (!previewContainer || !previewContainer.querySelector('video')) {
								participantConnected(room.localParticipant);
							}

							room.on('participantConnected', function(participant) {
								console.log("Joining: '"+participant.identity+"'");
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
				       div.setAttribute("style", "float: left; margin: 10px; width: inherit; height: inherit;");
				       div.innerHTML = "<div style='top:87%;left:7%;z-index:1;'><p id='topText'>"+participant.identity+"</p></div>";

					     const div1 = document.createElement('div1');
				       div1.id = participant.sid;
				       div1.setAttribute("style", "float: left; margin: 10px;");
				       div1.innerHTML = "<div style='top:87%;left:7%;z-index:1;'><p id='topText'>"+participant.identity+"</p></div>";

				       participant.tracks.forEach(function(track) {
				         trackAdded(div, track)
				       });

					   console.log("TRACKS:", participant.tracks.size);

				       participant.on('trackAdded', function(track) {
				         trackAdded(div, track)
				       });
				       participant.on('trackRemoved', trackRemoved);

					   if(participant.tracks.size>1){
							document.getElementById('trainer-media-div').appendChild(div);
					   }else{
							document.getElementById('media-div').appendChild(div);
							
					   }
				    }

				    function participantDisconnected(participant) {
				       console.log('Participant "%s" disconnected', participant.identity);

				       participant.tracks.forEach(trackRemoved);
				       document.getElementById(participant.sid).remove();
				    }
				    
				    function trackAdded(div, track) {
				       div.appendChild(track.attach());
				       var video = div.getElementsByTagName("video")[0];
					   	 console.log("--------------------------------",div.getElementsByTagName("video").length);
				       if (video) {
				        //  video.setAttribute("style", "max-width:100%;");
								//  video.setAttribute("style", "height:480px;");
				       }
				    }

                    

				    function trackRemoved(track) {
				       track.detach().forEach( function(element) { element.remove() });
				    }
				      }
				    });
				    
				    
				    var lengthb = document.getElementById('media-div').length;
						console.log("LENGTH: ",lengthb);
					
					
					
					$('#testrequest_model_form').submit(function(e){
						e.preventDefault();
						var timerval = $('#timervalue').val($('#timerdata').html());
						$.ajax({
							type: "POST",
							url: "{{url('/')}}/trainer/testrequests/testtimer",    
							headers: {
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
							},
							data: $('#testrequest_model_form').serialize()+"&timerval="+timerval,           
							success: function(res) {
								$('#exampleModal').modal('hide'); 

                                window.close();      
																		  
							},
							error:function(request, status, error) {
								console.log("ajax call went wrong:" + request.responseText);
							}
						});
						
					  });

				});
				
					var THROTTLE_AMOUNT = 4;
					var counter;
					var flag = 0;
					function countdown(secs) {		
						if(flag){
							return;
						}
						sendNotificationAjax('start',getParameterByName("uid"),0);

						$('.btn-success').css('display','none');
						$('.btn-danger').css('display','block');
						var milli = secs * (180000);
						
						counter = setInterval(function() {
							if(milli <= 0) {
								clearInterval(counter);
								return
							}
							milli -= THROTTLE_AMOUNT;
							
							$('#timerdata').html(msToTime(milli)); // watch for spelling
						}, THROTTLE_AMOUNT);
					}
					
					
					function msToTime(s) {
					  
					  var ms = s % 1000;
					  s = (s - ms) / 1000;
					  var secs = s % 60;
					  s = (s - secs) / 60;
					  var mins = s % 60;
					  var hrs = (s - mins) / 60;

					  return mins + ':' + secs + '.' + ms;
					}
					
					function stoptimer(){
							$('.btn-success').css('display','block');
							$('.btn-danger').css('display','none');	
							flag = 1;	
							clearInterval(counter);
							//var timex = $('#timervalue').val($('#timerdata').html());								
							var timex = $('#timerdata').html();
							var guid = getParameterByName("uid");
							//console.log(xxx);
							if(guid){
								sendNotificationAjax('stop',guid,timex);
								$('#exampleModal').modal('show');
								$('#timervalue').val($('#timerdata').html());
								$('#uid').val(getParameterByName("uid"));
							}
																  						  
					}
					
					
					function getParameterByName(name, url) {
						 if (!url) url = window.location.href;
						 name = name.replace(/[\[\]]/g, "\\$&");
						 var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
							 results = regex.exec(url);
						 if (!results) return null;
						 if (!results[2]) return '';
						 return decodeURIComponent(results[2].replace(/\+/g, " "));
					 }
					 
					function sendNotificationAjax(type,uid,timerstr){
						$.ajax({
							url : "{{url('/')}}/admin/testrequests/sendNotificationAjax",
							type: 'POST',
							headers: {
									'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
							},
							data : {"uid":uid,"type":type,"timerstr":timerstr},
							success: function(res){
							  console.log('test requested notification initiated/ended');
								
							}
						})
					} 

                    function closeTab()
                    {
                        window.close();
                    }

				</script>
			  <script src="{{ asset('js/custom.js') }}"></script>
			  
			  
			  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			  <div class="modal-dialog" role="document">
				<div class="modal-content">
				  <div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Test feedback</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					  <span aria-hidden="true">&times;</span>
					</button>
				  </div>
				  <div class="modal-body">
					
					<form method="POST" id="testrequest_model_form" action="">
					  <div class="form-group">
						<label for="recipient-name" class="col-form-label">Test status:</label>
						<select name="teststatus" class="form-control">
							<option value="P">Pass</option>
							<option value="F">Fail</option>
						</select>		
						<input type="hidden" name="uid" id="uid">				
						<input type="hidden" name="timervalue" id="timervalue">				
					  </div>
					  <div class="form-group">
						<label for="message-text" class="col-form-label">Comment:</label>
						<textarea class="form-control" name="comment" id="comment"></textarea>
					  </div>
					
				  </div>
				  <div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary" id="submittest">Submit</button>
				  </div>
				  </form>
				</div>
			  </div>
			</div>
	</body>

</html>
