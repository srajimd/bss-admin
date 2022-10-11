<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use DB;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Arr;
use App\Imports\QuestionsImport;
use Excel;
use Maatwebsite\Excel\HeadingRowImport;

class ImportController extends Controller
{
    /**
    * Instantiate a new controller instance.
    *
    * @return void
    */
    
    public function __construct()
    {        

        $this->middleware('permission:import-create', ['only' => ['create','store']]);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.imports.create');
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
            'file' => 'required|mimes:xls,xlsx',          
        ],
        [            
            'file.required' => 'You have to choose an excel/csv!',        
        ]);

        $input = $request->all();
        
        $input['file_name'] = $request->file('file')->getClientOriginalName();
 
        $input['file_path'] = $request->file('file')->store('bss/imports');   

        $input['file_identity'] = date('mdYHis') . uniqid();   

        $headings = (new HeadingRowImport)->toArray(request()->file('file')); 

        if(isset($headings[0][0])){
            $count = count($headings[0][0]);
        }else{
            $count = 0;
        }

        Excel::import(new QuestionsImport($count), request()->file('file'));

        return redirect()->route('questions.index')->with('success', 'Success! Questions imported successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
