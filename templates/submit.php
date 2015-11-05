<div class="directory-lite">

    <?php ldl_get_header(); ?>

    <?php if ( ldl_has_errors() ): ?>
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <strong><?php _e("We're sorry!", 'ldd-directory-lite'); ?></strong> <?php _e('There were some errors with the information you provided. Please check your submission and try again.', 'ldd-directory-lite'); ?><br>
        </div>
    <?php endif; ?>

    <?php if ( ldl_has_global_errors() ): while ( $error = ldl_get_global_errors() ): ?>
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <?php echo $error['value']; ?>
        </div>
    <?php endwhile; endif; ?>


    <form id="submit-listing" name="submit-listing" method="post" enctype="multipart/form-data" novalidate>
        <input type="hidden" name="action" value="submit-listing">
        <?php echo wp_nonce_field( 'submit-listing','nonce_field', 0, 0 ); ?>
        <?php do_action( 'lddlite_submit_listing_hidden_fields' ); ?>

        <?php ldl_get_template_part( 'panel', 'general' ); ?>
        <?php ldl_get_template_part( 'panel', 'meta' ); ?>
        <?php ldl_get_template_part( 'panel', 'geography' ); ?>

        <div class="container-fluid">
            <div class="row bump-down-more">
                <div class="col-md-12">
                    <p class="text-success"><?php _e('Please verify all information on this form before submitting. Your listing will not appear immediately as we review all submissions for accuracy and content, to ensure that listings fall within our terms of service.', 'ldd-directory-lite'); ?></p>
                    <?php ldl_the_tos(); ?>
                    <button type="submit" class="btn btn-primary"><?php _e('Submit Listing', 'ldd-directory-lite'); ?></button>
                </div>
            </div>
        </div>
    </form>

</div>
