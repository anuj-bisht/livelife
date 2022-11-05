<?php

namespace App;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $table = 'videos';
    protected $fillable = ['name','description','default','unit'];
    //protected $hidden = ['_token'];

    public function level(){
        return $this->belongsTo(Level::class,'level_id');
    }
            
}
