<?php
  
namespace App;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;
use DB;
  
class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    use HasRoles;
  
    public function type_user(){
        return $this->belongsTo('App\TypeUser');
    }

    public function userArhive(){
        return $this->hasMany('App\UserArchive');
    }

    // public function category(){
    //     return $this->hasMany('App\Category');
    // }


    public function goal(){
        return $this->belongsTo('App\Goal');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'gender','type_user_id','social_id','social_type',
        'email', 'password','phone','experience',
        'age','gender','weight','image','file_path','goal_weight','initial_weight','height','blood_group',
        'goal_id','address','otp','otp_expiration_time','medical_history',
        'image_front','file_path_front','image_back','file_path_back','image_side',
        'file_path_side','device_id','level','diet_type','email_verification_status','email_verification_code'
    ];
  
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','email_verification_code'
    ];
  
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Rest omitted for brevity

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }


    public function assignRole($role)
    {
        return $this->roles()->sync(
            Role::whereName($role)->firstOrFail()
        );
    }


   

    public static function getUsersList(){
        return self::pluck('name','id')->sortBy('name');
    }

    public static function getUserByType(){
        return self::pluck('name','id')->sortBy('name');
    }
    
    public static function getUserById($user_id){
        return self::select('id','name','email','age','image','device_id')->where('id',$user_id)->first();
    }

    public static function getUserByEmail($email){
        return self::select('id','name','email','age','image','device_id')->where('email',$email)->first();
    }

    public static function getAdminUser(){
        
        $result = DB::table('users')
                ->select('users.name','users.email','users.id')                                                        
                ->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')      
                 ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')                                                         
                ->where('roles.name','Admin')
                ->orderBy('users.name')
                ->first();
    
        return $result;
          
    }

    public static function getTrainerByCategoryId($category_id){
        $result = DB::table('category_users')
                ->select('categories.name as category_name','categories.id as category_id',
                'users.name as username',
                'users.email as useremail','users.experience',
                'users.id as user_id','users.image as userimage',
                DB::raw('AVG(rating_users.rating) as rating'),'rating_users.description'
                )                                                        
                ->join('users', 'users.id', '=', 'category_users.user_id')      
                ->leftJoin('rating_users', 'users.id', '=', 'rating_users.user_id')      
                ->join('categories', 'categories.id', '=', 'category_users.category_id')                                                         
                ->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')      
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')                                                         
                ->where('roles.name','Trainer')
                ->where('category_users.category_id',$category_id)
                ->groupBy('category_users.user_id')
                ->orderBy('users.name')
                ->get();
    
        return $result;
    }

    public static function getTrainerList(){
        $result = DB::table('users')
                ->select('users.name','users.email','users.id')                                                        
                ->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')      
                 ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')                                                         
                ->where('roles.name','Trainer')->orWhere('roles.name','trainer')
                ->orderBy('users.name')
                ->get();
    
        return $result;
        
    }

    
    public static function getClientList(){
        $result = DB::table('users')
                ->select('users.name','users.email','users.id')                                                        
                ->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')      
                 ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')                                                         
                ->where('roles.name','<>','Admin')->where('roles.name','<>','Trainer')
                ->orderBy('users.name')
                ->get();
    
        return $result;
        
    }

    public static function getCategoryByUser($user_id){
        return Self::select(DB::raw('group_concat(categories.name) as category_name'))
        ->join('category_users','category_users.user_id','users.id')
        ->join('categories','categories.id','category_users.category_id')
        ->where('category_users.user_id',$user_id)->first();
    }
	
	public static function getUserRole($user_id){
		$result = DB::table('model_has_roles')
                ->select('roles.name')                                                        
                ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')                                                              
                ->where('model_has_roles.model_id',$user_id)
                ->first();
    
        return $result;
	}
	
	public static function getAssignedClients($trainer_id){
		$result = DB::table('schedules')
                ->select('schedules.*','users.id as client_id','users.name as client_name','users.age','users.email','users.gender','users.level','levels.name as level_name')                                                                                                                                                                                                         
                ->join('users', 'users.id', '=', 'schedules.user_id') 
				->join('levels', 'levels.id', '=', 'users.level') 				
                ->where('schedules.trainer_id',$trainer_id)   
                ->groupBy('users.id')
                ->orderBy('schedules.start')
                ->get();
    
        return $result;
    }
    

    public static function getReview(){
        $result = DB::table('reviews')
                ->select('reviews.*','users.name',
                'users.email',
                'u1.name as trainer_name')     
                ->join('users', 'users.id', '=', 'reviews.user_id') 
                ->join('users  as u1', 'users.id', '=', 'reviews.rating_to')                                                                
                ->orderBy('users.name','asc')
                ->get();
    
        return $result;
        
    }

    public static function getGoalWeightProgress($user_id){
        $result = DB::table('users')
                ->select('users.*','user_weight.weight as user_weight_last_weekly')     
                ->join('user_weight', 'user_weight.user_id', '=', 'users.id')                 
                ->where('users.id',$user_id)
                ->orderBy('user_weight.created_at','desc')
                ->first();
        if(!isset($result->id)){
            return '00';
        }
        $weight_diff = abs($result->initial_weight - $result->goal_weight);
        $top = abs($result->initial_weight - $result->user_weight_last_weekly);
        // if($weight_diff < 0){
        //     return 0;
        // }        
        if($weight_diff==0){
            return 0;
        }
        $percentage = (($top*100) / $weight_diff);
        return number_format($percentage,2);
        
    }

    public static function getMySubscriptionByCategory($user_id){
        $result = DB::table('categories')
                ->select('categories.id as cat_id','categories.name as category_name','subscriptions.status as subs_status')     
                ->join('plans', 'plans.category_id', '=', 'categories.id') 
                ->join('subscriptions', 'subscriptions.plan_id', '=', 'plans.id')                                                                
                ->join('users', 'users.id', '=', 'subscriptions.user_id')    
                ->groupBy('categories.id')     
                ->where('users.id',$user_id)                                                                    
                ->where('subscriptions.status','active')                                                                    
                ->orderBy('categories.name','asc')
                ->get();
    
        return $result;
    }

    public static function getClientFullDataById($id){
        $result = DB::table('users')
                ->select('users.*','subscriptions.status as subscription_status',
                'subscriptions.start_date as subscription_start_date','subscriptions.next_bill_date',
                'plans.name as plan_name','plans.days as plan_days','plans.price as plan_price',
                'plans.diet_type','plans.client_type','categories.name as category_name')                                                        
                ->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')      
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')     
                ->join('subscriptions','subscriptions.user_id','=','users.id')
                ->join('plans','plans.id','=','subscriptions.plan_id')     
                ->join('categories','categories.id','=','plans.category_id')   
                ->where('users.id',$id)                                             
                ->where('roles.name','<>','Admin')->where('roles.name','<>','Trainer')
                ->orderBy('users.name')
                ->get();
    
        return $result;
        
    }
}
