<?php
    
namespace App\Http\Controllers;
    
use App\Batch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Classes\UploadFile;
use App\Slot;
use DB;

    
class BatchController extends Controller
{ 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        //  $this->middleware('permission:batch-list', ['only' => ['index','show']]);
        //  $this->middleware('permission:batch-list|batch-create|batch-edit|batch-delete', ['only' => ['index','show']]);
        //  $this->middleware('permission:batch-create', ['only' => ['create','store']]);
        //  $this->middleware('permission:batch-edit', ['only' => ['edit','update']]);
        //  $this->middleware('permission:batch-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
              
        return view('batches.index',[]);

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
            $obj = Batch::whereRaw('1 = 1');
                    
            
            if ($request->search['value'] != "") {            
              $obj = $obj->where('name','LIKE',"%".$search."%");
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
              
                
        return view('batches.create',[]);
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
            'name' => 'required|unique:batches,name',                      
        ]);
          
        $obj = new Batch();

        $obj->name = $request->name;
        $obj->description = $request->description;

        if(isset($_FILES['file']['name']) && !empty($_FILES['file']['name'])){
          $upload_handler = new UploadFile();
          $path = public_path('uploads/batches'); 
          $data = $upload_handler->upload($path,'batches');
          $res = json_decode($data);
          if($res->status=='ok'){
            $obj->image = $res->path;
            $obj->file_path = $res->img_path;
          }else{
            $request->session()->flash('message.level', 'error');
            $request->session()->flash('message.content', $res->message);
            return redirect('admin/batches/create');
          }
        }
    
        if($obj->save()){
          return redirect('admin/batches')
                        ->with('success','Batch created successfully.');
        }else{
          return redirect('admin/batches')
                        ->with('error','Error.');
        }
        
        
    }
    

     /**
     * Display the specified resource.
     *
     * @param  \App\category_expence  $category_expence
     * @return \Illuminate\Http\Response
     */
    public function show(Batch $batch)
    {
        return view('batches.show',compact('batch'));
    }
    

        
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\category_expence  $category_expence
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Batch $batch)
    {      
        //echo '<pre>';print_r($request->id); die;
        $batch = Batch::findOrFail($request->id);
        return view('batches.edit',compact('batch'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\category_expence  $category_expence
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Batch $batch)
    {
        $batch = Batch::findOrFail($request->id);

        request()->validate([
            'name' => 'required|unique:batches,name,'.$batch->id
        ]);

                
        $batch->name = $request->name;       
        $batch->description = $request->description;
        
        if(isset($_FILES['file']['name']) && !empty($_FILES['file']['name'])){
          $upload_handler = new UploadFile();
          $path = public_path('uploads/batches'); 
          $data = $upload_handler->upload($path,'batches');
          $res = json_decode($data);
          if($res->status=='ok'){
            $batch->image = $res->path;
            $batch->file_path = $res->img_path;
          }else{
            $request->session()->flash('message.level', 'error');
            $request->session()->flash('message.content', $res->message);
            return redirect('admin/batches');
          }
        }

    
        $batch->update();
    
        return redirect('admin/batches')
                        ->with('success','Batch updated successfully');
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CategoryExpence  $CategoryExpence
     * @return \Illuminate\Http\Response
     */
    public function destroy(Batch $batch)
    {
        $batch->delete();
    
        return redirect()->route('batches.index')
                        ->with('success','Batch deleted successfully');
    }


    
}
