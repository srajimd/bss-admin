<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Arr;

class UserApiController extends Controller
{
    /**
    * Handle a login request to the mobile app.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
    *
    */

    public function login(Request $request)
    {         
	    $validator = Validator::make($request->all(), [
        	'email' 		=> 'required|email',
	        'password' 	 	=> 'required',
	        'device_name' 	=> 'required',
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

	    $user = User::where('email', $request->email)->first();

	    if (! $user || ! Hash::check($request->password, $user->password)) {	       
	        return response()->json([                                
                                'data'		=> array('message' => 'The provided credentials are incorrect.'), 
                                'status'  	=> 'failure'
                            ], 400);
	    }

	    $token = $user->createToken($request->device_name)->plainTextToken; 

	    return response()->json([
                                'access_token'  => $token, 
                                'user'	  		=> $user, 
                                'status'  		=> 'success'
                            ], 200);      
    }

    /**
    * Handle a register request to the mobile app.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
    *
    */

    public function register(Request $request)
    {         
	    $validator = Validator::make($request->all(), [
        	'name' 			=> ['required', 'string', 'max:255'],
            'email' 		=> ['required', 'string', 'email', 'max:255', 'unique:users'],
            'mobile_number' => 'required',
            'password' 		=> ['required', 'string', 'min:8'],
	        'device_name' 	=> 'required',
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
                                'data'		=> $custom_error, 
                                'status'	=> 'failure'
                            ], 400);
        }

	    $user = User::create([
            'name' 		    => $request->name,
            'email' 	    => $request->email,
            'mobile_number' => $request->mobile_number,
            'password' 	    => Hash::make($request->password)        
        ]);

	    $token = $user->createToken($request->device_name)->plainTextToken; 

	    return response()->json([
                                'access_token'  => $token, 
                                'user'	  		=> $user, 
                                'status'  		=> 'success'
                            ], 200);      
    }

    /**
    * Handle a forgot password.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
    *
    */

    public function forgot_password(Request $request)
    {
        $validator = Validator::make($request->all(), [           
            'email' => 'required|email',           
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

        try {
            $response = Password::sendResetLink($request->only('email'));
            switch ($response) {
                case Password::RESET_LINK_SENT:                    
                    return response()->json([                                
                                'data'    => trans($response), 
                                'status'  => 'success'
                            ], 200);
                case Password::INVALID_USER:
                    return response()->json([                                
                                'data'    => trans($response), 
                                'status'  => 'failure'
                            ], 400);
            }
        } catch (\Swift_TransportException $ex) {            
            return response()->json([                                
                                'data'    => $ex->getMessage(), 
                                'status'  => 'failure'
                            ], 400);

        } catch (Exception $ex) {            
            return response()->json([                                
                                'data'    => $ex->getMessage(), 
                                'status'  => 'failure'
                            ], 400);            
        }            
    }
}
