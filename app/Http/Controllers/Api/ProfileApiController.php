<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Arr;

class ProfileApiController extends Controller
{
    /**
    * Get the authenticated User.
    *
    * @return \Illuminate\Http\JsonResponse
    */

    public function show() {

        return response()->json([                                
                                'data'    => auth()->user(), 
                                'status'  => 'success'
                            ], 200); 
    }

    /**
    * Update profile for authenticated user.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
    *
    */

    public function update(Request $request)
    {    
        $id = auth()->user()->id;         

        $input = $request->except('password');

	    $validator = Validator::make($input, [
        	'name'              => 'required|string|max:255',
            'email'             => 'required|email|unique:users,email,'.$id,
            'mobile_number'     => 'required',            
        ]);

        if ($validator->fails()) {

            $errors = $validator->errors()->getMessages();

            $custom_error = [];

            foreach ($errors as $key => $value) {
                if(is_array($value)){
                    $custom_error[$key] = Arr::first($value);
                }else{
                    $custom_error[$key] = $value;
                }
            }
                               
            return response()->json([                                
                                'data'      => $custom_error, 
                                'status'    => 'failure'
                            ], 400);
        }

        $user = User::findOrFail($id);

        $user->update($input);

	    return response()->json([                                
                                'data'	  => $user, 
                                'status'  => 'success'
                            ], 200);      
    } 

     /**
    * Update password for authenticated user.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
    *
    */

    public function password(Request $request)
    {    
        $id = auth()->user()->id;     

        $input = $request->only('old_password', 'new_password', 'confirm_password');

        $validator = Validator::make($input, [
            'old_password'          => 'required',
            'new_password'          => 'required',
            'confirm_password'      => 'required|same:new_password'           
        ]);

        if ($validator->fails()) {

            $errors = $validator->errors()->getMessages();

            $custom_error = [];

            foreach ($errors as $key => $value) {
                if(is_array($value)){
                    $custom_error[$key] = Arr::first($value);
                }else{
                    $custom_error[$key] = $value;
                }
            }
                               
            return response()->json([                                
                                'data'      => $custom_error, 
                                'status'    => 'failure'
                            ], 400);
        }
        
        if ((Hash::check($input['old_password'], auth()->user()->password)) == false) {            
            $response = [                                
                'data'    => 'Check your old password', 
                'status'  => 'failure'
            ];

            $status_code = '400';
        }else {
            $user = User::findOrFail($id);

            $user->update(['password' => Hash::make($input['new_password'])]);
            
            $response = [                                
                'data'    => 'Password updated successfully', 
                'status'  => 'success'
            ];

            $status_code = '200';
        }

        return response()->json($response, $status_code);      
    }    

    /**
    * Log the user out (Invalidate the token).
    *
    * @return \Illuminate\Http\JsonResponse
    */

    public function logout(Request $request)
    {
        
        $request->user()->currentAccessToken()->delete();
                
        return response()->json([                                
                                'data'    => 'User logged out successfully', 
                                'status'  => 'success'
                            ], 200);   
    }  
}
