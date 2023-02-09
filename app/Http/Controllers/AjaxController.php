<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskLog;
use App\Models\Invoice;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Admin\InvoiceController;

class AjaxController extends Controller
{
    public function dateWiseInvoice(Request $request)
    {
        $logArr = [];
        $firstDate = $request->firstDate;
        $lastDate = $request->firstDate;
        $projectId = $request->projectId;
        $invoiceTitle = $request->invoiceTitle;

        if ($request->lastDate < $request->firstDate) {
            $request->session()->flash('success', 'Incorret date choosen');
            // return view('admin.invoice.invoiceView')->with(['success' => 'Incorret date choosen', 'pid' => $pid]);
            return 'Incorret date choosen';
        }

        $project = Project::find($request->projectId);
        $tasks = $project->task;

        foreach ($tasks as $task) {
            $log = TaskLog::where('task_id', $task->id)->whereBetween('log_creation_date', [$request->firstDate, $request->lastDate])->get();
            array_push($logArr, $log);
        }

        if (count($logArr) == 0) {
            return 'No record found from' . $request->firstDate . 'to  ' . $request->lastDate . '!!';
        }
        $obj = new InvoiceController();

        $totalHours = $obj->calculateTotalTime($logArr);
        $invoiceRate = $project->per_hour_rate * str_replace(':', '.', $totalHours);
        $data = ['projectId' => $projectId, 'projectName' => $project->project_name, 'totalHours' => $totalHours, 'invoiceRate' => $invoiceRate, 'startDate' => $request->firstDate, 'lastDate' => $request->lastDate, 'invoiceTitle' => $request->invoiceTitle];

        return json_encode($data);
    }


    public function dateWiseInvoiceEntry(Request $request)
    {
        $startDate = $request->startDate;
        $lastDate = $request->lastDate;
        $projectName = $request->projectName;
        $totalHours = $request->totalHours;
        $invoiceRate = $request->invoiceRate;
        $invoiceTitle = $request->invoiceTitle;
        $projectId = $request->projectId;

        $tasks = Project::find($projectId)->task;
        // create new entry
        $invoice = new Invoice();

        // check invoice already exists or not
        // $check = Invoice::where('project_name', 'SMS Phase 3')->first();
        // if ($check) {
        //     return 'Invoice already exists';
        // }

        $invoice->project_name = $projectName;
        $invoice->date_created = Carbon::now();
        $invoice->start_date   = $startDate;
        $invoice->end_date     = $lastDate;
        $invoice->invoice_rate = $invoiceRate;
        $invoice->total_hours  = $totalHours;
        $invoice->invoice_title = $invoiceTitle;

        if ($invoice->save()) {
            $newInvoiceId =  $invoice->id;
            $obj = new InvoiceController();

            // $obj->addInvoiceReference($tasks, $newInvoiceId);

            return 'Invoice Added Successfully';
        }

        // $newInvoiceId = $obj->newInvoiceEntry($data);
        // if ($newInvoiceId) {
        //     $request->session()->flash('success', 'Invoice record created!!');
        //     $this->addInvoiceReference($tasks, $newInvoiceId);
        //     return redirect()->route('view.project', ['id' => $project->id]);
        // }
    }
}
