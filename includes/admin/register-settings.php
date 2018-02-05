<?php
/**
 * Register plugin settings
 *
 * @package   ldd_directory_lite
 * @author    LDD Web Design <info@lddwebdesign.com>
 * @license   GPL-2.0+
 * @link      http://lddwebdesign.com
 * @copyright 2014 LDD Consulting, Inc
 */


/**
 * Return the settings array
 *  1.3.0
 * @since 0.8.7
 * @return array
 */
function ldl_get_registered_settings()
{

    $settings = array(

        'general' => apply_filters('lddlite_settings_general',
            array(
                'directory_front_page'             => array(
                    'id'      => 'directory_front_page',
                    'name'    => __('Front Page', 'ldd-directory-lite'),
                    'desc'    => sprintf(__('This is the page where the <code>%1$s</code> shortcode has been placed.', 'ldd-directory-lite'), '[directory]'),
                    'type'    => 'select',
                    'options' => ldl_get_pages()
                ),
                'directory_submit_page'            => array(
                    'id'      => 'directory_submit_page',
                    'name'    => __('Submit Listing Page', 'ldd-directory-lite'),
                    'desc'    => sprintf(__('This is the page where the <code>%1$s</code> shortcode has been placed.', 'ldd-directory-lite'), '[directory_submit]'),
                    'type'    => 'select',
                    'options' => ldl_get_pages()
                ),
                'directory_manage_page'            => array(
                    'id'      => 'directory_manage_page',
                    'name'    => __('Manage Listings Page', 'ldd-directory-lite'),
                    'desc'    => sprintf(__('This is the page where the <code>%1$s</code> shortcode has been placed.', 'ldd-directory-lite'), '[directory_manage]'),
                    'type'    => 'select',
                    'options' => ldl_get_pages()
                ),
                
                'directory_taxonomy_slug'          => array(
                    'id'   => 'directory_taxonomy_slug',
                    'name' => __('Taxonomy Slug', 'ldd-directory-lite'),
                    'type' => 'text',
                    'size' => 'medium',
                    'std'  => 'listings',
                ),
                'directory_post_type_slug'         => array(
                    'id'   => 'directory_post_type_slug',
                    'name' => __('Post Type Slug', 'ldd-directory-lite'),
                    'type' => 'text',
                    'size' => 'medium',
                    'std'  => 'listing',
                ),

                'sidebar_separator'             => array(
                    'id'   => 'directory_sidebar',
                    'name' => '<h4 class="title ldd-admin-title">' . __('Sidebar Shortcode', 'ldd-directory-lite') . '</h4>',
                    'type' => 'header'
                ),
                'appearance_sidebar_shortcode'  => array(
                    'id'   => 'appearance_sidebar_shortcode',
                    'name' => __('Shortcode', 'ldd-directory-lite'),
                    'type' => 'text',
                    'size' => 'medium',
                    'desc' => __('If provided, Default sidebar contact form will be replaced with provided shortcode.', 'ldd-directory-lite'),
                ),

                'other_separator'                  => array(
                    'id'   => 'directory_information',
                    'name' => '<h4 class="title ldd-admin-title">' . __('Other', 'ldd-directory-lite') . '</h4>',
                    'type' => 'header'
                ),

                 'home_page_listing' => array(
                    'id'      => 'home_page_listing',
                    'name'    => __('Home page default listing', 'ldd-directory-lite'),
                    'desc'    => __('Home page default listing', 'ldd-directory-lite'),
                    'type'    => 'radio_inline',
                    'options' => array(
                        "category" => "Categories",
                        "listing"  => "Listing"
                    ),
                    "std"     => "category",
                 ), 
                   
                 
                'general_allow_public_submissions' => array(
                    'id'      => 'general_allow_public_submissions',
                    'name'    => __('Public Submissions', 'ldd-directory-lite'),
                    'desc'    => __('Would you like to allow public submissions?', 'ldd-directory-lite'),
                    'type'    => 'radio_inline',
                    'options' => array(
                        "yes" => "Yes",
                        "no"  => "No"
                    ),
                    "std"     => "yes",
                ),
                'general_display_img_placeholder'  => array(
                    'id'      => 'general_display_img_placeholder',
                    'name'    => __('Show Image Placeholder', 'ldd-directory-lite'),
                    'desc'    => __('Shows a default image if no logo is uploaded for listing.', 'ldd-directory-lite'),
                    'type'    => 'radio_inline',
                    'options' => array(
                        "yes" => "Yes",
                        "no"  => "No"
                    ),
                    "std"     => "yes",
                ),

                    'directory_contributor_access'  => array(
                        'id'      => 'directory_contributor_access',
                        'name'    => __('Dashboard access to Directory Contributor', 'ldd-directory-lite'),
                        'desc'    => __('should directory contributor user be allowed to access dashboard', 'ldd-directory-lite'),
                        'type'    => 'radio_inline',
                        'options' => array(
                            "yes" => "Yes",
                            "no"  => "No"
                        ),
                        "std"     => "yes",
                ),
                'googlemap_api_key'                => array(
                    'id'   => 'googlemap_api_key',
                    'name' => __('Google API Key', 'ldd-directory-lite'),
                    'desc' => sprintf(__('You can get the API Key from here <a href="%1$s" target="_blank">here</a>.', 'ldd-directory-lite'), esc_url('https://developers.google.com/maps/documentation/javascript/get-api-key#get-an-api-key') ),
                    'type' => 'text',
                ),
                'allow_tracking'                   => array(
                    'id'   => 'allow_tracking',
                    'name' => __('Allow Tracking', 'ldd-directory-lite'),
                    'desc' => __('Allow anonymous usage tracking', 'ldd-directory-lite'),
                    'type' => 'checkbox'
                ),
                'google_maps'                      => array(
                    'id'   => 'google_maps',
                    'name' => __('Google Maps', 'ldd-directory-lite'),
                    'desc' => __('This toggles the display of Google Maps for listings that have an address set.', 'ldd-directory-lite'),
                    'type' => 'checkbox'
                ),
            )
        ),

        'featured' => apply_filters('lddlite_settings_featured',
            array(
                'appearance_display_featured'   => array(
                    'id'   => 'appearance_display_featured',
                    'name' => __('Enable Featured Listings', 'ldd-directory-lite'),
                    'desc' => sprintf(__('If checked, listings tagged with <code>%1$s</code> will be shown on your directory home page.', 'ldd-directory-lite'), 'featured'),
                    'type' => 'checkbox',
                    'std'  => 1,
                ),
                'featured_listings_tags'       => array(
                    'id'   => 'featured_listings_tags',
                    'name' => __('Feature tags', 'ldd-directory-lite'),
                    'desc' => __('You can choose tags (comma separated) to show on directory home page.', 'ldd-directory-lite'),
                    'type' => 'text',
                    'std'  => '',
                ),
                'featured_listings_limit'       => array(
                    'id'   => 'featured_listings_limit',
                    'name' => __('Listings Limit', 'ldd-directory-lite'),
                    'desc' => __('You can choose how many featured listings should be displayed.', 'ldd-directory-lite'),
                    'type' => 'number',
                    'std'  => '3',
                ),
                /* Featured Listing Sort Options */
                'featured_sort_separator'       => array(
                    'id'   => 'featured_sort_separator',
                    'name' => '<h4 class="title ldd-admin-title">' . __('Default Sort order', 'ldd-directory-lite') . '</h4>',
                    'type' => 'header'
                ),
                'directory_featured_sort'       => array(
                    'id'      => 'directory_featured_sort',
                    'name'    => __('Default Sort', 'ldd-directory-lite'),
                    'desc'    => sprintf(__('you can change sort for shortcodes too, <code>%1$s</code>.', 'ldd-directory-lite'), '[directory fl_order_by="xxx" fl_order="asc"]'),
                    'type'    => 'select',
                    'options' => ldl_get_featured_sort_options(),
                    'default' => 'business_name',
                ),
                'directory_featured_sort_order' => array(
                    'id'      => 'directory_featured_sort_order',
                    'name'    => __('Sort Order', 'ldd-directory-lite'),
                    'desc'    => sprintf(__('Please choose %1$s sort order.', 'ldd-directory-lite'), '[ASC/DESC]'),
                    'type'    => 'select',
                    'options' => array(
                        'asc'  => __('ASC', 'ldd-directory-lite'),
                        'desc' => __('DESC', 'ldd-directory-lite'),
                    ),
                    'default' => 'asc',
                ),
            )
        ),

        'sort_options' => apply_filters('lddlite_settings_sort_options',
            array(
                /* Category Sort Options */
                'category_sort_separator'       => array(
                    'id'   => 'category_sort_separator',
                    'name' => '<h4 class="title ldd-admin-title">' . __('Category Sort Order', 'ldd-directory-lite') . '</h4>',
                    'type' => 'header'
                ),
                'directory_category_sort'       => array(
                    'id'      => 'directory_category_sort',
                    'name'    => __('Default Sort', 'ldd-directory-lite'),
                    'desc'    => sprintf(__('you can change sort for shortcodes too, <code>%1$s</code>.', 'ldd-directory-lite'), '[directory cat_order_by="xxx" cat_order="asc"]'),
                    'type'    => 'select',
                    'options' => ldl_get_cat_sort_options(),
                    'default' => 'title',
                ),
                'directory_category_sort_order' => array(
                    'id'      => 'directory_category_sort_order',
                    'name'    => __('Sort Order', 'ldd-directory-lite'),
                    'desc'    => sprintf(__('Please choose %1$s sort order.', 'ldd-directory-lite'), '[ASC/DESC]'),
                    'type'    => 'select',
                    'options' => array(
                        'asc'  => __('ASC', 'ldd-directory-lite'),
                        'desc' => __('DESC', 'ldd-directory-lite'),
                    ),
                    'default' => 'asc',
                ),
                 'listings_category_number'       => array(
                    'id'   => 'listings_category_number',
                    'name' => __('Category pages show at most:', 'ldd-directory-lite'),
                    'desc' => __('Leave empty to show all categories.', 'ldd-directory-lite'),
                    'type' => 'text',
                    'std'  => '10',
                ),
                /* Listings Sort Options */
                'listings_sort_separator'       => array(
                    'id'   => 'listings_sort_separator',
                    'name' => '<h4 class="title ldd-admin-title">' . __('Listings Sort Order', 'ldd-directory-lite') . '</h4>',
                    'type' => 'header'
                ),
                'directory_listings_sort'       => array(
                    'id'      => 'directory_listings_sort',
                    'name'    => __('Default Sort', 'ldd-directory-lite'),
                    'desc'    => sprintf(__('you can change sort for shortcodes too, <code>%1$s</code>.', 'ldd-directory-lite'), '[directory list_order_by="xxx" list_order="asc"]'),
                    'type'    => 'select',
                    'options' => ldl_get_sort_options(),
                    'default' => 'business_name',
                ),
                'directory_listings_sort_order' => array(
                    'id'      => 'directory_listings_sort_order',
                    'name'    => __('Sort Order', 'ldd-directory-lite'),
                    'desc'    => sprintf(__('Please choose %1$s sort order.', 'ldd-directory-lite'), '[ASC/DESC]'),
                    'type'    => 'select',
                    'options' => array(
                        'asc'  => __('ASC', 'ldd-directory-lite'),
                        'desc' => __('DESC', 'ldd-directory-lite'),
                    ),
                    'default' => 'asc',
                ),
                'listings_display_number'       => array(
                    'id'   => 'listings_display_number',
                    'name' => __('Listings pages show at most', 'ldd-directory-lite'),
                    'desc' => __('Leave empty to show all listings.', 'ldd-directory-lite'),
                    'type' => 'text',
                    'std'  => '10',
                ),

                 'search_display_separator'       => array(
                    'id'   => 'search_display_separator',
                    'name' => '<h4 class="title ldd-admin-title">' . __('Search Page', 'ldd-directory-lite') . '</h4>',
                    'type' => 'header'
                ),
                 'search_listings_sort'       => array(
                    'id'      => 'search_listings_sort',
                    'name'    => __('Default Sort', 'ldd-directory-lite'),
                    'desc'    => sprintf(__('you can change sort for shortcodes too, <code>%1$s</code>.', 'ldd-directory-lite'), '[directory list_order_by="xxx" list_order="asc"]'),
                    'type'    => 'select',
                    'options' => ldl_search_sort_options(),
                    'default' => 'business_name',
                ),
                'search_listings_sort_order' => array(
                    'id'      => 'search_listings_sort_order',
                    'name'    => __('Sort Order', 'ldd-directory-lite'),
                    'desc'    => sprintf(__('Please choose %1$s sort order.', 'ldd-directory-lite'), '[ASC/DESC]'),
                    'type'    => 'select',
                    'options' => array(
                        'asc'  => __('ASC', 'ldd-directory-lite'),
                        'desc' => __('DESC', 'ldd-directory-lite'),
                    ),
                    'default' => 'asc',
                ),
                 'listings_search_number'       => array(
                    'id'   => 'listings_search_number',
                    'name' => __('Search result pages show at most:', 'ldd-directory-lite'),
                    'desc' => __('Leave empty to show all search results.', 'ldd-directory-lite'),
                    'type' => 'text',
                    'std'  => '10',
                ),
                /* Prevent items entered in subcategory from being displayed in parent category listing view */
                'listings_display_separator'       => array(
                    'id'   => 'listings_display_separator',
                    'name' => '<h4 class="title ldd-admin-title">' . __('Subcategory Listing Behavior', 'ldd-directory-lite') . '</h4>',
                    'type' => 'header'
                ),
                
                'subcategory_listings'   => array(
	                'id'   => 'subcategory_listings',
	                'name' => __('Subcategory Listings', 'ldd-directory-lite'),
	                'desc' => __('Do not show child category listings in their parent category pages & counts.', 'ldd-directory-lite'),
	                'type' => 'checkbox',
	                'std'  => 1,
                ),
            )
        ),

        'emails' => apply_filters('lddlite_settings_emails',
            array(
                'email_from_name'            => array(
                    'id'   => 'email_from_name',
                    'name' => __('From Name', 'ldd-directory-lite'),
                    'desc' => __('This forms the first part of outgoing messages, ', 'ldd-directory-lite') . sprintf(' From: <strong>%s</strong> &lt;%s&gt;', ldl()->get_option('email_from_name'), ldl()->get_option('email_from_address')),
                    'type' => 'text',
                    'std'  => get_bloginfo('name')
                ),
                'email_from_address'         => array(
                    'id'   => 'email_from_address',
                    'name' => __('From Email', 'ldd-directory-lite'),
                    'desc' => __('This forms the second part of outgoing messages, ', 'ldd-directory-lite') . sprintf(' From: %s &lt;<strong>%s</strong>&gt;', ldl()->get_option('email_from_name'), ldl()->get_option('email_from_address')),
                    'type' => 'text',
                    'std'  => get_bloginfo('admin_email')
                ),
                
                'message_separator'          => array(
                    'id'   => 'message_separator',
                    'name' => '<strong>' . __('Email Templates', 'ldd-directory-lite') . '</strong>',
                    'type' => 'header'
                ),
                'email_toadmin_subject'      => array(
                    'id'   => 'email_toadmin_subject',
                    'name' => __('Admin Notification', 'ldd-directory-lite'),
                    'desc' => __('The subject line for email notifications sent to the site administrator.', 'ldd-directory-lite'),
                    'type' => 'text',
                    'std'  => __('A new listing has been submitted for review!', 'ldd-directory-lite')
                ),
                'email_toadmin_body'         => array(
                    'id'   => 'email_toadmin_body',
                    'name' => __('Notification Message', 'ldd-directory-lite'),
                    'type' => 'rich_editor',
                    'std'  => __('A new listing is pending review!', 'ldd-directory-lite') . "\n\n" . __('This submission is awaiting approval. Please visit the link to view and approve the new listing:', 'ldd-directory-lite') . "\n\n{approve_link}\n\n" . __('Listing Name:', 'ldd-directory-lite') . " {title}\n" . __('Listing Description:', 'ldd-directory-lite') . " {description}\n\n*****************************************\n" . __('This is an automated message from', 'ldd-directory-lite') . " {site_title}\n" . __('Please do not respond directly to this email', 'ldd-directory-lite') . "\n\n",
                ),
                'email_onsubmit_subject'     => array(
                    'id'   => 'email_onsubmit_subject',
                    'name' => __('Admin Notification', 'ldd-directory-lite'),
                    'desc' => __('Sent to the author after they submit a new listing. Use this to remind them of your terms, inform them of average wait times or other important information.', 'ldd-directory-lite'),
                    'type' => 'text',
                    'std'  => sprintf(__('Your listing on %s is pending review!', 'ldd-directory-lite'), get_bloginfo('name')),
                ),
                'email_onsubmit_body'        => array(
                    'id'   => 'email_onsubmit_body',
                    'name' => __('Notification Message', 'ldd-directory-lite'),
                    'type' => 'rich_editor',
                    'std'  => __('Thank you for submitting a listing to', 'ldd-directory-lite') . " {site_title}!\n\n" . __('Your listing is pending approval.', 'ldd-directory-lite') . "\n\n" . __('Please review the following information for accuracy, as this is what will appear on our web site. If you see any errors, please contact us immediately at', 'ldd-directory-lite') . " {directory_email}.\n\n" . __('Listing Name:', 'ldd-directory-lite') . " {title}\n" . __('Listing Description:', 'ldd-directory-lite') . " {description}\n\n*****************************************\n" . __('This is an automated message from', 'ldd-directory-lite') . " {site_title}\n" . __('Please do not respond directly to this email', 'ldd-directory-lite') . "\n\n",
                ),
                'email_onapprove_subject'    => array(
                    'id'   => 'email_onapprove_subject',
                    'name' => __('Admin Notification', 'ldd-directory-lite'),
                    'desc' => __('Sent to the author when their listing has been approved and is available publicly.', 'ldd-directory-lite'),
                    'type' => 'text',
                    'std'  => sprintf(__('Your listing on %s has been approved!', 'ldd-directory-lite'), get_bloginfo('name')),
                ),
                'email_onapprove_body'       => array(
                    'id'   => 'email_onapprove_body',
                    'name' => __('Notification Message', 'ldd-directory-lite'),
                    'type' => 'rich_editor',
                    'std'  => __('Thank you for submitting a listing to', 'ldd-directory-lite') . " {site_title}!\n\n" . __('Your listing has been approved! You can now view it online:', 'ldd-directory-lite') . "\n\n{link}\n\n*****************************************\n" . __('This is an automated message from', 'ldd-directory-lite') . " {site_title}\n" . __('Please do not respond directly to this email', 'ldd-directory-lite') . "\n\n",
                ),
            )
        ),

        'submit' => apply_filters('lddlite_settings_submit',
            array(
                'submit_auto_approve_submission' => array(
                    'id'      => 'submit_auto_approve_submission',
                    'name'    => __('Auto Publish listings?', 'ldd-directory-lite'),
                    'desc'    => __('Would you like to publish listings without approval?', 'ldd-directory-lite'),
                    'type'    => 'radio_inline',
                    'options' => array(
                        "no" => "No",
                        "yes"  => "Yes"
                    ),
                    "std"     => "no",
                ),
                'submit_use_tos'                 => array(
                    'id'   => 'submit_use_tos',
                    'name' => __('Require TOS', 'ldd-directory-lite'),
                    'desc' => __('Check this to require users agree to your terms of service (defined below) before submitting a listing.', 'ldd-directory-lite'),
                    'type' => 'checkbox',
                ),
                'submit_tos'                     => array(
                    'id'   => 'submit_tos',
                    'name' => __('Terms of Service', 'ldd-directory-lite'),
                    'type' => 'rich_editor',
                ),
                'submit_intro'                   => array(
                    'id'   => 'submit_intro',
                    'name' => __('Introduction', 'ldd-directory-lite'),
                    'desc' => __('This will be displayed at the top of the submit listing form.', 'ldd-directory-lite'),
                    'type' => 'rich_editor',
                    'std'  => '<p>' . __('Please tell us a little bit about the organization you would like to see listed in our directory. Try to include as much information as you can, and be as descriptive as possible where asked.', 'ldd-directory-lite') . '</p>',
                ),
                'submit_success'                 => array(
                    'id'   => 'submit_success',
                    'name' => __('Success Message', 'ldd-directory-lite'),
                    'desc' => __('Displayed following a successful listing submission.', 'ldd-directory-lite'),
                    'type' => 'rich_editor',
                    'std'  => '<h3>' . __('Congratulations!', 'ldd-directory-lite') . '</h3><p>' . __('Your listing has been successfully submitted for review. Please allow us sufficient time to review the listing and approve it for public display in our directory.', 'ldd-directory-lite') . '</p>',
                ),
            )
        ),

        'appearance' => apply_filters('lddlite_settings_appearance',
            array(
                'disable_bootstrap'             => array(
                    'id'   => 'disable_bootstrap',
                    'name' => __('Disable Bootstrap', 'ldd-directory-lite'),
                    'desc' => __('You can disable the Bootstrap CSS library if your theme already loads a copy, or if you want to use entirely custom CSS.', 'ldd-directory-lite'),
                    'type' => 'checkbox',
                ),

                 'directory_view_type'              => array(
                    'id'      => 'directory_view_type',
                    'name'    => __('Listing View', 'ldd-directory-lite'),
                    'desc'    => __('Set the default listing view.', 'ldd-directory-lite'),
                    'type'    => 'select',
                    'options' => ldl_get_listing_views()
                ),

                
               'appearance_header_background'     => array(
                    'id'   => 'appearance_header_background',
                    'name' => __('Header Background Color', 'ldd-directory-lite'),
                    'type' => 'color',
                    'std'  => '#222',
                ),
                'appearance_header_text'      => array(
                    'id'   => 'appearance_header_text',
                    'name' => __('Header Text Color', 'ldd-directory-lite'),
                    'type' => 'color',
                    'std'  => '#ffffff',
                ),
                'appearance_primary_normal'     => array(
                    'id'   => 'appearance_primary_normal',
                    'name' => __('Button Color', 'ldd-directory-lite'),
                    'type' => 'color',
                    'std'  => '#3bafda',
                ),
                'appearance_primary_hover'      => array(
                    'id'   => 'appearance_primary_hover',
                    'name' => __('Button Hover', 'ldd-directory-lite'),
                    'type' => 'color',
                    'std'  => '#3071a9',
                ),
                'appearance_primary_foreground' => array(
                    'id'   => 'appearance_primary_foreground',
                    'name' => __('Button Text Color', 'ldd-directory-lite'),
                    'type' => 'color',
                    'std'  => '#ffffff',
                ),

                'link_text_color'      => array(
                    'id'   => 'link_text_color',
                    'name' => __('Text Link Color', 'ldd-directory-lite'),
                    'type' => 'color',
                    'std'  => '#337ab7',
                ),
                'link_text_hover' => array(
                    'id'   => 'link_text_hover',
                    'name' => __('Text Link Hover', 'ldd-directory-lite'),
                    'type' => 'color',
                    'std'  => '#337ab7',
                ),
               
            )
        ),
        'template' => apply_filters('lddlite_settings_template',
       
            array(

                  'single_page_listing' => array(
                    'id'      => 'single_page_listing',
                    'name'    => __('Single Listing Template', 'ldd-directory-lite'),
                    'desc'    => __('Single Listing Template', 'ldd-directory-lite'),
                    'type'    => 'select',
                    'options' => ldl_single_temp_options()
         
        ),
        'template_display_separator'       => array(
                    'id'   => 'template_display_separator',
                    'name' => '<h4 class="title ldd-admin-title">' . __('ADVANCED TEMPLATE MODIFICATIONS', 'ldd-directory-lite') . '</h4>',
                    'type' => 'header'
                ),
                'template_override' => array(
                    'id'   => 'template_override',
                    //'name' => '<h4 class="title ldd-admin-title">' . __('Template Overriding', 'ldd-directory-lite') . '</h4>',
                    'type' => 'desc',
                    'desc' => __('If provided, Default sidebar contact form will be replaced with provided shortcode.', 'ldd-directory-lite'),
                    'callback'=> 'myprefix_setting_callback_function'
                ),
                
                
                
                
            )
        ),

    );

    return $settings;
}

