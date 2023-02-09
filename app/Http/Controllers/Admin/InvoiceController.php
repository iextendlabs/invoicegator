<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TaskLog;
use App\Models\Task;
use App\Models\Project;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskLogController;
use App\Http\Controllers\Admin\PreviewController;
use App\Models\Invoice;
use Carbon\Carbon;
use Symfony\Component\Routing\Route;
use DateTime;
use Illuminate\Support\Facades\DB;


class InvoiceController extends Controller
{
    protected $projectController;
    public function __construct()
    {
        $this->projectController = new ProjectController();
    }

    public function generateReceipt(Request $request)
    {
        $log    = TaskLog::find($request->logId);
        $start  = Carbon::parse($log->start_time);
        $end    = Carbon::parse($log->end_time);

        $diff   = $start->diff($end)->format('%h:%i');

        // Log Cost
        // $this->per_hour_rate = $request->input('per_hour_rate', config('app.per_hour_rate'));
        // $logCost = str_replace(":", ".", $diff) * $this->per_hour_rate;  // THROUGH ENV VARIABLE
        $logCost = str_replace(":", ".", $diff) * $log->task->project->per_hour_rate;
        // related TASK Info
        $task    = $log->task;
        // related PROJECT Info
        $project = $task->project;

        return view('admin.invoice.taskReceipt', ['log' => $log, 'task' => $task, 'project' => $project, 'diff' => $diff, 'logCost' => $logCost]);
    }

    public function createInvoiceView(Request $request, $projectId)
    {
        return view('admin.invoice.invoiceView', ['projectId' => $projectId]);
    }

    public function dateWiseDetail(Request $request)
    {
        // get two dates
        // validate dates
        // pick project -> Task between dates
        // calculate their total hours PLUS find total cost
        // create new invoice entry
        // add invoice_id reference in front of each task
        $taskArr = [];
        $request->validate([
            'firstDate' => 'required',
            'lastDate'  => 'required',
            'projectId' => 'required|numeric'
        ]);

        if ($request->lastDate < $request->firstDate) {
            $request->session()->flash('success', 'Incorret date choosen');
            // return view('admin.invoice.invoiceView')->with(['success' => 'Incorret date choosen', 'pid' => $pid]);
            return redirect(url()->previous());
        }

        $project = Project::find($request->projectId);
        $tasks   = $project->task;

        foreach ($tasks as $task) {
            $log = TaskLog::where('task_id', $task->id)->whereBetween('log_creation_date', [$request->firstDate, $request->lastDate])->get();
            array_push($taskArr, $log);
        }
        if (count($taskArr) == 0) {
            $request->session()->flash('success', 'No record found from ' . $request->firstDate . 'to  ' . $request->lastDate . '!!');
            return redirect()->route('create.invoice.view', ['id' => $project->id]);
        } else {
            $totalHours = $this->calculateTotalTime($taskArr);
            $invoiceRate = $project->per_hour_rate * str_replace(':', '.', $totalHours);
            $data = ['projectName' => $project->project_name, 'totalHours' => $totalHours, 'invoiceRate' => $invoiceRate, 'startDate' => $request->firstDate, 'lastDate' => $request->lastDate];

            $newInvoiceId = $this->newInvoiceEntry($data);
            if ($newInvoiceId) {
                $request->session()->flash('success', 'Invoice record created!!');
                $this->addInvoiceReference($tasks, $newInvoiceId);
                return redirect()->route('view.project', ['id' => $project->id]);
            }
        }
    }

