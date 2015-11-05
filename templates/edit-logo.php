
<div class="directory-lite edit-logo">

    <?php ldl_get_header(); ?>

    <h2>Update logo for &ldquo;<?php echo ldl_get_value('title'); ?>&rdquo;</h2>

    <form id="submit-listing" name="submit-listing" method="post" enctype="multipart/form-data" novalidate>
        <input type="hidden" name="action" value="edit-logo">
        <?php echo wp_nonce_field('edit-logo', 'nonce_field', 0, 0); ?>

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4">
                    <strong>Current Logo:</strong>
                    <?php echo ldl_get_value('thumb'); ?>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <label class="control-label" for="f_logo"><?php _e('Select New Logo:', 'ldd-directory-lite'); ?></label>
                        <input type="file" id="f_logo" class="form-control" name="n_logo">
                        <?php echo ldl_get_error('category'); ?>
                    </div>
                </div>
            </div>
        </div>

        <?php ldl_get_template_part('edit', 'submit'); ?>
    </form>

</div>
