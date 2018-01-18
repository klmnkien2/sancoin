<table class="table table-bordered table-striped dataTable" role="grid" >
    <thead>
        <tr role="row">
            <th>
                @if(count($listUsers))
                    <div class="checkbox">
                        <input type="checkbox" id="pg-check-all">
                        <label for="checkbox1">
                        </label>
                    </div>
                @endif
            </th>
            <th >{{trans('admin.label.username')}}</th>
            <th >{{trans('admin.label.email')}}</th>
            <th >{{trans('admin.label.created_at')}}</th>
            <th >{{trans('admin.label.uploaded_verify')}}</th>
            <th >{{trans('admin.label.status')}}</th>
        </tr>
    </thead>
    <tbody>
        @if(count($listUsers))
            @foreach ($listUsers as $key => $user)
                <tr role="row" @if ($key % 2 == 1) class="odd" @endif>
                    <td>
                        <div class="checkbox">
                            <input type="checkbox" class="pg-checkbox" data-id="{{ $user->id }}">
                            <label for="checkbox1">
                            </label>
                        </div>
                    </td>
                    <td >{{ $user['username'] }}</td>
                    <td >{{ $user['email'] }}</td>
                    <td >{{ $user['created_at'] }}</td>
                    <td >{{ $user['username'] }}</td>
                    <td >
                        @if ($user['status'] == 0)
                            <span class="label label-danger">Not activated</span>
                        @endif
                        @if ($user['status'] == 1)
                                <span class="label label-warning">Not verified</span>
                        @endif
                        @if ($user['status'] == 2)
                                <span class="label label-success">Verified</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="6">
                    {{trans('admin.message.table_no_result')}}
                </td>
            </tr>
        @endif
    </tbody>
</table>