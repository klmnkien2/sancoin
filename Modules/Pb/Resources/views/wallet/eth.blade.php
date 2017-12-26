@extends('pb::layouts.main')

@section('content')
    <section class="section">
		<div class="container-fluid">
			<div class="row">
				<div class="col-xs-12 col-md-8 col-md-offset-2">
					<div class="clearfix box-base box-yellow">
						<div class="clearfix box-inner">
							<div class="clearfix box-head">
								<h3 class="box-title"><strong class="title-inner">{{ trans('messages.label.eth_wallet') }}</strong></h3>
							</div>
							<div class="clearfix box-body" style="padding: 15px;">
								<div class="offer-form">
									<form method="POST" type="post" action="{{route('pb.updateProfile')}}">
										<div class="clearfix form-inner">
											<div class="clearix offer-form-top">
												<div class="form-group">
													<div class="input-group">
														<div class="input-group-addon">{{trans('messages.label.available_eth')}}</div>
														<div class="form-control">0.00002230 ETH</div>
													</div>
												</div>
												<div class="form-group">
													<div class="input-group">
														<div class="input-group-addon">{{trans('messages.label.your_eth_addr')}}</div>
														<div class="form-control">0x00000000000</div>
													</div>
												</div>
											</div>

											<div class="hr-text"><span>{{trans('messages.label.withdraw')}}</span><hr></div>

											<div class="form-group">
												<label class="sr-only">{{trans('messages.label.amount')}}</label>
												<div class="input-group">
													<div class="input-group-addon">{{trans('messages.label.amount')}}</div>
													<input type="text" class="form-control" placeholder="ETH">
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
											<tr>
												<td class="change"><span class="fa fa-arrow-circle-o-up"></span></td>
												<td><span class="address-tag">0x17cb4341ef4d9132f9c86b335f6dd6010f6aea9a</span></td>
												<td>17 Seconds Ago</td>
												<td><span class="address-tag">0x17cb4341ef4d9132f9c86b335f6dd6010f6aea9a</span></td>
												<td><span class="address-tag">0x17cb4341ef4d9132f9c86b335f6dd6010f6aea9a</span></td>
												<td class="number">0.02526615</td>
											</tr>
											<tr>
												<td class="change"><span class="fa fa-arrow-circle-o-down"></span></td>
												<td><span class="address-tag">0x17cb4341ef4d9132f9c86b335f6dd6010f6aea9a</span></td>
												<td>17 Seconds Ago</td>
												<td><span class="address-tag">0x17cb4341ef4d9132f9c86b335f6dd6010f6aea9a</span></td>
												<td><span class="address-tag">0x17cb4341ef4d9132f9c86b335f6dd6010f6aea9a</span></td>
												<td class="number">0.02526615</td>
											</tr>
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
