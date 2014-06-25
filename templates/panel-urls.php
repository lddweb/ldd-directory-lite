<fieldset>
    <legend>Web & Social</legend>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label" for="">Website</label>
                    <input id="url" class="form-control" name="ld_s_url[website]" type="text" <?php if ( !empty( $urls['website'] ) ) { echo 'value="' . $urls['website'] . '" '; } ?>tabindex="12">
                    <span class="description">Examples include; 'http://www.yoursite.com', 'mysite.org'</span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label" for="">Facebook</label>
                    <input id="facebook" class="form-control" name="ld_s_url[facebook]" type="text" <?php if ( !empty( $urls['facebook'] ) ) { echo 'value="' . $urls['facebook'] . '" '; } ?>tabindex="13">
                    <span class="description">Help locating and customizing your <a href="https://www.facebook.com/help/www/329992603752372" title="Your Facebook Web Address | Facebook Help Center">Facebook profile URL</a></span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group">
                    <label class="control-label" for="">Twitter</label>
                    <input id="twitter" class="form-control" name="ld_s_url[twitter]" type="text" <?php if ( !empty( $urls['twitter'] ) ) { echo 'value="' . $urls['twitter'] . '" '; } ?>tabindex="14">
                    <span class="description">This will always be 'https://twitter.com/<strong>username</strong>'</span>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label class="control-label" for="">Linkedin</label>
                    <input id="linkedin" class="form-control" name="ld_s_url[linkedin]" type="text" <?php if ( !empty( $urls['linkedin'] ) ) { echo 'value="' . $urls['linkedin'] . '" '; } ?>tabindex="15">
                    <span class="description">Help locating and customizing your <a href="http://help.linkedin.com/app/answers/detail/a_id/85/~/promoting-your-public-profile" title="Promoting Your Public Profile | LinkedIn Help Center">LinkedIn profile URL</a></span>
                </div>
            </div>
        </div>
    </div>

</fieldset>
