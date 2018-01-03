<div class="clearfix table-wrap">
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Date/Time</th>
            <th>Type</th>
            <th class="number">Qty. Coin</th>
            <th class="number">Qty. VND</th>
            <th>Tx Hash</th>
            <th>Status</th>
            <th align="center">Cancel</th>
        </tr>
        </thead>
        <tbody>
        @if (!empty($myOrders))
            @foreach ($myOrders as $order)
            <tr>
                <td>{{ $order['created_at'] }}</td>
                <td>{{$order['order_type']}}/{{$order['coin_type']}}</td>
                <td class="number">
                    @if ($order['coin_type'] == 'btc')
                        {{ number_format($order['coin_amount'], 8) }}
                    @elseif ($order['coin_type'] == 'eth')
                        {{ number_format($order['coin_amount'], 18) }}
                    @endif
                </td>
                <td class="number">{{number_format($order['amount'] , 0 , '.' , ',' )}}</td>
                <td>
                    @if (!empty($order['hash']))
                        <span class="address-tag">{{$order['hash']}}</span>
                    @else
                        {{trans('messages.label.status_none')}}
                    @endif
                </td>
                <td>
                    @if ($order['status'] == 'waiting')
                        <span class="badge badge-default">{{trans('messages.label.status_waiting')}}</span>
                    @elseif ($order['status'] == 'pending')
                        <span class="badge badge-warning">{{trans('messages.label.status_pending')}}</span>
                    @elseif ($order['status'] == 'done')
                        <span class="badge badge-success">{{trans('messages.label.status_done')}}</span>
                    @endif
                </td>
                <td align="center">
                    <a class="pg-delete-order" data-url="{{route('pb.order.cancel', ['id'=>$order['id']])}}" href="#">
                        <i class="del-link fa fa-minus-square" aria-hidden="true"></i>
                    </a>
                </td>
            </tr>
            @endforeach
        @else
            <tr><td colspan="7" align="center">{{trans('messages.message.list_is_empty')}}</td></tr>
        @endif
        </tbody>
    </table>

    @include('pb::mod.pagination', [
        'total' => $pagination['total'],
        'page' => $pagination['page'],
        'per' => $pagination['per'],
        'condition' => $pagination['condition']
    ])
</div>