    public function fixedAmountInvoice(Request $request)
    {

        $request->validate([
            'amount'    => 'required|numeric',
            'projectId' => 'required|numeric',
        ]);
        $amount    = $request->amount;
        $projectID = $request->projectId;
        $project   = Project::find($projectID);
        $invoiceTitle = $request->invoiceTitle;
        $tasks     = $project->task;
        $obj       = new ProjectController();
        $taskHours = $obj->eachTaskHour($tasks);
        $projectHours = $obj->totalTimeSpend($taskHours);

        $calculatedHours = $amount / $project->per_hour_rate;

        if (!str_contains($request->hours, '.')) {
            $calculatedHours = $calculatedHours . '.0';
        }

        if ($calculatedHours >  $projectHours) {
            $remainingUnpaidHours = $this->generateInvoice(str_replace('.', ':',  $projectHours), $taskHours);
            foreach ($tasks as $index => $item) {
                Task::where('id', $item->id)->update(['unPaidLogs' => $remainingUnpaidHours[$index]]);
            }
            $this->updatePaidHours($tasks);
            $this->updatePaymentStatus($tasks);

            $invoiceId = $this->fixedAmountInvoiceEntry($project->project_name, $calculatedHours, $amount, $invoiceTitle);
            if ($invoiceId) {
                // $this->addInvoiceReference($tasks,  $invoiceId);
                $request->session()->flash('invoice', 'Invoice Created of Amount $' . $amount);
                return redirect()->route('view.project', ['id' => $projectID]);
            }
        }
        $remainingUnpaidHours = $this->generateInvoice(str_replace('.', ':', $calculatedHours), $taskHours);
        foreach ($tasks as $index => $item) {
            Task::where('id', $item->id)->update(['unPaidLogs' => $remainingUnpaidHours[$index]]);
        }
        $this->updatePaidHours($tasks);
        $this->updatePaymentStatus($tasks);

        $invoiceId = $this->fixedAmountInvoiceEntry($project->project_name, $calculatedHours, $amount, $invoiceTitle);
        if ($invoiceId) {
            // $this->addInvoiceReference($tasks,  $invoiceId);
            $request->session()->flash('invoice', 'Invoice Created of Amount $' . $amount);
            return redirect()->route('view.project', ['id' => $projectID]);
        }
        // return view('admin.invoice.invoiceView', ['projectId' => $projectID, 'task' => $tasks, 'taskHours' => $taskHours, 'amount' => $amount])->with('fixedAmountInvoice', 'set');
    }

    public function fixedHourInvoiceEntry($projectName, $invoiceHours, $projectHourRate, $invoiceTitle)
    {
        // dd($invoiceHours);
        $invoiceRate = $invoiceHours * $projectHourRate;

        $invoice = new Invoice();
        $invoice->project_name  = $projectName;
        $invoice->date_created  = Carbon::now();
        $invoice->total_hours   = str_replace('.', ':', $invoiceHours);
        $invoice->invoice_rate  = $invoiceRate;
        $invoice->invoice_title = $invoiceTitle;
        $invoice->start_date    = Null;
        $invoice->end_date      = Null;
        if ($invoice->save()) {
            return $invoice->id;
        }
    }

    public function fixedHourInvoice(Request $request)
    {
        $request->validate([
            'hours' => 'required|numeric',
            'projectId' => 'required|numeric',
        ]);
        $timeToReduceLeft = $request->hours;
        if (!str_contains($request->hours, '.')) {
            $timeToReduceLeft = $request->hours . '.0';
        }
        $timeToReduceLeft = str_replace('.', ':', $timeToReduceLeft);
        $projectID = $request->projectId;
        $project = Project::find($projectID);
        $tasks = $project->task;

        $taskHours = $this->projectController->eachTaskHour($tasks);

        $projectTotalHours = str_replace(':', '.', $this->projectController->totalTimeSpend($taskHours));

        if (str_replace(':', '.', $request->hours) > $projectTotalHours) {
            // dd('no enough hours');
            $remainingUnpaidHours = $this->generateInvoice($timeToReduceLeft, $taskHours);
            foreach ($tasks as $index => $item) {
                Task::where('id', $item->id)->update(['unPaidLogs' => $remainingUnpaidHours[$index]]);
            }
            $this->updatePaidHours($tasks);
            $this->updatePaymentStatus($tasks);

            $this->eachTaskInvoice($tasks, $project->project_name, $project->per_hour_rate, $request->invoiceTitle);
            $request->session()->flash('invoice', 'Invoice Created of total Hours ' . str_replace('.', ':', $request->hours));
            return redirect()->route('view.project', ['id' => $projectID]);
        } else {
            $remainingUnpaidHours = $this->generateInvoice($timeToReduceLeft, $taskHours);
            // dd($remainingUnpaidHours);
            foreach ($tasks as $index => $item) {
                Task::where('id', $item->id)->update(['unPaidLogs' => $remainingUnpaidHours[$index]]);
            }
            $paidHours = $this->updatePaidHours($tasks);

            $this->updatePaymentStatus($tasks);
            $this->eachTaskInvoice($tasks, $project->project_name, $project->per_hour_rate, $request->invoiceTitle);
            $request->session()->flash('invoice', 'Invoice Created of total Hours ' . str_replace('.', ':', $request->hours));
            return redirect()->route('view.project', ['id' => $projectID]);
        }
    }


