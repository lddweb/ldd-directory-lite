<div id="contact-form-wrap">
    <h3><?php _e('What is this in regards to?', 'ldd-directory-lite'); ?></h3>

    <form id="contact-form" method="post" novalidate>
        <?php wp_nonce_field( 'contact-form-nonce', 'nonce' ); ?>
        <input type="hidden" name="action" value="contact_form">
        <input type="hidden" name="post_id" value="<?php echo get_the_ID(); ?>">


        <div class="row bump-down">
            <div class="col-xs-12">
                <label for="senders_name" class="sr-only"><?php _e('What is this in regards to?', 'ldd-directory-lite'); ?></label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
                    <input id="senders_name" name="senders_name" type="text" class="form-control" placeholder="<?php _e('What is this in regards to?', 'ldd-directory-lite'); ?>" required>
                </div>
            </div>
        </div>
        <div class="row bump-down">
            <div class="col-xs-12">
                <label for="email" class="sr-only"><?php _e('What is this in regards to?', 'ldd-directory-lite'); ?></label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-envelope fa-fw"></i></span>
                    <input id="email" name="email" type="email" class="form-control" placeholder="<?php _e('What is this in regards to?', 'ldd-directory-lite'); ?>" required>
                </div>
            </div>
        </div>

        <div class="row bump-down">
            <div class="col-xs-12">
                <label for="subject" class="sr-only"><?php _e('What is this in regards to?', 'ldd-directory-lite'); ?></label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-bookmark fa-fw"></i></span>
                    <input id="subject" name="subject" type="text" class="form-control" placeholder="<?php _e('What is this in regards to?', 'ldd-directory-lite'); ?>" required>
                </div>
            </div>
        </div>
        <div class="row bump-down">
            <div class="col-xs-12">
                <label for="message" class="sr-only"><?php _e('Message', 'ldd-directory-lite'); ?></label>
                <textarea id="message" name="message" class="form-control" rows="4" placeholder="<?php _e('Enter your message here.', 'ldd-directory-lite'); ?>" required></textarea>
            </div>
        </div>
        <div class="row bump-down">
            <div class="col-xs-12">
                <label for="math" class="sr-only"><?php _e('What is 7 + seven?', 'ldd-directory-lite'); ?></label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-plus fa-fw"></i></span>
                    <input id="math" name="math" type="text" class="form-control" placeholder="<?php _e('What is seven &#43; s&#101;v&#101;n?', 'ldd-directory-lite'); ?>" required>
                </div>
            </div>
        </div>

        <button type="submit" id="contact-form-submit" class="btn btn-default btn-block bump-down"><?php _e('Send', 'ldd-directory-lite'); ?></button>

    </form>
</div>

<div id="contact-messages" class="bump-down">
    <div id="message-error" class="alert alert-danger" style="display:none;" role="alert"></div>
    <div id="message-success" class="alert alert-success" style="display:none;" role="alert"></div>
</div>

<script>
    jQuery(document).ready( function($) {
        $("#contact-form").isHappy({
            fields: {
                '#senders_name': {
                    required: true,
                    message: '<?php _e('Please enter a valid name.', 'ldd-directory-lite'); ?>',
                    test: happy.minLength,
                    arg: 3
                },
                '#email': {
                    required: true,
                    message: '<?php _e('Please enter a valid email address.', 'ldd-directory-lite'); ?>',
                    test: happy.email
                },
                '#subject': {
                    required: true,
                    message: '<?php _e('Please enter a valid subject.', 'ldd-directory-lite'); ?>',
                    test: happy.minLength,
                    arg: 6
                },
                '#message': {
                    required: true,
                    message: '<?php _e('Please enter a longer message.', 'ldd-directory-lite'); ?>',
                    test: happy.minLength,
                    arg: 10
                },
                '#math': {
                    required: true,
                    message: "<?php _e("That doesn't appear correct.", 'ldd-directory-lite'); ?>",
                    test: happy.math
                }
            }
        })
    })
</script>