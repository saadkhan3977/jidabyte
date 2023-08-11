<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Hash;
use App\Models\User;
use App\Models\Settings;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }

    public function profile()
    {
        $profile=Auth()->user();
        return view('admin.users.profile')->with('profile',$profile);
    }

    public function profileUpdate(Request $request,$id)
    {
        $user=User::findOrFail($id);
        $data=$request->all();
        $status=$user->fill($data)->save();

        if($status)
        {
            request()->session()->flash('success','Successfully updated your profile');
        }
        else
        {
            request()->session()->flash('error','Please try again!');
        }
        
        return redirect()->back();
    }


    public function settings()
    {
        $data= Settings::first();
        return view('admin.setting')->with('data',$data);
    }

    public function settingsUpdate(Request $request)
    {
     
        $this->validate($request,[
            'short_des'=>'required|string',
            'description'=>'required|string',
            'photo'=>'required',
            'logo'=>'required',
            'address'=>'required|string',
            'copyright'=>'required|string',
            'email'=>'required|email',
            'phone'=>'required|string',
        ]);
        $data=$request->all();
        $settings=Settings::first();
        if($settings)
        {
            $status=$settings->fill($data)->save();
        }
            else{
            $status=Settings::create($data);

        }
        if($status)
        {
            request()->session()->flash('success','Setting successfully updated');
        }

        else
        {
            request()->session()->flash('error','Please try again');
        }
        return redirect()->back();
    }

    public function changePassword()
    {
        return view('admin.layouts.changePassword');
    }

    public function changPasswordStore(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'new_password' => ['required'],
            'new_confirm_password' => ['same:new_password'],
        ]);

        $user = Auth::user();

        if (!Hash::check($request->input('current_password'), $user->password)) {
            return back()->withErrors(['error   ' => 'The current password is incorrect.']);
        }
   
        User::find(auth()->user()->id)->update(['password'=> Hash::make($request->new_password)]);
   
        return redirect()->route('admin.dashboard')->with('success','Password successfully changed');
    }
}
