<nav class="navbar-youplay navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="off-canvas" data-target="#navbar"
                    aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="http://spin2spin.com">
                <img src="http://mosgorinvest.ru/wp-content/uploads/2017/12/2spin_prel-1.png" alt="">
            </a>
        </div>

        <div id="navbar" class="navbar-collapse collapse">
            <ul id="menu-main" class="nav navbar-nav">
                <li id="menu-item-834" class="menu-item menu-item-type-post_type menu-item-object-page"><a
                            href="http://spin2spin.com/main-copy/" role="button" aria-expanded="false">About Us</a></li>
                <li id="menu-item-842" class="menu-item menu-item-type-post_type menu-item-object-page"><a
                            href="http://spin2spin.com/games/" role="button" aria-expanded="false">Games</a></li>
                <li id="menu-item-993" class="menu-item menu-item-type-post_type menu-item-object-page"><a
                            href="http://spin2spin.com/get-a-bonus/" role="button" aria-expanded="false">Get a BONUS</a>
                </li>
                <li id="menu-item-836" class="menu-item menu-item-type-post_type menu-item-object-page"><a
                            href="http://spin2spin.com/payment/" role="button" aria-expanded="false">Payment</a></li>
                <li id="menu-item-1202" class="menu-item menu-item-type-post_type menu-item-object-page"><a
                            href="http://spin2spin.com/support-2/" role="button" aria-expanded="false">Support</a></li>
            </ul>


            <ul class="nav navbar-nav navbar-right">
                <li class="search-toggle"><a href="javascript:void(0)" role="button" aria-expanded="false"><span
                                class="fa fa-search"></span></a></li>
            </ul>


            @include('lobby.parts.auth')

            <div class="nav navbar-nav navbar-right"></div>
        </div>
    </div>
</nav>