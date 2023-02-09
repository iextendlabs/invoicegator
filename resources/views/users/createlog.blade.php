@extends('customLayout.layout')

@section('title', 'User Dashboard')

<!-- navbar -->
@section('navbar')
<nav class="navbar navbar-expand-lg navbar-dark bg-dark w-100">
    <a href="{{ route('user.dashboard') }}" class="nav-link text-light"><i class="bi bi-arrow-left-circle"></i></a>
    <a class="navbar-brand" href="{{ route('user.dashboard') }}">
        <span class="d-md-inline-block d-none">Invoice Management System | <small>Create Log</small></span>
        <span class="d-md-none">IMS | <small>Dashboard</small></span>
    </a>
    <a href="{{ url('logout') }}" class="nav-link ml-auto text-light" style="font-size: 18px;">Logout</a>
</nav>
@endsection

{{-- main content  --}}
@section('main-content')
<div class="row py-3">
    @if(session('success'))
        {{ session('success') }}
    @endif
    <h4 class="mx-auto font-italic text-success">Add new Task Log</h4>
</div>

<div class="row">
    <div class="col-md-8 offset-md-2 col-sm-12">
        <form action="{{ route('user.post.log') }}" method="post">
            @csrf
            {{-- hidden task ID --}}
            <input type="hidden" name="taskId" value="{{ $taskId }}">
            <input type="hidden" name="uid" value="{{ $uid[0] }}">

            <label for="starttime" class="form-label"><small>Start Time</small></label>
            <input type="time"  class="form-control" name="starttime">
            @if($errors->any())
            @foreach($errors->get('starttime') as $name_err)
            <small class="text-danger ml-3">{{ $name_err }}</small>
            @endforeach
            @endif
                <br>

            <label for="endtime" class="form-label"><small>End Time</small></label>
            <input type="time" class="form-control" name="endtime">
            @if($errors->any())
            @foreach($errors->get('endtime') as $name_err)
            <small class="text-danger ml-3">{{ $name_err }}</small>
            @endforeach
            @endif
            <br>

            <label for="endtime" class="form-label"><small>Log Creation Date</small></label>
            <input type="text" onfocus="(this.type='date')" onblur="(this.type='text')" class="form-control" name="date_creation" placeholder="dd/mm/yyyy">
            @if($errors->any())
            @foreach($errors->get('date_creation') as $name_err)
            <small class="text-danger ml-3">{{ $name_err }}</small>
            @endforeach
            @endif
            <br>

            <button type="submit" name="submit" class="btn btn-primary btn-block">Create Log</button>
        </form>
    </div>
</div>
@endsection