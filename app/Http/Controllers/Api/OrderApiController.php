<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class OrderApiController extends Controller
{
    /**
    * Update profile for authenticated user.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
    *
    */

    public function create(Request $request)
    {         
        $id = auth()->user()->id;         

        $input = $request->only('course_id', 'transaction_id');

	    $validator = Validator::make($input, [        	
            'course_id' => 'required', 
            'transaction_id' => 'required',           
        ]);

        if ($validator->fails()) {

            $errors = $validator->errors()->getMessages();

            return response()->json([                                
                                'data'      => $errors, 
                                'status'    => 'failure'
                            ], 400);
        }

        $course = Course::active()->find($request->course_id);

        //DB::enableQueryLog();    

        $now = Carbon::now();

        $user_enrolled = Enrollment::where('user_id', $id)
                                    ->where('status', '1')
                                    ->where('course_id', $request->course_id)
                                    ->where('expiry_date', '>', $now)    
                                    ->count(); 
        //dd(DB::getQueryLog()); 
        if(!$course){
            return response()->json([                                
                                'data' => array('message' => 'Course may be inactive/not found'),  
                                'status'    => 'failure'
                            ], 400);            
        }elseif($user_enrolled > 0){
            return response()->json([                                
                                'data' => array('message' => 'Course has been already active'),  
                                'status'    => 'failure'
                            ], 400); 
        }else{

            $enrollment = Enrollment::create([
                'user_id'        => $id,
                'course_id'      => $request->course_id,
                'transaction_id' => $request->transaction_id,
                'name'           => $course->name,
                'duration'       => $course->duration,
                'amount'         => $course->amount,
                'expiry_date'    => Carbon::now()->addDays((int)$course->duration),               
            ]);

            $enrollment->enrollment_id = $enrollment->id;
            unset($enrollment->id);

            //echo '<pre>'; print_r($enrollment); echo '</pre>'; exit;

            return response()->json([   
                                'data'    => $enrollment,                                
                                'status'  => 'success'
                            ], 200); 
        }	       
    } 

    /**
    * Update payment status.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
    *
    */

    public function status(Request $request)
    {    
        $id = auth()->user()->id;         

        if($request->has('enrollment_id')){
            $enroll_update_result = Enrollment::where('id', $request->enrollment_id)
                        ->update(['status' => 1]);
        } 
        
        if(!empty($enroll_update_result)){
            return response()->json([   
                'data'    => 'Status updated',                                
                'status'  => 'success'
            ], 200);
        } else {
            return response()->json([                                
                'data' => array('message' => 'Status update has been failed. No enrollment found.'),  
                'status'    => 'failure'
            ], 400); 
        }      
                  
    } 

    /**
    * My courses.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
    *
    */

    public function courses(Request $request, $type = "active")
    {    
        $id = auth()->user()->id; 

        if($request->has('items_per_page')){
            $items_per_page = (int)$request->input('items_per_page');
        }else{
            $items_per_page = '20';
        }        

        if($type == "active"){
            $operation = '>';
        }else{
            $operation = '<=';
        }

        $now = Carbon::now();

        $courses = Enrollment::where('user_id', $id)
                                ->where('status', '1')                                    
                                ->where('expiry_date', $operation, $now)    
                                ->paginate($items_per_page);  
                    
        return response()->json([   
                                'data'    => $courses,                                
                                'status'  => 'success'
                            ], 200); 
                  
    } 

    public function getMyCertificates(Request $request)
    {        
        $id = auth()->user()->id; 

        $now = Carbon::now();
        
        //DB::enableQueryLog();
        $users_data = Enrollment::join('users', 'users.id', '=', 'enrollments.user_id')
        ->join('courses', 'courses.id', '=', 'enrollments.user_id')
        ->join('topics', 'topics.id', '=', 'courses.topic_id')
        ->select('courses.id as course_id', 'courses.name as course_name', 'users.name as user_name', 'users.id as user_id', 'enrollments.id as enrollment_id', 'enrollments.certificate_path', 'topics.id as topic_id', 'topics.name as topic_name')
        ->where('enrollments.status', 1)
        ->whereDate('expiry_date','<',$now)
        ->get();
           

        //dd(DB::getQueryLog());        
        $user_data = [];
        foreach($users_data as $ukey => $userdata){

            $question_attempt_count = DB::table('question_user')
                ->join("answers",function($join){
                    $join->on('answers.question_id','=', 'question_user.question_id')
                        ->on('answers.id','=', 'question_user.answer_id');
                })
                ->where('question_user.enrollment_id',$userdata->enrollment_id)
                ->where('answers.status',1)
                ->where('answers.correct_answer',1)
                ->count(); 

            if($question_attempt_count<8) continue;

            $user_data[$ukey] = [
                'user_id' => $userdata->user_id,
                'user_name' => $userdata->user_name,
                'course_id' => $userdata->course_id, 
                'course_name' => $userdata->course_name,
                'enrollment_id' => $userdata->enrollment_id,
                'topic_id' => $userdata->topic_id,
                'topic_name' => $userdata->topic_name
            ]; 
            
            if(empty($userdata->certificate_path) && !File::exists($userdata->certificate_path)) {
                $certificate_file = Enrollment::generateCertificate($userdata);
                DB::table('enrollments')
                    ->where('id', $userdata->enrollment_id)
                    ->update([
                        'certificate_path' => $certificate_file
                    ]);                
            }else{
                $certificate_file = $userdata->certificate_path;
            } 
            
            $user_data[$ukey]['certificate_url'] = str_replace('public/','',url('/').Storage::url('app/'.$certificate_file));
            $user_data[$ukey]['grade'] = Enrollment::getGrade($question_attempt_count);          
        } 
        if(!empty($user_data)){
            return response()->json([   
                'data'    => $user_data,                                
                'status'  => 'success'
            ], 200); 
        }else{
            return response()->json([   
                'data'    => [
                    'message'=>'No certificate found'
                ],                                
                'status'  => 'failure'
            ], 400); 
        }
            
    }
     
}
