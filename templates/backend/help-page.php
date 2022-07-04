<?php
/*
* File version: 2
*/
$tab = (!empty($_GET['tab']))? sanitize_text_field(($_GET['tab'])) : 'about';
function help_page_tabs($current = 'about')
{
    $tabs = array(
        'about'       => esc_html("About the plugin", 'ldd-directory-lite'),
        'shortcodes'  => esc_html("Shortcodes", 'ldd-directory-lite'),
        'templates'   => esc_html("Template Overriding", 'ldd-directory-lite')
    );
    $html = '<h2 class="nav-tab-wrapper">';
    foreach ($tabs as $tab => $name) {
        $class = ($tab == $current) ? 'nav-tab-active' : '';
        $html .= '<a class="nav-tab ' . $class . '" href="?post_type=directory_listings&page=lddlite-help&tab=' . $tab . '">' . $name . '</a>';
    }
    $html .= '</h2>';
    echo $html;
}
?>
    <form id="ldd_directory_lite_addon_admin" enctype="multipart/form-data" method="post">
    <div class="wrap about-wrap">
    <h3><?php esc_html_e('Welcome to LDD Directory Lite (' . LDDLITE_VERSION . ')', 'ldd-directory-lite'); ?></h3>
        <div class="sub-heading">
            <p><?php esc_html_e('Add new add-ons support to your LDD Directory Lite. If you require support or would like to make a suggestion for improving this plugin, please refer to the following links.', 'ldd-directory-lite'); ?></p>
            <ul id="directory-links">
                <li><?php printf( __( '<a href="%1$s" title="Submit a bug or feature request on GitHub" class="bold-link"><i class="fa fa-exclamation-triangle fa-fw"></i>Submit an Issue</a>', 'ldd-directory-lite' ), esc_url('https://github.com/lddweb/ldd-directory-lite/issues') ); ?></li>
                <li class="right"><?php printf( __( '<i class="fa fa-wordpress fa-fw"></i>Visit us on <a href="%1$s" title="Come visit the plugin homepage on WordPress.org">%2$s</a>', 'ldd-directory-lite' ), esc_url('https://wordpress.org/support/plugin/ldd-directory-lite'), 'WordPress.org' ); ?></li>
                <li><?php printf( __( '<a href="%1$s" title="Visit the LDD Directory Lite Support Forums on WordPress.org" class="bold-link"><i class="fa fa-comments fa-fw"></i>Support Forums</a>', 'ldd-directory-lite' ), esc_url('https://wordpress.org/support/plugin/ldd-directory-lite') ); ?></li>
                <li class="right"><?php printf( __( '<i class="fa fa-github-alt fa-fw"></i>Visit us on <a href="%1$s" title="We do most of our development from GitHub, come join us!">%2$s</a>', 'ldd-directory-lite' ), esc_url('https://github.com/lddweb/ldd-directory-lite'), 'GitHub.com' ); ?></li>
                <li><?php printf( __( '<a href="%1$s" title="Rate this plugin on WordPress.org" class="bold-link"><i class="fa fa-star fa-fw"></i>Rate this Plugin</a>', 'ldd-directory-lite' ), esc_url('https://wordpress.org/support/plugin/ldd-directory-lite/reviews/#new-topic-0') ); ?></li>
            </ul>
        </div>
        <?php
            /* Tabs displayed here... */
            help_page_tabs($tab);
            /* Tabs content displayed here... */
            ldl_get_template_part('backend/help', $tab);
        ?>
    </div>
        <!-- </div>/.wrap-->
    </form>