/**
 * Register the default settings and sections
 *
 * @since 0.8
 */
function ldl_register_settings()
{

    foreach (ldl_get_registered_settings() as $tab => $settings) {

        add_settings_section(
            'lddlite_settings_' . $tab,
            __return_null(),
            '__return_false',
            'lddlite_settings_' . $tab
        );

        foreach ($settings as $option) {

            $name = isset($option['name']) ? $option['name'] : '';

            add_settings_field(
                'lddlite_settings[' . $option['id'] . ']',
                $name,
                function_exists('ldl_' . $option['type'] . '_callback') ? 'ldl_' . $option['type'] . '_callback' : 'ldl_missing_callback',
                'lddlite_settings_' . $tab,
                'lddlite_settings_' . $tab,
                array(
                    'id'      => isset($option['id']) ? $option['id'] : null,
                    'desc'    => !empty($option['desc']) ? $option['desc'] : '',
                    'name'    => isset($option['name']) ? $option['name'] : null,
                    'section' => $tab,
                    'size'    => isset($option['size']) ? $option['size'] : null,
                    'options' => isset($option['options']) ? $option['options'] : '',
                    'std'     => isset($option['std']) ? $option['std'] : ''
                )
            );
        }

    }

    // Creates our settings in the options table
    register_setting('lddlite_settings', 'lddlite_settings', 'ldl_settings_sanitize');

}

