<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Http\Controllers\Traits\SendMail;
use App\Http\Controllers\Traits\Common;
use Illuminate\Support\Facades\View;
use PDF;
use App\LevelUser;
use App\Subscription;
use App\Menu;
use App\Plan;
use App\Slot;
use App\Order;
use App\Schedule;
use App\Notification;
use DB;

class Controller extends BaseController
{

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, SendMail, Common;
    
    public function __construct(){
        $menu = array(
            'items' => array(),
            'parents' => array()
        );
        $obj = Menu::where('id','<>', '')->get();

        foreach($obj as $k=>$items){
            $menu['items'][$items->id] = $items;
            $menu['parents'][$items->parent_id][] = $items->id;
        }
        View::share('menuData', $menu);
       //echo '<pre>'; print_r($menu);die;
    }
    
    public $ajaxResponse = ["success"=>false,"msg"=>"","data"=>[]];

    public $chainage_gap = 100;
    public $paging = 10;

    public function updateLevel($data){
        try{
            
            $result = LevelUser::updateLevel($data);
            return $result;
        }catch(Exception $e){			
            return response()->json(['status'=>0,'message'=>'Error','data'=>json_decode("{}")]);    
        }
    }

    public function goToNextLevel($data){
        try{
            
            $result = LevelUser::goToNextLevel($data);
        }catch(Exception $e){			
            return response()->json(['status'=>0,'message'=>'Error','data'=>json_decode("{}")]);    
        }
    }

    

    
    public function getStatesByCountry(Request $request){
        try{
            
            $result = State::getStateByCountry($request->id);                                    
            return response()->json(['status'=>1,'message'=>'','data'=>$result]);    
        }catch(Exception $e){			
            return response()->json(['status'=>0,'message'=>'Error','data'=>json_decode("{}")]);    
        }
    }

    public function scheduleClass($user_id,$plan_id,$stype,$slot_id,$trainer_id,$category_id){
        $plan_data = Plan::getPlanById($plan_id);
        
        $dayArr = $stype;

        $curr_date = date("Y-m-d");        
        $newdate = date("Y-m-d");
        

        $slot_data = Slot::getSlotBySlotId($slot_id);

        if(isset($plan_data->days) && $plan_data->days>0){
            
            $insertArr = "INSERT INTO schedules (user_id,slot_id,trainer_id,category_id,title,start,end) VALUES ";
            for($i=1;$i<=$plan_data->days;$i++){
                $newdate1 = date("N",strtotime($newdate."+ ".$i." days")); 
                if(in_array($newdate1,$dayArr)){
                    $startStr = date("Y-m-d",strtotime($newdate."+ ".$i." days")).' '.$slot_data->start_time; 
                    $endStr = date("Y-m-d",strtotime($newdate."+ ".$i." days")).' '.$slot_data->end_time; 
                    $start = strval($startStr);
                    $end = strval($endStr);
                    $insertArr .= "($user_id,$slot_id,$trainer_id,$category_id,'','$start','$end'),";    
                }                
            }
           $insertArr = substr($insertArr,0,-1); 

            DB::statement($insertArr);
            return true;
        }

    }

    public function scheduleCustom($user_id,$stype,$slot_id,$trainer_id,$category_id,$days,$start){
        
        $dayArr = $stype;
        $curr_date = $start;
        $newdate = $start;
        

        $slot_data = Slot::getSlotBySlotId($slot_id);
        $flag = 0;
        if(isset($days) && $days>0){
            
            $insertArr = "INSERT INTO schedules (user_id,slot_id,trainer_id,category_id,title,start,end) VALUES ";
            for($i=0;$i<=$days;$i++){
                $newdate1 = date("N",strtotime($newdate."+ ".$i." days")); 
                if(in_array($newdate1,$dayArr)){
                    $startStr = date("Y-m-d",strtotime($newdate."+ ".$i." days")).' '.$slot_data->start_time; 
                    $endStr = date("Y-m-d",strtotime($newdate."+ ".$i." days")).' '.$slot_data->end_time; 
                    $start = strval($startStr);
                    $end = strval($endStr);
                    $insertArr .= "($user_id,$slot_id,$trainer_id,$category_id,'','$start','$end'),";    
                    $flag = 1;
                }                
            }
            if($flag){
                $insertArr = substr($insertArr,0,-1); 
                DB::statement($insertArr);
                return true;
            }else{
                return false;
            }
            
        }

    }

    
    public function updateOrder($order_id){
        $order = Order::findOrFail($order_id);
        if(isset($order->id)){
            $order->scheduled = 'Y';
            $order->save();
            return true;
        }
        return false;
    }


    public function convertToMilisecond($timestr){
        //$string = "00:11.546";
        
        
        $time   = explode(":", $timestr);
        //$hour   = $time[0] * 60 * 60 * 1000;
        $minute = (int)$time[0]*60*1000;
        //$mil = explode(".",$time[1]);
        $sec    = (float)$time[1]*1000;
        //$mls = (int)$mil[1];
        $result = $minute + $sec;
        return $result;
    }

    public function convertToTime($input){
        //$input = 4648;
        $uSec = $input % 1000;
        $input = floor($input / 1000);
        $seconds = $input % 60;
        $input = floor($input / 60);
        $minutes = $input % 60;
        $input = floor($input / 60);
        $hour = $input ;
        return sprintf('%02d:%02d:%03d', $minutes, $seconds, $uSec);
    }


