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

            register_setting( 'lddlite-options', 'lddlite-options', array( $this, 'validate_settings' ) );


            add_settings_section( 'lddlite-settings-general', '', '_s_general_section', 'lddlite-settings' );
            function _s_general_section() {
                // Leave review link out until it can be turned off (option? cookie? option.)
                ?>

                <p>LDD Business Directory [lite] configuration settings can all be found here. If you require support, or would like to make a suggestion for improving this plugin, please refer to the following links.</p>
                <ul id="directory-links">
                    <li>Visit us on <a href="http://wordpress.org/support/plugin/ldd-directory-lite" title="Come visit the plugin homepage on WordPress.org">WordPress.org</a></li>
                    <li class="right"><a href="https://github.com/mwaterous/ldd-directory-lite/issues" title="Submit a bug or feature request on GitHub" class="bold-link">Submit an Issue</a></li>
                    <li>Visit us on <a href="https://github.com/mwaterous/ldd-directory-lite" title="We do most of our development from GitHub, come join us!">GitHub.com</a></li>
                    <li class="right"><a href="http://wordpress.org/support/plugin/ldd-directory-lite" title="Visit the LDD Directory [lite] Support Forums on WordPress.org" class="bold-link">Support Forums</a></li>
                </ul>
                <?php

            }

            add_settings_field( 'directory_label',          '<label for="directory_label">' . __( 'Directory Label' , ldd::$slug ) . '</label>',        '_f_directory_label',       'lddlite-settings', 'lddlite-settings-general' );
            add_settings_field( 'directory_description',    '<label for="directory_label">' . __( 'Directory Description' , ldd::$slug ) . '</label>',  '_f_directory_description', 'lddlite-settings', 'lddlite-settings-general' );
            add_settings_field( 'disable_bootstrap',        '<label for="public_or_private">' . __( 'Disable Bootstrap', ldd::$slug ) . '</label>',     '_f_disable_bootstrap',     'lddlite-settings', 'lddlite-settings-general' );
            add_settings_field( 'public_or_private',        '<label for="public_or_private">' . __( 'Public Directory', ldd::$slug ) . '</label>',      '_f_public_or_private',     'lddlite-settings', 'lddlite-settings-general' );
            add_settings_field( 'google_maps',              '<label for="google_maps">' . __( 'Use Google Maps', ldd::$slug ) . '</label>',             '_f_google_maps',           'lddlite-settings', 'lddlite-settings-general' );

            function _f_directory_label() {
                echo '<input id="directory_label" type="text" size="80" name="lddlite-options[directory_label]" value="' . ldd::opt( 'directory_label', 1 ) . '">';
                echo '<p class="description">' . __( 'Name your directory; "My Business Directory", "Local Restaurant Feed", "John\'s List of Links", etc.', ldd::$slug ) . '</p>';
            }

            function _f_directory_description() {
                wp_editor( ldd::opt( 'directory_description' ), 'ld_directory_description', array( 'textarea_name' => 'lddlite-options[directory_description]', 'textarea_rows' => 5 ) );
            }

            function _f_disable_bootstrap() {
                echo '<label title=""><input type="checkbox" name="lddlite-options[disable_bootstrap]" value="1" ' . checked( ldd::opt( 'disable_bootstrap' ), 1, 0 ) . '> <span>Disable</span></label>';
                echo '<p class="description">A lot of themes already use bootstrap; if yours is one, disable the plugin from loading another copy.</p>';
            }

            function _f_public_or_private() {
                $lddlite = ldd::load();

                echo '<label title=""><input type="radio" name="lddlite-options[public_or_private]" value="1" ' . checked( $lddlite->options['public_or_private'], 1, 0 ) . '> <span>Yes</span></label><br />';
                echo '<label title=""><input type="radio" name="lddlite-options[public_or_private]" value="0" ' . checked( $lddlite->options['public_or_private'], 0, 0 ) . '> <span>No</span></label><br />';
                echo '<p class="description">Determines whether features such as "Submit a Listing" are available.</p>';

            }

            function _f_google_maps() {
                $lddlite = ldd::load();

                echo '<label title=""><input type="radio" name="lddlite-options[google_maps]" value="1" ' . checked( $lddlite->options['google_maps'], 1, 0 ) . '> <span>Yes</span></label><br />';
                echo '<label title=""><input type="radio" name="lddlite-options[google_maps]" value="0" ' . checked( $lddlite->options['google_maps'], 0, 0 ) . '> <span>No</span></label><br />';
                echo '<p class="description">Display Google Maps on listing pages?</p>';

            }


            // @TODO Compartmentalize this, as if it was a module.
            add_settings_section( 'lddlite-settings-email', 'Email Settings', '_s_email_settings_section', 'lddlite-settings' );
            function _s_email_settings_section() {
                echo '<p>'.__( 'The following configuration options control how outgoing emails from Business Directory [lite] are handled.', ldd::$slug ).'</p>';
            }


            add_settings_field( 'email_replyto', '<label for="email_replyto">' . __( 'Email Reply-to' , ldd::$slug ) . '</label>', '_f_email_replyto', 'lddlite-settings', 'lddlite-settings-email' );
            function _f_email_replyto() {
                echo '<input id="email_replyto" type="text" size="80" name="lddlite-options[email_replyto]" value="' . ldd::opt( 'email_replyto', 1 ) . '" style="margin-bottom: 2em;">';
            }


            add_settings_field( 'email_toadmin_subject', '<label for="email_toadmin_subject">' . __( 'Administrator Notification Email' , ldd::$slug ) . '</label>', '_f_email_toadmin_subject', 'lddlite-settings', 'lddlite-settings-email' );
            add_settings_field( 'email_toadmin_body', '<label for="email_toadmin_body" class="screen-reader-text">' . __( 'Email Body' , ldd::$slug ) . '</label>', '_f_email_toadmin_body', 'lddlite-settings', 'lddlite-settings-email' );

            function _f_email_toadmin_subject() {
                echo '<input id="email_toadmin_subject" type="text" size="80" name="lddlite-options[email_toadmin_subject]" value="' . ldd::opt( 'email_toadmin_subject', 1 ) . '">';
                echo '<p class="description">Sent to the site administrator when a listing is submitted and pending approval.</p>';
            }

            function _f_email_toadmin_body() {
                wp_editor( ldd::opt( 'email_toadmin_body' ), 'ld_email_toadmin_body', array( 'textarea_name' => 'lddlite-options[email_toadmin_body]', 'textarea_rows' => 5 ) );
            }


            add_settings_field( 'email_onsubmit_subject', '<label for="email_onsubmit_subject">' . __( 'Listing Submission' , ldd::$slug ) . '</label>', '_f_email_onsubmit_subject', 'lddlite-settings', 'lddlite-settings-email' );
            add_settings_field( 'email_onsubmit_body', '<label for="email_onsubmit_body" class="screen-reader-text">' . __( 'Email Body' , ldd::$slug ) . '</label>', '_f_email_onsubmit_body', 'lddlite-settings', 'lddlite-settings-email' );

            function _f_email_onsubmit_subject() {
                echo '<input id="email_onsubmit_subject" type="text" size="80" name="lddlite-options[email_onsubmit_subject]" value="' . ldd::opt( 'email_onsubmit_subject', 1 ) . '">';
                echo '<p class="description">Sent to the listing owner on submission of their information, prior to approval.</p>';
            }

            function _f_email_onsubmit_body() {
                wp_editor( ldd::opt( 'email_onsubmit_body' ), 'ld_email_onsubmit_body', array( 'textarea_name' => 'lddlite-options[email_onsubmit_body]', 'textarea_rows' => 5 ) );
            }


            add_settings_field( 'email_onapprove_subject', '<label for="email_onapprove_subject">' . __( 'Listing Approval' , ldd::$slug ) . '</label>', '_f_email_onapprove_subject', 'lddlite-settings', 'lddlite-settings-email' );
            add_settings_field( 'email_onapprove_body', '<label for="email_onapprove_body" class="screen-reader-text">' . __( 'Email Body' , ldd::$slug ) . '</label>', '_f_email_onapprove_body', 'lddlite-settings', 'lddlite-settings-email' );

            function _f_email_onapprove_subject() {
                echo '<input id="email_onapprove_subject" type="text" size="80" name="lddlite-options[email_onapprove_subject]" value="' . ldd::opt( 'email_onapprove_subject', 1 ) . '">';
                echo '<p class="description">Sent to the listing owner when the site administrator approves their listing.</p>';
            }

            function _f_email_onapprove_body() {
                wp_editor( ldd::opt( 'email_onapprove_body' ), 'ld_email_onapprove_body', array( 'textarea_name' => 'lddlite-options[email_onapprove_body]', 'textarea_rows' => 5 ) );
            }


        }


            public function validate_settings( $input ) {

                //@todo separate version from settings. whynot.
                $input['version'] = ldd::opt( 'version' );

                if ( $input['disable_bootstrap'] != 0 )
                    $input['disable_bootstrap'] = 1;

                if ( $input['public_or_private'] != 0 )
                    $input['public_or_private'] = 1;

                if ( $input['google_maps'] != 0 )
                    $input['google_maps'] = 1;

                $input['email_onsubmit_subject'] = wp_filter_nohtml_kses( $input['email_onsubmit_subject'] );
                $input['email_onapprove'] = wp_filter_nohtml_kses( $input['email_onapprove'] );

                return $input;
            }


    public function add_settings_menu() {
        $slug = add_submenu_page( 'edit.php?post_type=' . LDDLITE_POST_TYPE, 'Directory [lite] Configuration', 'Settings', 'manage_options', 'lddlite-settings', array( $this, 'settings_page' ) );
    }


        public function settings_page() {

            wp_enqueue_style( ldd::$slug . '-admin' );

            ?>
            <div class="wrap">
                <h2>Directory <span class="lite">[lite]</span> <?php _e( 'Settings', ldd::$slug ); ?></h2>

                <form method="post" action="options.php">
                    <?php settings_fields( 'lddlite-options' ); ?>
                    <?php do_settings_sections( 'lddlite-settings' ); ?>
                    <?php submit_button(); ?>
                </form>
            </div>
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

