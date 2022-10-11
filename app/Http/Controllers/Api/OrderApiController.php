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

        $input = $request->only('course_id');

	    $validator = Validator::make($input, [        	
            'course_id' => 'required',            
        ]);

        if ($validator->fails()) {

            $errors = $validator->errors()->getMessages();

            $custom_error = [];

            foreach ($errors as $key => $value) {
                if(is_array($value)){
                    $custom_error[$key] = Arr::first($value);
                }else{
                    $custom_error[$key] = $value;
                }
            }
                               
            return response()->json([                                
                                'data'      => $custom_error, 
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
                'user_id'       => $id,
                'course_id'     => $request->course_id,
                'name'          => $course->name,
                'duration'      => $course->duration,
                'amount'        => $course->amount,
                'expiry_date'   => Carbon::now()->addDays((int)$course->duration),               
            ]);

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
            Enrollment::where('id', $request->enrollment_id)
                        ->update(['status' => 1]);
        }  
        
        return response()->json([   
                                'data'    => 'Status updated',                                
                                'status'  => 'success'
                            ], 200); 
                  
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
     
}
