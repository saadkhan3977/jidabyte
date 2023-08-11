<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register_form()
    {
        return view('auth.register');
    }
    public function register(Request $request){
        // return $request->all();
        $this->validate($request,[
            'name'=>'string|required|min:2',
            'email'=>'string|required|unique:users,email',
            'password'=>'required|min:6',
        ]);
        $data=$request->all();
        // dd($data);
        User::create([
            'name'=>$data['name'],
            'email'=>$data['email'],
            'password'=>Hash::make($data['password']),
            'role'=> $data['role'],
            'status'=>'active'
        ]);
        \Session::put('user',$data['email']);
        
        request()->session()->flash('success','Successfully registered');
        return redirect('/');
    }
}
