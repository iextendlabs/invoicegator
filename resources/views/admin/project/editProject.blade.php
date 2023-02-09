@extends('customLayout.layout')

@section('title', 'Edit Project')

<!-- navbar -->

@section('navbar')
<nav class="navbar navbar-expand-lg navbar-dark bg-dark w-100">
    <a href="{{ url()->previous() }}" class="nav-link text-light"><i class="bi bi-arrow-left-circle"></i></a>
    <a class="navbar-brand" href="{{ route('admin/create/project') }}">Admin | <small>Edit Project</small> </a>
    <a href="{{ route('adminlogout') }}" class="nav-link ml-auto text-light">Logout</a>

</nav>
@endsection


{{-- main content --}}

@section('main-content')
<div class="row my-2">
    @if (session('success'))
        <p class="mx-auto text-success display-5">{{ session('success') }}</p>        
    @endif

</div>
    <div class="row mt-2">
        <div class="col-md-10 offset-md-1">
            @foreach($project_data as $data)
            <form method="post" action="{{ route('project-updated') }}">  
                @csrf      
                <input type="hidden" name="project_id" value="{{ $data->id }}">

                <label for="p_name" class="form-label"><small>Project Name</small></label>
                <input type="text" name="p_name" id="p_name" class="form-control form-control-sm" value="{{ $data->project_name }}" name="project_name" placeholder="project name here">
                <small class="ml-2 text-info">min. 5 characters</small>
                @if($errors->any())
                @foreach($errors->get('p_name') as $err)
                <small class="text-danger ml-3">{{ $err }}</small>
                @endforeach
                @endif
                <br>

                <label for="project_rate" class="form-label"><small>Project Rate</small></label>
                <input type="text" class="form-control form-control-sm" name="project_rate" id="project_rate" placeholder="Enter Project Rate" value="{{ $data->project_rate }}">
                <small class="text-info">Integer / float only</small>
                @if($errors->any())
                @foreach($errors->get('project_rate') as $err)
                <small class="text-danger ml-3">{{ $err }}</small>
                @endforeach
                @endif
                <br>

                <label for="p_desc" class="form-label"><small>Description</small></label>
                <textarea name="project_description" placeholder="Enter description here" id="p_desc" cols="130" rows="6" class="form-control form-control-sm">{{ $data->project_desc }}</textarea>
                <small class="ml-2 text-info">min. 10 chars.</small>
                @if($errors->any())
                @foreach($errors->get('project_description') as $err)
                <small class="text-danger ml-3">{{ $err }}</small>
                @endforeach
                @endif
                <br>

                <label for="hour_rate" class="form-label"><small>Per Hour Rate</small></label>
                <input type="text" class="form-control form-control-sm" name="hour_rate" id="hour_rate" placeholder="Per Hour Rate" value="{{ $data->per_hour_rate }}">
                <small class="text-info">Integer / float only</small>
                @if($errors->any())
                @foreach($errors->get('hour_rate') as $err)
                <small class="text-danger ml-3">{{ $err }}</small>
                @endforeach
                @endif
                <br>

                <label for="status" class="form-label"><small>Projcet Status</small></label>
                <select name="project_status" id="p_status" class="form-control form-control-sm">
                    <option value="pending">Pending</option>
                    <option value="complete">Complete</option>
                </select>
                @if($errors->any())
                @foreach($errors->get('project_status') as $err)
                <small class="text-danger ml-3">{{ $err }}</small>
                @endforeach
                @endif
                <br>

                <button class="btn btn-block btn-sm btn-success" type="submit">Update Project</button>
            </form>
            @endforeach
        </div>
    </div>
@endsection