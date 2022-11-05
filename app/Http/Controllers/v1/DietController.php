<?php

namespace App\Http\Controllers\v1;

use App\Diet;
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


class DietController extends Controller
{
  
      
    /**
     * Edit event method
     * @return success or error
     * 
     * */
    public function getDietList(Request $request){
      
      try{
        $status = 0;
        $message = "";
              
        //$user  = JWTAuth::user();
        $data = Diet::getAllDiet();
        
        if(!$data->count()){          
            return response()->json(['status'=>$status,'message'=>'No record found','data'=>$user]);                    
        }else{          
            return response()->json(['status'=>1,'message'=>'','data'=>$data]);                    
        }   
      }catch(Exception $e){
        return response()->json(['status'=>$status,'message'=>'User update Error','data'=>json_decode("{}")]);                    
      }
              
    }


    /**
     * Edit event method
     * @return success or error
     * 
     * */
    public function getDietByUser(Request $request){
      
      try{
        $status = 0;
        $message = "";
              
        $user  = JWTAuth::user();
        if(!isset($user->id)){
          return response()->json(['status'=>$status,'message'=>'User not found','data'=>json_decode("{}")]);                    
        }

        $data = Diet::getDietByUser($user->id);
        
        if(!$data->count()){          
            return response()->json(['status'=>$status,'message'=>'No record found','data'=>json_decode("{}")]);                    
        }else{          
            return response()->json(['status'=>1,'message'=>'','data'=>$data]);                    
        }   
      }catch(Exception $e){
        return response()->json(['status'=>$status,'message'=>'User update Error','data'=>json_decode("{}")]);                    
      }
              
    }

}