<?php

function ldl_schedule_tracking( $value, $force_unschedule = false ) {
	$current_schedule = wp_next_scheduled( 'directory_lite_tracking' );

	if ( $force_unschedule !== true && ( $value['allow_tracking'] == true && $current_schedule === false ) ) {
		wp_schedule_event( time(), 'daily', 'directory_lite_tracking' );
	} else if ( $force_unschedule === true || ( $value['allow_tracking'] == false && $current_schedule !== false ) ) {
		wp_clear_scheduled_hook( 'directory_lite_tracking' );
	}
}

class LDD_Directory_Admin {

    /**
     * @var $_instance An instance of ones own instance
     */
    protected static $_instance = null;


    public static function get_in() {

        if ( !isset( self::$_instance ) && !( self::$_instance instanceof LDD_Directory_Admin ) ) {
            self::$_instance = new self;
            self::$_instance->action_filters();
        }

        return self::$_instance;
    }


    public function action_filters() {
	    $basename = plugin_basename( __FILE__ );
	    add_filter( 'plugin_action_links_' . $basename, array( $this, 'add_action_links' ) );

        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

        add_action( 'admin_init', array( $this, 'register_settings' ) );
        add_action( 'admin_menu', array( $this, 'add_settings_menu' ) );
	    if ( true !== ldl_get_setting( 'allow_tracking_popup_done' ) )
		    add_action( 'admin_enqueue_scripts', array( 'LDL_Pointers', 'get_instance' ) );
	    if ( true === ldl_get_setting( 'allow_tracking' ) )
		    add_action( 'directory_lite_tracking', array( 'LDL_Tracking', 'get_instance' ) );
    }


    function enqueue_scripts( $hook_suffix ) {

        if ( 'directory_listings_page_lddlite-settings' != $hook_suffix )
            return;

        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'wp-color-picker' );
        wp_enqueue_script( 'lddlite-admin', LDDLITE_URL . '/public/js/admin.js', array( 'wp-color-picker' ), false, true );

