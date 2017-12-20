(function (global, $) {
    var pageClass = {
        root: null,
        build: function () {
            // Bind event listeners
            this.bindAllListeners();
        },
        bindAllListeners: function () {
            $('#pg-login-submit').on('click', $.proxy(this, 'onLoginSubmit'));
        },
        /**
         * Handler to be invoked when a link is clicked.
         *
         * @param {jQuery.Event} evt
         */
        onLoginSubmit: function (evt) {
        	evt.preventDefault();
        	var form = $(evt.currentTarget).closest('form');
        	$.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serializeArray(),
            beforeSend: function( xhr ) {
            	$.LoadingOverlay("show");
            }
          }).done(function (response) {
          	var response = jQuery.parseJSON(response);
          	if (response.success === true) {
          		//$.LoadingOverlay("hide");
          		window.location.href = response.redirect;
          	} else {
          		if (jQuery.isArray(response.error) && !jQuery.isEmptyObject(response.error)) {
          			form.find("span.error").html(response.error[0]);
          		}
          	}
          }).fail(function (data) {
          }).always(function (data) {
          	$.LoadingOverlay("hide");
          });
        }
    };

    $(function() {
        pageClass.build();
    });
} (window, jQuery));