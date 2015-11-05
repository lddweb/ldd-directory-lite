<?php
/**
 * (Orphan) Plugin Usage Tracking
 *
 * Borrowed from WordPress SEO.
 *
 * This file is temporarily disconnected. I was doing it wrong, and it's not mission critical at the moment while
 * I pursue a stable version of this plugin. All related files and functions have been condensed into this file until
 * such time as it's ready to be deployed again.
 *
 * @package   ldd_directory_lite
 * @author    LDD Web Design <info@lddwebdesign.com>
 * @license   GPL-2.0+
 * @link      http://lddwebdesign.com
 * @copyright 2014 LDD Consulting, Inc
 */

function ldl_tracking_additions($options) {

    $options['directory_lite'] = array(
        'directory_page'         => ldl()->get_option('directory_page'),
        'disable_bootstrap'      => ldl()->get_option('disable_bootstrap'),
        'google_maps'            => ldl()->get_option('google_maps'),
        'submit_use_tos'         => ldl()->get_option('submit_use_tos'),
        'submit_use_locale'      => ldl()->get_option('submit_use_locale'),
        'submit_locale'          => ldl()->get_option('submit_locale'),
        'submit_require_address' => ldl()->get_option('submit_require_address'),
    );

    return $options;
}

add_filter('lite_tracking_filters', 'ldl_tracking_additions');


class ldd_directory_lite_tracking {

    /**
     * @var $_instance An instance of ones own instance
     */
    private static $_instance = null;

    /**
     * Class constructor
     */
    function __construct() {
        if (current_filter('init'))
            $this->tracking();
    }

    /**
     * Main tracking function.
     */
    public function tracking() {

        if (get_transient('lite_tracking_cache'))
            return;

        // Start of Metrics
        global $blog_id, $wpdb;

        $hash = get_option('lite_tracking_hash', false);

        if (!$hash || empty($hash)) {
            // create and store hash
            $hash = md5(site_url());
            update_option('lite_tracking_hash', $hash);
        }

        $pts = array();
        $post_types = get_post_types(array('public' => true));
        if (is_array($post_types) && $post_types !== array()) {
            foreach ($post_types as $post_type) {
                $count = wp_count_posts($post_type);
                $pts[$post_type] = $count->publish;
            }
        }
        unset($post_types);

        $comments_count = wp_count_comments();

        $theme_data = wp_get_theme();
        $theme = array(
            'name'       => $theme_data->display('Name', false, false),
            'theme_uri'  => $theme_data->display('ThemeURI', false, false),
            'version'    => $theme_data->display('Version', false, false),
            'author'     => $theme_data->display('Author', false, false),
            'author_uri' => $theme_data->display('AuthorURI', false, false),
        );
        $theme_template = $theme_data->get_template();
        if ($theme_template !== '' && $theme_data->parent()) {
            $theme['template'] = array(
                'version'    => $theme_data->parent()->display('Version', false, false),
                'name'       => $theme_data->parent()->display('Name', false, false),
                'theme_uri'  => $theme_data->parent()->display('ThemeURI', false, false),
                'author'     => $theme_data->parent()->display('Author', false, false),
                'author_uri' => $theme_data->parent()->display('AuthorURI', false, false),
            );
        } else {
            $theme['template'] = '';
        }
        unset($theme_template);


        $plugins = array();
        $active_plugin = get_option('active_plugins');
        foreach ($active_plugin as $plugin_path) {
            if (!function_exists('get_plugin_data')) {
                require_once(ABSPATH . 'wp-admin/includes/plugin.php');
            }

            $plugin_info = get_plugin_data(WP_PLUGIN_DIR . '/' . $plugin_path);

            $slug = str_replace('/' . basename($plugin_path), '', $plugin_path);
            $plugins[$slug] = array(
                'version'    => $plugin_info['Version'],
                'name'       => $plugin_info['Name'],
                'plugin_uri' => $plugin_info['PluginURI'],
                'author'     => $plugin_info['AuthorName'],
                'author_uri' => $plugin_info['AuthorURI'],
            );
        }
        unset($active_plugins, $plugin_path);

        $data = array(
            'site'     => array(
                'hash'      => $hash,
                'version'   => get_bloginfo('version'),
                'multisite' => is_multisite(),
                'users'     => $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $wpdb->users INNER JOIN $wpdb->usermeta ON ({$wpdb->users}.ID = {$wpdb->usermeta}.user_id) WHERE 1 = 1 AND ( {$wpdb->usermeta}.meta_key = %s )", 'wp_' . $blog_id . '_capabilities')),
                'lang'      => get_locale(),
            ),
            'pts'      => $pts,
            'comments' => array(
                'total'    => $comments_count->total_comments,
                'approved' => $comments_count->approved,
                'spam'     => $comments_count->spam,
                'pings'    => $wpdb->get_var("SELECT COUNT(comment_ID) FROM $wpdb->comments WHERE comment_type = 'pingback'"),
            ),
            'options'  => apply_filters('lite_tracking_filters', array()),
            'theme'    => $theme,
            'plugins'  => $plugins,
        );

        $args = array(
            'body' => $data,
        );

        //mdd( $args );
        wp_remote_post('http://tracking.lddwebdesign.com/', $args);

        // Store for a week, then push data again.
        set_transient('lite_tracking_cache', true, 60 * 60 * 24 * 7);

    }

    /**
     * Get the singleton instance of this class
     *
     * @return object
     */
    public static function get_instance() {
        if (null === self::$_instance)
            self::$_instance = new self;

        return self::$_instance;
    }
}


