<?php

namespace App\Http\Controllers\v1;

use App\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Controllers\Traits\SendMail;
use Config;
use App\Common\Utility;
use App\Classes\UploadFile;
use Mail;

use TwilioRestClient;
use TwilioJwtAccessToken;
use TwilioJwtGrantsVideoGrant;
use Twilio\Jwt\AccessToken;
use Twilio\Jwt\Grants\VideoGrant;
use Twilio\Rest\Client;



class VideoController extends Controller
{
  
      
  protected $sid;
	protected $token;
	protected $key;
	protected $secret;

	public function __construct()
	{
	   $this->sid = config('services.twilio.sid');
	   $this->token = config('services.twilio.token');
	   $this->key = config('services.twilio.key');
	   $this->secret = config('services.twilio.secret');
  }

  public function getAccessToken(Request $request){
     
	 $user  = JWTAuth::user();
	 $status= 0;
	 $message = "";
	 
	 if(!isset($user->name)){
		return response()->json(["status"=>1,"message"=>'Invalid token',"data"=>json_decode("{}")]);	 
	 }

	 
	 
	 $identity = $user->name;
	 
	 $validator = Validator::make($request->all(), [
		'room_name' => 'required',
		'category_id' => 'required'
	 ]);
	$params = [];  
	if($validator->fails()){
	$error = json_decode(json_encode($validator->errors()));
	if(isset($error->room_name[0])){
		$message = $error->room_name[0];
	}else if(isset($error->category_id[0])){
		$message = $error->category_id[0];
	}
	return response()->json(["status"=>$status,"message"=>$message,"data"=>json_decode("{}")]);
	}

	$sche_type = (isset($request->type)) ? $request->type : 'schedule';
  $category_id = $request->category_id;
	$chk_schdule = Schedule::checkScheduleByUserId($user->id,$request->room_name,$sche_type,$category_id);
	if(!isset($chk_schdule->id)){
		return response()->json(["status"=>0,"message"=>'You can not join class before time/schedule',"data"=>json_decode("{}")]);
	}

	$roomName = $request->room_name;

	//\Log::debug("joined with identity: $identity");
	$token = new AccessToken($this->sid, $this->key, $this->secret, 3600, $identity);		
	$videoGrant = new VideoGrant();
	$videoGrant->setRoom($roomName);
    $access_token = strval($token->addGrant($videoGrant));     
	//string$access_token

    return response()->json(["status"=>1,"message"=>'',"data"=>$access_token]);
	}
	

	public function access_token(Request $request){
     
	
	$validator = Validator::make($request->all(), [
	 'roomName' => 'required',
	 'identity' => 'required'
	]);
 

	$roomName = $request->roomName;
	$identity = $request->identitity;

	//\Log::debug("joined with identity: $identity");
	$token = new AccessToken($this->sid, $this->key, $this->secret, 3600, $identity);		
	$videoGrant = new VideoGrant();
	$videoGrant->setRoom($roomName);
	$token->addGrant($videoGrant);     
	echo $token->toJWT(); 
	//echo $access_token = strval($token->addGrant($videoGrant));
	 
 }
  

}