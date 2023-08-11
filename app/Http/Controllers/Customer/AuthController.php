<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('logout');
    }
    
    public function loginform()
    {
        return view('customer.auth.login');
    }

    public function login(Request $request)
    {
        try{
            $data= $request->all();
            $validator = \Validator::make($data, [
                'email' => 'required|string|email|exists:users',
                'password' => 'required|string',
            ]);
            if ($validator->fails()) 
            {
                return redirect()->back()->with('error',$validator->errors()->first());
            }
            // $user = User::where('email',$request->email)->first();
        
            if(Auth::attempt(['email' => $data['email'], 'password' => $data['password'],'status'=>'active','role'=>'customer'])
            ){
                \Session::put('user',$data['email']);
                request()->session()->flash('success','Successfully login');
                return redirect('customer/dashboard')->with('success','Successfully login');
            }
            
            else
            {
                return redirect()->back()->with('error','Invalid Password pleas try again!');
            }
        }
        catch(\Exception $e)
        {
            return redirect()->back()->with('error',$e->getMessage());
        }
    }
}
