(function($) {
    // mailchimp help code
    window.fnames = [];
    window.ftypes = [];
    fnames[0]='EMAIL';
    ftypes[0]='email';
    window.$mcj = jQuery;

    // init tether drop
    $('.nk-drop').each(function () {
        new Drop({
            target: this,
            content: $(this).children('.nk-drop-cont').hide().html(),
            classes: 'nk-drop-tether drop-theme-arrows drop-theme-twipsy',
            position: 'bottom center',
            openOn: 'hover'
        })
    });

    function GET(name) {
        var match = RegExp('[?&]' + name + '=([^&]*)').exec(window.location.search);
        return match && decodeURIComponent(match[1].replace(/\+/g, ' '));
    }

    // Activate nk admin menu theme option entry when theme options are active
    var $appearance_menu = $('#menu-appearance');
    $appearance_menu.children('.wp-submenu').find('a[href*="ot-theme-options"]').parent().remove();
    if ( GET('page') === 'ot-theme-options' ) {
        var $nk_admin_menu = $('#toplevel_page_nk-theme');
        $nk_admin_menu.addClass('wp-has-current-submenu wp-menu-open');
        $nk_admin_menu.children('a').addClass('wp-has-current-submenu wp-menu-open');
        $appearance_menu.removeClass('wp-has-current-submenu wp-menu-open');
        $appearance_menu.addClass('wp-not-current-submenu');
        $appearance_menu.find('a[href="themes.php"]').removeClass('wp-has-current-submenu wp-menu-open');
        $appearance_menu.find('a[href="themes.php"]').addClass('wp-not-current-submenu');
    }

    // import demo
    var demoSuccessMessage = nkThemeAdminOptions.damoImportSuccess;
    var $demoDescription = $('.about-description');
    var $demoButtons = $('.nk-demos-list .button-demo');
    var busyDemoImport = 0;

    // import demo data
    if($('.nk-demos-list').length) {
        $('.nk-demos-list .theme-wrapper').append('<div class="nk-theme-demo-icon"></div>');

        // click on import button
        $demoButtons.on('click', function(e) {
            e.preventDefault();
            if(busyDemoImport) {
                return;
            }

            var $confirm = window.confirm(nkThemeAdminOptions.demoImportConfirm);
            if ($confirm != true) {
                return;
            }

            busyDemoImport = 1;
            $demoButtons.attr('disabled', 'disabled');
            var $theme = $(this).parents('.theme:eq(0)');
            var $progress = $theme.find('.theme-progress').addClass('is-active').children();
            var $spinner = $theme.find('.spinner').addClass('is-active');
            var demo_name = $(this).attr('data-demo');

            var evtSource = new EventSource(ajaxurl + '?action=nk_demo_import_action&demo_name=' + demo_name);
            var delta = 0;
            evtSource.onmessage = function ( message ) {
                var data = JSON.parse( message.data );
                switch ( data.action ) {
                    case 'updateDelta':
                        delta += data.delta;
                        if (data.max_delta) {
                            $progress.css('width', (100 * delta / data.max_delta) + '%')
                        }
                        break;

                    case 'complete':
                    case 'error':
                        evtSource.close();
                        $demoButtons.removeAttr('disabled');
                        $spinner.removeClass('is-active');
                        $progress.css('width', '').parent().removeClass('is-active');
                        $('.nk-demos-list .theme.active').removeClass('.active');
                        $theme.addClass('active');
                        $demoDescription.html(data.action == 'error' ? data.error : demoSuccessMessage);
                        busyDemoImport = 0;
                        break;
                }
            };
            evtSource.onerror = function(e) {
                console.error(e, this);
                $demoDescription.html('Error: ' + this.readyState);
                evtSource.close();
                busyDemoImport = 0;
            };
            evtSource.addEventListener( 'log', function (message) {
                var response = JSON.parse(message.data);
                console.info(response.message);
            });
        });

        // prevent user leaving page
        $(window).on('beforeunload', function () {
            if(busyDemoImport) {
                return nkThemeAdminOptions.demoImportBeforeUnload;
            }
        });
    }

    // activate theme copy by purchase key
    var busy_activate;
    $('#nk-theme-activate').on('click', function () {
        if(busy_activate) {
            return;
        }
        var code = $('#nk-theme-activate-license').val();
        var token = $('#nk-theme-activate-token').val();
        var refresh_token = $('#nk-theme-refresh-token').val();
        if(code && token && refresh_token) {
            busy_activate = 1;
            $(this).attr('disabled', 'disabled')
                .next('.spinner').addClass('is-active');

            $.post(ajaxurl, {
                action: 'nk_activation_action',
                type: 'activate',
                code: code,
                token: token,
                refresh_token: refresh_token
            }, function (response) {
                if(response === 'ok') {

                } else {
                    alert(response);
                }
                // reload
                var reloadUri = $('#nk-theme-activate-reload').val();
                location.href = reloadUri || location.href;
            });
        }
    });

    // deactivate theme
    var busy_deactivate;
    $('#nk-theme-deactivate-license').on('click', function () {
        if(busy_deactivate) {
            return;
        }
        var $confirm = window.confirm(nkThemeAdminOptions.deactivateTheme);

        if($confirm == true) {
            busy_deactivate = 1;
            $(this).attr('disabled', 'disabled')
                .next('.spinner').addClass('is-active');

            $.post(ajaxurl, {
                action: 'nk_activation_action',
                type: 'deactivate'
            }, function (response) {
                if(response === 'ok') {

                } else {
                    alert(response);
                }
                // reload
                var reloadUri = $('#nk-theme-deactivate-reload').val();
                location.href = reloadUri || location.href;
            });
        }
    });
}(jQuery));
