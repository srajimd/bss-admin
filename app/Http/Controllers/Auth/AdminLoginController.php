<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AdminLoginController extends Controller
{
	/**
    * Create a new controller instance.
    *
    * @return void
    */

    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
    }

    /**
    * Show the admin login form.
    *
    * @return \Illuminate\View\View
    */

    public function showLoginForm()
    {
        return view('auth.admin-login');
    }

    /**
    * Handle a login request to the admin.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
    *
    * @throws \Illuminate\Validation\ValidationException
    */

    public function login(Request $request)
    {
        $request->validate([
            'email' 	=> 'required|email|string',
            'password'  => 'required|string',
        ]);
        
        if(Auth::guard('admin')->attempt($request->only('email','password'), $request->filled('remember'))){
        	//Authentication passed...
        	return redirect()->intended(route('admin.home'))->with('status','You are Logged in as Admin!');
    	}

    	return $this->sendFailedLoginResponse($request);        
    }

    /**
    * Get the failed login response instance.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Symfony\Component\HttpFoundation\Response
    *
    * @throws \Illuminate\Validation\ValidationException
    */

    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            'email' => [trans('auth.failed')],
        ]);
    }

    /**
    * Log the user out of the admin.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
    */

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        return redirect('admin.login');
    }
}
