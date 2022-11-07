<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Arr;

class CourseApiController extends Controller
{    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {      
        $is_user_logged = auth('sanctum')->check() ? "Y" : "N"; 

        $user_id = '';

        if($is_user_logged == 'Y'){
            $user_id = auth('sanctum')->user()->id;            
        }

        if($request->has('items_per_page')){
            $items_per_page = (int)$request->input('items_per_page');
        }else{
            $items_per_page = '20';
        }

        $courses = QueryBuilder::for(Course::class)
                        ->where('status','1')
                        ->allowedFilters([
                            'name',
                            AllowedFilter::exact('status'),
                            AllowedFilter::scope('created_at'),
                            AllowedFilter::scope('topic_id'),
                        ])                        
                        ->sortable(['id' => 'desc'])
                        ->paginate($items_per_page); 
                        
        foreach($courses as $ckey => $course){
            $courses[$ckey]->enrollment_id = "0";
            $courses[$ckey]->is_subscribed = "0";

            if($user_id){
                $enrollment = Enrollment::where('course_id', $course->id)
                        ->where('user_id', $user_id)
                        ->where('status', 1)
                        ->select('enrollments.id')
                        ->first();

                if($enrollment){
                    $courses[$ckey]->enrollment_id = $enrollment->id;
                    $courses[$ckey]->is_subscribed = "1";
                }
            }
        }

        return response()->json([
                                'data'    => $courses,  
                                'status'  => 'success'
                            ], 200);                         
    }

    public function coursesList(Request $request)
    {
        $id = auth()->user()->id; 

        if($request->has('items_per_page')){
            $items_per_page = (int)$request->input('items_per_page');
        }else{
            $items_per_page = '20';
        }

        $courses = QueryBuilder::for(Course::class)
                        ->where('status','1')
                        ->allowedFilters([
                            'name',
                            AllowedFilter::exact('status'),
                            AllowedFilter::scope('created_at'),
                            AllowedFilter::scope('topic_id'),
                        ])                        
                        ->sortable(['id' => 'desc'])
                        ->paginate($items_per_page); 
                        
        foreach($courses as $ckey => $course){
            $enrollment = Enrollment::where('course_id', $course->id)
                    ->where('user_id', $id)
                    ->where('status', 1)
                    ->select('enrollments.id')
                    ->first();

            //print_r($enrollment->id); exit;

            $courses[$ckey]->enrollment_id = "0";
            $courses[$ckey]->is_subscribed = "0";
            if($enrollment){
                $courses[$ckey]->enrollment_id = $enrollment->id;
                $courses[$ckey]->is_subscribed = "1";
            }
        }

        return response()->json([
                                'data'    => $courses,  
                                'status'  => 'success'
                            ], 200);                         
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $course = Course::find($id);

        if($course){
            $response = [
                'data'    => $course,  
                'status'  => 'success'
            ];
            $status_code = 200;            
        }else{
            $response = [
                'data'    => null,  
                'status'  => 'failure'
            ];
            $status_code = 404;            
        }

        return response()->json($response, $status_code); 
    }    
    
}
