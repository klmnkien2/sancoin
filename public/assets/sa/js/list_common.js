$(document).ready(function () {

  $(document).on('click', '#pg-check-all', function () {
    if ($(this).prop('checked') == true) {
      $('.pg-checkbox').prop('checked', true)
    } else {
      $('.pg-checkbox').prop('checked', false)
    }
    if ($('input.pg-checkbox:checkbox:checked').length > 0) {
      $('.pg-btn-action').prop('disabled', false)
    } else {
      $('.pg-btn-action').prop('disabled', true)
    }
  })

  $(document).on('click', '.pg-checkbox', function () {
    var count = $('input.pg-checkbox:checkbox:checked').length
    var total = $('input.pg-checkbox:checkbox').length
    if (count < total) {
      $('#pg-check-all').prop('checked', false)
    } else {
      $('#pg-check-all').prop('checked', true)
    }
    if (count > 0) {
      $('.pg-btn-action').prop('disabled', false)
    } else {
      $('.pg-btn-action').prop('disabled', true)
    }
  })

  $('#pg-select-sort').on('change', function () {
    if ($('#pg-total-result').val() > 0) {
      $('#pg-sort').val($(this).val())
      $('form').submit()
    }
  })

  $('#pg-per-page').on('change', function () {
    if ($('#pg-total-result').val() > 0) {
      $('#pg-per').val($(this).val())
      $('form').submit()
    }
  })

})

function callAjax(url, submit_data, callbackSuccess, callbackFail) {
  submit_data._token = $('meta[name="csrf-token"]').attr('content')
  $.ajax({
    type: 'POST',
    url: url,
    data: submit_data,
    success: function (data) {
      callbackSuccess(data)
    },
    error: function (rs) {
      if (rs.status == 401) {
        location.reload()
      } else {
        callbackFail()
      }

    }
  })
}

function showResultModal(id, data) {
  $('#' + id + ' ' + '.pg-msg').html($('#' + id + ' ' + '.pg-msg').html().replace(':number', '<strong>' + data.count + '</strong>'))
  var errorList = Object.values(data.errorList)
  if (errorList.length > 0) {
    $('#' + id + ' ' + '.pg-error-area').show()
    $('#' + id + ' ' + '.pg-error-msg').html($('#' + id + ' ' + '.pg-error-msg').html().replace(':number', '<strong>' + errorList.length + '</strong>'))
  } else {
    $('#' + id + ' ' + '.pg-error-area').hide()
  }

  $('#' + id).modal('show');
}

function getCheckedID () {
  var listID = []
  $('input.pg-checkbox:checkbox:checked').each(function () {
    listID.push($(this).data('id'))
  })
  return listID
}

function reloadTableData (response) {
  $('#pg-table-area').html(response.dataHtml)
  $('#pg-pagination-text').html(response.paginationText)
  $('#pg-pagination-html').html(response.paginationHtml)
  var errorLength = Object.keys(response.errorList).length;
  if (errorLength == 0) {
    $(".pg-btn-action").prop("disabled", true);
  } else {
    if (errorLength == $('#pg-per-page').val()) {
      $('#pg-check-all').prop('checked', true);
    }
    for (var i = 0; i < Object.keys(response.errorList).length; i++) {
      $('.pg-checkbox[data-id="' + Object.keys(response.errorList)[i] + '"]').prop('checked', true);
    }
  }
}
