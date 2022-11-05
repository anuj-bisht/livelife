<?php

namespace App\Http\Controllers\v1;

use App\Level;
use App\LevelData;
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


class LevelController extends Controller
{
  
      
    /**
     * Edit event method
     * @return success or error
     * 
     * */
    public function getLevelList(Request $request){
      
      try{
        $status = 0;
        $message = "";
              
        //$user  = JWTAuth::user();
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

        $data = Level::getLevelByCategoryId($request->category_id);
        
        if(!$data->count()){          
            return response()->json(['status'=>$status,'message'=>'No record found','data'=>$user]);                    
        }else{          
            return response()->json(['status'=>1,'message'=>'','data'=>$data]);                    
        }   
      }catch(Exception $e){
        return response()->json(['status'=>$status,'message'=>'Error','data'=>json_decode("{}")]);                    
      }
              
    }

    public function getLevelListByUser(Request $request){
      
      try{
        $status = 0;
        $message = "";
              
        $user  = JWTAuth::user();
        //echo $user->id; die;
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

        $catData = Level::getLevelByCategoryId($request->category_id);
        
        $data = Level::getLevelListByUser($request->category_id,$user->id);

        //print_r($data); die;
        $levelArr = [];
        $c = 0;
        $idArr = [];

        if($data){
          foreach($data as $k=>$v){
            foreach($catData as $k1=>$v1){          
              if($v->id == $v1->id){
                $catData[$k]->status = 'pass';
              }  
            }    
          }
        }
                
        return response()->json(['status'=>1,'message'=>'','data'=>$catData]);  

      }catch(Exception $e){
        return response()->json(['status'=>$status,'message'=>'Error','data'=>json_decode("{}")]);                    
      }
              
    }

    /**
     * Edit event method
     * @return success or error
     * 
     * */
    public function getLevelById(Request $request){
      
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

        $data = Level::getLevelById($request->id);
        
        if(!$data->count()){          
            return response()->json(['status'=>$status,'message'=>'No record found','data'=>$user]);                    
        }else{          
            return response()->json(['status'=>1,'message'=>'','data'=>$data]);                    
        }   
      }catch(Exception $e){
        return response()->json(['status'=>$status,'message'=>'Error','data'=>json_decode("{}")]);                    
      }
              
    }

    public function getLevelData(Request $request){
      
      try{
        $status = 0;
        $message = "";
              
        //$user  = JWTAuth::user();
        $validator = Validator::make($request->all(), [
          'level_id' => 'required',
          'type' => 'required'
        ]);

        
        if($validator->fails()){
          $error = json_decode(json_encode($validator->errors()));
          if(isset($error->level_id[0])){
            $message = $error->level_id[0];
          }else if(isset($error->type[0])){
            $message = $error->type[0];  
          }
          return response()->json(["status"=>$status,"message"=>"Invalid data set","data"=>json_decode("{}")]);
        }

        $data = LevelData::getLevelData($request->level_id,$request->type);
        
        if(!$data->count()){          
            return response()->json(['status'=>$status,'message'=>'No record found','data'=>json_decode("{}")]);                    
        }else{          
            return response()->json(['status'=>1,'message'=>'','data'=>$data]);                    
        }   
      }catch(Exception $e){
        return response()->json(['status'=>$status,'message'=>'Error','data'=>json_decode("{}")]);                    
      }
              
    }

    

}