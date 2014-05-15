<?php


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
        add_action( 'admin_init', array( $this, 'register_settings' ) );
        add_action( 'admin_menu', array( $this, 'add_settings_menu' ) );
    }


    public function register_settings() {

        add_settings_section( 'lddlite_settings_general', __return_null(), '__return_false()', 'lddlite_settings_general' );

        add_settings_field( 'lddlite_settings[directory_label]',       '<label for="directory_label">' . __( 'Directory Label' , ldd::$slug ) . '</label>',       '_f_directory_label',       'lddlite_settings_general', 'lddlite_settings_general' );
        add_settings_field( 'lddlite_settings[directory_description]', '<label for="directory_label">' . __( 'Directory Description' , ldd::$slug ) . '</label>', '_f_directory_description', 'lddlite_settings_general', 'lddlite_settings_general' );
        add_settings_field( 'lddlite_settings[locale_separator]',      '<span style="font-size: 18px">' . __( 'Locale Settings', ldd::$slug ) . '</span>',        '__return_false',           'lddlite_settings_general', 'lddlite_settings_general' );
        add_settings_field( 'lddlite_settings[directory_use_locale]',  '<label for="directory_use_locale">' . __( 'Use Locale', ldd::$slug ) . '</label>',        '_f_directory_use_locale',  'lddlite_settings_general', 'lddlite_settings_general' );
        add_settings_field( 'lddlite_settings[directory_locale]',      '<label for="directory_locale">' . __( 'Directory Locale', ldd::$slug ) . '</label>',      '_f_directory_locale',      'lddlite_settings_general', 'lddlite_settings_general' );
        add_settings_field( 'lddlite_settings[other_separator]',       '<span style="font-size: 18px">' . __( 'Other Settings', ldd::$slug ) . '</span>',         '__return_false',           'lddlite_settings_general', 'lddlite_settings_general' );
        add_settings_field( 'lddlite_settings[disable_bootstrap]',     '<label for="public_or_private">' . __( 'Disable Bootstrap', ldd::$slug ) . '</label>',    '_f_disable_bootstrap',     'lddlite_settings_general', 'lddlite_settings_general' );
        add_settings_field( 'lddlite_settings[public_or_private]',     '<label for="public_or_private">' . __( 'Public Directory', ldd::$slug ) . '</label>',     '_f_public_or_private',     'lddlite_settings_general', 'lddlite_settings_general' );
        add_settings_field( 'lddlite_settings[google_maps]',           '<label for="google_maps">' . __( 'Use Google Maps', ldd::$slug ) . '</label>',            '_f_google_maps',           'lddlite_settings_general', 'lddlite_settings_general' );

        function _f_directory_label() {
            echo '<input id="directory_label" type="text" size="80" name="lddlite_settings[directory_label]" value="' . ldd::opt( 'directory_label', 1 ) . '">';
            echo '<p class="description">' . __( 'Name your directory; "My Business Directory", "Local Restaurant Feed", "John\'s List of Links", etc.', ldd::$slug ) . '</p>';
        }

        function _f_directory_description() {
            wp_editor( ldd::opt( 'directory_description' ), 'ld_directory_description', array( 'textarea_name' => 'lddlite_settings[directory_description]', 'textarea_rows' => 5 ) );
        }

        function _f_directory_use_locale() {
            echo '<label title=""><input type="checkbox" name="lddlite_settings[directory_use_locale]" value="1" ' . checked( ldd::opt( 'directory_use_locale' ), 1, 0 ) . '> <span>If checked, set locale below.</span></label>';
        }

        function _f_directory_locale() {

            if ( !function_exists( 'ld_dropdown_country' ) )
                require_once( LDDLITE_PATH . '/includes/actions/submit/functions.php' );
            echo ld_dropdown_country( 'lddlite_settings[directory_locale]', ldd::opt( 'directory_locale' ) );

        }

        function _f_disable_bootstrap() {
            echo '<label title=""><input type="checkbox" name="lddlite_settings[disable_bootstrap]" value="1" ' . checked( ldd::opt( 'disable_bootstrap' ), 1, 0 ) . '> <span>Disable</span></label>';
            echo '<p class="description">A lot of themes already use bootstrap; if yours is one, disable the plugin from loading another copy.</p>';
        }

        function _f_public_or_private() {
            $lddlite = ldd::load();

            echo '<label title=""><input type="radio" name="lddlite_settings[public_or_private]" value="1" ' . checked( $lddlite->options['public_or_private'], 1, 0 ) . '> <span>Yes</span></label><br />';
            echo '<label title=""><input type="radio" name="lddlite_settings[public_or_private]" value="0" ' . checked( $lddlite->options['public_or_private'], 0, 0 ) . '> <span>No</span></label><br />';
            echo '<p class="description">Determines whether features such as "Submit a Listing" are available.</p>';

        }

        function _f_google_maps() {
            $lddlite = ldd::load();

            echo '<label title=""><input type="radio" name="lddlite_settings[google_maps]" value="1" ' . checked( $lddlite->options['google_maps'], 1, 0 ) . '> <span>Yes</span></label><br />';
            echo '<label title=""><input type="radio" name="lddlite_settings[google_maps]" value="0" ' . checked( $lddlite->options['google_maps'], 0, 0 ) . '> <span>No</span></label><br />';
            echo '<p class="description">Display Google Maps on listing pages?</p>';

        }


        // @TODO Compartmentalize this, as if it was a module.
        add_settings_section( 'lddlite_settings_email', __return_null(), '_s_email_settings_section', 'lddlite_settings_email' );
        function _s_email_settings_section() {
            echo '<p>'.__( 'The following configuration options control how outgoing emails from Business Directory [lite] are handled.', ldd::$slug ).'</p>';
        }


        add_settings_field( 'lddlite_settings[email_replyto]',           '<label for="email_replyto">' . __( 'Email Reply-to' , ldd::$slug ) . '</label>',                               '_f_email_replyto',           'lddlite_settings_email', 'lddlite_settings_email' );
        add_settings_field( 'lddlite_settings[email_toadmin_subject]',   '<label for="email_toadmin_subject">' . __( 'Administrator Notification Email' , ldd::$slug ) . '</label>',     '_f_email_toadmin_subject',   'lddlite_settings_email', 'lddlite_settings_email' );
        add_settings_field( 'lddlite_settings[email_toadmin_body]',      '<label for="email_toadmin_body" class="screen-reader-text">' . __( 'Email Body' , ldd::$slug ) . '</label>',   '_f_email_toadmin_body',      'lddlite_settings_email', 'lddlite_settings_email' );
        add_settings_field( 'lddlite_settings[email_onsubmit_subject]',  '<label for="email_onsubmit_subject">' . __( 'Listing Submission' , ldd::$slug ) . '</label>',                  '_f_email_onsubmit_subject',  'lddlite_settings_email', 'lddlite_settings_email' );
        add_settings_field( 'lddlite_settings[email_onsubmit_body]',     '<label for="email_onsubmit_body" class="screen-reader-text">' . __( 'Email Body' , ldd::$slug ) . '</label>',  '_f_email_onsubmit_body',     'lddlite_settings_email', 'lddlite_settings_email' );
        add_settings_field( 'lddlite_settings[email_onapprove_subject]', '<label for="email_onapprove_subject">' . __( 'Listing Approval' , ldd::$slug ) . '</label>',                   '_f_email_onapprove_subject', 'lddlite_settings_email', 'lddlite_settings_email' );
        add_settings_field( 'lddlite_settings[email_onapprove_body]',    '<label for="email_onapprove_body" class="screen-reader-text">' . __( 'Email Body' , ldd::$slug ) . '</label>', '_f_email_onapprove_body',    'lddlite_settings_email', 'lddlite_settings_email' );

        function _f_email_replyto() {
            echo '<input id="email_replyto" type="text" size="80" name="lddlite_settings[email_replyto]" value="' . ldd::opt( 'email_replyto', 1 ) . '" style="margin-bottom: 2em;">';
        }

        function _f_email_toadmin_subject() {
            echo '<input id="email_toadmin_subject" type="text" size="80" name="lddlite_settings[email_toadmin_subject]" value="' . ldd::opt( 'email_toadmin_subject', 1 ) . '">';
            echo '<p class="description">Sent to the site administrator when a listing is submitted and pending approval.</p>';
        }

        function _f_email_toadmin_body() {
            wp_editor( ldd::opt( 'email_toadmin_body' ), 'ld_email_toadmin_body', array( 'textarea_name' => 'lddlite_settings[email_toadmin_body]', 'textarea_rows' => 5 ) );
        }

        function _f_email_onsubmit_subject() {
            echo '<input id="email_onsubmit_subject" type="text" size="80" name="lddlite_settings[email_onsubmit_subject]" value="' . ldd::opt( 'email_onsubmit_subject', 1 ) . '">';
            echo '<p class="description">Sent to the listing owner on submission of their information, prior to approval.</p>';
        }

        function _f_email_onsubmit_body() {
            wp_editor( ldd::opt( 'email_onsubmit_body' ), 'ld_email_onsubmit_body', array( 'textarea_name' => 'lddlite_settings[email_onsubmit_body]', 'textarea_rows' => 5 ) );
        }

        function _f_email_onapprove_subject() {
            echo '<input id="email_onapprove_subject" type="text" size="80" name="lddlite_settings[email_onapprove_subject]" value="' . ldd::opt( 'email_onapprove_subject', 1 ) . '">';
            echo '<p class="description">Sent to the listing owner when the site administrator approves their listing.</p>';
        }

        function _f_email_onapprove_body() {
            wp_editor( ldd::opt( 'email_onapprove_body' ), 'ld_email_onapprove_body', array( 'textarea_name' => 'lddlite_settings[email_onapprove_body]', 'textarea_rows' => 5 ) );
        }

        register_setting( 'lddlite_settings', 'lddlite_settings', array( $this, 'validate_settings' ) );

    }


    public function validate_settings( $input ) {

        if ( empty( $_POST['_wp_http_referer'] ) )
            return $input;

        $options = get_option( 'lddlite_settings' );

        parse_str( $_POST['_wp_http_referer'], $referrer );
        $tab      = isset( $referrer['tab'] ) ? $referrer['tab'] : 'general';

        $input = $input ? $input : array();
        $input = apply_filters( 'lddlite_settings_' . $tab . '_sanitize', $input );

        $output = array_merge( $options, $input );

        add_settings_error( 'lddlite_settings', '', __( 'Settings updated.', ldd::$slug ), 'updated' );

        return $output;
    }


    public function add_settings_menu() {
        add_submenu_page( 'edit.php?post_type=' . LDDLITE_POST_TYPE, 'Directory [lite] Configuration', 'Settings', 'manage_options', 'lddlite-settings', array( $this, 'settings_page' ) );
    }


    public function settings_page() {

        wp_enqueue_style( ldd::$slug . '-admin' );
        $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'general';

        ?>
        <div class="wrap">
            <h2>Directory <span class="lite">[lite]</span> <?php _e( 'Settings', ldd::$slug ); ?></h2>

            <?php settings_errors( 'lddlite_settings' ) ?>

            <p>LDD Business Directory [lite] configuration settings can all be found here. If you require support, or would like to make a suggestion for improving this plugin, please refer to the following links.</p>
            <ul id="directory-links">
                <li><a href="https://github.com/mwaterous/ldd-directory-lite/issues" title="Submit a bug or feature request on GitHub" class="bold-link">Submit an Issue</a></li>
                <li class="right">Visit us on <a href="http://wordpress.org/support/plugin/ldd-directory-lite" title="Come visit the plugin homepage on WordPress.org">WordPress.org</a></li>
                <li><a href="http://wordpress.org/support/plugin/ldd-directory-lite" title="Visit the LDD Directory [lite] Support Forums on WordPress.org" class="bold-link">Support Forums</a></li>
                <li class="right">Visit us on <a href="https://github.com/mwaterous/ldd-directory-lite" title="We do most of our development from GitHub, come join us!">GitHub.com</a></li>
            </ul>


            <h2 class="nav-tab-wrapper">
                <a href="<?php echo add_query_arg( 'tab', 'general', remove_query_arg( 'settings-updated' ) ); ?>" class="nav-tab <?php echo $active_tab == 'general' ? 'nav-tab-active' : ''; ?>"><?php _e( 'General', ldd::$slug ); ?></a>
                <a href="<?php echo add_query_arg( 'tab', 'email',   remove_query_arg( 'settings-updated' ) ); ?>" class="nav-tab <?php echo $active_tab == 'email' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Email', ldd::$slug ); ?></a>
            </h2>

            <div id="tab_container">

                <form method="post" action="options.php">
                    <?php
                    if ( $active_tab == 'general' ) {
                        settings_fields( 'lddlite_settings' );
                        do_settings_sections( 'lddlite_settings_general' );
                    } elseif ( $active_tab == 'email' ) {
                        settings_fields( 'lddlite_settings' );
                        do_settings_sections( 'lddlite_settings_email' );
                    }

                    submit_button();
                    ?>

                </form>
            </div><!-- #tab_container-->
        </div><!-- .wrap -->
    <?php

    }


    public function enqueue_scripts() {
        global $post_type;

        if( LDDLITE_POST_TYPE == $post_type )
            wp_enqueue_script( 'post' );

    }

}

