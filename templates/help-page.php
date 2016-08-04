<?php
$tab = (!empty($_GET['tab']))? esc_attr($_GET['tab']) : 'about';
function help_page_tabs($current = 'about')
{
    $tabs = array(
        'about'       => __("About the plugin", 'ldd-directory-lite'),
        'shortcodes'  => __("Shortcodes", 'ldd-directory-lite'),
        'templates'   => __("Template Overriding", 'ldd-directory-lite')
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
    <h3><?php _e('Welcome to LDD Directory Lite (' . LDDLITE_VERSION . ')', 'ldd-directory-lite'); ?></h3>
        <div class="sub-heading">
            <p><?php _e('Add new add-ons support to your LDD Directory Lite. If you require support or would like to make a suggestion for improving this plugin, please refer to the following links.', 'ldd-directory-lite'); ?></p>
            <ul id="directory-links">
                <li><a href="https://github.com/lddweb/ldd-directory-lite/issues"
                       title="Submit a bug or feature request on GitHub" class="bold-link"><i
                            class="fa fa-exclamation-triangle fa-fw"></i> <?php _e('Submit an Issue', 'ldd-directory-lite'); ?>
                    </a></li>
                <li class="right"><i class="fa fa-wordpress fa-fw"></i> Visit us on <a
                        href="http://wordpress.org/support/plugin/ldd-directory-lite"
                        title="Come visit the plugin homepage on WordPress.org"><?php _e('WordPress.org', 'ldd-directory-lite'); ?></a>
                </li>
                <li><a href="http://wordpress.org/support/plugin/ldd-directory-lite"
                       title="Visit the LDD Directory Lite Support Forums on WordPress.org" class="bold-link"><i
                            class="fa fa-comments fa-fw"></i> <?php _e('Support Forums', 'ldd-directory-lite'); ?>
                    </a>
                </li>
                <li class="right"><i class="fa fa-github-alt fa-fw"></i> Visit us on <a
                        href="https://github.com/lddweb/ldd-directory-lite"
                        title="We do most of our development from GitHub, come join us!"><?php _e('GitHub.com', 'ldd-directory-lite'); ?></a>
                </li>
            </ul>
        </div>
        <?php
            /* Tabs displayed here... */
            help_page_tabs($tab);
            /* Tabs content displayed here... */
            ldl_get_template_part('help', $tab);
        ?>
    </div>
        <!-- </div>/.wrap-->
    </form>