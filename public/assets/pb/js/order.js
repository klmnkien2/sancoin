(function (global, $) {
  var pageClass = {
    build: function () {
      this.bindAllListeners();
    },
    bindAllListeners: function () {
    	$(".pg-order-form [name=coin_type]").on("change", $.proxy(this, 'onChangeCoinType'));
    	$(".pg-delete-order").on("click", $.proxy(this, 'onDeleteOrder'));
    	$(".pg-order-form .pg-param-money").on("change", $.proxy(this, 'updateTotalMoney'));
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
    	select.closest('form').find("[name=amount]").val('');
    },
    updateTotalMoney: function (evt) {
        var form = $(evt.currentTarget).closest('form'),
        	coin_type = form.find("[name=coin_type]"),
        	coin_amount_btc = form.find("[name=coin_amount_btc]"),
        	btc_to_usd = form.find("[name=btc_to_usd]"),
        	coin_amount_eth = form.find("[name=coin_amount_eth]"),
        	eth_to_usd = form.find("[name=eth_to_usd]"),
        	usd_to_vnd = form.find("[name=usd_to_vnd]"),
        	amount = form.find("[name=amount]");
      	
      	if (coin_type.val() == 'btc' && coin_amount_btc.val() && btc_to_usd.val() && usd_to_vnd.val()) {
      		amount.val(Math.ceil(parseFloat(coin_amount_btc.val()) * parseFloat(btc_to_usd.val()) * parseFloat(usd_to_vnd.val())));
      	}
      	if (coin_type.val() == 'eth' && coin_amount_eth.val() && eth_to_usd.val() && usd_to_vnd.val()) {
      		amount.val(Math.ceil(parseFloat(coin_amount_eth.val()) * parseFloat(eth_to_usd.val()) * parseFloat(usd_to_vnd.val())));
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