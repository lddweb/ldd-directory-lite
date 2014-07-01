<?php


/**
 * Unceremoniously ripped from `/wp-admin/includes/meta-boxes.php` in order to change
 * the labels which are inaccessible via filter.
 *
 * @param       $post
 * @param array $args
 */
function ldl_metaboxes__submitdiv($post) {
    global $action;

    $post_type = $post->post_type;
    $post_type_object = get_post_type_object($post_type);
    $can_publish = current_user_can($post_type_object->cap->publish_posts);
    ?>
    <div class="submitbox" id="submitpost">

        <div id="minor-publishing">

            <?php // Hidden submit button early on so that the browser chooses the right button when form is submitted with Return key ?>
            <div style="display:none;">
                <?php submit_button(__('Save'), 'button', 'save'); ?>
            </div>

            <div id="minor-publishing-actions">
                <div id="save-action">
                    <?php if ('publish' != $post->post_status && 'future' != $post->post_status && 'pending' != $post->post_status) { ?>
                        <input <?php if ('private' == $post->post_status) { ?>style="display:none"<?php } ?>
                               type="submit" name="save" id="save-post" value="<?php esc_attr_e('Save Draft'); ?>"
                               class="button"/>
                    <?php } elseif ('pending' == $post->post_status && $can_publish) { ?>
                        <input type="submit" name="save" id="save-post" value="<?php esc_attr_e('Save as Pending'); ?>"
                               class="button"/>
                    <?php } ?>
                    <span class="spinner"></span>
                </div>
                <div class="clear"></div>
            </div>
            <!-- #minor-publishing-actions -->

            <div id="misc-publishing-actions">

                <div class="misc-pub-section misc-pub-post-status"><label
                        for="post_status"><?php _e('Status:') ?></label>
<span id="post-status-display">
<?php
switch ($post->post_status) {
    case 'private':
        _e('Privately Listed Business');
        break;
    case 'publish':
        _e('Approved');
        break;
    case 'future':
        _e('Scheduled');
        break;
    case 'pending':
        _e('Pending Review');
        break;
    case 'draft':
    case 'auto-draft':
        _e('Disabled');
        break;
}
?>
</span>
                    <?php if ('publish' == $post->post_status || 'private' == $post->post_status || $can_publish) { ?>
                        <a href="#post_status" <?php if ('private' == $post->post_status) { ?>style="display:none;"
                           <?php } ?>class="edit-post-status hide-if-no-js"><?php _e('Edit') ?></a>

                        <div id="post-status-select" class="hide-if-js">
                            <input type="hidden" name="hidden_post_status" id="hidden_post_status"
                                   value="<?php echo esc_attr(('auto-draft' == $post->post_status) ? 'draft' : $post->post_status); ?>"/>
                            <select name='post_status' id='post_status'>
                                <?php if ('publish' == $post->post_status) : ?>
                                    <option<?php selected($post->post_status, 'publish'); ?>
                                        value='publish'><?php _e('Approved') ?></option>
                                <?php elseif ('private' == $post->post_status) : ?>
                                    <option<?php selected($post->post_status, 'private'); ?>
                                        value='publish'><?php _e('Privately Listed') ?></option>
                                <?php
                                elseif ('future' == $post->post_status) : ?>
                                    <option<?php selected($post->post_status, 'future'); ?>
                                        value='future'><?php _e('Scheduled') ?></option>
                                <?php endif; ?>
                                <option<?php selected($post->post_status, 'pending'); ?>
                                    value='pending'><?php _e('Pending Review') ?></option>
                                <?php if ('auto-draft' == $post->post_status) : ?>
                                    <option<?php selected($post->post_status, 'auto-draft'); ?>
                                        value='draft'><?php _e('Disabled') ?></option>
                                <?php else : ?>
                                    <option<?php selected($post->post_status, 'draft'); ?>
                                        value='draft'><?php _e('Disabled') ?></option>
                                <?php endif; ?>
                            </select>
                            <a href="#post_status" class="save-post-status hide-if-no-js button"><?php _e('OK'); ?></a>
                            <a href="#post_status"
                               class="cancel-post-status hide-if-no-js button-cancel"><?php _e('Cancel'); ?></a>
                        </div>

                    <?php } ?>
                </div>
                <!-- .misc-pub-section -->

                <?php
                // translators: Publish box date format, see http://php.net/date
                $datef = __('M j, Y @ G:i');
                if (0 != $post->ID) {
                    if ('future' == $post->post_status) { // scheduled for publishing at a future date
                        $stamp = __('Scheduled for: <b>%1$s</b>');
                    } else if ('publish' == $post->post_status || 'private' == $post->post_status) { // already published
                        $stamp = __('Approved on: <b>%1$s</b>');
                    } else if ('0000-00-00 00:00:00' == $post->post_date_gmt) { // draft, 1 or more saves, no date specified
                        $stamp = __('Approve <b>immediately</b>');
                    } else if (time() < strtotime($post->post_date_gmt . ' +0000')) { // draft, 1 or more saves, future date specified
                        $stamp = __('Schedule for: <b>%1$s</b>');
                    } else { // draft, 1 or more saves, date specified
                        $stamp = __('List on: <b>%1$s</b>');
                    }
                    $date = date_i18n($datef, strtotime($post->post_date));
                } else { // draft (no saves, and thus no date specified)
                    $stamp = __('Publish <b>immediately</b>');
                    $date = date_i18n($datef, strtotime(current_time('mysql')));
                }

                if ($can_publish) : // Contributors don't get to choose the date of publish
                    ?>
                    <div class="misc-pub-section curtime misc-pub-curtime">
                    <span id="timestamp">
	<?php printf($stamp, $date); ?></span>
                    <a href="#edit_timestamp" class="edit-timestamp hide-if-no-js"><?php _e('Edit') ?></a>

                    <div id="timestampdiv" class="hide-if-js"><?php touch_time(($action == 'edit'), 1); ?></div>
                    </div><?php // /misc-pub-section ?>
                <?php endif; ?>

                <?php do_action('post_submitbox_misc_actions'); ?>
            </div>
            <div class="clear"></div>
        </div>

        <div id="major-publishing-actions">
            <?php do_action('post_submitbox_start'); ?>
            <div id="delete-action">
                <?php
                if (current_user_can("delete_post", $post->ID)) {
                    if (!EMPTY_TRASH_DAYS)
                        $delete_text = __('Delete Permanently'); else
                        $delete_text = __('Move to Trash');
                    ?>
                    <a class="submitdelete deletion"
                       href="<?php echo get_delete_post_link($post->ID); ?>"><?php echo $delete_text; ?></a><?php
                } ?>
            </div>

            <div id="publishing-action">
                <span class="spinner"></span>
                <?php
                if (!in_array($post->post_status, array('publish', 'future', 'private')) || 0 == $post->ID) {
                    if ($can_publish) :
                        if (!empty($post->post_date_gmt) && time() < strtotime($post->post_date_gmt . ' +0000')) : ?>
                            <input name="original_publish" type="hidden" id="original_publish"
                                   value="<?php esc_attr_e('Schedule') ?>"/>
                            <?php submit_button(__('Schedule'), 'primary button-large', 'publish', false, array('accesskey' => 'p')); ?>
                        <?php else : ?>
                            <input name="original_publish" type="hidden" id="original_publish"
                                   value="<?php esc_attr_e('Publish') ?>"/>
                            <?php submit_button(__('Approve'), 'primary button-large', 'publish', false, array('accesskey' => 'p')); ?>
                        <?php    endif;
                    else : ?>
                        <input name="original_publish" type="hidden" id="original_publish"
                               value="<?php esc_attr_e('Submit for Review') ?>"/>
                        <?php submit_button(__('Submit for Review'), 'primary button-large', 'publish', false, array('accesskey' => 'p')); ?>
                    <?php
                    endif;
                } else {
                    ?>
                    <input name="original_publish" type="hidden" id="original_publish"
                           value="<?php esc_attr_e('Update') ?>"/>
                    <input name="save" type="submit" class="button button-primary button-large" id="publish"
                           accesskey="p" value="<?php esc_attr_e('Update') ?>"/>
                <?php
                } ?>
            </div>
            <div class="clear"></div>
        </div>
    </div>

<?php
}


