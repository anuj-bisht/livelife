<?php

namespace App;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;
use DB;

class Schedule extends Model
{
    protected $table = 'schedules';
    protected $fillable = ['title','start','end','user_id','category_id'];
    //protected $hidden = ['_token'];

    public static function getTrainerScheduleByCategory($params){
        $result = DB::table('schedules')
                ->select('schedules.*','categories.name as category_name',
                'categories.id as category_id')                                                                            
                ->join('categories', 'categories.id', '=', 'schedules.category_id')                                                         
                ->where('schedules.category_id',$params['category_id'])                
                //->where('schedules.start',date('Y-m-d'))                
                ->orderBy('schedules.start')
                ->get();
    
        return $result;
    }

    public static function getScheduleByCategory($params){
        $result = DB::table('schedules')
                ->select('schedules.*','categories.name as category_name',
                'categories.id as category_id')                                                                            
                ->join('categories', 'categories.id', '=', 'schedules.category_id')                                                         
                ->where('schedules.category_id',$params['category_id'])                
                //->where('schedules.start',date('Y-m-d'))                
                ->orderBy('schedules.start')
                ->get();
    
        return $result;
    }

    public static function getScheduleById($id){
        $result = DB::table('schedules')
                ->select('schedules.*','categories.name as category_name',
                'categories.id as category_id','users.device_id',
                'users.name as username','users.email as user_email','u1.email as trainer_email')                                                                            
                ->join('categories', 'categories.id', '=', 'schedules.category_id')                                                         
                ->join('users', 'users.id', '=', 'schedules.user_id')   
                ->leftJoin('users AS u1', 'u1.id', '=', 'schedules.trainer_id')                                                       
                ->where('schedules.id',$id)                
                ->first();
    
        return $result;
    }

    

    public static function getAvailabeSchedule($params){
        $result = DB::table('schedules')
                ->select('schedules.*','categories.name as category_name',
                'categories.id as category_id','users.name as username')                                                                            
                ->join('categories', 'categories.id', '=', 'schedules.category_id')                                                         
                ->join('categories', 'categories.id', '=', 'schedules.category_id')                                                         
                ->join('users', 'users.id', '=', 'schedules.user_id')                                                                         
                ->where(DB::raw('TIME(schedules.start)'),$params['time'])                
                ->orderBy('schedules.start')
                ->first();
    
        return $result;
    }

    public static function getMyScheduleToday($user_id,$category_id){
        $dt = date('Y-m-d');
        $nw = date('Y-m-d H:i:s');
        $result = DB::table('schedules')
                ->select('schedules.*','categories.name as category_name',
                'categories.id as category_id','users.name as username',
                'u1.name as trainer_name','u1.image as trainer_image',
                'u1.image_front as trainer_image_front'
                ,'u1.image_back as trainer_image_back','u1.image_side as trainer_image_side',
                'u1.room_name','slots.start_time','slots.end_time')                                                                            
                ->join('categories', 'categories.id', '=', 'schedules.category_id')                                                                                                                              
                ->join('slots', 'slots.id', '=', 'schedules.slot_id')                                                                                                                              
                ->join('users', 'users.id', '=', 'schedules.user_id')                                                                         
                ->leftJoin('users AS u1', 'u1.id', '=', 'schedules.trainer_id')                                   
                ->where(DB::raw('DATE(schedules.start)'),date('Y-m-d'))
                ->where(DB::raw('TIME_TO_SEC(now())'),'<',DB::raw('TIME_TO_SEC(CONCAT('.$dt.'," ",slots.end_time))'));
                if($category_id != 0){
                    $result = $result->where('categories.id',$category_id);                 
                }   
                $result = $result->where('schedules.user_id',$user_id)   
                ->orderBy('schedules.start')
                ->get();
    
        return $result;
    }

