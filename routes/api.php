<?php

use Illuminate\Support\Facades\Route;

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

/**
 * @group  User management
 *
 * APIs for managing users
 */

Route::get('avatar/{user_sl}/{filename}', 'AuthController@GetAvatar');

Route::get('join/{activation_token}', 'UserController@Join');

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', 'AuthController@Login');
    Route::post('register', 'AuthController@Register');
    Route::get('signup/activate/{token}', 'AuthController@signupActivate');

    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('logout', 'AuthController@Logout');
        Route::get('user', 'AuthController@User');
    });
});


/**
 * Reset Password Routes
 */
Route::post('create', 'PasswordResetController@create');
Route::group(['prefix' => 'password', 'middleware' => 'auth:api'], function () {
    Route::get('find/{token}', 'PasswordResetController@find');
    Route::post('reset', 'PasswordResetController@reset');
});


Route::group(['middleware' => 'auth:api'], function () {

    Route::group(['prefix' => 'faculty'], function () {

        // Fetch Trashed Teacher Data
        Route::get('/trashed/{id?}', 'UserController@FindTrashedTeachers')->middleware('super.admin.scope');

        // Fetch Teacher Data
        Route::get('/{id?}', 'UserController@FindTeachers')->middleware('teacher.scope');

        // Create New Teacher
        Route::post('/', 'UserController@CreateTeacher')->middleware('super.admin.scope');
    });

    Route::group(['prefix' => 'student'], function () {

        // Fetch Trashed Student Data
        Route::get('/trashed/{id?}', 'UserController@FindTrashedStudent')->middleware('super.admin.scope');

        // Fetch Student Data
        Route::get('/{id?}', 'UserController@FindStudent');

        // Create New Student
        Route::post('/', 'UserController@CreateStudent')->middleware('super.admin.scope');
    });




    Route::group(['prefix' => 'space'], function () {

        // Update Space Data
        Route::put('/', 'SpaceController@Update')->middleware('teacher.scope');

        // Invite Student To A Space
        Route::post('/invite/{usertype?}/{resend?}', 'SpaceController@Invite')->middleware('teacher.scope');

        // Fetch Invited Space Data
        Route::get('/find_invited/{id?}', 'SpaceController@FindInvited')->middleware('teacher.scope');

        // Fetch Trashed Space Data
        Route::get('/trashed/{id?}', 'SpaceController@FindTrashed')->middleware('teacher.scope');

        // Restore Trashed Space Data
        Route::get('/restore/{id}', 'SpaceController@Restore')->middleware('teacher.scope');

        // Fetch Space Data
        Route::get('/{id?}', 'SpaceController@Find')->middleware('teacher.scope');

        // Delete Space Data
        Route::delete('/{id}', 'SpaceController@Delete')->middleware('teacher.scope');

        // Create New Space
        Route::post('/', 'SpaceController@Create')->middleware('teacher.scope');
    });

    Route::group(['prefix' => 'class'], function () {

        // Update Class Data
        Route::put('/', 'ClassController@Update')->middleware('teacher.scope');

        // Invite Student To A Space
        Route::post('/invite/{usertype?}/{resend?}', 'ClassController@Invite')->middleware('teacher.scope');

        // Fetch Invited Space Data
        Route::get('/find_invited/{id?}', 'ClassController@FindInvited')->middleware('teacher.scope');

        // Fetch Trashed Class Data
        Route::get('/trashed/{id?}', 'ClassController@FindTrashed')->middleware('teacher.scope');

        // Restore Trashed Class Data
        Route::get('/restore/{id}', 'ClassController@Restore')->middleware('teacher.scope');

        // Fetch Class Data
        Route::get('/{id?}', 'ClassController@Find');

        // Delete Class Data
        Route::delete('/{id}', 'ClassController@Delete')->middleware('teacher.scope');

        // Create New Class
        Route::post('/', 'ClassController@Create')->middleware('teacher.scope');
    });

    Route::group(['prefix' => 'subject'], function () {

        // Update Subject Data
        Route::put('/', 'SubjectController@Update')->middleware('teacher.scope');

        // Fetch Trashed Subject Data
        Route::get('/trashed/{id?}', 'SubjectController@FindTrashed')->middleware('teacher.scope');

        // Restore Trashed Subject Data
        Route::get('/restore/{id}', 'SubjectController@Restore')->middleware('teacher.scope');

        // Fetch Subject Data
        Route::get('/{id?}', 'SubjectController@Find');

        // Delete Subject Data
        Route::delete('/{id}', 'SubjectController@Delete')->middleware('teacher.scope');

        // Create New Subject
        Route::post('/', 'SubjectController@Create')->middleware('teacher.scope');
    });

    Route::group(['prefix' => 'examination'], function () {

        // Map Questions with Examination
        Route::post('/addquestions', 'ExaminationController@MapQuestions')->middleware('teacher.scope');

        // Get Mapped Questions
        Route::get('/questions/{id}', 'ExaminationController@GetMappedQuestions')->middleware('teacher.scope');

        // Update Subject Data
        Route::put('/', 'ExaminationController@Update')->middleware('teacher.scope');

        // Fetch Trashed Subject Data
        Route::get('/trashed/{id?}', 'ExaminationController@FindTrashed')->middleware('teacher.scope');

        // Restore Trashed Subject Data
        Route::get('/restore/{id}', 'ExaminationController@Restore')->middleware('teacher.scope');

        // Fetch Subject Data
        Route::get('/{id?}', 'ExaminationController@Find')->middleware('teacher.scope');

        // Delete Subject Data
        Route::delete('/{id}', 'ExaminationController@Delete')->middleware('teacher.scope');

        // Create New Subject
        Route::post('/', 'ExaminationController@Create')->middleware('teacher.scope');
    });

    Route::group(['prefix' => 'question'], function () {

        // Update Subject Data
        Route::put('/', 'QuestionController@Update')->middleware('teacher.scope');

        // Fetch Trashed Subject Data
        Route::get('/trashed/{id?}', 'QuestionController@FindTrashed')->middleware('teacher.scope');

        // Restore Trashed Subject Data
        Route::get('/restore/{id}', 'QuestionController@Restore')->middleware('teacher.scope');

        // Fetch Subject Data
        Route::get('/{id?}', 'QuestionController@Find')->middleware('teacher.scope');

        // Delete Subject Data
        Route::delete('/{id}', 'QuestionController@Delete')->middleware('teacher.scope');

        // Create New Subject
        Route::post('/', 'QuestionController@Create')->middleware('teacher.scope');
    });

    Route::group(['prefix' => 'stats'], function () {
        // Fetch dashboard
        Route::get('/dashboard/overview', 'Statistics@DashboardOverview');
    });
});

Route::get('/ping', function () {
    $response = config('QuestApp.JsonResponse.success');
    return ResponseHelper($response);
});


Route::fallback(function () {
    $response = config('QuestApp.JsonResponse.404');
    $response['data']['message'] = 'Route Not Found';
    return ResponseHelper($response);
});
