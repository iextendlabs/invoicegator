@extends('customLayout.layout')

@section('title', 'User Dashboard')

<!-- navbar -->
@section('navbar')
<nav class="navbar navbar-expand-lg navbar-dark bg-dark w-100">
    <a href="{{ route('user.dashboard') }}" class="nav-link text-light"><i class="bi bi-arrow-left-circle"></i></a>
    <a class="navbar-brand" href="{{ route('user.dashboard') }}">
        <span class="d-md-inline-block d-none">Invoice Management System | <small>Project List</small></span>
        <span class="d-md-none">IMS | <small>Dashboard</small></span>
    </a>
    <a href="{{ url('logout') }}" class="nav-link ml-auto text-light" style="font-size: 18px;">Logout</a>
</nav>
@endsection

<!-- Page => main content -->
@section('main-content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Your Projects</th>
                </tr>
            </thead>
            <tbody>
                @foreach($project as $data)
                <tr>
                    <td><a href="{{ route('user.create.task', ['pid' => $data->id]) }}">{{ $data->project_name }}</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection