<?php
    
namespace App\Http\Controllers;
    
use App\Review;
use Illuminate\Http\Request;
use App\Classes\UploadFile;

    
class ReviewController extends Controller
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
              
        return view('reviews.index',[]);

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
            $obj = Review::select('reviews.*','users.name','users.email','u1.name as trainer_name')
            ->join('users', 'users.id', '=', 'reviews.user_id')             
            ->join('users as u1', 'u1.id', '=', 'reviews.rating_to');
                    
            
            if ($request->search['value'] != "") {            
              $obj = $obj->where('reviews.rating','LIKE',"%".$search."%");
            } 
            
            if(isset($request->order[0]['column']) && $request->order[0]['column']==0){
                $sort = $request->order[0]['dir'];
                $obj = $obj->orderBy('reviews.rating',$sort);
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
