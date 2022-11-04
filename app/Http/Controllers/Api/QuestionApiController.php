<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Course;
use App\Models\Enrollment;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Arr;


class QuestionApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $input = $request->all();

        //print_r($input); exit;
        
        $validator = Validator::make($input, [        	
            'course_id' => 'required', 
            'enrollment_id' => 'required',           
        ]);

        if ($validator->fails()) {

            $errors = $validator->errors()->getMessages();
                                         
            return response()->json([                                
                                'data'      => $errors, 
                                'status'    => 'failure'
                            ], 400);
        }

        $course_id = $input['course_id'];
        $enrollment_id = $input['enrollment_id'];

        $id = auth()->user()->id;

       // DB::enableQueryLog();

        $results = DB::table('question_user')             
                        ->where('user_id', $id)             
                        ->where('course_id', $course_id) 
                        ->where('enrollment_id', $enrollment_id)
                        ->get();

        //dd(DB::getQueryLog());           

        $questionsIds = array();

        foreach($results as $result){
            $questionsIds[] = $result->question_id;
        }    

        if(!$questionsIds){
            $random_questions = Question::where('status','1')
                        ->where('course_id', $course_id)                         
                        ->select('id')
                        ->orderByRaw('RAND()')                    
                        ->limit(20)
                        ->get(); 

            $data = array();

            $now = Carbon::now();

            foreach($random_questions as $random_question){
                $questionsIds[] = $random_question->id;
                 $data[] = [
                    'user_id' => $id,
                    'question_id' => $random_question->id, 
                    'course_id' => $course_id,
                    'answer_id' => 0,
                    'enrollment_id' => $enrollment_id,
                    'date_added' => $now
                ];               
                
            } 

            DB::table('question_user')->insert($data); 
        } 

        $questions = Question::whereIn('id', $questionsIds)                      
                        ->leftJoin('question_user', 'questions.id', '=', 'question_user.question_id')
                        ->with(['answers' =>  function($query) {
                            $query->orderByRaw('RAND()');
                         }])
                        //->where('status','1') 
                        ->select('questions.*', 'question_user.answer_id')                      
                        ->where('question_user.user_id', $id)
                        ->where('question_user.course_id', $course_id)    
                        ->get();                                        

        return response()->json([
                                'data'    => $questions,  
                                'status'  => 'success'
                            ], 200);                         
    }

    public function create(Request $request)
    {    
        $id = auth()->user()->id;         

        $input = $request->only('course_id', 'enrollment_id', 'questions');

        $validator = Validator::make($input, [          
            'course_id' => 'required', 
            'enrollment_id' => 'required', 
            'questions' => 'required|array',            
        ]);

        if ($validator->fails()) {

            $errors = $validator->errors()->getMessages();

            /*$custom_error = [];

            foreach ($errors as $key => $value) {
                if(is_array($value)){
                    $custom_error[$key] = Arr::first($value);
                }else{
                    $custom_error[$key] = $value;
                }
            }*/
                               
            return response()->json([                                
                                'data'      => $errors, 
                                'status'    => 'failure'
                            ], 400);
        }

        if($request->has('questions')){
            $questions = $request->questions; 
        }else{
            $questions = array();
        } 

        //print_r($questions);

        $course_id = $request->input('course_id'); 
        $enrollment_id = $request->input('enrollment_id');              

        $course = Course::active()->find($course_id);

        if(!$course){
            return response()->json([                                
                                'data' => array('message' => 'Course may be inactive/not found'), 
                                'status' => 'failure'
                            ], 400);            
        }elseif(count($questions) == 0){
             return response()->json([                                
                                'data' => array('message' => 'Missing questions'), 
                                'status' => 'failure'
                            ], 400);
        }else{

            if(!empty($questions['question_id']) && !empty($questions['answer_id'])){
                DB::table('question_user')
                    ->where('user_id', $id)             
                    ->where('course_id', $course_id)
                    ->where('enrollment_id', $enrollment_id)
                    ->where('question_id', $questions['question_id'])
                    ->update([
                        'answer_id' => $questions['answer_id']
                    ]); 
            }else{
                foreach($questions as $question){
                    DB::table('question_user')
                            ->where('user_id', $id)             
                            ->where('course_id', $course_id)
                            ->where('enrollment_id', $enrollment_id)
                            ->where('question_id', $question['question_id'])
                            ->update([
                                'answer_id' => $question['answer_id']
                            ]); 
                }
            }

            return response()->json([   
                                'data' =>array('message' => 'Updated Successfully'),                               
                                'status'=> 'success'
                            ], 200); 
        }          
    }

    public function exam(Request $request)
    {         
        $id = auth()->user()->id;         

        $input = $request->only('course_id');

	    $validator = Validator::make($input, [        	
            'course_id' => 'required'           
        ]);

        if ($validator->fails()) {

            $errors = $validator->errors()->getMessages();

            return response()->json([                                
                                'data'      => $errors, 
                                'status'    => 'failure'
                            ], 400);
        }       

        //DB::enableQueryLog();    

        $now = Carbon::now();
        $enrollment = Enrollment::where('course_id', $request->course_id)
                            ->where('status', 1)
                            /*->where('expiry_date','>',$now)*/
                            ->where('user_id', '=', $id)
                            ->select('id as enrollment_id', DB::raw("'{$now}' as date"), 'duration', 'total_marks', 'expiry_date')
                            ->get();

        //dd(DB::getQueryLog());
        if(!$enrollment->count()){
            return response()->json([                                
                                'data' => array('message' => 'No exam has not been enrolled for this course'),  
                                'status'    => 'failure'
                            ], 400);            
        }else{  
            $is_completed=0; 
            $exam_details = [];           
            foreach($enrollment as $ekey => $enroll){
                if($enroll->expiry_date < $now){
                    return response()->json([                                
                        'data' => array('message' => 'Exam has been expired'),  
                        'status'    => 'failure'
                    ], 400); 
                }
                //DB::enableQueryLog();
                $results = DB::table('question_user')           
                        ->where('user_id', $id)             
                        ->where('course_id', $request->course_id) 
                        ->where('enrollment_id', $enroll->enrollment_id)
                        ->get();
                //dd(DB::getQueryLog());      
                //echo $id, '___', $request->course_id, '___', $enroll->enrollment_id;
                //echo count($results); exit;    
                if(count($results)>0){
                    $is_completed=1;
                }

                $exam_details[$ekey] = $enroll;
                $exam_details[$ekey]->is_completed = $is_completed;
            }
            
            return response()->json([   
                                'data'    => $exam_details,                                
                                'status'  => 'success'
                            ], 200); 
        }	       
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $question = Question::find($id);

        if($question){
            $response = [
                'data'    => $question,  
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
