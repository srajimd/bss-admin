<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Topic;

use App\Models\Question;
use App\Models\Course;
use App\Models\Answer;
use Spatie\Permission\Models\Role;
use DB;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class QuestionController extends Controller
{
    /**
    * Instantiate a new controller instance.
    *
    * @return void
    */
    
    public function __construct()
    {
        $this->middleware('permission:question-list|question-create|question-edit|question-delete', ['only' => ['index','show']]);

        $this->middleware('permission:question-create', ['only' => ['create','store']]);

        $this->middleware('permission:question-edit', ['only' => ['edit','update']]);

        $this->middleware('permission:question-delete', ['only' => ['destroy','deleteAll']]);
    }

    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function index(Request $request)
    {
        //DB::enableQueryLog();

        $data = array();    

        $data['questions'] = QueryBuilder::for(Question::class)
                                ->join('courses', 'questions.course_id', 'courses.id')
                                ->select('questions.*')
                                ->allowedFilters([
                                    'name',
                                    AllowedFilter::exact('status'),
                                    AllowedFilter::scope('created_at'),
                                    AllowedFilter::exact('course_identifier', 'courses.course_identifier', false)
                                ])
                                ->sortable(['id' => 'desc'])
                                ->paginate(20);
    
        //dd(DB::getQueryLog()); 
                                
        $data['i'] = ($request->input('page', 1) - 1) * 20;

        $data['name'] = $request->input('filter.name'); 
        $data['status'] = $request->input('filter.status');
        $data['created_at'] = $request->input('filter.created_at'); 
        $data['course_identifier'] = $request->input('filter.course_identifier');

        return view('admin.questions.index', $data);
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function create()
    {        
        $topics = Topic::pluck('name','id')->all();           

        return view('admin.questions.create', compact('topics'));
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */

    public function store(Request $request)
    { 
        $this->validate($request, [
            'name'          => 'required', 
            'topic_id'      => 'required',    
            'course_id'     => 'required',
            'answer.*.name' => 'required',        
        ],
        [
            'topic_id.required'      => 'You have to choose a topic!',
            'course_id.required'     => 'You have to choose a course!',        
            'answer.*.name.required' => 'Answer should not be empty',
        ]);

        $input = $request->all();         
       
        $question = Question::create($input);        

        if($question && $request->has('answer')){
            
            $question->answers()->createMany($request->answer);
        }

        return redirect()->route('questions.index')->with('success', 'Success! Question created successfully');
    }

    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */

    public function show($id)
    {
        $question = Question::findOrFail($id);

        $answers = $question->answers()->get();

        return view('admin.questions.show', compact('question', 'answers'));
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */

    public function edit($id)
    {
        $question = Question::findOrFail($id);    

        $topics = Topic::pluck('name','id')->all(); 

        $answers = $question->answers()->get();    
        
        return view('admin.questions.edit', compact('question', 'topics', 'answers'));
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */

    public function update(Request $request, $id)
    { 
        $this->validate($request, [
            'name'          => 'required',
            'topic_id'      => 'required',     
            'course_id'     => 'required',
            'answer.*.name' => 'required',          
        ],
        [
            'topic_id.required'      => 'You have to choose a topic!',
            'course_id.required'     => 'You have to choose a course!',
            'answer.*.name.required' => 'Answer should not be empty',        
        ]);

        $input = $request->only(['name', 'topic_id', 'course_id', 'status', 'answer']);       
        $question = Question::find($id);

        $question->update($input);  

        if($question){
            Answer::where('question_id', $id)->delete();
            if($request->has('answer')){
                $question->answers()->createMany($request->answer);                             
            }            
        }

        $querystring = $request->query(); 

        return redirect()->route('questions.index', $querystring)->with('success','Success! Question updated successfully');
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */

    public function destroy($id)
    {
        $querystring = $request->query();

        Question::find($id)->delete();

        return redirect()->route('questions.index', $querystring)->with('success','Success! Question deleted successfully');
    }

    /**
    * Delete multiple resource from storage.
    *
    * @param  param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */

    public function deleteAll(Request $request)
    {        

        $querystring = $request->query();

        $input = $request->input('selected');

        if(!empty($input)){
            foreach($input as $id){                                               
                Question::find($id)->delete();                                    
            }            
            return redirect()->route('questions.index', $querystring)->with('success','Success! Question deleted successfully');
        }else{
            return redirect()->route('questions.index', $querystring);
        }         
    }
}
