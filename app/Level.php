<?php

namespace App;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;
use DB;

class Level extends Model
{
    protected $table = 'levels';
    protected $fillable = ['name','description','category_id','duration'];
    //protected $hidden = ['_token'];

    public function video(){
        return $this->hasMany('App\Video');
    }

    public static function getAllLevel(){
        return Self::with(['Video'])->get();
    }      

    public static function getAllLevelList(){
        return Self::pluck('name','id');
    }

    public static function getLevelById($id){
        return Self::select('levels.*','categories.name as category_name')->with(['Video'])
        ->join('categories','categories.id','=','levels.category_id')
        ->where('levels.id',$id)->get();
    }

    public static function getLevelByCategoryId($id){
        return Self::where('category_id',$id)->get();
    }

    public static function getLevelByCategoryDD($id){
        return self::where('category_id',$id)->pluck('name','id')->sortBy('name');
    }
	
	public static function getLevelbyUser($level){
		return Self::where('id','<=',$level)->pluck('name','id');
    }
    
    public static function getLevelListByUser($category_id,$user_id){
        $result = DB::table('levels')
                ->select('levels.*')   
                ->leftJoin('level_users','level_users.level_id','=','levels.id')                                                                        
                ->leftJoin('users','users.id','=','level_users.user_id')                                                                                        				
                ->where('level_users.user_id',$user_id)   
                ->where('levels.category_id',$category_id)->get();

       

        // $a = Level::where('code', '=', $code)
        // ->where('col_a', '=' , 1);
        
        // $b = LevelUser::where('code', '=', $code)->where('col_b', '=' , 1)
        // ->union($a)
        // ->get();
                
        //         $result = $b;
    
        return $result;
    }

    public function updateData($id,$data)
    {
        return Self::where('id',$id)->update($data);
    }
       
}
