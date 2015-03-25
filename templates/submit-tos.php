<textarea id="submit-tos" class="form-control" rows="8" readonly><?php echo ldl()->get_option('submit_tos'); ?></textarea>
<div class="checkbox">
    <label>
        <input name="n_tos" type="checkbox" value="1"> <?php _e('By submitting, you agree your listing abides by our terms of service.', 'ldd-directory-lite'); ?><br>
        <?php echo ldl_get_error('tos'); ?>
    </label>
</div>

