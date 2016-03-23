# LDD Directory Lite

> This is a fork of the [LDD Business Directory](http://wordpress.org/plugins/ldd-business-directory/) plugin. Going forward the original plugin is considered unsupported and all active development will occur under the new name, ldd directory lite.

* **Working Version**: 0.8.60
* **Latest Stable**: 
* **Contributors**: [@lddweb](https://github.com/lddweb/)

The LDD Directory Lite plugin is the best way to add a powerful, fully functional and free custom directory to your WordPress powered web site.

Within minutes you can install and configure LDD Directory Lite and start accepting submissions, or populating the content with your own listings. This is the best and easiest way to start any kind of directory you can think of, be it of local businesses (restaurants, coffee shops, craft stores, etc), or any other type of organization you would like to build around.

The Directory is extremely user friendly, ready to go pretty much out of the box. One of our primary focuses is ensuring that it integrates with almost any theme, anywhere, anytime, and we have utilized the power of Bootstrap to ensure that the Directory is 100% mobile ready and responsive. It won't break all that hard work you've put into making sure your site works on any medium.

### Here are just a few of the many features:

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

### Extensions

* [Directory Reports & Exports.](http://plugins.lddwebdesign.com/extensions/directory-reports-exports/) The LDD Directory Lite Reports and Exports extension allows a user to create reports and to export listings in multiple formats e.g XML, CSV, HTML, PDF.
* [Directory Import.](http://plugins.lddwebdesign.com/extensions/directory-import/) The LDD Directory Lite Import extension allows a user to import listings directly to their directory via CSV files that can be edited by hand or in applications such as Microsoft Excel.

### Tags:

[business](https://wordpress.org/plugins/tags/business), [business-directory](https://wordpress.org/plugins/tags/business-directory), [directory](https://wordpress.org/plugins/tags/directory), [listing directory](https://wordpress.org/plugins/tags/listing-directory), listings, business listings

## Installation

###### *You should always back up your data and/or test on a staging site before making major changes to the way your production web site functions.*

Installation is easiest when done through your WordPress Dashboard:
Select the **Add New** option under your **Plugins** menu, type in *ldd directory lite* and install directly from the repository.

If you prefer to install manually, follow these steps;

1. Download the latest stable version from [WordPress.org](http://wordpress.org/plugins/ldd-directory-lite/)
2. Upload the archived plugin directory to your `/wp-content/plugins/` directory
3. Activate the plugin from the **Plugins** screen on your dashboard
4. Necessary pages will automatically be installed for you containing the appropriate short codes. Feel free to further customize these!

If you have any questions or are unsure about any part of the process, don't hesitate to ask for help in the [WordPress.org Support Forums](http://wordpress.org/support/plugin/ldd-directory-lite)

## Upgrading from LDD Business Directory

###### *You do not need to deactivate or uninstall the LDD Business Directory plugin before activating ldd directory lite.*

This plugin is a rewrite of the [LDD Business Directory](http://wordpress.org/plugins/ldd-business-directory/). As such, you can upgrade to ldd directory lite from LDD Business Directory *without* losing any of your content.

1. Install and activate the plugin using the instructions [here](http://wordpress.org/plugins/ldd-directory-lite/installation/).
2. The plugin will collect all your existing content and import it automatically.

As of now the plugin *does not* remove the data from the original Directory plugin. It also does not store any of its own data in the same locations, so the two datasets are completely separate.

While this does leave some clutter behind, it also ensures a painless upgrade path. Until the plugin has successfully been upgraded on a few hundred more sites, we chose not to remove your old data in case you either wanted to roll back to using the original plugin, or on the chance that something went awry with the beta.

A future version of ldd directory lite will scan for this leftover data and ask if you want to remove it.


## Issues & Bug Reports

**Please direct all bug reports to our [GitHub Issue Tracker](https://github.com/lddweb/ldd-directory-lite/issues)**

You are more than welcome to post problems and questions in the [WordPress.org Support Forums](http://wordpress.org/support/plugin/ldd-directory-lite), and we will do our best to respond in a timely fashion. Most posts will be answered within 3-5 business days.

If you have discovered an issue that you feel needs our attention, please take a look at our [GitHub Issues](https://github.com/lddweb/ldd-directory-lite/issues) page. You can open a New Issue to let us know what you found.

Please try to include as much information as possible, including steps that we can take to reproduce the issue and any other information that may be pertinent to your particular situation. If you can, include a list of other plugins you are running (in case it may be a conflict), what version of WordPress you're using, and any error messages or notices you saw.

**Feature requests can also be posted on the [GitHub Issue Tracker](https://github.com/lddweb/ldd-directory-lite/issues).**

## Credits

This plugin is where it is thanks to the hard work and open source beliefs of more people than just myself. While I always try to make sure they are credited in the code itself, I would like to take the time to include them here as well. In no particular order:

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

If I forgot anyone, it was not intentional and will be added to future updates of this file.