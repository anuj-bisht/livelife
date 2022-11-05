<?php

namespace App;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;
use DB;

class Banner extends Model
{
    protected $table = 'banners';


    protected $fillable = ['name','image','file_path'];
    //protected $hidden = ['_token'];

    

    public static function getAllBanner(){
        
        $result = DB::table('banners')
                ->select('banners.*')                                                                        
                ->orderBy('banners.name','desc')
                ->get();
    
        return $result;
    }

         
}
