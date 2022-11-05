@extends('layouts.app')
<link href="{{ asset('/css/jquery.dm-uploader.min.css') }}" rel="stylesheet">
@section('content')

	<!-- Navigation -->
	@include('layouts.left')

	<div id="page-wrapper">
		<div class="container-fluid">


            <div class="row">
                    <div class="col-lg-10">
                        <h1 class="page-header">{{__('Edit Video')}}</h1>                        
                    </div>		                    
            </div>

            @include('layouts.flash')

            <ul class="nav nav-tabs">
                <li ><a data-toggle="tab" href="#main">Main</a></li>
                <li class="active"><a data-toggle="tab" href="#video">Videos</a></li>
            </ul>

            <div class="tab-content">
                <div id="main" class="tab-pane fade in active">
                    <form method="POST" action="{{ url('/') }}/admin/videos/update/{{$video->id}}">
                    @csrf
                    <div class="row">                        
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Name:</strong>
                                {!! Form::text('name', $video->name, array('placeholder' => 'Video Name' , 'class' => 'form-control')) !!}
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
                        <strong>Youtube Code:</strong>
                        {!! Form::text('code', $LevelData->code, array('placeholder' => 'Youtube code','class' => 'form-control')) !!}
                    </div>
                </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>description:</strong>
                                {!! Form::textarea('description', $video->description, array('placeholder' => 'Video description','class' => 'form-control')) !!}
                            </div>
                        </div>                                                                                        
                        <div class="col-xs-12 col-sm-12 col-md-12">                            
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
                <div id="video" class="tab-pane fade">
                    <div class="row">
                        <div class="col-md-6 col-sm-12">                            
                            <!-- Our markup, the important part here! -->
                            <div id="drag-and-drop-zone" class="dm-uploader p-5">
                                <h3 class="mb-5 mt-5 text-muted">Drag &amp; drop files here</h3>

                                <div class="btn btn-primary btn-block mb-5">
                                    <span>Open the file Browser</span>
                                    <input type="file" title='Click to add Files' />
                                </div>
                            </div><!-- /uploader -->

                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="card h-100">
                                <div class="card-header">
                                File List
                                </div>

                                <ul class="list-unstyled p-2 d-flex flex-column col" id="files">
                                <li class="text-muted text-center empty">No files uploaded.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-top:20px;">
                        <div class="col-md-12 col-sm-24">
                            <h3>Uploaded Videos</h3>
                        </div>
                        <div class="col-md-12 col-sm-24">                                                            
                            <div id="videolist">
                                
                            </div>
                        </div>                            
                    </div>
                </div>
            </div>                                    
		</div>		
	</div>
	<!-- /#page-wrapper -->

<script src="{{ url('/') }}/js/jquery.dm-uploader.min.js"></script>
<script src="{{ url('/') }}/js/demo-ui.js"></script>
<script src="{{ url('/') }}/js/demo-config.js"></script>

<!-- File item template -->
<script type="text/html" id="files-template">
    <li class="media">
    <div class="media-body mb-1">
        <p class="mb-2">
        <strong>%%filename%%</strong> - Status: <span class="text-muted">Waiting</span>
        </p>
        <div class="progress mb-2">
        <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" 
            role="progressbar"
            style="width: 0%" 
            aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
        </div>
        </div>
        <hr class="mt-1 mb-1" />
    </div>
    </li>
</script>

<script>

function videolist(id){
    jQuery.ajax({        
        type : 'POST',
        url : "{{url('/')}}/admin/videos/videolist",
        data : {'id':id},
        beforeSend: function(xhr){xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));},
        success: function (data) {
            var output = JSON.parse(data);
            var str = '';
            if(Object.keys(output).length > 0){
                $.each( output, function( key, val ) {
                    var selected = '';
                    if(val.default == 'Y'){
                        selected = 'checked';
                    }

                    

                    str += '<div class="col-md-3 col-sm-6"><div class="polaroid">';
                    str += '<video width="212" height="180" controls>';
                    str += '<source src="'+val.video+'" type="video/mp4">';                    
                    str += 'Your browser does not support the video tag.';
                    str += '</video>';                    
                    str += '<div style="padding:5px;"><p><input type="radio" name="default" onclick="setDefault('+val.id+')" '+selected+' value="'+val.id+'">&nbsp;Default<i class="fa fa-trash fontcls" onclick="deleteImage('+val.id+')" aria-hidden="true"></i></p></div></div></div>';


                });
                $('#videolist').html(str);
            }else{
                $('#videolist').html('No Image Found');    
            }
            
            
            
        },
        error: function(data){
        var output = JSON.parse(data)
        jQuery('#videolist').html('<span style="color:red">'+output['message']+'</span>');
        }
    })
}

               
function setDefault(id){
    jQuery.ajax({        
        type : 'POST',
        url : "{{url('/')}}/admin/videos/setDefault",
        data : {'id':id},
        beforeSend: function(xhr){xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));},
        success: function (data) {
            videolist({{$video->level_id}});            
        },
        error: function(data){
        var output = JSON.parse(data)
        jQuery('#videolist').html('<span style="color:red">'+output['message']+'</span>');
        }
    })
}
function deleteImage(id){


    $.confirm({
        title: 'Delete Confirmation!',
        content: 'Are you sure want to delete Video!',
        buttons: {
            confirm: function () {
                jQuery.ajax({        
                    type : 'POST',
                    url : "{{url('/')}}/admin/videos/deleteVideo",
                    data : {'id':id},
                    beforeSend: function(xhr){xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));},
                    success: function (data) {
                        videolist({{$video->level_id}});            
                    },
                    error: function(data){
                    var output = JSON.parse(data)
                    jQuery('#videolist').html('<span style="color:red">'+output['message']+'</span>');
                    }
                })
            },
            cancel: function () {
                return true;
            }
        }
    });


    
    
}

