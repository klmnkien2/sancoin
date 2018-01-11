<?php $url = !empty($url) ? $url : '';?>
<div id="{{$id}}" class="modal fade" aria-hidden="true">
    <div class="modal-dialog error-popup">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">{{$title}}</h4>
            </div>
            <div class="modal-body error-body">
                <div class="row">
                    <div class="col-sm-12">
                        <p class="message-content">{{$message}}</p>
                        <button type="button" class="btn pull-right btn-success button-ok" id="confirm_popup_yes" {!! !empty($url) ? 'data-url="'.$url.'"' : '' !!} data-dismiss="modal">{{!empty($btnRequest) ? trans('labels_cm.CM_BS0010_L038') : trans('labels_c.C_L054')}}</button>
                        <button type="button" class="btn pull-right btn-white m-r-sm button-cancel" id="confirm_popup_no" data-dismiss="modal">{{trans('labels_c.C_L053')}}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>