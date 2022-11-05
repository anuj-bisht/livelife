<?php

namespace App;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;
use DB;

class Slot extends Model
{
  protected $table = 'slots';
  protected $fillable = ['name','category_id','batch','start_time','end_time'];
  protected $hidden = ['_token'];

  
  public static function getSelect($is_select=false){
      $select = Self::orderBy('id','DESC');
      if($is_select){
          return $select;
      }
      return $select->get();
  }
  
  public static function getSlotById($category_id){
    return Self::where('category_id',$category_id)->get();
  }

  public static function getSlotBySlotId($slot_id){
    return Self::where('id',$slot_id)->first();
  }

  public static function getSlotByBatch($batch_id){
    $result = DB::table('slots')                                                                                           
                ->where('slots.batch_id',$batch_id)
                ->get();
    
        return $result;
    
    
  }
}
