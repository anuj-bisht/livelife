<?php

namespace App;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;
use DB;

class Testrequest extends Model
{
    protected $table = 'testrequests';
    protected $fillable = ['user_id','level_id','description','assign_to',
    'is_completed','complete_time','comment','status',
    'assign_slot'];
    //protected $hidden = ['_token'];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function level(){
        return $this->belongsTo(Level::class,'level_id');
    }
         
    public static function getTestRequestByUser($user_id){
        return self::with(['user'])->get();
    }

    public static function getNewTestRequest(){
        return self::join('users','users.id','=','testrequests.user_id')->where('testrequests.is_completed','N')
        ->where('testrequests.status','N')
        ->where('testrequests.assign_to',0)->get();
    }

    public static function getTestRequest($count){
        $result = DB::table('testrequests')
                ->select('testrequests.*',
                'users.name as username',
                'levels.name as level_name',
                'u1.name as trainername'
                )                                                        
                ->join('levels', 'levels.id', '=', 'testrequests.level_id')                                                         
                ->join('users', 'users.id', '=', 'testrequests.user_id')                                                         
                ->leftJoin('users as u1', 'u1.id', '=', 'testrequests.assign_to');
                
        if($count=='count'){
            $result = $result->count();           
        }else if($count=='full'){
            $result = $result;
        }else{
            $result = $result->get();
        }
        return $result;
    }
	
	public static function getTrainerTestRequest($trainer_id,$type){
        $result = DB::table('testrequests')
                ->select('testrequests.*',
                'users.name as username',
                'levels.name as level_name',
                'u1.name as trainer_name','u1.room_name'
                
                )                                                        
                                                                     
                ->join('users', 'users.id', '=', 'testrequests.user_id') 
				->join('levels', 'levels.id', '=', 'users.level') 
                ->leftJoin('users AS u1', 'u1.id', '=', 'testrequests.assign_to')   				
                
                ->where('testrequests.is_completed','A')
                ->where('testrequests.assign_to',$trainer_id);
				if($type == 'today')
					$result = $result->where(DB::raw('DATE(testrequests.assign_slot)'),date('Y-m-d'));
				else
					$result = $result->where(DB::raw('DATE(testrequests.assign_slot)'),'>=',date('Y-m-d'));
				
		 $result = $result->get();
                
        return $result;
    }
	
	public static function getTrainerTestRequestDateWise($trainer_id,$date){
        $result = DB::table('testrequests')
                ->select('testrequests.*',
                'users.name as username',
                'levels.name as level_name',
                'u1.name as trainer_name','u1.room_name'
                
                )                                                        
                                                                      
                ->join('users', 'users.id', '=', 'testrequests.user_id')   
				->join('levels', 'levels.id', '=', 'users.level') 
                ->leftJoin('users AS u1', 'u1.id', '=', 'testrequests.assign_to') 
                
                ->where('testrequests.is_completed','A')
                ->where('testrequests.assign_to',$trainer_id)
				->where(DB::raw('DATE(testrequests.assign_slot)'),$date);
				
		 $result = $result->get();
                
        return $result;
    }
	
	public static function getTrainerTestRequestOfMonth($user_id,$date,$like_date){
		$result = DB::table('testrequests')                                                                           
                ->where(DB::raw('DATE(testrequests.assign_slot)'),'>=',$date)  
                ->where('testrequests.assign_to','<>',0)
				->where(DB::raw('DATE(testrequests.assign_slot)'), 'like', '%'.$like_date.'%')				
                ->where('testrequests.assign_to',$user_id)   
                ->orderBy('testrequests.assign_slot')
                ->pluck(DB::raw('distinct(DATE(testrequests.assign_slot)) as start_date'));
    
        return $result;
	}

    public static function getClientTestRequest($client_id,$type){
        $result = DB::table('testrequests')
                ->select('testrequests.*',
                'users.name as username',
                'levels.name as level_name',
                'u1.name as trainer_name','u1.room_name'
                
                )                                                        
                                                                     
                ->join('users', 'users.id', '=', 'testrequests.user_id') 
                ->join('levels', 'levels.id', '=', 'testrequests.level_id') 
                ->leftJoin('users AS u1', 'u1.id', '=', 'testrequests.assign_to')                   
                
                ->where('testrequests.is_completed','<>','Y')
                ->where('testrequests.user_id',$client_id);
                if($type == 'today')
                    $result = $result->where(DB::raw('DATE(testrequests.assign_slot)'),date('Y-m-d'));
                else
                    $result = $result->where(DB::raw('DATE(testrequests.assign_slot)'),'>=',date('Y-m-d'));
                
         $result = $result->get();
                
        return $result;
    }
    
    public static function getClientTestRequestDateWise($client_id,$category_id,$date){
        $result = DB::table('testrequests')
                ->select('testrequests.*',
                'users.name as username',
                'levels.name as level_name',
                'u1.name as trainer_name','u1.room_name',
                'u1.id as trainer_id','categories.name as cat_name','categories.id as cat_id'                
                )                                                        
                                                                      
                ->join('users', 'users.id', '=', 'testrequests.user_id')   
                ->join('levels', 'levels.id', '=', 'testrequests.level_id') 
                ->join('categories', 'categories.id', '=', 'levels.category_id') 
                ->leftJoin('users AS u1', 'u1.id', '=', 'testrequests.assign_to'); 
                if($category_id != 0){
                    $result = $result->where('levels.category_id',$category_id);                 
                }
                //echo $client_id; die;
                $result = $result->where('testrequests.user_id',$client_id)
                ->where(DB::raw('DATE(testrequests.assign_slot)'),$date);
                
         $result = $result->get();
                
        return $result;
    }
    
    public static function getClientTestRequestOfMonth($user_id,$date,$like_date){
        $result = DB::table('testrequests')                                                                           
                ->where(DB::raw('DATE(testrequests.assign_slot)'),'>=',$date)  
                ->where('testrequests.user_id','<>',0)
                ->where(DB::raw('DATE(testrequests.assign_slot)'), 'like', '%'.$like_date.'%')              
                ->where('testrequests.user_id',$user_id)   
                ->orderBy('testrequests.assign_slot')
                ->pluck(DB::raw('distinct(DATE(testrequests.assign_slot)) as start_date'));
    
        return $result;
    }

    public static function getLeaderBoardAll($category_id){
        $leveldata = DB::table('levels')->select('id')->where('category_id',$category_id)->get()->toArray();
        $levels = array_column($leveldata,'id');
        $arr = [];
        foreach($levels as $levelid)
        {
            // $data = DB::table('testrequests')->select('testrequests.*')
            //         ->where('level_id',$levelid)
            //         ->where('is_completed','Y')
            //         ->orderBy('complete_time','asc')->groupBy('user_id')->get();
            //         print_r($data[0]);
            $result = DB::table('testrequests')               
                ->select('testrequests.*','categories.name as category_name','levels.name as level_name',
                'users.name as username','users.email as useremail',
                DB::raw('AVG(0+testrequests.complete_time) as avg_time_to_complete'),
                'users.image as userimage','users.image_front','users.image_back','users.image_back'
                )    
                ->join('users', 'users.id', '=', 'testrequests.user_id')   
                ->join('levels', 'levels.id', '=', 'testrequests.level_id')                
                ->join('categories', 'levels.category_id', '=', 'categories.id'); 

                $result = $result->where('testrequests.is_completed','Y')   
                ->where('testrequests.status','P')   
                ->where('testrequests.level_id',$levelid)   
                // ->where('levels.category_id',$category_id)
                ->where('testrequests.complete_time','<>','0');
                
                $result = $result->groupBy('testrequests.user_id');
                
                $result = $result->orderBy('avg_time_to_complete','asc')->get();
                array_push($arr,$result[0]);

        }
        return $arr;
        
        //DB::raw('FIND_IN_SET(complete_time,(SELECT GROUP_CONCAT(complete_time ORDER BY complete_time asc ) FROM testrequests )) AS rank')
        $result = DB::table('testrequests')               
                ->select('testrequests.*','categories.name as category_name','levels.name as level_name',
                'users.name as username','users.email as useremail',
                DB::raw('AVG(0+testrequests.complete_time) as avg_time_to_complete'),
                'users.image as userimage','users.image_front','users.image_back','users.image_back'
                )    
                ->join('users', 'users.id', '=', 'testrequests.user_id')   
                ->join('levels', 'levels.id', '=', 'testrequests.level_id')                
                ->join('categories', 'levels.category_id', '=', 'categories.id'); 

                $result = $result->where('testrequests.is_completed','Y')   
                ->where('testrequests.status','P')   
                ->where('levels.category_id',$category_id)
                ->where('testrequests.complete_time','<>','0');
                
                $result = $result->groupBy('testrequests.level_id');
                
                $result = $result->orderBy('avg_time_to_complete','asc')->get();
                // dd($result);
    
        return $result;
    }

    public static function getLeaderBoardByLevel($level_id,$category_id){
        $result = DB::table('testrequests')               
                ->select('testrequests.*','categories.name as category_name','levels.name as level_name',
                'users.name as username','users.email as useremail',                
                'users.image as userimage','users.image_front','users.image_back','users.image_back',
                DB::raw('FIND_IN_SET(complete_time,(SELECT GROUP_CONCAT(complete_time ORDER BY complete_time DESC ) FROM testrequests where level_id = '.$level_id.')) AS rank')
                )    
                ->join('users', 'users.id', '=', 'testrequests.user_id')   
                ->join('levels', 'levels.id', '=', 'testrequests.level_id')
                ->join('categories', 'levels.category_id', '=', 'categories.id');                             

                $result = $result->where('testrequests.is_completed','Y')   
                ->where('testrequests.status','P')   
                ->where('levels.category_id',$category_id)
                ->where('testrequests.complete_time','<>','0');
                
                $result = $result->where('testrequests.level_id',$level_id);    
                
                // $result = $result->groupBy('levels.category_id');
                $result = $result->orderBy('testrequests.complete_time','Desc')->get();
    
        return $result;
    }

    public static function getLeaderBoardByUser($user_id){
        $result = DB::table('testrequests')               
                ->select('testrequests.id',
                DB::raw('FIND_IN_SET(complete_time,(SELECT GROUP_CONCAT(complete_time ORDER BY complete_time DESC ) FROM testrequests )) AS rank')
                )    
                ->join('users', 'users.id', '=', 'testrequests.user_id')   
                ->join('levels', 'levels.id', '=', 'testrequests.level_id')
                ->join('categories', 'levels.category_id', '=', 'categories.id');                             

                $result = $result->where('testrequests.is_completed','Y')   
                ->where('testrequests.status','P')   
                //->where('levels.category_id',$category_id)
                ->where('testrequests.complete_time','<>','0');
                
                //$result = $result->where('testrequests.level_id',$level_id);    
                $result = $result->where('testrequests.user_id',$user_id);
                
                $result = $result->groupBy('levels.category_id');
                $result = $result->orderBy('testrequests.complete_time','Desc')->first();
    
        return $result;
    }

    
}
