<?php

namespace App;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    protected $table = 'goals';
    protected $fillable = ['name'];
    //protected $hidden = ['_token'];

				
    public function users()
    {
        return $this->hasMany('App\User');
    }					
}
