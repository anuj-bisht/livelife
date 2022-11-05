<?php

namespace App;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;
use DB;

class Tip extends Model
{
    protected $table = 'tips';


    protected $fillable = ['name','description','daynum'];
    //protected $hidden = ['_token'];

    

    public static function getAllTip(){
        
        $result = DB::table('tips')
                ->select('tips.*')                                                                        
                ->orderBy('tips.name','desc')
                ->get();
    
        return $result;
    }

         
}
