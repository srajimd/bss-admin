<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HardCopyRequest;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Support\Facades\Validator;

class HardCopyRequestApiController extends Controller
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

        $hardcopyrequest = QueryBuilder::for(HardCopyRequest::class)
                        ->allowedFilters([
                            'file_path',
                            AllowedFilter::scope('created_at'),
                            AllowedFilter::scope('course_id'),
                            AllowedFilter::scope('user_id')
                        ])                                             
                        ->sortable(['id' => 'desc'])
                        ->paginate($items_per_page);        

        return response()->json([
                                'data'    => $hardcopyrequest,  
                                'status'  => 'success'
                            ], 200);                         
    }

     /**
     * Create a hard copy request in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {           
        $id = auth()->user()->id; 

        $input = $request->all();

	    $validator = Validator::make($input, [        	
            'course_id' => 'required',
            'enrollment_id' => 'required',
            'address1'        => 'required',  
            'address2'        => 'required',  
            'city'        => 'required',  
            'state'        => 'required',  
            'zipcode'        => 'required',  
            'mobile'        => 'required',      
        ]);

        if ($validator->fails()) {

            $errors = $validator->errors()->getMessages();

            return response()->json([                                
                                'data'      => $errors, 
                                'status'    => 'failure'
                            ], 400);
        }  
        $hardcopyrequest = HardCopyRequest::create([
            'user_id'        => $id,
            'course_id'      => $request->course_id,
            'enrollment_id' => $request->enrollment_id,
            'address1'      => $request->address1,
            'address2'      => $request->address2,
            'city'      => $request->city,
            'state'      => $request->state,
            'zipcode'      => $request->zipcode,
            'mobile'      => $request->mobile
        ]);

        return response()->json([   
            'data' =>array('message' => 'Your hard copy request has been sent successfully'),                               
            'status'=> 'success'
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
        $hardcopyrequest = HardCopyRequest::find($id);

        if($hardcopyrequest){
            $response = [
                'data'    => $hardcopyrequest,  
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
