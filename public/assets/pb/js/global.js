(function (global, $) {
  var pageClass = {
      root: null,
      build: function () {
        // Bind event listeners
        this.bindAllListeners();
      },
      bindAllListeners: function () {
        $('#pg-login-submit').on('click', $.proxy(this, 'onLoginSubmit'));
        $('#pg-reg-submit').on('click', $.proxy(this, 'onRegSubmit'));
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
            if (!jQuery.isEmptyObject(response.error)) {
              for (key in response.error) {
                form.find("span.error").html(response.error[key][0]);
                break;
              }
            }
          }
        }).fail(function (data) {
        }).always(function (data) {
          $.LoadingOverlay("hide");
        });
      },
      onRegSubmit: function (evt) {
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
        	  form.find("span.error").html('');
            alert('Success, check your email to confirm.')
          } else {
            if (!jQuery.isEmptyObject(response.error)) {
              for (key in response.error) {
                form.find("span.error").html(response.error[key][0]);
                break;
              }
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