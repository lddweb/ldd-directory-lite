<div class="directory-lite">

    <?php ldl_get_header(); ?>

    <?php if ( ldl_has_errors() ): ?>
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <strong>We're sorry!</strong> There were some errors with the information you provided. Please review your entries and try again.
        </div>
    <?php endif; ?>

    <?php if ( ldl_has_global_errors() ): while ( $error = ldl_get_global_errors() ): ?>
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <?php echo $error['value']; ?>
        </div>
    <?php endwhile; endif; ?>


    <form id="submit-listing" name="submit-listing" method="post" enctype="multipart/form-data" novalidate>
        <input type="hidden" name="action" value="submit_form">
        <?php echo wp_nonce_field( 'submit-listing-nonce','nonce_field', 0, 0 ); ?>
        <?php do_action( 'lddlite_submit_listing_hidden_fields' ); ?>

        <a id="sample-data" href="">Sample Data</a>

        <?php ldl_get_template_part( 'panel', 'general' ); ?>
        <?php ldl_get_template_part( 'panel', 'meta' ); ?>
        <?php ldl_get_template_part( 'panel', 'geography' ); ?>
        <?php if ( !is_user_logged_in() ): ?>
            <?php ldl_get_template_part( 'panel', 'account' ); ?>
        <?php endif; ?>

        <div class="container-fluid">
            <div class="row bump-down-more">
                <div class="col-md-12">
                    <p class="text-success">Please verify all information on this form before submitting. Your listing will not appear immediately as we review all submissions for accuracy and content, to ensure that listings fall within our terms of service.</p>
                    <?php ldl_the_tos(); ?>
                    <button type="submit" class="btn btn-primary">Submit Listing</button>
                </div>
            </div>
        </div>
    </form>

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