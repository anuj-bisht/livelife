<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
	<div class="navbar-header">
		<a class="navbar-brand" href="index.html">LiveFitLife.in - Admin</a>
	</div>

	<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
		<span class="sr-only">Toggle navigation</span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
	</button>

	<ul class="nav navbar-right navbar-top-links">
		<li class="dropdown navbar-inverse" onclick="notificationData()">
			<a class="dropdown-toggle" data-toggle="dropdown" href="#" style="color:red">
			<span id="notificationid"></span><i class="fa fa-bell fa-fw"></i> <b class="caret"></b>
			</a>
			<ul class="dropdown-menu dropdown-alerts" id="notification_ul">
							
			</ul>
		</li>
		<li class="dropdown">
			<a class="dropdown-toggle" data-toggle="dropdown" href="#">
				<i class="fa fa-user fa-fw"></i> {{ Auth::user()->name }} <b class="caret"></b>
			</a>
			<ul class="dropdown-menu dropdown-user">
				<li><a href="#"><i class="fa fa-user fa-fw"></i> change password</a>
				</li>				
				<li class="divider"></li>
				<li>
				<a class="dropdown-item" href="{{ route('logout') }}"
					onclick="event.preventDefault();
									document.getElementById('logout-form').submit();">
					{{ __('Logout') }}
				</a>
				<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
					@csrf
				</form>				
				</li>
			</ul>
		</li>
	</ul>
	<!-- /.navbar-top-links -->

	<div class="navbar-default sidebar" role="navigation" style="overflow: auto;">
		<div class="sidebar-nav navbar-collapse">
			<ul class="nav" id="side-menu">
				
				<li>
					<a href="{{ route('home') }}" class="active"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
				</li>				
				@can('user-list')	
				<li>
					<a href="#"><i class="fa fa-files-o fa-fw"></i> User Management<span class="fa arrow"></span></a>
					<ul class="nav nav-second-level">
						@can('user-list')		
						<li>
							<a href="{{ route('users.index') }}">Manage Users</a>
						</li>				
						@endcan 
						@can('role-list')
						<li>
							<a href="{{ route('roles.index') }}"> Manage Role</a>
						</li>						
						@endcan 
						<li>
							<a href="{{ url('/') }}/admin/notifications"> Send Notificaiton</a>
						</li>
						
					</ul>
					<!-- /.nav-second-level -->
				</li>	
				@endcan 

				<li>
					<a href="#"><i class="fa fa-files-o fa-fw"></i> Demo/Test Request<span class="fa arrow"></span></a>
					<ul class="nav nav-second-level">
						@can('demorequest-list')			
						<li>
							<a href="{{ route('demorequests.index') }}">Demo Request</a>
						</li>				
						@endcan						
						<li>
							<a href="{{ route('testrequests.index') }}">Test Request</a>
						</li>
						
					</ul>
					<!-- /.nav-second-level -->
				</li>	


				<li>
					<a href="#"><i class="fa fa-files-o fa-fw"></i> Membership Management<span class="fa arrow"></span></a>
					<ul class="nav nav-second-level">
						@can('plan-list')					
						<li>
							<a href="{{ url('/') }}/admin/plans">Plan List</a>
						</li>		
						@endcan		
						<li>
							<a href="{{ url('/') }}/admin/subscriptions">Subscription List</a>
						</li>																				
					</ul>
					<!-- /.nav-second-level -->
				</li>	

				<li>
					<a href="#"><i class="fa fa-files-o fa-fw"></i> Diet Plan Management<span class="fa arrow"></span></a>
					<ul class="nav nav-second-level">
						@can('diet-list')							
						<li>
							<a href="{{ url('/') }}/admin/diets">Diet Plan List</a>
						</li>		
						@endcan																				
					</ul>
					<!-- /.nav-second-level -->
				</li>	

				<li>
					<a href="#"><i class="fa fa-files-o fa-fw"></i> Schedule Management<span class="fa arrow"></span></a>
					<ul class="nav nav-second-level">
						
										
						<li>
							<a href="{{ url('/') }}/admin/schedules/create">Create Schedule</a>
						</li>				
												
					</ul>
					
				</li>	
				@can('category-list')					
				<li>
					<a href="#"><i class="fa fa-files-o fa-fw"></i> Category Management<span class="fa arrow"></span></a>
					<ul class="nav nav-second-level">
								
						<li>
							<a href="{{ url('/') }}/admin/categories">category List</a>
						</li>				
												
					</ul>
					<!-- /.nav-second-level -->
				</li>
				@endcan

				<li>
					<a href="#"><i class="fa fa-files-o fa-fw"></i> Slot Management<span class="fa arrow"></span></a>
					<ul class="nav nav-second-level">
								
						<li>
							<a href="{{ url('/') }}/admin/slots">Slot List</a>
						</li>				
												
					</ul>
					<!-- /.nav-second-level -->
				</li>

				<li>
					<a href="#"><i class="fa fa-files-o fa-fw"></i> Level Management<span class="fa arrow"></span></a>
					<ul class="nav nav-second-level">

						@can('level-list')									
						<li>
							<a href="{{ url('/') }}/admin/levels">Level List</a>
						</li>		
						@endcan
						<li>
							<a href="{{ url('/') }}/admin/leveldatas">Level Data</a>
						</li>
						@can('video-list')							
						<li>
							<a href="{{ url('/') }}/admin/videos">Level Videos</a>
						</li>	
						@endcan
						@can('warmup-list')					
						<li>
							<a href="{{ url('/') }}/admin/warmups">Warmup Videos</a>
						</li>	
						@endcan			
												
					</ul>
					<!-- /.nav-second-level -->
				</li>	
				
				<li>
					<a href="#"><i class="fa fa-files-o fa-fw"></i> Recipe Management<span class="fa arrow"></span></a>
					<ul class="nav nav-second-level">
								
						<li>
							<a href="{{ url('/') }}/admin/recipes">Recipe List</a>
						</li>				
												
					</ul>
					<!-- /.nav-second-level -->
				</li>

				<li>
					<a href="#"><i class="fa fa-files-o fa-fw"></i> Chat Management<span class="fa arrow"></span></a>
					<ul class="nav nav-second-level">
								
						<li>
							<a href="{{ url('/') }}/admin/chats">Chat User</a>
						</li>				
												
					</ul>
					<!-- /.nav-second-level -->
				</li>

				<li>
					<a href="#"><i class="fa fa-files-o fa-fw"></i> Review List<span class="fa arrow"></span></a>
					<ul class="nav nav-second-level">
								
						<li>
							<a href="{{ url('/') }}/admin/reviews">Trainer Reviews</a>
						</li>				
												
					</ul>
					<!-- /.nav-second-level -->
				</li>

				<li>
					<a href="#"><i class="fa fa-files-o fa-fw"></i> Upload Section<span class="fa arrow"></span></a>
					<ul class="nav nav-second-level">
								
						<li>
							<a href="{{ url('/') }}/admin/tips">Daily Tip</a>
						</li>	
						<li>
							<a href="{{ url('/') }}/admin/gdiets">Diet plan</a>
						</li>	
						<li>
							<a href="{{ url('/') }}/admin/generic">Generic upload</a>
						</li>	
						<li>
							<a href="{{ url('/') }}/admin/banners">Banner upload</a>
						</li>				
												
					</ul>
					<!-- /.nav-second-level -->
				</li>

				<!-- <li>
					<a href="#"><i class="fa fa-files-o fa-fw"></i> Class Management<span class="fa arrow"></span></a>
					<ul class="nav nav-second-level">
								
						<li>
							<a href="{{ url('/') }}/admin/videolist">Room List</a>
						</li>				
												
					</ul>
					
				</li>	 -->
				@can('level-list')
				<li>
					<a href="{{ url('/') }}/admin/contactus"><i class="fa fa-files-o fa-fw"></i> Contact Enquiry</a>					
				</li>															
				@endcan		

				<li>
					<a href="{{ url('/') }}/admin/users/settings/1"><i class="fa fa-files-o fa-fw"></i> Settings</a>					
				</li>															
			</ul>
		</div>
	</div>
</nav>

<script>

</script>