    public static function getTrainerSlot($user_id,$type){
        $result = DB::table('schedules')
                  ->select('schedules.slot_id','slots.start_time','slots.end_time',
                    'schedules.start as schedule_date','u1.name as trainer_name','u1.room_name',
                    DB::raw('count(*) as count'))
                  ->leftJoin('slots','slots.id', '=', 'schedules.slot_id')
                  ->leftJoin('users AS u1', 'u1.id', '=', 'schedules.trainer_id') ;
        if($type == 'today')
            $result = $result->where(DB::raw('DATE(schedules.start)'),date('Y-m-d'));
        else
            $result = $result->where(DB::raw('DATE(schedules.start)'),'>=',date('Y-m-d'));
        $result = $result->where('schedules.trainer_id','<>',0)             
                ->where('schedules.trainer_id',$user_id) 
                ->groupBy('schedules.slot_id')
                ->groupBy(DB::raw('DATE(schedules.start)'))  
                ->orderBy('schedules.start')
                ->get();
        return $result;

    }               
	public static function getUsersSlot($user_id,$type,$slot_id){
        $result = DB::table('schedules')
                  ->select('u1.name')
                  ->leftJoin('users AS u1', 'u1.id', '=', 'schedules.user_id') ;
        if($type == 'today')
            $result = $result->where(DB::raw('DATE(schedules.start)'),date('Y-m-d'))->where('schedules.slot_id',$slot_id)->get();
        // else
        //     $result = $result->where(DB::raw('DATE(schedules.start)'),'>=',date('Y-m-d'));
        // $result = $result->where('schedules.trainer_id','<>',0)             
        //         ->where('schedules.trainer_id',$user_id) 
        //         ->groupBy('schedules.slot_id')
        //         ->groupBy(DB::raw('DATE(schedules.start)'))  
        //         ->orderBy('schedules.start')
        //         ->get();
        return $result;

    }
	public static function getTrainerScheduleToday($user_id,$type,$category_id){
        $result = DB::table('schedules')
                ->select('schedules.*','categories.name as category_name',
                'categories.id as category_id','users.name as username',
                'u1.name as trainer_name','u1.room_name','u1.image as trainer_image','u1.image_front as trainer_image_front'
                ,'u1.image_back as trainer_image_back','u1.image_side as trainer_image_side','rooms.name as room_name')                                                                            
                ->join('categories', 'categories.id', '=', 'schedules.category_id')                                                                                                                              
                ->join('users', 'users.id', '=', 'schedules.user_id')                                                                         
                ->leftJoin('users AS u1', 'u1.id', '=', 'schedules.trainer_id')   
                ->leftJoin('trainer_rooms', 'trainer_rooms.user_id', '=', 'schedules.trainer_id')   
                ->leftJoin('rooms', 'trainer_rooms.room_id', '=', 'rooms.id');
					if($type == 'today')
						$result = $result->where(DB::raw('DATE(schedules.start)'),date('Y-m-d'));
					else
						$result = $result->where(DB::raw('DATE(schedules.start)'),'>=',date('Y-m-d'));
                
                if($category_id != 0){
                    $result = $result->where('categories.id',$category_id);                 
                }   
                $result = $result->where('schedules.trainer_id','<>',0)             
                ->where('schedules.trainer_id',$user_id)   
                ->orderBy('schedules.start')
                ->get();
    
        return $result;
    }
	
	public static function getTrainerScheduleDateWise($user_id,$date){
        $result = DB::table('schedules')
                  ->select('schedules.slot_id','slots.start_time','slots.end_time',
                    'schedules.start as schedule_date',DB::raw('count(*) as count'))
                  ->leftJoin('slots','slots.id', '=', 'schedules.slot_id')
                ->where(DB::raw('DATE(schedules.start)'),$date)           
                ->where('schedules.trainer_id',$user_id) 
                ->groupBy('schedules.slot_id')
                ->groupBy(DB::raw('DATE(schedules.start)'))  
                ->orderBy('schedules.start')
                ->get();
        return $result;
    }
	
