jQuery(document).ready(function($) {

    $('.notification-bar-close-btn').on('click', function(event) {
        var $this = $(this);

        var tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);

        $this.parent('.notification-bar').hide();

        $('body').removeClass('has-notification-bar');

        document.cookie='ner_notif_bar_shown=true; expires=' + new Date(tomorrow) + '; path=/';
    });


    var imgSrc = BackStretchImg.src;

    if(imgSrc) {
        var $bgContainer = $('<div></div>');

        $bgContainer.addClass('ner-bg-image');

        $bgContainer.css({
            backgroundImage: "url('" + imgSrc + "')",
            backgroundSize: 'cover',
            backgroundRepeat: 'no-repeat',
            backgroundPosition: '0 0',
            position: 'fixed',
            width: '100%',
            height: '100%',
            top: 0,
            left: 0,
            opacity: 0,
            zIndex: -1,
            transition: 'opacity 2s'
        });

        $('body').append($bgContainer);

        $(window).load(function() {
            $bgContainer.css({
                opacity: .6
            });
        });
    }

    
});