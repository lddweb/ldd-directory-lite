
<div class="directory-lite edit-details">

    <?php ldl_get_header(); ?>

    <h2>Edit contact information for &ldquo;<?php echo ldl_get_value('title'); ?>&rdquo;</h2>

    <form id="submit-listing" name="submit-listing" method="post" enctype="multipart/form-data" novalidate>
        <input type="hidden" name="action" value="edit-contact">
        <?php echo wp_nonce_field('edit-contact', 'nonce_field', 0, 0); ?>

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label" for=""><?php _e('Email', 'ldd-directory-lite'); ?></label>
                        <input type="text" id="f_contact_email" class="form-control" name="n_contact_email" value="<?php echo ldl_get_value('contact_email'); ?>">
                        <?php echo ldl_get_error('contact_email'); ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label" for=""><?php _e('Phone', 'ldd-directory-lite'); ?></label>
                        <input type="text" id="f_contact_phone" class="form-control" name="n_contact_phone" value="<?php echo ldl_get_value('contact_phone'); ?>">
                        <?php echo ldl_get_error('contact_phone'); ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label" for=""><?php _e('Fax', 'ldd-directory-lite'); ?></label>
                        <input type="text" id="f_contact_fax" class="form-control" name="n_contact_fax" value="<?php echo ldl_get_value('contact_fax'); ?>">
                        <?php echo ldl_get_error('contact_fax'); ?>
                    </div>
                </div>
            </div>
        </div>

        <?php ldl_get_template_part('edit', 'submit'); ?>
    </form>

</div>