    public function eachTaskInvoice($tasks, $projectName, $hourRate, $invoiceTitle)
    {

        foreach ($tasks as $value) {
            $invoice = new Invoice();
            $invoice->project_name  = $projectName;
            $invoice->date_created  = Carbon::now();
            $invoiceRate = str_replace(':', '.', Carbon::parse($value->paidLogs)->format('H:i'));

            $invoice->invoice_rate  = $invoiceRate * $hourRate;
            $invoice->total_hours   = $value->paidLogs;
            $invoice->invoice_title = $invoiceTitle;
            $invoice->task_id       = $value->id;
            $invoice->save();
        }

        // if ($invoice->id) {
        //     dd($invoice->id, 'task wise invoice created');
        // }
    }


    public function unPaidHours($projectTasks)
    {
        // loop through each TASK -> unPaidHours
        // return the sum of unPaidHours
        $unPaidHours = [];
        foreach ($projectTasks as $task) {
            $unpaid = $task->unPaidLogs;
            array_push($unPaidHours, $unpaid);
        }
        return $unPaidHours;
        // return $this->totalTimeSpend($unPaidHours);
    }

    public function updatePaymentStatus($tasks)
    {
        foreach ($tasks as $task) {
            // update payment_status to PAID
            if ($task->paidLogs == $task->totalHours) {
                Task::where('id', $task->id)->update(['payment_status' => 'paid']);
            }
            // update payment_status to Partial PAID
            if (($task->paidLogs !== $task->totalHours) && ($task->unPaidLogs !== '00:00:00')) {
                Task::where('id', $task->id)->update(['payment_status' => 'partialPaid']);
            }
            // update payment_status to UNPAID
            if ($task->unPaidLogs == $task->totalHours) {
                Task::where('id', $task->id)->update(['payment_status' => 'unpaid']);
            }
        }
    }
    public function updatePaidHours($taskArr)
    {
        foreach ($taskArr as $value) {
            $data = Task::find($value->id, ['totalHours', 'unPaidLogs']);
            $unPaidLogs = Carbon::createFromFormat('H:i:s', $data['unPaidLogs']);
            $totalHours = Carbon::createFromFormat('H:i:s', $data['totalHours']);
            $updatedPaidLogs = $unPaidLogs->diff($totalHours)->format('%H:%i');
            $updatedPaidLogs = Carbon::parse($updatedPaidLogs)->format('H:i:s');
            Task::where('id', $value->id)->update(['paidLogs' =>  $updatedPaidLogs]);
        }
    }

    public function getLogTimeDifference($logs)
    {
        $logsHours = [];
        $obj = new ProjectController();
        foreach ($logs as $value) {
            $logDifference = $this->projectController->timeDifference($value->start_time, $value->end_time);
            // match the entered time with $d 
            array_push($logsHours, $logDifference);
        }
        return $obj->totalTimeSpend($logsHours);
    }

    public function fixedAmountInvoiceEntry($projectName, $projectHours, $invoiceRate, $invoiceTitle)
    {
        $invoice = new Invoice();
        $invoice->project_name = $projectName;
        $invoice->date_created = Carbon::now();
        $invoice->total_hours  = str_replace('.', ':', $projectHours);
        $invoice->invoice_rate = $invoiceRate;
        $invoice->invoice_title = $invoiceTitle;
        $invoice->start_date   = Null;
        $invoice->end_date     = Null;

        if ($invoice->save()) {
            return $invoice->id;
        }
    }

    public function calculateTotalTime($taskArr)
    {
        // get start time and end time of each log
        // add difference in an array
        // calculate total time
        $time = [];
        $obj = new ProjectController();
        foreach ($taskArr as $data) {
            foreach ($data as $difference) {
                $start  = new Carbon($difference->start_time);
                $end    = new Carbon($difference->end_time);
                $info = $obj->timeDifference($start, $end);
                array_push($time, $info);
            }
        }
        $totalTime = $obj->totalTimeSpend($time);
        return $totalTime;
    }

