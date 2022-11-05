<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
   
Route::get('/home', 'HomeController@index')->name('home');

Route::get('/sendmail', 'UserController@sendmail1');

Route::post('/getStatesByCountry','Controller@getStatesByCountry');

Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('config:cache');
    // return what you want
});

 
// Route::prefix('room')->middleware('auth')->group(function() {
//    Route::get('join/{roomName}', 'VideoRoomsController@joinRoom');
//    Route::post('create', 'VideoRoomsController@createRoom');
// });


Route::group(['prefix' => 'front','middleware' => ['auth']], function() {    
    
});

Route::group(['prefix' => 'admin','middleware' => ['auth']], function() {    
    
    Route::any('/rooms/getAccessToken', 'VideoRoomsController@getAccessToken');

    Route::get('/videolist', "VideoRoomsController@index");
    Route::get('/rooms/join/{roomName}', 'VideoRoomsController@joinRoom');
    Route::post('/rooms/create', 'VideoRoomsController@createRoom');
    
    Route::any('/roles/copy/{id}', 'RoleController@copy'); 
    Route::resource('roles','RoleController');


    Route::get('/schedules/getschedule', 'ScheduleController@getschedule');  
    Route::post('/schedules/addevent', 'ScheduleController@addevent');  
    Route::post('/schedules/delete', 'ScheduleController@delete');  
    Route::post('/schedules/drop', 'ScheduleController@drop');  
    Route::any('/schedules/ajaxData', 'ScheduleController@ajaxData');
    Route::any('/schedules/create', 'ScheduleController@create');
    Route::any('/schedules/store', 'ScheduleController@store');
	 
    
    Route::resource('schedules','ScheduleController');
    Route::any('schedules', 'ScheduleController@index');  
    

    Route::resource('videos','VideoController');
    Route::any('videos', 'VideoController@index');  
    Route::any('/videos/{id}/edit', 'VideoController@edit');
    Route::any('/videos/update/{id}', 'VideoController@update');
    Route::any('/videos/create', 'VideoController@create');
    Route::any('/videos/store', 'VideoController@store');
    Route::any('/videos/destroy', 'VideoController@destroy');
    Route::any('/videos/ajaxData', 'VideoController@ajaxData');
    Route::any('/videos/videolist', 'VideoController@videolist');
    Route::any('/videos/uploadFiles/{id}', 'VideoController@uploadFiles');
    Route::any('/videos/deleteVideo', 'VideoController@deleteVideo');
    Route::any('/videos/setDefault', 'VideoController@setDefault');
    

    Route::resource('levels','LevelController');
    Route::any('levels', 'LevelController@index');  
    Route::any('/levels/{id}/edit', 'LevelController@edit');
    Route::any('/levels/update/{id}', 'LevelController@update');
    Route::any('/levels/create', 'LevelController@create');
    Route::any('/levels/store', 'LevelController@store');
    Route::any('/levels/destroy', 'LevelController@destroy');
    Route::any('/levels/ajaxData', 'LevelController@ajaxData');
    Route::any('/levels/setDefault', 'LevelController@setDefault');
    

    Route::resource('diets','DietController');
    Route::any('diets', 'DietController@index');  
    Route::any('/diets/{id}/edit', 'DietController@edit');
    Route::any('/diets/update/{id}', 'DietController@update');
    Route::any('/diets/create', 'DietController@create');
    Route::any('/diets/store', 'DietController@store');
    Route::any('/diets/destroy', 'DietController@destroy');
    Route::any('/diets/ajaxData', 'DietController@ajaxData');

    Route::resource('plans','PlanController');
    Route::any('plans', 'PlanController@index');  
    Route::any('/plans/{id}/edit', 'PlanController@edit');
    Route::any('/plans/update/{id}', 'PlanController@update');
    Route::any('/plans/create', 'PlanController@create');
    Route::any('/plans/store', 'PlanController@store');
    Route::any('/plans/destroy', 'PlanController@destroy');
    Route::any('/plans/ajaxData', 'PlanController@ajaxData'); 
    
    
    Route::any('categories', 'CategoryController@index');  
    Route::any('/categories/{id}/edit', 'CategoryController@edit');
    Route::any('/categories/update/{id}', 'CategoryController@update');
    Route::any('/categories/create', 'CategoryController@create');
    Route::any('/categories/store', 'CategoryController@store');
    Route::any('/categories/destroy', 'CategoryController@destroy');
    Route::any('/categories/ajaxData', 'CategoryController@ajaxData'); 
    Route::any('/categories/makelfl', 'CategoryController@makelfl'); 
    
    

    Route::resource('users','UserController');
    Route::any('/users/ajaxData', 'UserController@ajaxData');  
    Route::any('/users/addRemoveNotification', 'UserController@addRemoveNotification');  
    Route::resource('projects','ProjectController');
    Route::any('/projects/ajaxData', 'ProjectController@ajaxData');  
    Route::resource('clients','ClientController');

    
    Route::any('menus', 'MenuController@index');  
    Route::any('/menus/edit/{id}', 'MenuController@edit');
    Route::any('/menus/update/{id}', 'MenuController@update');
    Route::any('/menus/add', 'MenuController@add');
    Route::any('/menus/destroy', 'MenuController@destroy');
    Route::any('/menus/assignpage', 'MenuController@assignpage');
    Route::any('/menus/assignpageSubmit', 'MenuController@assignpageSubmit');
    

    //$router->resource('pages', 'PagesController');
    Route::resource('pages','PagesController');
    
    Route::post('/pages/pageIndexAjax', 'PagesController@pageIndexAjax');
    Route::post('/pages/imagelist', 'PagesController@imagelist');  
    Route::post('/pages/setDefault', 'PagesController@setDefault');  
    Route::post('/pages/deleteImage', 'PagesController@deleteImage');
    Route::any('/pages/uploadFiles/{id}', 'PagesController@uploadFiles'); 
    Route::any('/pages/settings', 'PagesController@settings');
    Route::post('/pages/contactus', 'PagesController@contactus');


    Route::any('recipes', 'RecipeController@index');  
    Route::any('/recipes/{id}/edit', 'RecipeController@edit');
    Route::any('/recipes/update/{id}', 'RecipeController@update');
    Route::any('/recipes/create', 'RecipeController@create');
    Route::any('/recipes/store', 'RecipeController@store');
    Route::any('/recipes/destroy', 'RecipeController@destroy');
    Route::any('/recipes/ajaxData', 'RecipeController@ajaxData'); 

    Route::any('contactus', 'ContactusController@index');  
    Route::any('/contactus/ajaxData', 'ContactusController@ajaxData'); 
    Route::any('/contactus/{id}/edit', 'ContactusController@edit');
    Route::any('/contactus/update/{id}', 'ContactusController@update');

    Route::any('warmups', 'WarmupController@index');  
    Route::any('/warmups/{id}/edit', 'WarmupController@edit');
    Route::any('/warmups/update/{id}', 'WarmupController@update');
    Route::any('/warmups/create', 'WarmupController@create');
    Route::any('/warmups/store', 'WarmupController@store');
    Route::any('/warmups/destroy', 'WarmupController@destroy');
    Route::any('/warmups/ajaxData', 'WarmupController@ajaxData'); 

    Route::any('subscriptions', 'SubscriptionController@index');  
    Route::any('/subscriptions/ajaxData', 'SubscriptionController@ajaxData');


    Route::resource('demorequests','DemorequestController');
    Route::any('/demorequests', 'DemorequestController@index');  
    Route::post('/demorequests/ajaxData', 'DemorequestController@ajaxData');

    Route::resource('testrequests','TestrequestController');
    Route::post('/testrequests/ajaxData', 'TestrequestController@ajaxData');

    Route::post('/users/getTrainerList', 'UserController@getTrainerList');
    Route::post('/users/assigntrainer', 'UserController@assigntrainer');

    Route::any('/users/settings/{id}', 'UserController@settings');
    

});

