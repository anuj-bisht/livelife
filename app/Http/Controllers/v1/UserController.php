<?php

namespace App\Http\Controllers\v1;

use App\User;
use App\Level;
use App\Category;
use App\Review;
use App\UserWeight;
use App\LevelUser;
use App\Chat;
use App\Testrequest;
use App\Schedule;
use App\Notification;
use App\Contactus;
use App\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Controllers\Traits\SendMail;
use App\Http\Controllers\Traits\Common;
use Config;
use App\Common\Utility;
use App\Classes\UploadFile;
use Mail;


class UserController extends Controller
{
  use SendMail;
  use Common;

  public function sendotp(Request $request){
    
    try{
      $status = 0;
      $message = "";
                  
            
      $validator = Validator::make($request->all(), [          
        'phone' => 'required|string|max:10|min:10'      
      ]);

      
      if($validator->fails()){
         $error = json_decode(json_encode($validator->errors()));
         if(isset($error->phone[0])){
           $message = $error->phone[0];
         }

         return response()->json(["status"=>$status,"responseCode"=>"NP997","message"=>$message,"data"=>json_decode("{}")]);
       }
      

      $userList = User::where('phone',$request->phone)->first();
      
      $otp = rand(100000,999999);
      //$otp = 123456;
      if($userList !=null && $userList->count() > 0){

        $phone = $request->phone;
        $message = "Hi Welcome to LivFit Gym APP, your 6 digit OTP is $otp";
        $userList->otp = $otp;
        $userList->otp_expiration_time = time();
        $userList->save();
        if($this->SendSms($phone,$message)){
          return response()->json(["status"=>1,"responseCode"=>"APP001","message"=>"OTP Sent","otp"=>$otp,"data"=>json_decode("{}")]);
        }
                
      }else{       
          return response()->json(['status'=>0,"responseCode"=>"NP997",'message'=>'User not fount. please register first','data'=>json_decode("{}")]);       
      }            
         
    }catch(Exception $e){
      return response()->json(['status'=>0,"responseCode"=>"NP997",'message'=>'User update Error','data'=>json_decode("{}")]);                    
    }
            
  }

  public function sendotpReg(Request $request){

    $status = 0;
    $message = "";

    $validator = Validator::make($request->all(), [            
        'phone' => 'required',
        'email' => 'required|email',
    ]);        
    //$validator->errors()
    if($validator->fails()){
      return response()->json(["status"=>$status,"responseCode"=>"NP997","message"=>"invalid input details","data"=>json_decode("{}")]);
    }

    $email_otp = rand(111111,999999);
    $otp = rand(111111,999999);
    DB::table('temp_user')->insert(
      [
        'email' => $request->email, 
        'mobile' => $request->phone, 
        'email_otp' => $email_otp, 
        'otp' => $otp,
        'expire_in'=>strtotime("+3 minutes")
      ]
    );

    

    $data = [];  
    $data['to_email'] = $request->email;         ;
    $data['from'] = config('app.MAIL_FROM_ADDRESS');         ;
    $data['subject'] = 'Email OTP Verification';
    $data['name'] = 'Admin,';
    $data['otp'] = $email_otp;
    $data['message1'] = 'Email OTP Verification';
    
    
    if($this->SendMail($data,'mail_otp')){
      $res = ['email_otp'=>$email_otp,'opt'=>$otp];
      return response()->json(["status"=>1,"message"=>"Verification sent to mobile and email","data"=>$res]);
    }else{
      return response()->json(["status"=>$status,"responseCode"=>"NP997","message"=>"Email/OTP error","data"=>json_decode("{}")]);
    }

    
  }

  public function verifyotp(Request $request){

    $status = 0;
    $message = "";

    $validator = Validator::make($request->all(), [            
        'phone' => 'required',
        'email' => 'required|email',
        'email_otp'=>'required',
        'otp'=>'required'
    ]);        
    //$validator->errors()
    if($validator->fails()){
      return response()->json(["status"=>$status,"responseCode"=>"NP997","message"=>"invalid input details","data"=>json_decode("{}")]);
    }
    
    $tempUser = DB::table('temp_user')->where('email',$request->email)
    ->orderBy('id','desc')
    ->where('email_otp',$request->email_otp)
    ->where('mobile',$request->phone)
    ->where('otp',$request->otp)->first();

    if(!isset($tempUser->id)){
      return response()->json(["status"=>$status,"message"=>"Invalid otp verification","data"=>json_decode("{}")]);
    }else{
      return response()->json(["status"=>1,"message"=>"Email and phone has been verified","data"=>json_decode("{}")]);
    }


  }

