<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UsersDietChart extends Model
{
	use SoftDeletes;
    protected $table = 'users_diet_charts';
    protected $fillable = ['user_id','master_diet_id'];
}