Route::group(['prefix' => 'trainer','middleware' => ['auth']], function() { 
	Route::Post('/schedules/trainer', 'ScheduleController@getTrainerScheduleOnDate');
	Route::get('/schedules/trainer_month_schedule', 'ScheduleController@getTrainerScheduleOfMonth');
	Route::get('/schedules/filter_trainer_jobs', 'ScheduleController@filterTrainerJobs');
	Route::get('/clients', 'UserController@getAssignedClients')->name('trainer_clients');
	
	
	Route::resource('master-diets','MasterDietController');
    Route::any('master-diets', 'MasterDietController@index')->name('master_diets');  
    Route::any('/master-diets/{id}/edit', 'MasterDietController@edit');
    Route::any('/master-diets/update/{id}', 'MasterDietController@update');
    Route::any('/master-diets/create', 'MasterDietController@create')->name('master_diet_create');
    Route::any('/master-diets/store', 'MasterDietController@store');
    Route::any('/master-diets/destroy/{id}', 'MasterDietController@destroy');
    Route::any('/master-diets/ajaxData', 'MasterDietController@ajaxData');
	Route::get('/clients/assigned_diet', 'MasterDietController@getClientAssignedDiet')->name('client_assigned_diet');
	Route::get('/clients/assign_diet', 'MasterDietController@assignClientDiet')->name('assign_diet');
	Route::post('/clients/{client_id}/assign_diet', 'MasterDietController@postAssignClientDiet')->name('save_diet');
	Route::delete('/clients/unassign_diet/{id}', 'MasterDietController@unAssignClientDiet')->name('unassign_diet');
});
