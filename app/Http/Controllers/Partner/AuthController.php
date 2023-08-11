<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function loginform()
    {
        return view('partner.auth.login');
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
        
            if(Auth::attempt(['email' => $data['email'], 'password' => $data['password'],'status'=>'active','role'=>'partner'])
            ){
                \Session::put('user',$data['email']);
                request()->session()->flash('success','Successfully login');
                return redirect('partner/dashboard')->with('success','Successfully login');
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
