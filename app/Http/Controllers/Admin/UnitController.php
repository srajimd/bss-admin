<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Unit;
use App\Models\Syllabus;
use Spatie\Permission\Models\Role;
use DB;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class UnitController extends Controller
{
    /**
    * Instantiate a new controller instance.
    *
    * @return void
    */
    
    public function __construct()
    {
        $this->middleware('permission:unit-list|unit-create|unit-edit|unit-delete', ['only' => ['index','show']]);

        $this->middleware('permission:unit-create', ['only' => ['create','store']]);

        $this->middleware('permission:unit-edit', ['only' => ['edit','update']]);

        $this->middleware('permission:unit-delete', ['only' => ['destroy','deleteAll']]);
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

        $data['units'] = QueryBuilder::for(Unit::class)
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

        return view('admin.units.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $syllabi = Syllabus::pluck('name','id')->all();

        return view('admin.units.create', compact('syllabi'));
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
            'syllabus_id'   => 'required'          
        ],
        [
            'syllabus_id.required' => 'You have to choose a syllabus!',        
        ]);

        $input = $request->all(); 
       
        $unit = Unit::create($input);        

        return redirect()->route('units.index')->with('success', 'Success! Unit created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $unit = Unit::findOrFail($id);

        return view('admin.units.show', compact('unit'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $unit = Unit::findOrFail($id);    

        $syllabi = Syllabus::pluck('name','id')->all();

        return view('admin.units.edit', compact('unit', 'syllabi'));
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
            'syllabus_id'   => 'required'          
        ],
        [
            'syllabus_id.required' => 'You have to choose a syllabus!',        
        ]);

        $input = $request->only(['name', 'syllabus_id', 'status']);       

        $unit = Unit::find($id);

        $unit->update($input);  

        $querystring = $request->query(); 

        return redirect()->route('units.index', $querystring)->with('success','Success! Unit updated successfully');
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

        Unit::find($id)->delete();

        return redirect()->route('units.index', $querystring)->with('success','Success! Unit deleted successfully');
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
                Unit::find($id)->delete();                                    
            }            
            return redirect()->route('units.index', $querystring)->with('success','Success! Unit deleted successfully');
        }else{
            return redirect()->route('units.index', $querystring);
        }         
    }
}