add_action('admin_init', 'ldl_register_settings');


/**
 * Settings Sanitization
 *
 * @since 0.8
 *
 * @param array $input The value inputted in the field
 *
 * @return string $input
 */
function ldl_settings_sanitize($input = array())
{

    if (empty($_POST['_wp_http_referer'])) {
        return $input;
    }

    parse_str($_POST['_wp_http_referer'], $referrer);

    $settings = ldl_get_registered_settings();
    $tab = isset($referrer['tab']) ? $referrer['tab'] : 'general';

    $input = $input ? $input : array();
    $input = apply_filters('lddlite_settings_' . $tab . '_sanitize', $input);

    // Loop through each setting being saved and pass it through a sanitization filter
    foreach ($input as $key => $value) {

        // Get the setting type (checkbox, select, etc)
        $type = isset($settings[ $tab ][ $key ]['type']) ? $settings[ $tab ][ $key ]['type'] : FALSE;

        if ($type) {
            // Field type specific filter
            $input[ $key ] = apply_filters('lddlite_settings_sanitize_' . $type, $value, $key);
        }

        // General filter
        $input[ $key ] = apply_filters('lddlite_settings_sanitize', $value, $key);
    }

    $ldl_settings = get_option('lddlite_settings');

    if (!empty($settings[ $tab ])) {
        foreach ($settings[ $tab ] as $key => $value) {

            if (isset($ldl_settings[ $key ]) and empty($input[ $key ])) {
                unset($ldl_settings[ $key ]);
            }

        }
    }

    // Merge our new settings with the existing
    $output = array_merge($ldl_settings, $input);

    add_settings_error('lddlite-notices', '', __('Settings updated.', 'ldd-directory-lite'), 'updated');
    flush_rewrite_rules();

    return $output;
}