	public static function getTrainerScheduleOfMonth($user_id,$date,$like_date){
        $result = DB::table('schedules')                                                                           
                ->where(DB::raw('DATE(schedules.start)'),'>=',$date)  
                ->where('schedules.trainer_id','<>',0)
				->where(DB::raw('DATE(schedules.start)'), 'like', '%'.$like_date.'%')				
                ->where('schedules.trainer_id',$user_id)   
                ->orderBy('schedules.start')
                ->pluck(DB::raw('distinct(DATE(schedules.start)) as start_date'));
    
        return $result;
    }

    public static function getClientSlot($user_id,$type){
        $result = DB::table('schedules')
                  ->select('schedules.slot_id','slots.start_time','slots.end_time',
                    'schedules.start as schedule_date','u1.room_name',DB::raw('count(*) as count'))
                  ->leftJoin('slots','slots.id', '=', 'schedules.slot_id')
                  ->leftJoin('users AS u1', 'u1.id', '=', 'schedules.trainer_id') ;
        if($type == 'today')
            $result = $result->where(DB::raw('DATE(schedules.start)'),date('Y-m-d'));
        else
            $result = $result->where(DB::raw('DATE(schedules.start)'),'>=',date('Y-m-d'));
        $result = $result->where('schedules.user_id','<>',0)             
                ->where('schedules.user_id',$user_id) 
                ->groupBy('schedules.slot_id')
                ->groupBy(DB::raw('DATE(schedules.start)'))  
                ->orderBy('schedules.start')
                ->get();
        return $result;

    }               
    
    public static function getClientScheduleToday($user_id,$type,$category_id){
        $result = DB::table('schedules')
                ->select('schedules.*','categories.name as category_name',
                'categories.id as category_id','users.name as username',
                'u1.name as trainer_name','u1.room_name','u1.image as trainer_image','u1.image_front as trainer_image_front'
                ,'u1.image_back as trainer_image_back','u1.image_side as trainer_image_side','rooms.name as room_name')                                                                            
                ->join('categories', 'categories.id', '=', 'schedules.category_id')                                                                                                                              
                ->join('users', 'users.id', '=', 'schedules.user_id')                                                                         
                ->leftJoin('users AS u1', 'u1.id', '=', 'schedules.trainer_id')   
                ->leftJoin('trainer_rooms', 'trainer_rooms.user_id', '=', 'schedules.trainer_id')   
                ->leftJoin('rooms', 'trainer_rooms.room_id', '=', 'rooms.id');
                    if($type == 'today')
                        $result = $result->where(DB::raw('DATE(schedules.start)'),date('Y-m-d'));
                    else
                        $result = $result->where(DB::raw('DATE(schedules.start)'),'>=',date('Y-m-d'));
                
                if($category_id != 0){
                    $result = $result->where('categories.id',$category_id);                 
                }   
                $result = $result->where('schedules.user_id','<>',0)             
                ->where('schedules.user_id',$user_id)   
                ->orderBy('schedules.start')
                ->get();
    
        return $result;
    }
    
    public static function getClientScheduleDateWise($user_id,$date){
        $result = DB::table('schedules')
                  ->select('schedules.slot_id','slots.start_time','slots.end_time',
                    'schedules.start as schedule_date',DB::raw('count(*) as count'))
                  ->leftJoin('slots','slots.id', '=', 'schedules.slot_id')
                ->where(DB::raw('DATE(schedules.start)'),$date)           
                ->where('schedules.user_id',$user_id) 
                ->groupBy('schedules.slot_id')
                ->groupBy(DB::raw('DATE(schedules.start)'))  
                ->orderBy('schedules.start')
                ->get();
        return $result;
    }
    
    public static function getClientScheduleOfMonth($user_id,$date,$like_date){
        $result = DB::table('schedules')                                                                           
                ->where(DB::raw('DATE(schedules.start)'),'>=',$date)  
                ->where('schedules.user_id','<>',0)
                ->where(DB::raw('DATE(schedules.start)'), 'like', '%'.$like_date.'%')              
                ->where('schedules.user_id',$user_id)   
                ->orderBy('schedules.start')
                ->pluck(DB::raw('distinct(DATE(schedules.start)) as start_date'));
    
        return $result;
    }

