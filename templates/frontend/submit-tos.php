<div id="submit-tos" ><?php echo ldl()->get_option('submit_tos'); ?></div>
<div class="checkbox">
    <label>
        <input name="n_tos" type="checkbox" required value="1"> <?php _e('By submitting, you agree your listing abides by our terms of service.', 'ldd-directory-lite'); ?><br>
        <?php echo ldl_get_error('tos'); ?>
    </label>
</div>