    public function subscriptionReminder(Request $request){

        $subs = Subscription::getSubsDue();        
        if($subs->count()){
            foreach($subs as $k=>$v){
                $data = [];
                $data['to_email'] = $v->user_email;      
                $data['from'] = config('app.MAIL_FROM_ADDRESS');                  
                $data['subject'] = 'Subscription end reminder';
                $data['name'] = $v->username;
                $data['message1'] = 'Your subscription is expiring soon. Please make payment asap to continue.';
                $this->SendMail($data,'subsreminder'); 

                $suscription_title = "Subscription end reminder";
                $suscription_msg = "Your subscription is expiring soon. Please make payment asap to continue.";
               
                $notification = [];
                $notification['title'] = $suscription_title;
                $notification['message'] = $suscription_msg;
                $notification['user_id'] = $v->userid;
                $notification['type'] = 'Normal';                
                Notification::saveNotification($notification);
            }

            echo "subscription reminder sent";
            
        }
        
    }

    public function lastreminder(Request $request){

        $minute = 15;
        $diviceIds = [];
        $subs = Schedule::getReminderByMinute($minute,'schedule');        
        $tests = Schedule::getReminderByMinute($minute,'test');                
        $demos = Schedule::getReminderByMinute($minute,'demo');        
        //dd($demos);
        //dd($subs);
        if($subs->count()){
            foreach($subs as $k=>$v){
                $data = [];
                $data['to_email'] = $v->user_email;      
                $data['from'] = config('app.MAIL_FROM_ADDRESS');                  
                $data['subject'] = 'Class Reminder';                
                if(isset($v->trainer_email)){
                    $data['bcc'] = $v->trainer_email; 
                }                
                $data['message1'] = 'Your livefit training class is just going to start in few minutes, Please be ready and see schedule in mobile';
                $data['name'] = $v->username;

                $data['start_time'] = $v->start;                
                $data['category_name'] = $v->category_name;
                $data['trainer_name'] = $v->trainer_name;

                if(isset($v->device_id)){
                    $diviceIds[] = $v->device_id;
                }
                
                $this->SendMail($data,'schedule_reminder'); 

                $suscription_title = "Class Reminder";
                $suscription_msg = "Your livefit training class is just going to start in few minutes, Please be ready and see schedule in mobile";
               
                $notification = [];
                $notification['title'] = $suscription_title;
                $notification['message'] = $suscription_msg;
                $notification['user_id'] = $v->userid;
                $notification['type'] = 'Normal';                
                Notification::saveNotification($notification);
            }            
            
        }

        if($tests->count()){
            foreach($tests as $k=>$v){
                $data = [];
                $data['to_email'] = $v->user_email;      
                $data['from'] = config('app.MAIL_FROM_ADDRESS');                  
                $data['subject'] = 'Test Class Reminder';                
                $data['message1'] = 'Your livefit training assesment is just going to start in few minutes, Please be ready and see schedule in mobile';
                $data['name'] = $v->username;
                if(isset($v->trainer_email)){
                    $data['bcc'] = $v->trainer_email; 
                }
                $data['start_time'] = $v->assign_slot;                
                $data['category_name'] = $v->level_name;
                $data['trainer_name'] = $v->trainer_name;
                
                if(isset($v->device_id)){
                    $diviceIds[] = $v->device_id;
                }
                $this->SendMail($data,'schedule_reminder'); 

                $suscription_title = "Test Class Reminder";
                $suscription_msg = "Your livefit training assesment is just going to start in few minutes, Please be ready and see schedule in mobile";
               
                $notification = [];
                $notification['title'] = $suscription_title;
                $notification['message'] = $suscription_msg;
                $notification['user_id'] = $v->userid;
                $notification['type'] = 'Normal';                
                Notification::saveNotification($notification);

            }            
            
        }

        if($demos->count()){
            foreach($demos as $k=>$v){
                $data = [];
                $data['to_email'] = $v->user_email;      
                $data['from'] = config('app.MAIL_FROM_ADDRESS');                  
                $data['subject'] = 'Demo Class Reminder';                
                $data['message1'] = 'Your livefit demo class is just going to start in few minutes, Please be ready and see schedule in mobile';
                $data['name'] = $v->username;
                if(isset($v->trainer_email)){
                    $data['bcc'] = $v->trainer_email; 
                }
                $data['start_time'] = $v->assign_slot;                
                $data['category_name'] = $v->category_name;
                $data['trainer_name'] = $v->trainer_name;
                
                if(isset($v->device_id)){
                    $diviceIds[] = $v->device_id;
                }

                $this->SendMail($data,'schedule_reminder'); 

                $suscription_title = "Demo Class Reminder";
                $suscription_msg = "Your livefit demo class is just going to start in few minutes, Please be ready and see schedule in mobile";
               
                $notification = [];
                $notification['title'] = $suscription_title;
                $notification['message'] = $suscription_msg;
                $notification['user_id'] = $v->userid;
                $notification['type'] = 'Normal';                
                Notification::saveNotification($notification);

            }            
            
        }

        if(count($diviceIds)){
            $this->sendNotification($diviceIds,'','Schedule Reminder','Your class is just about to start, Please join asap');
        }


        echo "reminder sent";
        
    }

    
}
