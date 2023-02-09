@extends('customLayout.layout')

@section('title', 'Invoice Fixed Hours')

<!-- navbar -->

@section('navbar')
<nav class="navbar navbar-expand-lg navbar-dark bg-dark w-100">
    <a href="{{ route('view.project', ['id' => $projectId]) }}" class="nav-link text-light"><i class="bi bi-arrow-left-circle"></i></a>
    <a class="navbar-brand" href="#">Admin | <small>Generate Invoice</small></a>
    <a href="{{ route('adminlogout') }}" class="nav-link ml-auto text-light">Logout</a>
</nav>
@endsection

@section('main-content')

<div class="row my-3">
    <div class="col-md-10">
      <table class="table-hover table table-bordered">
        <thead class="thead-dark">
          <tr>
            <th><small>Task Title</small></th>
            <th><small>Created At</small></th>
            <th><small>Task Desc</small></th>
          </tr>
        </thead>
        <tbody>
          @foreach ($task as $index => $item)
          <tr>
            <td><small>{{ $item->task_title }}<small></td>
            <td><small>{{ $item->created_at }}<small></td>
            <td><small>{{ $item->task_desc }}<small></td>
          </tr>            
          @endforeach
        </tbody>
      </table>
    </div>
</div>

<div class="row">
    <div class="col-12 text-center">
        <p class="text-light mr-3 bg-info"><b>${{ $hourInvoice }}</b></p>
    </div>
</div>

@endsection
