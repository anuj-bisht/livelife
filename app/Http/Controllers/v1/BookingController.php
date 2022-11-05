<?php

namespace App\Http\Controllers\v1;

use App\Booking;
use App\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Controllers\Traits\SendMail;
use Config;
use App\Common\Utility;
use App\Classes\UploadFile;
use Mail;


class BookingController extends Controller
{  
      
    /**
     * Edit event method
     * @return success or error
     * 
     * */
    public function book(Request $request){
      
      try{
        $status = 0;
        $message = "";
        $user  = JWTAuth::user();

        if(!isset($user->id)){
          return response()->json(["status"=>$status,"message"=>'User does not exist',"data"=>json_decode("{}")]);
        }
        $validator = Validator::make($request->all(), [
          'slot_id' => 'required',          
          'start_date' => 'required',    
          'category_id'=>'required'                          
        ]);

        $params = [];  
        if($validator->fails()){
          $error = json_decode(json_encode($validator->errors()));
          if(isset($error->slot_id[0])){
            $message = $error->slot_id[0];
          }else if(isset($error->start_date[0])){
            $message = $error->start_date[0];
          }
          return response()->json(["status"=>$status,"message"=>$message,"data"=>json_decode("{}")]);
        }

        $subsData = Subscription::getSubsByUser($user->id);
        if($subsData->count()==0){
          return response()->json(["status"=>$status,"message"=>'No subscription exist',"data"=>json_decode("{}")]);          
        }

        $catData = Booking::getBookingBySlotId($request->slot_id);
        if(isset($catData->id)){
          return response()->json(["status"=>$status,"message"=>'You have already subscribed',"data"=>json_decode("{}")]);                    
        }

        $obj = new Booking();       
        $obj->slot_id = $request->slot_id; 
        $obj->start_date = $request->start_date;
        $obj->user_id = $subsData[0]->user_id;
        $obj->subscription_id = $subsData[0]->id;
        if($obj->save()){
          return response()->json(['status'=>1,'message'=>'Your request has been saved','data'=>json_decode("{}")]);                      
        }else{
          return response()->json(['status'=>$status,'message'=>'Err','data'=>json_decode("{}")]);                      
        }
        // list($date,$time) = explode(" ",$request->start_time);
        // $startTime = strtotime($date); 
        // $endTime = strtotime('+'.$planData->days.' day',$startTime);  //strtotime('2010-05-10'); 
        // while($startTime <= $endTime){
        //   $day = date('D',$startTime);
        //   $startTime = strtotime('+1 day',$startTime); 
        // }        
        // $params = ['time'=>$request->time];
        // $result = Schedule::getAvailabeSchedule($params);       

      }catch(Exception $e){
        return response()->json(['status'=>$status,'message'=>'Error','data'=>json_decode("{}")]);                    
      }
              
    }

    

}