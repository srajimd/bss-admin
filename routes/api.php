<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\Api\TopicApiController;
use App\Http\Controllers\Api\CourseApiController;
use App\Http\Controllers\Api\SyllabusApiController;
use App\Http\Controllers\Api\UnitApiController;
use App\Http\Controllers\Api\ProfileApiController;
use App\Http\Controllers\Api\OrderApiController;
use App\Http\Controllers\Api\DocumentApiController;
use App\Http\Controllers\Api\VideoApiController;
use App\Http\Controllers\Api\QuestionApiController;
use App\Http\Controllers\Api\ReceiptApiController;
use App\Http\Controllers\Api\HardCopyRequestApiController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
	Route::get('/profile/me', [ProfileApiController::class, 'show']);
	Route::patch('/profile/update', [ProfileApiController::class, 'update']);
	Route::patch('/profile/password/update', [ProfileApiController::class, 'password']);   
	Route::get('/profile/logout', [ProfileApiController::class, 'logout']);

	Route::post('/course/subscribe', [OrderApiController::class, 'create']);
	Route::post('/enroll/update/status', [OrderApiController::class, 'status']);
	Route::get('/my/course/{active}', [OrderApiController::class, 'courses']);

	Route::get('/get/questions', [QuestionApiController::class, 'index']);
	Route::post('/post/questions', [QuestionApiController::class, 'create']);
	Route::get('/get/exam', [QuestionApiController::class, 'exam']);

	Route::post('/post/receipt', [ReceiptApiController::class, 'create']);

	Route::post('/hardcopy/request', [HardCopyRequestApiController::class, 'create']);

	Route::post('/my/certificates', [OrderApiController::class, 'getMyCertificates']);

}); 

Route::post('/user/login', [UserApiController::class, 'login']);
Route::post('/user/register', [UserApiController::class, 'register']);
Route::post('/user/forgot/password', [UserApiController::class, 'forgot_password']);


Route::get('/get/topics', [TopicApiController::class, 'index']);
Route::get('/get/topic/{id}', [TopicApiController::class, 'show']);

Route::get('/get/courses', [CourseApiController::class, 'index']);
Route::get('/get/course/{id}', [CourseApiController::class, 'show']);

Route::get('/get/syllabuses', [SyllabusApiController::class, 'index']);
Route::get('/get/syllabus/{id}', [SyllabusApiController::class, 'show']);

Route::get('/get/documents', [DocumentApiController::class, 'index']);
Route::get('/get/document/{id}', [DocumentApiController::class, 'show']);

Route::get('/get/videos', [VideoApiController::class, 'index']);
Route::get('/get/video/{id}', [VideoApiController::class, 'show']);

Route::get('/get/units', [UnitApiController::class, 'index']);


