@extends('customLayout.layout')

@section('title', 'Developer Dashboard')

<!-- navbar -->
@section('navbar')

<nav class="navbar navbar-expand-lg navbar-dark bg-dark w-100">
    <a class="navbar-brand" href="{{ route('user.dashboard') }}">
        <span class="d-md-inline-block d-none"><b class="text-capitalize">{{ $role }}</b> | <small>Dashboard</small></span>
        <span class="d-md-none">IMS | <small>Dashboard</small></span>
    </a>
    <a href="{{ url('logout') }}" class="nav-link ml-auto text-light" style="font-size: 18px;">Logout</a>
</nav>
@endsection

<!-- Page => main content -->
@section('main-content')


@if($role != 'client')
<div class="row my-2">
    <a href="{{ route('user.project.list') }}" class="btn btn-outline-success btn-sm px-5">Create Task</a>
    <a href="{{ route('user.change.password') }}" class="btn btn-outline-danger btn-sm">Change Password</a>
</div>

<div class="row my-2">
    <h5 class="mx-auto text-primary">Showing Available Tasks</h5>
    @if(session('success'))
        <h6 class="text-success"><small class="mx-auto">{{ session('success') }}</small></h6>
    @endif   
</div>

<div class="row mt-3">
    <div class="col-md-10 offset-md-3 col-sm-12">
        <table class="table table-hover table-responsive">
            <thead class="thead-dark">
                <tr>
                    <th>Task Title</th>
                    <th>Task Status</th>
                    <th>Assign Date</th>
                    <th class="bg-danger">Delete</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tasks as $task)
                <tr>
                    <td><a href="{{ route('user.task.details', ['id' => $task->id]) }}" class="nav-link">{{ $task->task_title }}</a></td>
                    <td>
                        @if($task->task_status == 'pending')
                        <span class="text-danger text-capitalize">{{ $task->task_status }}</span>
                        @endif

                        @if($task->task_status == 'completed')
                        <span class="text-success text-capitalize">{{ $task->task_status }}</span>
                        @endif
                    </td>
                    <td>{{ $task->date }}</td>
                    <td class="text-center">
                        <a class="btn text-info">                            
                            <form action="task/delete/{{ $task->id }}" method="post">
                                @csrf
                                <button type="submit" class="text-info btn-default border-0"><i class="bi bi-trash3-fill"></i></button>
                            </form>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $tasks->links()}}
    </div>
</div>

@endif

@endsection