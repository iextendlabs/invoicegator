@extends('customLayout.layout')

@section('title', 'Project | create task')

<!-- navbar -->

@section('navbar')
<nav class="navbar navbar-expand-lg navbar-dark bg-dark w-100">
    <a href="{{ url()->previous() }}" class="nav-link text-light"><i class="bi bi-arrow-left-circle"></i></a>
    <a class="navbar-brand" href="{{ url('admin/dashboard') }}">Admin | <small>Project -> Create Task</small></a>
    <a href="{{ route('adminlogout') }}" class="nav-link ml-auto text-light">Logout</a>
</nav>
@endsection

<!-- Page => main content -->
@section('main-content')

<h3 class="text-center my-2 mb-4 text-muted" style="font-style: oblique;">Create Task</h3>
<div class="row mt-2">
    <div class="col-md-5 offset-md-4">
        @if(session('success'))
        <span class="alert alert-info">{{ session('success') }}</span>
        @endif
        <form action="{{ route('admin/storeTask') }}" method="post">
            @csrf

            <label for="taskTitle" class="form-label ml-1" style="font-size: 14px;">Task Title</label>
            <input type="text" name="taskTitle" id="taskTitle" placeholder="Task Title" class="form-control form-control-sm">
            <small class="text-info">Title must be atleast 5 characters</small>
            @if($errors->any())
            @foreach($errors->get('taskTitle') as $name_err)
            <small class="text-danger ml-3">{{ $name_err }}</small>
            @endforeach
            @endif
            <br>

            <label for="taskDesc" class="form-label ml-1" style="font-size: 14px;">Task Description</label>
            <textarea name="taskDesc" id="taskDesc" cols="30" rows="5" class="form-control form-control-sm" placeholder="Task Description"></textarea>
            {{-- <input type="text" name="taskDesc" id="taskDesc" placeholder="Task Description" class="form-control"> --}}
            <small class="text-info">Title must be atleast 5 characters</small>
            @if($errors->any())
            @foreach($errors->get('taskDesc') as $name_err)
            <small class="text-danger ml-3">{{ $name_err }}</small>
            @endforeach
            @endif
            <br>

            <input type="hidden" name="status" value="pending">
            <input type="hidden" name="assignProject" value="{{ $project->id }}">

            <label for="assignDate" class="form-label ml-1" style="font-size: 14px;">Date Assign</label>
            <input type="text" onfocus="(this.type='date')" onblur="(this.type='text')" name="assignDate" id="assignDate" class="form-control form-control-sm" placeholder="dd-mm-yyyy">
            @if($errors->any())
            @foreach($errors->get('assignDate') as $name_err)
            <small class="text-danger ml-3">{{ $name_err }}</small>
            @endforeach
            @endif
            <br>

            {{-- displaying developers --}}
            <label for="assignTo" class="form-label ml-1" style="font-size: 14px;">Assign To</label>
            <select name="assignTo" id="assignTo" class="form-control form-control-sm">
                @foreach($user as $user)
                <option value="{{ $user->id }}">{{ $user->name }} <span class="text-info">&nbsp; ({{ $user->user_role }})</span></option>
                @endforeach
            </select>
            @if($errors->any())
            @foreach($errors->get('assignTo') as $name_err)
            <small class="text-danger ml-3">{{ $name_err }}</small>
            @endforeach
            @endif
            <br>
            <button type="submit" name="addTask" class="btn btn-block btn-outline-success">Create Task</button>
        </form>
    </div>
</div>

@endsection