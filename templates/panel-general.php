<div class="container-fluid">
	<?php if (ldl_get_setting('submit_intro')): ?>
    <div class="row">
		<div class="col-md-12">
            <?php echo wpautop(ldl_get_setting('submit_intro')); ?>
		</div>
	</div>
    <?php endif; ?>
	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				<label class="control-label" for="f_title"><?php _e('Title', 'lddlite'); ?></label>
				<input type="text" id="f_title" class="form-control" name="n_title" value="<?php echo ldl_get_value('title'); ?>" required>
				<?php echo ldl_get_error('title'); ?>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label class="control-label" for="f_category"><?php _e('Category', 'lddlite'); ?></label>
				<?php ldl_submit_categories_dropdown( ldl_get_value('category'), 'category' ); ?>
				<?php echo ldl_get_error('category'); ?>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<label class="control-label" for="f_logo"><?php _e('Logo', 'lddlite'); ?></label>
				<input type="file" id="f_logo" class="form-control" name="n_logo">
				<?php echo ldl_get_error('category'); ?>
			</div>
		</div>
	</div>
	<div class="row bump-down">
		<div class="col-md-12">
			<div class="form-group">
				<label class="control-label" for="f_description"><?php _e('Description', 'lddlite'); ?></label>
				<textarea id="f_description" class="form-control" name="n_description" rows="5" required><?php echo ldl_get_value('description'); ?></textarea>
				<?php echo ldl_get_error('description'); ?>
				<p class="help-block"><?php printf(__('The description you include here will make up a major portion of your listing when viewed individually. You may use <a href="%s">markdown</a> to format your description, though we reserve the right to remove excess formatting before approving your listing.', 'lddlite'), 'https://help.github.com/articles/markdown-basics'); ?></p>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<label class="control-label" for="f_summary"><?php _e('Summary', 'lddlite'); ?></label>
				<input type="text" id="f_summary" class="form-control" name="n_summary" value="<?php echo ldl_get_value('summary'); ?>" required>
				<?php echo ldl_get_error('summary'); ?>
				<p class="help-block"><?php _e('Please provide a short summary of your listing that will appear in search results.', 'lddlite'); ?></p>
			</div>
		</div>
	</div>
</div>
