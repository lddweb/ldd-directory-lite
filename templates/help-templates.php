<div id="poststuff" class="ldd-help-container">
    <div id="post-body">
        <div id="post-body-content">
            <h4>Q) How can I avoid having my template customizations overwritten when the plugin is updated?</h4>
            <h4>Solution:</h4>
            <p>All the template files found in <strong>"/ldd-directory-lite/templates"</strong> can be copied to a directory in your theme called <strong><em>"lddlite_templates"</em></strong>.
            <br />
                You can edit these files in an upgrade-safe way using overrides. The copied template files will override the LDD Directory Lite's default template file.
            </p>
            <p>For example, if you need to edit /wp-content/plugins/ldd-directory-lite/templates/category.php, you can copy it to /wp-content/themes/your-theme-directory/lddlite_templates/category.php. While you can copy the entire directory verbatim, it is recommended that you only copy the files you need.</p>
            <p class="ldd_plugin_note">
                <strong>Note:</strong> Do not edit these files within the core plugin itself as they are overwritten during the upgrade process and any customization will be lost.
            </p>
        </div><!-- /#post-body-content -->
    </div><!-- /#post-body -->
</div>