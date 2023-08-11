<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

class AuthController extends Controller
{
    public function loginform()
    {
        return view('admin.auth.login');
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
        
            if(Auth::attempt(['email' => $data['email'], 'password' => $data['password'],'status'=>'active','role'=>'admin'])
            ){
                \Session::put('user',$data['email']);
                request()->session()->flash('success','Successfully login');
                return redirect('admin/dashboard')->with('success','Successfully login');
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