/**
 * Change Metabox titles
 *
 * There's no way to filter the titles on most metaboxes, so we just disable them and turn them right back on
 * with our own name.
 *
 * @since 0.5.0
 */
function ldl_metaboxes__swap() {
    if (LDDLITE_POST_TYPE == get_post_type()) {
        remove_meta_box('postimagediv', LDDLITE_POST_TYPE, 'side');
        remove_meta_box('submitdiv', LDDLITE_POST_TYPE, 'side');
        remove_meta_box('authordiv', LDDLITE_POST_TYPE, 'side');
        add_meta_box('authordiv', __('Owner', 'lddlite'), 'post_author_meta_box', null, 'side', 'high');
        add_meta_box('postimagediv', __('Logo', 'lddlite'), 'post_thumbnail_meta_box', null, 'side', 'high');
        add_meta_box('submitdiv', __('Approval', 'lddlite'), 'ldl_metaboxes__submitdiv', null, 'side', 'high');
    }
}


function ldl_metaboxes__init_cmb() {
    if (!class_exists('cmb_Meta_Box'))
        require_once(LDDLITE_PATH . 'includes/cmb/init.php');
}


function ldl_metaboxes__setup_cmb(array $meta_boxes) {

    $use_locale = ldl_get_setting('submit_use_locale');

    $meta_boxes['listings_address'] = array(
        'id'         => 'listings_address',
        'title'      => __('Location', 'lddlite'),
        'pages'      => array(LDDLITE_POST_TYPE),
        'context'    => 'normal',
        'priority'   => 'core',
        'show_names' => true,
        'fields'     => array(
            array(
                'name' => 'Address',
                'id'   => LDDLITE_PFX . '_geo',
                'type' => 'geo_location',
            ),
        ),
    );

    $meta_boxes['listings_web'] = array(
        'id'         => 'listings_web',
        'title'      => __('Web Addresses', 'lddlite'),
        'pages'      => array(LDDLITE_POST_TYPE),
        'context'    => 'normal',
        'priority'   => 'core',
        'show_names' => true,
        'fields'     => array(
            array(
                'name' => __('Website', 'lddlite'),
                'desc' => __('Valid examples include; <code>mywebsite.net</code>, <code>www.business.com</code>, or <code>www.hosting.com/mysite/mypage.html</code>', 'lddlite'),
                'id'   => LDDLITE_PFX . '_url_website',
                'type' => 'text',
            ),
            array(
                'name' => __('Facebook', 'lddlite'),
                'desc' => __('This should always start with <code>facebook.com/</code> or <code>www.facebook.com</code>.', 'lddlite'),
                'id'   => LDDLITE_PFX . '_url_facebook',
                'type' => 'text',
            ),
            array(
                'name' => __('Twitter', 'lddlite'),
                'desc' => __('Enter the entire url (<code>www.twitter.com/username</code>) or just the username.', 'lddlite'),
                'id'   => LDDLITE_PFX . '_url_twitter',
                'type' => 'text',
            ),
            array(
                'name' => __('LinkedIn', 'lddlite'),
                'desc' => __('This should start with <code>www.linkedin.com</code>', 'lddlite'),
                'id'   => LDDLITE_PFX . '_url_linkedin',
                'type' => 'text',
            ),
        ),
    );

    $meta_boxes['listings_contact'] = array(
        'id'         => 'listings_contact',
        'title'      => __('Contact Information', 'lddlite'),
        'pages'      => array(LDDLITE_POST_TYPE),
        'context'    => 'side',
        'priority'   => 'core',
        'show_names' => true,
        'fields'     => array(
            array(
                'name' => __('Email', 'lddlite'),
                'id'   => LDDLITE_PFX . '_contact_email',
                'type' => 'text_medium',
            ),
            array(
                'name' => __('Phone', 'lddlite'),
                'id'   => LDDLITE_PFX . '_contact_phone',
                'type' => 'text_small',
            ),
            array(
                'name' => __('Fax', 'lddlite'),
                'id'   => LDDLITE_PFX . '_contact_fax',
                'type' => 'text_small',
            ),
        ),
    );

    return $meta_boxes;
}


