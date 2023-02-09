@extends('customLayout.layout')

@section('title', 'Client Area')

<!-- navbar -->
@section('navbar')

<nav class="navbar navbar-expand-lg navbar-dark bg-success w-100">
    <a class="navbar-brand" href="{{ route('client.dashboard') }}">
        <span class="d-md-inline-block d-none"><b class="text-capitalize">Client</b> | <small>Tasks</small></span>
        <span class="d-md-none">Client | <small>Tasks</small></span>
    </a>
    <div class="ml-auto d-flex">
        <a href="{{ route('client.change.password') }}" class="nav-link text-light">Change Password</a>
        <a href="{{ route('client.logout') }}" class="nav-link text-light">Logout</a>
    </div>
        
</nav>
@endsection

<!-- Page => main content -->
@section('main-content')


<div class="row my-2">
    <h5 class="text-sm mx-auto mb-3 text-danger">Tasks List</h5>
</div>

<div class="row my-2">
    @if(session('success'))
        <h6 class="text-success"><small class="mx-auto">{{ session('success') }}</small></h6>
    @endif 
    {{-- dispaying projects --}}
    <div class="col-md-10 offset-md-1 col-12">
        <table class="table table-responsive table-hover" cellspacing="2">
            <thead>
                <tr>
                    <th>Task Title</th>
                    <th>Description</th>
                    <th>Date</th>
                    <th>Developer</th>
                    <th>Paid</th>
                    <th>UnPaid</th>
                    <th>Invoices</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tasks as $task)
                    <tr>
                        <td>{{ $task->task_title }}</td>
                        <td>{{ $task->task_desc }}</td>
                        <td>{{ $task->date }}</td>
                        <td>{{ $task->user->name }}</td>
                        <td>{{ $task->paidLogs }}</td>
                        <td>{{ $task->unPaidLogs }}</td>
                        @if($task->invoice_id)
                        <td class="text-center"><a href="{{ route('check.invoice', ['invoice_id' => $task->invoice_id]) }}"><i class="bi bi-eye-fill text-warning"></i></a></td>
                            @else 
                                <td>NULL</td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
{{-- project Details --}}
<p class="text-right mr-5">Summary</p>
<div class="row justify-content-end">
    <div class="col-3">
        <table class="table table-responsive table-bordered table-light shadow">
            <thead>
                <tr class="bg-primary text-light">
                    <th>Paid</th>
                    <th>UnPaid</th>
                    <th>Total Hours</th>
                </tr>
            </thead>
            <tbody>
               <tr>
                   <td>{{ $paidHours }} hrs</td>
                   <td>{{ $unPaidHours }} hrs</td>
                   <td>{{ $totalHours }} hrs</td>
               </tr>
            </tbody>
        </table>
    </div>
</div>



@endsection
