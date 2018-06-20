@if(!Auth::check())
    <ul class="nav navbar-nav navbar-right">
        <li class="dropdown dropdown-hover dropdown-user">
            <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" role="button"
               aria-expanded="true">
                <i class="fa fa-user"></i>

                <span class="caret"></span>
            </a>
            <div class="dropdown-menu pb-20" style="width: 300px;">
                <div class="block-content m-20 mnb-10 mt-0">
                    <div class="lwa lwa-default">
                        <form class="lwa-form block-content" action="/login/"
                              method="post">

                            <span class="lwa-status"></span>

                            <p>
                                Username/Email: </p>
                            <div class="youplay-input">
                                <input type="text" name="log">
                            </div>

                            <p>
                                Password: </p>
                            <div class="youplay-input">
                                <input type="password" name="pwd">
                            </div>


                            <div class="youplay-checkbox mb-15 ml-5">
                                <input type="checkbox" name="rememberme" value="forever" id="rememberme-lwa-1"
                                       tabindex="103">
                                <label for="rememberme-lwa-1">Remember Me</label>
                            </div>

                            <button class="btn btn-sm ml-0 mr-0" name="wp-submit" id="lwa_wp-submit-1"
                                    tabindex="100">
                                Log In
                            </button>

                            <br>

                            {{ csrf_field() }}
                            <input type="hidden" name="lwa_profile_link" value="1">
                            <input type="hidden" name="login-with-ajax" value="login">

                            <p></p>
                            <a class="lwa-links-remember no-fade"
                               href="/auth_login/?action=lostpassword"
                               title="Password Lost and Found">Lost password?</a>

                            |
                            <a href="/register-2/" class="lwa-links-register lwa-links-modal no-fade">Register</a>

                        </form>
                        <form class="lwa-remember block-content"
                              action="/auth_login/?action=lostpassword" method="post"
                              style="display:none;">
                            <div>
                                <span class="lwa-status"></span>
                                <table>
                                    <tbody>
                                    <tr>
                                        <td>
                                            <strong>Forgotten Password</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="lwa-remember-email">
                                            <div class="youplay-input">
                                                <input type="text" name="user_login" class="lwa-user-remember"
                                                       value="Enter username or email"
                                                       onfocus="if(this.value == 'Enter username or email'){this.value = '';}"
                                                       onblur="if(this.value == ''){this.value = 'Enter username or email'}">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="lwa-remember-buttons">
                                            <button class="btn btn-sm">Get New Password</button>
                                            <a href="#" class="lwa-links-remember-cancel">Cancel</a>
                                            {{ csrf_field() }}
                                            <input type="hidden" name="login-with-ajax" value="remember">
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </form>
                        <div class="lwa-register lwa-register-default lwa-modal modal-dialog" style="display:none;">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close lwa-modal-close"><span
                                                aria-hidden="true">×</span></button>
                                    <h4 class="modal-title">Register</h4>
                                </div>
                                <div class="modal-body">
                                    <form class="lwa-register-form" action="/register-2/" method="post">
                                        <div>
                                            <span class="lwa-status"></span>

                                            <p>Username/Email: </p>
                                            <div class="youplay-input">
                                                <input type="text" name="user_login" id="user_login-1" class="input"
                                                       size="20"
                                                       tabindex="10">
                                            </div>


                                            <p>
                                                <em class="lwa-register-tip">A password will be e-mailed to you.</em>
                                            </p>

                                            <button class="btn ml-3" name="wp-submit" id="wp-submit-1" tabindex="100">
                                                Register
                                            </button>

                                            {{ csrf_field() }}
                                            <input type="hidden" name="login-with-ajax" value="register">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </li>
    </ul>
@else
    <ul class="nav navbar-nav navbar-right">
        <li class="dropdown dropdown-hover dropdown-user">
            <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" role="button"
               aria-expanded="false">
                <i class="fa fa-user"></i>
                Credits: <span class="balance" id="credits">{{ Auth::user()->credits }}</span><span
                        style="text-transform: none;"> mBTC</span><br>
                {{Auth::user()->email}} <span class="caret"></span>
            </a>
            <div class="dropdown-menu pb-20" style="width: 300px;">
                <div class="block-content m-20 mnb-10 mt-0">
                    <div class="lwa block-content">
                        <span class="lwa-title-sub" style="display:none">Hi {{Auth::user()->email}}</span>
                        <table>
                            <tbody>
                            <tr>
                                <ul class="list-unstyled lwa-profile-links">
                                    <li>
                                        <a id="wp-logout"
                                           href="{{ route((Auth::check() ? Auth::user()->getSlugRole() : '') . '.home') }}">Dashboard</a>
                                    </li>
                                    <li>
                                        <a id="wp-logout"
                                           href="{{ route('auth.logout') }}">Log
                                            Out</a>
                                    </li>
                                </ul>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </li>
    </ul>
@endif