function lddlite_settings_after_update( $lddlite_setting_old_values, $lddlite_setting_new_value ){

	if ( $lddlite_setting_old_values['directory_taxonomy_slug'] != $lddlite_setting_new_value['directory_taxonomy_slug']
		|| $lddlite_setting_old_values['directory_post_type_slug'] != $lddlite_setting_old_values['directory_post_type_slug'] ){

		update_site_option( 'lddlite_settings_have_changed', 'yes' );

		if ( wp_get_referer() ){
			wp_safe_redirect( wp_get_referer() );
		}else{
			wp_safe_redirect( admin_url( 'edit.php?post_type=directory_listings&page=lddlite-settings&tab=general' ) );
		}
		exit;
	}
}
add_action( 'update_option_lddlite_settings', 'lddlite_settings_after_update', 10, 2 );

function lddlite_settings_flush_rewrite(){
    if ( get_site_option('lddlite_settings_have_changed') == true ) {
        flush_rewrite_rules();
	    update_site_option('lddlite_settings_have_changed', false);
    }
}
add_action('admin_init', 'lddlite_settings_flush_rewrite');

function ldl_sanitize_text_field($input)
{
    return trim($input);
}

add_filter('ldl_settings_sanitize_text', 'ldl_sanitize_text_field');


function ldl_get_settings_tabs()
{

    $settings = ldl_get_registered_settings();

    $tabs = array();
    $tabs['general']        = __('General', 'ldd-directory-lite');
    $tabs['featured']       = __('Featured Listings', 'ldd-directory-lite');
    $tabs['sort_options']   = __('Listings Sort Options', 'ldd-directory-lite');
    $tabs['emails']         = __('Email Settings', 'ldd-directory-lite');
    $tabs['submit']         = __('Submit', 'ldd-directory-lite');
    $tabs['appearance']     = __('Appearance', 'ldd-directory-lite');
    $tabs['template']     = __('Template Overriding', 'ldd-directory-lite');

    return apply_filters('ldl_settings_tabs', $tabs);
}

