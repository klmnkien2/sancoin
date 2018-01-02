(function (global, $) {
  var pageClass = {
    build: function () {
      this.bindAllListeners();
    },
    bindAllListeners: function () {
    	$(".pg-order-form [name=coin_type]").on("change", $.proxy(this, 'onChangeCoinType'));
    	$(".pg-delete-order").on("click", $.proxy(this, 'onDeleteOrder'));
    },
    onChangeCoinType: function (evt) {
      //evt.preventDefault();
    	var select = $(evt.currentTarget);
    	select.closest('form').find('.pg-for-btc').hide();
    	select.closest('form').find('.pg-for-eth').hide();
    	if (select.val() == 'btc') {
    		select.closest('form').find('.pg-for-btc').show();
    	}
    	if (select.val() == 'eth') {
    		select.closest('form').find('.pg-for-eth').show();
    	}
    },
    onDeleteOrder: function(evt) {
    	evt.preventDefault();
    	swal({
	  	  title: 'Are you sure?',
	  	  confirmButtonText: 'OK',
	  	  cancelButtonText: 'Cancel',
	  	  showCancelButton: true,
	  	  showCloseButton: true
	  	}).then((result) => {
	  	  if (result.value) {
	  		var newForm = $('<form>', {
	  	        'action': $(evt.currentTarget).data('url'),
	  	        'method': 'POST',
	  	        'target': '_top'
	  	    }).append($('<input>', {
	  	        'name': '_token',
	  	        'value': $('meta[name=csrf-token]').attr("content"),
	  	        'type': 'hidden'
	  	    }));
	  		$(document.body).append(newForm);
	  	    newForm.submit();
	  	  // result.dismiss can be 'cancel', 'overlay',
	  	  // 'close', and 'timer'
	  	  } else if (result.dismiss === 'cancel') {
	  	    //do something special if needed
	  	  }
	  	})
    }
  };

  $(function() {
    pageClass.build();
  });
} (window, jQuery));