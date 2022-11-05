<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Utilities\Constant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{

    public function getLogin(){

        return view('admin.login');
    }

    public function postLogin(Request $request){

        //check account login [email,pass,level]
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
            'level' => Constant::user_level_admin, // level Admin
        ];

        $remember = $request->remember;

        if (Auth::attempt($credentials, $remember)) {
            return redirect('admin/user');
        } else {
            return back()->with('notification', 'Email or Password is not correct');
        }
    }

    public function logout(){
        Auth::logout();
        return redirect('admin');
    }
}
