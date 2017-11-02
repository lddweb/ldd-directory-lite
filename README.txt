=== LDD Directory Lite ===
Contributors: lddwebdesign
Tags: directory, listings, listing directory, business, business directory
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=7EUP56RH7E8RJ
Requires at least: 3.9.1 or higher
Tested up to: 4.8.2
Stable tag: 1.4
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The LDD Directory Lite plugin is the best way to add a powerful, fully functional and free custom directory to your WordPress powered web site.

== Description ==

The LDD Directory Lite plugin is the best way to add a powerful, fully functional and free custom directory to your WordPress powered web site.

Within minutes you can install and configure LDD Directory Lite and start accepting submissions, or populating the content with your own listings. This is the best and easiest way to start any kind of directory you can think of, be it of local businesses (restaurants, coffee shops, craft stores, etc), or any other type of organization you would like to build around.

The Directory is extremely user friendly, ready to go pretty much out of the box. One of our primary focuses is ensuring that it integrates with almost any theme, anywhere, anytime, and we have utilized the power of Bootstrap to ensure that the Directory is 100% mobile ready and responsive. It won't break all that hard work you've put into making sure your site works on any medium.

= Here are just a few of the many features =

* Easy to install, easy to configure
* Add the [directory] and [directory_submit] shortcode to any page (automatically installed for you, but feel free to change them)
* Default list sorting options for Categories,Featured and Listings in dashboard.
* Sort using shortcodes, i.e. [directory cat_order_by="xxx" cat_order="asc" fl_order_by="xxx" fl_order="asc" list_order_by="xxx" list_order="asc"]
* Ability to change Taxonomy and Post type slugs.
* Added new fields for listings.
* Customize the look and feel as much, or as little as you want
* Features can easily be enabled or disabled on the dashboard
* Users can easily maintain their listings through a front end control panel
* Fully customizable email notifications, for the admin and the user
* Strong focus on internationalization, we want this to work for everyone, everywhere
* Clean, fully responsive interface
* Social media integration, build traffic for your users
* Google Maps integration
* Lightweight but still powerful
* Supported Views: grid , compact

