<?php

namespace App\Http\Controllers\v1;

use App\Demorequest;
use App\Category;
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
use Razorpay\Api\Api;




class DemorequestController extends Controller
{
    use SendMail;
    use Common;
      
    /**
     * Edit event method
     * @return success or error
     * 
     * */
    public function addDemoRequest(Request $request){
      
      try{
        $status = 0;
        $message = "";
              
        $user  = JWTAuth::user();
        $validator = Validator::make($request->all(), [
          'category_id' => 'required',          
          'description'=>'required',
          'slot_time'=>'required'
        ]);
        $params = [];  
        if($validator->fails()){
          $error = json_decode(json_encode($validator->errors()));
          if(isset($error->category_id[0])){
            $message = $error->category_id[0];
          }else if(isset($error->description[0])){
            $message = $error->description[0];
          }else if(isset($error->slot_time[0])){
            $message = $error->slot_time[0];
          }

          return response()->json(["status"=>$status,"message"=>$message,"data"=>json_decode("{}")]);
        }

        $data = [
            'description' => $request->description,
            'user_id' => $user->id,
            'category_id' => $request->category_id,
            'slot_time' => $request->slot_time
          ];
        
        if(Demorequest::insert($data)){     
            $category = Category::getCategoryById($request->category_id);
            if(isset($category->id)){
              
              $data['to_email'] = $user->email;
              $data['from'] = config('app.MAIL_FROM_ADDRESS');
              $data['subject'] = 'New demo request';
              $data['name'] = $user->name;
              $data['category_name'] = $category->name;
              $data['slot_time'] = $request->slot_time;
              $data['message1'] = 'New demo request from below details';    

              $suscription_title = "New demo request";
              $suscription_msg = "New demo request from below details";
              $suscription_msg .= "Category:".$category->name." and Slot time is:".$request->slot_time;

              $notification = [];
              $notification['title'] = $suscription_title;
              $notification['message'] = $suscription_msg;
              $notification['user_id'] = $user->id;
              $notification['type'] = 'Normal';                
              Notification::saveNotification($notification);              

              $this->SendMail($data,'demorequest');

              $data['to_email'] = config('app.MAIL_FROM_ADDRESS');
              $data['from'] = $user->email;
              $data['message1'] = 'New demo request from a user, please assign available trainer for him';                  

              $this->SendMail($data,'demorequest');

              if(isset($user->device_id)){
                $diviceIds = [];
                $diviceIds[] = $user->device_id; 
                $this->sendNotification($diviceIds,'','Demo request success','Your demo request successfully submitted, Our team will respond soon.');
              }
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
    public function getDemoRequestStatus(Request $request){
      
        try{
          $status = 0;
          $message = "";
                
          $user  = JWTAuth::user();
          $data = Demorequest::getDemoRequestByUser($user->id);            
          //print_r($data); die;          
          if(!$data->count()){                        
              return response()->json(['status'=>$status,'message'=>'No demo request found','data'=>$user]);                    
          }else{          
              if($data[0]->is_completed=='N'){
                if($data[0]->assign_to != 0){
                    $userData = User::getUserById($data[0]->assign_to);
                    $message = "Your demo class is assigned";
                    return response()->json(['status'=>1,'message'=>$message,'data'=>$userData]);                    
                }else{
                    $message = "Your demo class is in progress";
                    return response()->json(['status'=>1,'message'=>$message,'data'=>json_decode("{}")]);                    
                }
              }else{
                $message = "Your demo class is completed";
                return response()->json(['status'=>1,'message'=>$message,'data'=>json_decode("{}")]);                    
              }
              return response()->json(['status'=>1,'message'=>'','data'=>$data]);                    
          }   
        }catch(Exception $e){
          return response()->json(['status'=>$status,'message'=>'Error','data'=>json_decode("{}")]);                    
        }
                
      }
      
}