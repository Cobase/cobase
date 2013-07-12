if (!($ = window.jQuery)) { // typeof jQuery=='undefined' works too
    script = document.createElement( 'script' );
    script.src = 'http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js';
    script.onload=loadCobase;
    document.body.appendChild(script);
}
else {
    loadCobase();
}

function loadCobase() {
    var pageWidth = $(window).width();
    var pageHeight = $(window).height();
    var width = (pageWidth / 2) - 250;
    var height = (pageHeight / 2) - 200;
    $('html, body').animate({ scrollTop: 0 }, 'slow');
    $('body').prepend('<div id="cobase" style="z-index: 1000; width: 500px; height: 400px; margin: 0px auto; top: '+height+'px; position: absolute; left: '+width+'px;"><iframe id="cobaseIframe" width="100%" height="100%"></iframe></div>');
    var cobaseIframe = $('#cobaseIframe');
    cobaseIframe.attr('src', 'http://cobase.localhost/app_dev.php/post/new?url='+encodeURIComponent(document.location));
}