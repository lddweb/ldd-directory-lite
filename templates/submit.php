<div class="directory-lite">

    <?php ldl_get_header(); ?>

    <?php if ( ldl_has_errors() ): ?>
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <strong>We're sorry!</strong> There were some errors with the information you provided. <strong>Errors are marked in red.</strong>
        </div>
    <?php endif; ?>

    <?php if ( ldl_has_global_errors() ): while ( $error = ldl_get_global_errors() ): ?>
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <?php echo $error['value']; ?>
        </div>
    <?php endwhile; endif; ?>


    <form id="submit-listing" name="submit-listing" method="post" enctype="multipart/form-data">
        <input type="hidden" name="action" value="submit_form">
        <?php echo wp_nonce_field( 'submit-listing-nonce','nonce_field', 0, 0 ); ?>
        <?php do_action( 'lddlite_submit_listing_hidden_fields' ); ?>

        <!-- TESTING ONLY -->
        <a id="sample-data" href="" style="padding:.5em;background:#fff;position:fixed;top:50px;left:20px;">Sample Data</a>

        <?php ldl_get_template_part( 'panel', 'general' ); ?>
        <?php ldl_get_template_part( 'panel', 'meta' ); ?>
        <?php ldl_get_template_part( 'panel', 'geography' ); ?>

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
        f_title: "Sample Listing",
        f_category: 143,
        f_description: "Curabitur sodales ligula in libero. Sed dignissim lacinia nunc. Curabitur tortor. Pellentesque nibh. Aenean quam. In scelerisque sem at dolor. Maecenas mattis. Sed convallis tristique sem.",
        f_summary: "Curabitur sodales ligula in libero. Sed dignissim lacinia nunc.",
        f_contact_email: "mark@watero.us",
        f_contact_phone: "505.123.4567",
        f_url_website: "http://mark.watero.us",
        f_url_facebook: "facebook.com/mwaterous",
        f_url_twitter: "markwaterous",
        f_address_one: "450 Michelle Cir",
        f_address_two: "Bernalillo NM",
        f_postal_code: "87004",
        f_country: "United States",
        geo: "450 Michelle Cir",
    }

    jQuery.each( sampleData, function( key, value) {
        jQuery('#' + key).val( value )
    })

})
</script>