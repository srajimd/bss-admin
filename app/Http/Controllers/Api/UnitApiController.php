<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Unit;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class UnitApiController extends Controller
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

        $units = QueryBuilder::for(Unit::class)
                        ->where('status','1')
                        ->allowedFilters([
                            'name',
                            AllowedFilter::exact('status'),
                            AllowedFilter::scope('created_at'),
                            AllowedFilter::scope('syllabus_id'),
                        ])                        
                        ->sortable(['id' => 'desc'])
                        ->paginate($items_per_page);        

        return response()->json([
                                'data'    => $units,  
                                'status'  => 'success'
                            ], 200);                         
    }
        
}
