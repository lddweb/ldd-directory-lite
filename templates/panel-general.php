<fieldset>
    <legend>General Information</legend>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label" for="">Title</label>
                    <input id="title" class="form-control" name="ld_s_title" type="text" <?php if ( !empty( $data['title'] ) ) { echo 'value="' . $data['title'] . '" '; } ?>tabindex="1" required>
                    <?php if ( !empty( $errors['title'] ) ) { echo $errors['title']; } ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label" for="">Category</label>
                    <?php echo wp_dropdown_categories( $category_args ) ?>
                    <?php if ( !empty( $errors['category'] ) ) { echo $errors['category']; } ?>
                </div>
            </div>
        </div>
        <div class="row bump">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label" for="">Description</label>
                    <textarea id="description" class="form-control" name="ld_s_description" rows="5" tabindex="3" required><?php if ( !empty( $data['description'] ) ) { echo $data['description']; } ?></textarea>
                    <?php if ( !empty( $errors['description'] ) ) { echo $errors['description']; } ?>
                </div>
            </div>
        </div>
        <div class="row hpt">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label" for="">Summary</label>
                    <input id="summary" class="form-control" name="ld_s_summary" placeholder="Add a summary for your listing" type="text" <?php if ( !empty( $data['summary'] ) ) { echo 'value="' . $data['summary'] . '" '; } ?>tabindex="4">
                    <?php if ( !empty( $errors['summary'] ) ) { echo $errors['summary']; } ?>
                </div>
            </div>
        </div>
        <div class="row bump">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label" for="submit-logo">Logo</label>
                    <input id="submit-logo" class="form-control" name="ld_s_logo" type="file" tabindex="5">
                    <?php if ( !empty( $errors['logo'] ) ) { echo $errors['logo']; } ?>
                    <span class="description">Maximum file size is 2mb. This will be displayed on your profile page, and search results.</span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label" for="">Contact Email</label>
                    <input id="contact_email" class="form-control" name="ld_s_contact_email" type="text" <?php if ( !empty( $data['contact_email'] ) ) { echo 'value="' . $data['contact_email'] . '" '; } ?>tabindex="6" required>
                    <?php if ( !empty( $errors['contact_email'] ) ) { echo $errors['contact_email']; } ?>
                    <span class="description">This is not displayed publicly, however if you choose to provide an email address, visitors will be able to contact you via an online form</span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label" for="">Contact Phone</label>
                    <input id="contact_phone" class="form-control" name="ld_s_contact_phone" type="text" <?php if ( !empty( $data['contact_phone'] ) ) { echo 'value="' . $data['contact_phone'] . '" '; } ?>tabindex="7" required>
                    <?php if ( !empty( $errors['contact_phone'] ) ) { echo $errors['contact_phone']; } ?>
                    <span class="description">This will be displayed publicly on your listings profile page</span>
                </div>
            </div>
        </div>
    </div>

</fieldset>