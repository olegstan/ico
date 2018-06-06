<div class="lwa">
    <div class="lwa-register lwa-register-default lwa-modal modal-dialog" style="display:none;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close lwa-modal-close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Register</h4>
            </div>
            <div class="modal-body">
                <form class="lwa-register-form" action="/register-2/" method="post">
                    <div>
                        <span class="lwa-status"></span>

                        <p>Username: </p>
                        <div class="youplay-input">
                            <input type="text" name="user_login" id="user_login-1" class="input" size="20"
                                   tabindex="10">
                        </div>

                        <p>E-mail: </p>
                        <div class="youplay-input">
                            <input type="text" name="user_email" id="user_email-1" class="input" size="25"
                                   tabindex="20">
                        </div>


                        <p>
                            <em class="lwa-register-tip">A password will be e-mailed to you.</em>
                        </p>

                        <button class="btn ml-3" name="wp-submit" id="wp-submit-1" tabindex="100">Register</button>

                        {{ csrf_field() }}
                        <input type="hidden" name="login-with-ajax" value="register">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>