  public function authenticate(Request $request)
  {
      
      $status = 0;
      $message = "";

                    
      $validator = Validator::make($request->all(), [            
          'phone' => 'required|string|max:10',
          'otp' => 'required|string',
      ]);        
      //$validator->errors()
      if($validator->fails()){
        return response()->json(["status"=>$status,"responseCode"=>"NP997","message"=>"invalid input details","data"=>json_decode("{}")]);
      }
      //echo $pwd = Hash::make($request->password).'      ='.$request->email; die;
      $validationChk = User::where('phone',$request->phone)->get();
      
      
      if($validationChk->count()==0){
        return response()->json(["status"=>$status,"responseCode"=>"NP997","message"=>"invalid credentials","data"=>json_decode("{}")]);          
      }else if($validationChk[0]->status != '1'){
        return response()->json(["status"=>$status,"responseCode"=>"NP997","message"=>"User not verified","data"=>json_decode("{}")]);          
      }
      
      // $otp_time_stamp = (int) $validationChk[0]->otp_expiration_time; 
      // $curr_time_stamp = time(); 
      // $diff = $curr_time_stamp - $otp_time_stamp; 
      // $minute = ($diff / 60); 
      // if($minute > 3){ 
      //   return response()->json(["status"=>$status,"responseCode"=>"NP997","message"=>"Otp is expired","data"=>json_decode("{}")]);   
      // }

      $credentials = $request->only('phone', 'otp');                

      
      try {
        $myTTL = 43200; //minutes
        JWTAuth::factory()->setTTL($myTTL);            
          if (! $token = JWTAuth::attempt($credentials, ['status'=>'1'])) {            
              $message = 'invalid_credentials';                
              return response()->json(['status'=>$status,"responseCode"=>"NP997",'message'=>$message,'data'=>json_decode("{}")]);
          }
      } catch (JWTException $e) {
          $message = 'could_not_create_token';
          return response()->json(['status'=>$status,"responseCode"=>"NP997",'message'=>$message,'data'=>json_decode("{}")]);            
      }        
      $user  = JWTAuth::user();
      unset($user->otp);
      unset($user->verified_otp);
      $user->token = $token;
      $user->remember_token = $token;
      $user->device_id = (isset($request->device_id)) ? $request->device_id : '';
      //$this->SendSms($request->phone,'Welcome to LivFit Your OTP is: '.$otp);
      $user->save();
      unset($user->remember_token);
      $status = 1;        
      return response()->json(['status'=>$status,"responseCode"=>"APP001",'message'=>$message,'data'=>$user]);
  }


  public function socialcheck(Request $request)
  {
      
      $status = 0;
      $message = "";

                    
      $validator = Validator::make($request->all(), [            
          'social_id' => 'required',
          'name' => 'required|string',          
      ]);        
      //$validator->errors()
      if($validator->fails()){
        return response()->json(["status"=>$status,"responseCode"=>"NP997","message"=>"invalid input details","data"=>json_decode("{}")]);
      }
      
      if(isset($request->email)){
        $validationChk = User::where('email',$request->email)->get();  
      }else{
        $validationChk = User::where('social_id',$request->social_id)->get();
      }
      
      $obj = new User();
      if($validationChk->count()>0){

        $obj = $validationChk[0];
        $otp = rand(100000,999999);
        $obj->otp = $otp;
        $obj->save();

        $credentials = ['phone'=>$obj->phone,'otp'=>$obj->otp];                
      
        try {
          $myTTL = 43200; //minutes
          JWTAuth::factory()->setTTL($myTTL);            
            if (! $token = JWTAuth::attempt($credentials, ['status'=>'1'])) {            
                $message = 'invalid_credentials';                
                return response()->json(['status'=>$status,"responseCode"=>"NP997",'message'=>$message,'data'=>json_decode("{}")]);
            }
        } catch (JWTException $e) {
            $message = 'could_not_create_token';
            return response()->json(['status'=>$status,"responseCode"=>"NP997",'message'=>$message,'data'=>json_decode("{}")]);            
        }
        
        $user  = JWTAuth::user();
        unset($user->otp);
        unset($user->verified_otp);
        $user->token = $token;
        $user->remember_token = $token;
        $user->device_id = (isset($request->device_id)) ? $request->device_id : '';

        //$this->SendSms($request->phone,'Welcome to LivFit Your OTP is: '.$otp);
        $user->save();
        unset($user->remember_token);
        $status = 1;        
        return response()->json(['status'=>$status,"responseCode"=>"0001",'message'=>$message,'data'=>$user]);
      }else{               
        return response()->json(['status'=>1,"responseCode"=>"0000",'message'=>'You are not associted with us, Please register first','data'=>json_decode("{}")]);            
      }
                   
  }

