<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\TaskController;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Session;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Admin;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskLog;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    protected $paginate_limit;

    public function __construct(Request $request)
    {
        $this->paginate_limit = $request->input('paginate_limit', config('app.paginate_limit'));
    }


    public function AdminsignUp()
    {
        // display VIEW for admin SignUp Form
        return view('admin.adminSignUp');
    }
    
    public function storeAdmin(Request $request)
    {
        // validating inputs
        $request->validate([
            'username' => 'required|min:7',
            'email' => 'required|email|unique:admins',
            'password' => 'required|min:8|max:20',
        ]);
        
        $hash = Hash::make($request['password']);
        
        $create = Admin::create([
            'username' => $request['username'],
            'email' => $request['email'],
            'password' => $hash,
            'created_at' => Carbon::now(),
        ]);

        if ($create) {
            return redirect(route('AdminsignUp'))->with('status', 'success');
        } else {
            return redirect(route('AdminsignUp'))->with('status', 'denied');
        }
        
    }

    public function registerAdmin(Request $request)
    {
        // validating inputs
        $request->validate([
            'adminName' => 'required|min:7',
            'adminEmail' => 'required|email|unique:admins',
            'adminPassword' => 'required|min:8|max:20',
        ]);

        $hash = Hash::make($request['adminPassword']);
        
        $create = Admin::create([
            'username' => $request['adminName'],
            'email' => $request['adminEmail'],
            'password' => $hash,
            'created_at' => Carbon::now(),
        ]);

        if ($create) {
            return redirect(route('AdminsignUp'))->with('status', 'success');
        } else {
            return redirect(route('AdminsignUp'))->with('status', 'denied');
        }
    }

    public function adminLogin()
    {
        // return login VIEW for Admin
        return view('admin.adminLogin');
    }

    public function AdminLoginPost(Request $request)
    {
        $request->validate([
            'adminEmail' => 'required|email',
            'adminPassword' => 'required',
        ]);

        $auth = $this->authenticateAdmin($request);

        if ($auth === true) {
            $request->session()->put('adminEmail', $request['adminEmail']);
            return redirect()->route('adminDashboard');
        }

        $request->session()->flash('success_msg', 'invalid credentials');
        return redirect()->route('adminLogin');
    }


    public function authenticateAdmin($request)
    {
        // match credentials with DB
        // match with db hashed pass key
        // redirect to dashboard 

        $admin = Admin::where('email', '=', $request['adminEmail'])->first();
        if (empty($admin)) {
            $request->session()->flash('success_msg', 'Email-Address Not Fetched');
            return redirect()->route('adminLogin');
            // return redirect(url()->previous());
        }

        if (Hash::check($request['adminPassword'], $admin->password)) {
            $request->session()->put('adminEmail', $request['adminEmail']);
            return true;
        }
    }

    public function adminDashboard(Request $request)
    {
        // display user details
        // display project details
        // navigation (create USERS, TASKS AND LOGS and PROJECTS)
        // add pagination
        $project = Project::paginate($this->paginate_limit);
        $users = User::all();
        $totalUsers = User::count();
        return view('admin.adminDashboard', ['projects' => $project, 'users' => $users, 'totalUsers' => $totalUsers]);
    }

    public function showProjects(Request $request)
    {
        $this->paginate_limit = $request->input('paginate_limit', config('app.paginate_limit'));
        $projects = Project::paginate($this->paginate_limit);
        return view('admin.allProjects', ['projects' => $projects]);
    }

    public function deleteProject(Request $request, $id)
    {
        Project::where('id', $id)->delete();
        $request->session()->flash('success', 'Project Deleted');
        return redirect()->route('adminDashboard');
    }

    public function adminLogout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect(route('adminLogin'));
    }

    // create a new user (developer)
    public function createUserForm(Request $request)
    {
        // show create user form
        return view('admin.createUser');
    }

    public function createUser(Request $request)
    {
        // create a new User with role 
        $request->validate([
            'name' => 'required|min:7',
            'email' => 'required|email',
            'passkey' => 'required|min:8|max:20',
            'role' => 'required',
        ]);


        $user = new User();
        $hash = Hash::make($request['passkey']);

        $user->name      = $request['name'];
        $user->email     = $request['email'];
        $user->password  = $hash;
        $user->user_role = $request['role'];

        if ($user->save()) {
            $request->session()->flash('success', 'User Successfully Created');
            return redirect()->route('create.user');
        }
    }


    public function editUser($id)
    {
        $detail = User::where('id', $id)->get();
        return view('admin/editUser', ['details' => $detail]);
    }

    public function UpdateUser(Request $request)
    {
        $request->validate([
            'name' => 'required|min:7',
            'passkey' => 'required|min:8|max:20',
            'role' => 'required',
            'userId' => 'required|numeric',
        ]);
        // hashed password
        $hash = Hash::make($request['passkey']);

        $userUpdate = User::where('id', $request['userId'])->update([
            'name' => $request['name'],
            'password' => $hash,
            'user_role' => $request['role'],
        ]);

        if ($userUpdate) {
            $request->session()->flash('success', 'User Updated Successfully');
            return redirect()->route('adminDashboard');
        }
    }
    public function deleteUser(Request $request, $id)
    {
        if (User::where('id', $id)->delete()) {
            $request->session()->flash('success', 'User Deleted Successfully');
            return redirect()->route('adminDashboard');
        }
    }


    // work details functions

    public function workDetail()
    {
        // return a view for work detail
        // workDetail VIEW contains 2 date input fields (START DATE and END DATE)
        return view('admin.workDetail');
    }

    public function getWworkDate(Request $request)
    {
        // retrieve data form DB according to dates
        // collect all the required data between start date and end date
        // START AND END DATES ARE INCLUDED
        $request->validate([
            'startdate' => 'required',
            'enddate' => 'required',
        ]);

        if ($request->enddate < $request->startdate) {
            $request->session()->flash('success', 'Incorret date choosen');
            return view('admin.workDetail');
        }

        $tasks = Task::whereBetween('created_at', [$request->startdate, $request->enddate])->get();

        if ($tasks) {
            $request->session()->put('startdate', $request->startdate);
            $request->session()->put('enddate', $request->enddate);
            return view('admin.workDetail', ['data' => $tasks]);
        } else {
            $request->session()->flash('success', 'No data found!!');
            return view('admin.workDetail');
        }
    }

    public function WorkDetailOfTask(Request $request, $taskID)
    {

        $taskLogs = TaskLog::where('task_id', $taskID)->whereBetween('created_at', [session('startdate'), session('enddate')])->get();
        return view('admin.workDetail', ['taskLogs' => $taskLogs]);
    }

    // Change Admin Password
    public function changePassForm()
    {
        return view('admin.changePassword');
    }

    public function changeAdminPassword(Request $request)
    {
        // CHANGE THE PASSWORD 
        // MATCH HASHED PASSWORD
        $request->validate([
            'oldPass' => 'required',
            'newPass' => 'required|min:8',
            'confirmPass' => 'required|min:8',
        ]);

        // match new and confirm new password
        if ($request['newPass'] === $request['confirmPass']) {
            $data = Admin::where('email', session('adminEmail'))->pluck('password');
            if (Hash::check($request['oldPass'], $data[0])) {
                $update = Admin::where('email', session('adminEmail'))->update([
                    'password' => Hash::make($request['newPass'])
                ]);

                if ($update) {
                    $request->session()->flash('success', 'Password has been updated successfully!!');
                    return redirect()->route('change.admin.password');
                }
            } else {
                $request->session()->flash('success', 'Incorrect Old Password');
                return redirect()->route('change.admin.password');
            }
        } else {
            $request->session()->flash('success', 'New & Confirm new password must be same');
            return redirect()->route('change.admin.password');
        }
    }
}
