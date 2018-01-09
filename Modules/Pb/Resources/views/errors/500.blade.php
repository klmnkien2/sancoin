@extends('pb::layouts.main')

@section('content')
    <section class="section section-offers">
		<div class="container-fluid">
			<div class="row">
				<div class="col-xs-12 col-md-8 col-md-offset-2">
					<div class="clearfix box-base box-green list-of-sellers">
						<div class="clearfix box-inner">
							<div class="clearfix box-head">
								<h3 class="box-title"><strong class="title-inner">Notification</strong></h3>
							</div>
							<div class="clearfix box-body offer-form">
								<p>{{ trans('messages.message.error_500') }}</p>
							</div>
						</div>
					</div>
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
