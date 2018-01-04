@extends('pb::layouts.main')

@section('content')
    <section class="section">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-12 col-md-8 col-md-offset-2">
                    <div class="clearfix box-base box-green">
                        <div class="clearfix box-inner">
                            <div class="clearfix box-head">
                                <h3 class="box-title"><strong class="title-inner">{{ trans('messages.label.user_summary') }}</strong></h3>
                            </div>
                            <div class="clearfix box-body" style="padding: 15px;">
                                <div class="col-xs-6 col-md-6">
                                    <div class="box box-red">
                                        <p class="desc">{{trans('messages.label.createSell')}}</p>
                                        <p class="value">{{$createSell}}</p>
                                    </div>
                                </div>

                                <div class="col-xs-6 col-md-6">
                                    <div class="box box-green">
                                        <p class="desc">{{trans('messages.label.createBuy')}}</p>
                                        <p class="value">{{$createBuy}}</p>
                                    </div>
                                </div>

                                <div class="col-xs-6 col-md-6">
                                    <div class="box box-red">
                                        <p class="desc">{{trans('messages.label.doneBuyVND')}}</p>
                                        <p class="value">{{$doneBuyVND}}</p>
                                    </div>
                                </div>

                                <div class="col-xs-6 col-md-6">
                                    <div class="box box-green">
                                        <p class="desc">{{trans('messages.label.doneSellVND')}}</p>
                                        <p class="value">{{$doneSellVND}}</p>
                                    </div>
                                </div>

                                <div class="col-xs-6 col-md-6">
                                    <div class="box box-red">
                                        <p class="desc">{{trans('messages.label.doneSellBTC')}}</p>
                                        <p class="value">{{$doneSellBTC}}</p>
                                    </div>
                                </div>

                                <div class="col-xs-6 col-md-6">
                                    <div class="box box-green">
                                        <p class="desc">{{trans('messages.label.doneBuyBTC')}}</p>
                                        <p class="value">{{$doneBuyBTC}}</p>
                                    </div>
                                </div>

                                <div class="col-xs-6 col-md-6">
                                    <div class="box box-red">
                                        <p class="desc">{{trans('messages.label.doneSellETH')}}</p>
                                        <p class="value">{{$doneSellETH}}</p>
                                    </div>
                                </div>

                                <div class="col-xs-6 col-md-6">
                                    <div class="box box-green">
                                        <p class="desc">{{trans('messages.label.doneBuyETH')}}</p>
                                        <p class="value">{{$doneBuyETH}}</p>
                                    </div>
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
