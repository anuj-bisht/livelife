<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- CSRF Token -->
		<meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'LiveFitLife.in - Client') }}</title>
		
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

			
			<!--<link href="{{ asset('css/bootstrap3.min.css') }}" rel="stylesheet">

			<link href="{{ asset('css/dataTables/dataTables.bootstrap.css') }}" rel="stylesheet">
			<link href="{{ asset('css/dataTables/buttons.dataTables.min.css') }}" rel="stylesheet">        
			<link href="{{ asset('css/dataTables/dataTables.responsive.css') }}" rel="stylesheet">-->
			
			
			
		</head>

		<body>
		
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
		  <!-- Main content -->
		  <div class="main-content" id="panel">
			<!-- Topnav -->
			<!-- Topnav -->
			<nav class="navbar navbar-top navbar-expand navbar-dark bg-magento border-bottom" style='background-color:#BC0E83;'>
			  <div class="container-fluid">
				<div class="collapse navbar-collapse" id="navbarSupportedContent">
					<ul class="navbar-nav">
					<li class="nav-item">
					  <a class="nav-link active" href="{{ route('home') }}">
						<i class="ni ni-tv-2 text-primary"></i>
						<span class="nav-link-text">LiveFitLife</span>
					  </a>
					</li>
					@if($roles->name == 'Trainer')
					<li class="nav-item">
					  <a class="nav-link active" href="{{ route('trainer_clients') }}">
						<i class="ni ni-tv-2 text-primary"></i>
						<span class="nav-link-text">Clients</span>
					  </a>
					</li>
					@endif
				  </ul>

				  <ul class="navbar-nav align-items-center  ml-md-auto ">
					<li class="nav-item d-xl-none">
					  
					  <div class="pr-3 sidenav-toggler sidenav-toggler-dark" data-action="sidenav-pin" data-target="#sidenav-main">
						<div class="sidenav-toggler-inner">
						  <i class="sidenav-toggler-line"></i>
						  <i class="sidenav-toggler-line"></i>
						  <i class="sidenav-toggler-line"></i>
						</div>
					  </div>
					</li>
					<li class="nav-item d-sm-none">
					  <a class="nav-link" href="#" data-action="search-show" data-target="#navbar-search-main">
						<i class="ni ni-zoom-split-in"></i>
					  </a>
					</li>
					<li class="nav-item dropdown">
					  <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<i class="ni ni-bell-55"></i>
					  </a>
					  <div class="dropdown-menu dropdown-menu-xl  dropdown-menu-right  py-0 overflow-hidden">
						
						<div class="px-3 py-3">
						  <h6 class="text-sm text-muted m-0">You have <strong class="text-primary">0</strong> notifications.</h6>
						</div>
					  </div>
					</li>
				  </ul>
				  <ul class="navbar-nav align-items-center  ml-auto ml-md-0 ">
					<li class="nav-item dropdown">
					  <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<div class="media align-items-center">
						  <div class="media-body  ml-2  d-none d-lg-block">
						  
							<span class="mb-0 text-sm  font-weight-bold"><i class="fa fa-user fa-fw "></i> {{ Auth::user()->name }} <b class="caret"></b></span>
						  </div>
						</div>
					  </a>
					  <div class="dropdown-menu  dropdown-menu-right ">
						<div class="dropdown-header noti-title">
						  <h6 class="text-overflow m-0">Welcome!</h6>
						</div>
						<a class="dropdown-item" href="{{ route('logout') }}"
							onclick="event.preventDefault();
											document.getElementById('logout-form').submit();">
							<i class="ni ni-user-run"></i>
						  <span>Logout</span>
						</a>
						<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
							@csrf
						</form>				

					  </div>
					</li>
				  </ul>
				</div>
			  </div>
			</nav>
			<!-- Header -->
			<div id="wrapper">
		
					@yield('content')
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
				});



				</script>
			  <script src="{{ asset('js/custom.js') }}"></script>
	</body>

</html>
			