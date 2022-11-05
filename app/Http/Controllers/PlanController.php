<?php
    
namespace App\Http\Controllers;
    
use App\Plan;
use App\Category;
use Illuminate\Http\Request;
use App\Classes\UploadFile;

    
class PlanController extends Controller
{ 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:plan-list', ['only' => ['index','show']]);
         $this->middleware('permission:plan-list|plan-create|plan-edit|plan-delete', ['only' => ['index','show']]);
         $this->middleware('permission:plan-create', ['only' => ['create','store']]);
         $this->middleware('permission:plan-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:plan-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
              
        return view('plans.index',[]);

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
            $obj = Plan::select('plans.*','categories.name as category_name')->whereRaw('1 = 1')
            ->join('categories','categories.id','=','plans.category_id');
                    
            
            if ($request->search['value'] != "") {            
              $obj = $obj->where('plans.name','LIKE',"%".$search."%");
            } 
            
            if(isset($request->order[0]['column']) && $request->order[0]['column']==0){
                $sort = $request->order[0]['dir'];
                $obj = $obj->orderBy('plans.name',$sort);
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
        return view('plans.create',compact('category'));
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
            'name' => 'required|unique:categories,name',  
            'days' =>'required',
            'category_id'=>'required',
            'price'=>'required'         
        ]);
       
		//print_r($request->all()); die;
        Plan::create($request->all());
        
        return view('plans.index',[]);
    }
    

     /**
     * Display the specified resource.
     *
     * @param  \App\category_expence  $category_expence
     * @return \Illuminate\Http\Response
     */
    public function show(Plan $plan)
    {
        return view('plans.show',compact('plan'));
    }
    

        
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\category_expence  $category_expence
     * @return \Illuminate\Http\Response
     */
    public function edit(Plan $plan)
    {      
        
        $category = Category::getCatList();         
        return view('plans.edit',compact('plan','category'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\category_expence  $category_expence
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Plan $plan)
    {
        
        $plan = Plan::findOrFail($request->id);
         request()->validate([
            'name' => 'required',
            'days' =>'required',
            'category_id'=>'required',
            'price'=>'required'         
        ]);

        //print_r($request->all()); die;                
        $plan->name = $request->name;        
        $plan->description = $request->description;        
        $plan->days = $request->days;        
        $plan->category_id = $request->category_id;    
        $plan->diet_type = $request->diet_type;    
        $plan->client_type = $request->client_type;    
        $plan->price = $request->price;    
    
        $plan->update();
    
        return redirect()->route('plans.index')
                        ->with('success','Plan updated successfully');
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CategoryExpence  $CategoryExpence
     * @return \Illuminate\Http\Response
     */
    public function destroy(Plan $plan)
    {
        $plan->delete();
    
        return redirect()->route('plans.index')
                        ->with('success','Plan deleted successfully');
    }

    
}
