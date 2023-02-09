@extends('customLayout.layout')

@section('title', 'Client Area')

<!-- navbar -->
@section('navbar')

<nav class="navbar navbar-expand-lg navbar-dark bg-success w-100">
    <a class="navbar-brand" href="{{ route('client.dashboard') }}">
        <span class="d-md-inline-block d-none"><b class="text-capitalize">Client</b> | <small>Invoice</small></span>
        <span class="d-md-none">Client | <small>Invoice</small></span>
    </a>
    <div class="ml-auto d-flex">
        {{-- <a href="{{ route('client.change.password') }}" class="nav-link text-light">Change Password</a> --}}
        <a href="{{ route('client.logout') }}" class="nav-link text-light">Logout</a>
    </div>
        
</nav>
@endsection

<!-- Page => main content -->
@section('main-content')

<div class="row mt-5">
    <div class="col-md-8 offset-md-3">
        {{-- row 1 invoice Header --}}
        <div class="row">
            <div class="col-3 d-flex flex-column offset-3">
                <h4>iExtend Labs</h4>
                <small class="d-block">Gulberg-3 Lahore, Pakistan</small>
                <small class="d-block">Phone :- 0300 - 000000-0</small>
            </div>
            <div class="col-3 d-flex flex-column">
                <small class="d-block">Invoice ID :- {{ $invoice->id }}</small>
                <small class="d-block">Date :- 2022-04-25</small>
            </div>
        </div>
        {{-- row 2 containing invoice data --}}
        <div class="row">
            <div class="col-12 ">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Project Name</th>
                            <th>Date Created</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Invoice Rate</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $invoice->project_name }}</td>
                            <td>{{ $invoice->date_created }}</td>
                            <td>{{ $invoice->start_date }}</td>
                            <td>{{ $invoice->end_date }}</td>
                            <td class="bg-success text-light text-center">${{ $invoice->invoice_rate }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
</div>


@endsection
