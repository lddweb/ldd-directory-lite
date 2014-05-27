<div class="modal fade" id="contact-listing-owner" tabindex="-1" role="dialog" aria-labelledby="contact-listing-ownerLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="directory-lite modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 id="contact-modal-title" class="modal-title">Contact</h4>
            </div>
            <form id="contact-form" action="{$ajaxurl}" method="POST" data-async data-target="#contact-listing-body" novalidate>
                <input type="hidden" name="nonce" value="{$nonce}">
                <input type="hidden" name="action" value="contact_form">
                <input type="hidden" name="post_id" value="{$post_id}">


            <div class="modal-body" id="contact-listing-body">

                <p>We appreciate your interest! Please get in touch about any questions or comments you may have.</p>


                <div class="row bump-down">
                    <div class="col-xs-6">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                            <input id="first_name" name="first_name" type="text" class="form-control" placeholder="Your name..." required>
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                            <input id="email" name="email" type="email" class="form-control" placeholder="Your email..." required>
                        </div>
                    </div>
                    <div id="col-xs-h">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                            <input id="last_name" name="last_name" type="text" class="form-control" placeholder="Don't fill this out..." required>
                        </div>
                    </div>
                </div>
                <div class="row bump-down">
                    <div class="col-xs-12">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-bookmark"></i></span>
                            <input id="subject" name="subject" type="text" class="form-control" placeholder="What is this in regards to?" required>
                        </div>
                    </div>
                </div>
                <div class="row bump-down">
                    <div class="col-xs-12">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                            <textarea id="message" name="message" class="form-control" rows="4" required></textarea>
                        </div>
                    </div>
                </div>
                <div class="row bump-down">
                    <div class="col-xs-6">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-plus"></i></span>
                            <input id="other_name" name="other_name" type="text" class="form-control" placeholder="What is seven &#43; s&#101;v&#101;n?" required>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" id="contact-form-submit" class="btn btn-success">Send</button>
            </div>
            </form>
        </div>
    </div>
</div>


<script>

    jQuery(document).ready( function($) {
        var $form = $("#contact-form")
        var $target = $("#contact-listing-body")

        $("#col-xs-h").hide();

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