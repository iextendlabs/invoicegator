@extends('customLayout.layout')

@section('title', 'Task Invoice')

<!-- navbar -->

@section('navbar')
<nav class="navbar navbar-expand-lg navbar-dark bg-dark w-100">
  <a href="{{ url()->previous() }}" class="nav-link text-light"><i class="bi bi-arrow-left-circle"></i></a>
  <a class="navbar-brand" href="{{ url()->previous() }}">Admin | <small>Task Invoice</small> </a>
  <button id="prntBtn" class="ml-auto d-print-none btn text-light"><abbr title="Print Report"><i class="bi bi-printer-fill"></i></abbr></button>

</nav>
@endsection

<!-- main content -->
@section('main-content')
    <div class="row my-2">
        <div class="col-md-7 offset-md-2 col-12">
            <div class="my-3 bg-danger card-header">
                <h4 class="text-italic text-light">Log Receipt</h4>
            </div>
            <div class="card w-100">
                <div class="card-body bg-light">
                    <div class="card-title">
                        <h6 class="d-inline"><small>Project</small> &nbsp;<b>{{ $project->project_name }}</b></h6>
                        <img src="{{ asset('assets/paid.jpg') }}" alt="paidStamp" class="d-inline img-fluid float-right" style="width: 70px;">
                    </div>
                    <hr>
                    <div class="mb-4"><small>Task Title &nbsp; &nbsp;</small><b>{{ $task->task_title }}</b></div>
                    <div class="card-description">
                        <div class="row d-flex flex-inline-row">
                            <div class="col">
                                <div class="card-text">
                                    Developer Name
                                </div>
                                <hr>
                                <p>{{ $task->user->name }}</p>
                            </div>
                            <div class="col">
                                <div class="card-text">
                                    Assign Date
                                </div>
                                <hr>
                                <p>{{ $task->date }}</p>
                            </div>
                        </div>
                    </div>
                </div>
               {{-- LOG Details --}}
                <div class="card-footer">
                    <div class="row bg-success p-0">
                        <p class="mx-auto text-light"><b>Log Details</b></p>
                        <b class="float-right text-light mr-3">Log ID:- {{ $log->id }}</b>
                    </div>
                    <div class="row d-flex text-center flex-inline-row">
                        <div class="col">
                            <div class="card-text">
                               Creation Date
                            </div>
                            <hr>
                            <p>{{ $log->log_creation_date }}</p>
                        </div>
                        <div class="col">
                            <div class="card-text">
                                Start Time
                             </div>
                             <hr>
                             <p>{{ $log->start_time }}</p>
                        </div>
                        <div class="col">
                            <div class="card-text">
                                End Time
                             </div>
                             <hr>
                             <p>{{ $log->end_time }}</p>
                        </div>

                        <div class="col">
                            <div class="card-text">
                                Time Spend
                             </div>
                             <hr>
                             <p>{{ $diff }}</p>
                        </div>

                        <div class="col">
                            <div class="card-text">
                                Log Cost
                             </div>
                             <hr>
                             <p>${{ $logCost }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
@endsection