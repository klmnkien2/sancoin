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
									<li role="presentation" class="active"><a href="#buy-order" aria-controls="Buy Order" role="tab" data-toggle="tab">Buy Order</a></li>
									<li role="presentation"><a href="#sell-order" aria-controls="Sell Order" role="tab" data-toggle="tab">Sell Order</a></li>
									<li role="presentation"><a href="#my-orders" aria-controls="My Orders" role="tab" data-toggle="tab">My Orders</a></li>
								</ul>
							</div>
							<div class="clearfix box-body">
								<div class="tab-content">
									<div role="tabpanel" class="tab-pane active" id="buy-order">
										<div class="offer-form">
											<form>
												<div class="clearfix form-inner">
													<div class="clearix offer-form-top">
														<div class="form-group">
															<div class="input-group">
																<div class="input-group-addon">Available Balance (VND)</div>
																<div class="form-control">0.00002230 <a href="#">(All)</a></div>
															</div>
														</div>
														<div class="form-group">
															<div class="input-group">
																<div class="input-group-addon">Max Amount (VND)</div>
																<div class="form-control">9.999.9999.0</div>
															</div>
														</div>
													</div>

													<div class="hr-text"><span>Order</span><hr></div>

													<div class="form-group">
														<label class="sr-only">BTC you receive</label>
														<div class="input-group">
															<div class="input-group-addon">BTC you receive</div>
															<input type="number" class="form-control" min="0" placeholder="0.00002230">
														</div>
													</div>
													<div class="form-group">
														<label class="sr-only">VND you spend</label>
														<div class="input-group">
															<div class="input-group-addon">VND you spend</div>
															<input type="number" class="form-control" min="0" placeholder="28.230.000">
														</div>
													</div>
													<div class="form-group">
														<label class="sr-only">Your BTC wallet</label>
														<div class="input-group">
															<div class="input-group-addon">Your BTC wallet</div>
															<input type="text" class="form-control" placeholder="e3d84c41bdca4e2e89a8d129429a0e0f">
														</div>
													</div>
													<div class="button-group">
														<button type="submit" class="btn btn-flat-green"><span class="btn-inner">Buy Order</span></button>
														<button type="reset" class="btn"><span class="btn-inner">Reset</span></button>
													</div>
													<div class="service-fee-text">Service Fee 0.1/0.25% (102.99 USD)</div>
												</div>
											</form>
										</div>
									</div>
									<div role="tabpanel" class="tab-pane" id="sell-order">
										<div class="offer-form">
											<form>
												<div class="clearfix form-inner">
													<div class="clearix offer-form-top">
														<div class="form-group">
															<div class="input-group">
																<div class="input-group-addon">Available Balance (VND)</div>
																<div class="form-control">0.00002230 <a href="#">(All)</a></div>
															</div>
														</div>
														<div class="form-group">
															<div class="input-group">
																<div class="input-group-addon">Max Amount (VND)</div>
																<div class="form-control">9.999.9999.0</div>
															</div>
														</div>
													</div>

													<div class="hr-text"><span>Order</span><hr></div>

													<div class="form-group">
														<label class="sr-only">BTC you receive</label>
														<div class="input-group">
															<div class="input-group-addon">BTC to sell</div>
															<input type="number" class="form-control" min="0" placeholder="0.00002230">
														</div>
													</div>
													<div class="form-group">
														<label class="sr-only">VND you spend</label>
														<div class="input-group">
															<div class="input-group-addon">VND you receive</div>
															<input type="number" class="form-control" min="0" placeholder="28.230.000">
														</div>
													</div>
													<div class="form-group">
														<label class="sr-only">Your BTC wallet</label>
														<div class="input-group">
															<div class="input-group-addon">Your BTC wallet</div>
															<input type="text" class="form-control" placeholder="e3d84c41bdca4e2e89a8d129429a0e0f">
														</div>
													</div>
													<div class="button-group">
														<button type="submit" class="btn btn-flat-green"><span class="btn-inner">Sell Order</span></button>
														<button type="reset" class="btn"><span class="btn-inner">Reset</span></button>
													</div>
													<div class="service-fee-text">Service Fee 0.1/0.25% (102.99 USD)</div>
												</div>
											</form>
										</div>
									</div>
									<div role="tabpanel" class="tab-pane" id="my-orders">
										<div class="clearfix table-wrap">
											<table class="table table-striped">
												<thead>
												<tr>
													<th>Date/Time</th>
													<th>Type</th>
													<th class="number">Rate</th>
													<th class="number">Qty. (THB)</th>
													<th class="number">Total (BTC)</th>
													<th>Cancel</th>
												</tr>
												</thead>
												<tbody>
												<tr>
													<td colspan="6" class="text-center"><a href="#" data-toggle="modal" data-target="#signinModal">Please login to view your orders</a></td>
												</tr>
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
