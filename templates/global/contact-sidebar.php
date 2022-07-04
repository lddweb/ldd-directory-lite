<div id="contact-form-wrap" class="ldd-rightbar-single">
<h3><?php echo sprintf(__( 'Contact %s', 'ldd-directory-lite' ), get_the_title( get_the_ID() ) ); ?> now... </h3>

<form id="contact-form" method="post" novalidate class='ldd_contact_form'>
    <?php wp_nonce_field( 'contact-form-nonce', 'nonce' ); ?>
    <input type="hidden" name="action" value="contact_form">
    <input type="hidden" name="post_id" value="<?php echo get_the_ID(); ?>">


    <div class="row bump-down">
        <div class="col-xs-12">
            <label for="senders_name" class="sr-only"><?php _e('Your Name', 'ldd-directory-lite'); ?></label>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
                <input id="senders_name" name="senders_name" type="text" class="form-control" placeholder="<?php _e('Your Name', 'ldd-directory-lite'); ?>" required>
            </div>
        </div>
    </div>
    <div class="row bump-down">
        <div class="col-xs-12">
            <label for="email" class="sr-only"><?php _e('Email Address', 'ldd-directory-lite'); ?></label>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-envelope fa-fw"></i></span>
                <input id="email" name="email" type="email" class="form-control" placeholder="<?php _e('Email Address', 'ldd-directory-lite'); ?>" required>
            </div>
        </div>
    </div>

    <div class="row bump-down">
        <div class="col-xs-12">
            <label for="subject" class="sr-only"><?php _e('Subject', 'ldd-directory-lite'); ?></label>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-bookmark fa-fw"></i></span>
                <input id="subject" name="subject" type="text" class="form-control" placeholder="<?php _e('Subject', 'ldd-directory-lite'); ?>" required>
            </div>
        </div>
    </div>
    <div class="row bump-down">
        <div class="col-xs-12">
            <label for="message" class="sr-only"><?php _e('Message', 'ldd-directory-lite'); ?></label>
            <textarea id="message" name="message" class="form-control" rows="4" placeholder="<?php _e('Enter your message here.', 'ldd-directory-lite'); ?>" required></textarea>
        </div>
    </div>
    
       <?php if(ldl()->get_option('google_recaptcha_site')):?>
    <div class="row bump-down">
        <div class="col-xs-12">
            
            <span class="msg-error unhappyMessage"></span>
    <div class="g-recaptcha" data-sitekey="<?php echo ldl()->get_option('google_recaptcha_site');?>"></div>
      </div>
        </div>
        
        
        <?php endif;?>
    <button type="submit" id="contact-form-submit" class="btn btn-default btn-block bump-down"><?php _e('Send', 'ldd-directory-lite'); ?></button>

</form>
</div>

<div id="contact-messages" class="bump-down">
<div id="message-error" class="alert alert-danger" style="display:none;" role="alert"></div>
<div id="message-success" class="alert alert-success" style="display:none;" role="alert"></div>
</div>

<?php if(ldl()->get_option('google_recaptcha_site')):?>
<script>
            jQuery( '#contact-form-submit' ).click(function(event){
    
var $captcha = jQuery( '#recaptcha' ),
  response = grecaptcha.getResponse();

if (response.length === 0) {
  event.preventDefault();
jQuery( '.msg-error').text( "reCAPTCHA is mandatory" );
if( !$captcha.hasClass( "unhappyMessage" ) ){
  $captcha.addClass( "unhappyMessage" );
}
} else {
jQuery( '.msg-error' ).text('');
$captcha.removeClass( "unhappyMessage" );

}
})

        </script>
<?php endif;?>


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
                message: '<?php _e('Please enter a longer message..', 'ldd-directory-lite'); ?>',
                test: happy.minLength,
                arg: 10
            },
            
            
            
        }
    })
})




</script>