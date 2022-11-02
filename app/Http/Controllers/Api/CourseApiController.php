<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use DB;

class CourseApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->has('items_per_page')){
            $items_per_page = (int)$request->input('items_per_page');
        }else{
            $items_per_page = '20';
        }

        $courses = QueryBuilder::for(Course::class)
                        ->leftJoin('enrollments', 'enrollments.course_id', '=', 'courses.id')
                        ->where('courses.status','1')
                        ->select(DB::raw('courses.*,IF(enrollments.status=1, 1, 0) AS is_subscribed'))
                        ->allowedFilters([
                            'name',
                            AllowedFilter::exact('courses.status'),
                            AllowedFilter::scope('courses.created_at'),
                            AllowedFilter::scope('courses.topic_id'),
                        ])                        
                        ->sortable(['id' => 'desc'])
                        ->paginate($items_per_page);        

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
