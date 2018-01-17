var deleteBtn = $('#pg-confirm-delete').ladda();
var verifyBtn = $('#pg-confirm-verify').ladda();

$(document).ready(function () {
  $('.datepicker').datepicker({
    keyboardNavigation: false,
    forceParse: false,
    autoclose: true,
    todayHighlight: true,
    enableOnReadonly: false,
    language: "ja"
  });

  $('#pg-confirm-delete').on('click', function () {
    deleteBtn.ladda('start');
    var listID = getCheckedID();
    var callbackSuccess = function (response) {
      $('#pg-modal-delete').modal('hide');
      deleteBtn.ladda('stop');
      if (response.status == true) {
        reloadTableData(response);
        showResultModal('pg-modal-delete-success', response);
      } else {
        callbackFail();
      }
    }
    var callbackFail = function (response) {
      $('#pg-modal-error').modal('show');
    };
    callAjax($(this).data('url'), {id: listID, condition: window.location.search.substr(1)}, callbackSuccess, callbackFail);
  });
});//document ready