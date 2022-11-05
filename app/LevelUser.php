<?php

namespace App;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;
use DB;

class LevelUser extends Model
{
    protected $table = 'level_users';
    protected $fillable = ['user_id','level_id','status'];
    //protected $hidden = ['_token'];

    public function users(){
        return $this->BelongsTo('App\User');
    }
		
    public static function updateLevel($data){
        
        $count = Self::where('user_id',$data['user_id'])
        ->where('level_id',$data['level_id'])        
        ->where('completed','Y')->count();
        if($count > 0){
            return 0;
        }

        DB::table('level_users')
        ->where('user_id', $data['user_id'])
        ->where('level_id',$data['level_id'])
        ->where('completed','N')
        ->delete();
        
        $result = Self::insert(
            ['user_id' => $data['user_id'], 'level_id' => $data['level_id'],'completed'=>'Y']
        );
        

        return $result;
    }
    
    public static function goToNextLevel($data){

        $lvldata = Self::select('levels.*')
        ->join('levels','levels.id','=','level_users.level_id')
        ->where('level_users.user_id',$data['user_id'])        
        ->where('level_users.level_id',$data['level_id'])
        ->where('levels.category_id',$data['category_id'])
        ->where('level_users.completed','Y')
        ->orderBy('level_users.id','Desc')
        ->first();
        
        if(isset($lvldata->id)){
            $order = $lvldata->order+1;
            $nextdata = Level::where('category_id',$data['category_id'])
            ->where('order',$order)->first();

            if(isset($nextdata->id)){
                $result = Self::insert(
                    ['user_id' => $data['user_id'], 'level_id' => $nextdata->id]
                );
        
                return $result;
            }
            
        }

        
    }

    public static function getClearedLevel($user_id){

        $result = Self::select('level_users.*','levels.name as level_name',
        'categories.name as category_name','categories.id as category_id')
        ->join('levels','levels.id','=','level_users.level_id')
        ->join('categories','categories.id','=','levels.category_id')
        ->where('level_users.completed','Y')
        ->where('level_users.user_id',$user_id)              
        ->orderBy('levels.order','DESC')->get();

        return $result;
    }

    public static function getCurrentLevel($user_id){

        $result = Self::select('level_users.*','levels.name as level_name',
        'categories.name as category_name','categories.id as category_id')
        ->join('levels','levels.id','=','level_users.level_id')
        ->join('categories','categories.id','=','levels.category_id')
        ->where('level_users.completed','N')
        ->where('level_users.user_id',$user_id)      
        ->groupBy('levels.category_id')          
        ->orderBy('levels.order','DESC')->get();

        return $result;
    }
       
}
