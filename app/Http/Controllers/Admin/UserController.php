<?php

namespace App\Http\Controllers\Admin;   

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use Illuminate\Support\Arr;

class UserController extends Controller
{
    /**
    * Instantiate a new controller instance.
    *
    * @return void
    */
    
    public function __construct()
    {
        $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index','show']]);

        $this->middleware('permission:user-create', ['only' => ['create','store']]);

        $this->middleware('permission:user-edit', ['only' => ['edit','update']]);

        $this->middleware('permission:user-delete', ['only' => ['destroy','deleteAll']]);
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

        $search = $request->input('search');

        $data['users'] = User::query()                        
                            ->where(function ($query) use ($search){
                                return $query->where('id', 'LIKE', "%{$search}%") 
                                        ->orwhere('name', 'LIKE', "%{$search}%") 
                                        ->orWhere('email', 'LIKE', "%{$search}%")
                                        ->orWhere('mobile_number', 'LIKE', "%{$search}%");
                            })                        
                            ->sortable(['id' => 'desc'])
                            ->paginate(20);

        //dd(DB::getQueryLog());     

        $data['i'] = ($request->input('page', 1) - 1) * 20;

        $data['search'] = $search;        

        return view('admin.users.index', $data);    
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function create()
    {        
        return view('admin.users.create');
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
            'email'             => 'required|email|unique:users,email',
            'mobile_number'     => 'required',
            'password'          => 'required|same:confirm_password',
            'confirm_password'  => 'required|same:password'            
        ]);

        $input = $request->all();

        $input['password'] = Hash::make($input['password']);

        $user = User::create($input);         

        return redirect()->route('users.index')->with('success', 'Success! User created successfully');
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
        $user = User::findOrFail($id);

        return view('admin.users.edit', compact('user'));        
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
            'email'             => 'required|email|unique:users,email,'.$id,
            'mobile_number'     => 'required',
            'password'          => 'same:confirm_password',
            'confirm_password'  => 'same:password'
        ]);

        $input = $request->all();

        if(!empty($input['password'])){ 
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = Arr::except($input, array('password'));    
        }

        $user = User::find($id);

        $user->update($input);
        
        return redirect()->route('users.index')->with('success','Success! User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {        
        User::find($id)->delete();
        
        return redirect()->route('users.index')->with('success','Success! User deleted successfully');
    }

    /**
     * Delete multiple resource from storage.
     *
     * @param  param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function deleteAll(Request $request)
    {        
        $input = $request->input('selected');

        if(!empty($input)){
            foreach($input as $id){                                        
                User::find($id)->delete();                                    
            }            
            return redirect()->route('users.index')->with('success','Success! User deleted successfully');
        }else{
            return redirect()->route('users.index');
        }         
    }
}