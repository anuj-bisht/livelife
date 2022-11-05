<?php

namespace App;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;
use DB;

class Generic extends Model
{
    protected $table = 'generic';


    protected $fillable = ['name','image','file_path','gtype','youtube_link','description'];
    //protected $hidden = ['_token'];

    

    public static function getAllGeneric(){
        
        $result = DB::table('generic')
                ->select('generic.*')                                                                        
                ->orderBy('generic.name','desc')
                ->get();
    
        return $result;
    }

         
}
