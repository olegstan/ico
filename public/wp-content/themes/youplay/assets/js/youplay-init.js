(function ($) {
    if(typeof youplay !== 'undefined') {
        youplay.init({
            // enable parallax
            parallax: youplayInitOptions.enableParallax == 1,

            // set small navbar on load
            navbarSmall:      false,

            // enable fade effect between pages
            fadeBetweenPages: youplayInitOptions.enableFadeBetweenPages == 1,

            // twitter and instagram php paths (no need for WordPress version)
            php: {
                twitter: false,
                instagram: false
            }
        });
    }
}(jQuery));