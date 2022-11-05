@extends('layouts.app')

@section('content')

	<!-- Navigation -->
	@include('layouts.left')

	<div id="page-wrapper">
		<div class="container-fluid">


      <div class="row">
				<div class="col-lg-12">
					<h1 class="page-header">{{__('Demo Request List')}}</h1>
				</div>
				<!-- /.col-lg-12 -->
			</div>  

      <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>&nbsp;</h2>
            </div>   
            <div class="pull-right">                
                <a  href="#" data-toggle="modal" data-target="#exampleModal" class="btn btn-default trainer-btn-action">Assign Trainer</a>                
            </div>      
        </div>
      </div>

      @include('layouts.flash')

      <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>&nbsp;</h2>
            </div>   
            <div class="pull-right">                
               <select name="category_id" class="form-control" id="categoryList">
                  @foreach($catList as $k=>$v)
                    <option value="{{$v->id}}">{{$v->name}}</option>
                  @endforeach
               </select>
            </div>      
        </div>
      </div>
      <br/>
            
      <table id="tableData" class="table-responsive table table-striped table-bordered" style="font-size:12px;width:100% !important">
          
          <thead>
              <tr>
                  <th>&nbsp;</th>                                                                                                  
                  <th>Name</th>                      
                  <th>Category</th>      
                  <th>Slot</th> 
                  <th>Status</th>
                  <th>Trainer</th>  
                  
              </tr>
          </thead>
          <tbody>
                        
          </tbody>
          <tfoot>
              <tr>                                                                            
                  <th>&nbsp;</th>                                                                                                  
                  <th>Name</th>                      
                  <th>Category</th>      
                  <th>Slot</th> 
                  <th>Status</th> 
                  <th>Trainer</th>  
                  
              </tr>
          </tfoot>
      </table>  


		</div>		
	</div>
	<!-- /#page-wrapper -->
  


<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
  <form id="assign_trainer_form">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Assign Trainer</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">        
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Choose Trainer:</label>
            <select name="assign_to" id="assign_to" class="form-control">
              <option value="0">Choose Trainer</option>
            </select>
            <input type="hidden" id="trainers" name="trainers">
          </div>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Slot Time:</label>
            <input type="text" class="form-control datetimepicker" readonly id="assign_slot" name="assign_slot">
            
          </div>
          <div class="form-group">
            <label for="message-text" class="col-form-label">Description:</label>
            <textarea class="form-control" id="description" name="description"></textarea>
          </div>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Assign</button>
      </div>
    </div>
    </form>
  </div>