// Get... in!
LDD_Directory_Admin::get_in();


function lddlite_settings_general_sanitize( $input ) {

    $input['directory_label']   = wp_filter_nohtml_kses( $input['directory_label'] );

    $input['disable_bootstrap'] = ( '1' == $input['disable_bootstrap'] ) ? 1 : 0;
    $input['public_or_private'] = ( '0' == $input['public_or_private'] ) ? 0 : 1;
    $input['google_maps']       = ( '0' == $input['google_maps'] ) ? 0 : 1;

    return $input;
}
add_filter( 'lddlite_settings_general_sanitize', 'lddlite_settings_general_sanitize' );


function lddlite_settings_email_sanitize( $input ) {

    if ( !is_email( $input['email_replyto'] ) ) {
        $input['email_replyto'] = '';
        add_settings_error( 'lddlite_settings', '', __( 'Please enter a valid email address.', ldd::$slug ), 'error' );
    }

    $input['email_toadmin_subject'] = wp_filter_nohtml_kses( $input['email_toadmin_subject'] );
    $input['email_onsubmit_subject'] = wp_filter_nohtml_kses( $input['email_onsubmit_subject'] );
    $input['email_onapprove_subject'] = wp_filter_nohtml_kses( $input['email_onapprove_subject'] );

    return $input;
}
add_filter( 'lddlite_settings_email_sanitize', 'lddlite_settings_email_sanitize' );