function ldl_get_cat_sort_options()
{

    $pages_options['id']    = __('ID', 'ldd-directory-lite');
    $pages_options['slug']  = __('Slug', 'ldd-directory-lite');
    $pages_options['title'] = __('Title', 'ldd-directory-lite');
    $pages_options['count'] = __('Count', 'ldd-directory-lite');

    return $pages_options;
}

function ldl_get_featured_sort_options()
{

    $pages_options['business_name'] = __('Business name', 'ldd-directory-lite');
    $pages_options['zip']           = __('Zip', 'ldd-directory-lite');
    $pages_options['area']          = __('Area', 'ldd-directory-lite');
    $pages_options['category']      = __('Category', 'ldd-directory-lite');
    $pages_options['random']        = __('Random', 'ldd-directory-lite');

    return $pages_options;
}

function ldl_get_sort_options()
{

    $pages_options['business_name'] = __('Business name', 'ldd-directory-lite');
    $pages_options['city']      = __('City', 'ldd-directory-lite');
    $pages_options['zip']           = __('Zip/Postal Code', 'ldd-directory-lite');
     $pages_options['country']          = __('Country', 'ldd-directory-lite');
    $pages_options['state']          = __('State/Province', 'ldd-directory-lite');
    $pages_options['id']      = __('ID', 'ldd-directory-lite');
    $pages_options['random']        = __('Random', 'ldd-directory-lite');

    return $pages_options;
}

