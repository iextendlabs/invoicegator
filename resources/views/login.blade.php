@extends('customLayout.layout')

@section('title', 'Invoice Management System')

<!-- navbar -->

@section('navbar')

<nav class="navbar navbar-expand-lg navbar-dark bg-dark w-100">
    <a class="navbar-brand" href="{{ route('userLogin') }}">
        <span class="d-md-inline-block d-none">Invoice Management System</span>
        <span class="d-md-none">IMS</span>
    </a>
    <a href="{{ route('signUp') }}" class="nav-link ml-auto text-light"><i class="bi bi-person-plus-fill mr-1"></i>SignUp</a>
</nav>

@endsection


<!-- login form -->
@section('main-content')

<div class="row">
<div class="col-md-6 offset-md-3 my-4">
    <div class="card-header bg-danger p-1">
        <h3 class="text-md-right text-center text-light mr-4">Enter Your Credentials</h3>
    </div>
    <div class="card-body shadow bg-light">
        <form action="{{ route('authenticate.user') }}" method="post">
            @csrf
            <div class="input-group flex-nowrap">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-success text-light"><i class="bi bi-envelope-fill"></i></span>
                </div>
                <input type="email" name="email" class="form-control border-success border-style" value="{{ old('email') }}" placeholder="Email">
                @if($errors->any())
                @foreach($errors->get('email') as $name_err)
                <small class="text-danger ml-3">{{ $name_err }}</small>
                @endforeach
                @endif
            </div>
            <br>
            <div class="input-group flex-nowrap">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-danger text-light"><i class="bi bi-key-fill"></i></span>
                </div>
                <input type="password" name="password" class="form-control border-danger border-style" placeholder="Password">
                @if($errors->any())
                @foreach($errors->get('password') as $name_err)
                <small class="text-danger ml-3">{{ $name_err }}</small>
                @endforeach
                @endif
            </div>
            <br>
            <p class="font-weight-bold text-info">Choose Role</p>

            <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" name="role" value="client" id="client" class="custom-control-input">
                <label class="custom-control-label" for="client">Client</label>
            </div>
            <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" name="role" value="developer" id="developer" class="custom-control-input input-success">
                <label class="custom-control-label" for="developer">Developer</label>
            </div>
            @if($errors->any())
            @foreach($errors->get('role') as $name_err)
            <small class="text-danger ml-3">{{ $name_err }}</small>
            @endforeach
            @endif
            <br><br>
            <div class="d-flex justify-content-end">
                @if(session('success_msg'))
                <span class="text-danger mr-5">{{ session('success_msg') }}</span>
                @endif
                <button class="btn btn-danger mr-5" type="submit" name="login">Login</button>
            </div>
        </form>
    </div>
</div>
</div>
@endsection