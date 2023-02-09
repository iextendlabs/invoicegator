@extends('customLayout.layout')

@section('title', 'Project View')


<!-- navbar -->

@section('navbar')
<nav class="navbar navbar-expand-lg navbar-dark bg-dark w-100">
    <a href="{{ route('adminDashboard') }}" class="nav-link text-light"><i class="bi bi-arrow-left-circle"></i></a>
    <a class="navbar-brand" href="#">Admin | <small>Project Details</small></a>
    <a href="{{ route('adminlogout') }}" class="nav-link ml-auto text-light">Logout</a>
</nav>


{{-- carousal end --}}


@endsection

@section('main-content')
   
@if ($projectInfo->per_hour_rate  == 0)
    @include('admin.project.fixedProject')
    @else
    {{-- row 1 --}}
<div class="row my-3">
        
    <div class="col-md-11 col-10">
        {{-- <div class="btn-group float-right" role="group" aria-label="Basic example"> --}}
            {{-- not a fixed rate project --}}
            @if (!$projectInfo->per_hour_rate == 0)                
                <span class="btn btn-danger btn-sm">Total Cost : ${{ $totalCost }}</span>
            @endif
            <span class="d-inline-block btn-sm btn-info">Total Hours : {{ $total_hours }} hrs</span>
            <span class="d-inline-block btn-sm btn-success">Paid : ${{ $paid }}</span>
            <span class="d-inline-block btn-sm btn-dark">Due : ${{ $due }}</span>
            <span class="d-inline-block btn-sm btn-primary">Paid Hours : {{ $paidHours }} hrs</span>
            <span class="d-inline-block btn-sm btn-danger">unPaid Hours : {{ $unPaidHours }} hrs</span>
            {{-- <p class="d-inline-block btn btn-success">project cost - log cost: ${{ $projectInfo->project_rate -  $totalCost }}</p> --}}
        {{-- </div> --}}
    </div>
    <div class="col-md-1 col-2">
        <a class="dropdown-toggle btn-sm btn-primary" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-list"></i>
        </a>
        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">    
            <li><a href="{{ route('project/create-task', ['pid' => $projectInfo->id]) }}" class="nav-link">Create Task</a></li>
            <li><a href="{{ route('projectPreview', ['id' => $projectInfo->id, 'time' => $totalHours]) }}" class="nav-link">Preview</a></li>
            <li><a href="{{ route('create.invoice.view', ['id' => $projectInfo->id]) }}" class="nav-link">Generate Invoice</a></li>
            <li><a href="{{ route('available.invoices.view', ['id' => $projectInfo->id]) }}" class="nav-link">Invoices</a></li>
            {{-- <li><a href="#" class="nav-link">View Invoice</a></li> --}}
        </ul>
    </div>

</div>
@if (session('success'))
        <div class="row text-success justify-content-center">{{ session('success') }}</div>
    @endif
{{-- row 2 --}}
<div class="row">
    <div class="col-md-8 offset-md-2">       
        <div class="card shadow shadow-sm">
            <div class="card-header">
                <h6 class="d-inline float-left">{{ $projectInfo->project_name }} <small>({{ $projectInfo->project_status }})</small></h6>
                <small class="float-right">Project ID :- {{ $projectInfo->id }}</small>
            </div>        
            <div class="card-body">
              <p class="card-text text-muted">{{ $projectInfo->project_desc }}</p>
              <p class="card-description">Project Rate:- <b class="display-6">${{ $projectInfo->project_rate }}</b></p>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin/project-edit', ['id' => $projectInfo->id]) }}" class="btn btn-primary float-left d-inline">Edit Project</a>
                <div class="d-inline-flex float-right flex-column">
                    <p style="font-size: 12px;" class="float-right badge badge-danger"><b>Created at :- </b>{{ $projectInfo->created_at }}</p>
                </div>
            </div>
          </div>
    </div>
</div>
<h6 class="text-center mt-5 text-capitalize">related tasks</h6>
@if (session('invoice'))
        <div class="row text-success justify-content-center">{{ session('invoice') }}</div>
    @endif
<div class="row mt-4">
    <div class=" col-md-10 offset-md-1" style="height:500px; overflow-y: auto">
        @if($task)
        <table class="table table-responsive table-hover">
            <thead class="bg-primary text-light text-center">
                <tr>
                    <th class="border border-right">Title</th>
                    <th class="border border-right">Description</th>
                    <th class="border border-right">Assigned Date</th>
                    <th class="border border-right">Assigned to</th>
                    {{-- <th class="border border-right">Task Status</th> --}}
                    <th class="border border-right">Paid Logs</th>
                    <th class="border border-right">UnPaid Logs</th>
                    <th class="border border-right">Total Hours</th>
                    {{-- <th class="border border-right">View Invoice</th> --}}
                    <th>Payment Status</th>
                    <th class="bg-danger">Task Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($task as $index => $item)
                    <tr class="text-center">
                        <td class="text-capitalize"><a href="{{ route('explore-task/logs', ['task_id'=>$item->id]) }}" class="nav-link">{{ $item->task_title }}</a></td>
                        <td>{{ $item->task_desc }}</td>
                        <td><small>{{ $item->created_at }}</small></td>
                        <td class="text-center">{{ $item->user->name }}</td>
                        {{-- <td>
                            @if($item->task_status == 'pending')
                            <span class="text-danger text-capitalize">{{ $item->task_status }}</span>
                            @endif

                            @if($item->task_status == 'completed')
                            <span class="text-success text-capitalize">{{ $item->task_status }}</span>
                            @endif
                        </td> --}}
                        <td class="text-success">{{ $item->paidLogs }}</td>
                        <td class="text-danger">{{ $item->unPaidLogs }}</td>
                        <td class="text-info">
                            {{ $totalTaskHours[$index] }}
                        </td>
                        {{-- invoice td --}}
                        {{-- <td class="text-success">
                            <a href="{{ route('taskInvoice', ['id'=>$item->id]) }}"><i class="bi bi-eye-fill"></i></a>                            
                        </td> --}}
                        <td>{{ $item->payment_status }}</td>
                        <td class="text-center btn-group">
                            <a href="{{ route('admin/task-details', ['id' => $item->id]) }}" class="text-primary btn mr-n1"><i class="bi bi-pencil-square"></i></a>                            
                            <a href="{{ route('admin/preview-task', ['id' => $item->id]) }}" class="text-secondary btn mr-n1"><i class="bi bi-eye-fill"></i></a>                            
                            <form action="{{ route('admin/task-delete', ['id' => $item->id]) }}" method="post">
                                @csrf
                                <button class="btn btn-default text-danger" type="submit"><i class="bi bi-trash"></i></button>
                            </form>                    
                        </td>
                    </tr>
                @endforeach 
            </tbody>
        </table>
        @endif
    </div>
</div>
@endif
@endsection
