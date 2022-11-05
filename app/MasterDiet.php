<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class MasterDiet extends Model
{
	use SoftDeletes;
	protected $table = 'master_diets';
    protected $fillable = ['name','type','file_path','description','deleted_at'];
	
	public function level(){
        return $this->belongsTo('App\Level');
    }
	
	public static function getDiet($level_id,$trainer_id){
		$diet = DB::table('master_diets')->select('master_diets.*','levels.name as level_name')
				->join('levels','levels.id','=','master_diets.level_id');
		if($trainer_id != 0)
			$diet = $diet->where('master_diets.trainer_id',$trainer_id);
		if($level_id != 0)
			$diet = $diet->where('master_diets.level_id','<=',$level_id);
		$diet->where('master_diets.deleted_at',null);
		$diet = $diet->get();
	return $diet;
	}
	
	public static function getAssignedDietOfUser($client_id,$client_level,$trainer_id){
		$diet = DB::table('users_diet_charts')->select('users_diet_charts.*','master_diets.name as diet_name','master_diets.type as diet_type','master_diets.file_path','master_diets.description','levels.name as level_name')
				->join('master_diets','master_diets.id','=','users_diet_charts.master_diet_id')
				->join('levels','levels.id','=','master_diets.level_id')
				->where('users_diet_charts.user_id',$client_id);
		if($trainer_id != 0)
			$diet = $diet->where('master_diets.trainer_id',$trainer_id);
		    $diet->where('users_diet_charts.deleted_at',null);
		$diet = $diet->get();
	return $diet;
	}
}