$(function(){

    
  /*
   * For the sake keeping the code clean and the examples simple this file
   * contains only the plugin configuration & callbacks.
   * 
   * UI functions ui_* can be located in: demo-ui.js
   */
  $('#drag-and-drop-zone').dmUploader({ //
    url: "{{url('/')}}/admin/videos/uploadFiles/{{$video->level_id}}",
    maxFileSize: 30000000000000, // 3 Megs 
    
    headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
              },
    onDragEnter: function(){
      // Happens when dragging something over the DnD area
      this.addClass('active');
    },
    onDragLeave: function(){
      // Happens when dragging something OUT of the DnD area
      this.removeClass('active');
    },
    onInit: function(){
      // Plugin is ready to use
      
      ui_add_log('Penguin initialized :)', 'info');
    },
    onComplete: function(){
      // All files in the queue are processed (success or error)
      ui_add_log('All pending tranfers finished');
    },
    onNewFile: function(id, file){
      // When a new file is added using the file selector or the DnD area
      ui_add_log('New file added #' + id);
      ui_multi_add_file(id, file);
    },
    onBeforeUpload: function(id){
      // about tho start uploading a file
      ui_add_log('Starting the upload of #' + id);
      ui_multi_update_file_status(id, 'uploading', 'Uploading...');
      ui_multi_update_file_progress(id, 0, '', true);
    },
    onUploadCanceled: function(id) {
      // Happens when a file is directly canceled by the user.
      ui_multi_update_file_status(id, 'warning', 'Canceled by User');
      ui_multi_update_file_progress(id, 0, 'warning', false);
    },
    onUploadProgress: function(id, percent){
      // Updating file progress
      ui_multi_update_file_progress(id, percent);
    },
    
    onUploadSuccess: function(id, data){
      if(data.status=="error"){
        ui_multi_update_file_status(id, 'danger', data.message);
        ui_multi_update_file_progress(id, 0, 'danger', false);  
      }else{
        // A file was successfully uploaded
        ui_add_log('Server Response for file #' + id + ': ' + JSON.stringify(data));
        ui_add_log('Upload of file #' + id + ' COMPLETED', 'success');
        ui_multi_update_file_status(id, 'success', 'Upload Complete');
        ui_multi_update_file_progress(id, 100, 'success', false);
        videolist({{$video->level_id}});
      }
      
    },
    onUploadError: function(id, xhr, status, message){
      ui_multi_update_file_status(id, 'danger', message);
      ui_multi_update_file_progress(id, 0, 'danger', false);  
    },
    onFallbackMode: function(){
      // When the browser doesn't support this plugin :(
      ui_add_log('Plugin cant be used here, running Fallback callback', 'danger');
    },
    onFileSizeError: function(file){
      ui_add_log('File \'' + file.name + '\' cannot be added: size excess limit', 'danger');
    }
  });

  
  videolist({{$video->level_id}});

});

</script>
<style>

.dm-uploader {
    border: 0.25rem dashed #A5A5C7;
    text-align: center;
}
.dm-uploader.active {
	border-color: red;

	border-style: solid;
}
.card-header:first-child {
    border-radius: calc(.25rem - 1px) calc(.25rem - 1px) 0 0;
}

.card {
    position: relative;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -ms-flex-direction: column;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 1px solid rgba(0,0,0,.125);
    border-radius: .25rem;
}

.card-header {
    padding: .75rem 1.25rem;
    margin-bottom: 0;
    background-color: rgba(0,0,0,.03);
    border-bottom: 1px solid rgba(0,0,0,.125);
}

@media (min-width: 768px)
.col-md-6 {
    /* -webkit-box-flex: 0; */
    -ms-flex: 0 0 50%;
    /* flex: 0 0 50%; */
    max-width: 50%;
}   
#files {
    min-height: 0;
}
#files {
    overflow-y: scroll !important;
    min-height: 320px;
}
.p-2 {
    padding: .5rem!important;
}

.p-5 {
    padding: 3rem!important;
}
.mb-5, .my-5 {
    margin-bottom: 11rem!important;
}
.mt-5, .my-5 {
    margin-top: 3rem!important;
}
.h3, h3 {
    font-size: 1.75rem;
}
#videolist p {
   margin: 0 0 0px;
}
#videolist div.polaroid {  
  background-color: white;
  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
  margin-bottom: 25px;
}

#videolist .fontcls {
    float: right;
    margin: 0 4px 0 0;
    font-size: 16px;
    color: red;
    cursor:pointer;
}

#videolist div.container {  
  padding: 10px 5px;
}

</style>            
@endsection
