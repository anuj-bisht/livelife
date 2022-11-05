<?php

namespace App;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;

class Warmup extends Model
{
    protected $table = 'warmups';
    protected $fillable = ['name','description','image','file_path','code'];
    //protected $hidden = ['_token'];
     
    public function categories(){
        return $this->belongsTo(Category::class,'category_id');
    }

    public static function getAllList(){
        return Self::all();
    }

    public static function getAllListByCat($id){
        return Self::where('category_id',$id)->get();
    }
    
       
}
