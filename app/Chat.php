<?php

namespace App;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;
use DB;

class Chat extends Model
{
    protected $table = 'chats';


    protected $fillable = ['from_id','to_id','message'];
    //protected $hidden = ['_token'];


    public static function getChat($user_id){
		$result = DB::table('chats')
                ->select('chats.*','users.name as client_name','u1.name as admin_name')                                                                                                                                                                                                         
                ->join('users', 'users.id', '=', 'chats.from_id') 				
                ->join('users as u1', 'u1.id', '=', 'chats.to_id') 				
                ->where('chats.from_id',$user_id)   
                ->orWhere('chats.to_id',$user_id)
                ->orderBy('chats.id','asc')
                ->get();
        
        return $result;
    }
    
    public static function getUserList(){
        $result = DB::table('users')
                ->select('users.name','users.id','roles.name as role_name','chats.message',DB::raw('count(chats.read_status) as read_count'))                                                                                                                                                                                                         
                ->join('chats', 'users.id', '=', 'chats.from_id') 	
                ->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')      
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id') 			                                
                ->where('roles.name','<>','Admin')
                //->where('chats.read_status','N')
                ->groupBy('chats.from_id')   
                ->orderBy('users.id','asc')                             
                ->get();
        //dd($result);
        return $result;
    }

    public static function getUnReadChat(){
        $result = DB::table('users')
                ->select('users.name','users.id','roles.name as role_name','chats.message',DB::raw('count(chats.read_status) as read_count'))                                                                                                                                                                                                         
                ->join('chats', 'users.id', '=', 'chats.from_id') 	
                ->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')      
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id') 			                                
                ->where('roles.name','<>','Admin')
                ->where('chats.read_status','N')
                ->orderBy('users.name','asc')                
                ->get();
        //dd($result);
        return $result;
    }
    
         
}
