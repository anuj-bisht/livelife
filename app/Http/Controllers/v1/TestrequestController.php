<?php

namespace App\Http\Controllers\v1;

use App\Testrequest;
use App\Level;
use App\User;
use App\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Controllers\Traits\SendMail;
use App\Http\Controllers\Traits\Common;
use Config;
use App\Common\Utility;
use App\Classes\UploadFile;
use Mail;


class TestrequestController extends Controller
{
  
    use Common;  
    use SendMail;
    /**
     * Edit event method
     * @return success or error
     * 
     * */
    public function addTestRequest(Request $request){
      
      try{
        $status = 0;
        $message = "";
              
        $user  = JWTAuth::user();
        $validator = Validator::make($request->all(), [
          'level_id' => 'required',          
          'description'=>'required'
        ]);
        $params = [];  
        if($validator->fails()){
          $error = json_decode(json_encode($validator->errors()));
          if(isset($error->level_id[0])){
            $message = $error->level_id[0];
          }else if(isset($error->description[0])){
            $message = $error->description[0];
          }
          return response()->json(["status"=>$status,"message"=>$message,"data"=>json_decode("{}")]);
        }

        // $result = Testrequest::getTestRequestByUser($user->id); 

        // if($result->count()){
        //   if($result[0]->is_completed=='N'){
        //      $message = "You have already one test in progress"; 
        //      return response()->json(["status"=>1,"message"=>$message,"data"=>json_decode("{}")]);
        //   }
        // }

        $data1 = [
            'description' => $request->description,
            'user_id' => $user->id,
            'level_id' => $request->level_id            
        ];

        $level_data = Level::getLevelById($request->level_id);
        
        if(!$level_data->count()){
          return response()->json(['status'=>1,'message'=>'No level exist','data'=>json_decode("{}")]);                    
        }
        
        if(Testrequest::insert($data1)){   
              $lvl = $level_data[0];
              $data = [];
              $data['to_email'] = $user->email;              
              $data['from'] = config('app.MAIL_FROM_ADDRESS');
              $data['subject'] = 'New test request';
              $data['name'] = $user->name;
              $data['category_name'] = $lvl->category_name;              
              $data['message1'] = 'Thanks for your test request. we will assign a trainer soon';                  

              $this->SendMail($data,'testrequest');

              $suscription_title = "New test request";
              $suscription_msg = "Thanks for your test request. we will assign a trainer soon.";
              //$suscription_msg .= "Category:".$category->name." and Slot time is:".$request->slot_time;

              $notification = [];
              $notification['title'] = $suscription_title;
              $notification['message'] = $suscription_msg;
              $notification['user_id'] = $user->id;
              $notification['type'] = 'Normal';                
              Notification::saveNotification($notification); 

              $data['to_email'] = config('app.MAIL_FROM_ADDRESS'); 
              $data['from'] = $user->email;
              $data['name'] = 'Admin';
              $data['message1'] = 'New test request from a user ,'.$user->name.', please assign available trainer for him';                  
              
              $this->SendMail($data,'testrequest');

              if(isset($user->device_id)){
                $diviceIds = [];
                $diviceIds[] = $user->device_id; 
                $this->sendNotification($diviceIds,'','Test request success','Your test request successfully submitted, Our team will respond soon.');
              }

            return response()->json(['status'=>1,'message'=>'','data'=>json_decode("{}")]);                    
        }else{          
            return response()->json(['status'=>0,'message'=>'insert Error','data'=>json_decode("{}")]);                    
        }   
      }catch(Exception $e){
        return response()->json(['status'=>$status,'message'=>'Error','data'=>json_decode("{}")]);                    
      }
              
    }

