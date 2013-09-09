=== Plugin Name ===
Contributors: suffrage
Donate link: http://www.lddwebdesign.com
Tags: business directory, ldd, lddbd, business, directory, listings, listings directory, directory plugin, business plugin, business directory plugin, listings plugin, listings directory plugin, business listings, business listings plugin
Requires at least: 3.5
Tested up to: 3.6
Stable tag: 1.3.10
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Create a directory of businesses and listings for your WordPress website.

== Description ==

The LDD Business Directory plugin allows a user to easily manage and display businesses and/or listings along with their information on a WordPress website. Simply using the shortcode `[business_directory]` on any WordPress page will display the directory as well as allow visitors to search through the directory and submit a business or listing of their own.

For users that have a listing on the directory, they can easily sign in and edit their information through the *Login* feature. From the WordPress administration it is easy to categorize businesses and listings, edit their information, and add any custom information fields that site administrator may want to include.

You can visit us at:

<http://www.lddwebdesign.com> or <http://www.lddconsulting.com>

If there are any questions or concerns feel free to contact us at the [LDD Business Directory homepage](http://www.lddwebdesign.com/ldd-web-design-featured-projects/ldd-business-directory-plugin/ "LDD Web Design - LDD Business Directory").

== Installation ==

1. Install from the WordPress plugins utility or simply upload the `ldd-business-directory` folder to your plugins directory (`/wp-content/plugins`).
2. Make sure you activate the plugin through the 'Plugins' page from the menu in WordPress.
3. Now you should have a `Directory` menu item on your dashboard.
4. From here you can alter your settings, add listings, edit listing information, approve user submitted listings, remove listings, and create categories.
5. Decide which page you wish to display it on or create a page and enter the shortcode `[business_directory]` in the content area of your page.
6. You can also use the PHP function `<?php display_business_directory(); ?>` directly in your template file.

== Frequently Asked Questions ==

= Q. Nothing is displayed when I click on a business entry! How can I fix this? =
A. Try changing your Permalink settings to *Post name* and refresh your page.

= Q. Does the Login feature connect with my Wordpress login? =
A. No. It is strictly used for the *Business Directory* and is for those who have submitted a business listing and would like to make changes to their directory entry.

= Q. I would like to use this plugin for community organizations but need to change the text in places where it says business. Is this possible? =
A. There is a text field on the Settings page called `Directory Label` where you can change this text.

= Q. The same image is being displayed across all the listings, why is this? =
A. The filename of the logo used for each listing is based upon the *login name* that has been entered for each individual listing. If all the listings have no login then whatever logo has been uploaded for any one of them will be applied to all of them (same goes if multiple listings have the same login). Each listing should have a unique login.

== Changelog ==

= 1.3.10 =

- Made WordPress 3.5 the minimum required version to operate this plugin.
- Modified logo file naming function to use listing names instead of login names.
- Added dialog boxes to Submit Listing to ensure a `listing name` and/or `login name` is entered.
- Added setting to change the default view of the directory front end to either Categories or Listings.

= 1.3.9 =

- Added Malaysia to the country list.
- Added Malaysian states/provinces dropdown when selecting Malaysia in the country selector.
- All Listings is now the default view instead of Categories.
- Fixed bug where uploaded documents lead to 404 error pages.

= 1.3.8.2 =

- Fixed a bug that removed client logo when editing a listing in the back end.

= 1.3.8.1 =

- Patched logo/file migration code to fix issue from warning message

= 1.3.8 =

- Fixed bug that caused the `Warning: Missing argument 2 for wpdb::prepare()` message to appear.
- Revised handling of logos and files again so that they are uploaded to `/wp-content/uploads/`.
- Added code to move logos and files from previous versions to the new directory.
- Corrected verbiage in some spots of the plugin to read as Listing instead of Business.

= 1.3.7 =

- Fixed bug that prevented Web, Facebook, Twitter, and LinkedIn icons from working.

= 1.3.6 =

- Added the ability to change the directory label from `Business` to whatever the user chooses and have this change reflected across the entire plugin.
- Fixed some bugs in the process of adding/removing categories.
- Fixed bug involving jQuery that broke some functionality in the back end.

= 1.3.4 =

- Revised how logos and files are handled by the business directory.

= 1.3.2 =

- Welcome message now displays on the front end.
- Added international address support for specific countries.
- Fixed error that contained warning message about headers already being sent when adding a new business.
- Added the ability to disable Google Maps from all businesses.
- Added ability to change the directory title from **Business Directory** to anything else.
- Welcome message appears above directory like it should.
- Additional Information sections have a small area to be displayed on the front end.

= 1.2.1 =

- Modified form(s) to allow a workaround for international addresses.
- Separated plugin out in to multiple config files for easier code maintenence.

= 1.1.2 =

Added options for directory title, and whether to display the promo filter in the search box.

= 1.1 =

Added ability to remove a logo, fixed some javascript bugs and updated styling.