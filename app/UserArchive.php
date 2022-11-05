<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class UserArchive extends Model
{
    protected $table = 'user_archive';
    
    
    public function user(){
        return $this->belongsTo('App\User');
    }
}
