<?php

namespace App\Http\Controllers;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Task;
use App\Models\Project;
use App\Models\Invoice;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\TaskController;
use App\Http\Controllers\TaskLogController;
use App\Models\TaskLog;
use Illuminate\Support\Facades\Auth;
use Session;
use Carbon\Carbon;


class ProjectController extends Controller
{

    public function createProjectForm(Request $request)
    {
        // show all users in form (select option)
        $users = User::where('user_role', '=', 'client')->get();
        return view('admin.project.createProject', ['users' => $users]);
    }

    public function createProjectPost(Request $request)
    {

        // validate the request
        // store info in db
        $request->validate([
            'projectName' => 'required|min:5',
            'projectDesc' => 'required|min:10',
            'project_rate' => 'required|integer|min:1',
            'per_hour_rate' => 'required|integer|min:1',
            // 'client' => 'required',
        ]);

        $saveProjcet = new Project();
        $saveProjcet->project_name  = $request->projectName;
        $saveProjcet->project_desc  = $request->projectDesc;
        $saveProjcet->project_rate  = $request->project_rate;
        $saveProjcet->per_hour_rate = $request->per_hour_rate;
        // $saveProjcet->user_id = $request->client;

        if ($saveProjcet->save()) {
            $request->session()->flash('success', 'Project Added');
            return redirect(route('adminDashboard'));
        }
    }

    public function viewProject(Request $request, $projectId)
    {
        // detail information about the project
        $projectInfo    = Project::find($projectId);
        // dd($projectInfo);
        $tasks          = $projectInfo->task;
        // dd($tasks);
        $paid = $this->paidHours();

        $paidHours      = ($paid / $projectInfo->per_hour_rate);
        // dd($paidHours);
        // $unPaidHours    = $this->unPaidHours($tasks);
        // code to display total hours in the project
        $totalTaskHours = $this->eachTaskHour($tasks);

        foreach ($tasks as $index => $task) {
            Task::where('id', $task->id)->update([
                'totalHours' => $totalTaskHours[$index]
            ]);
        }

        $logsPayment    =  str_replace(':', '.', $paidHours) * $projectInfo->per_hour_rate;

        // $dueCharges     = str_replace(':', '.', $unPaidHours) * $projectInfo->per_hour_rate;

        $hours          = $this->CalcHours($tasks);
        $totalCost      = str_replace(":", ".", $hours) * $projectInfo->per_hour_rate;
        // $totalCost      = ($hours * $projectInfo->per_hour_rate);

        $due = ($totalCost - $paid);

        $total_hours = ($totalCost / $projectInfo->per_hour_rate);

        $unPaidHours = ($total_hours - $paidHours);

        return view('admin.project.projectView', [
            'projectInfo' => $projectInfo, 'totalCost' => $totalCost, 'task' => $tasks, 'unPaidHours' => $unPaidHours, 'paidHours' => $paidHours, 'totalHours' => $hours, 'totalTaskHours' => $totalTaskHours, 'logsPayment' => $logsPayment, 'paid' => $paid, 'due' => $due, 'total_hours' => $total_hours
        ]);
    }

    public function totalHours($tasks)
    {
        // calculate total hours for a specific TASK
        $taskTotalHour = [];
        foreach ($tasks as $task) {

            $start      = $task->paidLogs;
            $end        = $task->unPaidLogs;
            $totalHours = $this->sum_the_time($start, $end);
            Task::where('id', $task->id)->update(array('totalHours' => $totalHours));
            array_push($taskTotalHour, $totalHours);
        }
        return $taskTotalHour;
    }

    public function eachTaskHour($tasks)
    {
        $totalTime = [];
        foreach ($tasks as $task) {

            $arr = [];
            $logs = TaskLog::where('task_id', $task->id)->get();
            foreach ($logs as $log) {
                $start  = new Carbon($log->start_time);
                $end    = new Carbon($log->end_time);
                $diff   = $start->diff($end)->format('%H:%i');
                array_push($arr, $diff);
            }
            array_push($totalTime, $this->totalTimeSpend($arr));
        }
        return $totalTime;
    }

    public function eachTaskUnpaidHours($tasks)
    {

        $arr = [];
        foreach ($tasks as $task) {
            $logs = TaskLog::where('task_id', $task->id)->where('log_status', 'pending')->get();
            foreach ($logs as $log) {

                $start  = new Carbon($log->start_time);
                $end    = new Carbon($log->end_time);
                $diff   = $start->diff($end)->format('%H:%i');
                array_push($arr, $diff);
            }
            $this->totalTimeSpend($arr);
        }
        return $arr;
    }

    // update TASK status
    public function updateTaskStatus($tasks)
    {
        $taskLogStatus = [];
        // loop through each log of a TASK
        // update TASK status (pending to completed) if all the logs of the specific TASK are completed
        $obj = new TaskLogController();

        foreach ($tasks as $task) {
            $taskLogStatus = $obj->updateTaskColumns($task->id);
            $logs = TaskLog::where('task_id', $task->id)->where('log_status', 'pending')->count();
            if ($logs == 0) {
                // update status from PENDING to COMPLETE
                Task::where('id', $task->id)->update(['task_status' => 'completed']);
            }
            if ($logs > 0) {
                // set task_status to PENDING  
                Task::where('id', $task->id)->update(['task_status' => 'pending']);
            }
        }
        return $taskLogStatus;
    }

