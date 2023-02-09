<div class="row justify-content-center">
    <div class="col-md-9 col-12">
        <table class="table table-danger table-bordered">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Assign to</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($task as $index => $task )
                <tr>
                    <td>{{ $task->task_title }}</td>
                    <td>{{ $task->task_desc }}</td>
                    <td >{{ $task->user->name }}</td>
                    <td class="text-capitalize">
                        @if ($task->task_status == 'pending')                                
                        <span class="text-danger">{{ $task->task_status }}</span>
                        @endif

                        @if ($task->task_status == 'completed')                                
                        <span class="text-sucess">{{ $task->task_status }}</span>
                        @endif
                    </td>
                   
                    @endforeach
                </tr>
            </tbody>                
        </table>
    </div>
</div>

<div class="row my-3">
    <div class="col-md-4 col-12 offset-md-6">
        {{-- <div class="card bg-primary text-light">
            <div class="card-body"> --}}
                <div><b class="d-inline-block">Time Spend :</b><span style="text-decoration: underline;" class="float-right">&nbsp;{{ Request::get('time') }} hrs</span> </div><hr>
                <div><b class="d-inline-block">Project Rate :</b><span style="text-decoration: underline;" class="float-right">&nbsp;${{ $project->project_rate }}</span> </div><hr>
                <div><b class="d-inline-block">Date Created :</b><span style="text-decoration: underline;" class="float-right">&nbsp;{{ $project->created_at->format('d-M-y') }}</span></div><hr>
                <div><b class="d-inline-block">Created Time :</b><span style="text-decoration: underline;" class="float-right">&nbsp;{{ $project->created_at->format('h:i a') }}</span></div>
            {{-- </div>
        </div> --}}
    </div>
</div>