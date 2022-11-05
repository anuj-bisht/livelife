@extends('layouts.app')

@section('content')
	
	<div id="page-wrapper">
		<div class="container-fluid">         
				@if(!empty($successMsg))
				  <div class="alert alert-success"> {{ $successMsg }}</div>
				@endif                   
				
				@if(!empty($errorMsg))
				  <div class="alert alert-danger"> {{ $errorMsg }}</div>
				@endif                   

		</div>		
	</div>

            
@endsection
