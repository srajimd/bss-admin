<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Receipt;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ReceiptApiController extends Controller
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

        $receipts = QueryBuilder::for(Receipt::class)
                        ->allowedFilters([
                            'file_path',
                            AllowedFilter::scope('created_at'),
                            AllowedFilter::scope('course_id'),
                            AllowedFilter::scope('user_id')
                        ])                                             
                        ->sortable(['id' => 'desc'])
                        ->paginate($items_per_page);        

        return response()->json([
                                'data'    => $receipts,  
                                'status'  => 'success'
                            ], 200);                         
    }

     /**
     * Create a receipt in storage.
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
            'receipt'   => 'required'         
        ]);

        if ($validator->fails()) {

            $errors = $validator->errors()->getMessages();

            return response()->json([                                
                                'data'      => $errors, 
                                'status'    => 'failure'
                            ], 400);
        }        
        
        $file_path =  $this->createImage($input['receipt']);      

        $isEnrollExist = Receipt::CheckEnrollmentExist($id, $request->enrollment_id, $request->course_id);
            
        if($isEnrollExist->count()){
            $receipt = Receipt::where('enrollment_id', $request->enrollment_id)
                        ->update(['file_path' => $file_path]);

        }else{
            $receipt = Receipt::create([
                'user_id'        => $id,
                'course_id'      => $request->course_id,
                'enrollment_id'  => $request->enrollment_id,
                'file_path'      => $file_path              
            ]);
        }

        return response()->json([   
            'data' =>array('message' => 'Receipt has been uploaded successfully'),                               
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
        $receipt = Receipt::find($id);

        if($receipt){
            $response = [
                'data'    => $receipt,  
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

    public function createImage($img)
    {

        $folderPath = "bss/receipts/";

        $image_parts = explode(";base64,", $img);
        $image_type_aux = explode("image/", $image_parts[0]);
      
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $file = $folderPath . uniqid() . '.'.$image_type;

        //file_put_contents($file, $image_base64);
        Storage::disk('local')->put($file, $image_base64);

        return $file;

    }
}
