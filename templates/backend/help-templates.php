<div id="poststuff" class="ldd-help-container">
    <div id="post-body">
        <div id="post-body-content">
            <h4><?php printf( __('Q) How can I avoid having my template customizations overwritten when the plugin is updated?', 'ldd-directory-lite') ); ?></h4>
            <h4><?php printf( __('Solution:', 'ldd-directory-lite') ); ?></h4>
            <p><?php printf( __('All the template files found in <strong>"%1$s"</strong> can be copied to a directory in your theme called <strong><em>"%2$s"</em></strong>.', 'ldd-directory-lite'), '/ldd-directory-lite/templates', 'lddlite_templates' ); ?>
            <br />
                <?php printf( __('You can edit these files in an upgrade-safe way using overrides. The copied template files will override the LDD Directory Lite\'s default template file.', 'ldd-directory-lite') ); ?>
            </p>
            <p><?php printf( __('For example, if you need to edit %1$s, you can copy it to %2$s. While you can copy the entire directory verbatim, it is recommended that you only copy the files you need.', 'ldd-directory-lite'), '/wp-content/plugins/ldd-directory-lite/templates/category.php', '/wp-content/themes/your-theme-directory/lddlite_templates/category.php' ); ?></p>
            <p class="ldd_plugin_note">
                <?php printf( __('<strong>Note:</strong> Do not edit these files within the core plugin itself as they are overwritten during the upgrade process and any customization will be lost.', 'ldd-directory-lite') ); ?>
            </p>
        </div><!-- /#post-body-content -->
    </div><!-- /#post-body -->
</div>