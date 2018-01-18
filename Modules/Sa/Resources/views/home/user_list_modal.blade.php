<div class="modal fade action-popup" id="pg-modal-delete" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">{{trans('admin.label.confirm')}}</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <p>{{trans('admin.message.confirm_delete')}}</p>
                        <button data-style="zoom-out" type="button" class="ladda-button btn pull-right btn-danger make-space-left" id="pg-confirm-delete" data-url="{{route('admin.user_delete')}}">{{trans('admin.label.ok')}}</button>
                        <button type="button" class="btn pull-right btn-default make-space-left" data-dismiss="modal">{{trans('admin.label.cancel')}}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade action-popup pg-success-popup" id="pg-modal-delete-success" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">{{trans('admin.label.result')}}</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <p class="pg-msg">{{ trans('admin.message.delete_success_result') }}</p>
                        <div class="pg-error-area">
                            <p class="pg-error-msg">{{ trans('admin.message.delete_fail_result') }}</p>
                        </div>
                        <button type="button" class="btn pull-right btn-white" data-dismiss="modal">{{trans('admin.label.ok')}}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade action-popup" id="pg-modal-verify" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">{{trans('admin.label.confirm')}}</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <p>{{trans('admin.message.confirm_verify')}}</p>
                        <button data-style="zoom-out" type="button" class="ladda-button btn pull-right btn-danger make-space-left" id="pg-confirm-verify" data-url="{{route('admin.user_verify')}}">{{trans('admin.label.ok')}}</button>
                        <button type="button" class="btn pull-right btn-default make-space-left" data-dismiss="modal">{{trans('admin.label.cancel')}}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade action-popup pg-success-popup" id="pg-modal-verify-success" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">{{trans('admin.label.result')}}</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <p class="pg-msg">{{ trans('admin.message.verify_success_result') }}</p>
                        <div class="pg-error-area">
                            <p class="pg-error-msg">{{ trans('admin.message.verify_fail_result') }}</p>
                        </div>
                        <button type="button" class="btn pull-right btn-white" data-dismiss="modal">{{trans('admin.label.ok')}}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