    public static function getRescheduleRequest($count=true,$withoutGet = false){
        $result = DB::table('schedules')         
                ->select('schedules.*','categories.name as category_name',
                'u1.name as trainer_name','slots.start_time','slots.end_time',
                DB::raw('DATE_FORMAT(schedules.start, "%Y-%m-%d") as sstart'),
                'users.name as username')                                                                  
                ->join('slots','slots.id','=','schedules.slot_id')
                ->join('users','users.id','=','schedules.user_id')
                ->join('categories','categories.id','=','schedules.category_id')
                ->leftJoin('users as u1','u1.id','=','schedules.trainer_id')
                ->where('schedules.reschedule_status','Y')                  
                ->where('schedules.reschedule_approved','N');                  
        $result = $result->orderBy('schedules.start');

        if($count){
            $result = $result->count();
        }else if($withoutGet){
            //$result = $result->get();
        }else {
            $result = $result->get();
        }
                
    
        return $result;
    }

    public static function getAllSchedule($user_id,$withHistroy = 1){
        $result = DB::table('schedules')         
                ->select('schedules.*','categories.name as category_name',
                'u1.name as trainer_name','slots.name as slot_name','slots.start_time','slots.end_time',
                'users.name as username')                                                                  
                ->join('slots','slots.id','=','schedules.slot_id')
                ->join('users','users.id','=','schedules.user_id')
                ->join('categories','categories.id','=','schedules.category_id')
                ->leftJoin('users as u1','u1.id','=','schedules.trainer_id')
                ->where('schedules.user_id',$user_id);

                if($withHistroy){
                    $result = $result->where(DB::raw('DATE(schedules.start)'),'>=',date('Y-m-d'));
                }
                

        $result = $result->orderBy('schedules.start');        
        $result = $result->get();
        return $result;
    }

    public static function checkScheduleByUserId($user_id,$room_name,$sche_type='schedule',$category_id){

        $start_time = date('Y-m-d H:i:s');
        $end_time =  date('Y-m-d H:i:s',strtotime('+50 minutes'));
        $start = date('Y-m-d');
        
        //echo $user_id; echo $room_name; die;
        if($sche_type=="test"){
            $result = DB::table('testrequests')         
                ->select('testrequests.*',
                'u1.name as trainer_name','users.name as username',
                'u1.room_name as room_name')                                                                                  
                ->join('users','users.id','=','testrequests.user_id')                
                ->join('users as u1','u1.id','=','testrequests.assign_to')
                ->join('levels','levels.id','=','testrequests.level_id')                
                ->join('categories','categories.id','=','levels.category_id')                
                ->where('testrequests.user_id',$user_id)
                ->where('testrequests.status','N')
                ->where('testrequests.is_completed','A')
                ->where('u1.room_name',$room_name)
                ->where('levels.category_id',$category_id)
                ->where(DB::raw('DATE(testrequests.assign_slot)'),date('Y-m-d'))
                ->where('testrequests.assign_slot','<=',$start_time)
                ->where(DB::raw('DATE_ADD(testrequests.assign_slot,INTERVAL 45 MINUTE)'),'>=',$start_time);
                $result = $result->first();
               
                return $result;    
        }else if($sche_type=="demo"){

            $result = DB::table('demorequests')         
                ->select('demorequests.*',
                'u1.name as trainer_name','users.name as username',
                'u1.room_name as room_name')                                                                                  
                ->join('users','users.id','=','demorequests.user_id')                
                ->join('users as u1','u1.id','=','demorequests.assign_to')
                ->where('demorequests.is_completed','N')
                ->where('demorequests.user_id',$user_id)
                ->where('demorequests.category_id',$category_id)
                ->where('u1.room_name',$room_name)
                ->where(DB::raw('DATE(demorequests.slot_time)'),date('Y-m-d'))
                ->where('demorequests.slot_time','<=',$start_time)
                ->where(DB::raw('DATE_ADD(demorequests.slot_time,INTERVAL 45 MINUTE)'),'>=',$start_time);
                $result = $result->first();
                return $result;    
        }else{
            $result = DB::table('schedules')         
                ->select('schedules.*','categories.name as category_name',
                'u1.name as trainer_name','slots.start_time','slots.end_time',
                'users.name as username','u1.room_name as room_name')                                                                  
                ->join('slots','slots.id','=','schedules.slot_id')
                ->join('users','users.id','=','schedules.user_id')
                ->join('categories','categories.id','=','schedules.category_id')
                ->join('users as u1','u1.id','=','schedules.trainer_id')
                ->where('schedules.user_id',$user_id)
                ->where('schedules.category_id',$category_id)
                ->where('u1.room_name',$room_name)
                ->where(DB::raw('DATE(schedules.start)'),date('Y-m-d'))
                ->where('schedules.start','<=',$start_time)
                ->where('schedules.end','>=',$end_time);
                //->whereBetween('schedules.start',[$start_time, $end_time]);

                $result = $result->first();
                //print_r($result); die;
                
                return $result;
        }
        
    }

