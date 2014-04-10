<?php


class _LDD_Directory_Admin
{

    /**
     * @var $_instance An instance of ones own instance
     */
    protected static $_instance = null;


    protected $_settings_slug = '';


    public static function get_in()
    {

        if ( !isset( self::$_instance ) && !( self::$_instance instanceof _LDD_Directory_Admin ) )
        {
            self::$_instance = new self;
            self::$_instance->action_filters();
        }

        return self::$_instance;
    }


    public function action_filters()
    {
        add_action( 'admin_init', array( $this, '_register_settings' ) );
        add_action( 'admin_menu', array( $this, '_add_settings_menu' ) );
    }


        public function _register_settings()
        {

            register_setting( 'lddlite-options', 'lddlite-options', array( $this, '_validate_settings' ) );


            add_settings_section( 'lddlite-settings-general', '', '_s_general_section', 'lddlite-settings' );
            function _s_general_section()
            {
                // Leave review link out until it can be turned off (option? cookie? option.)
                ?>

                <p>LDD Business Directory [lite] configuration settings can all be found here. If you require support, or would like to make a suggestion for improving this plugin, please refer to the following links.</p>
                <ul id="directory-links">
                    <li>Visit us on <a href="http://wordpress.org/support/plugin/ldd-directory-lite" title="Come visit the plugin homepage on WordPress.org">WordPress.org</a></li>
                    <li class="right"><a href="https://github.com/mwaterous/ldd-directory-lite/issues" title="Submit a bug or feature request on GitHub" class="bold-link">Submit an Issue</a></li>
                    <li>Visit us on <a href="https://github.com/mwaterous/ldd-directory-lite" title="We do most of our development from GitHub, come join us!">GitHub.com</a></li>
                    <li class="right"><a href="http://wordpress.org/support/plugin/ldd-directory-lite" title="Visit the LDD Directory [lite] Support Forums on WordPress.org" class="bold-link">Support Forums</a></li>
                    <!-- <li>Do you find this plugin useful? <a href="" title="" class="bold-link">Give us a short review!</a></li> -->
                </ul>
                <?php

            }

            add_settings_field( 'public_or_private', '<label for="public_or_private">' . __( 'Public Directory', lddslug() ) . '</label>', '_f_public_or_private', 'lddlite-settings', 'lddlite-settings-general' );
            function _f_public_or_private()
            {
                $lddlite = lddlite();

                echo '<label title=""><input type="radio" name="lddlite-options[public_or_private]" value="1" ' . checked( $lddlite->options['public_or_private'], 1, 0 ) . ' /> <span>Yes</span></label><br />';
                echo '<label title=""><input type="radio" name="lddlite-options[public_or_private]" value="0" ' . checked( $lddlite->options['public_or_private'], 0, 0 ) . ' /> <span>No</span></label><br />';
                echo '<p class="description">Determines whether features such as "Submit a Listing" are available.</p>';

            }

            add_settings_field( 'google_maps', '<label for="google_maps">' . __( 'Use Google Maps', lddslug() ) . '</label>', '_f_google_maps', 'lddlite-settings', 'lddlite-settings-general' );
            function _f_google_maps()
            {
                $lddlite = lddlite();

                echo '<label title=""><input type="radio" name="lddlite-options[google_maps]" value="1" ' . checked( $lddlite->options['google_maps'], 1, 0 ) . ' /> <span>Yes</span></label><br />';
                echo '<label title=""><input type="radio" name="lddlite-options[google_maps]" value="0" ' . checked( $lddlite->options['google_maps'], 0, 0 ) . ' /> <span>No</span></label><br />';
                echo '<p class="description">Display Google Maps on listing pages?</p>';

            }


            // @TODO Compartmentalize this, as if it was a module.
            add_settings_section( 'lddlite-settings-email', 'Email Settings', '_s_email_settings_section', 'lddlite-settings' );
            /**
             * @ignore
             */
            function _s_email_settings_section()
            {
                echo '<p>'.__( 'The following configuration options control how outgoing emails from Business Directory [lite] are handled.', lddslug() ).'</p>';
            }


            add_settings_field( 'email_onsubmit', '<label for="email_onsubmit">' . __( 'Listing Submitted' , lddslug() ) . '</label>', '_f_email_onsubmit', 'lddlite-settings', 'lddlite-settings-email' );
            /**
             * @ignore
             */
            function _f_email_onsubmit()
            {
                $lddlite = lddlite();
                echo '<input id="email_onsubmit" type="text" size="80" name="lddlite-options[email_onsubmit]" value="'.esc_attr( $lddlite->options['email_onsubmit'] ).'" />';
            }


            add_settings_field( 'email_onapprove', '<label for="email_onapprove">' . __( 'Listing Approved' , lddslug() ) . '</label>', '_f_email_onapprove', 'lddlite-settings', 'lddlite-settings-email' );
            /**
             * @ignore
             */
            function _f_email_onapprove()
            {
                $lddlite = lddlite();
                echo '<input id="email_onapprove" type="text" size="80" name="lddlite-options[email_onapprove]" value="'.esc_attr( $lddlite->options['email_onapprove'] ).'" />';
            }

        }


            public function _validate_settings( $input )
            {

                if ( $input['public_or_private'] != 0 ) {
                    $input['public_or_private'] = 1;
                }

                if ( $input['google_maps'] != 0 ) {
                    $input['google_maps'] = 1;
                }

                $input['email_onsubmit'] = wp_filter_nohtml_kses( $input['email_onsubmit'] );
                $input['email_onapprove'] = wp_filter_nohtml_kses( $input['email_onapprove'] );

                return $input;
            }


    public function _add_settings_menu()
    {
        $slug = add_submenu_page( 'edit.php?post_type=' . LDDLITE_POST_TYPE, 'Directory [lite] Configuration', 'Settings', 'edit_post', 'lddlite-settings', array( $this, '_settings_page' ) );
        add_action( 'admin_print_styles-' . $slug, array( $this, '_enqueue_styles' ) );
    }


        public function _settings_page()
        {

        ?>
            <div class="wrap">
                <h2>Directory <span class="lite">[lite]</span> <?php _e( 'Settings', lddslug() ); ?></h2>

                <form method="post" action="options.php">
                    <?php settings_fields( 'lddlite-options' ); ?>
                    <?php do_settings_sections( 'lddlite-settings' ); ?>
                    <?php submit_button(); ?>
                </form>
            </div>
        <?php

        }


        public function _enqueue_styles()
        {
            wp_enqueue_style( lddslug() . '-styles', LDDLITE_URL . '/public/css/admin.css', false, LDDLITE_VERSION );
        }


}

// Get... in!
_LDD_Directory_Admin::get_in();

