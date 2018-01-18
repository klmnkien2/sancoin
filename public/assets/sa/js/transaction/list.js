var approveBtn = $('#pg-confirm-approve').ladda();
var verifyBtn = $('#pg-confirm-verify').ladda();

$(document).ready(function () {
  $('#pg-confirm-approve').on('click', function () {
    approveBtn.ladda('start');
    var listID = getCheckedID();
    var callbackSuccess = function (response) {
      $('#pg-modal-approve').modal('hide');
      approveBtn.ladda('stop');
      if (response.status == true) {
        reloadTableData(response);
        showResultModal('pg-modal-approve-success', response);
      } else {
        callbackFail();
      }
    }
    var callbackFail = function (response) {
      $('#pg-modal-error').modal('show');
      $('#pg-modal-approve').modal('hide');
      approveBtn.ladda('stop');
    };
    callAjax($(this).data('url'), {id: listID, condition: window.location.search.substr(1)}, callbackSuccess, callbackFail);
  });

  $('#pg-confirm-verify').on('click', function () {
    verifyBtn.ladda('start');
    var listID = getCheckedID();
    var callbackSuccess = function (response) {
      $('#pg-modal-verify').modal('hide');
      verifyBtn.ladda('stop');
      if (response.status == true) {
        reloadTableData(response);
        showResultModal('pg-modal-verify-success', response);
      } else {
        callbackFail();
      }
    }
    var callbackFail = function (response) {
      $('#pg-modal-verify-error').modal('show');
      $('#pg-modal-verify').modal('hide');
      verifyBtn.ladda('stop');
    };
    callAjax($(this).data('url'), {id: listID, condition: window.location.search.substr(1)}, callbackSuccess, callbackFail);
  });
});//document ready