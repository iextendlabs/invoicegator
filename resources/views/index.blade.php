@extends('customLayout.layout')

@section('title', 'Invoice Management System')

<!-- navbar -->

@section('navbar')

<nav class="navbar navbar-expand-lg navbar-dark bg-dark w-100">
    <a class="navbar-brand" href="{{ route('signUp') }}">
        <span class="d-md-inline-block d-none">Invoice Management System</span>
        <span class="d-md-none">IMS</span>
    </a>
    <a href="{{ route('userLogin') }}" class="nav-link ml-auto text-light"><i class="bi bi-person-square mr-1"></i>Login</a>
</nav>

@endsection


<!-- register form -->
@section('main-content')
<div class="row">
    <div class="col-md-6 offset-md-3 my-4">
        <div class="card-header bg-info p-1">
            <h3 class="text-md-right text-center text-light mr-4">Register Yourself</h3>
        </div>
        <div class="card-body shadow bg-light">
            <form method="post" action="{{ route('user.register') }}">
                @csrf
                <input type="text" class="form-control" name="name" id="name" placeholder="Name">
                <small class="ml-3 text-info">minimum 7 characters.</small>
                
                <!-- display error (if any) -->
                @if($errors->any())
                @foreach($errors->get('name') as $name_err)
                <small class="text-danger ml-3">{{ $name_err }}</small>
                @endforeach
                @endif
                <input type="email" class="form-control" name="email" id="email" placeholder="Email">
                @if($errors->any())
                @foreach($errors->get('email') as $email_err)
                <small class="text-danger ml-3">{{ $email_err }}</small>
                @endforeach
                @endif
                <br>
                <input type="password" class="form-control" name="password" id="password" placeholder="Password">
                <small class="ml-3 text-info">minimum 8 characters.</small>
                @if($errors->any())
                @foreach($errors->get('password') as $pass_err)
                <small class="text-danger ml-3">{{ $pass_err }}</small>
                @endforeach
                @endif
                <br>
                <input type="text" class="form-control" name="company" id="company" placeholder="Company">
                @if($errors->any())
                @foreach($errors->get('company') as $company_err)
                <small class="text-danger ml-3">{{ $company_err }}</small>
                @endforeach
                @endif
                <br>
                <input type="text" class="form-control" name="address_line_one" id="address-line-one" placeholder="Address Line 1">
                @if($errors->any())
                @foreach($errors->get('address_line_one') as $address_line_one_err)
                <small class="text-danger ml-3">{{ $address_line_one_err }}</small>
                @endforeach
                @endif
                <br>
                <input type="text" class="form-control" name="address_line_two" id="address-line-two" placeholder="Address Line 2">
                @if($errors->any())
                @foreach($errors->get('address_line_two') as $address_line_two_err)
                <small class="text-danger ml-3">{{ $address_line_two_err }}</small>
                @endforeach
                @endif
                <br>
                <input type="text" class="form-control" name="country" id="country" placeholder="Country">
                @if($errors->any())
                @foreach($errors->get('country') as $country_err)
                <small class="text-danger ml-3">{{ $country_err }}</small>
                @endforeach
                @endif
                <br>
                <select name="roleSelected" class="form-control">
                    <option value="client">Client</option>
                    <option value="developer">Developer</option>
                </select>
                <br>
                <button type="submit" class="btn btn-outline-primary btn-block" name="register">Create User</button>
                <div class="mt-4 d-flex text-center">
                    @if(session()->has('status') && session('status') == 'success')
                    <span class="alert alert-success messageAlert">Created Successfully!!</span>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@endsection