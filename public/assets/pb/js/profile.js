(function (global, $) {
  var pageClass = {
    build: function () {
    	if (!window.File || !window.FileReader || !window.FileList || !window.Blob) {
        alert('The File APIs are not fully supported in this browser.');
        return;
      }
//    	swal({
//    	  title: 'Are you sure?',
//    	  confirmButtonText: 'OK',
//    	  cancelButtonText: 'Cancel',
//    	  showCancelButton: true,
//    	  showCloseButton: true
//    	});
      // Bind event listeners
      this.bindAllListeners();
    },
    bindAllListeners: function () {
    	$("#pg-upload-image").on("change", $.proxy(this, 'onChangeFileInput'));
    	$("#pg-select-image").on("click", $.proxy(this, 'onSelectButton'));
    	$("#pg-clear-image").on("click", $.proxy(this, 'onClearButton'));
    	//$("#pg-profile-save").on("click", $.proxy(this, 'onSaveProfile'));
    },
    onSaveProfile: function(evt) {
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
          //TODO
        } else {
        	//TODO
        }
      }).fail(function (data) {
      }).always(function (data) {
        $.LoadingOverlay("hide");
      });
    },
    onChangeFileInput: function (evt) {
      //evt.preventDefault();
    	$("#pg-item-upload").html('');
    	var files = document.getElementById('pg-upload-image').files;
    	this.showThumbnail(files);
    },
    onSelectButton: function(e) {
      $("#pg-upload-image").show().focus().click().hide();
      e.preventDefault();
    },
    onClearButton: function(e) {
      var $el = $('#pg-upload-image');
      $el.wrap('<form>').closest('form').get(0).reset();
      $el.unwrap();

      $("#pg-item-upload").html('');
      e.preventDefault();
      
    },
    showThumbnail: function(files) {
    	//console.log(files);
    	for(var i=0;i<files.length;i++){
        var file = files[i]
        var imageType = /image.*/
        if(!file.type.match(imageType)){
          console.log("Not an Image");
          continue;
        }

        var image = document.createElement("img");
        image.file = file;
        $(image).addClass("thumbnail");
        $(image).css("width", "120px");
        $(image).css("display", "inline");
        $(image).css("margin-right", "10px");
        var item_upload = document.getElementById("pg-item-upload");
        item_upload.appendChild(image);

        var reader = new FileReader()
        reader.onload = (function(aImg){
          return function(e){
            aImg.src = e.target.result;
          };
        }(image))
        var ret = reader.readAsDataURL(file);
        var canvas = document.createElement("canvas");
        ctx = canvas.getContext("2d");
        image.onload= function(){
          ctx.drawImage(image,100,100)
        }
      }
    }
  };

  $(function() {
    pageClass.build();
  });
} (window, jQuery));