<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Topic;
use Spatie\Permission\Models\Role;
use DB;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class TopicController extends Controller
{
    /**
    * Instantiate a new controller instance.
    *
    * @return void
    */
    
    public function __construct()
    {
        $this->middleware('permission:topic-list|topic-create|topic-edit|topic-delete', ['only' => ['index','show']]);

        $this->middleware('permission:topic-create', ['only' => ['create','store']]);

        $this->middleware('permission:topic-edit', ['only' => ['edit','update']]);

        $this->middleware('permission:topic-delete', ['only' => ['destroy','deleteAll']]);
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

        $data['topics'] = QueryBuilder::for(Topic::class)
                                ->allowedFilters([
                                    'name',
                                    AllowedFilter::exact('status'),
                                    AllowedFilter::scope('created_at'),
                                ])
                                ->sortable(['id' => 'desc'])
                                ->paginate(20);
    
        //dd(DB::getQueryLog()); 

        $data['i'] = ($request->input('page', 1) - 1) * 20; 

        $data['name'] = $request->input('filter.name'); 
        $data['status'] = $request->input('filter.status');
        $data['created_at'] = $request->input('filter.created_at');          

        return view('admin.topics.index', $data); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.topics.create');
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
            'name' => 'required'            
        ]);

        $input = $request->all(); 
       
        $topic = Topic::create($input);        

        return redirect()->route('topics.index')->with('success', 'Success! Topic created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $topic = Topic::findOrFail($id);

        return view('admin.topics.show', compact('topic'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $topic = Topic::findOrFail($id);    

        return view('admin.topics.edit', compact('topic'));
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
            'name' => 'required'
        ]);

        $input = $request->only(['name', 'meta_title', 'meta_description', 'status']);       

        $topic = Topic::find($id);

        $topic->update($input);  

        $querystring = $request->query(); 

        return redirect()->route('topics.index', $querystring)->with('success','Success! Topic updated successfully');
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

        Topic::find($id)->delete();

        return redirect()->route('topics.index', $querystring)->with('success','Success! Topic deleted successfully');
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
                Topic::find($id)->delete();                                    
            }            
            return redirect()->route('topics.index', $querystring)->with('success','Success! Topic deleted successfully');
        }else{
            return redirect()->route('topics.index', $querystring);
        }         
    }
}
