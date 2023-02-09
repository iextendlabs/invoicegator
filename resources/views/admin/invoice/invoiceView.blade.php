@extends('customLayout.layout')

@section('title', 'Invoice')

<!-- navbar -->

@section('navbar')
<nav class="navbar navbar-expand-lg navbar-dark bg-dark w-100">
    <a href="{{ route('view.project', ['id' => $projectId]) }}" class="nav-link text-light"><i class="bi bi-arrow-left-circle"></i></a>
    <a class="navbar-brand" href="#">Admin | <small>Generate Invoice</small></a>
    <a href="{{ route('adminlogout') }}" class="nav-link ml-auto text-light">Logout</a>
</nav>
@endsection

@section('main-content')

{{-- fixed rate Invoice --}}
<div class="row d-flex justify-content-end">
  <button class="btn btn-default mt-1" id="dateWiseInvoice">Date Wise Invoice</button>
  <button class="btn btn-default mt-1" id="fixedAmount">Fixed Amount</button>
  <button class="btn btn-default mt-1" id="fixedHours">Fixed Hours</button>
</div>

{{-- date wise invoice form--}}
<div class="row mt-3">
    <div class="col-md-7 offset-md-2" id="dateForm">
        <form method="POST">            
            <div class="form-row">
              <div class="col-md-5 mb-3">
                <label for="invoiceTitle" class="form-label ml-1"><small>Invoice Title</small></label>
                <input type="text" value="Datewise Invoice" class="form-control form-control-sm mb-3" name="invoiceTitle" id="invoiceTitle" placeholder="enter invoice title">
              </div>
                
              <div class="col-md-5 mb-3">
                <input type="hidden" class="form-control form-control-sm mb-3" name="projectId" id="projectId" value="{{ $projectId }}"  readonly>
              </div>
            </div>      
            
            <div class="form-row">
              <div class="col-md-5 mb-3">
                <label for="firstDate" class="form-label ml-1"><small>Enter First Date</small></label>
                <input type="text" name="firstDate" onblur="(this.type='text')" onfocus="(this.type='date')" class="form-control form-control-sm" id="firstDate" placeholder="starting date (DD/MM/YYYY)" required>
              </div>
                
              <div class="col-md-5 mb-3">
                <label for="lastDate" class="form-label ml-1"><small>Enter Last Date</small></label>
                <input type="text" name="lastDate" onblur="(this.type='text')" onfocus="(this.type='date')" class="form-control form-control-sm" id="lastDate" placeholder="ending date (DD/MM/YYYY)" required>
              </div>
            </div>              
              <button type="button" class="btn btn-sm btn-primary" id="dateWiseInvoiceEntry">New Invoice Entry</button>
        </form>
        <p id="message" class="text-danger"></p>
        {{-- FLASH MESSAGE --}}
        <div>
            @if (session('success'))
               <p class="text-danger text-center">{{ session('success') }}</p>
            @endif
        </div>
    </div>

    {{-- fixed amount invoice form --}}

    <div class="col-md-4 offset-md-2" id="fixedAmountForm">
      <!--<form method="post" action="#">-->
       <form method="post" action="{{ route('fixed.amount.invoice') }}"> 
          @csrf
          <label for="amount" class="form-label"><small>Enter Fixed Amount</small></label>
          <input type="text" value="Fixed Amount Invoice" name="amount" id="amount" class="form-control form-control-sm mb-2" placeholder="enter a fixed amount">

          <label for="invoiceTitle" class="form-label"><small>Enter Invoice Title</small></label>
      <input type="text" name="invoiceTitle" id="invoiceTitle" class="form-control form-control-sm mb-2" placeholder="enter invoice title">
          <input type="hidden" class="form-control form-control-sm mb-3" name="projectId" id="projectId" value="{{ $projectId }}"  readonly>
          <div class="form-row">           
            <button type="submit" class="btn btn-sm btn-primary ml-2">Create Invoice</button>
      </form>
      @if($errors->any())
        @foreach($errors->get('amount') as $name_err)
        <small class="text-danger ml-3">{{ $name_err }}</small>
        @endforeach
      @endif
      {{-- FLASH MESSAGE --}}
      <div>
          @if (session('success'))
             <p class="text-danger text-center">{{ session('success') }}</p>
          @endif
      </div>
  </div>

  {{-- fixed Hour invoice --}}
