<?php

namespace App\Http\Controllers\v1;

use App\Slot;
use App\Batch;
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


class SlotController extends Controller
{  
      
    /**
     * Edit event method
     * @return success or error
     * 
     * */
    public function getSlot(Request $request){
      
      try{
        $status = 0;
        $message = "";
        $user  = JWTAuth::user();
        
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

        $result = Slot::getSlotById($request->category_id);

        if($result->count()){
          return response()->json(['status'=>1,'message'=>'','data'=>$result]);                        
        }else{
          return response()->json(['status'=>$status,'message'=>'No record exist','data'=>json_decode("{}")]);                      
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
    public function getBatch(Request $request){
      
      try{
        $status = 0;
        $message = "";
        
        if(!isset($request->batch_id)){

          $result = Batch::getSelectData();
          if($result->count()){
            return response()->json(['status'=>1,'message'=>'','data'=>$result]);                        
          }else{
            return response()->json(['status'=>$status,'message'=>'No record exist','data'=>json_decode("{}")]);                      
          } 

        }
        
        $validator = Validator::make($request->all(), [
          'batch_id' => 'required'
        ]);
        $params = [];  
        if($validator->fails()){
          $error = json_decode(json_encode($validator->errors()));
          if(isset($error->batch_id[0])){
            $message = $error->batch_id[0];
          }

          return response()->json(["status"=>$status,"message"=>$message,"data"=>json_decode("{}")]);
        }

        $result = Batch::getDataById($request->batch_id);

        if(isset($result->id)){
          return response()->json(['status'=>1,'message'=>'','data'=>$result]);                        
        }else{
          return response()->json(['status'=>$status,'message'=>'No record exist','data'=>json_decode("{}")]);                      
        }      
      }catch(Exception $e){
        return response()->json(['status'=>$status,'message'=>'Error','data'=>json_decode("{}")]);                    
      }
              
    }

    public function getSlotByBatch(Request $request){
      
      try{
        $status = 0;
        $message = "";
        $user  = JWTAuth::user();
        
        $validator = Validator::make($request->all(), [
          'batch_id' => 'required'
        ]);
        $params = [];  
        if($validator->fails()){
          $error = json_decode(json_encode($validator->errors()));
          if(isset($error->batch_id[0])){
            $message = $error->batch_id[0];
          }

          return response()->json(["status"=>$status,"message"=>$message,"data"=>json_decode("{}")]);
        }

        $result = Slot::getSlotByBatch($request->batch_id);

        if($result->count()){
          return response()->json(['status'=>1,'message'=>'','data'=>$result]);                        
        }else{
          return response()->json(['status'=>$status,'message'=>'No record exist','data'=>json_decode("{}")]);                      
        }      
      }catch(Exception $e){
        return response()->json(['status'=>$status,'message'=>'Error','data'=>json_decode("{}")]);                    
      }
              
    }

    

}