add_action('admin_enqueue_scripts', 'custom_admin_pointers_header');

function custom_admin_pointers_header() {
    if (custom_admin_pointers_check()) {
        add_action('admin_print_footer_scripts', 'custom_admin_pointers_footer');

        wp_enqueue_script('wp-pointer');
        wp_enqueue_style('wp-pointer');
    }
}


function custom_admin_pointers_check() {
    $admin_pointers = custom_admin_pointers();
    foreach ($admin_pointers as $pointer => $array) {
        if ($array['active'])
            return true;
    }
}

function custom_admin_pointers_footer() {
    $admin_pointers = custom_admin_pointers();
    ?>
    <script type="text/javascript">
        (function ($) {
            <?php
            foreach ( $admin_pointers as $pointer => $array ) {
               if ( $array['active'] ) {
                  ?>
            $('<?php echo $array['anchor_id']; ?>').pointer({
                content: '<?php echo $array['content']; ?>',
                position: {
                    edge: '<?php echo $array['edge']; ?>',
                    align: '<?php echo $array['align']; ?>'
                },
                close: function () {
                    $.post(ajaxurl, {
                        pointer: '<?php echo $pointer; ?>',
                        action: 'dismiss-wp-pointer'
                    });
                }
            }).pointer('open');
            <?php
         }
      }
      ?>
        })(jQuery);
    </script>
<?php
}

function custom_admin_pointers() {
    $dismissed = explode(',', (string) get_user_meta(get_current_user_id(), 'dismissed_wp_pointers', true));
    $version = '1_1'; // replace all periods in 1.0 with an underscore
    $prefix = 'custom_admin_pointers' . $version . '_';

    $new_pointer_content = '<h3>' . __('LDD Directory version' . LDDLITE_VERSION) . '</h3>';
    $new_pointer_content .= '<p>' . __('Thank you for updating to the latest LDD Directory version! You can add, edit or remove listings from this menu. Some new features have been added since last time, so be sure to review your settings!') . '</p>';

    return array(
        $prefix . 'new_items' => array(
            'content'   => $new_pointer_content,
            'anchor_id' => '#menu-posts-directory_listings',
            'edge'      => 'top',
            'align'     => 'left',
            'active'    => (!in_array($prefix . 'new_items', $dismissed))
        ),
    );
}


class ldd_directory_lite_pointers {

    /**
     * @var $_instance An instance of ones own instance
     */
    private static $_instance = null;

    /**
     * Class constructor.
     */
    private function __construct() {
        if (current_user_can('manage_options') && !ldl()->get_option('allow_tracking_pointer_done')) {
            wp_enqueue_style('wp-pointer');
            wp_enqueue_script('jquery-ui');
            wp_enqueue_script('wp-pointer');
            wp_enqueue_script('utils');
            add_action('admin_print_footer_scripts', array($this, 'print_scripts'));
        }
    }

    /**
     * Get the singleton instance of this class
     *
     * @return object
     */
    public static function get_instance() {
        if (null === self::$_instance)
            self::$_instance = new self;

        return self::$_instance;
    }


    function print_scripts() {

        $nonce = wp_create_nonce('lddlite-allow-tracking-nonce');

        $content = '<h3>' . __('Help improve LDD Directory Lite', 'ldd-directory-lite') . '</h3>';
        $content .= '<p>' . __('Usage tracking is completely anonymous and allows us to know what configurations, plugins and themes we should be testing future versions of our plugin with.', 'ldd-directory-lite') . '</p>';

        $opt_arr = array(
            'content'  => $content,
            'position' => array('edge' => 'top', 'align' => 'center')
        );

        ?>
        <script type="text/javascript">
            (function ($) {
                var lite_pointer_options = <?php echo json_encode( $opt_arr ); ?>, setup;

                function ldl_store_answer(input, nonce) {
                    var ldl_tracking_data = {
                        action: 'lite_allow_tracking',
                        allow_tracking: input,
                        nonce: nonce
                    };
                    jQuery.post(ajaxurl, ldl_tracking_data, function () {
                        jQuery('#wp-pointer-0').remove();
                    });
                }

                lite_pointer_options = $.extend(lite_pointer_options, {
                    buttons: function (event, t) {
                        var button = jQuery('<a id="pointer-close" style="margin-left:5px;" class="button-secondary">' + '<?php _e( 'Do not allow tracking', 'ldd-directory-lite' ) ?>' + '</a>');
                        button.bind('click.pointer', function () {
                            t.element.pointer('close');
                        });
                        return button;
                    },
                    close: function () {
                    }
                });

                setup = function () {
                    $('#wpadminbar').pointer(lite_pointer_options).pointer('open');
                    jQuery('#pointer-close').after('<a id="pointer-primary" class="button-primary">' + '<?php _e( 'Allow tracking', 'ldd-directory-lite' ) ?>' + '</a>');
                    jQuery('#pointer-primary').click(function () {
                        ldl_store_answer("yes", "<?php echo $nonce ?>")
                    });
                    jQuery('#pointer-close').click(function () {
                        ldl_store_answer("no", "<?php echo $nonce ?>")
                    });
                };

                if (lite_pointer_options.position && lite_pointer_options.position.defer_loading)
                    $(window).bind('load.wp-pointers', setup);
                else
                    $(document).ready(setup);
            })(jQuery);
        </script>
    <?php
    }


} /* End of class */

