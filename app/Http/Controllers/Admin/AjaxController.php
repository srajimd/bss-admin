<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Topic;
use App\Models\Answer;
use DB;

class AjaxController extends Controller
{
    /**
    * Instantiate a new controller instance.
    *
    * @return void
    */
    
    public function __construct()
    {
        
    }

    public function getCoursesByTopicId(Request $request){
        if($request->has('topic_id')){
            $topic_id = $request->topic_id;
        }else{
            $topic_id = 0;            
        }

        $courses = Course::where('topic_id', $topic_id)->get(); 

        return response()->json([
                            'status' => true, 
                            'courses' => $courses
                        ]);
    }

    public function setQuestionCorrectAnswer(Request $request){
        if($request->has('question_id')){
            $question_id = $request->question_id;
        }else{
            $question_id = 0;            
        }

        if($request->has('answer_id')){
            $answer_id = $request->answer_id;
        }else{
            $answer_id = 0;            
        }

        $answer = Answer::where('question_id', $question_id)
                            ->where('id', $answer_id)
                            ->first(); 
        if($answer){

            Answer::where('question_id', $question_id)
                    ->update([
                        'correct_answer' => 0
                    ]);
                           

            $answer->update([
                'correct_answer' => 1
            ]);
        }

        return response()->json([
                            'status' => true,                             
                        ]);
    }

    
}
