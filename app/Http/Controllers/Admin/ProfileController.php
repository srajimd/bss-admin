<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Hash;
use Illuminate\Support\Arr;


class ProfileController extends Controller
{
    /**
    * Show the form for editing the specified resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function edit()
    {

        $id = auth()->user()->id; 

       	$admin = Admin::findOrFail($id);

		return view('admin.admins.profile', compact('admin'));      
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */

    public function update(Request $request)
    {

    	$id = auth()->user()->id;

        $this->validate($request, [
            'name'              => 'required|string|max:255',
            'email'             => 'required|email|unique:admins,email,'.$id,
            'password'          => 'same:confirm_password',
            'confirm_password'  => 'same:password',
        ]);

        $input = $request->all();

        if(!empty($input['password'])){ 
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = Arr::except($input, array('password'));    
        }

        $admin = Admin::find($id);

        $admin->update($input);        

        return redirect()->route('admin.home')->with('success','Success! Settings updated successfully');
    }
}
