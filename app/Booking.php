<?php

namespace App;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
  protected $table = 'bookings';
  protected $fillable = ['name','category_id','start_time','end_time','total_assigned_trainer'];
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

  public static function getBookingBySlotId($slot_id){
    return Self::where('slot_id',$slot_id)->where('status','<>','Completed')->first();
  }
}
