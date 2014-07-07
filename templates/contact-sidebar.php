
<h3><?php _e('Send a Message', 'lddlite'); ?></h3>

<form id="contact-form" method="post" novalidate>
    <?php wp_nonce_field( 'contact-form-nonce', 'nonce' ); ?>
    <input type="hidden" name="action" value="contact_form">

    <div class="row bump-down">
        <div class="col-xs-12">
            <label for="senders_name" class="sr-only"><?php _e('Your Name', 'lddlite'); ?></label>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
                <input id="senders_name" name="first_name" type="text" class="form-control" placeholder="<?php _e('Your name...', 'lddlite'); ?>" required>
            </div>
        </div>
    </div>
    <div class="row bump-down">
        <div class="col-xs-12">
            <label for="email" class="sr-only"><?php _e('Your Email Address', 'lddlite'); ?></label>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-envelope fa-fw"></i></span>
                <input id="email" name="email" type="email" class="form-control" placeholder="<?php _e('Your email...', 'lddlite'); ?>" required>
            </div>
        </div>
    </div>

    <div class="row bump-down">
        <div class="col-xs-12">
            <label for="subject" class="sr-only"><?php _e('Message Subject', 'lddlite'); ?></label>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-bookmark fa-fw"></i></span>
                <input id="subject" name="subject" type="text" class="form-control" placeholder="<?php _e('What is this in regards to?', 'lddlite'); ?>" required>
            </div>
        </div>
    </div>
    <div class="row bump-down">
        <div class="col-xs-12">
            <label for="message" class="sr-only"><?php _e('Message', 'lddlite'); ?></label>
            <textarea id="message" name="message" class="form-control" rows="4" required></textarea>
        </div>
    </div>
    <div class="row bump-down">
        <div class="col-xs-12">
            <label for="senders_name" class="sr-only"><?php _e('What is 7 + seven?', 'lddlite'); ?></label>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-plus fa-fw"></i></span>
                <input id="other_name" name="other_name" type="text" class="form-control" placeholder="<?php _e('What is seven &#43; s&#101;v&#101;n?', 'lddlite'); ?>" required>
            </div>
        </div>
    </div>

    <button type="submit" id="contact-form-submit" class="btn btn-default btn-block bump-down"><?php _e('Send', 'lddlite'); ?></button>

</form>



<script>
    jQuery(document).ready( function($) {
        var $form = $("#contact-form")

        $form.isHappy({
            fields: {
                '#senders_name': {
                    required: true,
                    message: '<?php _e('Your name is required.', 'lddlite'); ?>'
                },
                '#email': {
                    required: true,
                    message: '<?php _e('Please enter a valid email address.', 'lddlite'); ?>',
                    test: happy.email
                },
                '#subject': {
                    required: true,
                    message: '<?php _e('Please enter a subject.', 'lddlite'); ?>',
                },
                '#message': {
                    required: true,
                    message: '<?php _e('Please enter a message.', 'lddlite'); ?>',
                },
                '#other_name': {
                    required: true,
                    message: '<?php _e("That doesn't appear correct.", 'lddlite'); ?>',
                    test: happy.math
                }
            },
            happy: function(e) {
                $form.hide()
                return false
            }
        })

    })
</script>