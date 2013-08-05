=== Plugin Name ===
Contributors: suffrage
Donate link: http://www.lddwebdesign.com
Tags: business directory, ldd, business
Requires at least: 2.0.2
Tested up to: 3.6
Stable tag: 1.3.8
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

The LDD Business Directory plugin allows a user to easily manage and display businesses and their information on a WordPress site. Simply use the shortcode [business_directory] on any wordpress page and the directory is displayed, allowing visitors to search through the directory and submit a listing of their own. If the user has a listing on the directory, they can easily sign in and edit their information. From the WordPress backend, it is easy to categorize businesses and edit their information, as well as add any custom information fields that may be needed. Plus the settings allow for a greater range of customization of the functionality.

== Installation ==

1. Install from the WordPress plugins utility or simply move the `ldd-business-directory` folder to your plugins directory (`/wp-content/plugins`)
2. Make sure you activate the plugin on your plugins page
3. Now you should have a `Directory Listings` menu item on your dashboard
4. From here you can alter your settings, add businesses, edit business information, and customize your categories
5. Decide which page you wish to display it on or create a page and enter the shortcode `[business_directory]` in the content area of your page
6. You can also use the php function `<?php display_business_directory(); ?>` directly in your template file

== Frequently Asked Questions ==

= Q. Nothing is displayed when I click on a business entry! How can I fix this? =
A. Try changing your Permalink settings to `Post name` and refresh your page.

= Q. Does the Login feature connect with my Wordpress login? =
A. No. It is strictly for anyone who has submitted a business listing and would like to make changes to their entry.

= Q. I would like to use this plugin for community organizations but need to change the text in places where it says business. Is this possible? =
A. There is now a text field on the Settings page called `Directory Label` where you can change this text.

= Q. The same image is being displayed across all the listings, why is this? =
A. The filename of the logo used for each listing is based upon the *username* that has been entered for each individual listing. If all the listings have no username then whatever logo you upload for any one of them will be applied to all of them (same goes if multiple listings have the same username). Each listing should have a unique username.

== Changelog ==

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


== Upgrade Notice ==

= 1.3.8 =