function ldl_search_sort_options()
{

    $pages_options['business_name'] = __('Business name', 'ldd-directory-lite');
    $pages_options['id']      = __('ID', 'ldd-directory-lite');
    $pages_options['date']           = __('Date', 'ldd-directory-lite');
    

    return $pages_options;
}

function ldl_single_temp_options()
{

    $pages_options['theme_default'] = __('Theme Default', 'ldd-directory-lite');
    $pages_options['plugin_default']      = __('Plugin Default', 'ldd-directory-lite');
   
    

    return $pages_options;
}

function ldl_get_pages($force = FALSE)
{

    $pages_options = array(0 => ''); // Blank option

    if ((!isset($_GET['page']) || 'lddlite-settings' != $_GET['page']) && !$force) {
        return $pages_options;
    }

    $pages = get_pages();
    if ($pages) {
        foreach ($pages as $page) {
            $pages_options[ $page->ID ] = $page->post_title;
        }
    }

    return $pages_options;
}

function ldl_get_listing_views()
{

    $pages_options["compact"]   = __('Compact View', 'ldd-directory-lite');
    $pages_options["grid"]      = __('Grid View', 'ldd-directory-lite');

    return $pages_options;
}

function ldl_header_callback($args)
{
    echo '<hr/>';
}


function ldl_checkbox_callback($args)
{

    $checked = checked(1, ldl()->get_option($args['id']), FALSE);
    $html = '<input type="checkbox" id="lddlite_settings[' . $args['id'] . ']" name="lddlite_settings[' . $args['id'] . ']" value="1" ' . $checked . '>';
    $html .= '<label for="lddlite_settings[' . $args['id'] . ']"> ' . $args['desc'] . '</label>';

    echo $html;
}