        wp_enqueue_style( 'lddlite-bootstrap', LDDLITE_URL . '/public/css/bootstrap.css', array(), LDDLITE_VERSION );
        wp_enqueue_script( 'bootstrap-tagsinput', LDDLITE_URL . '/public/js/bootstrap-tagsinput.min.js', array( 'lddlite-bootstrap' ), false, true );
        wp_enqueue_style( 'bootstrap-tagsinput', LDDLITE_URL . '/public/css/bootstrap-tagsinput.css' );

    }


    /**
	 * Add a 'Settings' link on the Plugins page for easier access.
	 *
	 * @since 0.5.0
	 * @param $links array Passed by the filter
	 * @return array The modified $links array
	 */
	public function add_action_links( $links ) {

		return array_merge(
			array(
				'settings'   => '<a href="' . admin_url( 'edit.php?post_type=' . LDDLITE_POST_TYPE . '&page=lddlite-settings' ) . '">' . __( 'Settings', 'lddlite' ) . '</a>',
				'addlisting' => '<a href="' . admin_url( 'post-new.php?post_type=' . LDDLITE_POST_TYPE ) . '">' . __( 'Add Listing', 'lddlite' ) . '</a>',
			),
			$links
		);

	}


    public function register_settings() {

        add_settings_section( 'lddlite_settings_general', __return_null(), '__return_false', 'lddlite_settings_general' );

        add_settings_field( 'lddlite_settings[directory_label]',       '<label for="lite-directory_label">' . __( 'Directory Label' , 'lddlite' ) . '</label>',             '_f_directory_label',       'lddlite_settings_general', 'lddlite_settings_general' );
        add_settings_field( 'lddlite_settings[directory_description]', '<label for="lite-directory_description">' . __( 'Directory Description' , 'lddlite' ) . '</label>', '_f_directory_description', 'lddlite_settings_general', 'lddlite_settings_general' );
        add_settings_field( 'lddlite_settings[directory_page]',        '<label for="lite-directory_page">' . __( 'Directory Page' , 'lddlite' ) . '</label>',               '_f_directory_page',        'lddlite_settings_general', 'lddlite_settings_general' );
        add_settings_field( 'lddlite_settings_other_separator',        '<span style="font-size: 18px">' . __( 'Other Settings', 'lddlite' ) . '</span>',                    '__return_false',           'lddlite_settings_general', 'lddlite_settings_general' );
        add_settings_field( 'lddlite_settings[allow_tracking]',        __( 'Allow Tracking', 'lddlite' ),                                                                   '_f_allow_tracking',        'lddlite_settings_general', 'lddlite_settings_general' );
        add_settings_field( 'lddlite_settings[disable_bootstrap]',     __( 'Disable Bootstrap', 'lddlite' ),                                                                '_f_disable_bootstrap',     'lddlite_settings_general', 'lddlite_settings_general' );
        add_settings_field( 'lddlite_settings[public_or_private]',     __( 'Public Directory', 'lddlite' ),                                                                 '_f_public_or_private',     'lddlite_settings_general', 'lddlite_settings_general' );
        add_settings_field( 'lddlite_settings[google_maps]',           __( 'Use Google Maps', 'lddlite' ),                                                                  '_f_google_maps',           'lddlite_settings_general', 'lddlite_settings_general' );
        add_settings_field( 'lddlite_settings_debug_separator]',       '<span style="font-size: 18px">' . __( 'Debug Tools', 'lddlite' ) . '</span>',                       '__return_false',           'lddlite_settings_general', 'lddlite_settings_general' );
        add_settings_field( 'debug_uninstall',                         '<span>Uninstall Data</span>',                                                  '_f_debug_uninstall',       'lddlite_settings_general', 'lddlite_settings_general' );

        function _f_directory_label() {
            echo '<input id="lite-directory_label" type="text" size="80" name="lddlite_settings[directory_label]" value="' . ldl_get_setting( 'directory_label', 1 ) . '">';
            echo '<p class="description">' . __( 'Name your directory; "My Business Directory", "Local Restaurant Feed", "John\'s List of Links", etc.', 'lddlite' ) . '</p>';
        }

        function _f_directory_description() {
            wp_editor( ldl_get_setting( 'directory_description' ), 'lite-directory_description', array( 'textarea_name' => 'lddlite_settings[directory_description]', 'textarea_rows' => 5 ) );
        }

        function _f_directory_page() {
            $args = array(
                'name'              => 'lddlite_settings[directory_page]',
                'id'                => 'lite-directory_page',
                'selected'          => ldl_get_setting( 'directory_page' ),
                'show_option_none'  => 'Select a page...',
                'option_none_value' => '',
            );
            wp_dropdown_pages( $args );
            echo '<p class="description">' . __( 'Contains the <code>[directory_lite]</code> shortcode.', 'lddlite' ) . '</p>';
        }

        function _f_allow_tracking() {
            echo '<label for="lite-allow_tracking"><input id="lite-allow_tracking" type="checkbox" name="lddlite_settings[allow_tracking]" value="1" ' . checked( ldl_get_setting( 'allow_tracking' ), 1, 0 ) . '> <span>Allow anonymous usage tracking</span></label>';
            echo '<p class="description">' . __( 'Your privacy is important to us, and all information collected is completely anonymous. Information collected is used only to improve future versions of the plugin, and is never shared with anyone who is not directly involved in developing LDD Directory Lite.', 'lddlite' ) . '</p>';
        }

        function _f_disable_bootstrap() {
            echo '<label for="lite-disable_bootstrap"><input id="lite-disable_bootstrap" type="checkbox" name="lddlite_settings[disable_bootstrap]" value="1" ' . checked( ldl_get_setting( 'disable_bootstrap' ), 1, 0 ) . '> <span>Disable</span></label>';
            echo '<p class="description">' . __( 'A lot of themes already use bootstrap; if yours is one, disable the plugin from loading another copy.', 'lddlite' ) . '</p>';
        }

        function _f_public_or_private() {
            echo '<label for="lite-public_or_private-yes" title="Allow public interaction"><input id="lite-public_or_private-yes" type="radio" name="lddlite_settings[public_or_private]" value="1" ' . checked( ldl_get_setting( 'public_or_private' ), 1, 0 ) . '> <span>Yes</span></label><br />';
            echo '<label for="lite-public_or_private-no" title="Disallow public interaction"><input id="lite-public_or_private-no" type="radio" name="lddlite_settings[public_or_private]" value="0" ' . checked( ldl_get_setting( 'public_or_private' ), 0, 0 ) . '> <span>No</span></label><br />';
            echo '<p class="description">' . __( 'Determines whether features such as "Submit a Listing" are available.', 'lddlite' ) . '</p>';
        }

        function _f_google_maps() {
            echo '<label for="lite-google_maps-yes" title="Enable Google Maps"><input id="lite-google_maps-yes" type="radio" name="lddlite_settings[google_maps]" value="1" ' . checked( ldl_get_setting( 'google_maps' ), 1, 0 ) . '> <span>Yes</span></label><br />';
            echo '<label for="lite-google_maps-no" title="Disable Google Maps"><input id="lite-google_maps-no" type="radio" name="lddlite_settings[google_maps]" value="0" ' . checked( ldl_get_setting( 'google_maps' ), 0, 0 ) . '> <span>No</span></label><br />';
            echo '<p class="description">' . __( 'Display Google Maps on listing pages?', 'lddlite' ) . '</p>';
        }

        function _f_debug_uninstall() {
            echo '<label for="lite-debug_uninstall"><input id="lite-debug_uninstall" type="checkbox" name="lddlite_settings[debug_uninstall]" value="1"> <span>Confirm</span></label>';
            echo '<p class="description warning">Only select this option if you know what you are doing! This will remove ALL of your Directory Lite posts and taxonomies.</p>';
        }


        add_settings_section( 'lddlite_settings_email', __return_null(), '__return_false', 'lddlite_settings_email' );

        add_settings_field( 'lddlite_settings[email_from_name]',            '<label for="email_from_name">' . __( 'From - Name' , 'lddlite' ) . '</label>',                                '_f_email_from_name',            'lddlite_settings_email', 'lddlite_settings_email' );
        add_settings_field( 'lddlite_settings[email_from_address]',         '<label for="email_from_address">' . __( 'From - Address' , 'lddlite' ) . '</label>',                          '_f_email_from_Address',         'lddlite_settings_email', 'lddlite_settings_email' );
        add_settings_field( 'lddlite_settings[email_notification_address]', '<label for="email_notification_address">' . __( 'Notify' , 'lddlite' ) . '</label>',                          '_f_email_notification_address', 'lddlite_settings_email', 'lddlite_settings_email' );
        add_settings_field( 'lddlite_settings_other_separator',             '<span style="font-size: 18px">' . __( 'Message Contents', 'lddlite' ) . '</span>',                            '__return_false',                'lddlite_settings_email', 'lddlite_settings_email' );
        add_settings_field( 'lddlite_settings[email_toadmin_subject]',      '<label for="email_toadmin_subject">' . __( 'Administrator Notification Email' , 'lddlite' ) . '</label>',     '_f_email_toadmin_subject',      'lddlite_settings_email', 'lddlite_settings_email' );
        add_settings_field( 'lddlite_settings[email_toadmin_body]',         '<label for="email_toadmin_body" class="screen-reader-text">' . __( 'Email Body' , 'lddlite' ) . '</label>',   '_f_email_toadmin_body',         'lddlite_settings_email', 'lddlite_settings_email' );
        add_settings_field( 'lddlite_settings[email_onsubmit_subject]',     '<label for="email_onsubmit_subject">' . __( 'Listing Submission' , 'lddlite' ) . '</label>',                  '_f_email_onsubmit_subject',     'lddlite_settings_email', 'lddlite_settings_email' );
        add_settings_field( 'lddlite_settings[email_onsubmit_body]',        '<label for="email_onsubmit_body" class="screen-reader-text">' . __( 'Email Body' , 'lddlite' ) . '</label>',  '_f_email_onsubmit_body',        'lddlite_settings_email', 'lddlite_settings_email' );
        add_settings_field( 'lddlite_settings[email_onapprove_subject]',    '<label for="email_onapprove_subject">' . __( 'Listing Approval' , 'lddlite' ) . '</label>',                   '_f_email_onapprove_subject',    'lddlite_settings_email', 'lddlite_settings_email' );
        add_settings_field( 'lddlite_settings[email_onapprove_body]',       '<label for="email_onapprove_body" class="screen-reader-text">' . __( 'Email Body' , 'lddlite' ) . '</label>', '_f_email_onapprove_body',       'lddlite_settings_email', 'lddlite_settings_email' );

        function _f_email_from_name() {
            echo '<input id="email_from_name" type="text" size="80" name="lddlite_settings[email_from_name]" value="' . ldl_get_setting( 'email_from_name', 1 ) . '">';
            echo '<p class="description">' . __( 'This forms the first part of outgoing messages, ', 'lddlite' ) . sprintf( ' From: <strong>%s</strong> &lt;%s&gt;', ldl_get_setting( 'email_from_name' ), ldl_get_setting( 'email_from_address' ) ) . '</p>';
        }

        function _f_email_from_address() {
            echo '<input id="email_from_address" type="text" size="80" name="lddlite_settings[email_from_address]" value="' . ldl_get_setting( 'email_from_address', 1 ) . '">';
            echo '<p class="description">' . __( 'This forms the second part of outgoing messages, ', 'lddlite' ) . sprintf( ' From: %s &lt;<strong>%s</strong>&gt;', ldl_get_setting( 'email_from_name' ), ldl_get_setting( 'email_from_address' ) ) . '</p>';
        }

        function _f_email_notification_address() {
            echo '<input id="email_notification_address" type="text" size="80" data-role="tagsinput" name="lddlite_settings[email_notification_address]" value="' . ldl_get_setting( 'email_notification_address', 1 ) . '">';
            echo '<p class="description">' . __( 'This is where administrative notifications will get sent. Separate multiple emails with a ; ', 'lddlite' ) . '</p>';
        }

        function _f_email_toadmin_subject() {
            echo '<input id="email_toadmin_subject" type="text" size="80" name="lddlite_settings[email_toadmin_subject]" value="' . ldl_get_setting( 'email_toadmin_subject', 1 ) . '">';
            echo '<p class="description">' . __( 'Sent to the site administrator when a listing is submitted and pending approval.', 'lddlite' ) . '</p>';
        }

        function _f_email_toadmin_body() {
            wp_editor( ldl_get_setting( 'email_toadmin_body' ), 'lite-email_toadmin_body', array( 'textarea_name' => 'lddlite_settings[email_toadmin_body]', 'textarea_rows' => 5 ) );
        }

        function _f_email_onsubmit_subject() {
            echo '<input id="email_onsubmit_subject" type="text" size="80" name="lddlite_settings[email_onsubmit_subject]" value="' . ldl_get_setting( 'email_onsubmit_subject', 1 ) . '">';
            echo '<p class="description">' . __( 'Sent to the listing owner on submission of their information, prior to approval.', 'lddlite' ) . '</p>';
        }

        function _f_email_onsubmit_body() {
            wp_editor( ldl_get_setting( 'email_onsubmit_body' ), 'lite-email_onsubmit_body', array( 'textarea_name' => 'lddlite_settings[email_onsubmit_body]', 'textarea_rows' => 5 ) );
        }

        function _f_email_onapprove_subject() {
            echo '<input id="email_onapprove_subject" type="text" size="80" name="lddlite_settings[email_onapprove_subject]" value="' . ldl_get_setting( 'email_onapprove_subject', 1 ) . '">';
            echo '<p class="description">' . __( 'Sent to the listing owner when the site administrator approves their listing.', 'lddlite' ) . '</p>';
        }

        function _f_email_onapprove_body() {
            wp_editor( ldl_get_setting( 'email_onapprove_body' ), 'ld_email_onapprove_body', array( 'textarea_name' => 'lddlite_settings[email_onapprove_body]', 'textarea_rows' => 5 ) );
        }


        if ( ldl_get_setting( 'public_or_private' ) ) {
            add_settings_section( 'lddlite_settings_submit', __return_null(), '__return_false', 'lddlite_settings_submit' );

            add_settings_field( 'lddlite_settings[submit_use_tos]',         __( 'Include Terms', 'lddlite' ),                                                 '_f_submit_use_tos',         'lddlite_settings_submit', 'lddlite_settings_submit' );
            add_settings_field( 'lddlite_settings[submit_tos]',             '<label for="submit_tos">' . __( 'Terms of Service' , 'lddlite' ) . '</label>',   '_f_submit_tos',             'lddlite_settings_submit', 'lddlite_settings_submit' );
            add_settings_field( 'lddlite_settings[submit_other_separator]', '<span style="font-size: 18px">' . __( 'Other Settings', 'lddlite' ) . '</span>', '__return_false',            'lddlite_settings_submit', 'lddlite_settings_submit' );
            add_settings_field( 'lddlite_settings[submit_use_locale]',      __( 'Use Locale', 'lddlite' ),                                                    '_f_submit_use_locale',      'lddlite_settings_submit', 'lddlite_settings_submit' );
            add_settings_field( 'lddlite_settings[submit_locale]',          '<label for="submit_locale">' . __( 'Directory Locale', 'lddlite' ) . '</label>', '_f_submit_locale',          'lddlite_settings_submit', 'lddlite_settings_submit' );
	        add_settings_field( 'lddlite_settings[submit_require_address]', __( 'Require Address', 'lddlite' ),                                               '_f_submit_require_address', 'lddlite_settings_submit', 'lddlite_settings_submit' );

            function _s_settings_submit() {
                echo '<p>' . __( 'Control the way your submit form appears to people, and the information that is displayed on it.', 'lddlite' ) . '</p>';
            }

            function _f_submit_use_tos() {
                echo '<label for="lite-submit_use_tos"><input id="lite-submit_use_tos" type="checkbox" name="lddlite_settings[submit_use_tos]" value="1" ' . checked( ldl_get_setting( 'submit_use_tos' ), 1, 0 ) . '> <span>' . __( 'If checked, submission form will include terms of service', 'lddlite' ) . '</span></label>';
            }

            function _f_submit_tos() {
                wp_editor( ldl_get_setting( 'submit_tos' ), 'ldl_submit_tos', array( 'textarea_name' => 'lddlite_settings[submit_tos]', 'textarea_rows' => 5 ) );
            }

            function _f_submit_use_locale() {
                echo '<label for="lite-submit_use_locale"><input type="checkbox" name="lddlite_settings[submit_use_locale]" value="1" ' . checked( ldl_get_setting( 'submit_use_locale' ), 1, 0 ) . '> <span>' . __( 'If checked, set locale below', 'lddlite' ) . '</span></label>';
            }

            function _f_submit_locale() {
                echo ldl_dropdown_country( 'lddlite_settings[submit_locale]', ldl_get_setting( 'submit_locale' ) );
            }

	        function _f_submit_require_address() {
		        echo '<label for="lite-submit_require_address"><input id="lite-submit_require_address" type="checkbox" name="lddlite_settings[submit_require_address]" value="1" ' . checked( ldl_get_setting( 'submit_require_address' ), 1, 0 ) . '> <span>' . __( 'If checked, users will be required to enter their address', 'lddlite' ) . '</span></label>';
	        }

        }


        add_settings_section( 'lddlite_settings_appearance', __return_null(), '_s_settings_appearance', 'lddlite_settings_appearance' );

        add_settings_field( 'lddlite_settings[appearance_display_new]',      __( 'Display New Listings', 'lddlite' ),                                                                  '_f_appearance_display_new',      'lddlite_settings_appearance', 'lddlite_settings_appearance' );
        add_settings_field( 'lddlite_settings[appearance_panel_background]', '<label for="appearance_panel_background">' . __( 'Homepage Header Background', 'lddlite' ) . '</label>', '_f_appearance_panel_background', 'lddlite_settings_appearance', 'lddlite_settings_appearance' );
        add_settings_field( 'lddlite_settings[appearance_panel_foreground]', '<label for="appearance_panel_foreground">' . __( 'Homepage Header Foreground', 'lddlite' ) . '</label>', '_f_appearance_panel_foreground', 'lddlite_settings_appearance', 'lddlite_settings_appearance' );

        function _s_settings_appearance() {
            echo '<p>' . __( "This section is brand new and currently not very all encompassing. Don't worry, there's a lot more yet to come!", 'lddlite' ) . '</p>';
        }

        function _f_appearance_display_new() {
            echo '<label for="lite-appearance_display_new"><input type="checkbox" name="lddlite_settings[appearance_display_new]" value="1" ' . checked( ldl_get_setting( 'appearance_display_new' ), 1, 0 ) . '> <span>' . __( 'If checked, front page will display thumbnails of your most recently added listings', 'lddlite' ) . '</span></label>';
        }

        function _f_appearance_panel_background() {
            echo '<input id="appearance_panel_background" type="text" name="lddlite_settings[appearance_panel_background]" value="' . ldl_get_setting( 'appearance_panel_background' ) . '" class="my-color-field" data-default-color="#3bafda">';
            echo '<p class="description">' . __( 'Controls the background color of the header used to display your directories name on the front page of the plugin.', 'lddlite' ) . '</p>';
        }

        function _f_appearance_panel_foreground() {
            echo '<input id="appearance_panel_foreground" type="text" name="lddlite_settings[appearance_panel_foreground]" value="' . ldl_get_setting( 'appearance_panel_foreground' ) . '" class="my-color-field" data-default-color="#fff">';
            echo '<p class="description">' . __( 'Same as the above, except for the foreground.', 'lddlite' ) . '</p>';
        }


        register_setting( 'lddlite_settings', 'lddlite_settings', array( $this, 'validate_settings' ) );
    }


    public function validate_settings( $input ) {

        if ( empty( $_POST['_wp_http_referer'] ) )
            return $input;

        $settings = wp_parse_args(
            get_option( 'lddlite_settings' ),
            ldl_get_default_settings() );

        parse_str( $_POST['_wp_http_referer'], $referrer );
        $tab      = isset( $referrer['tab'] ) ? $referrer['tab'] : 'general';

        $input = $input ? $input : array();
        $input = apply_filters( 'lddlite_settings_' . $tab . '_sanitize', $input );

        $output = array_merge( $settings, $input );

        add_settings_error( 'lddlite_settings', '', __( 'Settings updated.', 'lddlite' ), 'updated' );

        return $output;
    }


    public function add_settings_menu() {
        add_submenu_page( 'edit.php?post_type=' . LDDLITE_POST_TYPE, 'Directory Lite Configuration', 'Settings', 'manage_options', 'lddlite-settings', array( $this, 'settings_page' ) );
    }


    public function settings_page() {

        wp_enqueue_style( 'font-awesome' );
        wp_enqueue_style( 'lddlite-admin' );

        $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'general';

        ?>
        <div class="wrap directory-lite">
            <h2 class="heading"><?php _e( 'Directory Settings', 'lddlite' ); ?></h2>

            <div class="sub-heading">
                <p><?php _e( 'Customize your Directory using the settings found on the following pages. If you require support or would like to make a suggestion for improving this plugin, please refer to the following links.', 'lddlite' ); ?></p>
                <ul id="directory-links">
                    <li><a href="https://github.com/mwaterous/ldd-directory-lite/issues" title="Submit a bug or feature request on GitHub" class="bold-link"><i class="fa fa-exclamation-triangle fa-fw"></i> <?php _e( 'Submit an Issue', 'lddlite' ); ?></a></li>
                    <li class="right"><i class="fa fa-wordpress fa-fw"></i> Visit us on <a href="http://wordpress.org/support/plugin/ldd-directory-lite" title="Come visit the plugin homepage on WordPress.org"><?php _e( 'WordPress.org', 'lddlite' ); ?></a></li>
                    <li><a href="http://wordpress.org/support/plugin/ldd-directory-lite" title="Visit the LDD Directory Lite Support Forums on WordPress.org" class="bold-link"><i class="fa fa-comments fa-fw"></i> <?php _e( 'Support Forums', 'lddlite' ); ?></a></li>
                    <li class="right"><i class="fa fa-github-alt fa-fw"></i> Visit us on <a href="https://github.com/mwaterous/ldd-directory-lite" title="We do most of our development from GitHub, come join us!"><?php _e( 'GitHub.com', 'lddlite' ); ?></a></li>
                </ul>
            </div>

            <?php settings_errors( 'lddlite_settings' ) ?>

            <h2 class="nav-tab-wrapper">
                <a href="<?php echo add_query_arg( 'tab', 'general', remove_query_arg( 'settings-updated' ) ); ?>" class="nav-tab <?php echo $active_tab == 'general' ? 'nav-tab-active' : ''; ?>"><?php _e( 'General', 'lddlite' ); ?></a>
                <a href="<?php echo add_query_arg( 'tab', 'email',   remove_query_arg( 'settings-updated' ) ); ?>" class="nav-tab <?php echo $active_tab == 'email' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Email', 'lddlite' ); ?></a>
                <?php if ( ldl_get_setting( 'public_or_private' ) ): ?><a href="<?php echo add_query_arg( 'tab', 'submit',  remove_query_arg( 'settings-updated' ) ); ?>" class="nav-tab <?php echo $active_tab == 'submit' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Submit Form', 'lddlite' ); ?></a><?php endif; ?>
                <a href="<?php echo add_query_arg( 'tab', 'appearance',   remove_query_arg( 'settings-updated' ) ); ?>" class="nav-tab <?php echo $active_tab == 'appearance' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Appearance', 'lddlite' ); ?></a>
            </h2>

            <div id="tab_container">

                <form method="post" action="options.php">
                    <?php
                    settings_fields( 'lddlite_settings' );

                    if ( $active_tab == 'general' ) {
                        do_settings_sections( 'lddlite_settings_general' );
                    } elseif ( $active_tab == 'email' ) {
                        do_settings_sections( 'lddlite_settings_email' );
                    } elseif ( $active_tab == 'submit' ) {
                        do_settings_sections( 'lddlite_settings_submit' );
                    } elseif ( $active_tab == 'appearance' ) {
                        do_settings_sections( 'lddlite_settings_appearance' );
                    }

                    submit_button();
                    ?>

                </form>
            </div><!-- #tab_container-->
        </div><!-- .wrap -->
    <?php

    }


}

