<?php
    
namespace App\Http\Controllers;
    
use App\Banner;
use Illuminate\Http\Request;
use App\Classes\UploadFile;

    
class BannerController extends Controller
{ 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        //  $this->middleware('permission:Banner-list', ['only' => ['index','show']]);
        //  $this->middleware('permission:Banner-list|Banner-create|Banner-edit|Banner-delete', ['only' => ['index','show']]);
        //  $this->middleware('permission:Banner-create', ['only' => ['create','store']]);
        //  $this->middleware('permission:Banner-edit', ['only' => ['edit','update']]);
        //  $this->middleware('permission:Banner-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
              
        return view('banners.index',[]);

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
            $obj = Banner::where('id','<>',0);
                    
            
            if ($request->search['value'] != "") {            
              $obj = $obj->where('banners.name','LIKE',"%".$search."%");
            } 
            
            if(isset($request->order[0]['column']) && $request->order[0]['column']==0){
                $sort = $request->order[0]['dir'];
                $obj = $obj->orderBy('banners.name',$sort);
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
                           
        return view('banners.create',[]);
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
            'name' => 'required|unique:banners,name'
        ]);
       
        //print_r($request->all()); die;
        $obj = new Banner();
        
        if(isset($_FILES['file']['name'])) {          
            $upload_handler = new UploadFile();
            $path = public_path('uploads/banners'); 
            $data = $upload_handler->upload($path,'banners');
            $res = json_decode($data);
            if($res->status=='ok'){                 
              $obj->image = $res->path;
              $obj->file_path = $res->img_path;                              
            }          
        }        

        $obj->name = $request->name;        
        
        if($obj->save()){
          return view('banners.index',[]);
        }else{
            $request->session()->flash('message.level', 'error');
            $request->session()->flash('message.content', 'Not able to save');
            return redirect('admin/banners/create');
        }
        
        
    }
    

     /**
     * Display the specified resource.
     *
     * @param  \App\category_expence  $category_expence
     * @return \Illuminate\Http\Response
     */
    public function show(Banner $banner)
    {
        return view('banners.show',compact('banner'));
    }
    

        
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\category_expence  $category_expence
     * @return \Illuminate\Http\Response
     */
    public function edit(Banner $banner)
    {      
        return view('banners.edit',compact('banner'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\category_expence  $category_expence
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Banner $banner)
    {
        
        $banner = Banner::findOrFail($request->id);
         request()->validate([
            'name' => 'required|unique:banners,name,'.$banner->id,
        ]);

        $banner->name = $request->name;    

        if(isset($_FILES['file']['name'])) {          
          $upload_handler = new UploadFile();
          $path = public_path('uploads/banners'); 
          $data = $upload_handler->upload($path,'banners');
          $res = json_decode($data);
          if($res->status=='ok'){          
            if(file_exists($banner->file_path))
              unlink($banner->file_path);       
            $banner->image = $res->path;
            $banner->file_path = $res->img_path;                              
          }          
        }          
        $banner->update();    
        return redirect()->route('banners.index')
                        ->with('success','banner updated successfully');
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CategoryExpence  $CategoryExpence
     * @return \Illuminate\Http\Response
     */
    public function destroy(Banner $banner)
    {
        $banner->delete();
    
        return redirect()->route('banners.index')
                        ->with('success','banner deleted successfully');
    }

    
}
