<?php
  
  namespace App;
  use Illuminate\Database\Eloquent\Model;
  use DB;
  
class Recipe extends Model
{
    
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','youtube_link'
    ];

    public static function getAllList(){
        return Self::all();
    }

    
    
}