    /**
     * Edit event method
     * @return success or error
     * 
     * */
    public function getTestRequestStatus(Request $request){
      
        try{
          $status = 0;
          $message = "";
                
          $user  = JWTAuth::user();
          $data = Testrequest::getTestRequestByUser($user->id);            
          //print_r($data); die;          
          if(!$data->count()){                        
              return response()->json(['status'=>$status,'message'=>'No Test request found','data'=>$user]);                    
          }else{          
              if($data[0]->is_completed=='N'){
                if($data[0]->assign_to != NULL){
                    $userData = User::getUserById($data[0]->assign_to);
                    $message = "Your Test class is assigned";
                    return response()->json(['status'=>1,'message'=>$message,'data'=>$userData]);                    
                }else{
                    $message = "Your Test class is in progress";
                    return response()->json(['status'=>1,'message'=>$message,'data'=>json_decode("{}")]);                    
                }
              }else{
                $message = "Your Test class is completed";
                return response()->json(['status'=>1,'message'=>$message,'data'=>json_decode("{}")]);                    
              }
              return response()->json(['status'=>1,'message'=>'','data'=>$data]);                    
          }   
        }catch(Exception $e){
          return response()->json(['status'=>$status,'message'=>'Error','data'=>json_decode("{}")]);                    
        }
                
      }

      /**
     * Edit event method
     * @return success or error
     * 
     * */
    public function addTimer(Request $request){
      
      try{
        $status = 0;
        $message = "";
              
        $user  = JWTAuth::user();
        $validator = Validator::make($request->all(), [
          'time' => 'required'
        ]);
        $params = [];  
        if($validator->fails()){
          $error = json_decode(json_encode($validator->errors()));
          if(isset($error->time[0])){
            $message = $error->time[0];
          }
          return response()->json(["status"=>$status,"message"=>$message,"data"=>json_decode("{}")]);
        }
        $obj = Testrequest::findOrFail($user->id);

        $obj->is_completed = 'Y';
        $obj->complete_time = $request->time;
        $obj->status = (isset($request->status)) ? $request->status : $obj->status;
        if($obj->save()){

        }

                
        if(Testrequest::insert($data)){          
            return response()->json(['status'=>1,'message'=>'','data'=>json_decode("{}")]);                    
        }else{          
            return response()->json(['status'=>0,'message'=>'insert Error','data'=>json_decode("{}")]);                    
        }   
      }catch(Exception $e){
        return response()->json(['status'=>$status,'message'=>'Error','data'=>json_decode("{}")]);                    
      }
              
    }

    public function getLeaderBoard(Request $request){
      
      try{
        $status = 0;
        $message = "";
              
        $user  = JWTAuth::user();
        if(!isset($user->id)){
          return response()->json(['status'=>$status,'message'=>'Invalid token value','data'=>json_decode("{}")]);                              
        }

        $validator = Validator::make($request->all(), [
          'category_id' => 'required'
        ]);
        $params = [];  
        if($validator->fails()){
          $error = json_decode(json_encode($validator->errors()));
          if(isset($error->category_id[0])){
            $message = $error->category_id[0];
          }
          return response()->json(["status"=>$status,"message"=>$message,"data"=>json_decode("{}")]);
        }

        $level_id = 0;
        if(isset($request->level_id)){
          $level_id = $request->level_id;
        }
        if($level_id==0){
          $obj = Testrequest::getLeaderBoardAll($request->category_id);
          // dd($obj);
          $counter = 1;
          // if($obj->count()){
            foreach($obj as $k=>$v){
              $obj[$k]->rank = $counter;
              $counter++;
            }
          // }
        }else{
          $obj = Testrequest::getLeaderBoardByLevel($level_id,$request->category_id);
        }
        
                
        if($obj){          
            return response()->json(['status'=>1,'message'=>'','data'=>$obj]);                    
        }else{          
            return response()->json(['status'=>0,'message'=>'No record found','data'=>[]]);                    
        }   
      }catch(Exception $e){
        return response()->json(['status'=>$status,'message'=>'Error','data'=>json_decode("{}")]);                    
      }
              
    }

    

}