<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Hash;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        return view('partner.index');
    }

    public function changePassword()
    {
        return view('partner.layouts.changePassword');
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
   
        return redirect()->route('partner.dashboard')->with('success','Password successfully changed');
    }

    public function profile()
    {
        $profile=Auth()->user();
        return view('partner.users.profile')->with('profile',$profile);
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
}
