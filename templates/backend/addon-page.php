<?php
/*
 * Fetch records from LDD Plugins for Addons.
 */
$addons     = array();
$addons[]   = array(
    'title'   => __('Directory Reviews', 'ldd-directory-lite'),
    'image'   => LDDLITE_URL . '/public/images/LDD-Directory-Review.png',
    'content' => __('Add a powerful user review system to your online directory with star ratings, comments and filterable/searchable review content.', 'ldd-directory-lite'),
    'rm_link' => 'https://plugins.lddwebdesign.com/extensions/directory-listing-reviews/',
    'buy_link'=> 'https://plugins.lddwebdesign.com/extensions/?edd_action=add_to_cart&download_id=1659',
    'plugin'  => 'ldd-directory-reviews/ldd-directory-lite-reviews.php'
);
$addons[]   = array(
    'title'   => __('Directory Social Login', 'ldd-directory-lite'),
    'image'   => LDDLITE_URL . '/public/images/LDD-Directory-Social-Login.png',
    'content' => __('The LDD Directory Social Login extension that allow with the ability to login with facebook, google+, linkedin logins.', 'ldd-directory-lite'),
    'rm_link' => 'https://plugins.lddwebdesign.com/extensions/directory-social-login/',
    'buy_link'=> 'https://plugins.lddwebdesign.com/extensions/?edd_action=add_to_cart&download_id=1680',
    'plugin'  => 'ldd-directory-reports/ldd-directory-lite-reporting.php'
);
$addons[]   = array(
    'title'   => __('Directory Social Share', 'ldd-directory-lite'),
    'image'   => LDDLITE_URL . '/public/images/LDD-Directory-Social-Share.png',
    'content' => __('Add the ability to share your directory listings on popular social networks like Facebook, Twitter, Google+, LinkedIn, Pinterest and via E-mail.', 'ldd-directory-lite'),
    'rm_link' => 'https://plugins.lddwebdesign.com/extensions/directory-social-share/',
    'buy_link'=> 'https://plugins.lddwebdesign.com/extensions/?edd_action=add_to_cart&download_id=1684',
    'plugin'  => 'ldd-directory-social-share/ldd-directory-lite-social-share.php'
);
$addons[]   = array(
    'title'   => __('Directory Exports & Reports', 'ldd-directory-lite'),
    'image'   => LDDLITE_URL . '/public/images/LDD-Directory-Export-logo-notext.png',
    'content' => __('The LDD Directory Reports and Exports extension allows a user to create reports and to export listings in multiple formats e.g XML, CSV, HTML, PDF.', 'ldd-directory-lite'),
    'rm_link' => 'https://plugins.lddwebdesign.com/extensions/directory-reports-exports/',
    'buy_link'=> 'https://plugins.lddwebdesign.com/extensions/?edd_action=add_to_cart&download_id=1465',
    'plugin'  => 'ldd-directory-reports/ldd-directory-lite-reporting.php'
);
$addons[]   = array(
    'title'   => __('Directory Imports', 'ldd-directory-lite'),
    'image'   => LDDLITE_URL . '/public/images/LDD-Directory-Import-logo-notext.png',
    'content' => __('The LDD Directory Lite Import extension allows a user to import listings directly to their directory via CSV files that can be edited by hand or in applications such as Microsoft Excel.', 'ldd-directory-lite'),
    'rm_link' => 'https://plugins.lddwebdesign.com/extensions/directory-import/',
    'buy_link'=> 'https://plugins.lddwebdesign.com/extensions/?edd_action=add_to_cart&download_id=1424',
    'plugin'  => 'ldd-directory-import/ldd-directory-import.php'
);

