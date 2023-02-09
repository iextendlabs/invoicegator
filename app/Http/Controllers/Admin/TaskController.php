<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ProjectController;
use App\Models\Task;
use App\Models\User;
use App\Models\Project;
use Carbon\Carbon;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Session;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminController;
use App\Models\TaskLog;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createTask()
    {
        // create a new task for a specific project
        // VIEW form containing INFO FIELD of all project and developer 
        $user    = User::where('user_role', 'developer')->get();
        $project = Project::all();
        return view('admin.createNewTask', ['user' => $user, 'project' => $project]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeTasks(Request $request)
    {
        // validate the request -> input
        // store the task in DB and display message

        $request->validate([
            'taskTitle'     => 'required|min:5|max:100',
            'assignTo'      => 'required|numeric',
            'assignProject' => 'required|numeric',
            'taskDesc'      => 'required|',
            'assignDate'    => 'required|date'

        ]);
        $date = Carbon::now()->format('Y-m-d H:i:s');

        $task = new Task();
        $task->task_title   = $request->taskTitle;
        $task->user_id      = $request->assignTo;
        $task->project_id   = $request->assignProject;
        $task->date         = $date;
        $task->task_desc    = $request->taskDesc;

        if (!$task->save()) {
            $request->session()->flash('success', 'Something went wrong try again later!!');
            return redirect()->route('view.project', ['id' => $request->assignProject]);
        }

        $request->session()->flash('success', 'Task Added Successfully!!');
        // return redirect(route('admin/create-task'));
        return redirect()->route('view.project', ['id' => $request->assignProject]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function editTask($id)
    {
        $task = Task::find($id);
        $user = User::where('user_role', 'developer')->get();
        return view('admin.taskDetail', ['detail' => $task, 'user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function updateTask(Request $request, $id)
    {
        $request->validate([
            'task_id'     => 'required',
            'task_title'  => 'required|min:5|max:100',
            'assign_to'   => 'required',
            'date_assign' => 'required',
            'taskDesc'    => 'required',
        ]);
        $task = Task::find($id);
        $update =  $task->update([
            'task_title' => $request['task_title'],
            'user_id'    => $request['assign_to'],
            'date'       => $request['date_assign'],
            'task_desc'  => $request['taskDesc'],
        ]);

        if (!$update) {
            $request->session()->flash('success', 'Unable to update task');
            return redirect(url()->previous());
        }

        $request->session()->flash('success', 'Task Updated');
        // return redirect(url()->previous());
        return redirect()->action(
            [ProjectController::class, 'viewProject'],
            ['id' => $task->project->id]
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function deleteTask(Request $request, $id)
    {
        Task::where('id', $id)->delete();
        $request->session()->flash('success', 'Deleted Successfully');
        return redirect(url()->previous());
    }

    // update TASK status to PAID or UNPAID OR Partial Paid
    public function projectTaskStatus($tasks)
    {
        foreach ($tasks as $task) {
            $totalHours = $task->totalHours;
            $paidLogs   = $task->paidLogs;
            $unPaidLogs = $task->unPaidLogs;

            // update payment_status to PAID
            if ($paidLogs != '00:00:00'  && $unPaidLogs == '00:00:00') {
                Task::where('id', $task->id)->update(['payment_status' => 'paid']);
            }

            // update payment_status to UNPAID
            if ($unPaidLogs != '00:00:00' && $paidLogs == '00:00:00') {
                Task::where('id', $task->id)->update(['payment_status' => 'unpaid']);
            }

            // update payment_status to Partial PAID
            if ($unPaidLogs != '00:00:00' && $paidLogs != '00:00:00') {
                Task::where('id', $task->id)->update(['payment_status' => 'partialPaid']);
            }
        }
        return true;
    }
}
