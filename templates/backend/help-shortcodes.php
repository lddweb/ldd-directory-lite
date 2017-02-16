<div id="poststuff" class="ldd-help-container">
    <div id="post-body">
        <div id="post-body-content">
            <ol>
                <li>
                    <h2 class="shortcode-title"><?php _e('Show LDD Directory Lite inside a page/post.', 'ldd-directory-lite'); ?></h2>
                    <div class="shortcode_example_div">
                        <h4><?php _e('Shortcode:', 'ldd-directory-lite'); ?></h4>
                            [directory cat_order_by="xxx" cat_order="asc" fl_order_by="xxx" fl_order="asc" list_order_by="xxx" list_order="asc"]
                        <div class="shortcode_example_options">
                            <h4><?php _e('Sorting Options:', 'ldd-directory-lite'); ?></h4>
                            <ol>
                                <li><strong>cat_order_by</strong>: id, slug, title, count</li>
                                <li><strong>cat_order</strong>: ASC , DESC</li>
                                <li><strong>fl_order_by</strong>: business_name, zip, area, category, random</li>
                                <li><strong>fl_order</strong>: ASC , DESC</li>
                                <li><strong>list_order_by</strong>: business_name, zip, area, category, random</li>
                                <li><strong>list_order</strong>: ASC , DESC</li>
                            </ol>
                        </div>
                    </div>
                </li>
                <li>
                    <h2 class="shortcode-title"><?php _e('Show listings from specify category(s)', 'ldd-directory-lite'); ?></h2>
                    <div class="shortcode_example_div">
                        <h4><?php _e('Shortcode:', 'ldd-directory-lite'); ?></h4>
                        [directory_category slug="my-category,my-other-category" view="compact" list_order_by="title" list_order="ASC" limit="8"]
                        <div class="shortcode_example_options">
                            <h4><?php _e('Options:', 'ldd-directory-lite'); ?></h4>
                            <ol>
                                <li><strong>view</strong>: grid , compact</li>
                                <li><strong>list_order_by</strong>: business_name, zip, area, category, random</li>
                                <li><strong>list_order</strong>: ASC , DESC</li>
                            </ol>
                        </div>
                    </div>
                </li>
            </ol>

        </div><!-- /#post-body-content -->
    </div><!-- /#post-body -->
</div>