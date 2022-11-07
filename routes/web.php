<?php

  

use Illuminate\Support\Facades\Route;

  

use App\Http\Controllers\HomeController;

use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\TopicController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\SyllabusController;
use App\Http\Controllers\Admin\UnitController; 
use App\Http\Controllers\Admin\DocumentController;
use App\Http\Controllers\Admin\VideoController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\EnrollmentController;
use App\Http\Controllers\Admin\ImportController;
use App\Http\Controllers\Admin\AjaxController;


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

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::prefix('admin')->group(function () {

    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminLoginController::class, 'login'])->name('admin.login.submit');
    
    Route::group(['middleware' => ['auth:admin']], function() {    

        Route::post('/admins/deleteAll', [AdminController::class, 'deleteAll'])->name('admins.deleteAll');
        Route::resource('admins', AdminController::class);

        Route::post('/users/deleteAll', [UserController::class, 'deleteAll'])->name('users.deleteAll');
        Route::resource('users', UserController::class);

        Route::resource('roles', RoleController::class);

        Route::get('/profile/settings', [ProfileController::class, 'edit'])->name('profile');
        Route::patch('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

        Route::post('/topics/deleteAll', [TopicController::class, 'deleteAll'])->name('topics.deleteAll');
        Route::resource('topics', TopicController::class);

        Route::post('/courses/deleteAll', [CourseController::class, 'deleteAll'])->name('courses.deleteAll');
        Route::resource('courses', CourseController::class);

        Route::post('/syllabi/deleteAll', [SyllabusController::class, 'deleteAll'])->name('syllabi.deleteAll');
        Route::resource('syllabi', SyllabusController::class); 

        Route::post('/units/deleteAll', [UnitController::class, 'deleteAll'])->name('units.deleteAll');
        Route::resource('units', UnitController::class);

        Route::post('/documents/deleteAll', [DocumentController::class, 'deleteAll'])->name('documents.deleteAll');
        Route::resource('documents', DocumentController::class);

        Route::post('/videos/deleteAll', [VideoController::class, 'deleteAll'])->name('videos.deleteAll');
        Route::get('/video/{file}', [VideoController::class, 'display'])->name('video.display');
        Route::resource('videos', VideoController::class);

        Route::post('/questions/deleteAll', [QuestionController::class, 'deleteAll'])->name('questions.deleteAll');
        Route::resource('questions', QuestionController::class);

        Route::resource('imports', ImportController::class);

        Route::resource('enrollments', EnrollmentController::class); 
        Route::get('/showaddress', [EnrollmentController::class,'showAddress'])->name('showaddress');
        Route::post('/post/updatestatus', [EnrollmentController::class,'updateStatus'])->name('post.updatestatus');  

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.home');    
        Route::get('/', [DashboardController::class, 'index']);   

        Route::get('/getCoursesByTopicId', [AjaxController::class, 'getCoursesByTopicId'])->name('topic.courses');

        Route::post('/setQuestionCorrectAnswer', [AjaxController::class, 'setQuestionCorrectAnswer'])->name('question.setAnswer');


    });
});