    public static function getReminderByMinute($minute=15,$type='schedule'){

        $date1 = date('Y-m-d H:i:s',strtotime('+'.$minute.' minutes',strtotime(date('Y-m-d H:i:s')))); 
        if($type=='schedule'){
            $result = DB::table('schedules')         
                ->select('schedules.*','users.id as userid','categories.name as category_name',
                'u1.name as trainer_name','u1.email as trainer_email','slots.name as slot_name','slots.start_time','slots.end_time',
                'users.name as username','users.email as user_email','users.device_id')                                                                  
                ->join('slots','slots.id','=','schedules.slot_id')
                ->join('users','users.id','=','schedules.user_id')
                ->join('categories','categories.id','=','schedules.category_id')
                ->leftJoin('users as u1','u1.id','=','schedules.trainer_id')
                ->where('schedules.trainer_id','<>',0)
                ->where('schedules.start','<=',$date1)
                ->where('schedules.start','>=',date('Y-m-d H:i:s'));                                

            $result = $result->orderBy('schedules.start');     

        }else if($type=='test'){
            $result = DB::table('testrequests')         
                ->select('testrequests.*','users.id as userid','levels.name as level_name',
                'u1.name as trainer_name','u1.email as trainer_email',
                'users.name as username','users.email as user_email','users.device_id')                                                                  
                ->join('levels','levels.id','=','testrequests.level_id')
                ->join('users','users.id','=','testrequests.user_id')                
                ->leftJoin('users as u1','u1.id','=','testrequests.assign_to')
                ->where('testrequests.assign_to','<>',0)
                ->where('testrequests.assign_slot','<=',$date1)
                ->where('testrequests.assign_slot','>=',date('Y-m-d H:i:s'));  
        }else{
            $result = DB::table('demorequests')         
                ->select('demorequests.*','users.id as userid','categories.name as category_name',
                'u1.name as trainer_name','u1.email as trainer_email',
                'users.name as username','users.email as user_email','users.device_id')                                                                                  
                ->join('users','users.id','=','demorequests.user_id')
                ->join('categories','categories.id','=','demorequests.category_id')
                ->leftJoin('users as u1','u1.id','=','demorequests.assign_to')
                ->where('demorequests.assign_to','<>',0)
                ->where('demorequests.slot_time','<=',$date1)
                ->where('demorequests.slot_time','>=',date('Y-m-d H:i:s'));  
        }
        
        $result = $result->get();
        return $result;
    }    

    public static function checkRescheduleRequest($user_id){

        $date1 = date('Y-m-d');
        $date_old =  date( "Y-m-d", strtotime( $date1 . "-30 day"));

        $result = DB::table('schedules')
                ->where('reschedule_status','Y')
                ->where('user_id',$user_id);
        $result = $result->where(DB::raw('DATE(start)'),'>=',$date_old);
        
        $result = $result->get();
        
        return $result;

    }
            
}
