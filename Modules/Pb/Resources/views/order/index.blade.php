@extends('pb::layouts.main')

@section('content')
    <section class="section section-orders">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-12 col-md-8 col-md-offset-2">
                    <div class="clearfix box-base box-green box-create-order">
                        <div class="clearfix box-inner">
                            <div class="clearfix box-head">
                                <h3 class="box-title"><strong class="title-inner">Create Order <span>(VND/BTC)</span></strong></h3>
                                <ul class="nav nav-tabs" role="tablist">
                                    <li role="presentation" @if($tabActive=='buy') class="active" @endif><a href="#buy-order" aria-controls="Buy Order" role="tab" data-toggle="tab">Buy Order</a></li>
                                    <li role="presentation" @if($tabActive=='sell') class="active" @endif><a href="#sell-order" aria-controls="Sell Order" role="tab" data-toggle="tab">Sell Order</a></li>
                                    <li role="presentation" @if($tabActive=='myorder') class="active" @endif><a href="#my-orders" aria-controls="My Orders" role="tab" data-toggle="tab">My Orders</a></li>
                                </ul>
                            </div>
                            <div class="clearfix box-body">
                                <div class="tab-content">
                                    <div class="order-message">{!! ($messages?:'') !!}</div>
                                    <div role="tabpanel" class="tab-pane @if($tabActive=='buy') active @endif>" id="buy-order">
                                        @include('pb::order.buy')
                                    </div>
                                    <div role="tabpanel" class="tab-pane @if($tabActive=='sell') active @endif" id="sell-order">
                                        @include('pb::order.sell')
                                    </div>
                                    <div role="tabpanel" class="tab-pane @if($tabActive=='myorder') active @endif" id="my-orders">
                                        @include('pb::order.myorder')
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
<script src="{{url_sync('assets/pb/js/order.js')}}"></script>
@endsection
