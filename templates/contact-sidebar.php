
<h3>Send a Message</h3>

<form id="contact-form" action="<?php echo admin_url( 'admin-ajax.php' ); ?>" method="post">
    <?php wp_nonce_field( 'contact-form-nonce', 'nonce' ); ?>
    <input type="hidden" name="action" value="contact_form">

    <div class="row bump-down">
        <div class="col-xs-12">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
                <input id="first_name" name="first_name" type="text" class="form-control" placeholder="Your name..." required>
            </div>
        </div>
    </div>
    <div class="row bump-down">
        <div class="col-xs-12">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-envelope fa-fw"></i></span>
                <input id="email" name="email" type="email" class="form-control" placeholder="Your email..." required>
            </div>
        </div>
    </div>

    <div class="row bump-down">
        <div class="col-xs-12">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-bookmark fa-fw"></i></span>
                <input id="subject" name="subject" type="text" class="form-control" placeholder="What is this in regards to?" required>
            </div>
        </div>
    </div>
    <div class="row bump-down">
        <div class="col-xs-12">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                <textarea id="message" name="message" class="form-control" rows="4" required></textarea>
            </div>
        </div>
    </div>
    <div class="row bump-down">
        <div class="col-xs-12">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-plus fa-fw"></i></span>
                <input id="other_name" name="other_name" type="text" class="form-control" placeholder="What is seven &#43; s&#101;v&#101;n?" required>
            </div>
        </div>
    </div>

    <button type="submit" id="contact-form-submit" class="btn btn-default btn-block bump-down">Send</button>

</form>



<script>

    jQuery(document).ready( function($) {
        var $form = $("#contact-form")
        var $target = $("#contact-listing-body")

        $form.isHappy({
            fields: {
                '#first_name': {
                    required: true,
                    message: 'Your name is required.'
                },
                '#email': {
                    required: true,
                    message: 'Please enter a valid email address.',
                    test: happy.email
                },
                '#subject': {
                    required: true,
                    message: 'Please enter a subject.'
                },
                '#message': {
                    required: true,
                    message: 'Please enter a message.'
                },
                '#other_name': {
                    required: true,
                    message: "That doesn't appear correct.",
                    test: happy.math
                }
            }
        });

        $form.submit( function(event) {
            event.preventDefault()

            $.ajax({
                type: $form.attr('method'),
                url: $form.attr('action'),
                data: $form.serialize(),

                success: function(data, status) {
                    var $response = $.parseJSON( data )
                    if ( $response.success && $response.msg ) {
                        $("#contact-form-submit").hide()
                        $target.html( $response.msg )

                    }

                }
            })
        })

    });
</script>