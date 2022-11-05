<?php
    
namespace App\Http\Controllers;
    
use App\LevelData;
use App\Level;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Classes\UploadFile;

    
class LeveldataController extends Controller
{ 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        //  $this->middleware('permission:leveldatas-list', ['only' => ['index','show']]);
        //  $this->middleware('permission:leveldatas-list|leveldatas-create|leveldatas-edit|leveldatas-delete', ['only' => ['index','show']]);
        //  $this->middleware('permission:leveldatas-create', ['only' => ['create','store']]);
        //  $this->middleware('permission:leveldatas-edit', ['only' => ['edit','update']]);
        //  $this->middleware('permission:leveldatas-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
              
        return view('leveldatas.index',[]);

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
            $obj = LevelData::select('level_data.*','levels.name as level_name','categories.name as category_name')
            ->join('levels','levels.id','=','level_data.level_id')
            ->join('categories','categories.id','=','level_data.category_id');
            

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
        return view('leveldatas.create',compact('categories'));
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
            'category_id' => 'required',
            'level_id' => 'required',
            'level_type' => 'required'
        ]);
       
        
        $obj = new LevelData();       
        
        $obj->category_id = $request->category_id;        
        $obj->level_id = $request->level_id;
        $obj->level_type = $request->level_type;
        $obj->name = $request->name;
        $obj->code = (isset($request->code)) ? $request->code : '';

        if(isset($_FILES['file']['name']) && !empty($_FILES['file']['name'])){
          $upload_handler = new UploadFile();
          //unlink($warmup->file_path);
          $path = public_path('uploads/leveldatas'); 
          $data = $upload_handler->upload($path,'leveldatas');
          $res = json_decode($data);
          if($res->status=='ok'){
            $obj->image = $res->path;
            $obj->file_path = $res->img_path;
          }
        }   

        if($obj->save()){
          return redirect('admin/leveldatas');
        }else{
            $request->session()->flash('message.level', 'error');
            $request->session()->flash('message.content', 'Not able to save');
            return redirect('admin/leveldatas/create');
        }
        
        
    }
    

     /**
     * Display the specified resource.
     *
     * @param  \App\category_expence  $category_expence
     * @return \Illuminate\Http\Response
     */
    public function show(LevelData $LevelData)
    {
        return view('leveldatas.show',compact('leveldata'));
    }
    

        
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\category_expence  $category_expence
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, LevelData $LevelData)
    {     
        
        $LevelData = LevelData::findOrFail($request->id); 
        $categories = Category::getCatDDWithTestEnabled(); 

        $levels = Level::getLevelByCategoryDD($LevelData->category_id);

        return view('leveldatas.edit',compact('LevelData','categories','levels'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\category_expence  $category_expence
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LevelData $LevelData)
    {
        
        $obj = LevelData::findOrFail($request->id);

         request()->validate([
          'category_id' => 'required',
          'level_id' => 'required',
          'level_type' => 'required'
        ]);

        if(isset($_FILES['file']['name']) && !empty($_FILES['file']['name'])){
          $upload_handler = new UploadFile();
          unlink($obj->file_path);
          $path = public_path('uploads/leveldatas'); 
          $data = $upload_handler->upload($path,'leveldatas');
          $res = json_decode($data);
          if($res->status=='ok'){
            $obj->image = $res->path;
            $obj->file_path = $res->img_path;
          }
        }  

        $obj->category_id = $request->category_id;        
        $obj->level_id = $request->level_id;
        $obj->level_type = $request->level_type;
        $obj->code = (isset($request->code)) ? $request->code : '';     
        $obj->name = $request->name; 
    
        $obj->update();
    
        return redirect('admin/leveldatas');;
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CategoryExpence  $CategoryExpence
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      // dd($id);
        // $LevelData->delete();
        LevelData::find($id)->delete();
    
        return redirect()->route('leveldatas.index')
                        ->with('success','LevelData deleted successfully');
    }

    public function getLevelByCategory(Request $request){
      try {
        $status = 0;
        $message = "";

        $validator = Validator::make($request->all(), [
          'id' => 'required'          
        ]);

        $params = [];  
        if($validator->fails()){
          $error = json_decode(json_encode($validator->errors()));
          if(isset($error->id[0])){
            $message = $error->id[0];
          }
          return response()->json(["status"=>$status,"message"=>$message,"data"=>json_decode("{}")]);
        }

        $levels = Level::getLevelByCategoryId($request->id);
        
        return response()->json(["status"=>1,"message"=>"","data"=>$levels]);

      }catch(Exception $e){
        return response()->json(['status'=>$status,'message'=>'Error','data'=>json_decode("{}")]);                    
      }
    }
}
