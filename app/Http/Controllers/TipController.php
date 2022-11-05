<?php
    
namespace App\Http\Controllers;
    
use App\Tip;
use Illuminate\Http\Request;
use App\Classes\UploadFile;

    
class TipController extends Controller
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
              
        return view('tips.index',[]);

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
            $obj = Tip::where('id','<>',0);
                    
            
            if ($request->search['value'] != "") {            
              $obj = $obj->where('tips.name','LIKE',"%".$search."%");
            } 
            
            if(isset($request->order[0]['column']) && $request->order[0]['column']==0){
                $sort = $request->order[0]['dir'];
                $obj = $obj->orderBy('tips.name',$sort);
            }
            if(isset($request->order[0]['column']) && $request->order[0]['column']==2){
                $sort = $request->order[0]['dir'];
                $obj = $obj->orderBy('tips.daynum',$sort);
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
                           
        return view('tips.create',[]);
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
            'name' => 'required|unique:tips,name'
        ]);
       
        //print_r($request->all()); die;
        $obj = new Tip();
        

        // if(isset($_FILES['file']['name']) && count($_FILES['file']['name'])>0) {
        //   for($k=0; $k < count($_FILES['file']['name']); $k++) {
        //     $upload_handler = new UploadFile();
        //     $path = public_path('uploads/tips'); 
        //     $data = $upload_handler->multiUpload($k,$path,'tips');
        //     $res = json_decode($data);
        //     if($res->status=='ok'){    
        //       if($k==0){
        //         $obj->vegeterian = $res->path;
        //         $obj->vegeterian_path = $res->img_path;                
        //       }
        //       if($k==1){
        //         $obj->non_vegeterian = $res->path;
        //         $obj->non_vegeterian_path = $res->img_path;                
        //       }
        //       if($k==2){
        //         $obj->eggeterian = $res->path;
        //         $obj->eggeterian_path = $res->img_path;                
        //       }            
              
        //     }
        //   }
        // }

        
        $obj->name = $request->name;        
        $obj->description = $request->description;
        $obj->daynum = $request->daynum;

        if($obj->save()){
          return view('tips.index',[]);
        }else{
            $request->session()->flash('message.level', 'error');
            $request->session()->flash('message.content', 'Not able to save');
            return redirect('admin/tips/create');
        }
        
        
    }
    

     /**
     * Display the specified resource.
     *
     * @param  \App\category_expence  $category_expence
     * @return \Illuminate\Http\Response
     */
    public function show(Tip $tip)
    {
        return view('tips.show',compact('tip'));
    }
    

        
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\category_expence  $category_expence
     * @return \Illuminate\Http\Response
     */
    public function edit(Tip $tip)
    {      
        return view('tips.edit',compact('tip'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\category_expence  $category_expence
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tip $tip)
    {
        
        $tip = Tip::findOrFail($request->id);
         request()->validate([
            'name' => 'required|unique:tips,name,'.$tip->id,
        ]);

        $tip->name = $request->name;        
        $tip->description = $request->description;      
        $tip->daynum = $request->daynum;   
    
        $tip->update();
    
        return redirect()->route('tips.index')
                        ->with('success','tip updated successfully');
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CategoryExpence  $CategoryExpence
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tip $tip)
    {
        $tip->delete();
    
        return redirect()->route('tips.index')
                        ->with('success','tip deleted successfully');
    }

    public function makedefault(Request $request)
    {
        Tip::query()->update(['default' => '0']);
        $tip = Tip::find($request->id);
        $tip->default = '1';
        if($tip->save()){
          return response()->json(['status'=>1,'message'=>'Added successfully','data'=>[]]); 
        }else{
          return response()->json(['status'=>0,'message'=>'Error','data'=>[]]); 
        }
    }

    public function maketodaystip(Request $request)
    {
        Tip::query()->update(['default' => '0']);
        
        $currDay =  date('d');
        $tip = Tip::where('daynum',$currDay)->first();
        if(isset($tip->id)){
          $tip->default = '1';
        }else{
          $tip = Tip::where('id','<>','0')->inRandomOrder()->first();        
          $tip->default = '1';
        }
                
        if($tip->save()){
          return response()->json(['status'=>1,'message'=>'Added successfully','data'=>[]]); 
        }else{
          return response()->json(['status'=>0,'message'=>'Error','data'=>[]]); 
        }
    }

    
    
}
