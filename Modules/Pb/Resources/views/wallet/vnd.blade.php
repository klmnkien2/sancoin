@extends('pb::layouts.main')

@section('content')
    <section class="section">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-12 col-md-8 col-md-offset-2">
                    <div class="clearfix box-base box-yellow">
                        <div class="clearfix box-inner">
                            <div class="clearfix box-head">
                                <h3 class="box-title"><strong class="title-inner">{{ trans('messages.label.vnd_wallet') }}</strong></h3>
                            </div>
                            <div class="clearfix box-body" style="padding: 15px;">
                                <div class="offer-form">
                                    <form method="POST" type="post" action="{{route('pb.updateProfile')}}">
                                        <div class="clearfix form-inner">
                                            <div class="clearix offer-form-top">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <div class="input-group-addon">{{trans('messages.label.available')}} VND</div>
                                                        <div class="form-control">{{ $wallet['amount'] }}</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="input-group">
                                                    <div class="input-group-addon">{{trans('messages.label.fullname')}}</div>
                                                    <input type="text" class="form-control" placeholder="...">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <div class="input-group-addon">{{trans('messages.label.account_number')}}</div>
                                                    <input type="text" class="form-control" placeholder="...">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <div class="input-group-addon">{{trans('messages.label.bank_branch')}}</div>
                                                    <input type="text" class="form-control" placeholder="...">
                                                </div>
                                            </div>

                                            <div class="button-group">
                                                <button type="submit" class="btn btn-flat-green"><span class="btn-inner">{{trans('messages.label.buttonUpdate')}}</span></button>
                                            </div>

                                            <div class="service-fee-text">{{trans('messages.message.vnd_wallet_update_notice')}}</div>

                                            <div class="hr-text"><span>{{trans('messages.label.withdraw')}}</span><hr></div>

                                            <div class="form-group">
                                                <label class="sr-only">{{trans('messages.label.amount')}}</label>
                                                <div class="input-group">
                                                    <div class="input-group-addon">{{trans('messages.label.amount')}}</div>
                                                    <input type="text" class="form-control" placeholder="VND">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div>
                                                    <input id="pg-withdraw-all" type="checkbox" class="form-control" style="width: auto; float: left; line-height: 40px;">
                                                    <label style="margin-left:10px; line-height: 40px;">{{trans('messages.label.withdraw_all')}}</label>
                                                </div>
                                            </div>

                                            <div class="button-group">
                                                <button type="submit" class="btn btn-flat-green"><span class="btn-inner">{{trans('messages.label.buttonWithdraw')}}</span></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-md-12" style="margin-top: 20px;">
                    <div class="clearfix box-base box-blue">
                        <div class="clearfix box-inner">
                            <div class="clearfix box-head">
                                <h3 class="box-title"><strong class="title-inner">{{trans('messages.label.trans_history')}}</strong></h3>
                            </div>
                            <div class="clearfix box-body">
                                <div class="custom-scrollbar">
                                    <div class="clearfix table-wrap">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th class="change"></th>
                                                    <th>{{trans('messages.label.txhash')}}</th>
                                                    <th>{{trans('messages.label.date_time')}}</th>
                                                    <th>{{trans('messages.label.from')}}</th>
                                                    <th>{{trans('messages.label.to')}}</th>
                                                    <th class="number">{{trans('messages.label.value')}}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($transactionHistory as $transaction)
                                                <tr>
                                                    @if ($transaction['from'] == $walletAddress)
                                                        <td class="change"><span class="fa fa-arrow-circle-o-up"></span></td>
                                                    @else
                                                        <td class="change"><span class="fa fa-arrow-circle-o-down"></span></td>
                                                    @endif
                                                    <td><span class="address-tag">{{$transaction['hash']}}</span></td>
                                                    <td>{{ Carbon\Carbon::createFromTimestamp($transaction['timeStamp'])->diffForHumans() }}</td>
                                                    <td><span class="address-tag">{{$transaction['from']}}</span></td>
                                                    <td><span class="address-tag">{{$transaction['to']}}</span></td>
                                                    <td class="number">{{ number_format(floatval($transaction['value'])/1000000000000000000, 18) }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
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
