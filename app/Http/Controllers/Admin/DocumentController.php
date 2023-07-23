<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Topic;
use App\Models\Document;
use App\Models\Course;
use Spatie\Permission\Models\Role;
use DB;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Arr;

class DocumentController extends Controller
{
    /**
    * Instantiate a new controller instance.
    *
    * @return void
    */
    
    public function __construct()
    {
        $this->middleware('permission:document-list|document-create|document-edit|document-delete', ['only' => ['index','show']]);

        $this->middleware('permission:document-create', ['only' => ['create','store']]);

        $this->middleware('permission:document-edit', ['only' => ['edit','update']]);

        $this->middleware('permission:document-delete', ['only' => ['destroy','deleteAll']]);
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

        $data['documents'] = QueryBuilder::for(Document::class)
                                ->join('courses', 'documents.course_id', 'courses.id')
                                ->select('documents.*')
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
       
        return view('admin.documents.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $topics = Topic::pluck('name','id')->all(); 

        return view('admin.documents.create', compact('topics'));
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
            'topic_id'    => 'required',    
            'course_id'   => 'required',
            'file'        => 'required|mimes:pdf',          
        ],
        [
            'topic_id.required'  => 'You have to choose a topic!', 
            'course_id.required' => 'You have to choose a course!',
            'file.required'      => 'You have to choose a pdf!',        
        ]);

        $input = $request->all();
        
        $input['file_name'] = $request->file('file')->getClientOriginalName();
 
        $input['file_path'] = $request->file('file')->store('bss/documents');   

        $input['file_identity'] = date('mdYHis') . uniqid();    

        $document = Document::create($input);  

        return redirect()->route('documents.index')->with('success', 'Success! Study material created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $document = $document = Document::find($id);   
        return view('admin.documents.show', compact('document'));
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $document = Document::findOrFail($id);    

        $topics = Topic::pluck('name','id')->all(); 

        return view('admin.documents.edit', compact('document', 'topics'));
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
        $document = Document::findOrFail($id);       

        $rules = [
            'name'       => 'required', 
            'topic_id'   => 'required',
            'course_id'  => 'required',
        ];

        $msg = [
            'topic_id.required'  => 'You have to choose a topic!',
            'course_id.required' => 'You have to choose a course!',        
            'file.required'      => 'You have to choose a pdf!'
        ];

        if($document->file_path && Storage::exists($document->file_path)){            
        }else{           
            $rules = Arr::add($rules, 'file', 'required|mimes:pdf');        
        }

        $this->validate($request, $rules, $msg);

        $input = $request->only(['name', 'topic_id', 'course_id', 'file', 'status']);       
        
        if ($request->hasFile('file')) {   

            if($document->file_path && Storage::exists($document->file_path)){
                Storage::delete($document->file_path); 
            }   

            $input['file_name'] = $request->file('file')->getClientOriginalName();
     
            $input['file_path'] = $request->file('file')->store('bss/documents');

            $input['file_identity'] = date('mdYHis') . uniqid();
        }else{
            $input = Arr::except($input, array('file'));
        }

        $document->update($input);      

        $querystring = $request->query(); 

        return redirect()->route('documents.index', $querystring)->with('success','Success! Study material updated successfully');
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

        $document = Document::find($id);     
        $document->delete();               
        Storage::delete($document->file_path); 

        return redirect()->route('documents.index', $querystring)->with('success','Success! Study material deleted successfully');
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
                $document = Document::find($id);     
                $document->delete();               
                Storage::delete($document->file_path);                               
            }            
            return redirect()->route('documents.index', $querystring)->with('success','Success! Study material deleted successfully');
        }else{
            return redirect()->route('documents.index', $querystring);
        }         
    }
}
