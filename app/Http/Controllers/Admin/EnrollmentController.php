<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Enrollment;
use App\Models\Course;
use App\Models\Receipts;
use App\Models\HardCopyRequest;
use App\Models\User;
use Spatie\Permission\Models\Role;
use DB;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class EnrollmentController extends Controller
{
    /**
    * Instantiate a new controller instance.
    *
    * @return void
    */
    
    public function __construct()
    {
        $this->middleware('permission:enrollment-list', ['only' => ['index','show']]);        
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

        $data['enrollments'] = QueryBuilder::for(Enrollment::class)
                                ->join('users', 'users.id', 'enrollments.user_id')
                                ->leftJoin('receipts', 'receipts.enrollment_id', 'enrollments.id')
                                //->where('enrollments.status', '1')
                                ->allowedFilters([
                                    'name',
                                    AllowedFilter::exact('status'),
                                    AllowedFilter::scope('created_at'),
                                ])
                                ->select('users.name as customer','users.email', 'enrollments.id','enrollments.name', 'enrollments.status', 'enrollments.expiry_date', 'enrollments.duration', 'enrollments.status', 'enrollments.amount', 'enrollments.created_at', 'enrollments.certificate_path as certificate', 'receipts.file_path as receipt', 'enrollments.total_marks','enrollments.transaction_id') 
                                ->sortable(['id' => 'desc'])
                                ->paginate(20);    
                         
        $data['i'] = ($request->input('page', 1) - 1) * 20;

        $data['name'] = $request->input('filter.name'); 
        $data['status'] = $request->input('filter.status');
        $data['created_at'] = $request->input('filter.created_at');          

        return view('admin.enrollments.index', $data);
    } 

    public function showAddress(Request $request){
        $hardcopyrequest = HardCopyRequest::join('users','users.id', '=', 'hard_copy_requests.user_id')
                        ->where("hard_copy_requests.enrollment_id", $request->input('enrollment_id'))
                        ->select('users.name as customer','users.email','hard_copy_requests.id','hard_copy_requests.address1','hard_copy_requests.city','hard_copy_requests.state','hard_copy_requests.zipcode','hard_copy_requests.country','hard_copy_requests.mobile','hard_copy_requests.created_at')
                        ->orderBy('hard_copy_requests.id', 'desc')                        
                        ->first();
        return view('admin.hardcopyrequest.show', compact('hardcopyrequest'));
    }

    public function updateStatus(Request $request){
        $input = $request->input();
        if($input['enrollment_id'] && isset($input['status'])){
            $enroll_update_result = Enrollment::where('id', $input['enrollment_id'])
                        ->update(['status' => $input['status']]);

            if(!empty($enroll_update_result)){
                return response()->json([                                
                    'status'  => 'success'
                ], 200);
            } else {
                return response()->json([                                
                    'status'  => 'failure'
                ], 400);
            }
        }else{
            return response()->json([                                
                'status'  => 'failure'
            ], 400);
        } 
    }
}
