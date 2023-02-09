<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskLog;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Symfony\Component\HttpFoundation\Session\Session;


class TaskLogController extends Controller
{
    public function index()
    {
        // return to log creation form with some (details) like project, user, task
        $project = Project::all();
        $task = Task::all();
        $devs = User::where('user_role', 'developer')->get();

        return view('admin.createTaskLog', ['dev' => $devs, 'task' => $task, 'project' => $project]);
    }

    public function createLog(Request $request)
    {
        // add new task log
        // calculate time difference of paid and unpaid logs
        // update paid/unpaid/totalPaid columns in Task Table

        $request->validate([
            'dev_id' => 'required|integer',
            'task_id' => 'required|integer',
            'starttime' => 'required',
            'endtime' => 'required',
            'date_creation' => 'required',
            'logStatus' => 'required',
        ]);

        if ($request->endtime < $request->starttime) {
            $request->session()->flash('success', 'Invalid End Time entered');
            return redirect()->route('create.task.log');
        }

        $taskLog = new TaskLog();
        $taskLog->user_id           = $request->dev_id;
        $taskLog->task_id           = $request->task_id;
        $taskLog->start_time        = $request->starttime;
        $taskLog->end_time          = $request->endtime;
        $taskLog->log_creation_date = $request->date_creation;
        $taskLog->log_status        = $request->logStatus;

        if ($taskLog->save()) {
            $logDifference = $this->updateTaskColumns($request->task_id);
            Task::where('id', $request->task_id)->update([
                'paidLogs'   => $logDifference[0],
                'unPaidLogs' => $logDifference[1],
            ]);
            $request->session()->flash('success', 'Log Added Successfully!!');
            return redirect()->route('explore-task/logs', ['task_id' => $request->task_id]);
        }
    }

    public function updateTaskColumns($taskID)
    {
        // update task table fields paidLogs/unPaidLogs accordingly
        $data   = [];
        $paid   = TaskLog::where('task_id', $taskID)->where('log_status', 'complete')->get();
        $unpaid = TaskLog::where('task_id', $taskID)->where('log_status', 'pending')->get();
        $paidLogs   = $this->calcHours($paid);
        $unPaidLogs = $this->calcHours($unpaid);
        $data[0] = $paidLogs;
        $data[1] = $unPaidLogs;
        return $data;
    }

    public function exploreLogs(Request $request, $task_id)
    {
        // get task Log details from $task_id
        // get taskLog related task detail
        // get the related developer detail
        // display number of logs on specific task
        // details about logs (how many hours spend on each logs i.e difference of start-time and end-time)
        // developer name
        // 
        $completedLogs = 0;
        $pendingLogs   = 0;

        $task    = Task::find($task_id);
        $taskLog = $task->tasklog;
        $hours   = $this->calcHours($taskLog);
        $logDifference = [];
        foreach ($taskLog as $logs) {
            // check log is PENDING or COMPLETED
            if ($logs->log_status == 'pending') {
                $pendingLogs++;
            }
            if ($logs->log_status == 'complete') {
                $completedLogs++;
            }

            $startTime  = Carbon::parse($logs->start_time);
            $finishTime = Carbon::parse($logs->end_time);

            array_push($logDifference, $this->calculateDifference($startTime, $finishTime));
        }

        $taskTotalCost =  str_replace(":", ".", $hours) * $task->project->per_hour_rate;
        return view('admin.logDetails', ['task' => $task, 'taskLog' => $taskLog, 'hours' => $hours, 'taskTotalCost' => $taskTotalCost, 'logDifference' => $logDifference, 'per_hour_rate' => $task->project->per_hour_rate, 'pendingLogs' => $pendingLogs, 'completedLogs' => $completedLogs]);
    }

    public function changeLogStatus($id)
    {
        $taskID = TaskLog::find($id)->task->id;
        $update = TaskLog::where('id', $id)->update([
            'log_status' => 'complete'
        ]);
        if ($update) {
            return redirect()->route('explore-task/logs', ['task_id' => $taskID]);
        }
    }

