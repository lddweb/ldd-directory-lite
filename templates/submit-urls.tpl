<fieldset>
    <legend>Web & Social</legend>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label" for="">Website</label>
                    <input id="url" class="form-control" name="ld_s_url[website]" type="text" {if="!empty($url.website)"}value="{$url.website}" {/if}tabindex="12">
                    <span class="description">Examples include; 'http://www.yoursite.com', 'mysite.org'</span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label" for="">Facebook</label>
                    <input id="facebook" class="form-control" name="ld_s_url[facebook]" type="text" {if="!empty($url.facebook)"}value="{$url.facebook}" {/if}tabindex="13">
                    <span class="description">Help locating and customizing your <a href="https://www.facebook.com/help/www/329992603752372" title="Your Facebook Web Address | Facebook Help Center">Facebook profile URL</a></span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group">
                    <label class="control-label" for="">Twitter</label>
                    <input id="twitter" class="form-control" name="ld_s_url[twitter]" type="text" {if="!empty($url.twitter)"}value="{$url.twitter}" {/if}tabindex="14">
                    <span class="description">This will always be 'https://twitter.com/<strong>username</strong>'</span>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label class="control-label" for="">Linkedin</label>
                    <input id="linkedin" class="form-control" name="ld_s_url[linkedin]" type="text" {if="!empty($url.linkedin)"}value="{$url.linkedin}" {/if}tabindex="15">
                    <span class="description">Help locating and customizing your <a href="http://help.linkedin.com/app/answers/detail/a_id/85/~/promoting-your-public-profile" title="Promoting Your Public Profile | LinkedIn Help Center">LinkedIn profile URL</a></span>
                </div>
            </div>
        </div>
    </div>

</fieldset>
