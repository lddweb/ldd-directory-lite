<?php add_filter('wp_login_failed','something'); ?>
<div class="directory-lite bootstrap-wrapper">

    <?php ldl_get_header(); ?>

    <?php if (array_key_exists('registered', $_GET)): ?>
    <div class="alert alert-success" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <?php _e('A password has been sent to your email address. Thank you for registering!', 'ldd-directory-lite'); ?>
    </div>
    <?php endif; ?>
    <?php if (array_key_exists('reset', $_GET)): ?>
    <div class="alert alert-warning" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <?php _e('An email with information on how to reset your password has been sent.', 'ldd-directory-lite'); ?>
    </div>
    <?php endif; ?>


    <p><?php _e('Please log in, or register a new user account...', 'ldd-directory-lite' ); ?></p>

    <ul class="nav nav-tabs bump-up-more" role="tablist">
        <li class="active"><a href="#login" role="tab" data-toggle="tab"><?php _e('Login', 'ldd-directory-lite'); ?></a></li>
        <li><a href="#register" role="tab" data-toggle="tab"><?php _e('Register', 'ldd-directory-lite'); ?></a></li>
        <li><a href="#lost-password" role="tab" data-toggle="tab"><?php _e('Lost Password', 'ldd-directory-lite'); ?></a></li>
    </ul>

    <!-- Tab panes -->
    <div class="col-lg-6">
    <div class="tab-content abc">
        <div class="tab-pane active" id="login">

            <form method="post" action="<?php echo site_url('wp-login.php') ?>" class="form-horizontal">
                <input type="hidden" name="redirect_to" value="<?php echo $_SERVER['REQUEST_URI']; ?>">
                <input type="hidden" name="user-cookie" value="1">

                <div class="form-group">
                    <label for="user_login" class="col-sm-4 control-label"><?php _e('Username', 'ldd-directory-lite'); ?></label>
                    <div class="col-sm-8">
                        <input id="user_login" class="form-control" type="text" name="log">
                    </div>
                </div>
                <div class="form-group">
                    <label for="user_pass" class="col-sm-4 control-label"><?php _e('Password', 'ldd-directory-lite'); ?></label>
                    <div class="col-sm-8">
                        <input id="user_pass" class="form-control" type="password" name="pwd">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-9">
                        <div class="checkbox">
                            <label>
                                <input id="rememberme" type="checkbox" name="rememberme" value="forever"> <?php _e('Remember me', 'ldd-directory-lite'); ?>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-9">
                        <button type="submit" class="btn btn-default"><?php _e('Sign in', 'ldd-directory-lite'); ?></button>
                    </div>
                </div>
            </form>

        </div>
        <div class="tab-pane" id="register">

            <?php if (get_option('users_can_register')): ?>
                <form method="post" action="<?php echo site_url('wp-login.php?action=register&pt=directory_listing', 'login_post') ?>" class="form-horizontal">
                    <input type="hidden" name="redirect_to" value="<?php echo add_query_arg('registered', true); ?>">
                    <input type="hidden" name="user-cookie" value="1">
                    <div class="form-group">
                        <label for="user_login" class="col-sm-4 control-label"><?php _e('Username', 'ldd-directory-lite'); ?></label>
                        <div class="col-sm-8">
                            <input id="user_login" class="form-control" type="text" name="user_login">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="user_email" class="col-sm-4 control-label"><?php _e('Your Email', 'ldd-directory-lite'); ?></label>
                        <div class="col-sm-8">
                            <input id="user_email" class="form-control" type="email" name="user_email">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-9">
                            <button type="submit" class="btn btn-primary"><?php _e('Register', 'ldd-directory-lite'); ?></button>
                        </div>
                    </div>
                </form>
            <?php else: ?>
                <div class="alert alert-warning" role="alert">
                    <strong><?php _e('Sorry!', 'ldd-directory-lite'); ?></strong> <?php _e('User registration on this site is currently disabled.', 'ldd-directory-lite'); ?>
                </div>
            <?php endif; ?>

        </div>
        <div class="tab-pane" id="lost-password">

            <form method="post" action="<?php echo site_url('wp-login.php?action=lostpassword', 'login_post') ?>" class="form-horizontal">
                <input type="hidden" name="redirect_to" value="<?php echo add_query_arg('reset', true); ?>">
                <input type="hidden" name="user-cookie" value="1">
                <div class="form-group">
                    <label for="user_login" class="col-sm-3 control-label"><?php _e('Your Email', 'ldd-directory-lite'); ?></label>
                    <div class="col-sm-6">
                        <input id="user_login" class="form-control" type="text" name="user_login">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-9">
                        <button type="submit" class="btn btn-primary"><?php _e('Register', 'ldd-directory-lite'); ?></button>
                    </div>
                </div>
            </form>

        </div>
    </div>
    </div>
     <div class="col-lg-6">
<?php if(function_exists('lddsocialLoginLinks')){
    lddsocialLoginLinks();
}
?>
</div>
</div>
