@extends('customLayout.layout')

@section('title', 'Project Preview')

<div id="printPreview">
@section('navbar')
    <div class="col-12 text-center py-2 text-light bg-primary">
        <a href="{{ url()->previous() }}" class="nav-link text-light d-inline float-left d-print-none"><i class="bi bi-arrow-left-circle"></i></a>
        <h5 class="d-inline-block font-weight-normal">Preview Task <span class="text-capitalize font-weight-bolder">{{ $taskTitle }}</span></h5> 
        <button id="prntBtn" class="d-inline float-right d-print-none btn text-light"><abbr title="Print Report"><i class="bi bi-printer-fill"></i></abbr></button>
    </div>
@endsection

@section('main-content')
    <div class="row my-5">
        <div class="col-md-10 offset-md-1 col-12">
            <table class="table table-light table-hover text-center">
                <thead>
                    <tr>
                        <th>Developer Name</th>
                        <th>Created At</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Time Difference</th>
                        <th>Payment</th>
                        <th>Log Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($logs as $index => $log)
                    <tr>
                        <td>{{ $log->user->name }}</td>
                        <td>{{ $log->created_at->format('d-M-Y') }}</td>
                        <td>{{ $log->start_time }}</td>
                        <td>{{ $log->end_time }}</td>
                        <td>{{ $logDifference[$index] }}</td>
                        <td>${{ $log->task->project->per_hour_rate * str_replace(':', '.', $logDifference[$index]) }}</td>
                        <td>
                            @if ($log->log_status == 'pending')
                                <span class="text-danger text-capitalize">Unpaid</span>
                            @endif

                            @if ($log->log_status == 'complete')
                                <span class="text-success text-capitalize">Paid</span>
                            @endif
                        </td>
                    </tr>
                        
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
</div>