    // total Paid Hours
    public function paidHours()
    {
        $total_paid = Invoice::sum('invoice_rate');
        return $total_paid;
        // dd($total_paid);
        // loop through each TASK -> paidHours
        // return the sum of paidHours
        // $paidHours = [];
        // foreach ($projectTasks as $task) {
        //     $paid = $task->paidLogs;
        //     array_push($paidHours, $paid);
        // }
        // return $this->totalTimeSpend($paidHours);
    }

    // total unPaid Hours
    public function unPaidHours($projectTasks)
    {
        // loop through each TASK -> unPaidHours
        // return the sum of unPaidHours
        $unPaidHours = [];
        foreach ($projectTasks as $task) {
            $unpaid  = $task->unPaidLogs;
            array_push($unPaidHours, $unpaid);
        }
        return $this->totalTimeSpend($unPaidHours);
    }

    public function editProject($id)
    {
        $project = Project::where('id', $id)->get();

        return view('admin.project.editProject', ['project_data' => $project]);
    }

    public function updateProject(Request $request)
    {
        $request->validate([
            'project_id'            => 'required|integer',
            'p_name'                => 'required|min:5|max:50',
            'project_description'   => 'required|min:10',
            'project_status'        => 'required',
            'project_rate'          => 'required|numeric',
            'hour_rate'             => 'required|numeric',
        ]);

        $update = Project::where('id', $request['project_id'])->update([
            'project_name'   => $request['p_name'],
            'project_desc'   => $request['project_description'],
            'project_status' => $request['project_status'],
            'project_rate'   => $request['project_rate'],
            'per_hour_rate'  => $request->hour_rate,
        ]);
        $request->session()->flash('success', 'Updated Successfully');
        // return redirect(url()->previous());
        return redirect()->route('view.project', ['id' => $request->project_id]);
    }

    // drop down display project details
    public function exploreProject(Request $request, $id)
    {
        $projectTasks = Project::find($id)->task;
        // dd($projectTasks);
        return $projectTasks;
    }

    public function CalcHours($tasks)
    {
        // return array containing each task --> totalTimeSpend
        $arr = [];
        foreach ($tasks as $tasks) {
            foreach ($tasks->tasklog as $loginfo) {
                $start      = new Carbon($loginfo->start_time);
                $end        = new Carbon($loginfo->end_time);
                $difference = $this->timeDifference($start, $end);
                array_push($arr, $difference);
                // $index++;
            }
        }

        return $this->totalTimeSpend($arr);
    }

    public function timeDifference($start, $end)
    {
        // calculate difference b/w two times
        $start  = new Carbon($start);
        $end    = new Carbon($end);
        return $start->diff($end)->format('%H:%I');
    }

    public function totalTimeSpend($time)
    {
        $total = 0;

        // Loop the data items
        foreach ($time as $element) {
            // Explode by separator :
            $temp = explode(":", $element);

            // Convert the hours into seconds
            // and add to total
            $total += (int) $temp[0] * 3600;

            // Convert the minutes to seconds
            // and add to total
            $total += (int) $temp[1] * 60;

            // Add the seconds to total
            // $total += (int) $temp[2];
        }

        // Format the seconds back into HH:MM:SS
        $formatted = sprintf(
            '%02d:%02d',
            ($total / 3600),
            ($total / 60 % 60),
            $total % 60
        );

        return $formatted;
    }


    public function createProjectTask($projectId)
    {
        $user = User::where('user_role', 'developer')->get();
        $data = Project::find($projectId);
        return view('admin.project.createProjectTask', ['project' => $data, 'user' => $user]);
    }

    public function sum_the_time($time1, $time2)
    {
        $times   = array($time1, $time2);
        $seconds = 0;
        foreach ($times as $time) {
            list($hour, $minute, $second) = explode(':', $time);
            $seconds += $hour * 3600;
            $seconds += $minute * 60;
            $seconds += $second;
        }
        $hours    = floor($seconds / 3600);
        $seconds -= $hours * 3600;
        $minutes  = floor($seconds / 60);
        $seconds -= $minutes * 60;
        if ($seconds < 9) {
            $seconds = "0" . $seconds;
        }
        if ($minutes < 9) {
            $minutes = "0" . $minutes;
        }
        if ($hours < 9) {
            $hours = "0" . $hours;
        }
        return "{$hours}:{$minutes}:{$seconds}";
    }

    public function displayInvoices(Request $request)
    {
        $project  = Project::find($request->get('id'));
        $invoices = Invoice::where('project_name', $project->project_name)->get();

        foreach($invoices as $invoice) {
            $project_name = $invoice['project_name'];
        }

        return view('admin.invoice.invoice', ['invoices' => $invoices, 'project_name' => $project_name, 'projectID' => $request->get('id')]);
    }

    public function invoiceDetail(Request $request)
    {
        $invoice = Invoice::find($request->get('id'));
        $tasks   = Project::find($request->get('projectId'))->task;
        return view('admin.invoice.invoiceDetail', ['invoice' => $invoice, 'tasks' => $tasks]);
    }
}
