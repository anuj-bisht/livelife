<?php

namespace App\Http\Controllers\v1;

use App\Warmup;
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


class WarmupController extends Controller
{
  
      
    /**
     * Edit event method
     * @return success or error
     * 
     * */
    public function getWarmupList(Request $request){
      
      try{
        $status = 0;
        $message = "";
              
        $user  = JWTAuth::user();
        $validator = Validator::make($request->all(), [
          'category_id' => 'required'
        ]);

        if($validator->fails()){
          $error = json_decode(json_encode($validator->errors()));
          if(isset($error->category_id[0])){
            $message = $error->category_id[0];
          }
          return response()->json(["status"=>$status,"message"=>"Invalid data set","data"=>json_decode("{}")]);
        }

        $data = Warmup::getAllListByCat($request->category_id);
        
        if(!$data->count()){          
            return response()->json(['status'=>$status,'message'=>'No record found','data'=>json_decode("{}")]);                    
        }else{          
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
    public function getWarmupById(Request $request){
      
      try{
        $status = 0;
        $message = "";
              
        //$user  = JWTAuth::user();
        $validator = Validator::make($request->all(), [
          'id' => 'required'
        ]);

        
        if($validator->fails()){
          $error = json_decode(json_encode($validator->errors()));
          if(isset($error->id[0])){
            $message = $error->id[0];
          }
          return response()->json(["status"=>$status,"message"=>"Invalid data set","data"=>json_decode("{}")]);
        }

        $data = Warmup::getWarmupById($request->id);
        
        if(!$data->count()){          
            return response()->json(['status'=>$status,'message'=>'No record found','data'=>$user]);                    
        }else{          
            return response()->json(['status'=>1,'message'=>'','data'=>$data]);                    
        }   
      }catch(Exception $e){
        return response()->json(['status'=>$status,'message'=>'Error','data'=>json_decode("{}")]);                    
      }
              
    }

}