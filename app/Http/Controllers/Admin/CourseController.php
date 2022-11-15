<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Topic;
use App\Models\Settings;
use Spatie\Permission\Models\Role;
use DB;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class CourseController extends Controller
{
    /**
    * Instantiate a new controller instance.
    *
    * @return void
    */
    
    public function __construct()
    {
        $this->middleware('permission:course-list|course-create|course-edit|course-delete', ['only' => ['index','show']]);

        $this->middleware('permission:course-create', ['only' => ['create','store']]);

        $this->middleware('permission:course-edit', ['only' => ['edit','update']]);

        $this->middleware('permission:course-delete', ['only' => ['destroy','deleteAll']]);
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

        $data['courses'] = QueryBuilder::for(Course::class)
                                ->allowedFilters([
                                    'name', 'course_identifier',
                                    AllowedFilter::exact('status'),
                                    AllowedFilter::scope('created_at'),
                                ])
                                ->sortable(['id' => 'desc'])
                                ->paginate(20);
    
        //dd(DB::getQueryLog()); 
                                
        $data['i'] = ($request->input('page', 1) - 1) * 20;

        $data['name'] = $request->input('filter.name'); 
        $data['course_identifier'] = $request->input('filter.course_identifier');
        $data['status'] = $request->input('filter.status');
        $data['created_at'] = $request->input('filter.created_at');          

        return view('admin.courses.index', $data);
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function create()
    {
        $topics = Topic::pluck('name','id')->all();

        return view('admin.courses.create', compact('topics'));
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
            'name'      => 'required',
            'duration'  => 'required|numeric|gte:0',
            'amount'    => 'required'          
        ]);

        $input = $request->all(); 
       
        $course = Course::create($input);        

        return redirect()->route('courses.index')->with('success', 'Success! Course created successfully');
    }

    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */

    public function show($id)
    {
        $course = Course::findOrFail($id);

        return view('admin.courses.show', compact('course'));
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */

    public function edit($id)
    {
        $course = Course::findOrFail($id);    

        $topics = Topic::pluck('name','id')->all();

        return view('admin.courses.edit', compact('course', 'topics'));
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
            'name'      => 'required',
            'duration'  => 'required|numeric|gte:0',
            'amount'    => 'required'          
        ]);

        $input = $request->only(['name', 'course_identifier', 'topic_id', 'duration', 'amount', 'certification', 'other_information', 'meta_title', 'meta_description', 'status']);       

        $course = Course::findOrFail($id);

        $course->update($input);  

        $querystring = $request->query(); 

        return redirect()->route('courses.index', $querystring)->with('success','Success! Course updated successfully');
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

        Course::find($id)->delete();

        return redirect()->route('courses.index', $querystring)->with('success','Success! Course deleted successfully');
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
                Course::find($id)->delete();                                    
            }            
            return redirect()->route('courses.index', $querystring)->with('success','Success! Course deleted successfully');
        }else{
            return redirect()->route('courses.index', $querystring);
        }         
    }

    public function settings(Request $request){
        //DB::enableQueryLog();
        $settings = Settings::where("settings.delete_flag", 'N')
                        ->select('*')
                        ->orderBy('settings.id', 'desc')                        
                        ->first();
        //dd(DB::getQueryLog());   
        //echo '<pre>'; print_r($settings); echo '</pre>'; exit;
        return view('admin.courses.settings', compact('settings'));
    }
    public function updatesettings(Request $request){
        $this->validate($request, [
            'hard_copy_charge'      => 'required|numeric|gte:0',
            'total_marks'  => 'required|numeric|gte:0'
        ]);

        $input = $request->only(['hard_copy_charge', 'total_marks']); 
        $settings = Settings::Active($input['hard_copy_charge'], $input['total_marks']);

        if($settings->count()){
            $settings->update($input);
        }else{
            Settings::where('delete_flag', 'N')
            ->update(['delete_flag'=>'Y']);
            
            $settings->create($input);
        }
        return redirect()->route('course.settings')->with('success','Success! settings saved successfully');
    }
}
