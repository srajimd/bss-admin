<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Enrollment;
use App\Models\Course;
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
                                ->where('enrollments.status', '1')
                                ->allowedFilters([
                                    'name',
                                    AllowedFilter::exact('status'),
                                    AllowedFilter::scope('created_at'),
                                ])
                                ->select('users.name as customer','users.email', 'enrollments.*') 
                                ->sortable(['id' => 'desc'])
                                ->paginate(20);
    
        //dd(DB::getQueryLog()); 
                                
        $data['i'] = ($request->input('page', 1) - 1) * 20;

        $data['name'] = $request->input('filter.name'); 
        $data['status'] = $request->input('filter.status');
        $data['created_at'] = $request->input('filter.created_at');          

        return view('admin.enrollments.index', $data);
    } 


}
