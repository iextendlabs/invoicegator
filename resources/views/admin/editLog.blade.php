@extends('customLayout.layout')

@section('title', 'Log Details')

<!-- navbar -->

@section('navbar')
<nav class="navbar navbar-expand-lg navbar-dark bg-dark w-100">
  <a href="{{ url()->previous() }}" class="nav-link text-light"><i class="bi bi-arrow-left-circle"></i></a>
  <a class="navbar-brand" href="">Admin | <small>Edit Log</small> </a>
    <a href="{{ route('adminlogout') }}" class="nav-link ml-auto text-light">Logout</a>

</nav>
@endsection

<!-- main content -->
@section('main-content')
<div class="row">
    <p class="text-center mx-auto">
        @if(session('success'))
          <span class="text-danger">{{ session('success') }}</span>    
        @endif
    </p>
</div>

<div class="row">
    <h5 class="text-success mx-auto">Update Log Details</h5>
</div>

<div class="row mt-2">
    <div class="col-md-9 offset-md-1">
        <form action="{{ route('admin.edit.log') }}" method="post">
            @csrf
            <input type="hidden" name="logID" value="{{ $log->id }}">
            <label for="starttime" class="form-label">Start Time</label>
            <input type="text" class="form-control" value="{{ $log->start_time }}" onfocus="(this.type='time')" onblur="(this.type='text')" placeholder="enter starting time  (hh/mm/am?pm)" name="starttime" id="starttime">
            @if($errors->any())
            @foreach($errors->get('starttime') as $err)
            <small class="text-danger ml-3">{{ $err }}</small>
            @endforeach
            @endif
            <br>

            <label for="endtime" class="form-label">End Time</label>
            <input type="text" class="form-control" value="{{ $log->end_time }}" onfocus="(this.type='time')" onblur="(this.type='text')" placeholder="enter ending time  (dd/mm/yyyy)" name="endtime" id="endtime">
            @if($errors->any())
            @foreach($errors->get('endtime') as $err)
            <small class="text-danger ml-3">{{ $err }}</small>
            @endforeach
            @endif
            <br>

            <label for="date" class="form-label">Date Created</label>
            <input type="text" onfocus="(this.type='date')" onblur="(this.type='text')" class="form-control" value="{{ $log->log_creation_date }}" placeholder="enter date  (dd/mm/yyyy)" name="date" id="date">
            @if($errors->any())
            @foreach($errors->get('date') as $err)
            <small class="text-danger ml-3">{{ $err }}</small>
            @endforeach
            @endif
            <br>

            <label for="developerName" class="form-label">Developer</label>
            <select name="developerName" class="form-control form-control-sm" id="developerName">
            @foreach ($developer as $devs)
            <option value="{{ $devs->id }}">{{ $devs->name }}</option>
            @endforeach                
            </select>
            <br>

            <label for="logStatus" class="form-label">Log Status</label>
            <select name="logStatus" class="form-control form-control-sm" id="logStatus">
            @if($log->log_status == 'complete')
                 <option value="pending">Pending</option>
                <option value="complete" selected>Complete</option>
            @endif  

            @if($log->log_status == 'pending')
                 <option value="pending" selected>Pending</option>
                <option value="complete">Complete</option>
            @endif       
            </select>
            <br>

            <button type="submit" class="btn btn-primary float-right">Update Log Information</button>
        </form>
    </div>
</div>
@endsection