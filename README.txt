=== Plugin Name ===
Contributors: lddwebdesign
Tags: directory, listings, listing directory, business, business directory
Requires at least: 3.9.1
Tested up to: 3.9.1
Stable tag: 0.8.4-beta
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=7EUP56RH7E8RJ

The ldd directory lite plugin allows you to add a powerful directory listing of local businesses or other organizations to your WordPress web site.

== Description ==

The ldd directory lite plugin is the best way to add a powerful, fully functional and free custom directory to your WordPress powered web site.

Within minutes you can install and configure ldd directory lite and start accepting submissions, or populating the content with your own listings. This is the best and easiest way to start any kind of directory you can think of, be it of local businesses (restaurants, coffee shops, craft stores, etc), or any other type of organization you would like to build around.

The Directory is extremely user friendly, ready to go pretty much out of the box. One of our primary focuses is ensuring that it integrates with almost any theme, anywhere, anytime, and we have utilized the power of Bootstrap to ensure that the Directory is 100% mobile ready and responsive. It won't break all that hard work you've put into making sure your site works on any medium.

= Here are just a few of the many features =

* Easy to install, easy to configure
* Add the `[directory]` and `[directory_submit]` shortcode to any page (automatically installed for you, but feel free to change them)
* Customize the look and feel as much, or as little as you want
* Features can easily be enabled or disabled on the dashboard
* Users can easily maintain their listings through a front end control panel
* Fully customizable email notifications, for the admin and the user
* Strong focus on internationalization, we want this to work for everyone, everywhere
* Clean, fully responsive interface
* Social media integration, build traffic for your users
* Google Maps integration
* Lightweight but still powerful

= Coming soon =

* **Our web site! This will be the home of extended documentation, community forums, premium support and much more!**
* Premium modules, extend the capabilities of the Directory with features like import/export
* Shortcodes to display single categories or listings where ever you want

= Extensions =

We have a variety of premium extensions in the works for further extending the capabilities of ldd directory lite. If you're a developer interested in working on premium extensions for the Directory, please contact us at [web@lddconsulting.com](mailto:web@lddconsulting.com)


== Installation ==

Installation is easiest when done through your WordPress Dashboard:
1. Select **Plugins >> Add New** from your sites WordPress dashboard.
2. Type **ldd directory lite** into the search field and press enter.
3. Click **Install Now** when you see ldd directory lite appear in the search results.
4. Select **Activate Plugin** after you see *Successfully installed the plugin ldd directory lite* appear on your screen.


= Upgrading from LDD Business Directory =

This plugin is a rewrite of the [LDD Business Directory](http://wordpress.org/plugins/ldd-business-directory/). As such, you can upgrade to ldd directory lite from LDD Business Directory *without* losing any of your content.

*You do not need to deactivate or uninstall the LDD Business Directory plugin before activating ldd directory lite.*

1. Install and activate the plugin using the above instructions.
2. Once installed, if data is detected from the original plugin it will automatically notify you with a rather large message at the top of your Directory Lite dashboard screens.
3. Press the "Import Data" button, and you will be taken to the upgrade page.
4. All done!

**The plugin only imports this data, it does nothing to alter or remove it. If you decide to revert to the original plugin because of errors encountered while using this beta release, you will have all you original content waiting for you.**


== Frequently Asked Questions ==

= How can I avoid having my template customizations overwritten when the plugin is updated? =

All the template files found in `/ldd-directory-lite/templates` can be copied to a directory in your theme called *lddlite_templates*.

For example, if you need to edit `/wp-content/plugins/ldd-directory-lite/templates/category.php`, you can copy it to `/wp-content/themes/your-theme-directory/lddlite_templates/category.php`. While you can copy the entire directory verbatim, it is recommended that you only copy the files you need.

== Screenshots ==

1. Most of ldd directory lite's configuration can be ignored or set once and forgotten; focus on content, don't worry about getting caught up constantly having to change settings!
2. Whether all your content is user generated, or if it's entirely owner generated, there's an easy to use interface for adding, editing, or removing listings.
3. We have done our best to design the directory's front end interface to integrate easily and painlessly with almost any theme. While it's impossible to guarantee this happening with 100% of themes, the fully responsive minimal design will quite often seem right at home, straight out of the box.


== Upgrade Notice ==

= 0.8.4-beta =
Fixes a major issue with the submission process, and other minor updates.


== Changelog ==

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

I tend to spend a lot of time coding; not only is it my day job, but it's also one of the things I like to do in my spare time. No matter how much time I spend however, I'm just one person. This plugin is where it is thanks to the hard work and open source beliefs of a great many people. While I always try to make sure they are credited in the code itself, I would like to take the time to include them here as well. In no particular order:

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