function ldl_multicheck_callback($args)
{

    if (!empty($args['options'])) {
        foreach ($args['options'] as $key => $option):
            if (isset($ldl_options[ $args['id'] ][ $key ])) {
                $enabled = $option;
            } else {
                $enabled = null;
            }
            echo '<input name="lddlite_settings[' . $args['id'] . '][' . $key . ']" id="lddlite_settings[' . $args['id'] . '][' . $key . ']" type="checkbox" value="' . $option . '" ' . checked($option, $enabled, FALSE) . '>&nbsp;';
            echo '<label for="lddlite_settings[' . $args['id'] . '][' . $key . ']">' . $option . '</label><br/>';
        endforeach;
        echo '<p class="description">' . $args['desc'] . '</p>';
    }
}


function ldl_radio_callback($args)
{

    foreach ($args['options'] as $key => $option) :
        $checked = FALSE;

        if (ldl()->get_option($args['id']) == $key)
            $checked = TRUE;
        elseif (isset($args['std']) && $args['std'] == $key && !isset($ldl_options[ $args['id'] ]))
            $checked = TRUE;

        echo '<input name="lddlite_settings[' . $args['id'] . ']"" id="lddlite_settings[' . $args['id'] . '][' . $key . ']" type="radio" value="' . $key . '" ' . checked(TRUE, $checked, FALSE) . '>&nbsp;';
        echo '<label for="lddlite_settings[' . $args['id'] . '][' . $key . ']">' . $option . '</label><br/>';
    endforeach;

    echo '<p class="description">' . $args['desc'] . '</p>';
}

function ldl_radio_inline_callback($args)
{

    foreach ($args['options'] as $key => $option) :
        $checked = FALSE;

        if (ldl()->get_option($args['id']) == $key)
            $checked = TRUE;
        elseif (isset($args['std']) && $args['std'] == $key && !isset($ldl_options[ $args['id'] ]))
            $checked = TRUE;

        echo "<div style='display:inline-block;'>";
        echo '<input name="lddlite_settings[' . $args['id'] . ']"" id="lddlite_settings[' . $args['id'] . '][' . $key . ']" type="radio" value="' . $key . '" ' . checked(TRUE, $checked, FALSE) . '>&nbsp;';
        echo '<label for="lddlite_settings[' . $args['id'] . '][' . $key . ']">' . $option . '</label>&nbsp&nbsp';
        echo "</div>";

    endforeach;

    echo '<p class="description">' . $args['desc'] . '</p>';
}

function ldl_text_callback($args)
{

    if (ldl()->has_option($args['id']))
        $value = ldl()->get_option($args['id']);
    else
        $value = isset($args['std']) ? $args['std'] : '';

    $size = (isset($args['size']) && !is_null($args['size'])) ? $args['size'] : 'regular';
    $html = '<input type="text" class="' . $size . '-text" id="lddlite_settings[' . $args['id'] . ']" name="lddlite_settings[' . $args['id'] . ']" value="' . esc_attr(stripslashes($value)) . '">';
    $html .= '<p class="description"> ' . $args['desc'] . '</p>';

    echo $html;
}


function ldl_number_callback($args)
{

    if (ldl()->has_option($args['id']))
        $value = ldl()->get_option($args['id']);
    else
        $value = isset($args['std']) ? $args['std'] : '';

    $max = isset($args['max']) ? $args['max'] : 999999;
    $min = isset($args['min']) ? $args['min'] : 0;
    $step = isset($args['step']) ? $args['step'] : 1;

    $size = (isset($args['size']) && !is_null($args['size'])) ? $args['size'] : 'regular';
    $html = '<input type="number" step="' . esc_attr($step) . '" max="' . esc_attr($max) . '" min="' . esc_attr($min) . '" class="' . $size . '-text" id="lddlite_settings[' . $args['id'] . ']" name="lddlite_settings[' . $args['id'] . ']" value="' . esc_attr(stripslashes($value)) . '">';
    $html .= '<p class="description"> ' . $args['desc'] . '</p>';

    echo $html;
}