  public function apilogout(Request $request){
    
    try{        
      JWTAuth::invalidate(JWTAuth::parseToken()); 
      //JWTAuth::setToken($token)->invalidate();
      return response()->json(['status'=>1,"responseCode"=>"APP001",'message'=>'','data'=>json_decode("{}")]);
    }catch(Exception $e){
      return response()->json(['status'=>0,"responseCode"=>"NP997",'message'=>'Not able to logout','data'=>json_decode("{}")]);
    }
    
  }
  
 
  public function getAuthenticatedUser() { 
       $status = 0;   
      try {

              if (! $user = JWTAuth::parseToken()->authenticate()) {
                //return response()->json(['user_not_found'], 404);
                return response()->json(['status'=>$status,'message'=>'user_not_found','data'=>json_decode("{}")]);
              }

      } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

          //return response()->json(['token_expired'], $e->getStatusCode());
          return response()->json(['status'=>$status,'message'=>'token_expired','data'=>json_decode("{}")]);

      } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

        //return response()->json(['token_invalid'], $e->getStatusCode());
        return response()->json(['status'=>$status,'message'=>'token_invalid','data'=>json_decode("{}")]);

      } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
        return response()->json(['status'=>$status,'message'=>'token_absent','data'=>json_decode("{}")]);
        //return response()->json(['token_absent'], $e->getStatusCode());
      }
      $status = 1;
      return response()->json(compact('user'));
 }
     
   

   public function changePassword(Request $request){
    try{
        
      $status = 0;
            $message = "";
          
            
            $user  = JWTAuth::user();  

            $validator = Validator::make($request->all(), [
                'old_password' => 'required',                                
                'new_password' => 'min:6|required_with:password_confirmation|same:password_confirmation',                                
                'password_confirmation' => 'required|min:6',                                
            ]);           
            if($validator->fails()){
              $error = json_decode(json_encode($validator->errors()));
              if(isset($error->old_password)){
                $message = $error->old_password[0];
              }else if(isset($error->new_password)){
                $message = $error->new_password[0];
              }else if(isset($error->password_confirmation)){
                $message = $error->password_confirmation[0];
              }
              return response()->json(["status"=>$status,"message"=>$message,"data"=>json_decode("{}")]);
            } 

            if(!Hash::check($request->old_password, $user->password)){
              return response()->json(['status'=>$status,'message'=>'old password incorrect','data'=>json_decode("{}")]);
            }else{            
              User::where('email', $user->email)->update(['password'=>Hash::make($request->new_password)]);
              
              return response()->json(['status'=>1,'message'=>$message, 'data'=>json_decode("{}")]);
            }            

            return response()->json(['status'=>1,
            'message'=>$message,                                
            'data'=>[]
            ]);
            
                                          
        }catch(Exception $e){
      
            return response()->json(['status'=>$status,'message'=>$message,'data'=>json_decode("{}")]);    
        }   
    }

      /**
     * Edit event method
     * @return success or error
     * 
     * */
    public function editMyProfile(Request $request){
      
      try{
        $status = 0;
        $message = "";
              
        $user  = JWTAuth::user();


        DB::table('user_archive')->insert(
          [
            'user_id' => $user->id, 
            'name' => $user->name, 
            'social_id' => $user->social_id, 
            'social_type' => $user->social_type, 
            'age' => $user->age,
            'weight'=>$user->weight,
            'height'=>$user->height,
            'medical_history'=>$user->medical_history,
            'diet_type'=>$user->diet_type,
            'gender'=>$user->gender,
            'goal_weight'=>$user->goal_weight,
            'image_front'=>$user->image_front,
            'file_path_front'=>$user->file_path_front,
            'image_back'=>$user->image_back,
            'file_path_back'=>$user->file_path_back,
            'image_side'=>$user->image_side,
            'file_path_side'=>$user->file_path_side,
            'image'=>$user->image,
            'file_path'=>$user->file_path
          ]
        );
        

        $user->id = $user->id; 
        $user->name = (isset($request->name)) ? $request->name : $user->name;
        $user->age = (isset($request->age)) ? $request->age : $user->age;
        $user->weight = (isset($request->weight)) ? $request->weight : $user->weight;
        $user->height = (isset($request->height)) ? $request->height : $user->height;
        $user->medical_history = (isset($request->medical_history)) ? $request->medical_history : $user->medical_history;
        $user->diet_type = (isset($request->diet_type)) ? $request->diet_type : $user->diet_type;
        $user->gender = (isset($request->gender)) ? $request->gender : $user->gender;
        
        //$user->initial_weight = (isset($request->initial_weight)) ? $request->initial_weight : $user->initial_weight;
        $user->goal_weight = (isset($request->goal_weight)) ? $request->goal_weight : $user->goal_weight;

        

        
        if(isset($_FILES['front']['name'])) {                      
          $upload_handler = new UploadFile();
          $path = public_path('uploads/users'); 
          $data = $upload_handler->uploadByName($path,'front','users');
          $res = json_decode($data);           
          
          if($res->status=='ok'){
            $user->image_front = $res->path;
            $user->file_path_front = $res->img_path;                                
          }                                                                   
        }        
        
        if(isset($_FILES['back']['name'])) {                
          $upload_handler = new UploadFile();
          $path = public_path('uploads/users'); 
          $data = $upload_handler->uploadByName($path,'back','users');
          $res = json_decode($data);           
          if($res->status=='ok'){
            $user->image_back = $res->path;
            $user->file_path_back = $res->img_path;                                
          }                                                 
        }

        if(isset($_FILES['side']['name'])) {                
          $upload_handler = new UploadFile();
          $path = public_path('uploads/users'); 
          $data = $upload_handler->uploadByName($path,'side','users');
          $res = json_decode($data);           
          if($res->status=='ok'){
            $user->image_side = $res->path;
            $user->file_path_side = $res->img_path;                                
          }                                                 
        }

        if(isset($_FILES['file']['name'])) {                
          $upload_handler = new UploadFile();
          $path = public_path('uploads/users'); 
          $data = $upload_handler->uploadByName($path,'file','users');
          $res = json_decode($data);           
          if($res->status=='ok'){
            $user->image = $res->path;
            $user->file_path = $res->img_path;                                
          }                                                 
        } 

        

            
        if(!$user->save()){          
            return response()->json(['status'=>$status,'message'=>'Unable to save','data'=>$user]);                    
        }else{          
            return response()->json(['status'=>1,'message'=>'Profile updated successfully','data'=>$user]);                    
        }   
      }catch(Exception $e){
        return response()->json(['status'=>$status,'message'=>'User update Error','data'=>json_decode("{}")]);                    
      }
              
    }

    public function getMyProfile(Request $request){
      
      try{
        $status = 1;
        $message = "";
              
        $user  = JWTAuth::user();
        //$data = [];
        if(!isset($user)){
          return response()->json(['status'=>0,'message'=>'User does not exist','data'=>json_decode("{}")]);                                      
        }

        $percentage_progress =  $this->getGoalWeightProgress($user->id); 

        $userData = TestRequest::getLeaderBoardByUser($user->id);
        
        $cleard = LevelUser::getClearedLevel($user->id);

        $catArr = [];
        $a1 = [];
        if($cleard->count()){
          foreach($cleard as $k=>$v){
            if(!in_array($v->category_id,$catArr)){              
              $catArr[] = $v->category_id;              
              $a1[] = $v;
            }
          }
        }
        
        $current = LevelUser::getCurrentLevel($user->id);
        $data = ['user'=>$user,'cleared_level'=>$a1,'current_level'=>$current,'progress_percentage'=>$percentage_progress];
        if(isset($userData->id)){
          $data ['rank_data'] = $userData;
        }else{
          $data ['rank_data'] = json_decode("{}");
        }
        
        return response()->json(['status'=>$status,'message'=>'','data'=>$data]);                                      
      }catch(Exception $e){
        return response()->json(['status'=>0,'message'=>'User update Error','data'=>json_decode("{}")]);                    
      }
              
    }

    public function register(Request $request)
    {
      $status = 0;
      $message = "";
      DB::beginTransaction();
      try{
          

           $validator = Validator::make($request->all(), [
               'name' => 'required|string|max:255',
               'email' => 'required|string|max:255|unique:users',               
               'age' => 'required|numeric|max:120',               
               'gender' => 'required|string',  
               'initial_weight'=> 'required',
               'password' => 'required|min:6',
               'goal_weight' => 'required',
               'phone' =>  'required|string|max:10|unique:users',
               'device_id'=>'required'
           ]);

           $data = [];          
          //  $data['email'] = $request->get('email');
          //  $data['name'] = $request->get('name');
          //  $data['supportEmail'] = config('mail.supportEmail');
          //  $data['website'] = config('app.site_url');  
          //  $data['site_name'] = config('app.site_name');                     
                  
          // $data['subject'] = 'Registration OTP from '.config('app.site_name'); 
           $otp = rand(111111,999999);  
           $data['otp'] = $otp;   
                                  
           //$validator->errors()
           if($validator->fails()){
             $error = json_decode(json_encode($validator->errors()));
             if(isset($error->name[0])){
               $message = $error->name[0];
             }else if(isset($error->email)){
               $message = $error->email[0];
             }else if(isset($error->phone)){
               $message = $error->phone[0];
             }else if(isset($error->gender)){
              $message = $error->gender[0];
             }else if(isset($error->age)){
              $message = $error->age[0];
             }else if(isset($error->initial_weight)){
              $message = $error->initial_weight[0];
             }else if(isset($error->goal_weight)){
              $message = $error->goal_weight[0];
             }else if(isset($error->password)){
              $message = $error->password[0];
             }else if(isset($error->device_id)){
              $message = $error->device_id[0];
             }
             return response()->json(["status"=>$status,"message"=>$message,"data"=>json_decode("{}")]);
           }
           
           $social_id = isset($request->social_id) ? $request->social_id : 0;
           $social_type = isset($request->social_type) ? $request->social_type : '';

           $user = User::create([
             'name' => $request->get('name'),
             'email' => $request->get('email'),
             'phone' => $request->get('phone'),                         
             'social_id' => $social_id,
             'social_type' => $social_type,
             'status' => '0',
             'weight'=>$request->get('initial_weight'),
             'initial_weight'=> $request->get('initial_weight'),                         
             'goal_weight'=> $request->get('goal_weight'),   
             'password' => Hash::make($request->get('password')),
             'verified_otp' => 0,          
             'email_verification_code'=>uniqid(),   
             'age' => $request->get('age'),
             'gender' => $request->get('gender'),             
             'device_id' => $request->get('device_id'),
             'otp' => $otp
         ]);

         $this->sendMailOtp($request->email,true);

         $user->assignRole('Client');

         $token = JWTAuth::fromUser($user);          
         //JWTAuth::setToken($token);
         $data1 = [];
         $defaultLevel = Level::where('default','Y')->get(); 
         if($defaultLevel->count()){
           foreach($defaultLevel as $k=>$v){
            $data1[] = [              
              'user_id' => $user->id,
              'level_id' => $v->id            
            ];            
           }
           LevelUser::insert($data1); 
         }

         $userWeight = ["user_id"=>$user->id,"weight"=>$request->get('initial_weight')];
         UserWeight::insert($userWeight); 
         //echo '>>>>>>>--'.config('app.MAIL_FROM_ADDRESS');die;
         $data['to_email'] = $request->email;
         $data['from'] = config('app.MAIL_FROM_ADDRESS');         ;
         $data['subject'] = 'Registration success';
         $data['name'] = $request->get('name');
         $data['message1'] = 'Your registration is successfully completed with us';

         $this->SendMail($data,'testmail');
                  
         if($this->SendMail($data,'registration')){

            $data = [];  
            $data['to_email'] = config('app.from_email');         ;
            $data['from'] = config('app.MAIL_FROM_ADDRESS');         ;
            $data['subject'] = 'New Registration';
            $data['name'] = 'Admin,';
            $data['message1'] = 'A new user registration is added in our site, Below are the details';
            $data['username'] = $request->get('name');
            $data['email'] = $request->get('email');
            $data['phone'] = $request->get('phone');
            $data['age'] = $request->get('age');
            $data['gender'] = $request->get('gender');

            $suscription_title = "New Registration";
            $suscription_msg = "Your registration successfully completed.";
            //$suscription_msg .= "Category:".$category->name." and Slot time is:".$request->slot_time;

            $notification = [];
            $notification['title'] = $suscription_title;
            $notification['message'] = $suscription_msg;
            $notification['user_id'] = $user->id;
            $notification['type'] = 'Normal';                
            Notification::saveNotification($notification); 
            
            $this->SendMail($data,'registration_toadmin');

           DB::commit();
           $message = "User registered successfully";
           return response()->json(["status"=>1,"message"=>$message,"data"=>compact('user','token')]);        
         }else{
           return response()->json(["status"=>0,"message"=>'Unable to send email',"data"=>json_decode("{}")]);        
         }                              
       } catch(Exception $e){
         DB::rollBack();
         return response()->json(['status'=>$status,'message'=>'asdfasdf','data'=>json_decode("{}")]);
       }              
    }

    /**
     * Edit event method
     * @return success or error
     * 
     * */
    public function getProfile(Request $request){
      
      try{
        $status = 0;
        $message = "";
              
        $user  = JWTAuth::user();

        if(isset($user->token)){
          unset($user->token);
          unset($user->verified_otp);
          unset($user->status);
        }
        
        if(!$user->id){          
            return response()->json(['status'=>$status,'message'=>'Profile does not exist','data'=>$user]);                    
        }else{          
            return response()->json(['status'=>1,'message'=>'','data'=>$user]);                    
        }   
      }catch(Exception $e){
        return response()->json(['status'=>$status,'message'=>'User update Error','data'=>json_decode("{}")]);                    
      }
              
    }

    public function getTrainerByCategory(Request $request){
      
      try{
        $status = 0;
        $message = "";
        $validator = Validator::make($request->all(), [
          'category_id' => 'required'
        ]);

        if($validator->fails()){
          $error = json_decode(json_encode($validator->errors()));
          if(isset($error->category_id[0])){
            $message = $error->category_id[0];
          }
          return response()->json(["status"=>$status,"message"=>$message,"data"=>json_decode("{}")]);
        }

        $category_id = $request->category_id;
              
        $user  = JWTAuth::user();
        $result = User::getTrainerByCategoryId($category_id);

        if($result->count()){
          foreach($result as $k=>$v){
            $res = User::getCategoryByUser($v->user_id);
            if(isset($res->category_name))
              $result[$k]->category_name =  $res->category_name;
            else
              $result[$k]->category_name =  '';
          }
        }
        
        if(!$user->id){          
            return response()->json(['status'=>$status,'message'=>'Profile does not exist','data'=>$user]);                    
        }else{          
            return response()->json(['status'=>1,'message'=>'','data'=>$result]);                    
        }   
      }catch(Exception $e){
        return response()->json(['status'=>$status,'message'=>'Error','data'=>json_decode("{}")]);                    
      }
              
    }


    public function getSettings(Request $request){
    
      try{        
        $data = Setting::select('online_training','virtual_training','offline_training')->where('id',1)->first();
        return response()->json(['status'=>1,"responseCode"=>"APP001",'message'=>'','data'=>$data]);
      }catch(Exception $e){
        return response()->json(['status'=>0,"responseCode"=>"NP997",'message'=>'Not able to logout','data'=>json_decode("{}")]);
      }
      
    }

    public function testNotification(){
      $diviceIds = ['dJUidcuZRBae7a7vn23xay:APA91bHlG1gGQp81PfDoapt7XK_Z3DOhVw-m5HVaTnwidTtYDsjcNy3x2DLYc6UpVZOZXF3CiiQW_py6umk1RUclQ1bY2J3cSfp3m3ksB6KPazjzg5aTMihfHfOSC8zJr7oD8-McVhlD'];
      $this->sendNotification($diviceIds,'','Subscription success','Your subscription is successfully added');
      return response()->json(["status"=>1,"message"=>'message send',"data"=>json_decode("{}")]);
    }

    public function contactus(Request $request){
      try{
        $status = 0;
        $message = "";
        $validator = Validator::make($request->all(), [
          'name' => 'required',
          'email' => 'required|email',
          'phone' => 'required',
          'message' => 'required'
        ]);

        if($validator->fails()){
          $error = json_decode(json_encode($validator->errors()));
          if(isset($error->name[0])){
            $message = $error->name[0];
          }else if(isset($error->email[0])){
            $message = $error->email[0];
          }else if(isset($error->phone[0])){
            $message = $error->phone[0];
          }else if(isset($error->message[0])){
            $message = $error->message[0];
          }
          return response()->json(["status"=>$status,"message"=>$message,"data"=>json_decode("{}")]);
        }
        
        $obj = new Contactus();
        $obj->name = $request->name;
        $obj->phone = $request->phone;
        $obj->email = $request->email;
        $obj->message = $request->message;                        

        if($obj->save()){      
            $data = [];
            $data['to_email'] = config('app.MAIL_FROM_ADDRESS');
            $data['from'] = $request->email;         ;
            $data['subject'] = 'Contact enquiry';
            $data['name'] = $request->name;
            $data['email'] = $request->email;
            $data['phone'] = $request->phone;
            $data['message1'] = $request->message;
            $this->SendMail($data,'contact');

            return response()->json(['status'=>1,'message'=>'message sent','data'=>json_decode("{}")]);                    
        }else{          
            return response()->json(['status'=>$status,'message'=>'error','data'=>json_decode("{}")]);                    
        }   
      }catch(Exception $e){
        return response()->json(['status'=>$status,'message'=>'Error','data'=>json_decode("{}")]);                    
      }
    }

    public function getMyNotifications(Request $request){
    
      try{                
        $status = 0;
        $message = "";
        $user  = JWTAuth::user();
        $result = Notification::getMyNotifications($user->id);
        return response()->json(['status'=>1,"responseCode"=>"APP001",'message'=>'','data'=>$result]);
      }catch(Exception $e){
        return response()->json(['status'=>0,"responseCode"=>"NP997",'message'=>'Not able to logout','data'=>json_decode("{}")]);
      }
      
    }

    public function getMySubscriptionByCategory(Request $request){
      try{          
            
        $status = 0;
        $message = "";
        $user  = JWTAuth::user();
        $category_list = Category::select('id','name')->get();
        $result = User::getMySubscriptionByCategory($user->id);
        $catArr = [];
        if($result->count()){
          foreach($result as $k=>$v){
            $catArr[] = $v->cat_id;
          }
        }
        
        foreach($category_list as $k1=>$v1){
          if(in_array($v1->id,$catArr)){
            $category_list[$k1]->subs_status = 'Yes';
          }else{
            $category_list[$k1]->subs_status = 'No';
          }  
        }
        
        return response()->json(['status'=>1,'message'=>'','data'=>$category_list]);
      }catch(Exception $e){
        return response()->json(['status'=>0,"responseCode"=>"NP997",'message'=>'Not able to logout','data'=>json_decode("{}")]);
      }
    }

    public function testmail(Request $request){
    
      try{                
        $status = 0;
        $message = "";
        $data = [];

        $data['to_email'] = 'ravindra2806@gmail.com';
        $data['from'] = 'support@livefitlife.in';
        $data['subject'] = 'Test subject';
        $data['name'] = 'RavindraT';
        $data['message1'] = 'My test message';

        $this->SendMail($data,'testmail');
        
        return response()->json(['status'=>1,"responseCode"=>"APP001",'message'=>'','data'=>[]]);
      }catch(Exception $e){
        return response()->json(['status'=>0,"responseCode"=>"NP997",'message'=>'Not able to logout','data'=>json_decode("{}")]);
      }
      
    }


    public function addChat(Request $request){
    
      try{                
        $status = 0;
        $message = "";
        $user  = JWTAuth::user();

        if(!isset($user->id)){
          return response()->json(['status'=>0,'message'=>'invalid token','data'=>json_decode("{}")]);  
        }

        $validator = Validator::make($request->all(), [
          'message' => 'required'
        ]);

        if($validator->fails()){
          $error = json_decode(json_encode($validator->errors()));
          if(isset($error->message[0])){
            $message = $error->message[0];
          }
          return response()->json(["status"=>$status,"message"=>$message,"data"=>json_decode("{}")]);
        }

        $adminData = User::getAdminUser();
        $to = 1;
        if(isset($adminData->id)){
          $to = $adminData->id;
        }

        $inputs = ['from_id' => $user->id,'message' => $request->message,"to_id"=>$to];

        Chat::create($inputs);

        return response()->json(['status'=>1,'message'=>'Message sent','data'=>json_decode("{}")]);
      }catch(Exception $e){
        return response()->json(['status'=>0,"responseCode"=>"NP997",'message'=>'Not able to logout','data'=>json_decode("{}")]);
      }
      
    }

    public function getChat(Request $request){
    
      try{                
        $status = 0;
        $message = "";
        $user  = JWTAuth::user();
        
        if(!isset($user->id)){
          return response()->json(['status'=>0,'message'=>'invalid token','data'=>json_decode("{}")]);  
        }

        $result = Chat::getChat($user->id);

        return response()->json(['status'=>1,'message'=>'','data'=>$result]);
      }catch(Exception $e){
        return response()->json(['status'=>0,'message'=>'Not able to get chat','data'=>json_decode("{}")]);
      }
      
    }


    public function addReview(Request $request){
    
      try{                
        $status = 0;
        $message = "";
        $user  = JWTAuth::user();

        if(!isset($user->id)){
          return response()->json(['status'=>0,'message'=>'invalid token','data'=>json_decode("{}")]);  
        }

        $validator = Validator::make($request->all(), [
          'rating' => 'required|min:1|max:5',
          'trainer_id' => 'required',
          'type'=>'required',
          'schedule_id'=>'required'
        ]);

        if($validator->fails()){
          $error = json_decode(json_encode($validator->errors()));
          if(isset($error->rating[0])){
            $message = $error->rating[0];
          }else if(isset($error->trainer_id[0])){
            $message = $error->trainer_id[0];
          }else if(isset($error->schedule_id[0])){
            $message = $error->schedule_id[0];
          }
          return response()->json(["status"=>$status,"message"=>$message,"data"=>json_decode("{}")]);
        }
        $message1 = '';
        if(isset($request->message)){
          $message1 = $request->message;
        }
        $review = Review::where('user_id',$user->id)
        ->where('rating_to',$request->trainer_id)
        ->where('schedule_id',$request->schedule_id)
        ->where(DB::raw('DATE(created_at)'),date('Y-m-d'))->first();
        if(isset($review->id)){
          return response()->json(['status'=>0,'message'=>'one rating is allowed for one day','data'=>json_decode("{}")]);  
        }
        //echo $request->schedule_id; die;
        $inputs = ['user_id' => $user->id,
        'type'=>$request->type,
        'rating_to'=>$request->trainer_id,
        'schedule_id'=>$request->schedule_id,
        'comment'=>$message1,'rating' => $request->rating];

        $data = Review::create($inputs);

        return response()->json(['status'=>1,'message'=>'Message sent','data'=>$data]);
      }catch(Exception $e){
        return response()->json(['status'=>0,"responseCode"=>"NP997",'message'=>'Not able to logout','data'=>json_decode("{}")]);
      }
      
    }

     /**
     * Edit event method
     * @return success or error
     * 
     * */
    public function updateWeight(Request $request){
      
      try{
        $status = 0;
        $message = "";
              
        $user  = JWTAuth::user();

        if(!isset($user->id)){
          return response()->json(['status'=>$status,'message'=>'invalid token','data'=>json_decode("{}")]);                              
        }
       
        $userData = User::find($user->id);
        $userData->weight = $request->weight;
        $userData->save();
        $insert = UserWeight::insert(['user_id'=>$user->id,'weight'=>$request->weight]);
                    
        if(!$insert){          
            return response()->json(['status'=>$status,'message'=>'Unable to save','data'=>$user]);                    
        }else{          
            return response()->json(['status'=>1,'message'=>'Weight updated successfully','data'=>json_decode("{}")]);                    
        }   
      }catch(Exception $e){
        return response()->json(['status'=>$status,'message'=>'User update Error','data'=>json_decode("{}")]);                    
      }
              
    }

    public function getsetting(Request $request){
    
      try{                
        $status = 0;
        $message = "";
        
        $result = Setting::select(
          'terms_and_condition',
          'privacy_policy',
          'faq',
          'test_content',
          'demo_content',
          'privacy_policy'
          )->where('id',1)->first();

        return response()->json(['status'=>1,'message'=>'','data'=>$result]);
      }catch(Exception $e){
        return response()->json(['status'=>0,'message'=>'Not able to get chat','data'=>json_decode("{}")]);
      }
      
    }


    public function getProfileData(Request $request){
      
      try{
        $status = 0;
        $message = "";
              
        $user  = JWTAuth::user();

        if(!isset($user->id)){
          return response()->json(['status'=>$status,'message'=>'invalid token','data'=>json_decode("{}")]);                              
        }
        
        $data1 = LevelUser::getClearedLevel($user->id);   
        $data2 = LevelUser::getCurrentLevel($user->id);  
        $data3 = Testrequest::getLeaderBoardByUser($user->id,0);

        $data = ['cleared'=>$data1,'current'=>$data2,'rankdata'=>$data3];

        return response()->json(['status'=>1,'message'=>'','data'=>$data]);                    

      }catch(Exception $e){
        return response()->json(['status'=>$status,'message'=>'User update Error','data'=>json_decode("{}")]);                    
      }
              
    }

    public function resendMailOtp(Request $request){
      if(isset($request->email)){
        $this->sendMailOtp($request->email,true);
        return response()->json(['status'=>1,'message'=>'','data'=>json_decode("{}")]); 
      }else{
        return response()->json(['status'=>0,'message'=>'Otp send error','data'=>json_decode("{}")]); 
      }
      
    }

    public function verifyEmailOtp(Request $request){

      if(isset($request->email) && isset($request->otp)){
        $userdata = User::where('email',$request->email)->where('email_verification_code',$request->otp)->first();
        if(isset($userdata->id)){
          if($userdata->email_verification_status=='Y'){
            return response()->json(['status'=>0,'message'=>'User already verified','data'=>json_decode("{}")]);             
          }
          $udata = User::findOrFail($userdata->id);
          $udata->email_verification_status = 'Y';
          if($udata->save()){
            return response()->json(['status'=>1,'message'=>'Email verified successfully','data'=>json_decode("{}")]);             
          }

          return response()->json(['status'=>0,'message'=>'DB Error','data'=>json_decode("{}")]);           
        }else{
          return response()->json(['status'=>0,'message'=>'User does not exist','data'=>json_decode("{}")]);           
        }
      }else{
        return response()->json(['status'=>0,'message'=>'invalid input data','data'=>json_decode("{}")]); 
      }
      
     
    }

}