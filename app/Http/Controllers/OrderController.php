<?php
    
namespace App\Http\Controllers;
    
use App\Order;
use App\Slot;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Classes\UploadFile;
use App\Notification;


    
class OrderController extends Controller
{ 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        //  $this->middleware('permission:tip-list', ['only' => ['index','show']]);
        //  $this->middleware('permission:tip-list|tip-create|tip-edit|tip-delete', ['only' => ['index','show']]);
        //  $this->middleware('permission:tip-create', ['only' => ['create','store']]);
        //  $this->middleware('permission:tip-edit', ['only' => ['edit','update']]);
        //  $this->middleware('permission:tip-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
              
        return view('orders.index',[]);

    }
    
    public function ajaxData(Request $request){
    
        $draw = (isset($request->data["draw"])) ? ($request->data["draw"]) : "1";
        $response = [
          "recordsTotal" => "",
          "recordsFiltered" => "",
          "data" => "",
          "success" => 0,
          "msg" => ""
        ];
        try {
            
            $start = (isset($request->start)) ? $request->start : 0;
            $end = ($request->length) ? $request->length : 10;
            $search = ($request->search['value']) ? $request->search['value'] : '';
            //echo 'ddd';die;
            $cond[] = [];
            
            //echo '<pre>'; print_r($users); die; categoryFilter
            $obj = Order::select('orders.*','users.name as username',
            'plans.name as plan_name','categories.name as category_name')
            ->join('users','users.id','=','orders.user_id')
            ->join('plans','plans.id','=','orders.plan_id')
            ->join('categories','categories.id','=','plans.category_id')
            ->where('orders.subscription_id','<>',0)
            ->where('orders.scheduled','N');
                    
            
            // if ($request->search['value'] != "") {            
            //   $obj = $obj->where('orders.name','LIKE',"%".$search."%");
            // } 
            
            // if(isset($request->order[0]['column']) && $request->order[0]['column']==0){
            //     $sort = $request->order[0]['dir'];
            //     $obj = $obj->orderBy('tips.name',$sort);
            // }


            $total = $obj->count();
            if($end==-1){
              $obj = $obj->get();
            }else{
              $obj = $obj->skip($start)->take($end)->get();
            }
            
            $response["recordsFiltered"] = $total;
            $response["recordsTotal"] = $total;
            //response["draw"] = draw;
            $response["success"] = 1;
            $response["data"] = $obj;
            
          } catch (Exception $e) {    
   
          }
        
   
        return response($response);
      }

      public function getorderbyid(Request $request){
        try{
          $status = 0;
          $message = "";
                
          //$user  = JWTAuth::user();
          $validator = Validator::make($request->all(), [
            'id' => 'required'
          ]);
  
          $slots = [];
          if($validator->fails()){
            $error = json_decode(json_encode($validator->errors()));
            if(isset($error->category_id[0])){
              $message = $error->category_id[0];
            }
            return response()->json(["status"=>$status,"message"=>"Invalid data set","data"=>json_decode("{}")]);
          }
          $data = Order::getOrderById($request->id);
          
          if(isset($data->id)){
            $slots = Slot::getSlotById($data->category_id);
            $trainers = User::getTrainerList();

            $result = ['order'=>$data,'slots'=>$slots,'trainers'=>$trainers];
            return response()->json(['status'=>1,'message'=>'','data'=>$result]);                    
            
          }else{
            return response()->json(['status'=>$status,'message'=>'No record found','data'=>json_decode("{}")]);                      
          }
             
        }catch(Exception $e){
          return response()->json(['status'=>$status,'message'=>'Error','data'=>json_decode("{}")]);                    
        }
      }

      public function show(Order $order)
      {
          return view('orders.show',compact('order'));
      }

      public function makeSchedule(Request $request){
        try{
          $status = 0;
          $message = "";

           
                
          //$user  = JWTAuth::user();
          $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'trainer_id' => 'required',
            'category_id' => 'required',
            'slot_id' => 'required',
            'plan_id' => 'required'
          ]);
  
          $slots = [];
          if($validator->fails()){
            $error = json_decode(json_encode($validator->errors()));
            if(isset($error->category_id[0])){
              $message = $error->category_id[0];
            }
            return response()->json(["status"=>$status,"message"=>"Invalid data set","data"=>json_decode("{}")]);
          }
          $user_id = $request->user_id;
          $plan_id = $request->plan_id;
          $slot_id = $request->slot_id;
          $trainer_id = $request->trainer_id;
          $category_id = $request->category_id;
        
          $type = $request->type;
          $Tnotification = [];
          $Tnotification['title'] = "Trainner Assign";
          $Tnotification['message'] = "New trainees are assigned for tarinning.";
          $Tnotification['user_id'] = $request->trainer_id;
          $Tnotification['type'] = 'Normal';                
          Notification::saveNotification($Tnotification);
          
          if($this->scheduleClass($user_id,$plan_id,$type,$slot_id,$trainer_id,$category_id)){
            $this->updateOrder($request->order_id);
            return response()->json(['status'=>1,'message'=>'Schedule added successfully','data'=>json_decode("{}")]);                      
          }else{
            return response()->json(['status'=>$status,'message'=>'Error on add','data'=>json_decode("{}")]);                    
          }                

             
        }catch(Exception $e){
          return response()->json(['status'=>$status,'message'=>'Error','data'=>json_decode("{}")]);                    
        }
      }


      public function failed(){              
        return view('orders.failed',[]);
      }

      public function ajaxFailedData(Request $request){
    
        $draw = (isset($request->data["draw"])) ? ($request->data["draw"]) : "1";
        $response = [
          "recordsTotal" => "",
          "recordsFiltered" => "",
          "data" => "",
          "success" => 0,
          "msg" => ""
        ];
        try {
            
            $start = (isset($request->start)) ? $request->start : 0;
            $end = ($request->length) ? $request->length : 10;
            $search = ($request->search['value']) ? $request->search['value'] : '';
            //echo 'ddd';die;
            $cond[] = [];
            
            //echo '<pre>'; print_r($users); die; categoryFilter
            $obj = Order::select('orders.*','users.name as username',
            'plans.name as plan_name','categories.name as category_name','users.email','users.phone')
            ->join('users','users.id','=','orders.user_id')
            ->join('plans','plans.id','=','orders.plan_id')
            ->join('categories','categories.id','=','plans.category_id')
            ->where('orders.subscription_id',0)
            ->where('orders.scheduled','N');
           

            $total = $obj->count();
            if($end==-1){
              $obj = $obj->get();
            }else{
              $obj = $obj->skip($start)->take($end)->get();
            }
            
            $response["recordsFiltered"] = $total;
            $response["recordsTotal"] = $total;
            //response["draw"] = draw;
            $response["success"] = 1;
            $response["data"] = $obj;
            
          } catch (Exception $e) {    
   
          }
        
   
        return response($response);
      }

    
    
}
