
<div class="container-fluid">
	<div class="row bump-down">
		<div class="col-md-12">
			<p class="section">Include as much of the following as you would like to be publicly available. Your email address will not be displayed; if it is provided, a contact form will be embedded with your listing.</p>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<label class="control-label" for="">Email</label>
				<input type="text" id="contact_email" class="form-control" name="ld_s_contact_email" <?php if ( !empty( $data['contact_email'] ) ) { echo 'value="' . $data['contact_email'] . '" '; } ?> required>
				<?php if ( !empty( $errors['contact_email'] ) ) { echo $errors['contact_email']; } ?>
				<p class="help-block">Including an email address will embed a contact form in your listings public page.</p>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				<label class="control-label" for="">Phone</label>
				<input type="text" id="contact_phone" class="form-control" name="ld_s_contact_phone" <?php if ( !empty( $data['contact_phone'] ) ) { echo 'value="' . $data['contact_phone'] . '" '; } ?> required>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label class="control-label" for="">Fax</label>
				<input type="text" id="contact_phone" class="form-control" name="ld_s_contact_fax" <?php if ( !empty( $data['contact_fax'] ) ) { echo 'value="' . $data['contact_phone'] . '" '; } ?> required>
			</div>
		</div>
	</div>
	<div class="row bump-down">
		<div class="col-md-12">
			<div class="form-group">
				<label class="control-label" for="">Website</label>
				<input type="text" id="url_website" class="form-control" name="ld_s_url_website" value="<?php echo ldl_get_value( 'url_website' ); ?>">
				<p class="help-block">Examples include; 'http://www.yoursite.com', 'mysite.org'</p>
			</div>
		</div>
		<div class="col-md-12">
			<div class="form-group">
				<label class="control-label" for="">Facebook</label>
				<input type="text" id="url_facebook" class="form-control" name="ld_s_url_facebook" value="<?php echo ldl_get_value( 'url_facebook' ); ?>">
				<p class="help-block">Help locating and customizing your <a href="https://www.facebook.com/help/www/329992603752372" title="Your Facebook Web Address | Facebook Help Center">Facebook profile URL</a></p>
			</div>
		</div>
		<div class="col-md-12">
			<div class="form-group">
				<label class="control-label" for="">Twitter</label>
				<input type="text" id="url_twitter" class="form-control" name="ld_s_url_twitter" value="<?php echo ldl_get_value( 'url_twitter' ); ?>">
				<p class="help-block">This will always be similar to 'https://twitter.com/<strong>username</strong>'</p>
			</div>
		</div>
		<div class="col-md-12">
			<div class="form-group">
				<label class="control-label" for="">Linkedin</label>
				<input type="text" id="url_linkedin" class="form-control" name="ld_s_url_linkedin" value="<?php echo ldl_get_value( 'url_linkedin' ); ?>">
				<p class="help-block">Help locating and customizing your <a href="http://help.linkedin.com/app/answers/detail/a_id/85/~/promoting-your-public-profile" title="Promoting Your Public Profile | LinkedIn Help Center">LinkedIn profile URL</a></p>
			</div>
		</div>
	</div>
</div>

