
<div class="container-fluid">
	<div class="row bump-down">
		<div class="col-md-12">
			<p class="section"><?php _e('The following information will help generate more traffic for your organization, the more the better. Your email address is not publicly available, instead a contact form will be embedded with your listing.', 'lddlite'); ?></p>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				<label class="control-label" for=""><?php _e('Email', 'lddlite'); ?></label>
				<input type="text" id="f_contact_email" class="form-control" name="n_contact_email" value="<?php echo ldl_get_value('contact_email'); ?>">
                <?php echo ldl_get_error('postal_code'); ?>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				<label class="control-label" for=""><?php _e('Phone', 'lddlite'); ?></label>
				<input type="text" id="f_contact_phone" class="form-control" name="n_contact_phone" value="<?php echo ldl_get_value('contact_phone'); ?>">
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label class="control-label" for=""><?php _e('Fax', 'lddlite'); ?></label>
				<input type="text" id="f_contact_fax" class="form-control" name="n_contact_fax" value="<?php echo ldl_get_value('contact_fax'); ?>">
			</div>
		</div>
	</div>
	<div class="row bump-down">
		<div class="col-md-6">
			<div class="form-group">
				<label class="control-label" for=""><?php _e('Website', 'lddlite'); ?></label>
				<input type="text" id="f_url_website" class="form-control" name="n_url_website" value="<?php echo ldl_get_value( 'url_website' ); ?>">
				<p class="help-block"><?php _e("Examples include; 'http://www.yoursite.com', 'mysite.org'", 'lddlite'); ?></p>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label class="control-label" for=""><?php _e('Facebook', 'lddlite'); ?></label>
				<input type="text" id="f_url_facebook" class="form-control" name="n_url_facebook" value="<?php echo ldl_get_value( 'url_facebook' ); ?>">
				<p class="help-block"><?php _e('Help locating and customizing your <a href="https://www.facebook.com/help/www/329992603752372" title="Your Facebook Web Address | Facebook Help Center">Facebook profile URL</a>', 'lddlite'); ?></p>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label class="control-label" for=""><?php _e('Twitter', 'lddlite'); ?></label>
				<input type="text" id="f_url_twitter" class="form-control" name="n_url_twitter" value="<?php echo ldl_get_value( 'url_twitter' ); ?>">
				<p class="help-block"><?php _e("This will always be similar to 'https://twitter.com/<strong>username</strong>'", 'lddlite'); ?></p>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label class="control-label" for=""><?php _e('Linkedin', 'lddlite'); ?></label>
				<input type="text" id="f_url_linkedin" class="form-control" name="n_url_linkedin" value="<?php echo ldl_get_value( 'url_linkedin' ); ?>">
				<p class="help-block"><?php _e('Help locating and customizing your <a href="http://help.linkedin.com/app/answers/detail/a_id/85/~/promoting-your-public-profile" title="Promoting Your Public Profile | LinkedIn Help Center">LinkedIn profile URL</a>', 'lddlite'); ?></p>
			</div>
		</div>
	</div>
</div>