    // calculate hours
    public function calcHours($taskLog)
    {
        $arr   = [];
        $index = 0;
        foreach ($taskLog as $logs) {
            $start  = new Carbon($logs->start_time);
            $end    = new Carbon($logs->end_time);
            array_push($arr, $this->calculateDifference($start, $end));
        }
        return $this->totalTimeSpend($arr);
    }

    public function calculateDifference($start, $end)
    {

        // calculate the difference between two times
        // $diff = str_replace(':', '.', $start->diff($end)->format('%H:%I'));
        $start  = new Carbon($start);
        $end    = new Carbon($end);
        $diff   = $start->diff($end)->format('%H:%I');
        return $diff;
        // $diff = $start->diffInHours($end) . ':' . $start->diff($end)->format('%H:%I:%S');
        // $d1 = new DateTime($start);
        // $d2 = new DateTime($end);
        // $interval = $d1->diff($d2);
        // return $interval->h . ':' . $interval->i;
    }


    public function totalTimeSpend($time)
    {
        $sum = strtotime('00:00:00');

        $totaltime = 0;

        foreach ($time as $element) {

            // Converting the time into seconds
            $timeinsec = strtotime($element) - $sum;

            // Sum the time with previous value
            $totaltime = $totaltime + $timeinsec;
        }

        $h = intval($totaltime / 3600);

        $totaltime = $totaltime - ($h * 3600);
        // Minutes is obtained by dividing
        // remaining total time with 60
        $m = intval($totaltime / 60);
        // Remaining value is seconds
        $s = $totaltime - ($m * 60);
        // Printing the result
        return ("$h:$m");
    }


    public function editLog(Request $request, $id)
    {
        // return view containing edit form for a given log
        $edit      = TaskLog::find($id);
        $developer = User::where('user_role', 'developer')->get();
        if ($edit) {
            return view('admin.editLog', ['log' => $edit, 'developer' => $developer]);
        }
    }

    public function postEditLog(Request $request)
    {
        $request->validate([
            'logID'         => 'required|numeric',
            'starttime'     => 'required',
            'endtime'       => 'required',
            'developerName' => 'required',
            'logStatus'     => 'required',
            'date'          => 'required',
        ]);
        if ($request->endtime < $request->starttime) {
            $request->session()->flash('success', 'Invalid End Time entered');
            return redirect(url()->previous());
        }
        // log update
        $logUpdate = TaskLog::where('id', $request->logID)->update([
            'user_id'    => $request->developerName,
            'start_time' => $request->starttime,
            'end_time'   => $request->endtime,
            'log_status' => $request->logStatus,
            'log_creation_date' => $request->date,
        ]);

        $taskID     = TaskLog::where('id', $request->logID)->pluck('task_id');
        $projectID  = Task::find($taskID[0])->project->id;
        // update paid/unpaidLog fiels in TASKS table
        $updateTask = $this->updateTaskColumns($taskID[0]);

        Task::where('id', $taskID[0])->update([
            'paidLogs'   => $updateTask[0],
            'unPaidLogs' => $updateTask[1],
        ]);

        if ($logUpdate) {
            $request->session()->flash('success', 'Log Updated Succesfully');

            return redirect()->action(
                [ProjectController::class, 'viewProject'],
                ['id' => $projectID]
            );

            // return redirect()->route('explore-task/logs', ['task_id' => $taskID[0]]);
        }
    }

    public function deleteLog(Request $request, $id)
    {
        $taskID = TaskLog::find($id)->task->id;
        $del    = TaskLog::where('id', $id)->delete();
        if ($del) {
            $request->session()->flash('success', 'Log Deleted Successfully!!');
            return redirect()->route('explore-task/logs', ['task_id' => $taskID]);
        }
    }

    public function createSpecificTaskLog($taskID)
    {
        // create new LOG for a specific task
        $developer = User::where('user_role', 'developer')->get();
        return view('admin.newLog', ['dev' => $developer, 'taskID' => $taskID]);
    }
}
