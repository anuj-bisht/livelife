<?php

namespace App;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $table = 'plans';
    protected $fillable = ['name','description','days',
    'month_count','price','category_id','diet_type','client_type'];
    //protected $hidden = ['_token'];


    public function category(){
        return $this->belongsTo('App\Category');
    }


    public static function getAllList(){
        return Self::all();
    }  

       
    
    public static function getPlanById($plan_id){
        
        return Self::select('plans.*','categories.is_test_enabled')->where('plans.id',$plan_id)
        ->join('categories','categories.id','=','plans.category_id')
        ->first();
    }  

    public static function getPlanByCategory($category_id){
        return Self::select('plans.*','categories.name as category_name','categories.id as category_id')
        ->join('categories','categories.id','=','plans.category_id')
        ->where('categories.id',$category_id)->get();
    }

    
}
