@extends('customLayout.layout')

@section('title', 'Create Admin')

<!-- navbar -->

@section('navbar')

<nav class="navbar navbar-expand-lg navbar-dark bg-dark w-100">
    <a class="navbar-brand" href="{{ route('adminLogin') }}">
        <span class="d-md-inline-block d-none">Administrator</span>
    </a>
    <a href="{{ route('adminLogin') }}" class="nav-link ml-auto text-light">Login</a>
</nav>

@endsection


<!-- register form -->
@section('main-content')
<div class="col-md-6 offset-md-3 my-4">
    <div class="card-header bg-dark p-1">
        <h3 class="text-md-right text-center text-light mr-4">Join as Admin</h3>
    </div>
    <div class="card-body shadow bg-light">
        <form action="/register" method="POST">
            @csrf
            <label class="form-label text-muted" for="name">Username</label>
            <input type="text" class="form-control mb-3" name="username" id="name" placeholder="Name">
            <!-- display error (if any) -->
            @if($errors->any())
            @foreach($errors->get('username') as $name_err)
            <small class="text-danger ml-3 d-block">{{ $name_err }}</small>
            @endforeach
            @endif
            <label class="form-label text-muted" for="email">Email Address</label>
            <input type="email" class="form-control" name="email" id="email" placeholder="Email">
            @if($errors->any())
            @foreach($errors->get('email') as $email_err)
            <small class="text-danger ml-3">{{ $email_err }}</small>
            @endforeach
            @endif
            <br>
            <label class="form-label text-muted" for="password">Password</label>
            <input type="password" class="form-control" name="password" id="password" placeholder="Password">
            @if($errors->any())
            @foreach($errors->get('password') as $pass_err)
            <small class="text-danger ml-3">{{ $pass_err }}</small>
            @endforeach
            @endif
    
            <br>
            <button type="submit" class="btn btn-outline-primary btn-block">Create</button>
            <div class="mt-4 d-flex text-center">
                @if(session()->has('status') && session('status') == 'success')
                <span class="alert alert-success messageAlert">Created Successfully!!</span>
                @endif
            </div>
        </form>
    </div>
</div>
@endsection