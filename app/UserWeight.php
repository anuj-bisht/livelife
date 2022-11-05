<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class UserWeight extends Model
{
    protected $table = 'user_weight';
    protected $fillable = [
    'weight',
    'user_id'
    ];
    //protected $hidden = ['_token'];
    
    public static function checkOneWeek($user_id){

        $date  = date('Y-m-d', strtotime('-7 days'));
        return Self::select('user_weight.*')
        ->join('users','users.id','=','user_weight.user_id')
        ->where('user_weight.user_id',$user_id)
        ->where(DB::raw('DATE(user_weight.created_at)'),'>',$date)
        ->count();
    }
    
}
