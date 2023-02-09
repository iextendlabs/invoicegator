@extends('customLayout.layout')

@section('title', 'Admin Dashboard')

<!-- navbar -->

@section('navbar')
<nav class="navbar navbar-expand-lg navbar-dark bg-dark w-100">
    <a class="navbar-brand" href="{{ route('adminDashboard') }}">Admin | <small>Home</small></a>
    <a href="{{ route('adminlogout') }}" class="nav-link ml-auto text-light">Logout</a>
</nav>
@endsection

<!-- main content -->

@section('main-content')
<div class="row px-3 py-1">
    {{-- <div class="btn-group" role="group">
        <button id="btnGroupDrop1" type="button" class="btn btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            Project Tasks
        </button>
        
        <div class="dropdown-menu">
            @foreach($projects as $project)
            <a class="dropdown-item" href="{{ route('explore/project', ['id' => $project->id]) }}">{{$project->project_name}}</a>
            @endforeach
        </div>
    </div> --}}

    <div class="nav-item dropdown d-md-none">
        <a class="dropdown-toggle btn-sm btn-primary" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-list"></i>
        </a>
        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="nav-link" href="{{ route('admin/create-task') }}">Create Task</a>
            <a class="nav-link" href="{{ route('admin/create/project') }}">Create Project</a>
            <a class="nav-link" href="{{ route('admin/projects') }}">Available Projects</a>
            <a class="nav-link" href="{{ route('create.task.log') }}">Create Task Log</a>
            <a class="nav-link" href="{{ route('create.user') }}">Create User</a>
            <a class="nav-link" href="{{ route('work.detail') }}">Work Detail</a>
            <a class="nav-link" href="{{ route('change.admin.password') }}">Change Password</a>
        </ul>
    </div>

    <div class="btn-group mx-auto d-md-inline d-none" role="group">
        <a class="btn btn-outline-info" href="{{ route('admin/create-task') }}">Create Task</a>
        <a class="btn btn-outline-info" href="{{ route('admin/create/project') }}">Create Project</a>
        {{-- <a class="btn btn-outline-info" href="{{ route('admin/projects') }}">Available Projects</a> --}}
        <a class="btn btn-outline-info" href="{{ route('create.task.log') }}">Create Task Log</a>
        <a class="btn btn-outline-dark" href="{{ route('create.user') }}">Create User</a>
        <a class="btn btn-outline-primary" href="{{ route('work.detail') }}">Work Detail</a>
        <a class="btn btn-outline-success" href="{{ route('change.admin.password') }}">Change Password</a>
    </div>
</div>

@if(session('success'))
<p class="text-center text-success">{{ session('success') }}</p>
@endif

<h3 class="text-center my-3" style="font-style: oblique;">Available <span class="text-info">Projects</span></h3>
<div class="row mt-3 text-center">
    <div class="col-md-10 offset-md-3 col-12">
        <div class="row">
           <table class="table table-responsive table-hover">
               <thead class="thead-dark">
                   <tr>
                       <th><small>Sr No.</small></th>                       
                       <th><small>Project Name</small></th>                       
                       <th><small>Status</small></th>
                       <th><small>Creation</small></th>
                       <th><small>Action</small></th>
                   </tr>
               </thead>
               <tbody>
                   @php static $sr_no = 1; @endphp
                @foreach($projects as $project)
                    <tr>
                        <td>{{ $sr_no++ }}</td>
                        {{-- <td class="d-none"><a href="{{ route('explore/project',['id'=> $project->id])}}" class="nav-link">{{ $project->id}}</a></td> --}}
                        <td><a href="{{ route('view.project',['id'=> $project->id])}}" class="nav-link"> {{ $project->project_name}} </a></td>                        
                        <td class="text-capitalize">
                            @if( $project->project_status == 'complete')
                                <span class="text-success">{{ $project->project_status}}</span>
                            @endif

                            @if( $project->project_status == 'pending')
                                <span class="text-danger">{{ $project->project_status}}</span>
                            @endif

                        </td>
                        <td>{{ $project->created_at}}</td>
                        <td>
                            <form method="post" action="{{ route('admin/project-delete',['id'=> $project->id])}}">
                                @csrf
                                <button class="btn btn-default text-danger"><i class="bi bi-trash-fill"></i></button>
                            </form>
                        </td>
                    </tr>
                    
                @endforeach
               </tbody>
            </table>
            {{ $projects->links() }}
        </div>

    </div>
</div>

<br><br>

<div class="row mb-5">
    <div class="col-12">
        <div class="accordion accordion-flush" id="accordionFlushExample">
            <div class="accordion-item">
              <h2 class="accordion-header" id="flush-headingOne">
                <button class="accordion-button collapsed btn btn-danger text-light btn-block" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                  Showing Available Users ( <small><b>{{ $totalUsers }}</b></small> )
                </button>
              </h2>
              <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">
                    <div class="row row-cols-md-3 roe-cols-1">
                        @foreach($users as $user)
                        <div class="card w-100 mb-md-0 mb-3">
                            <div class="card-header bg-dark text-light">
                                {{$user->name }}
                                <small class="float-right">
                                    @if ($user->user_role == 'developer')
                                        <span class="badge-success text-capitalize p-1">{{ $user->user_role }}</span>
                                    @endif
                                    
                                    @if ($user->user_role == 'client')
                                        <span class="badge-info text-capitalize p-1">{{ $user->user_role }}</span>
                                    @endif
                                </small>
                            </div>
                            <div class="card-body">
                                <h6 class="card-title">{{ $user->email }}</h6>
                            </div>
                            <div class="card-footer bg-warning text-light">
                                {{ $user->created_at->format('d-M-Y H:i:s') }}

                                <a class="float-right">
                                    <form action="{{ route('admin.delete.user', ['id' => $user->id]) }}" method="post">
                                        @csrf
                                        <button type="submit" class="btn btn-default mt-n2"><i class="bi text-danger text-sm bi-trash3"></i></button>
                                    </form>
                                </a>
                                <a href="{{ route('admin.edit.user', ['id' => $user->id]) }}" class="float-right"><i class="bi bi-pencil-square text-primary text-sm mx-3"></i></a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
              </div>
            </div>
        </div>
    </div>
</div>
@endsection