?>
<form id="ldd_directory_lite_addon_admin" enctype="multipart/form-data" method="post">
    <div class="wrap">

        <h2 class="heading"><?php _e('Directory Add-ons', 'ldd-directory-lite'); ?></h2>

        <div class="sub-heading">
            <p><?php _e('Add new add-ons support to your LDD Directory Lite. If you require support or would like to make a suggestion for improving this plugin, please refer to the following links.', 'ldd-directory-lite'); ?></p>
            <ul id="directory-links">
                <li><?php printf( __( '<a href="%1$s" title="Submit a bug or feature request on GitHub" class="bold-link"><i class="fa fa-exclamation-triangle fa-fw"></i>Submit an Issue</a>', 'ldd-directory-lite' ), esc_url('https://github.com/lddweb/ldd-directory-lite/issues') ); ?></li>
                <li class="right"><?php printf( __( '<i class="fa fa-wordpress fa-fw"></i>Visit us on <a href="%1$s" title="Come visit the plugin homepage on WordPress.org">%2$s</a>', 'ldd-directory-lite' ), esc_url('https://wordpress.org/support/plugin/ldd-directory-lite'), 'WordPress.org' ); ?></li>
                <li><?php printf( __( '<a href="%1$s" title="Visit the LDD Directory Lite Support Forums on WordPress.org" class="bold-link"><i class="fa fa-comments fa-fw"></i>Support Forums</a>', 'ldd-directory-lite' ), esc_url('https://wordpress.org/support/plugin/ldd-directory-lite') ); ?></li>
                <li class="right"><?php printf( __( '<i class="fa fa-github-alt fa-fw"></i>Visit us on <a href="%1$s" title="We do most of our development from GitHub, come join us!">%2$s</a>', 'ldd-directory-lite' ), esc_url('https://github.com/lddweb/ldd-directory-lite'), 'GitHub.com' ); ?></li>
                <li><?php printf( __( '<a href="%1$s" title="Rate this plugin on WordPress.org" class="bold-link"><i class="fa fa-star fa-fw"></i>Rate this Plugin</a>', 'ldd-directory-lite' ), esc_url('https://wordpress.org/support/plugin/ldd-directory-lite/reviews/#new-topic-0') ); ?></li>
            </ul>
        </div>

        <h2 class="nav-tab-wrapper">
            <span class="nav-tab nav-tab-active "><?php _e('Available Add-ons', 'ldd-directory-lite'); ?></span>
        </h2>
        <div id="poststuff">
            <div id="post-body">
                <div id="post-body-content">
                    <?php foreach($addons as $addon): ?>
                        <div class="ldd-extend ldd-box">
                            <img src="<?php echo $addon['image']; ?>"
                                 class="ldd-addons-image" alt="<?php echo $addon['title']; ?>">
                            <hr/>
                            <h2><?php echo $addon['title']; ?></h2>

                            <div class="ldd-extend-content">
                                <p><?php echo $addon['content']; ?></p>

                                <div class="ldd-extend-buttons">
                                    <a href="<?php echo $addon['rm_link']; ?>" target="_blank" class="button-secondary nf-doc-button"><?php _e('Learn More', 'ldd-directory-lite'); ?></a>

                                    <?php if( ! empty( $addon['plugin'] ) && file_exists( WP_PLUGIN_DIR.'/'.$addon['plugin'] ) ): ?>
                                        <?php if( is_plugin_active( $addon['plugin'] ) ): ?>
                                            <span class="button-secondary nf-button">
                                                <?php _e( 'Active', 'ldd-directory-lite' ); ?>
                                            </span>
                                        <?php elseif( is_plugin_inactive( $addon['plugin'] ) ): ?>
                                            <span class="button-secondary nf-button">
                                                <?php _e( 'Installed', 'ldd-directory-lite' ); ?>
                                            </span>
                                        <?php else: ?>
                                            <a href="<?php echo $addon['buy_link']; ?>" target="_blank" class="button-primary nf-button"><?php _e('Buy Now', 'ldd-directory-lite'); ?></a>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <a href="<?php echo $addon['buy_link']; ?>" target="_blank" class="button-primary nf-button"><?php _e('Buy Now', 'ldd-directory-lite'); ?></a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div><!-- /#post-body-content -->
            </div><!-- /#post-body -->
        </div>
    </div>
    <!-- </div>/.wrap-->
</form>