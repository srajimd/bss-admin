<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Topic;
use App\Models\Video;
use App\Models\Course;
use Spatie\Permission\Models\Role;
use DB;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Arr;

class VideoController extends Controller
{
    /**
    * Instantiate a new controller instance.
    *
    * @return void
    */
    
    public function __construct()
    {
        $this->middleware('permission:video-list|video-create|video-edit|video-delete', ['only' => ['index','show']]);

        $this->middleware('permission:video-create', ['only' => ['create','store']]);

        $this->middleware('permission:video-edit', ['only' => ['edit','update']]);

        $this->middleware('permission:video-delete', ['only' => ['destroy','deleteAll']]);
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

        $data['videos'] = QueryBuilder::for(Video::class)
                            ->join('courses', 'videos.course_id', 'courses.id')
                            ->select('videos.*')
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

        return view('admin.videos.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $topics = Topic::pluck('name','id')->all();

        return view('admin.videos.create', compact('topics'));
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
            'name'        => 'required',  
            'topic_id'      => 'required',  
            'course_id'   => 'required',
            'file'        => 'required|mimes:mp4',          
        ],
        [
            'topic_id.required'      => 'You have to choose a topic!',
            'course_id.required' => 'You have to choose a course!',
            'file.required'      => 'You have to choose a video!',        
        ]);

        $input = $request->all();
        
        $input['file_name'] = $request->file('file')->getClientOriginalName();
 
        $input['file_path'] = $request->file('file')->store('bss/videos'); 

        $input['file_identity'] = date('mdYHis') . uniqid();              

        $video = Video::create($input);  

        return redirect()->route('videos.index')->with('success', 'Success! Video created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $video = Video::findOrFail($id);        

        return view('admin.videos.show', compact('video'));  
    }

    public function display(Request $request, $file_identity)
    { 
        $video = Video::where('file_identity', $file_identity)->first(); 
        
        if(!$video){
            abort(404);
        }

        if (!Storage::exists($video->file_path)) {
            abort(404);
        }        

       /* $content = Storage::get('public/videos/'.$file); 
        
        $mime = Storage::mimeType('public/videos/'.$file);      
                            
        return response($content)->header('Content-Type', $mime); */  

        $filePath = storage_path('app/'.$video->file_path);

        $stream = new \App\Http\VideoStream($filePath);

        return response()->stream(function() use ($stream) {
            $stream->start();
        });
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $video = Video::findOrFail($id);    

        $topics = Topic::pluck('name','id')->all(); 

        return view('admin.videos.edit', compact('video', 'topics'));
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
        $video = Video::findOrFail($id);       

        $rules = [
            'name'       => 'required', 
            'topic_id'      => 'required',    
            'course_id'  => 'required',
        ];

        $msg = [
            'topic_id.required'      => 'You have to choose a topic!',
            'course_id.required' => 'You have to choose a course!',        
            'file.required'      => 'You have to choose a video!'
        ];

        if($video->file_path && Storage::exists($video->file_path)){            
        }else{           
            $rules = Arr::add($rules, 'file', 'required|mimes:mp4');        
        }

        $this->validate($request, $rules, $msg);

        $input = $request->only(['name', 'topic_id', 'course_id', 'file', 'status']);       
        
        if ($request->hasFile('file')) {   

            if($video->file_path && Storage::exists($video->file_path)){
                Storage::delete($video->file_path); 
            }   

            $input['file_name'] = $request->file('file')->getClientOriginalName();
     
            $input['file_path'] = $request->file('file')->store('bss/videos');

            $input['file_identity'] = date('mdYHis') . uniqid();
        }else{
            $input = Arr::except($input, array('file'));
        }

        $video->update($input);      

        $querystring = $request->query(); 

        return redirect()->route('videos.index', $querystring)->with('success','Success! Video updated successfully');
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

        $video = Video::find($id);     
        $video->delete();               
        Storage::delete($video->file_path); 

        return redirect()->route('videos.index', $querystring)->with('success','Success! Video deleted successfully');
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
                $video = Video::find($id);     
                $video->delete();               
                Storage::delete($video->file_path);                               
            }            
            return redirect()->route('videos.index', $querystring)->with('success','Success! Video deleted successfully');
        }else{
            return redirect()->route('videos.index', $querystring);
        }         
    }
}
