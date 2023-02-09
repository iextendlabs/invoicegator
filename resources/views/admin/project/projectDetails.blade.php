@extends('customLayout.layout')

@section('title', 'Project Details')

<!-- navbar -->

@section('navbar')
<nav class="navbar navbar-expand-lg navbar-dark bg-dark w-100">
    <a href="{{ route('adminDashboard') }}" class="nav-link text-light"><i class="bi bi-arrow-left-circle"></i></a>
    <a class="navbar-brand" href="{{ route('admin/create/project') }}">Admin | <small>Project Details</small> </a>
    <a href="{{ route('adminlogout') }}" class="nav-link ml-auto text-light">Logout</a>

</nav>
@endsection


@section('main-content')
<div class="row py-2">
    <h4 class="mx-auto text-info"><i>Showing Available Tasks</i></h4>
</div>
<div class="row mt-2">
    <!-- column 1 -->
    <div class="col-md-12">        
            <table class="table table-responsive table-hover">
                <thead class="bg-primary text-light text-center">
                    <tr>
                        <th>Task Id</th>
                        <th>Task Title</th>
                        <th>Task Description</th>
                        <th>Assigned Date</th>
                        <th>Assigned to</th>
                        <th>Task Status</th>
                        <th class="bg-danger">Delete Task</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($project_task_details)
                        @foreach ($project_task_details as $item)                        
                            <tr>
                                <td class="text-center"><a href="{{ route('explore-task/logs', ['task_id'=>$item->id]) }}"> {{ $item->id }}</a></td>
                                <td>{{ $item->task_title }}</td>
                                <td>{{ $item->task_desc }}</td>
                                <td>{{ $item->created_at }}</td>
                                <td class="text-center">{{ $item->user_id }}</td>
                                <td>{{ $item->task_status }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin/task-details', ['id' => $item->id]) }}" class="text-primary">Edit</a>
                                    <span class="text-info">|</span>
                                    <a href="{{ route('admin/task-delete', ['id' => $item->id]) }}" class="text-danger">Del</a>
                                </td>
                            </tr>
                        @endforeach   
                    @endif
                </tbody>
            </table>
    </div>
</div>
@endsection