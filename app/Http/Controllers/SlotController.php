<?php
    
namespace App\Http\Controllers;
    
use App\Slot;
use App\Category;
use App\Batch;
use Illuminate\Http\Request;
use App\Classes\UploadFile;

    
class SlotController extends Controller
{ 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        //  $this->middleware('permission:slot-list', ['only' => ['index','show']]);
        //  $this->middleware('permission:slot-list|slot-create|slot-edit|slot-delete', ['only' => ['index','show']]);
        //  $this->middleware('permission:slot-create', ['only' => ['create','store']]);
        //  $this->middleware('permission:slot-edit', ['only' => ['edit','update']]);
        //  $this->middleware('permission:slot-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
              
        return view('slots.index',[]);

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
            $obj = Slot::select('slots.*','categories.name as category_name')->whereRaw('1 = 1')
            ->join('categories','categories.id','=','slots.category_id');
                    
            
            if ($request->search['value'] != "") {            
              $obj = $obj->where('slots.name','LIKE',"%".$search."%");
            } 
            
            if(isset($request->order[0]['column']) && $request->order[0]['column']==0){
                $sort = $request->order[0]['dir'];
                $obj = $obj->orderBy('slots.name',$sort);
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
              
        $category = Category::getCatList();  
        //$batch = Batch::getDataList();                
        return view('slots.create',compact('category'));
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
            'name' => 'required|unique:slots,name',  
            'start_time' =>'required|date_format:H:i:s',
            'category_id'=>'required',                  
            'end_time'=>'required|date_format:H:i:s'         
        ]);
        
        $slot = new Slot();
        $slot->name = $request->name;        
        $slot->end_time = $request->end_time;        
        $slot->batch = implode(",",$request->batch);  
        $slot->start_time = $request->start_time;        
        $slot->category_id = $request->category_id;
       
		//print_r($request->all()); die;
        $slot->save();
        
        return view('slots.index',[]);
    }
    

     /**
     * Display the specified resource.
     *
     * @param  \App\category_expence  $category_expence
     * @return \Illuminate\Http\Response
     */
    public function show(Slot $slot)
    {
        return view('slots.show',compact('slot'));
    }
    

        
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\category_expence  $category_expence
     * @return \Illuminate\Http\Response
     */
    public function edit(Slot $slot)
    {      
        
        $category = Category::getCatList();      
        //$batch = Batch::getDataList();     
        return view('slots.edit',compact('slot','category'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\category_expence  $category_expence
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Slot $slot)
    {
        
        $slot = Slot::findOrFail($request->id);
         request()->validate([
            'name' => 'required|unique:slots,name,'.$slot->id,
            'start_time' =>'required|date_format:H:i:s',
            'category_id'=>'required',            
            'end_time'=>'required|date_format:H:i:s'         
        ]);

        //print_r($request->all()); die;                
        $slot->name = $request->name;        
        $slot->end_time = $request->end_time;        
        $slot->start_time = $request->start_time;    
        $slot->batch = implode(",",$request->batch);      
        $slot->category_id = $request->category_id;            
        
        $slot->update();
    
        return redirect()->route('slots.index')
                        ->with('success','slot updated successfully');
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CategoryExpence  $CategoryExpence
     * @return \Illuminate\Http\Response
     */
    public function destroy(Slot $slot)
    {
        $slot->delete();
    
        return redirect()->route('slots.index')
                        ->with('success','slot deleted successfully');
    }

    
}
