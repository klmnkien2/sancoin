<table class="table table-bordered table-striped dataTable" role="grid" >
    <thead>
        <tr role="row">
            <th>
                @if(count($listData))
                    <div class="checkbox">
                        <input type="checkbox" id="pg-check-all">
                        <label for="checkbox1">
                        </label>
                    </div>
                @endif
            </th>
            <th >{{trans('admin.label.from_user')}}</th>
            <th >{{trans('admin.label.from_account')}}</th>
            <th >{{trans('admin.label.to_user')}}</th>
            <th >{{trans('admin.label.to_account')}}</th>
            <th >{{trans('admin.label.amount')}}</th>
            <th >{{trans('admin.label.type')}}</th>
            <th >{{trans('admin.label.created_at')}}</th>
            <th >{{trans('admin.label.status')}}</th>
        </tr>
    </thead>
    <tbody>
        @if(count($listData))
            @foreach ($listData as $key => $data)
                <tr role="row" @if ($key % 2 == 1) class="odd" @endif>
                    <td>
                        <div class="checkbox">
                            <input type="checkbox" class="pg-checkbox" data-id="{{ $data->id }}">
                            <label for="checkbox1">
                            </label>
                        </div>
                    </td>
                    <td >{{ $data['from_user']['username'] }}</td>
                    <td >{{ $data['from_account'] }}</td>
                    <td >{{ $data['to_user']['username'] }}</td>
                    <td >{{ $data['to_account'] }}</td>
                    <td >{{ $data['amount'] }}</td>
                    <td >
                        @if ($data['type'] == 'order')
                            {{ $data['order']['coin_type'] }}
                        @else
                            {{ $data['type'] }}
                        @endif
                    </td>
                    <td >{{ $data['created_at'] }}</td>
                    <td >
                        @if ($data['status'] == 'pending')
                            <span class="label label-warning">Pending</span>
                        @endif
                        @if ($data['status'] == 'done')
                                <span class="label label-success">Done</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="9">
                    {{trans('admin.message.table_no_result')}}
                </td>
            </tr>
        @endif
    </tbody>
</table>