<?php
  
  namespace App;

  use Illuminate\Database\Eloquent\Model;
  use DB;
  
class Batch extends Model
{
    
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','image','file_path','description'
    ];

    public static function getAllList(){
        return Self::all();
    }

    public static function getDataList(){
        $category = Self::pluck('name','id')->all();   
        return $category;
    }

    public static function getSelectData(){
        $category = Self::select('name','id')->get();   
        return $category;
    }

    
    public static function getDataById($id){
        
        $result = DB::table('batches')        
        ->where('id',$id)
        ->first();
        
        return $result;
    }
    
}
