<?php
/*
* File version: 2
*/
?>
<div class="directory-lite edit-details bootstrap-wrapper">

    <?php ldl_get_header(); ?>

    <h2><?php printf( __( 'Edit URLs for &ldquo;%s&rdquo;', 'ldd-directory-lite' ), ldl_get_value('title') ); ?></h2>

    <form id="submit-listing" name="submit-listing" method="post" enctype="multipart/form-data" novalidate>
        <input type="hidden" name="action" value="edit-social">
        <?php echo wp_nonce_field('edit-social', 'nonce_field', 0, 0); ?>

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label" for=""><?php _e('Website', 'ldd-directory-lite'); ?></label>
                        <input type="text" id="f_url_website" class="form-control" name="n_url_website" value="<?php echo ldl_get_value( 'url_website' ); ?>">
                        <p class="help-block"><?php _e("Examples include; 'http://www.yoursite.com', 'mysite.org'", 'ldd-directory-lite'); ?></p>
                        <?php echo ldl_get_error('url_website'); ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label" for=""><?php _e('Facebook', 'ldd-directory-lite'); ?></label>
                        <input type="text" id="f_url_facebook" class="form-control" name="n_url_facebook" value="<?php echo ldl_get_value( 'url_facebook' ); ?>">
                        <p class="help-block"><?php _e('Help locating and customizing your <a href="https://www.facebook.com/help/www/329992603752372" title="Your Facebook Web Address | Facebook Help Center">Facebook profile URL</a>', 'ldd-directory-lite'); ?></p>
                        <?php echo ldl_get_error('url_facebook'); ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label" for=""><?php _e('Twitter', 'ldd-directory-lite'); ?></label>
                        <input type="text" id="f_url_twitter" class="form-control" name="n_url_twitter" value="<?php echo ldl_get_value( 'url_twitter' ); ?>">
                        <p class="help-block"><?php _e("This will always be similar to 'https://twitter.com/<strong>username</strong>'", 'ldd-directory-lite'); ?></p>
                        <?php echo ldl_get_error('url_twitter'); ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label" for=""><?php _e('Linkedin', 'ldd-directory-lite'); ?></label>
                        <input type="text" id="f_url_linkedin" class="form-control" name="n_url_linkedin" value="<?php echo ldl_get_value( 'url_linkedin' ); ?>">
                        <p class="help-block"><?php _e('Help locating and customizing your <a href="http://help.linkedin.com/app/answers/detail/a_id/85/~/promoting-your-public-profile" title="Promoting Your Public Profile | LinkedIn Help Center">LinkedIn profile URL</a>', 'ldd-directory-lite'); ?></p>
                        <?php echo ldl_get_error('url_linkedin'); ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label" for=""><?php _e('Google +', 'ldd-directory-lite'); ?></label>
                        <input type="text" id="f_url_googleplus" class="form-control" name="n_url_googleplus" value="<?php echo ldl_get_value( 'url_linkedin' ); ?>">
                        <p class="help-block"><?php _e('https://plus.google.com', 'ldd-directory-lite'); ?></p>
                        <?php echo ldl_get_error('url_googleplus'); ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label" for=""><?php _e('Instagram', 'ldd-directory-lite'); ?></label>
                        <input type="text" id="f_url_instagram" class="form-control" name="n_url_instagram" value="<?php echo ldl_get_value( 'url_linkedin' ); ?>">
                        <p class="help-block"><?php _e('https://www.instagram.com/?hl=en', 'ldd-directory-lite'); ?></p>
                        <?php echo ldl_get_error('url_instagram'); ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label" for=""><?php _e('YouTube', 'ldd-directory-lite'); ?></label>
                        <input type="text" id="f_url_youtube" class="form-control" name="n_url_youtube" value="<?php echo ldl_get_value( 'url_linkedin' ); ?>">
                        <p class="help-block"><?php _e('https://www.youtube.com/', 'ldd-directory-lite'); ?></p>
                        <?php echo ldl_get_error('url_youtube'); ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label" for=""><?php _e('Custom Link', 'ldd-directory-lite'); ?></label>
                        <input type="text" id="f_url_custom" class="form-control" name="n_url_custom" value="<?php echo ldl_get_value( 'url_linkedin' ); ?>">
                        <p class="help-block"><?php _e('www.yourdomain.com', 'ldd-directory-lite'); ?></p>
                        <?php echo ldl_get_error('url_custom'); ?>
                    </div>
                </div>
                
            </div>
        </div>

        <?php ldl_get_template_part('frontend/edit', 'submit'); ?>
    </form>

</div>
