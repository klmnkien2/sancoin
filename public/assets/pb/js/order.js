(function (global, $) {
  var pageClass = {
    build: function () {
      this.bindAllListeners();
    },
    bindAllListeners: function () {
    	$(".pg-order-form [name=coin_type]").on("change", $.proxy(this, 'onChangeCoinType'));
    	$("#pg-select-image").on("click", $.proxy(this, 'onSelectButton'));
    	$("#pg-clear-image").on("click", $.proxy(this, 'onClearButton'));
    	//$("#pg-profile-save").on("click", $.proxy(this, 'onSaveProfile'));
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
    }
  };

  $(function() {
    pageClass.build();
  });
} (window, jQuery));