<fieldset>
    <legend>Geographical Data</legend>

    <div class="container-fluid">
        {if="!$use_locale"}<div class="row bump">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label" for="">Country</label>
                    {$country_dropdown}
                    {if="!empty($errors.country)"}{$errors.country}{/if}
                </div>
            </div>
            </div>{/if}
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label" for="">Street</label>
                    <input id="address_one" class="form-control" name="ld_s_address_one" type="text" {if="!empty($data.address_one)"}value="{$data.address_one}" {/if}tabindex="8" required>
                    {if="!empty($errors.address_one)"}{$errors.address_one}{/if}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label" for="city">City/Town</label>
                    <input id="city" class="form-control" name="ld_s_city" type="text" {if="!empty($data.city)"}value="{$data.city}" {/if}tabindex="9" required>
                    {if="!empty($errors.city)"}{$errors.city}{/if}
                </div>
            </div>
            <div class="submit-ajax-loading col-md-8" style="display:none;">
                <img src="/wp-content/plugins/ldd-directory-lite/public/images/loading.gif" width="32" height="32">
            </div>
            <div class="submit-ajax-replace">
                <div class="col-md-4">
                    <div class="form-group">
                        <label id="subdivision_label" class="control-label" for="subdivision">State</label>
                        <span id="subdivision_control">{$subdivision_dropdown}</span>
                        {if="!empty($errors.subdivision)"}{$errors.subdivision}{/if}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label id="post_code_label" class="control-label" for="post_code">Zip/Postal Code</label>
                        <input id="post_code" class="form-control" name="ld_s_post_code" type="text" {if="!empty($data.post_code)"}value="{$data.post_code}" {/if}tabindex="11" required>
                        {if="!empty($errors.post_code)"}{$errors.post_code}{/if}
                    </div>
                </div>
            </div>
        </div>
    </div>

</fieldset>
