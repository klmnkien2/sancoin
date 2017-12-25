@extends('pb::layouts.main')

@section('content')
    <section class="section">
		<div class="container-fluid">
			<div class="row">
				<div class="col-xs-12 col-md-8 col-md-offset-2">
					<div class="clearfix box-base box-green">
						<div class="clearfix box-inner">
							<div class="clearfix box-head">
								<h3 class="box-title"><strong class="title-inner">{{ trans('messages.label.profile') }}</strong></h3>
							</div>
							<div class="clearfix box-body" style="padding: 15px;">
								<form enctype="multipart/form-data" method="POST" type="post" action="{{route('pb.updateProfile')}}">
									<div class="clearfix form-inner">
                                        <div>
                                            @includeIf('pb::mod.alert_danger', ['name' => 'error', 'display' => 'none'])
                                            @includeIf('pb::mod.alert_success', ['name' => 'error', 'display' => 'block'])
                                        </div>
                                        <div class="form-group">
											<label for="inputfullname">{{trans('messages.label.fullname')}}</label>
                                            <div class="input-group" style="width: 100%">
                                              <input type="text" class="form-control" name="fullname" id="inputfullname">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="id_number">{{trans('messages.label.id_number')}}</label>
                                            <div class="input-group" style="width: 100%">
                                              <input type="text" class="form-control" name="id_number" id="id_number">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="id_created_at">{{trans('messages.label.id_created_at')}}</label>
                                            <div class="input-group" style="width: 100%">
                                              <input type="text" class="form-control" name="id_created_at" id="id_created_at">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="id_created_by">{{trans('messages.label.id_created_by')}}</label>
                                            <div class="input-group" style="width: 100%">
                                              <input type="text" class="form-control" name="id_created_by" id="id_created_by">
                                            </div>
                                        </div>

										<div class="form-group">
                                            <label for="pg-profile-image">{{trans('messages.label.upload_image')}}</label>
                                            <div class="input-group" style="width: 100%">
                                                <button id="pg-select-image" type="button" class="btn btn-flat"><span class="btn-inner">{{trans('messages.label.buttonSelect')}}</span></button>
    											<div id="pg-item-upload" style="margin-top: 10px;">
    											</div>
    											<input type="file" name="images[]" class="hidden" id="pg-upload-image" multiple="multiple">
    										</div>
                                        </div>

										<div class="button-group">
											{{csrf_field()}}
											<button id="pg-profile-save" type="submit" class="btn btn-flat-green"><span class="btn-inner">{{trans('messages.label.buttonSave')}}</span></button>
											<button id="pg-clear-image" type="button" class="btn btn-flat-yellow" style="margin-left: 10px;"><span class="btn-inner">{{trans('messages.label.buttonClearImage')}}</span></button>
										</div>
									</div>
								</form>
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
