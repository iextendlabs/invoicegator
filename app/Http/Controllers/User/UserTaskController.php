<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TaskLogController;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use App\Models\TaskLog;
use App\Models\Project;
use Carbon\Carbon;
use DateTime;
use App\Providers\RouteServiceProvider;
// use Illuminate\Support\Facades\Auth;
use Session;

class UserTaskController extends Controller
{
    public function showProjectList()
    {
        // show list of project that are related to loggedIn user
        $uid      = User::where('email', session('userEmail'))->pluck('id');
        $projects = Task::where('user_id', $uid)->distinct()->pluck('project_id');
        $data     = $this->getProject($projects);
        return view('users.userProjectList', ['project' => $data]);
    }

    public function getProject($projectID)
    {
        $data = Project::find($projectID);
        return $data;
    }

    public function showTasksForm(Request $request, $pid)
    {
        // create task for a specific project
        $project = Project::find($pid);
        return view('users.taskForm', ['project' => $project]);
    }

    public function taskDetail($taskId)
    {
        // show TASK details
        $arr = [];
        $difference = [];
        $index = 0;

        $task    = Task::find($taskId);
        $logs    = $task->tasklog;
        $project = $task->project;

        foreach ($logs as $data) {
            $startTime = Carbon::parse($data->start_time);
            $endTime   = Carbon::parse($data->end_time);
            $diff      = $startTime->diff($endTime)->format('%H:%I:%S');
            array_push($difference, $diff);
            $arr[$index] = $this->calculateTimeDifference($startTime, $endTime);
            $index++;
        }

        $sum  = strtotime('00:00:00');
        $sum2 = 0;
        foreach ($arr as $v) {

            $sum1 = strtotime($v) - $sum;
            $sum2 = $sum2 + $sum1;
        }

        $sum3 = $sum + $sum2;
        $sum3 = date("H:i", $sum3);
        return view('users.taskDetails', ['task' => $task, 'project' => $project, 'logs' => $logs, 'total_hrs' => $sum3, 'diff' => $difference]);
    }

    public function deleteTask(Request $request, $id)
    {
        Task::where('id', $id)->delete();
        $request->session()->flash('success', 'Deleted Successfully');
        return redirect()->route('user.dashboard');
    }

    public function storeTasks(Request $request)
    {
        $userId = User::where('email', session('userEmail'))->pluck('id');
        $request->validate([
            'taskTitle'  => 'required|min:5|max:30',
            'project_id' => 'required|numeric',
            'taskDesc'   => 'required|',
            'assignDate' => 'required|date'

        ]);
        $date = Carbon::now()->format('Y-m-d H:i:s');

        $task = new Task();
        $task->task_title  = $request['taskTitle'];
        $task->user_id     = $userId[0];
        $task->project_id  = $request['project_id'];
        $task->date        = $date;
        $task->task_desc   = $request['taskDesc'];
        $task->task_status = $request['status'];

        if ($task->save()) {
            $request->session()->flash('success', 'Task Added Successfully!!');
            return redirect()->route('user.dashboard');
        } else {
            $request->session()->flash('success', 'Something went wrong');
            return redirect()->route('user.dashboard');
        }
    }

    public function calculateTimeDifference($start, $end)
    {
        $diff = $start->diff($end)->format('%H:%I:%S');
        $d1   = new DateTime($start);
        $d2   = new DateTime($end);
        $interval = $d1->diff($d2);
        return $interval->h . ':' . $interval->i;
    }


    // user task log functions
    public function createLog($taskId)
    {
        $uid = User::where('email', session('userEmail'))->pluck('id');
        return view('users.createLog', ['uid' => $uid, 'taskId' => $taskId]);
    }

    public function postLog(Request $request)
    {

        $request->validate([
            'uid'       => 'required|numeric',
            'starttime' => 'required',
            'endtime'   => 'required',
            'date_creation' => 'required'
        ]);

        $taskLog = new TaskLog();
        $taskLog->user_id    = $request->uid;
        $taskLog->task_id    = $request->taskId;
        $taskLog->start_time = $request->starttime;
        $taskLog->end_time   =  $request->endtime;
        $taskLog->log_creation_date = $request->date_creation;

        if ($taskLog->save()) {
            $request->session()->flash('success', 'Log created successfully');
            return redirect()->route('user.dashboard');
        }
    }
}
