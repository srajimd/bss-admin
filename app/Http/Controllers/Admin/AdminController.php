<?php

namespace App\Http\Controllers\Admin;   

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use Illuminate\Support\Arr;

class AdminController extends Controller
{
    /**
    * Instantiate a new controller instance.
    *
    * @return void
    */
    
    public function __construct()
    {
        $this->middleware('permission:admin-list|admin-create|admin-edit|admin-delete', ['only' => ['index','show']]);

        $this->middleware('permission:admin-create', ['only' => ['create','store']]);

        $this->middleware('permission:admin-edit', ['only' => ['edit','update']]);

        $this->middleware('permission:admin-delete', ['only' => ['destroy','deleteAll']]);
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

        $user_id = auth()->user()->id;

        $search = $request->input('search');

        $data['admins'] = Admin::where('id', '!=' , 1)
                            ->where('id', '!=' , $user_id)                       
                            ->where(function ($query) use ($search){
                                return $query->where('id', 'LIKE', "%{$search}%") 
                                        ->orwhere('name', 'LIKE', "%{$search}%") 
                                        ->orWhere('email', 'LIKE', "%{$search}%");
                            })                        
                            ->sortable(['id' => 'desc'])
                            ->paginate(20);

        //dd(DB::getQueryLog());     

        $data['i'] = ($request->input('page', 1) - 1) * 20;

        $data['search'] = $search;        

        return view('admin.admins.index', $data);    
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function create()
    {
        $all_roles = Role::pluck('name','name')->all();

        $roles = Arr::except($all_roles,['super-master-admin']);

        return view('admin.admins.create', compact('roles'));
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
            'name'              => 'required|string|max:255',
            'email'             => 'required|email|unique:admins,email',
            'password'          => 'required|same:confirm_password',
            'confirm_password'  => 'required|same:password',
            'roles'             => 'required'
        ]);

        $input = $request->all();

        $input['password'] = Hash::make($input['password']);

        $admin = Admin::create($input);

        $admin->assignRole($request->input('roles'));

        return redirect()->route('admins.index')->with('success', 'Success! Admin created successfully');
    }

    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */

    public function show($id)
    {
        
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */

    public function edit($id)
    {
        $user_id = auth()->user()->id; 

        if($id == 1 || $id == $user_id){
            return redirect()->route('admins.index');
        }else{
            $admin = Admin::findOrFail($id);

            $all_roles = Role::pluck('name','name')->all();

            $roles = Arr::except($all_roles,['super-master-admin']);

            $adminRole = $admin->roles->pluck('name','name')->all();

            return view('admin.admins.edit', compact('admin','roles','adminRole'));
        }
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
            'name'              => 'required|string|max:255',
            'email'             => 'required|email|unique:admins,email,'.$id,
            'password'          => 'same:confirm_password',
            'confirm_password'  => 'same:password',
            'roles'             => 'required'
        ]);

        $input = $request->all();

        if(!empty($input['password'])){ 
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = Arr::except($input, array('password'));    
        }

        $admin = Admin::find($id);

        $admin->update($input);

        DB::table('model_has_roles')->where('model_id', $id)->delete();

        $admin->assignRole($request->input('roles'));

        return redirect()->route('admins.index')->with('success','Success! Admin updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        $user_id = auth()->user()->id; 

        if($id != 1 && $id != $user_id){
            Admin::find($id)->delete();
        }

        return redirect()->route('admins.index')->with('success','Success! Admin deleted successfully');
    }

    /**
     * Delete multiple resource from storage.
     *
     * @param  param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function deleteAll(Request $request)
    {
        $user_id = auth()->user()->id; 

        $input = $request->input('selected');

        if(!empty($input)){
            foreach($input as $id){            
                if($id != 1 && $id != $user_id){                   
                    Admin::find($id)->delete();                    
                }
            }            
            return redirect()->route('admins.index')->with('success','Success! Admin deleted successfully');
        }else{
            return redirect()->route('admins.index');
        }         
    }
}