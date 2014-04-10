<?php


class _LDD_Directory_Admin
{

    /**
     * @var $_instance An instance of ones own instance
     */
    protected static $_instance = null;


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


            // @TODO Compartmentalize this, as if it was a module.
            add_settings_section( 'lddlite-settings-email', 'LDD Business Directory Email', '_s_email_settings_section', 'lddlite-settings' );
            /**
             * @ignore
             */
            function _s_email_settings_section()
            {
                echo '<p>'.__( 'Configure Business Directory email settings here.', lddslug() ).'</p>';
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
            function dirl_callback_onapprove()
            {
                $lddlite = lddlite();
                echo '<input id="email_onapprove" type="text" size="80" name="lddlite-options[email_onapprove]" value="'.esc_attr( $lddlite->options['email_onapprove'] ).'" />';
            }

        }


            public function _validate_settings( $input )
            {
                $input['email_onsubmit'] = wp_filter_nohtml_kses( $input['email_onsubmit'] );
                $input['email_onapprove'] = wp_filter_nohtml_kses( $input['email_onapprove'] );

                return $input;
            }


    public function _add_settings_menu()
    {
        $menu_slug = add_submenu_page( 'edit.php?post_type=' . LDDLITE_POST_TYPE, 'Directory [lite] Configuration', 'Settings', 'edit_post', 'lddlite-settings', array( $this, '_settings_page' ) );
    }


        public function _settings_page()
        {


    ?>
        <div class="wrap">
            <h2><?php _e('Directory Settings', 'lddbd'); ?></h2>

            <form method="post" action="options.php">
                <?php settings_fields( 'lddlite-options' ); ?>
                <?php do_settings_sections( 'lddlite-settings' ); ?>
                <p class="submit">
                    <input type="submit" class="button-primary" value="<?php _e('Save Changes', 'lddbd') ?>" />
                </p>
            </form>
        </div>
    <?php
    }

}

// Get... in!
_LDD_Directory_Admin::get_in();

