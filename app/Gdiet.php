<?php

namespace App;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;
use DB;

class Gdiet extends Model
{
    protected $table = 'gdiets';


    protected $fillable = ['name','image','file_path'];
    //protected $hidden = ['_token'];

    

    public static function getAllGdiet(){
        
        $result = DB::table('gdiets')
                ->select('gdiets.*')                                                                        
                ->orderBy('gdiets.name','desc')
                ->get();
    
        return $result;
    }

         
}