More information at [plugins.lddwebdesign.com](http://plugins.lddwebdesign.com)

= Extensions =

* [Directory Reports & Exports.](https://plugins.lddwebdesign.com/extensions/directory-reports-exports/) The LDD Directory Lite Reports and Exports extension allows a user to create reports and to export listings in multiple formats e.g XML, CSV, HTML, PDF.
* [Directory Import.](https://plugins.lddwebdesign.com/extensions/directory-import/) The LDD Directory Lite Import extension allows a user to import listings directly to their directory via CSV files that can be edited by hand or in applications such as Microsoft Excel.
* [Directory Reviews.](https://plugins.lddwebdesign.com/extensions/directory-listing-reviews/) The LDD Directory Lite Reviews extension add a powerful user review system to your online directory with star ratings, comments and filterable/searchable review content.
* [Directory Social Login.](https://plugins.lddwebdesign.com/extensions/directory-social-login/) The LDD Directory Lite Social Login extension that allow with the ability to login with facebook, google+, linkedin logins.
* [Directory Social Share.](https://plugins.lddwebdesign.com/extensions/directory-social-share/) The LDD Directory Lite Social Share extension add the ability to share your directory listings on popular social networks like Facebook, Twitter, Google+, LinkedIn, Pinterest and via E-mail.


== Installation ==

Installation is easiest when done through your WordPress Dashboard:

1. Select **Plugins >> Add New** from your sites WordPress dashboard.
1. Type **ldd directory lite** into the search field and press enter.
1. Click **Install Now** when you see ldd directory lite appear in the search results.
1. Select **Activate Plugin** after you see *Successfully installed the plugin ldd directory lite* appear on your screen.


= Upgrading from LDD Business Directory =

This plugin is a rewrite of the [LDD Business Directory](http://wordpress.org/plugins/ldd-business-directory/). As such, you can upgrade to ldd directory lite from LDD Business Directory *without* losing any of your content.

*You do not need to deactivate or uninstall the LDD Business Directory plugin before activating ldd directory lite.*

1. Install and activate the plugin using the above instructions.
1. Once installed, if data is detected from the original plugin it will automatically notify you with a rather large message at the top of your Directory Lite dashboard screens.
1. Press the "Import Data" button, and you will be taken to the upgrade page.
1. All done!

**The plugin only imports this data, it does nothing to alter or remove it. If you decide to revert to the original plugin because of errors encountered while using this beta release, you will have all you original content waiting for you.**


== Frequently Asked Questions ==

= How to sort categories, listings and featured listings using shortcode? =

You can sort categories using *cat_order_by*, listings using *list_order_by* and featured listings using *fl_order_by* attribute with shortcode.

e.g. [directory cat_order_by="**xxx**" cat_order="asc" fl_order_by="**xxx**" fl_order="asc" list_order_by="**xxx**" list_order="asc"]

**cat_order_by="xxx" :**
* id
* slug
* title
* count

**fl_order_by="**xxx**"**
* business_name
* zip
* area
* category
* random

**list_order_by="**xxx**"**
* business_name
* zip
* area
* category
* random

= How to show listings from specific category(s) using shortcode? =

You can display category specify listings using the following shortcode.

[directory_category slug="my-category,my-other-category" view="compact" list_order_by="title" list_order="ASC" limit="8"]

**view="xxx" :**
* grid
* compact

**list_order_by="**xxx**"**
* business_name
* zip
* area
* category
* random

**list_order="**xxx**"**
* ASC
* DESC

= How can I avoid having my template customizations overwritten when the plugin is updated? =

All the template files found in `/ldd-directory-lite/templates` can be copied to a directory in your theme called *lddlite_templates*.

For example, if you need to edit `/wp-content/plugins/ldd-directory-lite/templates/category.php`, you can copy it to `/wp-content/themes/your-theme-directory/lddlite_templates/category.php`. While you can copy the entire directory verbatim, it is recommended that you only copy the files you need.

== Screenshots ==

1. Most of ldd directory lite's configuration can be ignored or set once and forgotten; focus on content, don't worry about getting caught up constantly having to change settings!
2. Whether all your content is user generated, or if it's entirely owner generated, there's an easy to use interface for adding, editing, or removing listings.
3. We have done our best to design the directory's front end interface to integrate easily and painlessly with almost any theme. While it's impossible to guarantee this happening with 100% of themes, the fully responsive minimal design will quite often seem right at home, straight out of the box.


== Upgrade Notice ==

= 1.3.0 =
* Added limit options and pagination for search page
* Added pagination option and limit for home pages categories
* Updated basic queries for category listing page

= 1.2.2 =
* Fixed tag listing issue
* Added new template for listing_tag


= 1.2.1 =
* Updated sort order function and fixed bugs
* Fixed bug with css overwriting site theme fonts and structure
* Added support for content to display on Directory pages above and/or below Directory shortcodes

= 1.2.0 =
* Updated functionality of directory tags
* Added option to display full directory listing or list of categories at directory homepage
* Corrected search function to include all fields and tags
* Append to count child categories listings
* Add GEO Location for localizing map for users (not supported on http)
* Other minor bug fixes

= 1.1.2 =

* Added option for changeing behavior of category listings count (include or exclude sub categories).
* Fixed bug with listings pagination on some themes.
* Corrected output for contact form messages.
* Corrected major bug/conflict with Child Theme Configurator plugin causing all media files to be delted upan removal of LDD Directory Lite.
* Added GEO Location support for localizing map for users.

== Changelog ==

= 1.4 =
* Updated request for review notification behavior
* Corrected issue with Terms of Service notice on front end
* Updated social media links

= 1.3.3 =
* Fixed issues in listing sorts
* updates search filters

= 1.3.2 =
* Fixed issues on plugin activation

= 1.3.1 =
* Added Search sort option


= 1.3.0 =
* Added limit options and pagination for search page
* Added pagination option and limit for home pages categories
* Updated basic queries for category listing page

= 1.2.2 =
* Fixed tag listing issue
* Added new template for listing_tag

= 1.2.1 =
* Updated sort order function and fixed bugs
* Fixed bug with css overwriting site theme fonts and structure
* Added support for content to display on Directory pages above and/or below Directory shortcodes

= 1.2.0 =
* Updated functionality of directory tags
* Added option to display full directory listing or list of categories at directory homepage
* Corrected search function to include all fields and tags
* Append to count child categories listings
* Add GEO Location for localizing map for users (not supported on http)
* Other minor bug fixes


= 1.1.2 =

* Added option for changeing behavior of category listings count (include or exclude sub categories).
* Fixed bug with listings pagination on some themes.
* Corrected output for contact form messages.
* Corrected major bug/conflict with Child Theme Configurator plugin causing all media files to be delted upan removal of LDD Directory Lite.
* Added GEO Location support for localizing map for users.

= 1.1.0 =

* Corrected issue with subcategories not showing in the parent category view.
* Added hooks for start and end of the page wrapper in templates for third party theme compatibility.
* Added support for WordPress default themes.
* Updated plugin strings for translation.
* Restructured template structure in sub folders.
* Fixed Google Maps API - if user selects not to use Google Maps the API should not be required at all and Maps should not show on editor or front-end submission form.
* Fixed Google Maps GEO Code Error.
* Fixed issue when directory creates multiple pages upon deactivation and re-activation.
* Fixed Add-ons page to change the button from "Buy now" to "Installed" if add-on is already present.

= 1.0.2 =

* Added Skype field to front-end submission for contact info.
* Corrected issue with Address field in placeholder text.
* Updated banner for WordPress Repo.
* Fixed warning in search template.

= 1.0.1 =

* Added links to newest premium addons available
* Removed legacy shortcode and admin settigns for "Directory Information" (These can be added to the page editor in which the main shortcode for the directory is placed if the user wishes)
* Corrected issue with Fax and Skype contact fields not output to listings view.
* Corrected 404 error issue after installation and updating url slugs in settings.
* Corrected common theme conflict in directory navigation header dropdown for categories.
* Corrected conflict with CMB2 library if used by another installed plugin.
* Corrected install date issue when plugin is activated.

= 1.0.0 =

* Added rate this plugin link to header of settings page with other links.
* Added ability to auto-request a review after user has had the plugin activated for one week.
* Changed placeholder text for address/location fields.
* Added markup for telephone number to link to action (i.e. make phone call).

= 0.8.70 =

* Added support for admin to enable/disable public submissions.
* Added support for admin to select number of featured listings to display.
* Social links will open in new tab.
* Allow admin to hide/show default image placeholder.
* Added support to auto approve listings.
* Added help page for listing all available plugin options.
* Fixed plugin's template files.
* Fixed address format.
* Fixed grid view layout.
* Fixed pagination error.
* Added a new shortcode  [directory_category slug='xxx'] for displaying category specific listings.
* Fixed approval email template.
* Added support for adding Google Map API for removing javascript "noapi" notification issue.
* Updated CMB to CMB2.
* Minor bug fixes

= 0.8.6 =

* Added grid and compact view support.
* Fixed Pagination issue.
* Resolved wp_query global errors.
* Added support for displaying shortcodes in listings sidebar.
* Added add-ons support.
* Fixed shortcode overlapping issue.
* Fixed search issue.
* Minor bug fixes

= 0.8.53 =

* Fixed search menu for creating responsive view.
* Minor bug fixes
* Added support for wordpress 4.4.2

= 0.8.52 =

* Fixed list importing issue while updating from older version.
* Fixed sort issue for featured listings.
* Added pagination for inner pages.

= 0.8.51 =

* Added custom pagination support.
* Fixed css related issues.
* Fixed layout template issues.
* Added support for wordpress 4.4.1 

= 0.8.5-beta =

* Added new features, fixed a major issue with the submission process, and other minor updates.
* Added multiple categories section support for front-end users.
* Added an admin notice if wrong/same post types are used.
* Updated google maps for auto fetching locations based on user provided address.
* Added default sorting options in admin panel.
* Added sorting support using shortcodes.
* Added new fields for company listings.
* Fixed few bugs.

= 0.8.4-beta =

* Fixed an issue with the submission processor not verifying the correct nonce
* Added a notice to the registration form if registration is disabled
* Using EDD as a guide, rebuilt the settings API to allow for future extension development
* Fixed a bug in the Featured Listings query that was displaying listings regardless of how they were tagged

= 0.8-beta =

* Added the front end interface for users to maintain their approved listings
* Fixed a bug in the plugin localization that wasn't properly loading mo files

= 0.7.3-beta =

* Cleaned up code formatting and style considerably across the entire plugin, adding much needed inline documentation
* Removed the RainTPL class in favor of using native PHP templates (this was a rapid deployment solution that had a terminal lifespan to begin with)
* Rewrote the submission process in an attempt to simplify and improve the process
* Removed a lot of my ugly JavaScript hacks (I'm getting better!)
* Reduced dependence on internal styling; going forward the plugin should look "pretty enough" but leave most of the presentation to the end user
* Multitude of bug fixes
* Updated screenshots (don't actually forget to do this... )
* Outlined an upgrade system for seamless transitions from one version to the next
* Improved the import process for upgrading from LDD Business Directory
* Fixed two backwards compatibility errors
* Fixed a bug in the uninstall that was causing it to fail

= 0.5.4-beta =

* Major update to the upgrade functionality for people migrating from [LDD Business Directory](http://wordpress.org/plugins/ldd-business-directory/)
* Enqueued Bootstrap core as early as possible so that theme styles will cascade after
* Moved away from handling addresses internally, utilizing Google API more effectively
* Fixed outgoing emails hardcoded From: address
* Fixed an out of memory error being caused by `ldl_get_page_haz_shortcode()`, shortcode detection is now done during `save_post`

= 0.5.3-beta =

* Added opt-in anonymous usage tracking
* Trimmed a lot of excess from Bootstrap, and removed it from the global scope (it shouldn't affect the theme anymore)
* Condensed Bootflat into the main style sheet
* Fixed an issue with the search, submitting the form now takes you to physical search results

= 0.5.1-beta =
* Initial commit of the forked [LDD Business Directory](http://wordpress.org/plugins/ldd-business-directory/) plugin.


== Credits ==

This plugin is where it is thanks to the hard work and open source beliefs of a great many people. While I always try to make sure they are credited in the code itself, I would like to take the time to include them here as well. In no particular order:

**Who:** [WebDevStudios](http://webdevstudios.com/)<br>
**Where:** [Custom Metaboxes and Fields for WordPress](https://github.com/WebDevStudios/Custom-Metaboxes-and-Fields-for-WordPress)<br>
**Why:** Used to build the administrative user interface for the directory listings custom post types.

**Who:** [yoast](https://yoast.com)<br>
**Where:** [WordPress SEO](https://yoast.com/wordpress/plugins/#wpseo) Usage Tracking<br>
**Why:** Code from the WordPress SEO plugin was integrated into this plugin in order to quickly add anonymous usage tracking. The information supplied by those who opt in to this program allows us a fantastic insight as to what configurations the plugin is being used on, and how to test future releases.

**Who:** [Bootstrap](http://getbootstrap.com/)<br>
**Where:** Front end interface.<br>
**Why:** A trimmed down copy of bootstrap was used to rapidly define the front end interface for the plugin.

**Who:** [HappyJS](http://happyjs.com/)<br>
**Where:** Single listing view.<br>
**Why:** Happy.js is used on the listing page where strong validation is critical prior to a contact form being submitted.
