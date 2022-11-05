<?php

namespace App\Http\Controllers\v1;

use App\Banner;
use App\Gdiet;
use App\Generic;
use App\Tip;
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


class GenericController extends Controller
{
  
      
    /**
     * Edit event method
     * @return success or error
     * 
     * */
    public function getGenericData(Request $request){
      
      try{
        $status = 0;
        $message = "";
              
        
        if(isset($request->type) && $request->type=="banner"){
          $data = Banner::where('id','<>',0)->get();
        }else if(isset($request->type) && $request->type=="tip"){
          $data = Tip::where('default','1')->first();
        }else{
          $data1 = Generic::where('gtype','General')->get();
          $data2 = Gdiet::where('id','<>',0)->get();
          $data3 = Generic::where('gtype','Workout')->get();
          $data = ['generic'=>$data1,'diet'=>$data2,'generic_workout'=>$data3];
        }
        
        return response()->json(['status'=>1,'message'=>'','data'=>$data]);                    
           
      }catch(Exception $e){
        return response()->json(['status'=>$status,'message'=>'Error','data'=>json_decode("{}")]);                    
      }
              
    }

    

}