add_action('add_meta_boxes', 'ldl_metaboxes__swap', 5);

add_action('init', 'ldl_metaboxes__init_cmb');
add_filter('cmb_meta_boxes', 'ldl_metaboxes__setup_cmb');

add_action('save_post', 'ldl_metaboxes__sanitize', 5);


function ldl_metaboxes__sanitize($post_id) {

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE || !current_user_can('edit_post', $post_id))
        return $post_id;

    foreach ($_POST as $key => $value) {
        if (strpos($key, 'lddlite') === false || empty($value))
            continue;

        $field = substr($key, 9);

        if ('contact_phone' == $field || 'contact_fax' == $field) {

            $_POST[$key] = ldl_format_phone($value);

        } else if ('url_website' == $field) {

            $_POST[$key] = esc_url_raw($value, array('http', 'https'));

        } else if ('url_facebook' == $field) {

            $url = parse_url(esc_url_raw($value));
            $_POST[$key] = 'https://www.facebook.com' . $url['path'];

        } else if ('url_twitter' == $field) {

            if (strpos($value, '/') !== false) {
                $url = parse_url(esc_url_raw($value));
                $_POST[$key] = 'https://twitter.com' . $url['path'];
            } else {
                $_POST[$key] = 'https://twitter.com/' . $value;
            }

        } else if ('url_linkedin' == $field) {

            $url = parse_url(esc_url_raw($value));
            $_POST[$key] = 'https://www.linkedin.com' . $url['path'];

        }

    }

}


//
// CMB ADDITIONS
// *********************************************************************************************************************

function ldl_render_geo_location_field($field, $meta) {

    wp_enqueue_script('google-maps', 'http://maps.googleapis.com/maps/api/js?sensor=false&libraries=places');
    wp_enqueue_script('lddlite-admin');
    wp_enqueue_style('lddlite-admin');

    echo '<input type="text" class="map_search" id="' . $field['id'] . '" value="' . (isset($meta['formatted']) ? $meta['formatted'] : '') . '">';
    echo '<div class="map_wrapper"></div>';
    echo '<input type="hidden" class="formatted" name="' . $field['id'] . '[formatted]" value="' . (isset($meta['formatted']) ? $meta['formatted'] : '') . '">';
    echo '<input type="hidden" class="lat" name="' . $field['id'] . '[lat]" value="' . (isset($meta['lat']) ? $meta['lat'] : '') . '">';
    echo '<input type="hidden" class="lng" name="' . $field['id'] . '[lng]" value="' . (isset($meta['lng']) ? $meta['lng'] : '') . '">';

    if (!empty($field['desc']))
        echo '<p class="cmb_metabox_description">' . $field['desc'] . '</p>';
}

add_action('cmb_render_geo_location', 'ldl_render_geo_location_field', 10, 2);