</div>
</div>

{{-- fixed hour invoice form--}}
<div class="col-md-4 offset-md-2" id="fixedHoursForm">
  <form method="post" action="{{ route('fixed.hour.invoice') }}">
      @csrf
      <label for="hours" class="form-label"><small>Enter Hours</small></label>
      <input type="text" name="hours" id="hours" class="form-control form-control-sm mb-2" placeholder="enter a fixed hours">

      <label for="invoiceTitle" class="form-label"><small>Enter Invoice Title</small></label>
      <input type="text" name="invoiceTitle" id="invoiceTitle" value="Fixed Hour Invoice" class="form-control form-control-sm mb-2" placeholder="enter invoice title">
     
      <input type="hidden" class="form-control form-control-sm" name="projectId" id="projectId" value="{{ $projectId }}"  readonly>
      {{-- <small class="text-info">Enter time like 2.0 | 33.41 | 4.10</small> --}}
      <div class="form-row">           
        <button type="submit" class="btn btn-sm btn-primary ml-2">Create Invoice</button>
  </form>
  @if($errors->any())
    @foreach($errors->get('hours') as $name_err)
    <small class="text-danger ml-3">{{ $name_err }}</small>
    @endforeach
  @endif
</div>

{{-- row 2 fixed amount invoice --}}
  @if (isset($logs))
  <h5 class="text-center my-2 mb-3 text-success">{{ $projectName }}</h5>
  <div class="row my-3">
    <div class="col-md-10 offset-md-1" style="overflow-y: auto; height: 500px;">
      <table class="table table-hover table-bordered table-dark">
        <thead class="bg-danger">
          <tr>
            <th>Task ID</th>
            <th>Log ID</th>
            <th>Log Status </th>
            <th>Creation Date</th>
            <th>Time Spend</th>
            <th>Log Cost</th>
          </tr>
        </thead>
  
        <tbody>
          @foreach ($logs as $index => $data)
              <tr>
                <td>{{ $data->task_id }}</td>
                <td>{{ $data->id }}</td>
                <td>
                  @if ($data->log_status == 'pending')
                      <span class="badge badge-warning">{{ $data->log_status }}</span>
                  @endif

                  @if ($data->log_status == 'complete')
                  <span class="badge badge-success">{{ $data->log_status }}</span>
                  @endif
                </td>
                <td>{{ $data->log_creation_date }}</td>
                
              </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  @endif
</div>


{{-- row 3 fixedAmountInvoice View --}}

@if (isset($fixedAmountInvoice))
  <h4 class="my-3 text-center">Fixed Amount</h4>
    <div class="row my-3 justify-content-center">
      <div class="col-md-10">
        <table class="table-hover table table-bordered">
          <thead class="thead-dark">
            <tr>
              <th><small>Task Title</small></th>
              <th><small>Created At</small></th>
              <th><small>Task Desc</small></th>
              <th><small>Time Spend</small></th>
            </tr>
          </thead>
          <tbody>
            @foreach ($task as $index => $item)
            <tr>
              <td><small>{{ $item->task_title }}<small></td>
              <td><small>{{ $item->created_at }}<small></td>
              <td><small>{{ $item->task_desc }}<small></td>
              <td><small>{{ $taskHours[$index] }} hrs.<small></td>
            </tr>            
            @endforeach
          </tbody>
        </table>
        <p class="float-right">Total Cost :- <span class="badge-success px-1">${{ $amount }}</span></p>
      </div>
    </div>
@endif



@endsection
