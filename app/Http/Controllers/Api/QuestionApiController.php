<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Course;
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

        //$course_id = $request->input('filter.course_id');
        $course_id = $input['course_id'];

        $id = auth()->user()->id;

       // DB::enableQueryLog();

        $results = DB::table('question_user')             
                        ->where('user_id', $id)             
                        ->where('course_id', $course_id) 
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

            foreach($random_questions as $random_question){
                $questionsIds[] = $random_question->id;
                 $data[] = [
                    'user_id' => $id,
                    'question_id' => $random_question->id, 
                    'course_id' => $course_id,
                    'answer_id' => 0,
                    'enrollment_id' => 0,
                    'date_added' => ''
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
                                'data'    => [
                                    'exam'=>$exam_details,
                                    'questions'=>$questions
                                ],  
                                'status'  => 'success'
                            ], 200);                         
    }

    public function create(Request $request)
    {    
        $id = auth()->user()->id;         

        $input = $request->only('course_id', 'questions');

        $validator = Validator::make($input, [          
            'course_id' => 'required', 
            'questions' => 'required|array',            
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

        if($request->has('questions')){
            $questions = $request->questions; 
        }else{
            $questions = array();
        } 

        $course_id = $request->input('course_id');               

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
           
            foreach($questions as $question){ 
                DB::table('question_user')
                        ->where('user_id', $id)             
                        ->where('course_id', $course_id)
                        ->where('question_id', $question['question_id'])
                        ->update([
                            'answer_id' => $question['answer_id']
                        ]); 
            }

            return response()->json([   
                                'data' =>array('message' => 'Updated Successfully'),                               
                                'status'=> 'success'
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
