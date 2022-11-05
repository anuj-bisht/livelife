<?php
    
namespace App\Http\Controllers;
    
use App\Warmup;
use App\Category;
use Illuminate\Http\Request;
use App\Classes\UploadFile;

    
class WarmupController extends Controller
{ 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:warmup-list', ['only' => ['index','show']]);
         $this->middleware('permission:warmup-list|warmup-create|warmup-edit|warmup-delete', ['only' => ['index','show']]);
         $this->middleware('permission:warmup-create', ['only' => ['create','store']]);
         $this->middleware('permission:warmup-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:warmup-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $catList =  Category::getSelectCat();          
        return view('warmups.index',compact('catList'));

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
            $obj = Warmup::select('warmups.*','categories.name as category_name')            
            ->join('categories','categories.id','=','warmups.category_id');
                    
            
            if ($request->search['value'] != "") {            
              $obj = $obj->where('name','LIKE',"%".$search."%");
            } 

            if(isset($request->category_id)) {            
              $obj = $obj->where('categories.id',$request->category_id);
            }
            
            if(isset($request->order[0]['column']) && $request->order[0]['column']==0){
                $sort = $request->order[0]['dir'];
                $obj = $obj->orderBy('name',$sort);
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
              
        $categories = Category::getCatList();
        return view('warmups.create',compact('categories'));
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
          
        $obj = new Warmup();

        if(isset($_FILES['file']['name']) && !empty($_FILES['file']['name'])){
          $upload_handler = new UploadFile();
          $path = public_path('uploads/warmups'); 
          $data = $upload_handler->upload($path,'warmups');
          $res = json_decode($data);
          if($res->status=='ok'){
            $obj->image = $res->path;
            $obj->file_path = $res->img_path;
          }else{
            $request->session()->flash('message.level', 'error');
            $request->session()->flash('message.content', $res->message);
            return redirect('admin/warmups/create');
          }
        }

        $obj->name = $request->name;
        $obj->category_id = $request->category_id;
        $obj->code = $request->code;

        if($obj->save()){
          $request->session()->flash('message.level', 'success');
          $request->session()->flash('message.content', 'Record added successfully');            
          return redirect('admin/warmups');
        }else{
          $request->session()->flash('message.level', 'error');
          $request->session()->flash('message.content', 'Record Error');        
          return redirect('admin/warmups');  
        }        
    }
    

     /**
     * Display the specified resource.
     *
     * @param  \App\category_expence  $category_expence
     * @return \Illuminate\Http\Response
     */
    public function show(Warmup $warmup)
    {
        return view('warmups.show',compact('warmup'));
    }
    

        
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\category_expence  $category_expence
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {      
        $warmup = Warmup::findOrFail($request->id);
        $categories = Category::getCatList();
        //echo '<pre>';print_r($plan->name); die;
        return view('warmups.edit',compact('warmup','categories'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\category_expence  $category_expence
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Warmup $warmup)
    {
        
        $warmup = Warmup::findOrFail($request->id);
                         
        $warmup->name = $request->name;        
        $warmup->category_id = $request->category_id;   
        $warmup->description = $request->description;    
        $warmup->code = $request->code; 

       // $warmup->point = $request->point;   
       if(isset($_FILES['file']['name']) && !empty($_FILES['file']['name'])){
        $upload_handler = new UploadFile();
        if(file_exists($warmup->file_path)){
          unlink($warmup->file_path);
        }
        $path = public_path('uploads/warmups'); 
        $data = $upload_handler->upload($path,'warmups');
        $res = json_decode($data);
        if($res->status=='ok'){
          $warmup->image = $res->path;
          $warmup->file_path = $res->img_path;
        }
      }     
    
        $warmup->update();
    
            $request->session()->flash('message.level', 'success');
            $request->session()->flash('message.content', 'Record updated');
            return redirect('admin/warmups');
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CategoryExpence  $CategoryExpence
     * @return \Illuminate\Http\Response
     */
    public function destroy(Warmup $warmup)
    {
        $warmup->delete();
    
        return redirect()->route('warmups.index')
                        ->with('success','Warmup deleted successfully');
    }


    
}
