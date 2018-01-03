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
                                    <form enctype="multipart/form-data" method="POST" type="post" action="">
                                        <div class="clearfix form-inner">
                                            <div class="alert-message">{!! ($messages?:'') !!}</div>
                                            <div
                                                class="clearix offer-form-top">
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
                                                        <div class="input-group-addon">{{trans('messages.label.vnd')}}/{{trans('messages.label.' . $order['coin_type'])}}</div>
                                                        <div class="form-control"></div>
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
