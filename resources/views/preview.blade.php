@extends('customLayout.layout')

@section('title', 'Project Preview')

<div id="printPreview">
@section('navbar')
    <div class="col-12 text-center py-2 text-light bg-primary">
        <a href="{{ url()->previous() }}" class="nav-link text-light float-left d-inline d-print-none"><i class="bi bi-arrow-left-circle"></i></a>
        <h5 class="d-inline">Preview Project [ <span class="text-danger">{{ $project->project_name }}</span> ]</h5> 
        <button id="prntBtn" class="btn d-inline float-right d-print-none text-light"><abbr title="Print Report"><i class="bi bi-printer-fill"></i></abbr></button>
        {{-- <a href="{{ route('report.project.preview', ['id' => $project->id]) }}" class="btn d-inline float-right d-print-none text-light"><abbr title="Generate PDF Report"><i class="bi bi-printer-fill"></i></abbr></a> --}}
    </div>
@endsection

@section('main-content')
    <h5 class="mx-auto text-center my-3 font-italic">Tasks</h5>
    @if ($project->per_hour_rate  == 0)
    @include('admin.project.fixedProjectPreview')
        @else
    <div class="row justify-content-center">
        <div class="col-md-9 col-12">
            <table class="table table-danger table-bordered">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Assign to</th>
                        <th>Status</th>
                        <th>Time Spend</th>
                        <th>Task Cost</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($task as $index => $task )
                    <tr>
                        <td>{{ $task->task_title }}</td>
                        <td>{{ $task->task_desc }}</td>
                        <td >{{ $task->user->name }}</td>
                        <td class="text-capitalize">
                            @if ($task->task_status == 'pending')                                
                            <span class="text-danger">{{ $task->task_status }}</span>
                            @endif

                            @if ($task->task_status == 'completed')                                
                            <span class="text-sucess">{{ $task->task_status }}</span>
                            @endif
                        </td>
                        <td>{{ str_replace('.', ':', $hours[$index]) }}</td>
                        <td>${{ $hours[$index] * $hourRate}}</td>
                        @endforeach
                    </tr>
                </tbody>                
            </table>
        </div>
    </div>

    <div class="row my-3">
        <div class="col-md-4 col-12 offset-md-6">
            {{-- <div class="card bg-primary text-light">
                <div class="card-body"> --}}
                    <div><b class="d-inline-block">Time Spend :</b><span style="text-decoration: underline;" class="float-right">&nbsp;{{ Request::get('time') }} hrs</span> </div><hr>
                    <div><b class="d-inline-block">Project Rate :</b><span style="text-decoration: underline;" class="float-right">&nbsp;${{ $project->project_rate }}</span> </div><hr>
                    <div><b class="d-inline-block">Logs Cost :</b><span style="text-decoration: underline;" class="float-right">&nbsp;${{ str_replace(':', '.', Request::get('time')) * $hourRate }}</span> </div><hr>
                    <div><b class="d-inline-block">Date Created :</b><span style="text-decoration: underline;" class="float-right">&nbsp;{{ $project->created_at->format('d-M-y') }}</span></div><hr>
                    <div><b class="d-inline-block">Created Time :</b><span style="text-decoration: underline;" class="float-right">&nbsp;{{ $project->created_at->format('h:i a') }}</span></div>
                {{-- </div>
            </div> --}}
        </div>
    </div>
    @endif
@endsection
</div>

