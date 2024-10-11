document.addEventListener('DOMContentLoaded', function () {
    (function($){

        let $body = $('body');
        $body.find('.number').each(function(){
            // $(this).inputmask('+7 999 999 99 99');

            $(this).removeAttr( "pattern" );
            $(this).inputmask({
                mask: "+7 999 999 99 99",
                clearIncomplete: true
            });
        });

    })( jQuery );
}, false);
