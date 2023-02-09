@extends('customLayout.layout')

@section('title', 'Task Details')

<!-- navbar -->
@section('navbar')
<nav class="navbar navbar-expand-lg navbar-dark bg-dark w-100">
    <a href="{{ route('user.dashboard') }}" class="nav-link text-light"><i class="bi bi-arrow-left-circle"></i></a>
    <a class="navbar-brand" href="{{ route('user.dashboard') }}">
        <span class="d-md-inline-block d-none">User | <small>Task Details</small></span>
        <span class="d-md-none">IMS | <small>Task Details</small></span>
    </a>
    <a href="{{ url('logout') }}" class="nav-link ml-auto text-light" style="font-size: 18px;">Logout</a>
</nav>
@endsection

<!-- Page => main content -->
@section('main-content')
<div class="row mt-2">
    <div class="col-12">
        <a href="{{ route('user.create.log', ['logId' => $task->id]) }}" class="float-right btn btn-primary">Create Log</a>
    </div>
</div>
<div class="row my-3">
    <div class="col-md-6 offset-md-3">
        
        <div class="card shadow shadow-sm">
            <div class="card-header">
                <div class="row">
                    <div class="col-6">
                        <h6 class="d-inline float-left">{{ $task->task_title }} &nbsp;<small>({{ $task->task_status }})</small></h6>
                    </div>
                    <div class="col-6">
                        @if(!is_null($project->project_name))
                        <small class="float-right text-info">{{ $project->project_name }}</small>
                        @endif
                    </div>
                </div>
                {{-- <small class="float-right">Project ID :- {{ $pid->id }}</small> --}}
            </div>        
            <div class="card-body">
              <p class="card-text text-muted">{{ $task->task_desc }}</p>
            </div>
            <div class="card-footer">                
                <p style="font-size: 12px;" class="float-right badge badge-danger"><b>Created at :- </b>{{ $task->created_at }}</p>
            </div>
          </div>
               
    </div>
</div>


<div class="row">
    <h5 class="mr-auto ml-5 text-primary">Displaying related Logs</h5>
    <p class="float-right mr-5 badge badge-warning">{{ $total_hrs }} hrs Spend</p>
    <div class="col-md-8 offset-md-2">
        <table class="table">
            <thead>
                <tr>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Total Duration</th>
                    <th>Log Created</th>
                    
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $index => $log)
                <tr>
                    <td>{{ $log->start_time }}</td>
                    <td>{{ $log->end_time }}</td>
                    <td>{{ $diff[$index] }}</td>
                    <td>{{ $log->created_at->format('d-M-Y') }}</td>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection