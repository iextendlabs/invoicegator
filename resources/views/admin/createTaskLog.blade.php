@extends('customLayout.layout')

@section('title', 'Create Log')

<!-- navbar -->

@section('navbar')
<nav class="navbar navbar-expand-lg navbar-dark bg-dark w-100">
    <a href="{{ route('adminDashboard') }}" class="nav-link text-light"><i class="bi bi-arrow-left-circle"></i></a>
    <a class="navbar-brand" href="{{ route('adminDashboard') }}">Admin | <small>Task Log</small></a>
    <a href="{{ route('adminlogout') }}" class="nav-link ml-auto text-light">Logout</a>
</nav>
@endsection

{{-- main content  --}}
@section('main-content')
<div class="row py-3">
    <h4 class="mx-auto font-italic text-success d-block">Add new Task Log</h4>
</div>
@if(session('success'))
<p class="text-center text-primary">{{ session('success') }}</p>
@endif
<div class="row">
    <div class="col-md-8 offset-md-2 col-sm-12">
        <form action="{{ route('store.task.log') }}" method="post">
            @csrf
            <label for="developer" class="form-label">Developer Name</label>
            <select name="dev_id" class="form-control">
                @foreach ($dev as $dev)
                    <option value="{{ $dev->id }}">{{ $dev->name }}</option>
                @endforeach
            </select>

            @if($errors->any())
            @foreach($errors->get('dev_id') as $name_err)
            <small class="text-danger ml-3">{{ $name_err }}</small>
            @endforeach
            @endif
            <br>

            {{-- <label for="project" class="form-label">Project Name</label>
            <select name="project_id" class="form-control">
                @foreach ($project as $project)
                    <option value="{{ $project->id }}">{{ $project->project_name }}</option>
                @endforeach
            </select>

            <br> --}}
            @if($errors->any())
            @foreach($errors->get('project_id') as $name_err)
            <small class="text-danger ml-3">{{ $name_err }}</small>
            @endforeach
            @endif


            <label for="task" class="form-label">Task Title</label>
            <select name="task_id" class="form-control">
                @foreach ($task as $task)
                    <option value="{{ $task->id }}">{{ $task->task_title }}</option>
                @endforeach
            </select>
            <br>

            @if($errors->any())
            @foreach($errors->get('task_id') as $name_err)
            <small class="text-danger ml-3">{{ $name_err }}</small>
            @endforeach
            @endif
            {{-- log creation date --}}

            <label for="date_created" class="form-label">Creation Date</label>
            <input type="text" class="form-control form-control-sm" id="date_creation" placeholder="Enter Date" name="date_creation" onfocus="(this.type='date')" id="">
            @if($errors->any())
            @foreach($errors->get('date_creation') as $name_err)
            <small class="text-danger ml-3">{{ $name_err }}</small>
            @endforeach
            @endif
            <br>

            {{-- time --}}
            <div class="row">
                <div class="col-6">
                    <label for="starttime" class="form-label"><small>Start Time</small></label>
                    <input type="time"  placeholder="MM/DD/YYYY" class="form-control" name="starttime">
                    @if($errors->any())
                    @foreach($errors->get('starttime') as $name_err)
                    <small class="text-danger ml-3">{{ $name_err }}</small>
                    @endforeach
                    @endif
                </div>
                
                <div class="col-6">
                    <label for="endtime" class="form-label"><small>End Time</small></label>
                    <input type="time" class="form-control" name="endtime">
                    @if($errors->any())
                    @foreach($errors->get('endtime') as $name_err)
                    <small class="text-danger ml-3">{{ $name_err }}</small>
                    @endforeach
                    @endif
                </div>
            </div>
            <br>

            <label for="logStatus" class="form-label">Log Status</label>
            <select name="logStatus" class="form-control">
                <option value="pending">Pending</option>
                <option value="complete">Complete</option>
            </select>
            <br>
            
            <button type="submit" class="btn btn-default btn-block border-danger text-primary">Create Log</button>
        </form>
    </div>
</div>
@endsection