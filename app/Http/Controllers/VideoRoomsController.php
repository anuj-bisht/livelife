<?php 
	
   namespace App\Http\Controllers;
      
   use Illuminate\Http\Request;
   use TwilioRestClient;
   use TwilioJwtAccessToken;
   use TwilioJwtGrantsVideoGrant;
   use App\Http\Requests;
   use Auth;
   use Twilio\Jwt\AccessToken;
   use Twilio\Jwt\Grants\VideoGrant;

   

   use Twilio\Rest\Client;
   

class VideoRoomsController extends Controller
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
	
	public function index()
	{
		
	   $rooms = [];
	   try {
		   $client = new Client($this->sid, $this->token);
		   $allRooms = $client->video->rooms->read([]);

			$rooms = array_map(function($room) {
			   return $room->uniqueName;
			}, $allRooms);

	   } catch (Exception $e) {
		   echo "Error: " . $e->getMessage();
	   }
	   return view('rooms.index', ['rooms' => $rooms]);
	}
	
	public function createRoom(Request $request){
		
	   $client = new Client($this->sid, $this->token);

	   $exists = $client->video->rooms->read([ 'uniqueName' => $request->roomName]);

	   if (empty($exists)) {
		   $client->video->rooms->create([
			   'uniqueName' => $request->roomName,
			   'type' => 'group',
			   'recordParticipantsOnConnect' => false
		   ]);

		   \Log::debug("created new room: ".$request->roomName);
	   }

	   return redirect()->action('VideoRoomsController@joinRoom', [
		   'roomName' => $request->roomName
	   ]);
	}
	
	public function joinRoom($roomName)
	{
	   // A unique identifier for this user
	   $identity = Auth::user()->name;

	   \Log::debug("joined with identity: $identity");
	   $token = new AccessToken($this->sid, $this->key, $this->secret, 3600, $identity);
		
	   $videoGrant = new VideoGrant();
	   $videoGrant->setRoom($roomName);

	   $token->addGrant($videoGrant); 

	   return view('rooms.room', [ 'accessToken' => $token->toJWT(), 'roomName' => $roomName ]);
	}

	public function videoRegularRoom(Request $request){
		$client = new Client($this->sid, $this->token);
		$roomName = 	$request->room_name;
	   $exists = $client->video->rooms->read([ 'uniqueName' => $roomName]);

	   if (empty($exists)) {
		   $client->video->rooms->create([
			   'uniqueName' => $roomName,
			   'type' => 'group',
			   'recordParticipantsOnConnect' => false
		   ]);

		   \Log::debug("created new room: ".$roomName);
	   }
	   $identity = Auth::user()->name;

	   \Log::debug("joined with identity: $identity");
	   $token = new AccessToken($this->sid, $this->key, $this->secret, 3600, $identity);
		
	   $videoGrant = new VideoGrant();
	   $videoGrant->setRoom($roomName);

	   $token->addGrant($videoGrant); 
	    return response()->json(["accessToken"=>$token->toJWT(),"roomName"=>$roomName]);
	}

	public function videoDemoRoom(Request $request){
		$client = new Client($this->sid, $this->token);
		$roomName = 	$request->room_name;
	   $exists = $client->video->rooms->read([ 'uniqueName' => $roomName]);

	   if (empty($exists)) {
		   $client->video->rooms->create([
			   'uniqueName' => $roomName,
			   'type' => 'group',
			   'recordParticipantsOnConnect' => false
		   ]);

		   \Log::debug("created new room: ".$roomName);
	   }
	   $identity = Auth::user()->name;

	   \Log::debug("joined with identity: $identity");
	   $token = new AccessToken($this->sid, $this->key, $this->secret, 3600, $identity);
		
	   $videoGrant = new VideoGrant();
	   $videoGrant->setRoom($roomName);

	   $token->addGrant($videoGrant); 
	    return response()->json(["accessToken"=>$token->toJWT(),"roomName"=>$roomName]);
	}

	public function videoTestRoom(Request $request){
		$client = new Client($this->sid, $this->token);
		$roomName = 	$request->room_name;
	   $exists = $client->video->rooms->read([ 'uniqueName' => $roomName]);

	   if (empty($exists)) {
		   $client->video->rooms->create([
			   'uniqueName' => $roomName,
			   'type' => 'group',
			   'recordParticipantsOnConnect' => false
		   ]);

		   \Log::debug("created new room: ".$roomName);
	   }
	   $identity = Auth::user()->name;

	   \Log::debug("joined with identity: $identity");
	   $token = new AccessToken($this->sid, $this->key, $this->secret, 3600, $identity);
		
	   $videoGrant = new VideoGrant();
	   $videoGrant->setRoom($roomName);

	   $token->addGrant($videoGrant); 
	    return response()->json(["accessToken"=>$token->toJWT(),"roomName"=>$roomName]);
	}

	public function connectRoom(Request $request){
		$roomName = 	$request->room_name;
		$identity = Auth::user()->name;

	   \Log::debug("joined with identity: $identity");
	   $token = new AccessToken($this->sid, $this->key, $this->secret, 3600, $identity);
		
	   $videoGrant = new VideoGrant();
	   $videoGrant->setRoom($roomName);

	   $token->addGrant($videoGrant); 
	    return response()->json(["accessToken"=>$token->toJWT(),"roomName"=>$roomName]);
	}
	
	public function joinRegularConfress($slug){
		return view('rooms.regular_room', [ 'roomName' => $slug]);
	}

	public function joinDemoConfress($slug){
		return view('rooms.demo_room', [ 'roomName' => $slug]);
	}

	public function joinTestConfress($slug){
		return view('rooms.test_room', [ 'roomName' => $slug]);
	}
}
