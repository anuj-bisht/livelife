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
    //return view('welcome');
    return redirect()->to('/login');
});

Auth::routes();
   
Route::get('/home', 'HomeController@index')->name('home');



Route::get('/testemailsend', 'UserController@testemailsend');

Route::get('/maketodaystip', 'TipController@maketodaystip');
Route::get('/subscriptionReminder', 'Controller@subscriptionReminder');
Route::get('/lastreminder', 'Controller@lastreminder');

Route::get('/users/verifyEmailOtp/{vcode}', 'UserController@verifyEmailOtp');



Route::post('/getStatesByCountry','Controller@getStatesByCountry');

Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('config:cache');
    // return what you want
});

 

// Route::prefix('room')->middleware('auth')->group(function() {
//    Route::get('join/{roomName}', 'VideoRoomsController@joinRoom');
//    Route::post('create', 'VideoRoomsController@createRoom');
// });

Route::any('/notifications/ajaxNotificationData', 'NotificationController@ajaxNotificationData');  

Route::group(['prefix' => 'front','middleware' => ['auth']], function() {    
     
});

Route::group(['prefix' => 'admin','middleware' => ['auth']], function() {    
    
    Route::any('/rooms/getAccessToken', 'VideoRoomsController@getAccessToken');

    Route::get('/videolist', "VideoRoomsController@index");
    Route::get('/rooms/join/{roomName}', 'VideoRoomsController@joinRoom');
    Route::post('/rooms/create', 'VideoRoomsController@createRoom');
    
    Route::any('/roles/copy/{id}', 'RoleController@copy'); 

    Route::post('/testrequests/sendNotificationAjax', 'TestrequestController@sendNotificationAjax');
    
    Route::resource('roles','RoleController');

    Route::resource('tips','TipController');
    Route::any('tips', 'TipController@index');  
    Route::any('/tips/{id}/edit', 'TipController@edit');
    Route::any('/tips/update/{id}', 'TipController@update');
    Route::any('/tips/create', 'TipController@create');
    Route::any('/tips/store', 'TipController@store');
    Route::any('/tips/destroy', 'TipController@destroy');
    Route::any('/tips/ajaxData', 'TipController@ajaxData');
    Route::any('/tips/makedefault', 'TipController@makedefault');

    Route::any('/orders/failed', 'OrderController@failed');
    Route::resource('orders','OrderController');
    Route::any('orders', 'OrderController@index');  
    Route::any('/orders/ajaxData', 'OrderController@ajaxData');
    Route::any('/orders/getorderbyid', 'OrderController@getorderbyid');
    Route::any('/orders/makeSchedule', 'OrderController@makeSchedule');    
    Route::any('/orders/ajaxFailedData', 'OrderController@ajaxFailedData');
    

    Route::resource('slots','SlotController');
    Route::any('slots', 'SlotController@index');  
    Route::any('/slots/{id}/edit', 'SlotController@edit');
    Route::any('/slots/update/{id}', 'SlotController@update');
    Route::any('/slots/create', 'SlotController@create');
    Route::any('/slots/store', 'SlotController@store');
    Route::any('/slots/destroy', 'SlotController@destroy');
    Route::any('/slots/ajaxData', 'SlotController@ajaxData');

    Route::resource('banners','BannerController');
    Route::any('banners', 'BannerController@index');  
    Route::any('/banners/{id}/edit', 'BannerController@edit');
    Route::any('/banners/update/{id}', 'BannerController@update');
    Route::any('/banners/create', 'BannerController@create');
    Route::any('/banners/store', 'BannerController@store');
    Route::any('/banners/destroy', 'BannerController@destroy');
    Route::any('/banners/ajaxData', 'BannerController@ajaxData');

    Route::resource('generic','GenericController');
    Route::any('generic', 'GenericController@index');  
    Route::any('/generic/{id}/edit', 'GenericController@edit');
    Route::any('/generic/update/{id}', 'GenericController@update');
    Route::any('/generic/create', 'GenericController@create');
    Route::any('/generic/store', 'GenericController@store');
    Route::any('/generic/destroy', 'GenericController@destroy');
    Route::any('/generic/ajaxData', 'GenericController@ajaxData');

    Route::resource('gdiets','GdietController');
    Route::any('gdiets', 'GdietController@index');  
    Route::any('/gdiets/{id}/edit', 'GdietController@edit');
    Route::any('/gdiets/update/{id}', 'GdietController@update');
    Route::any('/gdiets/create', 'GdietController@create');
    Route::any('/gdiets/store', 'GdietController@store');
    Route::any('/gdiets/destroy', 'GdietController@destroy');
    Route::any('/gdiets/ajaxData', 'GdietController@ajaxData');

    Route::any('chats', 'ChatController@index');  
    Route::any('/chats/getChatById', 'ChatController@getChatById');  
    Route::any('/chats/addChat', 'ChatController@addChat');  
    Route::any('/chats/getUnReadChat', 'ChatController@getUnReadChat');  
    
    Route::any('/notifications', 'NotificationController@index');  
    Route::any('/notifications/ajaxNotificationData', 'NotificationController@ajaxNotificationData');  
    
    Route::any('/notifications/ajaxSendNotification', 'NotificationController@ajaxSendNotification');  
    
    
    


    Route::get('/schedules/getschedule', 'ScheduleController@getschedule');  
    Route::post('/schedules/addevent', 'ScheduleController@addevent');  
    Route::post('/schedules/delete', 'ScheduleController@delete');  
    Route::post('/schedules/drop', 'ScheduleController@drop');  
    Route::any('/schedules/ajaxData', 'ScheduleController@ajaxData');
    Route::any('/schedules/create', 'ScheduleController@create');
    Route::any('/schedules/store', 'ScheduleController@store');
    Route::any('/schedules/reschedule', 'ScheduleController@reschedule');   
    Route::any('/schedules/rescheduleAjax', 'ScheduleController@rescheduleAjax');   
    Route::post('/schedules/getTrainerAndSlot', 'ScheduleController@getTrainerAndSlot');
    Route::post('/schedules/reschedulesubmit', 'ScheduleController@reschedulesubmit');
    Route::any('/schedules/changeTrainer', "ScheduleController@changeTrainer");
    Route::any('schedules/createTest','ScheduleController@createTest');
        
    
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
    Route::any('/videos/addvideo/{id}','VideoController@addVideo');
    

    Route::resource('levels','LevelController');
    Route::any('levels', 'LevelController@index');  
    Route::any('/levels/{id}/edit', 'LevelController@edit');
    Route::any('/levels/update/{id}', 'LevelController@update');
    Route::any('/levels/create', 'LevelController@create');
    Route::any('/levels/store', 'LevelController@store');
    Route::any('/levels/destroy', 'LevelController@destroy');
    Route::any('/levels/ajaxData', 'LevelController@ajaxData');
    Route::any('/levels/setDefault', 'LevelController@setDefault');
    

    
    Route::any('diets', 'DietController@index');  
    
    Route::any('/diets/update/{id}', 'DietController@update');
    Route::any('/diets/create', 'DietController@create');
    Route::any('/diets/store', 'DietController@store');
    Route::any('/diets/destroy', 'DietController@destroy');
    Route::any('/diets/ajaxData', 'DietController@ajaxData');
    Route::resource('diets','DietController');

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
    Route::any('/categories/getCategoryById', 'CategoryController@getCategoryById');     
    Route::resource('categories','CategoryController');

    Route::any('batches', 'BatchController@index');  
    Route::any('/batches/{id}/edit', 'BatchController@edit');
    Route::any('/batches/update/{id}', 'BatchController@update');
    Route::any('/batches/create', 'BatchController@create');
    Route::any('/batches/store', 'BatchController@store');
    Route::any('/batches/destroy', 'BatchController@destroy');
    Route::any('/batches/ajaxData', 'BatchController@ajaxData'); 
    
    

    Route::resource('users','UserController');
    Route::any('/users/ajaxData', 'UserController@ajaxData');  
    Route::any('/users/ajaxDataNew', 'UserController@ajaxDataNew');  
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


    Route::resource('recipes','RecipeController');
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

    Route::resource('warmups','WarmupController');
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
    Route::post('/testrequests/assigntrainer', 'TestrequestController@assigntrainer');

    Route::post('/users/getTrainerList', 'UserController@getTrainerList');
    Route::get('/user/getNewUsers', 'UserController@getNewUsers');
    Route::any('/user/contact/{id}', 'UserController@updateUser');
    Route::get('/trainer/getTrainerList', 'UserController@getTrainerList1');
    Route::post('/users/assigntrainer', 'UserController@assigntrainer');

    Route::any('/users/settings/{id}', 'UserController@settings');

    Route::any('reviews', 'ReviewController@index');
    Route::any('/reviews/ajaxData', 'ReviewController@ajaxData');


    
    Route::resource('leveldatas','LeveldataController');
    Route::any('leveldatas', 'LeveldataController@index');  
    Route::any('/leveldatas/{id}/edit', 'LeveldataController@edit');
    Route::any('/leveldatas/update/{id}', 'LeveldataController@update');
    Route::any('/leveldatas/store', 'LeveldataController@store');
    Route::any('/leveldatas/ajaxData', 'LeveldataController@ajaxData');
    Route::any('/leveldatas/getLevelByCategory', 'LeveldataController@getLevelByCategory');
    Route::get('/leveldatas/edit/{id}','LeveldataController@edit');
    

});

