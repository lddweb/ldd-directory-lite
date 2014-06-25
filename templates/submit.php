<section class="directory-lite directory-submit">

    <header class="directory-header">
        <?php ldl_get_header(); ?>

        <div class="row">
            <div class="col-md-12">
                <ol class="l-breadcrumb" style="margin-bottom: 1em;">
                    <li><a href="<?php echo ldl_get_home_url(); ?>"><i class="fa fa-home"></i> Home</a></li>
                    <li class="active"><span>Submit a Listing</span></li>
                </ol>
            </div>
        </div>
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
            <input type="hidden" name="__T__action" value="submit_form">
            <?php echo wp_nonce_field( 'submit-listing-nonce','nonce_field', 1, 0 ); ?>

            <div id="submit-items"></div>

            <div class="submit-form-wrap">
                <ul id="submit-panels">
                    <li><?php ldl_get_template_part( 'panel', 'general' ); ?></li>
                    <li><?php ldl_get_template_part( 'panel', 'geography' ); ?></li>
                    <li><?php ldl_get_template_part( 'panel', 'urls' ); ?></li>
                    <?php if ( !is_user_logged_in() ): ?>
                        <li><?php ldl_get_template_part( 'panel', 'account' ); ?></li>
                    <?php endif; ?>
                </ul>
            </div>

            <div class="submit-form-wrap submit-confirm" style="display: none;">
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