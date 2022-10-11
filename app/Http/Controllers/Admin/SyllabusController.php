<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Topic;
use App\Models\Syllabus;
use App\Models\Course;
use App\Models\Lesson;
use Spatie\Permission\Models\Role;
use DB;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;

class SyllabusController extends Controller
{
    /**
    * Instantiate a new controller instance.
    *
    * @return void
    */
    
    public function __construct()
    {
        $this->middleware('permission:syllabus-list|syllabus-create|syllabus-edit|syllabus-delete', ['only' => ['index','show']]);

        $this->middleware('permission:syllabus-create', ['only' => ['create','store']]);

        $this->middleware('permission:syllabus-edit', ['only' => ['edit','update']]);

        $this->middleware('permission:syllabus-delete', ['only' => ['destroy','deleteAll']]);
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

        $data['syllabi'] = QueryBuilder::for(Syllabus::class)
                                ->join('courses', 'syllabi.course_id', 'courses.id')
                                 ->select('syllabi.*')
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

        return view('admin.syllabi.index', $data);
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function create()
    {        
        $topics = Topic::pluck('name','id')->all();           

        return view('admin.syllabi.create', compact('topics'));
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
            'lesson.*.name' => 'required',        
        ],
        [
            'topic_id.required'      => 'You have to choose a topic!',        
            'course_id.required'     => 'You have to choose a course!',        
            'lesson.*.name.required' => 'Title should not be empty',
        ]);

        $input = $request->all();         
       
        $syllabus = Syllabus::create($input);        

        if($syllabus && $request->has('lesson')){
            
            $syllabus->lessons()->createMany($request->lesson);
        }

        return redirect()->route('syllabi.index')->with('success', 'Success! Syllabus created successfully');
    }

    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */

    public function show($id)
    {
        $syllabus = Syllabus::findOrFail($id);

        $lessons = $syllabus->lessons()->get();

        return view('admin.syllabi.show', compact('syllabus', 'lessons'));
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */

    public function edit($id)
    {
        $syllabus = Syllabus::findOrFail($id);    

        $topics = Topic::pluck('name','id')->all(); 

        $lessons = $syllabus->lessons()->get();    
        
        return view('admin.syllabi.edit', compact('syllabus', 'topics', 'lessons'));
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
            'lesson.*.name' => 'required',          
        ],
        [
            'topic_id.required'      => 'You have to choose a topic!', 
            'course_id.required'     => 'You have to choose a course!',
            'lesson.*.name.required' => 'Title should not be empty',        
        ]);

        $input = $request->only(['name', 'topic_id', 'course_id', 'status', 'lesson']);       

        $syllabus = Syllabus::findOrFail($id);

        $syllabus->update($input);  

        if($syllabus){
            Lesson::where('syllabus_id', $id)->delete();
            if($request->has('lesson')){
                $syllabus->lessons()->createMany($request->lesson);                             
            }            
        }

        $querystring = $request->query(); 

        return redirect()->route('syllabi.index', $querystring)->with('success','Success! Syllabus updated successfully');
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

        Syllabus::find($id)->delete();

        return redirect()->route('syllabi.index', $querystring)->with('success','Success! Syllabus deleted successfully');
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
                Syllabus::find($id)->delete();                                    
            }            
            return redirect()->route('syllabi.index', $querystring)->with('success','Success! Syllabus deleted successfully');
        }else{
            return redirect()->route('syllabi.index', $querystring);
        }         
    }
}
