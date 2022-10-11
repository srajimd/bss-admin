<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Video;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class VideoApiController extends Controller
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

        $videos = QueryBuilder::for(Video::class)
                        ->where('status','1')
                        ->allowedFilters([
                            'name',
                            AllowedFilter::exact('status'),
                            AllowedFilter::scope('created_at'),
                            AllowedFilter::scope('course_id'),
                        ])                                             
                        ->sortable(['id' => 'desc'])
                        ->paginate($items_per_page);        

        return response()->json([
                                'data'    => $videos,  
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
        $video = Video::find($id);

        if($video){
            $response = [
                'data'    => $video,  
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