// Get... in!
LDD_Directory_Admin::get_in();


function lddlite_settings_general_sanitize( $input ) {

    if ( isset( $input['debug_uninstall'] ) ) {
        define( 'WP_UNINSTALL_PLUGIN', true );
        require_once( LDDLITE_PATH . '/uninstall.php' );
    }

    $input['directory_label']   = wp_filter_nohtml_kses( $input['directory_label'] );
    $input['disable_bootstrap'] = '1' == $input['disable_bootstrap'] ? 1 : 0;
    $input['public_or_private'] = '0' == $input['public_or_private'] ? 0 : 1;
    $input['google_maps']       = '0' == $input['google_maps'] ? 0 : 1;

    return $input;
}


function lddlite_settings_email_sanitize( $input ) {

    $input['email_toadmin_subject']   = wp_filter_nohtml_kses( $input['email_toadmin_subject'] );
    $input['email_onsubmit_subject']  = wp_filter_nohtml_kses( $input['email_onsubmit_subject'] );
    $input['email_onapprove_subject'] = wp_filter_nohtml_kses( $input['email_onapprove_subject'] );

    return $input;
}


function lddlite_settings_submit_sanitize( $input ) {


    $input['submit_use_tos']         = '1' == $input['submit_use_tos'] ? 1 : 0;
    $input['submit_tos']             = wp_filter_nohtml_kses( $input['submit_tos'] );
	$input['submit_use_locale']      = '1' == $input['submit_use_locale'] ? 1 : 0;
	$input['submit_require_address'] = '1' == $input['submit_require_address'] ? 1 : 0;

    return $input;
}


function lddlite_settings_appearance_sanitize( $input ) {

    $input['appearance_display_new'] = '1' == $input['appearance_display_new'] ? 1 : 0;

    if ( !preg_match( '~#([a-fA-F0-9]){3}(([a-fA-F0-9]){3})?\b~', $input['appearance_panel_background'] ) )
        $input['appearance_primary'] = '#c0ffee';
    if ( !preg_match( '~#([a-fA-F0-9]){3}(([a-fA-F0-9]){3})?\b~', $input['appearance_panel_foreground'] ) )
        $input['appearance_primary'] = '#fff';

    return $input;
}

add_filter( 'lddlite_settings_general_sanitize', 'lddlite_settings_general_sanitize' );
add_filter( 'lddlite_settings_email_sanitize', 'lddlite_settings_email_sanitize' );
add_filter( 'lddlite_settings_submit_sanitize', 'lddlite_settings_submit_sanitize' );
add_filter( 'lddlite_settings_appearance_sanitize', 'lddlite_settings_appearance_sanitize' );
