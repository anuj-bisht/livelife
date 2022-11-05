<?php
    
namespace App\Http\Controllers;
    
use App\Diet;
use App\User;
use App\Notification;
use Illuminate\Http\Request;
use App\Classes\UploadFile;
use App\Http\Controllers\Traits\SendMail;
use App\Http\Controllers\Traits\Common;

    
class DietController extends Controller
{ 
    use SendMail,Common;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:diet-list', ['only' => ['index','show']]);
         $this->middleware('permission:diet-list|diet-create|diet-edit|diet-delete', ['only' => ['index','show']]);
         $this->middleware('permission:diet-create', ['only' => ['create','store']]);
         $this->middleware('permission:diet-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:diet-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
              
        return view('diets.index',[]);

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
            $obj = Diet::select('diets.*','users.name as user_name')->whereRaw('1 = 1')
            ->join('users','users.id','=','diets.user_id');
                    
            
            if ($request->search['value'] != "") {            
              $obj = $obj->where('diets.name','LIKE',"%".$search."%");
            } 
            
            if(isset($request->order[0]['column']) && $request->order[0]['column']==0){
                $sort = $request->order[0]['dir'];
                $obj = $obj->orderBy('diets.name',$sort);
            }


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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {        
              
        $user_list = User::getClientList();        
        return view('diets.create',compact('user_list'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      
        request()->validate([
            'name' => 'required|unique:diets,name'
        ]);
       
        //print_r($request->all()); die;
        $obj = new Diet();
        

        if(isset($_FILES['file_veg']['name'])) {                      
              $upload_handler = new UploadFile();
              $path = public_path('uploads/diets'); 
              $data = $upload_handler->uploadByName($path,'file_veg','diets');
              $res = json_decode($data);           
              
              if($res->status=='ok'){
                
                $obj->vegeterian = $res->path;
                $obj->vegeterian_path = $res->img_path;                                                 
              }                                                                   
          }     

          if(isset($_FILES['file_nonveg']['name'])) {                      
              $upload_handler = new UploadFile();
              $path = public_path('uploads/diets'); 
              $data = $upload_handler->uploadByName($path,'file_nonveg','diets');
              $res = json_decode($data);           
              
              if($res->status=='ok'){                
                $obj->non_vegeterian = $res->path;
                $obj->non_vegeterian_path = $res->img_path;                                                 
              }                                                                   
          }   

          if(isset($_FILES['file_egg']['name'])) {                      
              $upload_handler = new UploadFile();
              $path = public_path('uploads/diets'); 
              $data = $upload_handler->uploadByName($path,'file_egg','diets');
              $res = json_decode($data);           
              
              if($res->status=='ok'){                
                $obj->eggeterian = $res->path;
                $obj->eggeterian_path = $res->img_path;                                                 
              }                                                                   
          } 

        
        $obj->name = $request->name;
        $obj->user_id = $request->user_id;
        if($obj->save()){
          $userdata = User::getUserById($request->user_id);
          
          if(isset($userdata->email)){
            $data = [];
            $data['to_email'] = $userdata->email;      
            $data['from'] = config('app.MAIL_FROM_ADDRESS');                  
            $data['subject'] = 'Diet chart assigned';
            $data['name'] = $userdata->name;
            $data['message1'] = 'Your diet chart is assened by your trainer. Please go to app and see the diet chart.';
            //$this->SendMail($data,'dietchart'); 
          }

          
          if(isset($userdata->device_id)){
            $diviceIds = [];
            $diviceIds[] = $userdata->device_id;           
            $this->sendNotification($diviceIds,'','Diet chart assigned','Diet chart is assined to you by admin.');
          }
          
          return view('diets.index',[]);
        }else{
            $request->session()->flash('message.level', 'error');
            $request->session()->flash('message.content', 'Not able to save');
            return redirect('admin/diets/create');
        }
        
        
    }
    

     /**
     * Display the specified resource.
     *
     * @param  \App\category_expence  $category_expence
     * @return \Illuminate\Http\Response
     */
    public function show(Diet $diet)
    {
        return view('diets.show',compact('diet'));
    }
    

        
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\category_expence  $category_expence
     * @return \Illuminate\Http\Response
     */
    public function edit(Diet $diet)
    {      
        $user_list = User::getClientList();    
        //echo '<pre>';print_r($plan->name); die;
        return view('diets.edit',compact('diet','user_list'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\category_expence  $category_expence
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Diet $diet)
    {
        // dd('hello');
        $diet = Diet::findOrFail($request->id);
         request()->validate([
            'name' => 'required|unique:diets,name,'.$diet->id,
            'user_id'=>'required',
        ]);


        if(isset($_FILES['file_veg']['name'])) {                      
            $upload_handler = new UploadFile();
            $path = public_path('uploads/diets'); 
            $data = $upload_handler->uploadByName($path,'file_veg','diets');
            $res = json_decode($data);           
            
            if($res->status=='ok'){
              if(file_exists($diet->vegeterian_path)){
                unlink($diet->vegeterian_path);
              }
              $diet->vegeterian = $res->path;
              $diet->vegeterian_path = $res->img_path;                                                 
            }                                                                   
        }     

        if(isset($_FILES['file_nonveg']['name'])) {                      
            $upload_handler = new UploadFile();
            $path = public_path('uploads/diets'); 
            $data = $upload_handler->uploadByName($path,'file_nonveg','diets');
            $res = json_decode($data);           
            
            if($res->status=='ok'){
              if(file_exists($diet->non_vegeterian_path)){
                unlink($diet->non_vegeterian_path);
              }
              $diet->non_vegeterian = $res->path;
              $diet->non_vegeterian_path = $res->img_path;                                                 
            }                                                                   
        }   

        if(isset($_FILES['file_egg']['name'])) {                      
            $upload_handler = new UploadFile();
            $path = public_path('uploads/diets'); 
            $data = $upload_handler->uploadByName($path,'file_egg','diets');
            $res = json_decode($data);           
            
            if($res->status=='ok'){
              if(file_exists($diet->eggeterian_path)){
                unlink($diet->eggeterian_path);
              }
              $diet->eggeterian = $res->path;
              $diet->eggeterian_path = $res->img_path;                                                 
            }                                                                   
        }   

        $diet->name = $request->name;        
        $diet->user_id = $request->user_id;      
        // dd($diet);
        $diet->update();

        $userdata = User::getUserById($request->user_id);
        if(isset($userdata->email)){
          $data = [];
          $data['to_email'] = $userdata->email;      
          $data['from'] = config('app.MAIL_FROM_ADDRESS');                  
          $data['subject'] = 'Diet chart updated';
          $data['name'] = $userdata->name;
          $data['message1'] = 'Your diet chart is updated by your trainer. Please go to app and see the diet chart.';
          $this->SendMail($data,'dietchart'); 

          $suscription_title = "Diet chart updated";
          $suscription_msg = "Your diet chart is updated by your trainer. Please go to app and see the diet chart.";
          
          $notification = [];
          $notification['title'] = $suscription_title;
          $notification['message'] = $suscription_msg;
          $notification['user_id'] = $userdata->id;
          $notification['type'] = 'Normal';                
          Notification::saveNotification($notification);


        }

        if(isset($userdata->device_id)){
          $diviceIds = [];
          $diviceIds[] = $userdata->device_id; 
          $this->sendNotification($diviceIds,'','Diet chart updated','Your diet chart is updated by admin.');
        }
    
        return redirect()->route('diets.index')
                        ->with('success','Diet updated successfully');
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CategoryExpence  $CategoryExpence
     * @return \Illuminate\Http\Response
     */
    public function destroy(Diet $diet)
    {
        
        $diet->delete();
    
        return redirect()->route('diets.index')
                        ->with('success','Diet deleted successfully');
    }

    
}
