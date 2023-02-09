@extends('customLayout.layout')

@section('title', 'Log Details')

<!-- navbar -->

@section('navbar')
<nav class="navbar navbar-expand-lg navbar-dark bg-dark w-100">
  <a href="{{ route('view.project', ['id' => $task->project->id]) }}" class="nav-link text-light"><i class="bi bi-arrow-left-circle"></i></a>
  <a class="navbar-brand" href="{{ route('adminDashboard') }}">Admin | <small>Log Details</small> </a>
    <a href="{{ route('adminlogout') }}" class="nav-link ml-auto text-light">Logout</a>

</nav>
@endsection

<!-- main content -->
@section('main-content')
@if ($per_hour_rate != 0)

<p class="d-inline-block float-md-left float-right badge- badge-info mt-1 p-1">Task <span class="d-md-inline-block d-none">Total</span> Cost = ${{$taskTotalCost}}</p>
@endif
<h4 class="text-md-center text-capitalize text-left my-3 mb-4">{{ $task->task_title }} <small class="badge badge-primary"><span>Total Hours: {{ $hours }}</span></small></h4>
<a href="{{ route('create.task.taskLog', ['id' => $task->id]) }}" class="btn btn-outline-secondary">Create New Log </a>
{{-- <span class="badge badge-success float-right">Paid Logs :- {{ $completedLogs }}</span>
<span class="badge badge-danger float-right">Unpaid Logs :- {{ $pendingLogs }}</span> --}}
<p class="text-center text-success">
  @if(session('success'))
    {{ session('success') }}
  @endif
</p>
<div class="row mt-2">
  <div class="col-md-9 offset-md-2">
    <table class="table table-hover table-responsive">
      {{-- IF PROJECT HAS NOT FIXED PAYMENT --}}
        @if($taskLog)
        @if ($per_hour_rate != 0)
        <thead>
          <tr>
            <th>Start Time</th>
            <th>End Time</th>
            <th>Developer</th>
            <th>Date Created</th>
            <th>Log Difference</th>
            {{-- <th>Log Cost</th> --}}
            {{-- <th>Pay Status</th>
            <th>Receipt</th> --}}
            <th>Modify Log</th>
          </tr>
        </thead>
        <tbody>
        @foreach ($taskLog as $index => $item)
          
            <tr class="text-center">
              <td>{{ $item->start_time }}</td>
              <td>{{ $item->end_time }}</td>
              <td>{{ $item->user->name }}</td>
              <td>{{ $item->log_creation_date }}</td>
              <td>{{ $logDifference[$index] }}</td>
              {{-- <td>${{ str_replace(':', '.', $logDifference[$index]) * $per_hour_rate }}</td> --}}
              {{-- <td>
                @if ($item->log_status == 'pending')
                    <span class="text-danger">Unpaid</span>
                @endif
                @if ($item->log_status == 'complete')
                    <span class="text-success">Paid</span>
                @endif
              </td> --}}
              {{-- invoice Tab--}}
              {{-- <td>
                @if ($item->log_status == 'complete')
                  <form method="POST" action="{{ route('admin.generate.invoice') }}">
                    @csrf
                    <input type="hidden" name="logId" value="{{ $item->id }}">
                    <button class="btn btn-default btn-sm" type="submit"><span class="badge badge-warning">Get Invoice</span></button>
                  </form>  
                  @else
                  <span class="badge badge-info">not available</span>                
                @endif
              </td> --}}

              {{-- <td>
                @if ($item->log_status == 'complete')                  
                      <form method="POST" action="{{ route('admin.invoice.receipt') }}">
                        @csrf
                        <input type="hidden" name="logId" value="{{ $item->id }}">
                        <button class="btn btn-default btn-sm" type="submit"><span class="badge badge-warning">Get Receipt</span></button>
                      </form>  
                  @else
                  <a href="{{ route('change.log.status', ['logID' => $item->id]) }}" class="badge badge-primary p-0">Generate Receipt</a>      
                @endif
              </td> --}}

              <td class="btn-group">                 
                  <a href="{{ route('admin.edit.log.form',['id' => $item->id]) }}" class="btn text-info"><i class="bi bi-pencil-square"></i></a>        
                  <a href="{{ route('admin.delete.log', ['id' => $item->id]) }}" class="btn text-danger"><i class="bi bi-trash-fill"></i></a>        
              </td>
            </tr>
        @endforeach
        @endif
      </tbody>
      @endif


      {{-- IF PROJECT HAS FIXED PAYMENT --}}


      @if($taskLog)
        @if ($per_hour_rate == 0)
        <thead>
          <tr>
            <th>Start Time</th>
            <th>End Time</th>
            <th>Developer</th>
            <th>Date Created</th>
            
            <th>Modify Log</th>
          </tr>
        </thead>
        <tbody>
        @foreach ($taskLog as $index => $item)
            <tr>
              <td>{{ $item->start_time }}</td>
              <td>{{ $item->end_time }}</td>
              <td>{{ $item->user->name }}</td>
              <td>{{ $item->created_at->format('d-M-Y') }}</td>
              <td class="btn-group">                 
                  <a href="{{ route('admin.edit.log.form', ['id' => $item->id]) }}" class="btn text-info"><i class="bi bi-pencil-square"></i></a>        
                  <a href="{{ route('admin.delete.log', ['id' => $item->id]) }}" class="btn text-danger"><i class="bi bi-trash-fill"></i></a>        
              </td>
            </tr>
        @endforeach
      
        @endif
      </tbody>
      @endif


    </table>
  </div>
</div>
@endsection