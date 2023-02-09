@extends('customLayout.layout')

@section('title', 'Create Developer')

<!-- navbar -->

@section('navbar')
<nav class="navbar navbar-expand-lg navbar-dark bg-dark w-100">
    <a href="{{ route('adminDashboard') }}" class="nav-link text-light"><i class="bi bi-arrow-left-circle"></i></a>
    <a class="navbar-brand" href="{{ route('admin/create/project') }}">Admin | <small>Edit User</small> </a>
    <a href="{{ route('adminlogout') }}" class="nav-link ml-auto text-light">Logout</a>

</nav>
@endsection

<!-- main content -->

@section('main-content')
<div class="row my-2">
    @if(session('success'))
    <small class="mx-auto text-success">{{ session('success') }}</small>
    @endif
</div>
<div class="row">
    <h4 class="mx-auto text-secondary"><i>Edit <span class="text-success">User</i></span></h4>
</div>
<div class="row mt-2">
    <div class="col-md-8 offset-md-2">
        <form action="{{ route('admin.update.user') }}" method="post">
            @csrf
            <input type="hidden" name="userId" value="{{ $details[0]->id }}">
            <label for="name" class="form-label">Enter Name</label>
            <input type="text" name="name" id="name" class="form-control form-control-sm" value="{{ $details[0]->name }}" placeholder="enter developer name">
            <small class="ml-3 text-info">minimum 7 characters</small>
            @if($errors->any('name'))
            @foreach ($errors->get('name') as $error)
            <small class="text-danger ml-3">{{ $error }}</small>
            @endforeach
            @endif
            <br>
            
            <label for="password" class="form-label">Enter New Password</label>
            <input type="password" name="passkey" id="myInput" class="form-control form-control-sm" placeholder="enter new password" value="{{ old('passkey') }}" autofocus>

            <small class="ml-3 text-info">minimum 8 characters</small>
            @if($errors->any('passkey'))
            @foreach ($errors->get('passkey') as $error)
            <small class="text-danger ml-3">{{ $error }}</small>
            @endforeach
            @endif
            <br>
    
            <div class="form-check">
                @if($details[0]->user_role === 'developer')
                <input class="form-check-input" type="radio" name="role" id="developer" value="developer" checked>
                <label class="form-check-label mr-5" for="developer">
                  Developer
                </label>
                <input class="form-check-input" type="radio"  name="role" id="client" value="client">
                <label class="form-check-label" for="client">
                 Client
                </label>
                @endif
                @if($details[0]->user_role === 'client')
                <input class="form-check-input" type="radio" name="role" id="developer" value="developer"> 
                <label class="form-check-label mr-5" for="developer">
                  Developer
                </label>
                <input class="form-check-input" type="radio"  name="role" id="client" value="client" checked>
                <label class="form-check-label" for="client">
                 Client
                </label>
                @endif

              </div>
            <br>
            @if($errors->any('role'))
            @foreach ($errors->get('role') as $error)
            <small class="text-danger ml-3">{{ $error }}</small>
            @endforeach
            @endif
            <button type="submit" name="editUser" id="createDeveloper" class="btn btn-outline-info btn-block">Edit User</button>
        </form>
    </div>
</div>
@endsection