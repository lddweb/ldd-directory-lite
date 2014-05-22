<fieldset>
    <legend>Account Information</legend>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label" for="">Username</label>
                    <input id="username" class="form-control" name="ld_s_username" placeholder="Choose a username" type="text" {if="!empty($data.username)"}value="{$data.username}" {/if}tabindex="16" required>
                    {if="!empty($errors.username)"}{$errors.username}{/if}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label" for="">Your Email</label>
                    <input id="email" class="form-control" name="ld_s_email" placeholder="Please enter your email address" type="email" {if="!empty($data.email)"}value="{$data.email}" {/if}tabindex="17" required>
                    {if="!empty($errors.email)"}{$errors.email}{/if}
                </div>
            </div>
        </div>

    </div>

</fieldset>
