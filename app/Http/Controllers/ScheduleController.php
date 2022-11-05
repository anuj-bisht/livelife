<?php
    
namespace App\Http\Controllers;
    
use App\Schedule;
use App\User;
use App\Slot;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Classes\UploadFile;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Traits\Common;
use App\Http\Controllers\Traits\SendMail;
use Auth;
use App\Testrequest;
use App\Demorequest;
use App\Notification;

    
class ScheduleController extends Controller
{ 
  use SendMail;
  use Common;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
          //$this->middleware('permission:category_expence-list', ['only' => ['index','show']]);
         //$this->middleware('permission:category-expence-list|category-expence-create|category-expence-edit|category-expence-delete', ['only' => ['index','show']]);
         //$this->middleware('permission:category-expence-create', ['only' => ['create','store']]);
         //$this->middleware('permission:category-expence-edit', ['only' => ['edit','update']]);
         //$this->middleware('permission:category-expence-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
              
        $catList = Category::getUserCatList(Auth::user()->id);
        $users = User::getClientList();
        $trainers = User::getTrainerList();
        $categories = [];
        //print_r($catList); die;
        return view('schedules.index',['catList'=>$catList,
        'users'=>$users,
        'categories'=>$categories,
        'trainers'=>$trainers
        ]);

    }
    
    public function ajaxData(Request $request){
    
      $draw = (isset($request->data["draw"])) ? ($request->data["draw"]) : "1";
      $response = [
        "recordsTotal" => "",
        "recordsFiltered" => "",
        "data" => "",
        "success" => 0,
        "msg" => ""
      ];
      try {
          
          $start = (isset($request->start)) ? $request->start : 0;
          $end = ($request->length) ? $request->length : 10;
          $search = ($request->search['value']) ? $request->search['value'] : '';
          //echo 'ddd';die;
          $cond[] = [];
          
          //echo '<pre>'; print_r($users); die; categoryFilter
          $obj = Schedule::select('schedules.*','users.name as username',
          'users.email as useremail','categories.name as category_name')
          ->whereRaw('1 = 1')
          ->join('categories','categories.id','=','schedules.category_id')
          ->join('users','users.id','=','schedules.user_id');
                  
          if($request->user > 0){
            $obj = $obj->where('users.id',$request->user);
          }

          if($request->category > 0){
            $obj = $obj->where('categories.id',$request->category);
          }

          if ($request->search['value'] != "") {            
            $obj = $obj->where('schedules.title','LIKE',"%".$search."%");
            $obj = $obj->orWhere('users.name','LIKE',"%".$search."%");
            $obj = $obj->orWhere('users.email','LIKE',"%".$search."%");
            $obj = $obj->orWhere('categories.name','LIKE',"%".$search."%");
            $obj = $obj->orWhere('schedules.start','LIKE',"%".$search."%");
            $obj = $obj->orWhere('schedules.end','LIKE',"%".$search."%");
          } 
          
          if(isset($request->order[0]['column']) && $request->order[0]['column']==0){
              $sort = $request->order[0]['dir'];
              $obj = $obj->orderBy('users.name',$sort);
          }
          if(isset($request->order[0]['column']) && $request->order[0]['column']==1){
            $sort = $request->order[0]['dir'];
            $obj = $obj->orderBy('categories.name',$sort);
          }
          if(isset($request->order[0]['column']) && $request->order[0]['column']==2){
            $sort = $request->order[0]['dir'];
            $obj = $obj->orderBy('schedules.title',$sort);
          }
          if(isset($request->order[0]['column']) && $request->order[0]['column']==3){
            $sort = $request->order[0]['dir'];
            $obj = $obj->orderBy('schedules.start_date',$sort);
          }
          if(isset($request->order[0]['column']) && $request->order[0]['column']==4){
            $sort = $request->order[0]['dir'];
            $obj = $obj->orderBy('schedules.end_date',$sort);
          }


          $total = $obj->count();
          if($end==-1){
            $obj = $obj->get();
          }else{
            $obj = $obj->skip($start)->take($end)->get();
          }
          
          $response["recordsFiltered"] = $total;
          $response["recordsTotal"] = $total;
          //response["draw"] = draw;
          $response["success"] = 1;
          $response["data"] = $obj;
          
        } catch (Exception $e) {    
 
        }
      
 
      return response($response);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {        
        $userlist = User::getClientList();                      
        $categories = Category::getCatList();                      
        $trainers = User::getTrainerList();  
        return view('schedules.createTest',compact('userlist','categories','trainers'));
    }
    public function createTest()
    {        
        $userlist = User::getClientList();                      
        $categories = Category::getCatList();                      
        $trainers = User::getTrainerList();  
        return view('schedules.createTest',compact('userlist','categories','trainers'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      
        $validator = Validator::make($request->all(), [
            'category_id' => 'required',  
            'user_id' =>'required',
            'trainer_id' =>'required',         
            'start' =>'required',
            'end' =>'required',
            'type'=>'required'         
        ]);

        if($validator->fails()){
          $error = json_decode(json_encode($validator->errors()));
          if(isset($error->category_id[0])){
            $message = $error->category_id[0];
          }else if(isset($error->user_id[0])){
            $message = $error->user_id[0];
          }else if(isset($error->trainer_id[0])){
            $message = $error->trainer_id[0];
          }else if(isset($error->start[0])){
            $message = $error->start[0];
          }else if(isset($error->end[0])){
            $message = $error->end[0];
          }else if(isset($error->type[0])){
            $message = $error->type[0];
          }

          return response()->json(["status"=>0,"message"=>$message,"data"=>json_decode("{}")]);
        }

        $user_id = $request->user_id;        
        $slot_id = $request->slot_id;
        $trainer_id = $request->trainer_id;
        $category_id = $request->category_id;
        $stype = $request->type;
        $start = $request->start;
        $end = $request->end;

        $days = floor((strtotime($end) - strtotime($start)) / (60 * 60 * 24));
        

        // $obj = new Schedule();
        // $obj->user_id = $request->user_id;
        // $obj->category_id = $request->category_id;
        // $obj->trainer_id = $request->trainer_id;
        // $obj->start = $request->start;
        // $obj->end = $request->end;
        
        if($this->scheduleCustom($user_id,$stype,$slot_id,$trainer_id,$category_id,$days,$start)){
          $userdata = User::getUserById($user_id);

          if(isset($userdata->id)){
            $data = [];
            $data['to_email'] = $userdata->email;      
            $data['from'] = config('app.MAIL_FROM_ADDRESS');                  
            $data['subject'] = 'Class Scheduled';                
            $data['message1'] = 'Your class is scheduled by admin, please have a look on mobile app';
            $data['name'] = $userdata->name;
            
            $this->SendMail($data,'schedule_reminder'); 

            $suscription_title = "Class Scheduled";
            $suscription_msg = "Your class is scheduled by admin, please have a look on mobile app.";
            
            $notification = [];
            $notification['title'] = $suscription_title;
            $notification['message'] = $suscription_msg;
            $notification['user_id'] = $userdata->id;
            $notification['type'] = 'Normal';                
            Notification::saveNotification($notification);

          }
                         
          return response()->json(["status"=>1,"message"=>'Schedule added successfully',"data"=>json_decode("{}")]);
        }else{
          return response()->json(["status"=>1,"message"=>'Error on adding',"data"=>json_decode("{}")]);
        }
    }
    

     /**
     * Display the specified resource.
     *
     * @param  \App\category_expence  $category_expence
     * @return \Illuminate\Http\Response
     */
    public function show(Schedule $schedule)
    {
        return view('schedules.show',compact('schedule'));
    }
    

        
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\category_expence  $category_expence
     * @return \Illuminate\Http\Response
     */
    public function edit(Schedule $schedule)
    {      
        
        //echo '<pre>';print_r($plan->name); die;
        return view('schedules.edit',compact('schedule'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\category_expence  $category_expence
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Schedule $schedule)
    {
        
        $schedule = Schedule::findOrFail($request->id);
         request()->validate([
            'name' => 'required|unique:schedules,title,'.$schedule->id
        ]);

                
        $schedule->name = $request->name;        
        
        $schedule->update();
    
        return redirect()->route('schedules.index')
                        ->with('success','Schedule updated successfully');
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CategoryExpence  $CategoryExpence
     * @return \Illuminate\Http\Response
     */
    public function destroy(Schedule $schedule)
    {
        $schedule->delete();
    
        return redirect()->route('schedules.index')
                        ->with('success','Schedule deleted successfully');
    }

    public function getschedule(Request $request)
    {
        try{
          
          $start = $request->start;
          $end = $request->end;          
          $user_id = Auth::user()->id;

          $result = Schedule::where(DB::raw('DATE(start)'),'>=',$start)
          ->where(DB::raw('DATE(end)'),'<=',$end)
          ->where('user_id',$user_id)
          ->get();
          return response()->json($result);        
        }catch(Exception $e){
         //$e->abort()         
       }  
    }


    public function addevent(Request $request){
      try{
          
          $start_date = $request->start_date;
          $end_date = $request->end_date;
          $title = $request->title;

          $user_id = Auth::user()->id;

          $start_hour = $request->start_hour;
          $start_minute = $request->start_minute;
          
          $end_hour = $request->end_hour;
          $end_minute = $request->end_minute;
          
          $second = '00';

          $actual_start_time = $start_hour.":".$start_minute.":".$second;
          $actual_end_time = $end_hour.":".$end_minute.":".$second;
          
          $date = $start_date;     
          
          $category_id = isset($request->catList) ? $request->catList: 1;
          
          while($date < $end_date){            
            
            $data = [
              'title' => $title,
              'user_id' => $user_id,
              'category_id' =>$category_id,
              'start' => $date.' '.$actual_start_time,
              'end' => $date.' '.$actual_end_time
            ];
            Schedule::insert($data);
            
            $date = date('Y-m-d', strtotime("+1 day", strtotime($date)));
          }
          
          return response()->json(['status'=>'1','message'=>'','data'=>[]]);        
        }catch(Exception $e){
        //$e->abort()         
        }
    }

    public function drop(Request $request){
      try{
          
          $start_date = $request->start;
          $end_date = $request->end;
          $title = $request->title;

          $user_id = Auth::user()->id;
            
          $data = [
            'title' => $title,
            'user_id' => $user_id,
            'start' => $start_date,
            'end' => $end_date
          ];
          Schedule::insert($data);
          
          
          return response()->json(['status'=>'1','message'=>'','data'=>[]]);        
        }catch(Exception $e){
        //$e->abort()         
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CategoryExpence  $CategoryExpence
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
      try{
          
            $id = $request->id;
            
            $user_id = Auth::user()->id;

            $result = Schedule::where('id',$id)->where('user_id',$user_id)->delete();
            return response()->json(['status'=>1,'message'=>'','data'=>[]]);        
          }catch(Exception $e){
          //$e->abort()         
      }
    }
	
	function getTrainerScheduleOnDate(Request $request){
		$date = $request->date;
		$strDate = substr($date,4,11);
		$date = date('Y-m-d', strtotime($strDate));
     $roles = User::getUserRole(Auth::user()->id);
    if($roles->name == 'Trainer'){
		$schedules = Schedule::getTrainerScheduleDateWise(Auth::user()->id,$date);
		$test_requests = Testrequest::getTrainerTestRequestDateWise(Auth::user()->id,$date);

		$demo_requests = Demorequest::getTrainerDemoRequestDateWise(Auth::user()->id,$date);
  }else{
    $schedules = Schedule::getClientScheduleDateWise(Auth::user()->id,$date);
    $test_requests = Testrequest::getClientTestRequestDateWise(Auth::user()->id,$date);
    $demo_requests = Demorequest::getClientDemoRequestDateWise(Auth::user()->id,$date);
  }
  
		$html = view('schedules.trainer_datewise', compact('schedules','test_requests','demo_requests'))->render();
		echo $html;
		die;
		
	}
	
	function getTrainerScheduleOfMonth(Request $request){
		$date = date('Y-m-d');
		$like_date = mb_substr($date, 0, 8);
		$sc_array = [];
		$schedules = Schedule::getTrainerScheduleOfMonth(Auth::user()->id,$date,$like_date);
		foreach($schedules as $k=>$schedule){
			$sc_array[] = array("title"=>"Event".($k+1),"start"=>$schedule);
			//dd($schedule);
		}
		dd($sc_array);
		
	}
	
	function filterTrainerJobs(Request $request){
		$type = $request->type;
		$filter_type_job = $request->filter_type_job;
      //$data = Schedule::getTrainerScheduleToday(Auth::user()->id,$type,0);
    $roles = User::getUserRole(Auth::user()->id);
    if($roles->name == 'Trainer'){
  		if($filter_type_job == 'schedule')
      $data = Schedule::getTrainerSlot(Auth::user()->id,$type);
  		else if($filter_type_job == 'test')
  			$data = Testrequest::getTrainerTestRequest(Auth::user()->id,$type);
  		else if($filter_type_job == 'demo')
  			$data = Demorequest::getTrainerDemoRequest(Auth::user()->id,$type);
  		else
  			$data = array();
  }else{
        if($filter_type_job == 'schedule')
      $data = Schedule::getClientSlot(Auth::user()->id,$type);
      else if($filter_type_job == 'test')
        $data = Testrequest::getClientTestRequest(Auth::user()->id,$type);
      else if($filter_type_job == 'demo')
        $data = Demorequest::getClientDemoRequest(Auth::user()->id,$type);
      else
        $data = array();
  }

		
			
		$html = view('schedules.filter_tainer_job', compact('data','filter_type_job','type'))->render();
		echo $html;
		die;
  }
  
  public function reschedule(Request $request) {
    try{    
      $result = Schedule::getRescheduleRequest(false);
      return view('schedules.reschedule',compact('result'));       
    }catch(Exception $e){
      //$e->abort()         
    }  
  }

  public function rescheduleAjax(Request $request){
    $draw = (isset($request->data["draw"])) ? ($request->data["draw"]) : "1";
      $response = [
        "recordsTotal" => "",
        "recordsFiltered" => "",
        "data" => "",
        "success" => 0,
        "msg" => ""
      ];
      try {
          
          $start = (isset($request->start)) ? $request->start : 0;
          $end = ($request->length) ? $request->length : 10;
          $search = ($request->search['value']) ? $request->search['value'] : '';
          //echo 'ddd';die;
          $cond[] = [];
          
          //echo '<pre>'; print_r($users); die; categoryFilter
          $obj = Schedule::getRescheduleRequest(false,true);
                  
          
          $total = $obj->count();
          if($end==-1){
            $obj = $obj->get();
          }else{
            $obj = $obj->skip($start)->take($end)->get();
          }
          
          $response["recordsFiltered"] = $total;
          $response["recordsTotal"] = $total;
          //response["draw"] = draw;
          $response["success"] = 1;
          $response["data"] = $obj;
          
        } catch (Exception $e) {    
 
        }
      
 
      return response($response);
  }

  public function getTrainerAndSlot(Request $request){
    try{    

      if(!isset($request->id)){
        return response()->json(['status'=>0,'message'=>'No record found','data'=>[]]); 
      }
      $curSchedule = Schedule::getScheduleById($request->id);
      $slots = Slot::getSelect(false);
      $trainers = User::getTrainerList();
      $result = ['slots'=>$slots,'trainers'=>$trainers,'schedule'=>$curSchedule];
      return response()->json(['status'=>1,'message'=>'','data'=>$result]);          
    }catch(Exception $e){
      //$e->abort()         
    }  
  }

  public function reschedulesubmit(Request $request){
    try{
      $status = 0;
      $message = "";
      if(!isset($request->date_start) && !isset($request->trainers) && !isset($request->slot_id) && !isset($request->schedule_id)){
        return response()->json(['status'=>$status,'message'=>'Invalid input data','data'=>[]]);   
      }

      $result = Schedule::where('id', $request->schedule_id)->update(
        [
          'trainer_id' => $request->trainers,
          'start'=>$request->date_start,
          'slot_id' => $request->slot_id,
          'reschedule_approved'=>'Y'
        ]
      ); 

      $scheduleData = Schedule::getScheduleById($request->schedule_id);
      if(isset($scheduleData->id)){
        $slotData = Slot::getSlotBySlotId($request->slot_id);
        $diviceIds = [$scheduleData->device_id];
        $this->sendNotification($diviceIds,'','Training rescheduled','Your reschedule request is approved');
        
        $data = [];
        $data['to_email'] = config('app.from_email');      
        $data['from'] = config('app.MAIL_FROM_ADDRESS');                  
        $data['subject'] = 'New Reschedule request';                
        $data['message1'] = 'Reschedule request approved by admin';
        $data['name'] = 'Admin';
        
        $data['category_name'] = $scheduleData->category_name;
        $data['start_time'] = $request->date_start.' '.$slotData->start_time;
        $data['name1'] = $scheduleData->username;
        $data['username'] = $scheduleData->username;
        $data['email'] = $scheduleData->user_email;                
        $this->SendMail($data,'reschedule'); 

        $suscription_title = "New Reschedule request";
        $suscription_msg = "Reschedule request approved by admin.";
        
        $notification = [];
        $notification['title'] = $suscription_title;
        $notification['message'] = $suscription_msg;
        $notification['user_id'] = $scheduleData->user_id;
        $notification['type'] = 'Normal';                
        Notification::saveNotification($notification);

        $data['name'] = $scheduleData->username;
        if(isset($scheduleData->trainer_email)){
          $data['bcc'] = $scheduleData->trainer_email;
        }
          
        $data['message1'] = 'Reschedule request approved, Kindly check mobile app.';
        $data['to_email'] = $scheduleData->user_email;      
        $this->SendMail($data,'reschedule'); 
      }

      echo '1'; die;
      
    }catch(Exception $e){
      return response()->json(['status'=>$status,'message'=>'User update Error','data'=>json_decode("{}")]);                    
    }
  }


  public function changeTrainer(Request $request){
    try{
      $status = 0;
      $message = "";
      if(!isset($request->assign_to) && !isset($request->trainers)){
        return response()->json(['status'=>$status,'message'=>'Invalid input data','data'=>[]]);   
      }
      $schedule_ids = explode(",",$request->trainers);
      $result = Schedule::whereIn('id', $schedule_ids)->update(
        [
          'trainer_id' => $request->assign_to,          
        ]
      ); 

      $scheduleData = Schedule::whereIn('id',$schedule_ids)->get();
      if($scheduleData->count() > 0){
        
                
        if(isset($scheduleData[0]->device_id)){
          $diviceIds = [$scheduleData[0]->device_id];
          $this->sendNotification($diviceIds,'','Trainer change notification','Your trainer has been changed by admin, Please look on your app for more details');        
        }
        
        $data = [];
        $data['to_email'] = config('app.from_email');      
        $data['from'] = config('app.MAIL_FROM_ADDRESS');                  
        $data['subject'] = 'Trainer Change';                
        $data['message1'] = 'Trainer has been successfully changed by you.';
        $data['name'] = 'Admin';
                              
        $this->SendMail($data,'trainer_change'); 

        $suscription_title = "Trainer Change";
        $suscription_msg = "Trainer has been successfully changed by you.";
        
        $notification = [];
        $notification['title'] = $suscription_title;
        $notification['message'] = $suscription_msg;
        $notification['user_id'] = Auth::user()->id;
        $notification['type'] = 'Normal';                
        Notification::saveNotification($notification);

        $trainerdata = User::getUserById($request->assign_to);
        if(isset($trainerdata->id)){
          $data = [];
          $data['to_email'] = $trainerdata->email;      
          $data['from'] = config('app.MAIL_FROM_ADDRESS');                  
          $data['subject'] = 'Client assigned';                
          $data['message1'] = 'New client has been assigned to you, please login to see the details';
          $data['name'] = $trainerdata->name;
                                
          $this->SendMail($data,'trainerassign'); 

          $suscription_title = "Client Change";
          $suscription_msg = "New client has been assigned to you, please login to see the details.";
          
          $notification = [];
          $notification['title'] = $suscription_title;
          $notification['message'] = $suscription_msg;
          $notification['user_id'] = $trainerdata->id;
          $notification['type'] = 'Normal';                
          Notification::saveNotification($notification);

        }


        $userData = User::getUserById($scheduleData[0]->user_id);
        if(isset($userData->id)){
          $data = [];
          $data['to_email'] = $userData->email;      
          $data['from'] = config('app.MAIL_FROM_ADDRESS');                  
          $data['subject'] = 'New Trainer assigned';                
          $data['message1'] = 'New trainer has been assigned to you, please login to app and see the details';
          $data['name'] = $userData->name;
                                
          $this->SendMail($data,'trainerassign'); 

          $suscription_title = "New Trainer Assigned";
          $suscription_msg = "New trainer has been assigned to you, please login to app and see the details.";
          
          $notification = [];
          $notification['title'] = $suscription_title;
          $notification['message'] = $suscription_msg;
          $notification['user_id'] = $userData->user_id;
          $notification['type'] = 'Normal';                
          Notification::saveNotification($notification);

        }

        return response()->json(['status'=>1,'message'=>'Trainer changed sucssfully','data'=>json_decode("{}")]);                    
       
      }else{
        return response()->json(['status'=>$status,'message'=>'No Schedule found','data'=>json_decode("{}")]);                    
      }      
      
    }catch(Exception $e){
      return response()->json(['status'=>$status,'message'=>'User update Error','data'=>json_decode("{}")]);                    
    }
  }
  public function getUsersBySlot($id)
	{
		$users = Schedule::getUsersSlot(Auth::user()->id,'today',$id);
		if($users)
		{
			return response()->json(['status'=>1,'message'=>'Success','data'=>$users]);

		}
		else{
			return response()->json(['status'=>0,'message'=>'Data Not Found','data'=>""]);


		}
		// dd($users);
	}
    
    
}
