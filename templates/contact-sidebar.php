
<h3>Send a Message</h3>

<form id="contact-form" method="post" novalidate>
    <?php wp_nonce_field( 'contact-form-nonce', 'nonce' ); ?>
    <input type="hidden" name="action" value="contact_form">

    <div class="row bump-down">
        <div class="col-xs-12">
            <label for="senders_name" class="sr-only">Your Name</label>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
                <input id="senders_name" name="first_name" type="text" class="form-control" placeholder="Your name..." required>
            </div>
        </div>
    </div>
    <div class="row bump-down">
        <div class="col-xs-12">
            <label for="email" class="sr-only">Your Email Address</label>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-envelope fa-fw"></i></span>
                <input id="email" name="email" type="email" class="form-control" placeholder="Your email..." required>
            </div>
        </div>
    </div>

    <div class="row bump-down">
        <div class="col-xs-12">
            <label for="subject" class="sr-only">Message Subject</label>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-bookmark fa-fw"></i></span>
                <input id="subject" name="subject" type="text" class="form-control" placeholder="What is this in regards to?" required>
            </div>
        </div>
    </div>
    <div class="row bump-down">
        <div class="col-xs-12">
            <label for="message" class="sr-only">Message</label>
            <textarea id="message" name="message" class="form-control" rows="4" required></textarea>
        </div>
    </div>
    <div class="row bump-down">
        <div class="col-xs-12">
            <label for="senders_name" class="sr-only">What is 7 + seven?</label>
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

        $form.isHappy({
            fields: {
                '#senders_name': {
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
            },
            happy: function(e) {

                $form.hide();
                return false;
            }
        });


    });
</script>