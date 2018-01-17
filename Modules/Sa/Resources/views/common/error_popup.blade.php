<div id="{{$id}}" class="modal fade" aria-hidden="true">
    <div class="modal-dialog error-popup">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">{{trans('admin.label.alert')}}</h4>
            </div>
            <div class="modal-body error-body">
                <p class="message-content">{{$message}}</p>
                <div class="text-center">
                    <button type="button" class="btn btn-white error-btn" data-dismiss="modal">{{trans('admin.label.ok')}}</button>
                </div>
            </div>
        </div>
    </div>
</div>