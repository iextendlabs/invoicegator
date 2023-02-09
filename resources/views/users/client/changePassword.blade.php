@extends('customLayout.layout')

@section('title', 'Change Password')

<!-- navbar -->
@section('navbar')
<nav class="navbar navbar-expand-lg navbar-dark bg-success w-100">
    <a class="navbar-brand" href="{{ route('client.dashboard') }}">
        <span class="d-md-inline-block text-light">Client  | <small>Change Password</small></span>
        {{-- <span class="d-md-none">Client | <small>Change Password</small></span> --}}
    </a>
    <a href="{{ route('client.logout') }}" class="nav-link ml-auto text-light" style="font-size: 18px;">Logout</a>
</nav>
@endsection

<!-- Page => main content -->
@section('main-content')
<div class="row mt-2 mb-3">
    <h2 class="mx-auto" style="color: darkgreen">Change Your Password</h2>
</div>

<div class="row">
    @if(session('success'))
    <small class="alert alert-success text-center text-danger mx-auto">{{ session('success') }}</small>
    @endif
</div>

<div class="row">
    <div class="col-md-9 offset-md-1">
        <form action="{{ route('client.password.update') }}" method="post">
            @csrf
            <div class="form-row">
                <label for="old-pass"><small>Enter Old Password</small></label>
                <input type="password" class="form-control form-control-sm" name="old-pass" value="{{ old('old-pass') }}" id="old-pass" placeholder="old password">
                @if($errors->any())
                @foreach($errors->get('old-pass') as $err)
                <small class="text-danger ml-3">{{ $err }}</small>
                @endforeach
                @endif
            </div>
            <br>
            <div class="form-row">
                <label for="new-pass"><small>Enter New Password</small></label>
                <input type="password" class="form-control form-control-sm" name="new-pass" id="new-pass" value="{{ old('new-pass') }}" placeholder="new password">
                <small class="text-sm text-danger">atleast 8 characters</small>
                @if($errors->any())
                @foreach($errors->get('new-pass') as $err)
                <small class="text-danger ml-3">{{ $err }}</small>
                @endforeach
                @endif
            </div>
            <br>
            <div class="form-row">
                <label for="con-new-pass"><small>Conform New Password</small></label>
                <input type="password" class="form-control form-control-sm" name="confirm-pass" id="con-new-pass" value="{{ old('confirm-pass') }}" placeholder="confirm password">
                @if($errors->any())
                @foreach($errors->get('confirm-pass') as $err)
                <small class="text-danger ml-3">{{ $err }}</small>
                @endforeach
                @endif
            </div>
            <br>
            <button type="submit" class="btn btn-success">Change Password</button>
            <button type="submit" class="btn btn-dark"><a href="{{ route('client.dashboard') }}" class="text-light">Back</a></button>
        </form>
    </div>
</div>

@endsection