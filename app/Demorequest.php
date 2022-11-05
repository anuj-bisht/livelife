<?php

namespace App;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;
use DB;

class Demorequest extends Model
{
    protected $table = 'demorequests';
    protected $fillable = ['user_id','category_id','description','assign_to','is_completed','slot_time'];
    //protected $hidden = ['_token'];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
         
    public static function getDemoRequestByUser($user_id){
        return self::with(['user'])->get();
    }

    public static function getNewDemoRequest(){
        return self::where('is_completed','N')        
        ->where('assign_to',0)->get();
    }

    public static function getDemoRequest($count){
        $result = DB::table('demorequests')
                ->select('demorequests.*',
                'users.name as username',
                'categories.name as category_name',
                'demorequests.assign_to as trainer_assigned',
                'u1.name as trainername',
                'u1.room_name'
                )                                                        
                ->join('categories', 'categories.id', '=', 'demorequests.category_id')                                                         
                ->join('users', 'users.id', '=', 'demorequests.user_id')                                                         
                ->leftJoin('users as u1', 'u1.id', '=', 'demorequests.assign_to')                                                         
                ->where('demorequests.is_completed','<>','Y');
                
        if($count=='count'){
            $result = $result->count();           
        }
        return $result;
    }
	
	public static function getTrainerDemoRequest($trainer_id,$type){
        $result = DB::table('demorequests')
                ->select('demorequests.*',
                'users.name as username',
                'categories.name as category_name',
                'demorequests.assign_to as trainer_assigned','u1.room_name'
                )                                                        
                ->join('categories', 'categories.id', '=', 'demorequests.category_id')                                                         
                ->join('users', 'users.id', '=', 'demorequests.user_id')                                                         
                ->leftJoin('users as u1', 'u1.id', '=', 'demorequests.assign_to')                                                         
                ->where('demorequests.is_completed','<>','Y')
				->where('demorequests.assign_to',$trainer_id);
				if($type == 'today')
					$result = $result->where(DB::raw('DATE(demorequests.slot_time)'),date('Y-m-d'));
				else
					$result = $result->where(DB::raw('DATE(demorequests.slot_time)'),'>=',date('Y-m-d'));
		$result = $result->get();
        return $result;
    }
	
	public static function getTrainerDemoRequestDateWise($trainer_id,$date){
        $result = DB::table('demorequests')
                ->select('demorequests.*',
                'users.name as username',
                'categories.name as category_name',
                'demorequests.assign_to as trainer_assigned','u1.room_name'
                )                                                        
                ->join('categories', 'categories.id', '=', 'demorequests.category_id')                                                         
                ->join('users', 'users.id', '=', 'demorequests.user_id')                                                         
                ->leftJoin('users as u1', 'u1.id', '=', 'demorequests.assign_to')                                                         
                ->where('demorequests.is_completed','<>','Y')
				->where('demorequests.assign_to',$trainer_id)
				->where(DB::raw('DATE(demorequests.slot_time)'),$date);
		$result = $result->get();
        return $result;
    }
	
	public static function getTrainerDemoRequestOfMonth($user_id,$date,$like_date){
		$result = DB::table('demorequests')                                                                           
                ->where(DB::raw('DATE(demorequests.slot_time)'),'>=',$date)  
                ->where('demorequests.assign_to','<>',0)
				->where(DB::raw('DATE(demorequests.slot_time)'), 'like', '%'.$like_date.'%')				
                ->where('demorequests.assign_to',$user_id)   
                ->orderBy('demorequests.slot_time')
                ->pluck(DB::raw('distinct(DATE(demorequests.slot_time)) as start_date'));
    
        return $result;
	}

    public static function getClientDemoRequest($trainer_id,$type){
        $result = DB::table('demorequests')
                ->select('demorequests.*',
                'users.name as username',
                'categories.name as category_name',
                'demorequests.assign_to as trainer_assigned','u1.room_name'
                )                                                        
                ->join('categories', 'categories.id', '=', 'demorequests.category_id')                                                         
                ->join('users', 'users.id', '=', 'demorequests.user_id')                                                         
                ->leftJoin('users as u1', 'u1.id', '=', 'demorequests.assign_to')                                                         
                ->where('demorequests.is_completed','<>','Y')
                ->where('demorequests.user_id',$trainer_id);
                if($type == 'today')
                    $result = $result->where(DB::raw('DATE(demorequests.slot_time)'),date('Y-m-d'));
                else
                    $result = $result->where(DB::raw('DATE(demorequests.slot_time)'),'>=',date('Y-m-d'));
        $result = $result->get();
        return $result;
    }
    
    public static function getClientDemoRequestDateWise($user_id,$category_id,$date){
        $result = DB::table('demorequests')
                ->select('demorequests.*',
                'users.name as username',
                'categories.name as category_name',
                'demorequests.assign_to as trainer_assigned','u1.room_name','u1.name as trainername'
                )                                                        
                ->join('categories', 'categories.id', '=', 'demorequests.category_id')                                                         
                ->join('users', 'users.id', '=', 'demorequests.user_id')                                                         
                ->leftJoin('users as u1', 'u1.id', '=', 'demorequests.assign_to')                                                         
                ->where('demorequests.is_completed','<>','Y');
                if($category_id != 0){
                    $result = $result->where('demorequests.category_id',$category_id);                 
                }
                
                $result = $result->where('demorequests.user_id',$user_id)
                ->where(DB::raw('DATE(demorequests.slot_time)'),$date);
        $result = $result->get();
        return $result;
    }
    
    public static function getClientDemoRequestOfMonth($user_id,$date,$like_date){
        $result = DB::table('demorequests')                                                                           
                ->where(DB::raw('DATE(demorequests.slot_time)'),'>=',$date)  
                ->where('demorequests.assign_to','<>',0)
                ->where(DB::raw('DATE(demorequests.slot_time)'), 'like', '%'.$like_date.'%')                
                ->where('demorequests.user_id',$user_id)   
                ->orderBy('demorequests.slot_time')
                ->pluck(DB::raw('distinct(DATE(demorequests.slot_time)) as start_date'));
    
        return $result;
    }

    
}