Route::any('/chats/getClietChat', 'ChatController@getClietChat');
Route::any('/chats/markread', 'ChatController@markread');
Route::any('/chats/clientSubmitChat', 'ChatController@clientSubmitChat');



Route::group(['prefix' => 'trainer','middleware' => ['auth']], function() { 

    


	Route::Post('/schedules/trainer', 'ScheduleController@getTrainerScheduleOnDate');
	Route::get('/schedules/users/{id}','ScheduleController@getUsersBySlot');
	Route::get('/schedules/trainer_month_schedule', 'ScheduleController@getTrainerScheduleOfMonth');
	Route::get('/schedules/filter_trainer_jobs', 'ScheduleController@filterTrainerJobs');
	Route::get('/clients', 'UserController@getAssignedClients')->name('trainer_clients');
	
	
	Route::resource('master-diets','MasterDietController');
    Route::any('master-diets', 'MasterDietController@index')->name('master_diets')->middleware('is_admin');  
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
    Route::post('/testrequests/testtimer', 'TestrequestController@testtimer');
    
    
    
});




Route::group(['prefix' => 'rooms','middleware' => ['auth']], function() {    
     Route::post('/join_regular_room', 'VideoRoomsController@videoRegularRoom')->name('joinRegular');
     Route::post('/join_demo_room', 'VideoRoomsController@videoDemoRoom')->name('joinDemo');
     Route::post('/join_test_room', 'VideoRoomsController@videoTestRoom')->name('joinTest');
     Route::get('/join/regular/{slug}', 'VideoRoomsController@joinRegularConfress')->name('joinRegularConfress');
     Route::get('/join/demo/{slug}', 'VideoRoomsController@joinDemoConfress')->name('joinDemoConfress');
     Route::get('/join/test/{slug}', 'VideoRoomsController@joinTestConfress')->name('joinTestConfress');
});


