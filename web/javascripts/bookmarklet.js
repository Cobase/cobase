// TODO : create a link somewhere in Cobase to allow users to easily bookmark the bookmarklet
// adapt the url to your development environment and copy this code in a bookmark to launch the bookmarklet
// javascript:(function(){var jsCode = document.createElement('script');jsCode.setAttribute('src','http://cobase.localhost/javascripts/bookmarklet.js');document.body.appendChild(jsCode);})();
//

if (!($ = window.jQuery)) { // typeof jQuery=='undefined' works too
    script = document.createElement( 'script' );
    script.src = 'http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js';
    script.onload=loadFancybox;
    document.body.appendChild(script);
} else {
    loadFancybox();
}

function loadFancybox() {
    // fancybox plugin (js and css)
    $('head').append('<link rel="stylesheet" type="text/css" href="http://cobase.localhost/stylesheets/jquery.fancybox.css">');
    fancybox = document.createElement( 'script' );
    fancybox.src = 'http://cobase.localhost/javascripts/jquery.fancybox.pack.js';
    fancybox.onload=loadCobase;
    document.body.appendChild(fancybox);
}

function loadCobase() {
    // loading cobase in popin
    console.log('ici');
    $.fancybox({
        'width': '50%',
        'height': '50%',
        'scrolling': 'no',
        'maxWidth': '500px',
        'maxHeight': '400px',
        // 'autoScale': true,
        'transitionIn': 'fade',
        'transitionOut': 'fade',
        'type': 'iframe',
        'href': 'http://cobase.localhost/app_dev.php/post/new?url='+encodeURIComponent(document.location)
    });
}