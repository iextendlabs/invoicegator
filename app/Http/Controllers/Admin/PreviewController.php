<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskLog;
use App\Http\Controllers\TaskLogController;
use Illuminate\Http\Request;
use DateTime;
use Symfony\Component\Routing\Route;
use Carbon\Carbon;

class PreviewController extends Controller
{
    public function preview(Request $request, $projectID)
    {
        $project = Project::find($projectID);
        $task = $project->task;
        $taskHours = [];
        foreach ($task as $tasks) {
            $taskLog    = $tasks->tasklog;
            $hours      = $this->calcHours($taskLog);
            $totalHours = str_replace(':', '.', $this->totalTimeSpend($hours));
            array_push($taskHours, $totalHours);
        }

        // $this->per_hour_rate = $request->input('per_hour_rate', config('app.per_hour_rate'));  ENV VARIABLE

        return view('preview', ['project' => $project, 'task' => $task, 'hours' => $taskHours, 'hourRate' => $project->per_hour_rate]);
    }

    public function previewTask($taskId)
    {
        // preview TASK
        // display all LOG of the specific task 
        $logDifference = [];

        $task = Task::find($taskId);
        $taskTitle = $task->task_title;
        $logs = $task->tasklog;
        foreach ($logs as $log) {

            $startTime = Carbon::parse($log->start_time);
            $finishTime = Carbon::parse($log->end_time);
            // dd($this->calculateDifference($startTime, $finishTime));
            array_push($logDifference, $this->calculateDifference($startTime, $finishTime));
        }
        return view('admin.taskPreview', ['logs' => $logs, 'taskTitle' => $taskTitle, 'logDifference' => $logDifference]);
    }

    public function calcHours($taskLog)
    {
        $arr = [];
        foreach ($taskLog as $logs) {
            $start  = new Carbon($logs->start_time);
            $end    = new Carbon($logs->end_time);
            array_push($arr, $this->calculateDifference($start, $end));
        }
        return $arr;
    }

    public function calculateDifference($start, $end)
    {
        // $diff = $start->diffInHours($end) . ':' . $start->diff($end)->format('%H:%I:%S');
        $diff = $start->diff($end)->format('%H:%I');
        return $diff;
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

        // Printing the result
        // return ("$h:$m:$s");
        return ("$h:$m");
    }
}
