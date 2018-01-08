@extends('pb::layouts.main')

@section('content')
    <section class="section">
		<div class="container-fluid">
			<div class="row">
				<div class="col-xs-12 col-md-8 col-md-offset-2">
					<div class="clearfix box-base box-yellow">
						<div class="clearfix box-inner">
							<div class="clearfix box-head">
								<h3 class="box-title"><strong class="title-inner">{{ trans('messages.label.btc_wallet') }}</strong></h3>
							</div>
							<div class="clearfix box-body" style="padding: 15px;">
								<div class="offer-form">
									<form method="POST" type="post" action="{{route('pb.wallet.withdraw')}}">
										<div class="clearfix form-inner">
											<div>{!! ($messages?:'') !!}</div>
											<div class="clearix offer-form-top">
												<div class="form-group">
													<div class="input-group">
														<div class="input-group-addon">{{trans('messages.label.available_btc')}}</div>
														<div class="form-control">{{ number_format($avaiableAmount/100000000, 8) }}</div>
													</div>
												</div>
												<div class="form-group">
                                                    <div class="input-group">
                                                        <div class="input-group-addon">{{trans('messages.label.in_order_money')}}</div>
                                                        <div class="form-control">{{ $inOrderCoin }}</div>
                                                    </div>
                                                </div>
												<div class="form-group">
													<div class="input-group">
														<div class="input-group-addon">{{trans('messages.label.your_btc_addr')}}</div>
														<div class="form-control">{{$walletAddress?:''}}</div>
													</div>
												</div>
											</div>

											<div class="hr-text"><span>{{trans('messages.label.withdraw')}}</span><hr></div>

											<div class="form-group">
												<label class="sr-only">{{trans('messages.label.amount')}}</label>
												<div class="input-group">
													<div class="input-group-addon">{{trans('messages.label.amount')}}</div>
													<input type="text" name="amount" class="form-control" placeholder="BTC">
												</div>
											</div>

											<div class="form-group">
												<label class="sr-only">{{trans('messages.label.to')}}</label>
												<div class="input-group">
													<div class="input-group-addon">{{trans('messages.label.to')}}</div>
													<input type="text" name="to_address" class="form-control" placeholder="Address">
												</div>
											</div>

											<!--<div class="form-group">
												<div>
    												<input id="pg-withdraw-all" type="checkbox" class="form-control" style="width: auto; float: left; line-height: 40px;">
    												<label style="margin-left:10px; line-height: 40px;">{{trans('messages.label.withdraw_all')}}</label>
												</div>
											</div>-->

											<div class="button-group">
												{{ csrf_field() }}
                                                <input type="hidden" name="coin_type" value="btc">
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
    												<td><a href="#{{$transaction['hash']}}" class="address-tag" title="{{$transaction['hash']}}">{{$transaction['hash']}}</a></td>
    												<td>{{ $transaction['received']->diffForHumans() }}</td>
    												<td><a href="#{{$transaction['from']}}" class="address-tag" title="{{$transaction['from']}}">{{$transaction['from']}}</a></td>
    												<td><a href="#{{$transaction['to']}}" class="address-tag" title="{{$transaction['to']}}">{{$transaction['to']}}</a></td>
    												<td class="number">{{ number_format(floatval($transaction['value'])/100000000, 8) }}</td>
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
