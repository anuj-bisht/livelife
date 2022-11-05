<?php
  
  namespace App;

  use Illuminate\Database\Eloquent\Model;
  use DB;
class LevelData extends Model
{
    
  protected $table = 'level_data';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'level_id','name','category_id','level_type','image','file_path','code',
    ];

    public static function getLevelData($level_id,$type){
      
      $result = DB::table('level_data')
              ->select('level_data.*')   
              //->leftJoin('levels','levels.id','=','level_data.level_id')                                                                        
              //->leftJoin('categories','categories.id','=','level_data.category_id')                                                                                        				
              ->where('level_data.level_type',$type)   
              ->where('level_data.level_id',$level_id)->get();

      return $result;
  }
    
}
