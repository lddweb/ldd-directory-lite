
<div class="directory-lite edit-details">

    <?php ldl_get_header(); ?>

    <h2><?php printf( __( 'Edit contact information for &ldquo;%s&rdquo;', 'ldd-directory-lite' ), ldl_get_value('title') ); ?></h2>

    <form id="submit-listing" name="submit-listing" method="post" enctype="multipart/form-data" novalidate>
        <input type="hidden" name="action" value="edit-contact">
        <?php echo wp_nonce_field('edit-contact', 'nonce_field', 0, 0); ?>

        <div class="container-fluid">
            <div class="row">
	            <div class="col-md-6">
		            <div class="form-group">
			            <label class="control-label" for=""><?php _e('Contact Name', 'ldd-directory-lite'); ?></label>
			            <input type="text" id="f_contact_name" class="form-control" name="n_contact_name" value="<?php echo ldl_get_value('contact_name'); ?>">
			            <p class="help-block"><?php _e("Name of person to contact", 'ldd-directory-lite'); ?></p>
			            <?php echo ldl_get_error('contact_name'); ?>
		            </div>
	            </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label" for=""><?php _e('Email', 'ldd-directory-lite'); ?></label>
                        <input type="text" id="f_contact_email" class="form-control" name="n_contact_email" value="<?php echo ldl_get_value('contact_email'); ?>">
                        <?php echo ldl_get_error('contact_email'); ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label" for=""><?php _e('Phone', 'ldd-directory-lite'); ?></label>
                        <input type="text" id="f_contact_phone" class="form-control" name="n_contact_phone" value="<?php echo ldl_get_value('contact_phone'); ?>">
                        <?php echo ldl_get_error('contact_phone'); ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label" for=""><?php _e('Fax', 'ldd-directory-lite'); ?></label>
                        <input type="text" id="f_contact_fax" class="form-control" name="n_contact_fax" value="<?php echo ldl_get_value('contact_fax'); ?>">
                        <?php echo ldl_get_error('contact_fax'); ?>
                    </div>
                </div>
            </div>
	        <div class="row">
		        <div class="col-md-6">
			        <div class="form-group">
				        <label class="control-label" for=""><?php _e('Skype', 'ldd-directory-lite'); ?></label>
				        <input type="text" id="f_contact_skype" class="form-control" name="n_contact_skype" value="<?php echo ldl_get_value('contact_skype'); ?>">
				        <p class="help-block"><?php _e("Your Skype Username", 'ldd-directory-lite'); ?></p>
				        <?php echo ldl_get_error('contact_skype'); ?>
			        </div>
		        </div>
	        </div>
        </div>

        <?php ldl_get_template_part('frontend/edit', 'submit'); ?>
    </form>

</div>
