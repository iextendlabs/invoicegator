@extends('customLayout.layout')

@section('title', 'Project Details')

<!-- navbar -->

@section('navbar')
<nav class="navbar navbar-expand-lg navbar-dark bg-dark w-100">
    <a href="{{ route('adminDashboard') }}" class="nav-link text-light"><i class="bi bi-arrow-left-circle"></i></a>
    <a class="navbar-brand" href="{{ route('admin/create/project') }}">Admin | <small>Project Available</small> </a>
    <a href="{{ route('adminlogout') }}" class="nav-link ml-auto text-light">Logout</a>

</nav>
@endsection

<!-- main content -->

@section('main-content')
<div class="row my-2">
    @if(session('success'))
    <small class="text-center">{{ session('success') }}</small>
    @endif
</div>
<div class="row mt-2">
    <div class="col-md-10 offset-md-2">
        <table class="table table-responsive table-hover table-bordered">
            <thead class="bg-info text-light text-center">
                <tr>
                    <th>Project Name</th>
                    {{-- <th>Description</th> --}}
                    <th>Created Time</th>
                    <th>Project Status</th>
                    <th>View Details</th>
                    <th>Delete Project</th>
                </tr>
            </thead>
            <tbody>
                @foreach($projects as $project)
                <tr>
                    <td>{{ $project->project_name }}</td>
                    <td>{{ $project->created_at }}</td>
                    <td class="text-capitalize">{{ $project->project_status }}</td>
                    <td class="text-center h5"><a href="{{ route('admin/project-edit', ['id'=>$project->id]) }}" class="text-success"><i class="bi bi-eye-fill"></i></a></td>
                    {{-- <td class="text-center h5"><a href="project/{{ $project->id }}" class="text-success"><i class="bi bi-eye-fill"></i></a></td> --}}
                    <td class="text-center h5"><a href="delete/project/{{ $project->id }}" class="text-danger"><i class="bi bi-trash3-fill"></i></a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <br>
        {{$projects->links()}}
    </div>
</div>
@endsection