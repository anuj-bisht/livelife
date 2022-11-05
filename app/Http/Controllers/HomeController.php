<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Demorequest;
use App\Testrequest;
use Illuminate\Support\Facades\Auth;
use App\Schedule;
use App\Subscription;
use App\User;
use App\Category;
use App\Order;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
		$new_order = Order::getNewOrder(true);
		$failed_payment = Order::getFailedOrder(true);
        $demorequest = Demorequest::getTrainerDemoRequest(Auth::user()->id,'today');        
		$testrequest = Testrequest::getTrainerTestRequest(Auth::user()->id,'today'); 
		
		$rescheduleRequest = Schedule::getRescheduleRequest();        
		
		$clients = User::getClientList()->count() ?? 0;
		$newTestRequest = Testrequest::getNewTestRequest()->count() ?? 0;
		$newDemoRequest = Demorequest::getNewDemoRequest()->count() ?? 0;
		$getTotalSubs = Subscription::getSubsTotal()->count() ?? 0;

		$expiredSubs = Subscription::getExpiredSubs()->count() ?? 0;
		$newUsers = User::where('is_inquiry','0')->count();
		
       //dd($testrequest);
		$schedules = Schedule::getTrainerScheduleToday(Auth::user()->id,'today',0);
		$slots = Schedule::getTrainerSlot(Auth::user()->id,'today');
		// $users = Schedule::getUsersSlot(Auth::user()->id,'today','37');
		// dd($users);
		$date = date('Y-m-d');
		$like_date = mb_substr($date, 0, 8);
		$sc_array = '[';
		
		$schedules_distinct = Schedule::getTrainerScheduleOfMonth(Auth::user()->id,$date,$like_date)->toArray();
		$test_distinct = Testrequest::getTrainerTestRequestOfMonth(Auth::user()->id,$date,$like_date)->toArray();
		$demo_distinct = Demorequest::getTrainerDemoRequestOfMonth(Auth::user()->id,$date,$like_date)->toArray();
		
		$array_intesect = array_unique(array_merge($schedules_distinct,$test_distinct,$demo_distinct));
		//dd($array_intesect);
		$count = count($array_intesect);
		$c = 0;
		foreach($array_intesect as $k=>$schedule){
			$key = $c+1;
			if($key < $count)
				$comma = ',';
			else
				$comma = '';
			//$sc_array[] = array("title"=>"Event".($k+1),"start"=>$schedule);
			$sc_array .= "{ title: 'Event',start:'$schedule',backgroundColor: '#80ACED' }$comma";
			$c++;
			//dd($schedule);
		}
		$sc_array .= ']';

		
		$user = new User();
		$roles = $user->getUserRole(Auth::user()->id);
		$categories = [];
		$trainers = User::getTrainerList();
		$trainersCount = ($trainers->count()) ? $trainers->count() : 0;
		
		if($roles->name == 'Trainer'){
			$catList = Category::getUserCatList(Auth::user()->id);
			$users = User::getClientList();
			
			$categories = [];
			return view('trainer.home',compact('demorequest','testrequest','schedules','catList','users','categories','trainers','sc_array','roles','slots'));
		}else if($roles->name == 'Client'){
			$demorequest = Demorequest::getClientDemoRequest(Auth::user()->id,'today');        
        $testrequest = Testrequest::getClientTestRequest(Auth::user()->id,'today'); 
		$schedules = Schedule::getClientScheduleToday(Auth::user()->id,'today',0);
		$slots = Schedule::getClientSlot(Auth::user()->id,'today');
		$catList = Category::getUserCatList(Auth::user()->id);
		$users = User::getClientList();
		$date = date('Y-m-d');
		$like_date = mb_substr($date, 0, 8);
		$sc_array = '[';
		
		$schedules_distinct = Schedule::getClientScheduleOfMonth(Auth::user()->id,$date,$like_date)->toArray();
		$test_distinct = Testrequest::getClientTestRequestOfMonth(Auth::user()->id,$date,$like_date)->toArray();
		$demo_distinct = Demorequest::getClientDemoRequestOfMonth(Auth::user()->id,$date,$like_date)->toArray();
		$array_intesect = array_unique(array_merge($schedules_distinct,$test_distinct,$demo_distinct));
		
		$count = count($array_intesect);
		$c = 0;
		foreach($array_intesect as $k=>$schedule){
			$key = $c+1;
			if($key < $count)
				$comma = ',';
			else
				$comma = '';
			//$sc_array[] = array("title"=>"Event".($k+1),"start"=>$schedule);
			$sc_array .= "{ title: 'Event',start:'$schedule',backgroundColor: '#80ACED' }$comma";
			$c++;
			//dd($schedule);
		}
		$sc_array .= ']';
			return view('client.home',compact('demorequest','rescheduleRequest','testrequest','schedules','catList','users','categories','trainers','sc_array','roles','slots'));

		}
		else
		{	
			return view('home',compact('demorequest','testrequest','rescheduleRequest','roles','new_order','failed_payment','clients','newTestRequest','newDemoRequest','getTotalSubs','expiredSubs','trainersCount','newUsers'));

		}
        
        
    }

	public function getUsersBySlot($slot_id)
	{
		$users = Schedule::getUsersSlot(Auth::user()->id,'today',$slot_id);
		if($users)
		{
			return response()->json(['status'=>1,'message'=>'Success','data'=>$users]);

		}
		else{
			return response()->json(['status'=>0,'message'=>'Data Not Found','data'=>""]);


		}
		// dd($users);
	}
}
