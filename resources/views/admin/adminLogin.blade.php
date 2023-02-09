@extends('customLayout.layout')

@section('title', 'Login as Admin')

<!-- navbar -->

@section('navbar')

<nav class="navbar navbar-expand-lg navbar-dark bg-dark w-100">
    <a class="navbar-brand" href="{{ route('adminLogin') }}">
        <span class="d-md-inline-block d-none">Administrator | <small>Area</small></span>
    </a>
    <a href="{{ route('AdminsignUp') }}" class="nav-link ml-auto text-light">Create Account</a>
</nav>

@endsection


<!-- login form -->
@section('main-content')
<div class="col-md-6 offset-md-3 my-4">
    <div class="card-header bg-primary p-1">
        <h3 class="text-md-right text-center text-light mr-4">Admin Access</h3>
    </div>
    <div class="card-body shadow bg-light">
        <form action="{{ route('adminAuthenticate') }}" method="post">
            @csrf

            <div class="input-group flex-nowrap">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-success text-light"><i class="bi bi-envelope-fill"></i></span>
                </div>
                <input type="email" name="adminEmail" class="form-control border-success border-style" value="{{ old('email') }}" placeholder="Email">
                @if($errors->any())
                @foreach($errors->get('adminEmail') as $name_err)
                <small class="text-danger ml-3">{{ $name_err }}</small>
                @endforeach
                @endif
            </div>
            <br>
            <div class="input-group flex-nowrap">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-danger text-light"><i class="bi bi-key-fill"></i></span>
                </div>
                <input type="password" name="adminPassword" class="form-control border-danger border-style" placeholder="Password">
                @if($errors->any())
                @foreach($errors->get('adminPassword') as $name_err)
                <small class="text-danger ml-3">{{ $name_err }}</small>
                @endforeach
                @endif
            </div>
            <br>

            <div class="d-flex justify-content-end">
                @if(session('success_msg'))
                <span class="text-danger mr-5">{{ session('success_msg') }}</span>
                @endif
                <button class="btn btn-danger mr-5">Enter</button>
            </div>
        </form>
    </div>
</div>
@endsection