</div>


  <script>
        
    var url = "{{url('/')}}";
    var table = '';

    jQuery(document).ready(function() {
          

      
					
          table = jQuery('#tableData').DataTable({
            'processing': true,
            'serverSide': true,                        
            'lengthMenu': [
              [10, 25, 50, -1], [10, 25, 50, "All"]
            ],
            dom: 'Bfrtip',
            buttons: [                        
            {extend:'csvHtml5',
              exportOptions: {
                columns: [0, 1]//"thead th:not(.noExport)"
              },
              className: 'btn btn-default',
                init: function(api, node, config) {
                  $(node).removeClass('dt-button')
                },
            },
            {extend: 'pdfHtml5',
              exportOptions: {
                columns: [0, 1] //"thead th:not(.noExport)"
              },
              className: 'btn btn-default',
                init: function(api, node, config) {
                  $(node).removeClass('dt-button')
                },
              customize : function(doc){
                    var colCount = new Array();
                    var length = $('#reports_show tbody tr:first-child td').length;
                    //console.log('length / number of td in report one record = '+length);
                    $('#reports_show').find('tbody tr:first-child td').each(function(){
                        if($(this).attr('colspan')){
                            for(var i=1;i<=$(this).attr('colspan');$i++){
                                colCount.push('*');
                            }
                        }else{ colCount.push(parseFloat(100 / length)+'%'); }
                    });
              }
            },
            {
            extend:'pageLength',
            className: 'btn btn-default',
                init: function(api, node, config) {
                  $(node).removeClass('dt-button')
                },
            
            }
            ],
            'sPaginationType': "simple_numbers",
            'searching': true,
            "bSort": true,
            "fnDrawCallback": function (oSettings) {
              
            },
            'fnRowCallback': function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
              if (aData["assign_to"] != 0) {
                jQuery('td', nRow).css('background-color', '#6fdc6f');
              } 
              
            },
						"initComplete": function(settings, json) {						
              //jQuery('.popoverData').popover();
					  },
            'ajax': {
              'url': '{{ url("/") }}/admin/demorequests/ajaxData',
              'headers': {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
              },
              'type': 'post',
              'data': function(d) {
                //d.statusFilter = jQuery('#statusFilter').val();
                d.category_id = jQuery('#categoryList option:selected').val();
                //d.search = jQuery("#msds-select option:selected").val();
              },
            },          

            'columns': [
              {
                  'data': 'chkbx',
                  'className': 'col-md-1',
                  'render': function(data,type,row){
                    
                    return '<input type="checkbox" class="demo_chkbox" name="demorequest[]" value="'+row.id+'">';
                  }
              },                
              {
                  'data': 'name',
                  'className': 'col-md-3',
                  'render': function(data,type,row){
                    
                    return row.username;
                  }
              },
              {
                  'data': 'category',
                  'className': 'col-md-3',
                  'render': function(data,type,row){
                    
                    return row.category_name;
                  }
              },
              {
                  'data': 'slot_time',
                  'className': 'col-md-2',
                  'render': function(data,type,row){
                    
                    return row.slot_time;
                  }
              },
              {
                  'data': 'is_completed',
                  'className': 'col-md-1',
                  'render': function(data,type,row){
                    
                    if(row.is_completed=='N'){
                      return 'New Request'
                    }else{
                      return 'Completed';
                    }
                    
                  }
              },
              {
                  'data': 'assign_to',
                  'className': 'col-md-2',
                  'render': function(data,type,row){
                    if(row.assign_to==0){
                      return 'NA';
                    }else{
                      return row.trainername;
                    }
                    
                  }
              }
            ]
          });      


          $('#categoryList').on('change',function(){
            table.draw();
          });

          

  });



  $("#assign_trainer_form").submit(function(event){
      event.preventDefault(); 
      var trainers = isChkBxChecked();
      
      if(trainers.length==0){
        jQuery.alert({
            title: 'Alert!',
            content: 'Please select a demo request checkbox!',
        });
        return false;
      }

      $('#trainers').val(trainers);

      var post_url = "{{url('/')}}/admin/users/assigntrainer"; 
      var request_method = 'POST';
      var form_data = $(this).serialize();
      
      $.ajax({
        url : post_url,
        type: request_method,
        headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        },
        data : form_data,
        success: function(res){
		  $('#exampleModal').modal('hide'); 
          table.draw();
		}
      })
  });   


  $('#exampleModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); 
    var id = button.data('id');
    $('#assign_slot').val(button.data('date'));
    $('#description').val(button.data('description'));
    $('#req_id').val(button.data('id'));
    
    $.ajax({
      method: "POST",
      headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
      },
      url: "{{url('/')}}/admin/users/getTrainerList",
      data: { id: id}
    }).done(function(res) {
        if(res.data){
          var str = '<option value="0">Choose Trainer</option>';
          $.each(res.data,function(index,value){
            var selected = '';
            if(id==value.id)
              selected='selected';
            str += '<option value="'+value.id+'" '+selected+'>'+value.name+'('+value.email+')</option>';
          });
          $('#assign_to').empty().append(str);
        }else{
          $.alert({
              title: 'Error!',
              content: res.msg,              
          });
        } 
    });
    // modal.find('.modal-title').text('New message to ' + recipient)
    // modal.find('.modal-body input').val(recipient)
  })




function deleteData(id){

  $.confirm({
      title: 'Confirm!',
      content: 'Are you sure want to delete?',
      buttons: {
          confirm: function () {
            $('#deleteRow').attr('action', url+"/admin/categories/").submit();  
          },
          cancel: function () {
              return true;
          }
      }
  });

}        



$.datetimepicker.setLocale('en');

$('.datetimepicker').datetimepicker({
  dayOfWeekStart : 1,
  lang:'en',
  format:'Y-m-d H:i',
  allowTimes:[
    '5:00', '5:30', '6:00', '6:30', '7:00', '7:30', '8:00',
      '8:30', '9:30', '10:00', '10:30', '11:00', '11:30', '12:00',
      '12:30','13:00','13:30','14:00','14:30','15:00','15:30','16:00','16:30',
      '17:00', '17:30', '18:00', '18:30', '19:00', '19:30', '20:00',
      '20:30', '21:00', '21:30', '22:00', '22:30','23:00','23:30'
  ],
  //disabledDates:['1986/01/08','1986/01/09','1986/01/10'],
  startDate:	'2010-12-08'
});

 

$('.trainer-btn-action').on('click',function(){
    var trainers = isChkBxChecked();
    console.log(trainers);
    if(trainers.length==0){
      jQuery.alert({
          title: 'Alert!',
          content: 'Please select a demo request checkbox!',
      });
      return false;
    }
});

function isChkBxChecked(){
    
    var chkbxArr = [];
    $(".demo_chkbox:checked").each(function(){
      chkbxArr.push($(this).val());
    });
    return chkbxArr;
} 

</script>
<style type="text/css">

.custom-date-style {
	background-color: red !important;
}

.input{	
}
.input-wide{
	width: 500px;
}

</style>


@endsection
