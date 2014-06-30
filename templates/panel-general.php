<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<p>Please tell us a little bit about the organization you would like to see listed in our directory. Try to include as much information as you can, and be as descriptive as possible where asked.</p>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				<label class="control-label" for="title">Title</label>
				<input type="text" id="title" class="form-control" name="ld_s_title" value="<?php echo ldl_get_value( 'title' ); ?>" required>
				<?php echo ldl_get_error( 'title' ); ?>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label class="control-label" for="category">Category</label>
				<?php ldl_submit_categories_dropdown( ldl_get_value( 'category' ), 'category' ); ?>
				<?php echo ldl_get_error( 'category' ); ?>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<label class="control-label" for="submit-logo">Logo</label>
				<input type="file" id="submit-logo" class="form-control" name="ld_s_logo">
				<?php echo ldl_get_error( 'category' ); ?>
			</div>
		</div>
	</div>
	<div class="row bump-down">
		<div class="col-md-12">
			<div class="form-group">
				<label class="control-label" for="">Description</label>
				<textarea id="description" class="form-control" name="ld_s_description" rows="5" required><?php echo ldl_get_value( 'description' ); ?></textarea>
				<?php echo ldl_get_error( 'description' ); ?>
				<p class="help-block">The description you include here will make up a major portion of your listing when viewed individually. You may use <a href="">markdown</a> to format your description, though we reserve the right to remove excess formatting before approving your listing.</p>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<label class="control-label" for="">Summary</label>
				<input type="text" id="summary" class="form-control" name="ld_s_summary" value="<?php echo ldl_get_value( 'summary' ); ?>" required>
				<?php echo ldl_get_error( 'summary' ); ?>
				<p class="help-block">Please provide a short summary of your listing that will appear in search results.</p>
			</div>
		</div>
	</div>
</div>
