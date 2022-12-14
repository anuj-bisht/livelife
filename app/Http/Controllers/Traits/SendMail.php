<?php 
namespace App\Http\Controllers\Traits; 
use Mail;
use Config;
use App\Notification;
use DB;
use App\User;

trait SendMail { 
    protected function SendMail($data,$template='message') {     
        
        try{
            Mail::send($template,$data, function($newObj) use($data)
            {
                
                $newObj->from($data['from'], $data['name']);
                $newObj->subject($data['subject']);
                $newObj->to($data['to_email']);         
                
                if(isset($data['cc'])){
                  $newObj->cc($data['cc']);
                }
                if(isset($data['bcc'])){
                  $newObj->bcc($data['bcc']);
                }   

                if(isset($data['file'])){
                  $newObj->attach($data['file'], [
                    'as' => $data['file'],
                    'mime' => 'application/pdf',
                  ]);
                }                
            });
            return true;
          }catch(Exception $e){
            return false;
          }  
    } 

    protected function SendSms($phone,$message) {     
        try{
            $url = "https://api.msg91.com/api/sendhttp.php?mobiles=$phone&authkey=298624AWJzQa0Z8n5da2dd16&route=4&sender=TRFVFT&message=$message&country=91";
            if(file_get_contents($url)){
              return true;
            }              
          }catch(Exception $e){
            return false;
          }  
    } 

    protected function SendMsgNotification($notification_id,$users,$dataArray) {     
        try{
           if(isset($notification_id) && count($users)){
                foreach($users as $k=>$v){
                    $data[$k]['notification_id'] = $notification_id;   
                    $data[$k]['user_id'] = $v;
                    $data[$k]['obj_data'] = serialize($dataArray);
                }
                
                DB::table('notification_users')->insert($data); 
           }
            return true;
          }catch(Exception $e){
            return false;
          }  
    } 

    public function sendMessage($type,$dataArray,$site_id){
        
      try{
        $result = Notification::getDataByItemCode($type,$site_id);
        
        //print_r($result); die;
        if($result->count()){

            $notification_id = $result[0]->id; 
            $email_subject = $result[0]->subject; 
            $email_message = $result[0]->message; 
            $mobile_message = $result[0]->mobile_message; 
            $notification_message = $result[0]->notification_message; 
            $phoneArr = [];
            $emailArr = [];
            $idArr = [];
            $nameArr = [];
            foreach($result as $k=>$v){
              if(!empty($v->phone) && $v->sms_notification=='1'){
                $phoneArr[] = $v->phone;
              }
              if(!empty($v->email) && $v->email_notification=='1'){
                $emailArr[] = $v->email;
              }
              if(!empty($v->user_id)){
                $idArr[] = $v->user_id;
              }
            }
            
            if(Config('app.email_message')){
                
                $data = [];          
                $data['name'] = 'Member';
                $data['to_email'] = $emailArr;
                //print_r($data['to_email']); die;
                //$data['cc'] = 'hemant.gupta@techconfer.com';                          
                $data['from'] = config('app.from_email');                
                $data['subject'] = $email_subject; 
                $data['message1'] = $email_message;   
                $data['info_data'] = $dataArray;
                $this->SendMail($data,'message');
            }
            if(config('app.mobile_message')){              
              $this->SendSms(implode(",",$phoneArr),$mobile_message);
            }
            if(config('app.notification_message')){              
              $this->SendMsgNotification($notification_id,$idArr,$dataArray);
            }
        }
      }catch(Exception $e){			
            return response()->json(['status'=>$status,'message'=>$message,'data'=>json_decode("{}")]);    
      } 
      
  }
  
  protected function sendMailOtp($email,$res=false){
    // dd($res);
    try{
      // $result = User::getUserByEmail($email);
      
      // //print_r($result); die;
      // if($result->count()){
          
          $otp = uniqid();
          // $userdata = User::findOrFail($result->id);
          // $userdata->email_verification_status = 'N';
          // $userdata->email_verification_code = $otp;
          $data = [];          
          $data['name'] = $email;
          $data['to_email'] = $email;          
          $data['from'] = config('app.from_email');                
          $data['subject'] = 'Email OTP Verification'; 
          $data['message1'] = "Please click below link to verify your OTP";   
          $data['otp'] = $res;
          $this->SendMail($data,'mail_otp');
          if($res){
            return true;
          }
          if($userdata->save()){
            return response()->json(['status'=>1,'message'=>'Otp sent','data'=>json_decode("{}")]);              
          }else{
            return response()->json(['status'=>0,'message'=>'Error sending otp','data'=>json_decode("{}")]);              
          }
          
      // }
    }catch(Exception $e){			
          return response()->json(['status'=>$status,'message'=>$message,'data'=>json_decode("{}")]);    
    } 
  }
}
