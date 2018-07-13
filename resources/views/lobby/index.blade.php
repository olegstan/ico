@extends('layouts.lobby')

@section('content')



    <section class="content-wrap ">
        <div class=" youplay-banner-parallax youplay-banner youplay-banner-id-1 mid banner-top">
            <div class="image"
                 style="background-image: url(http://mosgorinvest.ru/wp-content/uploads/2017/12/игровые-автоматы1.jpg);"
                 data-speed="0.4"></div>

            <div class="info">
                <div>
                    <div class="container">
                        <h2><span class="label  label-warning">Play and win</span></h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid mt-0 mb-0 youplay-content">
            <div class="row">

                <main class="col-xs-12 p-0">
                    <article id="post-202" class="post-202 page type-page status-publish hentry">
                        <div class="entry-content">
                            <div class="vc_row wpb_row vc_row-fluid">
                                <div class="wpb_column vc_column_container vc_col-sm-12">
                                    <div class="vc_column-inner ">
                                        <div class="wpb_wrapper">

                                            <div class=" container">
                                                <h2 class="h1">Popular Games&nbsp;<a class="btn pull-right" href="/games/" target="_self"> Show All </a></h2>
                                            </div>
                                            <div class="owl-carousel" data-stage-padding="70" data-item-padding="10" data-loop="true">
                                                @foreach($bets as $bet)
                                                    <div class="angled-img" onclick="window.open('{{ $bet->openUrl() }}', '{{ $bet->game->name }} {{ $bet->bet }} BTC', 'scrollbars=no,fullscreen=no,left=0,top=0,height=800,width=800')">
                                                        <div class="img">
                                                            <img src="{{ $bet->game->getLogo() }}" alt="">
                                                        </div>
                                                        <div class="over-info">
                                                            <div>
                                                                <div>
                                                                    <h4>{{ $bet->game->name }} {{ $bet->bet }} BTC</h4>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                                <div class="owl-nav">
                                                    <div class="owl-prev"></div>
                                                    <div class="owl-next"></div>
                                                </div>
                                                <div class="owl-dots disabled"></div>
                                            </div>
                                            <div class=" youplay-banner-parallax youplay-banner youplay-banner-id-2 full"
                                                 style="">
                                                <div class="image"
                                                     style="background-image: url(/wp-content/uploads/2018/01/spin2spin_main3.jpg);"
                                                     data-speed="0.4"></div>

                                                <div class="info">
                                                    <div>
                                                        <div class="container">
                                                            <div class="vc_row wpb_row vc_inner vc_row-fluid">
                                                                <div class="wpb_column vc_column_container vc_col-sm-12 vc_col-has-fill">
                                                                    <div class="vc_column-inner vc_custom_1515350488636">
                                                                        <div class="wpb_wrapper"><h2
                                                                                    class=" container h2">
                                                                            </h2>
                                                                            <h3 class="widget-title">GAMBLING IN THE
                                                                                ONLINE CASINO</h3>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="vc_row wpb_row vc_inner vc_row-fluid">
                                                                <div class="wpb_column vc_column_container vc_col-sm-12 vc_col-has-fill">
                                                                    <div class="vc_column-inner vc_custom_1515350488636">
                                                                        <div class="wpb_wrapper">
                                                                            <div class="">
                                                                                <h4>Passion, prestige, honesty: our
                                                                                    gambling machines combine all these
                                                                                    three features. Our gambling house
                                                                                    has already been visited by citizens
                                                                                    of the USA, European countries, Asia
                                                                                    and Arab countries. The secret is
                                                                                    that simple: our gambling machines
                                                                                    make it possible to play free of
                                                                                    charge, without registration or SMS.
                                                                                    Payments are always on time.</h4>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="vc_row wpb_row vc_inner vc_row-fluid">
                                                                <div class="wpb_column vc_column_container vc_col-sm-12 vc_col-has-fill">
                                                                    <div class="vc_column-inner vc_custom_1515351112099">
                                                                        <div class="wpb_wrapper"><h2 class=" h2">
                                                                            </h2>
                                                                            <h3>TOP 3 REASONS TO GAMBLE IN THE ONLINE
                                                                                CASINO</h3>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="vc_row wpb_row vc_inner vc_row-fluid">
                                                                <div class="wpb_column vc_column_container vc_col-sm-12 vc_col-has-fill">
                                                                    <div class="vc_column-inner vc_custom_1515351135898">
                                                                        <div class="wpb_wrapper">
                                                                            <div class="">
                                                                                <h4 class="widget-title">Our guests’
                                                                                    personal information is top
                                                                                    secret<br>
                                                                                    <i class="icon-ok "></i>&nbsp;All
                                                                                    the prizes are paid out within 24
                                                                                    hours!<br>
                                                                                    <i class="icon-ok "></i>&nbsp;Our
                                                                                    casino uses the most popular and
                                                                                    secure payment systems:</h4>
                                                                                <ul>
                                                                                    <li>
                                                                                        <h4>Visa and MasterCard bank
                                                                                            cards,</h4>
                                                                                    </li>
                                                                                    <li>
                                                                                        <h4>Webmoney,</h4>
                                                                                    </li>
                                                                                    <li>
                                                                                        <h4>QIWI,</h4>
                                                                                    </li>
                                                                                    <li>
                                                                                        <h4>PayPal</h4>
                                                                                    </li>
                                                                                </ul>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="vc_row wpb_row vc_inner vc_row-fluid">
                                                                <div class="wpb_column vc_column_container vc_col-sm-12 vc_col-has-fill">
                                                                    <div class="vc_column-inner vc_custom_1515350488636">
                                                                        <div class="wpb_wrapper">
                                                                            <h2 class=" container h2">
                                                                                <div class="title-wrapper">
                                                                                    <h3>WE DON’T LIMIT THE WINNING
                                                                                        AMOUNT</h3>
                                                                                </div>
                                                                            </h2>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="vc_row wpb_row vc_inner vc_row-fluid">
                                                                <div class="wpb_column vc_column_container vc_col-sm-12 vc_col-has-fill">
                                                                    <div class="vc_column-inner vc_custom_1515351263769">
                                                                        <div class="wpb_wrapper">
                                                                            <div class=" container">
                                                                                <h4>You can win a million completely by
                                                                                    chance, without making much
                                                                                    effort.<br>
                                                                                    Save money: how to play gambling
                                                                                    machines in a casino free of charge
                                                                                    and without registration. And how,
                                                                                    let’s say, a beginner can quickly
                                                                                    check whether he likes a game or not
                                                                                    and give it a practical trial? It’s
                                                                                    very easy: just try a demo version,
                                                                                    play the casino’s gambling machines
                                                                                    free of charge, without
                                                                                    registration. A good many time
                                                                                    proven: a good training has never
                                                                                    gone harm to anyone.</h4>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="vc_row wpb_row vc_inner vc_row-fluid vc_row-o-equal-height vc_row-o-content-middle vc_row-flex">
                                                <div class="wpb_column vc_column_container vc_col-sm-12">
                                                    <div class="vc_column-inner ">
                                                        <div class="wpb_wrapper">
                                                            <section class="vc_cta3-container">
                                                                <div class="vc_general vc_cta3 vc_cta3-style-3d vc_cta3-shape-rounded vc_cta3-align-justify vc_cta3-color-juicy-pink vc_cta3-icon-size-md vc_cta3-actions-right">
                                                                    <div class="vc_cta3_content-container">
                                                                        <div class="vc_cta3-content">
                                                                            <header class="vc_cta3-content-header">
                                                                                <h2>WOULD LIKE TO EVALUATE ALL THE
                                                                                    ADVANTAGES TO THE FULL?</h2>
                                                                            </header>
                                                                            <h4>Register: it will take even a beginning
                                                                                player as little as one minute to do it.
                                                                                This will make you a part of the site’s
                                                                                close-knit family and will be able to
                                                                                make use of the best offers. Another
                                                                                privilege for registered users is strict
                                                                                confidentiality of your personal data,
                                                                                including financial operations. All the
                                                                                information is ciphered and available to
                                                                                you only.<br>
                                                                                Put your phone aside: here you can play
                                                                                without SMS online.<br>
                                                                                We respect our guests. By deed, not by
                                                                                word. Here we have the best-known
                                                                                trademarks of gambling software; all are
                                                                                the leaders of the gambling industry.
                                                                                Start comfortably playing online our
                                                                                gambling machines free of charge with
                                                                                the super brands.</h4>
                                                                        </div>
                                                                        <div class="vc_cta3-actions">
                                                                            <div class="vc_btn3-container vc_btn3-inline">
                                                                                <a class="vc_general vc_btn3 vc_btn3-size-lg vc_btn3-shape-rounded vc_btn3-style-modern vc_btn3-color-primary"
                                                                                   href="/register-2/"
                                                                                   title="">Register</a></div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </section>

                                                            <div class=" container">
                                                                <h2 class="h1">Get a BONUS <a class="btn pull-right"
                                                                                              href="/get-a-bonus/"
                                                                                              target="_self"> See
                                                                        More </a></h2>
                                                            </div>
                                                            <div class=" container">
                                                                <h2>Play full, risk only half!</h2>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-center youplay-banner-parallax youplay-banner youplay-banner-id-3 mid">
                                                <div class="image"
                                                     style="background-image: url(/wp-content/uploads/2018/01/cashback.jpg);"
                                                     data-speed="0.4"></div>

                                                <div class="info">
                                                    <div>
                                                        <div class="container">

                                                            <div class="wpb_text_column wpb_content_element ">
                                                                <div class="wpb_wrapper">
                                                                    <h2><span class="label  label-primary">50% cash back bonus ends March, 15 2018</span>
                                                                    </h2>

                                                                </div>
                                                            </div>

                                                            <div class="countdown mb-50 style-1"
                                                                 id="youplay_countdown_id_1" data-end="2018-03-15 12:00"
                                                                 data-timezone="">
                                                                <div class="countdown-item">
                                                                    <span>Days</span><span><span>00</span></span></div>
                                                                <div class="countdown-item">
                                                                    <span>Hours</span><span><span>00</span></span></div>
                                                                <div class="countdown-item">
                                                                    <span>Minutes</span><span><span>00</span></span>
                                                                </div>
                                                                <div class="countdown-item">
                                                                    <span>Seconds</span><span><span>00</span></span>
                                                                </div>
                                                            </div>

                                                            <script type="text/javascript">
                                                                jQuery(function () {
                                                                    jQuery("#youplay_countdown_id_1").each(function () {
                                                                        var tz = jQuery(this).attr('data-timezone');
                                                                        var end = jQuery(this).attr('data-end');
                                                                        end = moment.tz(end, tz).toDate();
                                                                        jQuery(this).countdown(end, function (event) {

                                                                            jQuery(this).html(
                                                                                event.strftime([
                                                                                    '<div class="countdown-item">',
                                                                                    '<span>Days</span>',
                                                                                    '<span><span>%D</span></span>',
                                                                                    '</div>',
                                                                                    '<div class="countdown-item">',
                                                                                    '<span>Hours</span>',
                                                                                    '<span><span>%H</span></span>',
                                                                                    '</div>',
                                                                                    '<div class="countdown-item">',
                                                                                    '<span>Minutes</span>',
                                                                                    '<span><span>%M</span></span>',
                                                                                    '</div>',
                                                                                    '<div class="countdown-item">',
                                                                                    '<span>Seconds</span>',
                                                                                    '<span><span>%S</span></span>',
                                                                                    '</div>'
                                                                                ].join(''))
                                                                            );
                                                                        });
                                                                    })
                                                                })
                                                            </script>
                                                            <a class="btn  btn-lg"
                                                               href="https://wp.nkdev.info/youplay/coming-soon/"
                                                               target="_self"> Purchase </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="vc_row wpb_row vc_row-fluid">
                                <div class="wpb_column vc_column_container vc_col-sm-12">
                                    <div class="vc_column-inner ">
                                        <div class="wpb_wrapper"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <footer class="entry-footer">
                        </footer>
                    </article>


                </main>

            </div>
        </div>


        <div class="clearfix"></div>

        <!-- Footer -->
    @include('lobby.parts.footer')
    <!-- /Footer -->

    </section>

@endsection