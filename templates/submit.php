<section class="directory-lite directory-submit">

    <header class="directory-header">
        <?php ldl_get_header(); ?>
    </header>

    <section class="directory-content">

        <?php if ( !empty( $errors ) ): ?>
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <strong>Oops!</strong> Panels marked in red have errors that need to be fixed before submitting.
            </div>
        <?php endif; ?>


        <?php if ( $success ): ?>
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h4>Thank you for your submission!</h4>
            <p>Your listing is awaiting approval. Please allow time for us to review the accuracy of your content before it appears publicly in our directory.</p>
            <p><a class="btn btn-success" href="<?php echo ldl_get_home_url(); ?>">View Other Listings</a></p>
        </div>
        <?php endif; ?>


        <form id="submit-listing" name="submit-listing" method="post" enctype="multipart/form-data" novalidate>
            <input type="hidden" name="action" value="submit_form">
            <?php echo wp_nonce_field( 'submit-listing-nonce','nonce_field', 1, 0 ); ?>

            <?php ldl_get_template_part( 'panel', 'general' ); ?>
            <?php ldl_get_template_part( 'panel', 'geography' ); ?>
            <?php ldl_get_template_part( 'panel', 'urls' ); ?>
            <?php if ( !is_user_logged_in() ): ?>
                <?php ldl_get_template_part( 'panel', 'account' ); ?>
            <?php endif; ?>


            <div class="submit-form-wrap submit-confirm">
                <fieldset>
                    <legend>Confirm</legend>
                    <p>Please verify all information on this form before submitting. Your listing will not appear immediately as we review all submissions for accuracy and content, to ensure that listings fall within our terms of service.</p>
                    <?php if ( ldl_get_setting( 'submit_use_tos' ) ): ?><p>By submitting, you agree your listing abides by <a href="#" data-toggle="modal" data-target="#tos-modal">our terms of service</a>.</p><?php endif; ?>
                    <button type="submit" id="submit-form-submit" class="btn btn-success"><i class="fa fa-cog"></i> Submit for Review</button>
                </fieldset>
            </div>
        </form>

    </section>

</section>

<style>
    .directory-lite #submit-items ul li.ldd-submit-listing_here a.submit-error,
    .directory-lite #submit-items ul li a.submit-error:hover {
        background-color: #da4453;
        color: #fff;
    }
</style>


<?php
/*    if ( ldl_get_setting( 'submit_use_tos' ) )
        ldl_get_template_part( 'modal', 'tos' );*/
?>