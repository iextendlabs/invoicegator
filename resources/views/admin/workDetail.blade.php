@extends('customLayout.layout')

@section('title', 'Work Details')

<!-- navbar -->

@section('navbar')
<nav class="navbar navbar-expand-lg navbar-dark bg-dark w-100">
    <a href="{{ url()->previous() }}" class="nav-link text-light"><i class="bi bi-arrow-left-circle"></i></a>
    <a class="navbar-brand" href="{{ route('adminDashboard') }}">Admin | <small>Work Details</small></a>
    <a href="{{ route('adminlogout') }}" class="nav-link ml-auto text-light">Logout</a>
</nav>
@endsection

<!-- main content -->

@section('main-content')

<div class="row mt-2">
    <h4 class="mx-auto text-sm text-primary">Get Task Details</h4>
</div>
    <div class="row my-3">
        <div class="col-md-3 border border-primary offset-md-1 shadow p-3">
            <form action="{{ route('get.datewise.project.data') }}" method="post">
                @csrf
                <label for="startdate" class="form-label">Start Date</label>
                <input type="text" name="startdate" onblur="(this.type='text')" value="{{ old('startdate') }}" onfocus="(this.type='date')" id="startdate" class="form-control" placeholder="dd/mm/yyyy">
                <br>
    
                <label for="enddate" class="form-label">End Date</label>
                <input type="text" name="enddate" onblur="(this.type='text')" onfocus="(this.type='date')" id="enddate" value="{{ old('enddate') }}" class="form-control" placeholder="dd/mm/yyyy">
                <br>

                <button type="submit" class="btn btn-outline-danger btn-sm">Get Data</button>
            </form>
            @if (session('success'))
            <li class="text-danger"><small>{{ session('success') }}</small></li>
            @endif

            @if($errors->any('startdate'))
                @foreach ($errors->get('startdate') as $error)
                <li class="text-danger"><small>{{ $error }}</small></li>
                @endforeach
                @endif

                @if($errors->any('enddate'))
                @foreach ($errors->get('enddate') as $error)
                <li class="text-danger"><small>{{ $error }}</small></li>
                @endforeach
                @endif
        </div>

        {{-- Displaying Tasks --}}


        @if(isset($data))
        <div class="col-md-6 offset-md-1">
            <table class="table table-bordered table-hover">
                <thead class="thead-dark">
                    <tr class="text-center">
                        <th>Task Title</th>
                        <th>Task Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $data)
                    <tr class="text-center">
                        <td><a href="{{route('admin.task.work.detail',['id' => $data->id])}}">{{$data->task_title}}</a></td>
                        <td class="text-capitalize">
                            @if ($data->task_status == 'pending')
                                <span class="text-danger">{{$data->task_status}}</span>
                            @endif

                            @if ($data->task_status == 'completed')
                                <span class="text-success">{{$data->task_status}}</span>
                            @endif
                            
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        {{-- Displaying Logs --}}

        @if(isset($taskLogs))
            <div class="col-md-5 offset-md-1">
                <table class="table table-bordered table-responsive table-hover">
                    <thead class="thead-dark">
                        <tr class="text-center">
                            <th>Start At</th>
                            <th>End At</th>
                            <th>Date</th>
                            <th>Developer</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($taskLogs as $data)
                       
                        <tr class="text-center">
                            <td>{{$data->start_time}}</td>
                            <td>{{$data->end_time}}</td>
                            <td>{{$data->log_creation_date}}</td>
                            <td>{{$data->user->name}}</td>
                            <td>
                                @if ($data->log_status == 'pending')
                                    <span class="text-danger text-capitalize">{{$data->log_status}}</span>
                                @endif

                                @if ($data->log_status == 'complete')
                                    <span class="text-success text-capitalize">{{$data->log_status}}</span>
                                @endif                                
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

    </div>
    
@endsection