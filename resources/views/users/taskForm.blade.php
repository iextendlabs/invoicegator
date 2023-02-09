@extends('customLayout.layout')

@section('title', 'User Dashboard')

<!-- navbar -->
@section('navbar')
<nav class="navbar navbar-expand-lg navbar-dark bg-dark w-100">
    <a href="{{ route('user.dashboard') }}" class="nav-link text-light"><i class="bi bi-arrow-left-circle"></i></a>
    <a class="navbar-brand" href="{{ route('user.dashboard') }}"><span class="d-md-inline-block d-none">Invoice Management System</span><span class="d-md-none">IMS</span></a>
    <a href="{{ url('logout') }}" class="nav-link ml-auto text-light" style="font-size: 18px;">Logout</a>
</nav>
@endsection

@section('main-content')
<div class="row my-3">
    <h4 class="mx-auto text-primary">Create a new task</h4>
</div>
<div class="row">
    <div class="col-md-8 offset-md-2 col-sm-12">
        <form action="{{ route('user.store.task') }}" method="post">
            @csrf

            <label for="project" class="form-label ml-1" style="font-size: 14px;">Project Name</label>
            <input type="text" name="project_name" value="{{ $project->project_name }}" id="project_id" readonly placeholder="Project Name" class="form-control form-control-sm">
            <small class="text-info">Title must be atleast 5 characters</small>
            <br>

            {{-- hidden project ID --}}
            <input type="hidden" name="project_id" value="{{ $project->id }}">

            <label for="taskTitle" class="form-label ml-1" style="font-size: 14px;">Task Title</label>
            <input type="text" name="taskTitle" value="{{ old('taskTitle') }}" id="taskTitle" placeholder="Task Title" class="form-control form-control-sm">
            <small class="text-info">Title must be atleast 5 characters</small>
            @if($errors->any())
            @foreach($errors->get('taskTitle') as $name_err)
            <small class="text-danger ml-3">{{ $name_err }}</small>
            @endforeach
            @endif
            <br>

            <label for="taskDesc" class="form-label ml-1" style="font-size: 14px;">Task Description</label>
            <textarea name="taskDesc" id="taskDesc" cols="30" rows="5" class="form-control form-control-sm" placeholder="Task Description">{{old('taskDesc')}}</textarea>
            <small class="text-info">Title must be atleast 5 characters</small>
            @if($errors->any())
            @foreach($errors->get('taskDesc') as $name_err)
            <small class="text-danger ml-3">{{ $name_err }}</small>
            @endforeach
            @endif
            <br>
            {{-- <input type="text" placeholder="Date of birth" onfocus="(this.type='date')">  --}}
            <input type="hidden" name="status" value="pendnig">

            <label for="assignDate" class="form-label ml-1" style="font-size: 14px;">Date Creation</label>
            <input type="text" name="assignDate" id="assignDate" placeholder="dd-mm-yyyy" onfocus="(this.type='date')" onblur="(this.type='text')" value="{{ old('assignDate') }}" class="form-control form-control-sm">
            @if($errors->any())
            @foreach($errors->get('assignDate') as $name_err)
            <small class="text-danger ml-3">{{ $name_err }}</small>
            @endforeach
            @endif
            <br>

            <button type="submit" name="addTask" class="btn btn-block btn-outline-success">Create Task</button>
        </form>
    </div>
</div>
@endsection