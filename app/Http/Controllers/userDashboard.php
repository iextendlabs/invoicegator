<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class userDashboard extends Controller
{
    public function userDashboard()
    {
        if (session()->has('email')) {
            return view('userDashboard');
        } else {
            return redirect(route('Login'));
        }
    }
}
