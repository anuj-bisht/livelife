<?php
    
namespace App\Http\Controllers;
    
use App\Chat;
use App\User;
use Illuminate\Http\Request;
use App\Classes\UploadFile;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Traits\Common;
use Auth;
    
class ChatController extends Controller
{ 
    use Common;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        //  $this->middleware('permission:gdiet-list', ['only' => ['index','show']]);
        //  $this->middleware('permission:gdiet-list|gdiet-create|gdiet-edit|gdiet-delete', ['only' => ['index','show']]);
        //  $this->middleware('permission:gdiet-create', ['only' => ['create','store']]);
        //  $this->middleware('permission:gdiet-edit', ['only' => ['edit','update']]);
        //  $this->middleware('permission:gdiet-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $chatuser = Chat::getUserList();      
        //dd($chatuser);
        return view('chats.index',compact('chatuser'));

    }
    
    public function getChatById(Request $request)
    {
        try{

          $chatuser = Chat::getChat($request->id);    
                    
          Chat::where('from_id', $request->id)            
            ->update(['read_status'=>'Y']); 

          return response()->json(['status'=>1,'message'=>'','data'=>$chatuser]);
        }catch(Exception $e){
          return response()->json(['status'=>0,'message'=>'Not able to get chat','data'=>json_decode("{}")]);
        }
        
    }

    public function addChat(Request $request){
    
      try{                
        $status = 0;
        $message = "";
        $user_id = Auth::user()->id;

        $id = $request->user_id;
        if(!isset($user_id)){
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

        
        $inputs = ['from_id' => $user_id,'message' => $request->message,"to_id"=>$id];

        Chat::create($inputs);
        $userData = User::findOrFail($request->user_id);

        if(isset($userData->device_id) && $userData->device_id!=""){
            $diviceIds = [$userData->device_id];
            //$this->sendNotification($diviceIds,'','New chat response',$request->message);
        }        

        return response()->json(['status'=>1,'message'=>'Message sent','data'=>json_decode("{}")]);
      }catch(Exception $e){
        return response()->json(['status'=>0,'message'=>'Not able to add','data'=>json_decode("{}")]);
      }
      
    }

    public function getUnReadChat(Request $request){
      try{
          $chatuser = Chat::getUnReadChat();    
          return response()->json(['status'=>1,'message'=>'','data'=>$chatuser]);
      }catch(Exception $e){
        return response()->json(['status'=>0,'message'=>'Not able to add','data'=>json_decode("{}")]);
      }
    }


    public function getClietChat(Request $request)
    {
        try{

          if (Auth::check()){
              $id = Auth::user()->id;
          }   
                    
          $chat_data = Chat::where('from_id', $id)->orWhere('to_id',$id)->orderBy('id','asc')->get(); 

          return response()->json(['status'=>1,'message'=>'','data'=>$chat_data]);
        }catch(Exception $e){
          return response()->json(['status'=>0,'message'=>'Not able to get chat','data'=>json_decode("{}")]);
        }
        
    }

    public function markread(Request $request){
      try{

        if (Auth::check()){
            $id = Auth::user()->id;
        }   
                  
        Chat::where('from_id', $id)->orWhere('to_id',$id)->update(['read_status'=>'Y']); 

        return response()->json(['status'=>1,'message'=>'','data'=>[]]);
      }catch(Exception $e){
        return response()->json(['status'=>0,'message'=>'Not able to get chat','data'=>json_decode("{}")]);
      }
    }

    public function clientSubmitChat(Request $request){
    
      try{                
        $status = 0;
        $message = "";
        $user_id = Auth::user()->id;
        $adminuser = User::getAdminUser();
        if(isset($request->msg) && $request->msg!="" && isset($adminuser->id)){
          $inputs = ['from_id' => $user_id,'message' => $request->msg,"to_id"=>$adminuser->id];
          Chat::create($inputs);        
        }
                     
        return response()->json(['status'=>1,'message'=>'Message sent','data'=>json_decode("{}")]);
      }catch(Exception $e){
        return response()->json(['status'=>0,'message'=>'Not able to add','data'=>json_decode("{}")]);
      }
      
    }


    

    
    
}
