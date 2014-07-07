<?php add_filter('wp_login_failed','something'); ?>
<div class="directory-lite">

    <?php ldl_get_header(); ?>

    <?php if (array_key_exists('registered', $_GET)): ?>
    <div class="alert alert-success" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <?php _e('A password has been sent to your email address. Thank you for registering!', 'lddlite'); ?>
    </div>
    <?php endif; ?>
    <?php if (array_key_exists('reset', $_GET)): ?>
    <div class="alert alert-warning" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <?php _e('An email with information on how to reset your password has been sent.', 'lddlite'); ?>
    </div>
    <?php endif; ?>


    <p>To submit a listing you must be logged in. Please log in, or register a new user account.</p>

    <ul class="nav nav-tabs" role="tablist">
        <li class="active"><a href="#login" role="tab" data-toggle="tab">Login</a></li>
        <li><a href="#register" role="tab" data-toggle="tab">Register</a></li>
        <li><a href="#lost-password" role="tab" data-toggle="tab">Lost Password</a></li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <div class="tab-pane active" id="login">

            <form method="post" action="<?php echo site_url('wp-login.php') ?>" class="form-horizontal">
                <input type="hidden" name="redirect_to" value="<?php echo $_SERVER['REQUEST_URI']; ?>">
                <input type="hidden" name="user-cookie" value="1">

                <div class="form-group">
                    <label for="user_login" class="col-sm-3 control-label">Username</label>
                    <div class="col-sm-6">
                        <input id="user_login" class="form-control" type="text" name="log">
                    </div>
                </div>
                <div class="form-group">
                    <label for="user_pass" class="col-sm-3 control-label">Password</label>
                    <div class="col-sm-6">
                        <input id="user_pass" class="form-control" type="password" name="pwd">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-9">
                        <div class="checkbox">
                            <label>
                                <input id="rememberme" type="checkbox" name="rememberme" value="forever"> Remember me
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-9">
                        <button type="submit" class="btn btn-default">Sign in</button>
                    </div>
                </div>
            </form>

        </div>
        <div class="tab-pane" id="register">

            <form method="post" action="<?php echo site_url('wp-login.php?action=register', 'login_post') ?>" class="form-horizontal">
                <input type="hidden" name="redirect_to" value="<?php echo add_query_arg('registered', true); ?>">
                <input type="hidden" name="user-cookie" value="1">
                <div class="form-group">
                    <label for="user_login" class="col-sm-3 control-label">Username</label>
                    <div class="col-sm-6">
                        <input id="user_login" class="form-control" type="text" name="user_login">
                    </div>
                </div>
                <div class="form-group">
                    <label for="user_email" class="col-sm-3 control-label">Your Email</label>
                    <div class="col-sm-6">
                        <input id="user_email" class="form-control" type="email" name="user_email">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-9">
                        <button type="submit" class="btn btn-default">Register</button>
                    </div>
                </div>
            </form>

        </div>
        <div class="tab-pane" id="lost-password">

            <form method="post" action="<?php echo site_url('wp-login.php?action=lostpassword', 'login_post') ?>" class="form-horizontal">
                <input type="hidden" name="redirect_to" value="<?php echo add_query_arg('reset', true); ?>">
                <input type="hidden" name="user-cookie" value="1">
                <div class="form-group">
                    <label for="user_login" class="col-sm-3 control-label">Your Email</label>
                    <div class="col-sm-6">
                        <input id="user_login" class="form-control" type="text" name="user_login">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-9">
                        <button type="submit" class="btn btn-default">Register</button>
                    </div>
                </div>
            </form>

        </div>
    </div>


</div>


<script>
jQuery("#sample-data").click(function(e) {
    e.preventDefault()

    var sampleData = {
        title: "Sample Listing",
        category: 9,
        description: "Curabitur sodales ligula in libero. Sed dignissim lacinia nunc. Curabitur tortor. Pellentesque nibh. Aenean quam. In scelerisque sem at dolor. Maecenas mattis. Sed convallis tristique sem.",
        summary: "Curabitur sodales ligula in libero. Sed dignissim lacinia nunc.",
        contact_email: "mark@watero.us",
        contact_phone: "505.123.4567",
        url_website: "mark.watero.us",
        url_facebook: "facebook.com/mwaterous",
        url_twitter: "markwaterous",
        geo: "450 Michelle Cir, Bernalillo NM 87004"
    }

    jQuery.each( sampleData, function( key, value) {
        jQuery('#' + key).val( value )
    })

})
</script>