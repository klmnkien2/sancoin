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
                <h4 class="modal-title">{{trans('labels_c.C_L030')}}</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <p id="pg-delete-msg"></p>
                        <p id="pg-delete-msg-src" class="none">{{trans('labels_sa.SA_CIL0010_M002')}}</p>
                        <div id="pg-delete-error-area">
                            <p id="pg-delete-error-msg"></p>
                            <p id="pg-delete-error-msg-src" class="none">{{trans('labels_sa.SA_CIL0010_M003')}}</p>
                            <p id="pg-delete-error"></p>
                        </div>
                        <button type="button" class="btn pull-right btn-white" data-dismiss="modal">{{trans('labels_c.C_L002')}}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
