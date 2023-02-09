<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use App\Models\Task;
use Carbon\Carbon;
use Session;
use App\Models\Project;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;


class IndexController extends Controller
{

    public function index()
    {
        // display LOGIN form VIEW
        if (session('userEmail')) {
            return redirect()->route('user.dashboard');
        }
        return view('index');
    }

    public function login()
    {
        $user = User::where('email', session('userEmail'))->first();
        // display LOGIN form VIEW
        if (session('userEmail') && $user->user_role === 'client') {
            return redirect()->route('client.dashboard');
        } elseif (session('userEmail') && $user->user_role === 'developer') {
            return redirect()->route('user.dashboard');
        }
        return view('login');
    }

    public function registerUser(Request $request)
    {
        // validating inputs
        $request->validate([
            'name' => 'required|min:7',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|max:20',
            'company' => 'required',
            'address_line_one' => 'required',
            'address_line_two' => 'required',
            'country' => 'required',
            'roleSelected' => 'required'
        ]);

        $hash = Hash::make($request['password']);
        // creating record 
        $create = user::insert([
            'name' => $request['name'],
            'email' => $request['email'],
            'company' => $request['company'],
            'address_line_one' => $request['address_line_one'],
            'address_line_two' => $request['address_line_two'],
            'country' => $request['country'],
            'user_role' => $request['roleSelected'],
            'password' => $hash,
            'created_at' => Carbon::now(),
        ]);

        if ($create) {
            return redirect()->route('signUp')->with('status', 'success');
        } else {
            return redirect()->route('signUp')->with('status', 'denied');
        }
    }

    public function loginPost(Request $request)
    {
        // authenticate the user
        // retrun the user to dashboard
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'role' => 'required',
        ]);

        $auth = $this->authenticate($request);

        if ($auth === 'developer') {
            $request->session()->put('userEmail', $request['email']);
            return redirect()->route('user.dashboard');
        }

        if ($auth === 'client') {
            $request->session()->put('userEmail', $request['email']);
            return redirect()->route('client.dashboard');
        }

        if ($auth === false) {
            $request->session()->flash('success_msg', 'invalid credentials');
            return redirect()->route('userLogin');
        }
    }

    public function userDashboard(Request $request)
    {
        // if user has no session then the user will redirect to login blade
        if (!session('userEmail')) {
            return redirect()->route('userLogin');
        }

        $user = User::where('email', session('userEmail'))->get(['id', 'user_role']);
        // return all user related tasks to user's dashboard
        // paginate tasks
        $this->paginate_limit = $request->input('paginate_limit', config('app.paginate_limit'));
        $task = Task::where('user_id', $user[0]->id)->paginate($this->paginate_limit);
        $projects = Project::all();
        return view('users.userDashboard', ['tasks' => $task, 'projects' => $projects, 'role' => $user[0]->user_role]);
    }
    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */

    // authenticate user

    public function authenticate($request)
    {
        // match user entered password with DB hashed password 
        $user = User::where('email', $request['email'])->first();
        if ($user) {
            // match with db hashed pass key
            // match the role
            // redirect to dashboard according to role
            if (Hash::check($request['password'], $user->password)) {
                // Developer logged In
                if (($user->user_role === $request['role']) && ($request['role'] === 'developer')) {
                    return 'developer';
                }

                if (($user->user_role === $request['role']) && ($request['role'] === 'client')) {
                    return 'client';
                }

                // role not matched
                if ($user->user_role != $request['role']) {
                    // return 'invalid credentials';
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }


    public function destroy(Request $request)
    {
        // Auth::logout();
        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect(route('userLogin'));
    }


    // change password form
    public function ChangePasswordForm()
    {
        return view('users.changePassword');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'old-pass' => 'required',
            'new-pass' => 'required|min:8',
            'confirm-pass' => 'required|min:8',
        ]);

        // match new and confirm new password
        if ($request['new-pass'] === $request['confirm-pass']) {
            $data = User::where('email', session('userEmail'))->get();

            if (Hash::check($request['old-pass'], $data[0]->password)) {
                $update = User::where('email', session('userEmail'))->update([
                    'password' => Hash::make($request['new-pass'])
                ]);

                if ($update) {
                    $request->session()->flash('success', 'Password has been updated!!');
                    return redirect()->route('user.change.password');
                }
            } else {
                $request->session()->flash('success', 'Incorrect Old Password');
                return redirect()->route('user.change.password');
            }
        } else {
            $request->session()->flash('success', 'New & Confirm new password must be same');
            return redirect()->route('user.change.password');
        }
    }
}
