@extends('customLayout.layout')

@section('title', 'Client Dashboard')

<!-- navbar -->
@section('navbar')

<nav class="navbar navbar-expand-lg navbar-dark bg-success w-100">
    <a class="navbar-brand" href="{{ route('client.dashboard') }}">
        <span class="d-md-inline-block d-none"><b class="text-capitalize">{{ $role }}</b> | <small>Dashboard</small></span>
        <span class="d-md-none">Client | <small>Dashboard</small></span>
    </a>
    <div class="ml-auto d-flex">
        <a href="{{ route('client.change.password') }}" class="nav-link text-light">Change Password</a>
        <a href="{{ route('client.logout') }}" class="nav-link text-light">Logout</a>
    </div>
        
</nav>
@endsection

<!-- Page => Main Content -->
@section('main-content')


<div class="row my-2">
    <h5 class="text-sm mx-auto mb-3 text-danger">List of Projects</h5>
</div>

<div class="row my-2">
    @if(session('success'))
        <h6 class="text-success"><small class="mx-auto">{{ session('success') }}</small></h6>
    @endif
</div>
<div class="row my-2">
    <div class="col-md-10 offset-md-1 col-12">
        <table class="table table-hover">
            <thead>
                <tr class="text-center">
                    <th>Project Name</th>
                    <th>Project Description</th>
                    <th>Project Total</th>
                    <th>Paid</th>
                    <th>Remaining</th>
                    <th>Created at</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($projects as $project)
                    <tr class="text-center">
                        <td><a href="{{ route('client.available.invoices.view', ['id' => $project->id]) }}">{{ $project->project_name }}</a></td>
                        <td>{{ $project->project_desc }}</td>
                        <td>{{ $project_total }}</td>
                        <td>{{ $paid }}</td>
                        <td>{{ $remaining }}</td>
                        <td>{{ $project->created_at->format('d-M-Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br>
        {{ $projects->links() }}
        
    </div>
</div>
@endsection
