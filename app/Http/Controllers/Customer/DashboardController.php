<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Hash;
class DashboardController extends Controller
{
    public function index()
    {
        return view('customer.index');
    }

    public function changePassword()
    {
        return view('customer.layouts.changePassword');
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
   
        return redirect()->route('customer.dashboard')->with('success','Password successfully changed');
    }

    public function profile()
    {
        $profile=Auth()->user();
        return view('customer.users.profile')->with('profile',$profile);
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
