(function($) {
    // JS Custom
    jQuery(document).ready(function($) {
        var focusInputGroup = function () {
            var elements = $('.input-group');
            if (elements.length > 0) {
                elements.on('click', '.input-group-addon', function(e) {
                    e.preventDefault();
                    if ($(this).closest('.input-group').find('input').length > 0) {
                        $(this).closest('.input-group').addClass('focused').find('input').focus();
                    }
                });
                elements.find('input').focus( function() {
                    $(this).closest('.input-group').addClass('focused');
                }).blur( function() {
                    $(this).closest('.input-group').removeClass('focused');
                });
            }
        }
        focusInputGroup();
    });

    $(window).on("load",function(){
        $(".custom-scrollbar").mCustomScrollbar({
            theme: "minimal-dark"
        });
    });

})(jQuery);