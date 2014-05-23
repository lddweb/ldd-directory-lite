<fieldset>
    <legend>General Information</legend>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label" for="">Title</label>
                    <input id="title" class="form-control" name="ld_s_title" type="text" {if="!empty($data.title)"}value="{$data.title}" {/if} tabindex="1" required>
                    {if="!empty($errors.title)"}{$errors.title}{/if}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label" for="">Category</label>
                    {$category_dropdown}
                    {if="!empty($errors.category)"}{$errors.category}{/if}
                </div>
            </div>
        </div>
        <div class="row bump">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label" for="">Description</label>
                    <textarea id="description" class="form-control" name="ld_s_description" rows="5" tabindex="3" required>{if="!empty($data.description)"}{$data.description}{/if}</textarea>
                    {if="!empty($errors.description)"}{$errors.description}{/if}
                    <span class="description">The following HTML tags and attributes are allowed in your description:<br> {$allowed_tags} </span>
                </div>
            </div>
        </div>
        <div class="row hpt">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label" for="">Summary</label>
                    <input id="summary" class="form-control" name="ld_s_summary" placeholder="Add a summary for your listing" type="text" {if="!empty($data.summary)"}value="{$data.summary}" {/if} tabindex="4">
                    {if="!empty($errors.summary)"}{$errors.summary}{/if}
                </div>
            </div>
        </div>
        <div class="row bump">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label" for="submit-logo">Logo</label>
                    <input id="submit-logo" class="form-control" name="ld_s_logo" type="file" tabindex="5">
                    {if="!empty($errors.logo)"}{$errors.logo}{/if}
                    <span class="description">Maximum file size is 2mb. This will be displayed on your profile page, and search results.</span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label" for="">Contact Email</label>
                    <input id="contact_email" class="form-control" name="ld_s_contact_email" type="text" {if="!empty($data.contact_email)"}value="{$data.contact_email}" {/if}tabindex="6" required>
                    {if="!empty($errors.contact_email)"}{$errors.contact_email}{/if}
                    <span class="description">This is not displayed publicly, however if you choose to provide an email address, visitors will be able to contact you via an online form</span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label" for="">Contact Phone</label>
                    <input id="contact_phone" class="form-control" name="ld_s_contact_phone" type="text" {if="!empty($data.contact_phone)"}value="{$data.contact_phone}" {/if}tabindex="7" required>
                    {if="!empty($errors.contact_phone)"}{$errors.contact_phone}{/if}
                    <span class="description">This will be displayed publicly on your listings profile page</span>
                </div>
            </div>
        </div>
    </div>

</fieldset>