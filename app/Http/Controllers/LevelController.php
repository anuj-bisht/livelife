<?php
    
namespace App\Http\Controllers;
    
use App\Level;
use App\Category;
use Illuminate\Http\Request;
use App\Classes\UploadFile;

    
class LevelController extends Controller
{ 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:level-list', ['only' => ['index','show']]);
         $this->middleware('permission:level-list|level-create|level-edit|level-delete', ['only' => ['index','show']]);
         $this->middleware('permission:level-create', ['only' => ['create','store']]);
         $this->middleware('permission:level-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:level-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
              
        return view('levels.index',[]);

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
            $obj = Level::select('levels.*','categories.name as category_name')
            ->join('categories','categories.id','=','levels.category_id');
                    
            
            if ($request->search['value'] != "") {            
              $obj = $obj->where('levels.name','LIKE',"%".$search."%");
            } 
            
            if(isset($request->order[0]['column']) && $request->order[0]['column']==0){
                $sort = $request->order[0]['dir'];
                $obj = $obj->orderBy('levels.name',$sort);
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
              
        $categories = Category::getCatDDWithTestEnabled();                
        return view('levels.create',compact('categories'));
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
            'name' => 'required|unique:levels,name'
        ]);
       
		//print_r($request->all()); die;
        Level::create($request->all());
        
        return view('levels.index',[]);
    }
    

     /**
     * Display the specified resource.
     *
     * @param  \App\category_expence  $category_expence
     * @return \Illuminate\Http\Response
     */
    public function show(Level $level)
    {
        return view('levels.show',compact('level'));
    }
    

        
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\category_expence  $category_expence
     * @return \Illuminate\Http\Response
     */
    public function edit(Level $level)
    {      
        $categories = Category::getCatDDWithTestEnabled();
        //echo '<pre>';print_r($plan->name); die;
        return view('levels.edit',compact('level','categories'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\category_expence  $category_expence
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Level $level)
    {
        
        $level = Level::findOrFail($request->id);
        
         request()->validate([
            'name' => 'required|unique:levels,id,'.$level->id
        ]);

                
        $level->name = $request->name;        
        $level->category_id = $request->category_id;    
        $level->description = $request->description;                  
        $level->duration = $request->duration;  
    
        $level->update();
    
        return redirect()->route('levels.index')
                        ->with('success','Level updated successfully');
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CategoryExpence  $CategoryExpence
     * @return \Illuminate\Http\Response
     */
    public function destroy(Level $level)
    {
        $level->delete();
    
        return redirect()->route('levels.index')
                        ->with('success','Level deleted successfully');
    }


    public function setDefault(Request $request){
      try{        
        
        $id = $request->id;
        $obj = new Level();
        $obj = $obj->findOrFail($id);     

        Level::where('id','<>', 0)->update(array('default' => 'N'));

        if(isset($obj->id)){                    
          $obj->default = 'Y';
          $obj->save();
          echo json_encode(["success"=>1,"message"=>"set default"]); die;
        }else{
          echo json_encode(["success"=>1,"message"=>"Not default"]); die;
        }        
        
      }catch(Exception $e){
        echo json_encode(["success"=>1,"message"=>"Not default"]); die;
      }
    }

    
}
