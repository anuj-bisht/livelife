<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Use App\Rest;

Route::post('register', 'UserController@register');

Route::post('login', 'UserController@authenticate');
Route::post('sendotp', 'UserController@sendotp');

Route::get('testmail', 'UserController@testmail');

Route::get('getCategoryList', 'CategoryController@getCategoryList');

Route::get('getsetting', 'UserController@getsetting');

Route::get('/plans/getPlanList', 'PlanController@getPlanList');
Route::get('testOrder', 'DemorequestController@testOrder');
Route::get('/recipes/getRecipeList', 'RecipeController@getRecipeList');

Route::post('socialcheck', 'UserController@socialcheck');

Route::post('resendMailOtp', 'UserController@resendMailOtp');

Route::post('verifyEmailOtp', 'UserController@verifyEmailOtp');


Route::any('access_token', 'VideoController@access_token');

Route::any('testNotification', 'UserController@testNotification');

Route::any('/slots/getSlot', 'SlotController@getSlot');
Route::any('/slots/getBatch', 'SlotController@getBatch');
//Route::any('/slots/getSlotByBatch', 'SlotController@getSlotByBatch');

Route::post('contactus', 'UserController@contactus');

Route::post('getGenericData', 'GenericController@getGenericData');

Route::any('sendotpReg', 'UserController@sendotpReg');
Route::any('verifyotp', 'UserController@verifyotp');


Route::group(['middleware' => ['jwt.verify']], function() {

    

    Route::post('/users/getProfileData', 'UserController@getProfileData');

    Route::post('/orders/generateOrder', 'OrderController@generateOrder');
    Route::post('/orders/verifyPayment', 'OrderController@verifyPayment');

    Route::post('/getWarmupList', 'WarmupController@getWarmupList');

    Route::post('/users/updateWeight', 'UserController@updateWeight');
    
    

    Route::post('apilogout', 'UserController@apilogout');     
    Route::post('changePassword', 'UserController@changePassword');    
    Route::post('editMyProfile', 'UserController@editMyProfile');             
    Route::post('/users/getProfile', 'UserController@getProfile');
    Route::post('/users/getTrainerByCategory', 'UserController@getTrainerByCategory');
    Route::post('/users/editMyProfile', 'UserController@editMyProfile');
    Route::post('/users/getMyProfile', 'UserController@getMyProfile');
    
    Route::post('/plans/getPlanByCategory', 'PlanController@getPlanByCategory');
    
    
    Route::post('/users/getMyNotifications', 'UserController@getMyNotifications');

    Route::post('/schedules/getTrainerScheduleByCategory', 'ScheduleController@getTrainerScheduleByCategory');
    Route::post('/schedules/getScheduleByCategory', 'ScheduleController@getScheduleByCategory');
    Route::post('/schedules/getMyScheduleToday', 'ScheduleController@getMyScheduleToday');
    Route::post('/schedules/rescheduleRequest', 'ScheduleController@rescheduleRequest');
    Route::post('/schedules/getAllSchedule', 'ScheduleController@getAllSchedule');

    Route::post('/levels/getLevelListByUser', 'LevelController@getLevelListByUser');
    

    
    

    Route::post('/demorequests/addDemoRequest', 'DemorequestController@addDemoRequest');
    Route::post('/demorequests/getDemoRequestStatus', 'DemorequestController@getDemoRequestStatus');

    Route::post('/testrequests/addTestRequest', 'TestrequestController@addTestRequest');
    Route::post('/testrequests/getTestRequestStatus', 'TestrequestController@getTestRequestStatus');
    
    Route::post('/testrequests/addTimer', 'TestrequestController@addTimer');
    Route::post('/testrequests/getLeaderBoard', 'TestrequestController@getLeaderBoard');
    

    Route::post('/levels/getLevelList', 'LevelController@getLevelList');
    Route::post('/diets/getDietList', 'DietController@getDietList');
    Route::post('/diets/getDietByUser', 'DietController@getDietByUser');
    
    Route::post('/levels/getLevelById', 'LevelController@getLevelById');
    Route::post('/levels/getLevelData', 'LevelController@getLevelData');

    

    Route::post('/users/getSettings', 'UserController@getSettings');

    Route::post('getAccessToken', 'VideoController@getAccessToken');
    Route::post('/bookings/book', 'BookingController@book');

    Route::post('/orders/getsubscription', 'OrderController@getsubscription');

    Route::post('/users/addChat', 'UserController@addChat');
    Route::post('/users/getChat', 'UserController@getChat');

    Route::post('/users/addReview', 'UserController@addReview');    
    Route::post('/users/getMySubscriptionByCategory', 'UserController@getMySubscriptionByCategory');    

    
    
});

