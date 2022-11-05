<?php
    
namespace App\Http\Controllers;
    
use App\Demorequest;
use App\Notification;
use App\Category;
use Illuminate\Http\Request;
use App\Classes\UploadFile;

    
class DemorequestController extends Controller
{ 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:demorequest-list', ['only' => ['index','show']]);
         $this->middleware('permission:demorequest-list|demorequest-create|demorequest-edit|demorequest-delete', ['only' => ['index','show']]);
         $this->middleware('permission:demorequest-create', ['only' => ['create','store']]);
         $this->middleware('permission:demorequest-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:demorequest-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $catList =  Category::getSelectCat();             
        return view('demorequests.index',compact('catList'));

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
            $obj = Demorequest::getDemoRequest('full');    
            
            $obj = $obj->orderBy('demorequests.id','desc');
            
            if(isset($request->category_id)) {            
              $obj = $obj->where('categories.id',$request->category_id);
            }

            if ($request->search['value'] != "") {            
              $obj = $obj->where('users.name','LIKE',"%".$search."%");
            } 
            
            if(isset($request->order[0]['column']) && $request->order[0]['column']==0){
                $sort = $request->order[0]['dir'];
                $obj = $obj->orderBy('users.name',$sort);
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

    

    
}
