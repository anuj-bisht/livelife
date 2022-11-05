@extends('layouts.app')

@section('content')

	<!-- Navigation -->
	@include('layouts.left')

	<div id="page-wrapper">
		<div class="container-fluid">


            <div class="row">
                    <div class="col-lg-10">
                        <h1 class="page-header">{{__('Edit Level Data')}}</h1>                        
                    </div>		                    
            </div>

            @include('layouts.flash')

            
            <form method="POST" action="{{ url('/') }}/admin/leveldatas/update/{{$LevelData->id}}" enctype="multipart/form-data">

            @csrf
            <div class="row">

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Name:</strong>
                        {!! Form::text('name', $LevelData->name, array('placeholder' => 'Name','class' => 'form-control')) !!}
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Categories:{{$LevelData->category_id}}</strong>
                        {!! Form::select('category_id', $categories, $LevelData->category_id, array('placeholder' => 'Categories','class' => 'form-control','id'=>'category_id')) !!}
                    </div>
                </div>

                                
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>levels:</strong>
                        {!! Form::select('level_id',$levels, $LevelData->level_id, array('placeholder' => 'Level','class' => 'form-control','id'=>'level_id_dd')) !!}
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Type:</strong>
                        {!! Form::select('level_type', ['Warmup'=>'Warmup','Exercise'=>'Exercise','Optional Exercise'=>'Optional Exercise'], $LevelData->level_type, array('placeholder' => 'Type','class' => 'form-control')) !!}
                    </div>
                </div>
                
                @if($LevelData->image != "")
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">                        
                        <img src="{{$LevelData->image}}" style="max-width:200px;max-height:200px;">
                    </div>
                </div>
                @endif

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Upload Gif Icon:</strong>
                        <input type="file" class="form-control" name="file">
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Youtube Code:</strong>
                        {!! Form::text('code', $LevelData->code, array('placeholder' => 'Youtube code','class' => 'form-control')) !!}
                    </div>
                </div>
                
                
                                
                <div class="col-xs-12 col-sm-12 col-md-12">
                    
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
            {!! Form::close() !!}
                        


		</div>		
	</div>
	<!-- /#page-wrapper -->
    <script>
$(document).ready(function(){
    $('#category_id').on('change',function(){
        var selVal = $(this).val();
        if(selVal=="" || selVal=="0"){
            jQuery.alert({
                    title: 'Alert!',
                    content: 'Please select category',
            });
            return false;
        }
        $.ajax({
            type: "POST",
            url: "{{url('/')}}/admin/leveldatas/getLevelByCategory",    
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {"id":selVal},           
            success: function(res) {
                var str = '<option value="0">Level</option>';
                if(res.status){
                    $.each(res.data, function(index,value) {
                        str += '<option value="'+value.id+'">'+value.name+'</option>'; 
                    });
                    $('#level_id_dd').empty().append(str);
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
    })
})
</script>               
@endsection
