<?php
    
namespace App\Http\Controllers;
    
use App\Video;
use App\Level;
use Illuminate\Http\Request;
use App\Classes\UploadFile;
use App\Classes\UploadVideo;
use App\Category;
use DB;

    
class VideoController extends Controller
{ 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:video-list', ['only' => ['index','show']]);
         $this->middleware('permission:video-list|video-create|video-edit|video-delete', ['only' => ['index','show']]);
         $this->middleware('permission:video-create', ['only' => ['create','store']]);
         $this->middleware('permission:video-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:video-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
              
        return view('videos.index',[]);

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
            $obj = Video::select('videos.*','levels.name as level_name')->whereRaw('1 = 1')
            ->join('levels','levels.id','=','videos.level_id');
                                
            
            if ($request->search['value'] != "") {            
              $obj = $obj->where('videos.name','LIKE',"%".$search."%");
            } 
            
            if(isset($request->order[0]['column']) && $request->order[0]['column']==0){
                $sort = $request->order[0]['dir'];
                $obj = $obj->orderBy('videos.name',$sort);
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
                
        return view('videos.create',compact('categories'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      
      // dd($request->all());
        request()->validate([
            'name' => 'required|unique:videos,name',
            'category_id' => 'required',
            'level_id' => 'required'
        ]);
       
        $video = new Video();
        // $video->category_id = $request->category_id;        
        $video->level_id = $request->level_id;
        // $video->level_type = $request->level_type;
        $video->name = $request->name;
        $video->description = $request->description;
        $video->youtube = $request->code;
        $video->save();
        return redirect('admin/videos/'.$video->id.'/edit')->with('addVideo');
        
        return view('videos.index',[]);
    }
    
    
    
     /**
     * Display the specified resource.
     *
     * @param  \App\category_expence  $category_expence
     * @return \Illuminate\Http\Response
     */
    public function show(Video $video)
    {
        return view('videos.show',compact('video'));
    }
    

        
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\category_expence  $category_expence
     * @return \Illuminate\Http\Response
     */
    public function edit(Video $video)
    {      
      $categories = Category::getCatDDWithTestEnabled();

      $selectedCatId = Level::select('category_id')->where('id',$video->level_id)->first() ;
      
        return view('videos.edit',compact('video','categories','selectedCatId'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\category_expence  $category_expence
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Video $video)
    {
        
        $video = Video::findOrFail($request->id);
         request()->validate([
            'name' => 'required|unique:videos,name,'.$video->id
        ]);

                
        $video->name = $request->name;        
        $video->description = $request->description;        
            
        $video->update();
    
        return redirect()->route('videos.index')
                        ->with('success','Video updated successfully');
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CategoryExpence  $CategoryExpence
     * @return \Illuminate\Http\Response
     */
    public function destroy(Video $video)
    {
        $video->delete();
    
        return redirect()->route('videos.index')
                        ->with('success','Video deleted successfully');
    }




    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function uploadFiles(Request $request, $id)
    {
      try{        
        
        // $obj = new Level();
        // $objData = $obj->findOrFail($id);
        $upload_handler = new UploadVideo();
        $path = public_path('uploads/level_videos'); 
        
        $data = $upload_handler->upload($path,'level_videos');
        $res = json_decode($data);
        if($res->status=='ok'){
          $obj2 = DB::table('videos')->where('id',$id)->update(['video'=>$res->path,'video_path'=>$res->img_path]);
          // $obj2 = new Video();
          // $obj2 = $obj2->findOrFail($id);  
          // $obj2->video = $res->path;
          
          // $obj2->video_path = $res->img_path;
          // // $obj2->level_id = $id;
          // $obj2->save();
          
        }
        return response()->json(['status'=>1,'message'=>'Success','data'=>$obj2]);
        echo $data; die;
      }catch(Exception $e){
        abort(500, $e->message());
      }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function videolist(Request $request)
    {
      try{        
        $id = $request->id;
        $obj = new Level();
        $objData = $obj->findOrFail($id);
        if($objData->count()){
          $result = Video::where('level_id',$id)->get();
          echo json_encode($result); die;
        }
      }catch(Exception $e){
        abort(500, $e->message());
      }
    }


    public function setDefault(Request $request){
      try{        
        
        $id = $request->id;
        $obj = new Video();
        $obj = $obj->findOrFail($id);     
        
        if($obj->count()>0){
          
          Video::where('level_id', $obj->level_id)->update(array('default' => 'N'));

          $obj2 = new Video();
          $obj2 = $obj2->findOrFail($id);
          $obj2->default = 'Y';
          $obj2->save();
          echo json_encode(["success"=>1,"message"=>"set default"]); die;
        }else{
          echo json_encode(["success"=>1,"message"=>"Not default"]); die;
        }        
        
      }catch(Exception $e){
        echo json_encode(["success"=>1,"message"=>"Not default"]); die;
      }
    }

    public function deleteVideo(Request $request){
      try{        
        $id = $request->id;   
        $obj = new Video();
        $obj = $obj->findOrFail($id);     
        if($obj->count()>0){
          unlink($obj->video_path);
          $obj->delete();          
          echo json_encode(["success"=>1,"message"=>"deleted"]); die;
        }else{
          echo json_encode(["success"=>1,"message"=>"Not deleted"]); die;
        }        
        
      }catch(Exception $e){
        echo json_encode(["success"=>1,"message"=>"Not deleted"]); die;
      }
    }

    
}
