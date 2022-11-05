<?php
    
namespace App\Http\Controllers;
    
use App\Generic;
use Illuminate\Http\Request;
use App\Classes\UploadFile;

    
class GenericController extends Controller
{ 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        //  $this->middleware('permission:generic-list', ['only' => ['index','show']]);
        //  $this->middleware('permission:generic-list|generic-create|generic-edit|generic-delete', ['only' => ['index','show']]);
        //  $this->middleware('permission:generic-create', ['only' => ['create','store']]);
        //  $this->middleware('permission:generic-edit', ['only' => ['edit','update']]);
        //  $this->middleware('permission:generic-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
              
        return view('generic.index',[]);

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
            $obj = Generic::where('id','<>',0);
                    
            
            if ($request->search['value'] != "") {            
              $obj = $obj->where('generic.name','LIKE',"%".$search."%");
            } 
            
            if(isset($request->order[0]['column']) && $request->order[0]['column']==0){
                $sort = $request->order[0]['dir'];
                $obj = $obj->orderBy('generic.name',$sort);
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
                           
        return view('generic.create',[]);
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
            'name' => 'required|unique:generic,name'
        ]);
       
        //print_r($request->all()); die;
        $obj = new Generic();
        
        if(isset($_FILES['file']['name'])) {          
            $upload_handler = new UploadFile();
            $path = public_path('uploads/generic'); 
            $data = $upload_handler->upload($path,'generic');
            $res = json_decode($data);
            if($res->status=='ok'){                 
              $obj->image = $res->path;
              $obj->file_path = $res->img_path;                              
            }          
        }        

        $obj->name = $request->name;        
        $obj->gtype = $request->gtype;        
        $obj->youtube_link = (isset($request->youtube_link)) ? $request->youtube_link : null;        
        $obj->description = (isset($request->description)) ? $request->description: null; 
        
        if($obj->save()){
          return view('generic.index',[]);
        }else{
            $request->session()->flash('message.level', 'error');
            $request->session()->flash('message.content', 'Not able to save');
            return redirect('admin/generic/create');
        }
        
        
    }
    

     /**
     * Display the specified resource.
     *
     * @param  \App\category_expence  $category_expence
     * @return \Illuminate\Http\Response
     */
    public function show(Generic $generic)
    {
        return view('generic.show',compact('generic'));
    }
    

        
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\category_expence  $category_expence
     * @return \Illuminate\Http\Response
     */
    public function edit(Generic $generic)
    {      
        return view('generic.edit',compact('generic'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\category_expence  $category_expence
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Generic $generic)
    {
        
        $generic = Generic::findOrFail($request->id);
         request()->validate([
            'name' => 'required|unique:generic,name,'.$generic->id,
        ]);

        $generic->name = $request->name;    
        $generic->gtype = $request->gtype;        
        $generic->youtube_link = $request->youtube_link;    
        $generic->youtube_link = (isset($request->youtube_link)) ? $request->youtube_link : null;        
        $generic->description = (isset($request->description)) ? $request->description: null; 
            

        if(isset($_FILES['file']['name'])) {          
          $upload_handler = new UploadFile();
          $path = public_path('uploads/generic'); 
          $data = $upload_handler->upload($path,'generic');
          $res = json_decode($data);
          if($res->status=='ok'){          
            if(file_exists($generic->file_path))
              unlink($generic->file_path);       
            $generic->image = $res->path;
            $generic->file_path = $res->img_path;                              
          }          
        }          
        $generic->update();    
        return redirect()->route('generic.index')
                        ->with('success','generic updated successfully');
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CategoryExpence  $CategoryExpence
     * @return \Illuminate\Http\Response
     */
    public function destroy(Generic $generic)
    {
        $generic->delete();
    
        return redirect()->route('generic.index')
                        ->with('success','generic deleted successfully');
    }

    
}
