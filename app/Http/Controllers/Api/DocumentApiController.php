<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Document;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use DB;
use Illuminate\Support\Facades\Storage;

class DocumentApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->has('items_per_page')){
            $items_per_page = (int)$request->input('items_per_page');
        }else{
            $items_per_page = '20';
        }

        $documents = QueryBuilder::for(Document::class)
                        ->where('status','1')
                        ->select(DB::raw('documents.*,CONCAT("'.str_replace('public/','',url('/').Storage::url('app/')).'",documents.file_path) AS file_path'))
                        ->allowedFilters([
                            'name',
                            AllowedFilter::exact('status'),
                            AllowedFilter::scope('created_at'),
                            AllowedFilter::scope('course_id'),
                        ])                                             
                        ->sortable(['id' => 'desc'])
                        ->paginate($items_per_page);        

        return response()->json([
                                'data'    => $documents,  
                                'status'  => 'success'
                            ], 200);                         
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $document = Document::find($id);

        if($document){
            $response = [
                'data'    => $document,  
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
