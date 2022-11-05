<?php
    
namespace App\Http\Controllers;
    
use App\Recipe;
use Illuminate\Http\Request;
use App\Classes\UploadFile;

    
class RecipeController extends Controller
{ 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
          //$this->middleware('permission:recipes-list', ['only' => ['index','show']]);
         //$this->middleware('permission:recipes-list|recipes-create|recipes-edit|recipes-delete', ['only' => ['index','show']]);
         //$this->middleware('permission:recipes-create', ['only' => ['create','store']]);
         //$this->middleware('permission:recipes-edit', ['only' => ['edit','update']]);
         //$this->middleware('permission:recipes-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
              
        return view('recipes.index',[]);

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
            
            //echo '<pre>'; print_r($users); die; recipes
            $obj = Recipe::whereRaw('1 = 1');
            

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
              
                
        return view('recipes.create',[]);
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
            'name' => 'required|unique:recipes,name',                      
        ]);
          
        $obj = new Recipe();

        $obj->name = $request->name;
        $obj->youtube_link = $request->youtube_link;  

        if($obj->save()){
          return redirect('admin/recipes')
                        ->with('success','Recipe created successfully.');
        }else{
          return redirect('admin/recipes')
                        ->with('error','Error.');
        }
        
        
    }
    

     /**
     * Display the specified resource.
     *
     * @param  \App\recipes  $recipes
     * @return \Illuminate\Http\Response
     */
    public function show(Recipe $recipe)
    {
        return view('recipes.show',compact('recipe'));
    }
    

        
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Request  $Request
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {      
        //echo '<pre>';print_r($request->id); die;
        $recipe = Recipe::findOrFail($request->id);
        return view('recipes.edit',compact('recipe'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Recipe  $Recipe
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Recipe $recipe)
    {
        $recipe = Recipe::findOrFail($request->id);

         request()->validate([
            'name' => 'required|unique:recipes,name,'.$recipe->id
        ]);

                
        $recipe->name = $request->name;       
        $recipe->youtube_link = $request->youtube_link;       
            
        $recipe->update();
    
        return redirect('admin/recipes')
                        ->with('success','Recipe updated successfully');
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Recipe  $Recipe
     * @return \Illuminate\Http\Response
     */
    public function destroy(Recipe $recipe)
    {
        $recipe->delete();
    
        return redirect()->route('recipes.index')
                        ->with('success','Recipe deleted successfully');
    }

    
}
