<?php

namespace App;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;
use DB;

class Review extends Model
{
    protected $table = 'reviews';


    protected $fillable = ['comment','type','user_id','rating_to','schedule_id','rating'];
    //protected $hidden = ['_token'];
    

         
}