    public function addInvoiceReference($tasks, $invoiceID)
    {
        // add invoiceID foreign key in front of each task
        // array_push($logID, $log->id);
        foreach ($tasks as $task) {
            Task::where('id', $task->id)->update(['invoice_id' => $invoiceID]);
        }
        return true;
    }

    public function newInvoiceEntry($data)
    {
        // add new invoice record in invoice table
        $invoice = new Invoice();
        $invoice->project_name = $data['projectName'];
        $invoice->date_created = Carbon::now();
        $invoice->start_date   = $data['startDate'];
        $invoice->end_date     = $data['lastDate'];
        $invoice->invoice_rate = $data['invoiceRate'];
        $invoice->total_hours  = $data['totalHours'];

        if ($invoice->save()) {
            return $invoice->id;
        }
    }

    public function viewTaskInvoice(Request $request, $taskID)
    {
        // $invoice = [];
        $logs = TaskLog::whereNotNull('invoice_id')->where('task_id', $taskID)->where('invoice_status', '!=', 0)->get();

        // $taskLogs  = Task::find($taskID)->tasklog;
        $projectID = Task::find($taskID)->project->id;

        if (count($logs) == 0) {
            $request->session()->flash('success', 'No Invoice created against task ID ' . $taskID);
            return redirect()->route('view.project', ['id' => $projectID]);
        }
        $invoiceID = array_unique($this->fetchInvoiceId($logs));

        $invoices = $this->getInvoiceInfo($invoiceID);

        return view('admin.invoice.invoice', ['invoices' => $invoices, 'projectID' => $projectID, 'logs' => $logs]);
    }

    public function fetchInvoiceId($arr)
    {
        $invoice = [];
        foreach ($arr as $value) {
            if (!$value->invoice_id == NULL) {
                array_push($invoice, $value->invoice_id);
            }
        }
        return $invoice;
    }

    public function getInvoiceInfo($invoice)
    {
        $data = [];

        foreach ($invoice as $invoice) {
            $info = Invoice::find($invoice);
            array_push($data, $info);
        }
        return $data;
        // return $data;
    }


    function timeSubtractionFirstTime($actual_time, $time_to_reduce)
    {
        $actual_time_array = explode(":", $actual_time);
        $time_to_reduce = explode(":", $time_to_reduce);
        $final_result = [];
        if ($actual_time_array[1] < $time_to_reduce[1]) {
            $actual_time_array[0] = $actual_time_array[0] - 1;
            $final_result[] = $actual_time_array[1] + 60 - $time_to_reduce[1];
        } else {
            $final_result[] = $actual_time_array[1] - $time_to_reduce[1];
        }
        $final_result[] = $actual_time_array[0] - $time_to_reduce[0];

        return implode(":", array_reverse($final_result));
    }

    public function generateInvoice($timeToReduceLeft, $val)
    {
        // $timeToReduceLeft = "13:45";
        // $val = ["12:10", "4:16", "2:05"];
        // $arr = [];
        foreach ($val as &$value) {
            $diff = $this->timeSubtractionFirstTime($value, $timeToReduceLeft);
            if (strpos($diff, chr(45)) !== false) { //if $value < $timeToReduceLeft
                $timeToReduceLeft = $this->timeSubtractionFirstTime($timeToReduceLeft, $value);
                $value = "00:00";
            } else { //if $value >= $timeToReduceLeft
                $value = $this->timeSubtractionFirstTime($value, $timeToReduceLeft);
                $timeToReduceLeft = "00:00";
            }
            if ($timeToReduceLeft == "00:00") {
                break;
            }
        }

        return $val;
        // return array_push($arr, explode(",", $val));
        // return implode(",", $val);
    }

    // delete invoice
    public function deleteInvoice(Request $request, $id)
    {
        $deleteInvoice = Invoice::where('id', $id)->delete();
        if ($deleteInvoice) {
            $request->session()->flash('success', 'Invoice Deleted Successfully');
            return redirect(url()->previous());
        }
    }
}
