<?php

namespace App;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;
use DB;

class Diet extends Model
{
    protected $table = 'diets';


    protected $fillable = ['name','user_id',
    'vegeterian','vegeterian_path','non_vegeterian',
    'non_vegeterian_path','eggeterian','eggeterian_path'];
    //protected $hidden = ['_token'];

    public function user(){
        return $this->belongsTo('App\User');
    }

    public static function getAllDiet(){
        
        $result = DB::table('diets')
                ->select('diets.*','users.name as user_name')                                                        
                ->join('users', 'users.id', '=', 'diets.user_id')                                       
                ->orderBy('users.name')
                ->get();
    
        return $result;
    }

    public static function getDietByUser($user_id){
        
        $result = DB::table('diets')
                ->select('diets.*','users.name as user_name')                                                        
                ->join('users', 'users.id', '=', 'diets.user_id')                                       
                ->where('users.id',$user_id)
                ->get();
    
        return $result;
    }

    

         
}
