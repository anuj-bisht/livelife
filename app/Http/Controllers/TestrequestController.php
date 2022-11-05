<?php
    
namespace App\Http\Controllers;
    
use App\Testrequest;
use App\Notification;
use App\Level;
use App\User;
use Illuminate\Http\Request;
use App\Classes\UploadFile;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Traits\Common;
use App\Http\Controllers\Traits\SendMail;
use DB;

    
class TestrequestController extends Controller
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
              
        return view('testrequests.index',[]);

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
            $obj = Testrequest::getTestRequest('full');
            $obj = $obj->orderBy('testrequests.id','desc');
                    
            
            if ($request->search['value'] != "") {            
              $obj = $obj->where('users.name','LIKE',"%".$search."%");
            } 
            
            if(isset($request->order[0]['column']) && $request->order[0]['column']==0){
                $sort = $request->order[0]['dir'];
                //$obj = $obj->orderBy('users.name',$sort);
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

      public function assigntrainer(Request $request){
        
        $status = 0;
        $message = "";

        try{
            $validator = Validator::make($request->all(), [
                'assign_to' => 'required',
                'trainers' =>'required'
            ]);                
            if($validator->fails()){
                $error = json_decode(json_encode($validator->errors()));
                if(isset($error->trainers[0])){
                    $message = $error->trainers[0];
                }else if(isset($error->assign_slot[0])){
                    $message = $error->assign_slot[0];
                }    
                    
                return response()->json(["status"=>$status,"message"=>$message,"data"=>json_decode("{}")]);
            }
            
            if(isset($request->assign_to)){
                
                $ids = explode(",",$request->trainers); 
                $result = Testrequest::whereIn('id', $ids)->update(
                   [
                     'assign_to' => $request->assign_to,
                     'assign_slot'=>$request->assign_slot,
                     'is_completed'=>'A'
                   ]
                ); 
                if($result){
                    
                    $userlist = DB::table('testrequests')->select('testrequests.*','users.email','users.device_id')
                    ->whereIn('testrequests.id',$ids)
                    ->join('users','users.id','=','testrequests.user_id')
                    ->get();
                    $trainer = User::where('id',$request->assign_to)->first();
                    //dd($userlist); die;
                    $diviceIds = [];
                    $emails = [];
                    $userids = [];
                    if($userlist){
                        foreach($userlist as $key=>$value){
                            if($value->device_id){
                              $diviceIds[] = $value->device_id;
                            }                                
                            $emails[] = $value->email;
                            $userids[] = $value->user_id;
                        }
                    }
                    
                    
                    $data['to_email'] = $trainer->email;
                    $data['from'] = config('app.MAIL_FROM_ADDRESS');         ;
                    $data['bcc'] = $emails;         ;
                    $data['subject'] = 'Test request trainer assigned';
                    $data['name'] = $trainer->name;
                    $data['message1'] = 'Test request is scheduled on '.$request->assign_slot;                             
                    $this->SendMail($data,'testrequest_assign');
                    $Tnotification = [];
                    $Tnotification['title'] = "Test request assigned to you";
                    $Tnotification['message'] = "New trainees are assigned for tarinning.";
                    $Tnotification['user_id'] = $request->assign_to;
                    $Tnotification['type'] = 'Normal';                
                    Notification::saveNotification($Tnotification);


                    $suscription_title = "Test request trainer assigned";
                    $suscription_msg = 'Test request is scheduled on '.$request->assign_slot;
                    
                    if(count($userids)>0){
                      foreach($userids as $v){
                        $notification = [];
                        $notification['title'] = $suscription_title;
                        $notification['message'] = $suscription_msg;
                        $notification['user_id'] = $v;
                        $notification['type'] = 'Normal';                
                        Notification::saveNotification($notification);
                      }
                    }
                    

                    
                    if(count($diviceIds)){
                        $this->sendNotification($diviceIds,'','Test Request update','Your Test request is updated successfully');
                    }
                    
                    return response()->json(['status'=>1,'message'=>'','data'=>json_decode("{}")]);                    
                }else{
                    return response()->json(['status'=>$status,'message'=>'Unable to insert','data'=>json_decode("{}")]);                        
                }
                
            }  

            
        }catch(Exception $e){			
            return response()->json(['status'=>$status,'message'=>$message,'data'=>json_decode("{}")]);    
        } 
    }

    public function testtimer(Request $request){
        $status = 0;
        $message = "";
        
        try{
                                   
            $userlist = DB::table('testrequests')
            ->select('testrequests.*','users.device_id','users.name as username','users.email as user_email')            
            ->join('users','users.id','=','testrequests.user_id')            
            ->where('testrequests.id',$request->uid)
            ->first();
            


            $diviceIds = [];
            $userids = [];
            if(isset($userlist->device_id)){
                $diviceIds[] = $userlist->device_id;
                
            }            
            
            $testResult = 'Fail';
            
            $lvldata = Level::findOrFail($userlist->level_id);
            if(!isset($lvldata->id)){
              return response()->json(['status'=>0,'message'=>'Level does not exist','data'=>json_decode("{}")]);    
            }

            $res = $this->convertToMilisecond($request->timervalue);
            $duration = 180;
            $duration = ($lvldata->duration*60);                    
            $milisecond = (($duration*1000) - $res);
            $timex = $this->convertToTime($milisecond); 

            if($request->teststatus=='P'){                
                                
                $dataArr['user_id'] = $userlist->user_id;
                $dataArr['level_id'] = $userlist->level_id;
                $dataArr['category_id'] = $lvldata->category_id;
                
                if($this->updateLevel($dataArr)){
                  $this->goToNextLevel($dataArr);
                }                                    
                $testResult = 'Pass';                                           
            }

            $result = Testrequest::where('id', $request->uid)->update(
                [
                  'complete_time' => $timex,
                  'is_completed'=>'Y',
                  'status' => $request->teststatus,
                  'comment' => $request->comment
                ]
            ); 


            $resultData = ["result"=>$testResult,'time'=>$timex];

            $data = [];
            $data['to_email'] = $userlist->user_email;      
            $data['from'] = config('app.MAIL_FROM_ADDRESS');                  
            $data['subject'] = 'Test Completed';                                            
            $data['message1'] = 'Your LiveFit test request has been completed, Below are the details';
            $data['name'] = $userlist->username;              
            $data['result'] = $testResult;              

            $this->SendMail($data,'testresult'); 

            $suscription_title = "Test Completed";
            $suscription_msg = "Your LiveFit test request has been completed, Below are the details.";
            
            $notification = [];
            $notification['title'] = $suscription_title;
            $notification['message'] = $suscription_msg;
            $notification['user_id'] = $userlist->user_id;
            $notification['type'] = 'Normal';                
            Notification::saveNotification($notification);

            //dd($diviceIds);
            if(count($diviceIds)){
                $this->sendNotification($diviceIds,'','result','Your Test is completed successfully and your test result is: '.$testResult,$resultData);
            }
            return response()->json(['status'=>1,'message'=>'Timer is added','data'=>json_decode("{}")]);    
                                        
        }catch(Exception $e){			
            return response()->json(['status'=>$status,'message'=>$message,'data'=>json_decode("{}")]);    
        } 
    }

    public function sendNotificationAjax(Request $request){

        try{
  
          $validator = Validator::make($request->all(), [
            'uid' => 'required',
            'type'=>'required',
            'timerstr'=>'required'
          ]);
          
          if($validator->fails()){
            $error = json_decode(json_encode($validator->errors()));
            if(isset($error->time[0])){
              $message = $error->time[0];
            }else if(isset($error->type[0])){
              $message = $error->type[0];
            }else if(isset($error->timerstr[0])){
              $message = $error->timerstr[0];
            }
            return response()->json(["status"=>0,"message"=>$message,"data"=>json_decode("{}")]);
          }
  
          
          $userlist = DB::table('testrequests')
            ->select('testrequests.*','users.device_id','levels.id as level_id','levels.duration as level_duration')            
            ->join('users','users.id','=','testrequests.user_id')            
            ->join('levels','levels.id','=','testrequests.level_id')            
            ->where('testrequests.id',$request->uid)
            ->first();
            
          if(isset($userlist->id)){
            $duration = ($userlist->level_duration*60);
            if($request->type=="start"){
              $m  = "start";
              $data = ["time"=>$duration];
            }else{
              $m = "stop";
              
              $res = $this->convertToMilisecond($request->timerstr);
              $milisecond = (($duration*1000) - $res);
              $timex = $this->convertToTime($milisecond); // 16:28:43.045460
              $data = ["time"=>$timex];
            }
            
            if(isset($userlist->device_id)){
               $diviceIds = [$userlist->device_id];
               $this->sendNotification($diviceIds,'',$m,'Your test is just '.$m,$data);
            }
            return response()->json(['status'=>1,'message'=>'Success','data'=>json_decode("{}")]);                    
          }  
          
          return response()->json(['status'=>$status,'message'=>'Error','data'=>json_decode("{}")]);                    

        }catch(Exception $e){
          return response()->json(['status'=>$status,'message'=>'Error','data'=>json_decode("{}")]);                    
        }
      }

    
}
