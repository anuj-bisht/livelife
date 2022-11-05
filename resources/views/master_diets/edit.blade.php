

            
            <form method="POST" action="{{ url('/') }}/trainer/master-diets/update/{{$diet->id}}" enctype="multipart/form-data">

            @csrf
            <div class="row">

                
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        {!! Form::text('name', $diet->name, array('placeholder' => 'Diet Name','class' => 'form-control')) !!}
                    </div>
                </div>

                                
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        {!! Form::select('level_id', $level_list,$diet->level_id, array('placeholder' => 'Level','class' => 'form-control')) !!}
                    </div>
                </div>
				
				<div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        {!! Form::select('type', $diet_type,$diet->type, array('placeholder' => 'Diet Type','class' => 'form-control')) !!}
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        Upload Diet : <input type="file" class="form-control" name="file_type">
                    </div>
                </div>

                @if($diet->file_path != "")
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">                        
                        <a href="..{{$diet->file_path}}" target="_blank">View Diet</a>
                    </div>
                </div>
                @endif
				
				<div class="col-xs-12 col-sm-12 col-md-12">
					<div class="form-group">
					{!! Form::textarea('description', $diet->description, ['class'=>'form-control', 'placeholder'=>'Write Description','id' => 'description', 'rows' => 4, 'cols' => 54, 'style' => 'resize:none']) !!}
					</div>
				</div>

                
                                
                <div class="col-xs-12 col-sm-12 col-md-12">
                    
                    <center><button type="submit" class="btn btn-primary">Update Diet</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
					</center>
                </div>
            </div>
            {!! Form::close() !!}
                        
