@extends('pb::layouts.main')

@section('content')
    <section class="section section-offers">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-12 col-md-8 col-md-offset-2">
                    <div class="clearfix box-base box-yellow list-of-buyers">
                        <div class="clearfix box-inner">
                            <div class="clearfix box-head">
                                <h3 class="box-title"><strong class="title-inner">List Of Buyers</strong></h3>
                            </div>
                            <div class="clearfix box-body">
                                @if (!empty($pagination['total']))
                                <div class="custom-scrollbar">
                                    <ul class="clearfix offer-list">
                                        @foreach ($listBuyer as $buyer)
                                        <li class="offer">
                                            <div class="clearfix offer-inner">
                                                <div class="offer-info">
                                                    <a class="clearfix offer-link" href="#">
                                                        <p class="offer-price"><strong class="price">{{number_format($buyer['amount'] , 0 , '.' , ',' )}}</strong> <strong class="currency-from">VND</strong>/<strong class="currency-to">BTC</strong> via <strong class="bank">Vietcombank</strong></p>
                                                        <p class="maximum">Maximum: <strong class="amount">{{ $buyer['coin_amount'] }}</strong> <strong class="currency-to">BTC</strong></p>
                                                    </a>
                                                </div>
                                                <div class="clearfix user-info">
                                                    <div class="user-photo"><a href="{{ route('pb.get_user', ['username' => $buyer->user['username']]) }}"><img src="{{url('assets/pb/images/icon-user-sample.png')}}" alt=""></a></div>
                                                    <div class="username"><a class="nickname" href="{{ route('pb.get_user', ['username' => $buyer->user['username']]) }}">{{ $buyer->user['username'] }}</a></div>
                                                    <div class="badge-group">
                                                        @if ($buyer->user['status'] == 2)
                                                            <span class="badge badge-success"><em class="fa fa-unlock-alt"></em> {{trans('messages.label.verified')}}</span>
                                                        @else
                                                            <span class="badge badge-warning"><em class="fa fa-lock"></em> {{trans('messages.label.unverified')}}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <a href="{{route('pb.order.detail', ['id' => $buyer['id']])}}" class="btn btn-flat-yellow"><span class="btn-inner">Sell</span></a>
                                            </div>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                                @else
                                <div class="text-center no-record">{{trans('messages.message.list_is_empty')}}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    @include('pb::mod.pagination', [
                        'url' => route('pb.order.all', ['type' => 'buy']),
                        'total' => $pagination['total'],
                        'page' => $pagination['page'],
                        'per' => $pagination['per'],
                        'condition' => $pagination['condition']
                    ])

                </div>
            </div>
        </div>
    </section>

@stop

@section('popup-content')
    @if(!Auth::check())
        @include('pb::mod.popup_login')
        @include('pb::mod.popup_register')
        @include('pb::mod.popup_reset_passwd')
    @endif
@stop
