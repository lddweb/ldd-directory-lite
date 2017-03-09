<?php
/**
 * Primary class for the plugin settings administrative interface.
 *
 * This
 * @package   ldd_directory_lite
 * @author    LDD Web Design <info@lddwebdesign.com>
 * @license   GPL-2.0+
 * @link      http://lddwebdesign.com
 * @copyright 2014 LDD Consulting, Inc
 */

function ldl_add_settings_menu() {
    add_submenu_page('edit.php?post_type=' . LDDLITE_POST_TYPE, 'LDD Directory '. __( 'Configuration', 'ldd-directory-lite' ), __('Settings'), 'manage_options', 'lddlite-settings', 'ldl_settings_page');
    add_submenu_page('edit.php?post_type=' . LDDLITE_POST_TYPE, 'LDD Directory '. __( 'Add-ons', 'ldd-directory-lite' ), __('Add-ons'), 'manage_options', 'lddlite-addons', 'ldl_addons_page');
    add_submenu_page('edit.php?post_type=' . LDDLITE_POST_TYPE, 'LDD Directory '. __( 'Help', 'ldd-directory-lite' ), __('Help'), 'manage_options', 'lddlite-help', 'ldl_help_page');
}

add_action('admin_menu', 'ldl_add_settings_menu');


/*
 * Addons Page.
 * */
function ldl_addons_page() {
    wp_enqueue_style('lddlite-admin');
    wp_enqueue_style('font-awesome');
    include(LDDLITE_PATH."/templates/backend/addon-page.php");
}

/*
 * Help Page.
 * */
function ldl_help_page() {
    wp_enqueue_style('lddlite-admin');
    wp_enqueue_style('font-awesome');
    include(LDDLITE_PATH."/templates/backend/help-page.php");
}

/**
 * Options Page
 *
 * Renders the options page contents.
 *
 * @since 1.0
 * @global $edd_options Array of all the EDD Options
 * @return void
 */
function ldl_settings_page() {

    wp_enqueue_style('font-awesome');
    wp_enqueue_style('lddlite-admin');

    $active_tab = isset( $_GET[ 'tab' ] ) && array_key_exists( $_GET['tab'], ldl_get_settings_tabs() ) ? $_GET[ 'tab' ] : 'general';

    ?>
    <div class="wrap directory-lite">
        <h2 class="heading"><?php _e('Directory Settings', 'ldd-directory-lite'); ?></h2>

        <div class="sub-heading">
            <p><?php _e('Customize your Directory using the settings found on the following pages. If you require support or would like to make a suggestion for improving this plugin, please refer to the following links.', 'ldd-directory-lite'); ?></p>
            <ul id="directory-links">
                <li><?php printf( __( '<a href="%1$s" title="Submit a bug or feature request on GitHub" class="bold-link"><i class="fa fa-exclamation-triangle fa-fw"></i>Submit an Issue</a>', 'ldd-directory-lite' ), esc_url('https://github.com/lddweb/ldd-directory-lite/issues') ); ?></li>
                <li class="right"><?php printf( __( '<i class="fa fa-wordpress fa-fw"></i>Visit us on <a href="%1$s" title="Come visit the plugin homepage on WordPress.org">%2$s</a>', 'ldd-directory-lite' ), esc_url('https://wordpress.org/support/plugin/ldd-directory-lite'), 'WordPress.org' ); ?></li>
                <li><?php printf( __( '<a href="%1$s" title="Visit the LDD Directory Lite Support Forums on WordPress.org" class="bold-link"><i class="fa fa-comments fa-fw"></i>Support Forums</a>', 'ldd-directory-lite' ), esc_url('https://wordpress.org/support/plugin/ldd-directory-lite') ); ?></li>
                <li class="right"><?php printf( __( '<i class="fa fa-github-alt fa-fw"></i>Visit us on <a href="%1$s" title="We do most of our development from GitHub, come join us!">%2$s</a>', 'ldd-directory-lite' ), esc_url('https://github.com/lddweb/ldd-directory-lite'), 'GitHub.com' ); ?></li>
                <li><?php printf( __( '<a href="%1$s" title="Rate this plugin on WordPress.org" class="bold-link"><i class="fa fa-star fa-fw"></i>Rate this Plugin</a>', 'ldd-directory-lite' ), esc_url('https://wordpress.org/support/plugin/ldd-directory-lite/reviews/#new-topic-0') ); ?></li>
            </ul>
        </div>

        <?php settings_errors('lddlite_settings') ?>

        <h2 class="nav-tab-wrapper">
            <?php
            foreach( ldl_get_settings_tabs() as $tab_id => $tab_name ) {

                $tab_url = add_query_arg( array(
                    'settings-updated' => false,
                    'tab' => $tab_id
                ) );

                $active = $active_tab == $tab_id ? ' nav-tab-active' : '';

                echo '<a href="' . esc_url( $tab_url ) . '" title="' . esc_attr( $tab_name ) . '" class="nav-tab' . $active . '">';
                echo esc_html( $tab_name );
                echo '</a>';
            }
            ?>
        </h2>

        <div id="tab_container">

            <form method="post" action="options.php">
                <table class="form-table ldd-settings-table">
                    <?php
                    settings_fields('lddlite_settings');
                    do_settings_fields( 'lddlite_settings_' . $active_tab, 'lddlite_settings_' . $active_tab );
                    ?>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <!-- #tab_container-->
    </div><!-- .wrap -->
<?php

}