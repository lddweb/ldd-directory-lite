<?php
/**
 * Plugin Usage Tracking
 *
 * Adapted from WordPress SEO (I'm not the first, nor will I be the last to do this), the tracking
 * class allows us to gain a few insights into the usage of our plugin. Information is provided
 * on an opt in basis that can at any time be changed through the dashboard settings.
 *
 * @package   ldd_directory_lite
 * @author    LDD Web Design <info@lddwebdesign.com>
 * @license   GPL-2.0+
 * @link      http://lddwebdesign.com
 * @copyright 2014 LDD Consulting, Inc
 */

function ldl_tracking_additions($options) {

    $options['directory_lite'] = array(
        'directory_page'         => ldl_get_setting('directory_page'),
        'disable_bootstrap'      => ldl_get_setting('disable_bootstrap'),
        'public_or_private'      => ldl_get_setting('public_or_private'),
        'google_maps'            => ldl_get_setting('google_maps'),
        'submit_use_tos'         => ldl_get_setting('submit_use_tos'),
        'submit_use_locale'      => ldl_get_setting('submit_use_locale'),
        'submit_locale'          => ldl_get_setting('submit_locale'),
        'submit_require_address' => ldl_get_setting('submit_require_address'),
    );

    return $options;
}

add_filter('lite_tracking_filters', 'ldl_tracking_additions');

class LDL_Tracking {

    /**
     * @var $_instance An instance of ones own instance
     */
    private static $_instance = null;

    /**
     * Class constructor
     */
    function __construct() {

        if (current_filter('init')) {
            $this->tracking();
        }

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

add_action('init', array('LDL_Tracking', 'get_instance'));

