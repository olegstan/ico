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
                            Username: </p>
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
                        <a href="/register-2/"
                           class="lwa-links-register lwa-links-modal no-fade">Register</a>

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
                                        <input type="hidden" name="login-with-ajax" value="remember">
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </li>
</ul>