function ldl_textarea_callback($args)
{

    if (ldl()->has_option($args['id']))
        $value = ldl()->get_option($args['id']);
    else
        $value = isset($args['std']) ? $args['std'] : '';

    $size = (isset($args['size']) && !is_null($args['size'])) ? $args['size'] : 'regular';
    $html = '<textarea class="large-text" cols="50" rows="5" id="lddlite_settings[' . $args['id'] . ']" name="lddlite_settings[' . $args['id'] . ']">' . esc_textarea(stripslashes($value)) . '</textarea>';
    $html .= '<p class="description"> ' . $args['desc'] . '</p>';

    echo $html;
}


function ldl_password_callback($args)
{

    if (ldl()->has_option($args['id']))
        $value = ldl()->get_option($args['id']);
    else
        $value = isset($args['std']) ? $args['std'] : '';

    $size = (isset($args['size']) && !is_null($args['size'])) ? $args['size'] : 'regular';
    $html = '<input type="password" class="' . $size . '-text" id="lddlite_settings[' . $args['id'] . ']" name="lddlite_settings[' . $args['id'] . ']" value="' . esc_attr($value) . '">';
    $html .= '<p class="description"> ' . $args['desc'] . '</p>';

    echo $html;
}


function ldl_missing_callback($args)
{
    printf(__('The callback function used for the <strong>%s</strong> setting is missing.', 'ldd-directory-lite'), $args['id']);
}

function ldl_desc_callback($args)
{
    ?>
    
            <p><?php printf( __('The LDD Directory Lite template files which are responsible for the markup and structure for the frontend of your directory are located here:</p>
     ', 'lddlite_templates' )); ?> </p>
     <?php printf( __(' <code>/ldd-directory-lite/templates/</code>', 'lddlite_templates' )); ?>
           <p>
                <?php printf( __('In order to modify the look and feel of your directory, you will need to copy the specific file(s) to a folder in your theme called <code>lddlite_templates</code>', 'ldd-directory-lite') ); ?>
            </p>
            <p class=''><?php printf( __('Do not edit these files within the core plugin itself as they will be overwritten during the upgrade process and any customization will be lost.', 'ldd-directory-lite') );?></p>
            <hr />
            <p class="">
                <?php printf( __(' <em>NOTE: If you wish to modify a file in one of the subfolders of the /ldd-directory-lite/templates/, then you will need to maintain the folder structure in your theme as well.</em>', 'ldd-directory-lite') ); ?>
            </p>
            <p class="">
                <?php printf( __(' For example: <code>/yourtheme/lddlite_templates/global/header.php</code', 'ldd-directory-lite') ); ?>
            </p>
    <?php
}


function ldl_select_callback($args)
{

    if (ldl()->has_option($args['id']))
        $value = ldl()->get_option($args['id']);
    else
        $value = isset($args['std']) ? $args['std'] : '';

    $html = '<select id="lddlite_settings[' . $args['id'] . ']" name="lddlite_settings[' . $args['id'] . ']">';

    foreach ($args['options'] as $option => $name) :
        $selected = selected($option, $value, FALSE);
        $html .= '<option value="' . $option . '" ' . $selected . '>' . $name . '</option>';
    endforeach;

    $html .= '</select>';
    $html .= '<p class="description"> ' . $args['desc'] . '</p>';

    echo $html;
}


function ldl_rich_editor_callback($args)
{
    global $wp_version;

    if (ldl()->has_option($args['id']))
        $value = ldl()->get_option($args['id']);
    else
        $value = isset($args['std']) ? $args['std'] : '';

    ob_start();
    wp_editor(stripslashes($value), 'ldl_settings_' . $args['id'], array('textarea_name' => 'lddlite_settings[' . $args['id'] . ']', 'textarea_rows' => 8));
    $html = ob_get_clean();

    $html .= '<p class="description"> ' . $args['desc'] . '</p>';

    echo $html;
}


function ldl_upload_callback($args)
{

    if (ldl()->has_option($args['id']) and isset($ldl_options))
        $value = $ldl_options[ $args['id'] ];
    else
        $value = isset($args['std']) ? $args['std'] : '';

    $size = (isset($args['size']) && !is_null($args['size'])) ? $args['size'] : 'regular';
    $html = '<input type="text" class="' . $size . '-text ldl_upload_field" id="lddlite_settings[' . $args['id'] . ']" name="lddlite_settings[' . $args['id'] . ']" value="' . esc_attr(stripslashes($value)) . '">';
    $html .= '<span>&nbsp;<input type="button" class="ldl_settings_upload_button button-secondary" value="' . __('Upload File', 'ldd-directory-lite') . '"/></span>';
    $html .= '<p class="description"> ' . $args['desc'] . '</p>';

    echo $html;
}


function ldl_color_callback($args)
{

    if (ldl()->has_option($args['id']))
        $value = ldl()->get_option($args['id']);
    else
        $value = isset($args['std']) ? $args['std'] : '';

    $default = isset($args['std']) ? $args['std'] : '';

    $size = (isset($args['size']) && !is_null($args['size'])) ? $args['size'] : 'regular';
    $html = '<input type="text" class="lddlite-color-picker" id="lddlite_settings[' . $args['id'] . ']" name="lddlite_settings[' . $args['id'] . ']" value="' . esc_attr($value) . '" data-default-color="' . esc_attr($default) . '">';
    $html .= '<p class="description"> ' . $args['desc'] . '</p>';

    echo $html;
}


function ldl_hook_callback($args)
{
    do_action('ldl_' . $args['id']);
}