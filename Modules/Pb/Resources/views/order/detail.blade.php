@extends('pb::layouts.main')

@section('content')
    <section class="section">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-12 col-md-8 col-md-offset-2">
                    <div class="clearfix box-base box-green">
                        <div class="clearfix box-inner">
                            <div class="clearfix box-head">
                                <h3 class="box-title"><strong class="title-inner">{{ trans('messages.label.order_detail') }}</strong></h3>
                            </div>
                            <div class="clearfix box-body" style="padding: 15px;">
                                <div class="offer-form">
                                    <form method="POST" type="post" action="{{ route('pb.order.accept', $order['id']) }}">
                                        <div class="clearfix form-inner">
                                            <div class="alert-message">{!! ($messages?:'') !!}</div>
                                            @if (!empty($order))
                                            <div class="clearix offer-form-top">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <div class="input-group-addon">{{trans('messages.label.order_type')}}</div>
                                                        <div class="form-control">{{trans('messages.label.' . $order['order_type'])}}</div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <div class="input-group-addon">{{trans('messages.label.coin_amount')}}</div>
                                                        <div class="form-control">{{$order['coin_amount']}} {{trans('messages.label.' . $order['coin_type'])}}</div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <div class="input-group-addon">{{trans('messages.label.creator')}}</div>
                                                        <div class="form-control"><a class="nickname" href="{{ route('pb.get_user', ['username' => $order->user['username']]) }}">{{ $order->user['username'] }}</a></div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <div class="input-group-addon">1 {{trans('messages.label.' . $order['coin_type'])}}</div>
                                                        <div class="form-control">{{number_format($order['coin_to_vnd'] , 0 , '.' , ',' )}} VND</div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <div class="input-group-addon">{{trans('messages.label.total_vnd')}}</div>
                                                        <div class="form-control">{{number_format($order['amount'] , 0 , '.' , ',' )}}</div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <div class="input-group-addon">{{trans('messages.label.status')}}</div>
                                                        <div class="form-control">
                                                            @if ($order['status'] == 'waiting')
                                                                <span class="badge badge-default">{{trans('messages.label.status_waiting')}}</span>
                                                            @elseif ($order['status'] == 'pending')
                                                                <span class="badge badge-warning">{{trans('messages.label.status_pending')}}</span>
                                                            @elseif ($order['status'] == 'done')
                                                                <span class="badge badge-success">{{trans('messages.label.status_done')}}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div style="color: red;margin-bottom: 10px;" class="service-fee-text">{{trans('messages.message.confirm_order_notice')}}</div>
                                            <div class="button-group">
                                                {{csrf_field()}}
                                                <button id="pg-profile-save" type="submit" class="btn btn-flat-green"><span class="btn-inner">{{trans('messages.label.buttonConfirm')}}</span></button>
                                            </div>
                                            @endif
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('popup-content')
    @if(!Auth::check())
        @include('pb::mod.popup_login')
        @include('pb::mod.popup_register')
        @include('pb::mod.popup_reset_passwd')
    @endif
@endsection

@section('extend-js')
<script src="{{url_sync('assets/pb/js/profile.js')}}"></script>
@endsection
