=== Plugin Name ===
Contributors: lddwebdesign, delayedinsanity
Tags: directory, listings, listing directory, business, business directory, custom directory, catalog, index, yellow pages, white pages, 411, company listing
Requires at least: 3.9.1
Tested up to: 3.9.1
Stable tag: 0.5.5-beta
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=7EUP56RH7E8RJ

LDD Directory Lite allows you to add a powerful and fully integrated listing of local businesses or other organizations to your WordPress web site.

== Description ==

LDD Directory Lite is the best way to add a powerful, fully functional and free custom directory to your WordPress powered web site.

Within minutes you can install and configure LDD Directory Lite and start accepting submissions, or populating the content with your own listings. This is the best and easiest way to start any kind of directory you can think of, be it of local businesses (restaurants, coffee shops, craft stores, etc), or any other type of organization you would like to build around.

The Directory is extremely user friendly, ready to go pretty much out of the box. One of our primary focuses is ensuring that it integrates with almost any theme, anywhere, anytime, and we have utilized the power of Bootstrap to ensure that the Directory is 100% mobile ready and responsive. It won't break all that hard work you've put into making sure your site works on any medium.

We are also very developer friendly! LDD Directory Lite has another home on [GitHub](https://github.com/mwaterous/ldd-directory-lite), where you can get involved in making it even better.

= This plugin is a beta release. =

**Please be aware of this before installing, and see the [Other Notes](http://wordpress.org/plugins/ldd-directory-lite/other_notes/) tab if you encounter problems. **

= Here are just a few of the many features =

* Easy to install, easy to configure
* Add the `[directory]` shortcode to any page
* Customize the look and feel as much, or as little as you want
* Features can easily be enabled or disabled on the dashboard
* Fully customizable email notifications, for the admin and the user
* Focus on internationalization, this plugin works for everyone
* Clean, fully responsive interface
* Social media integration, build traffic for your users
* Google maps integration
* Live search, see your results as you type
* Powerful multi-part submission form

= Coming soon =

* User control panel, manage multiple listings and maintain full control over editing
* Premium modules, extend the capabilities of the Directory with features like import/export
* Shortcodes to display

= Extensions =

We have a variety of premium extensions in the works for further extending the capabilities of LDD Directory Lite. If you're a developer interested in working on premium extensions for the Directory, please contact us at [web@lddconsulting.com](mailto:web@lddconsulting.com)


== Installation ==

*You should always back up your data before making major changes to the way your production web site functions.*

This plugin

Installation is easiest when done through your WordPress Dashboard:
1. Select **Plugins >> Add New** from your sites WordPress dashboard.
2. Type **LDD Directory Lite** into the search field and press enter.
3. Click **Install Now** when you see LDD Directory Lite appear in the search results.
4. Select **Activate Plugin** after you see *Successfully installed the plugin LDD Directory Lite* appear on your screen.

If you prefer to install manually, [see the guide on Manually Installing Plugins](http://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation).

If you have any questions or are unsure about any part of the process, don't hesitate to ask for help in our [Support Forum](http://wordpress.org/support/plugin/ldd-directory-lite).

= Upgrading from LDD Business Directory =

This plugin is a rewrite of the [LDD Business Directory](http://wordpress.org/plugins/ldd-business-directory/). As such, you can upgrade to LDD Directory Lite from LDD Business Directory *without* losing any of your content.

*You do not need to deactivate or uninstall the LDD Business Directory plugin before activating LDD Directory Lite.*

1. Install and activate the plugin using the above instructions.
2. Once installed, if data is detected from the original plugin it will automatically notify you with a rather large message at the top of your Directory Lite dashboard screens.
3. Press the "Import Data" button, and you will be taken to the upgrade page.
4. All done!

**LDD Directory Lite only imports this data, it does nothing to alter or remove it. If you decide to revert to the original plugin because of errors encountered while using this beta release, you will have all you original content waiting for you.**


== Frequently Asked Questions ==

= Where can I suggest a new feature or report a bug? =

Please use our [issue tracker](https://github.com/mwaterous/ldd-directory-lite/issues) on the plugins [GitHub repo](https://github.com/mwaterous/ldd-directory-lite).

= How do I add featured listing thumbnails to the home page? =

From the Edit Listing screen on your WordPress dashboard, add "featured" as one of the tags for any listing you want to display on the home page. This feature will pick up to three random listings at a time, so feel free to add as many as you like!


== Screenshots ==

1. We know some people love turning switches on and off, but we believe the most efficient use of your time is focusing on content. Configure everything once, and never worry about it again!
2. Whether all your content is user generated, or if it's entirely owner generated, there's an easy to use interface for adding, editing, or removing listings.
3. The directory is built to be fully responsive, so they integrate without any fuss and are 100% mobile ready.
4. Users can easily get in touch with organizations listed on your site, keeping them on your web site.
5. The submission process is broken down into multiple pages. Never-ending one page forms can be intimidating; we encourage your users to get involved by making it as painless as possible!


== Upgrade Notice ==

= 0.5.5-beta =
This release addresses a variety of theme compatibility issues, and fixes a severe memory problem on the admin dashboard.


== Changelog ==

= 0.5.5-beta =
* Enqueued Bootstrap core as early as possible so that theme styles will cascade after
* Fixed outgoing emails hardcoded From: address
* Fixed an out of memory error being caused by `ldl_get_page_haz_shortcode()`, shortcode detection is now attempted during `save_post`

= 0.5.4-beta =
* Major update to the upgrade functionality for people migrating from [LDD Business Directory](http://wordpress.org/plugins/ldd-business-directory/)

= 0.5.3-beta =
* Added opt-in anonymous usage tracking
* Fixed an issue with the search, submitting the form now takes you to physical search results
* Trimmed a lot of excess from Bootstrap, and removed it from the global scope (it shouldn't affect the theme anymore)
* Condensed Bootflat into the main style sheet

= 0.5.1-beta =
* Initial commit of the forked [LDD Business Directory](http://wordpress.org/plugins/ldd-business-directory/) plugin.

== Issues & Bugs ==

**Please direct all bug reports to our [GitHub Issue Tracker](https://github.com/mwaterous/ldd-directory-lite/issues)**

You are more than welcome to post problems and questions in the [WordPress.org Support Forums](http://wordpress.org/support/plugin/ldd-directory-lite), and we will do our best to respond in a timely fashion. Most posts will be answered within 3-5 business days.

If you have discovered an issue that you feel needs our attention, please take a look at our [GitHub Issues](https://github.com/mwaterous/ldd-directory-lite/issues) page. You can open a New Issue to let us know what you found.

Please try to include as much information as possible, including steps that we can take to reproduce the issue and any other information that may be pertinent to your particular situation. If you can, include a list of other plugins you are running (in case it may be a conflict), what version of WordPress you're using, and any error messages or notices you saw.

**Feature requests can also be posted on the [GitHub Issue Tracker](https://github.com/mwaterous/ldd-directory-lite/issues).**

== Credits ==

I tend to spend a lot of time coding; not only is it my day job, but it's also one of the things I like to do in my spare time. No matter how much time I spend however, I'm just one person. This plugin is where it is thanks to the hard work and open source beliefs of more people than just myself. While I always try to make sure they are credited in the code itself, I would like to take the time to include them here as well. In no particular order:

**Who:** [WebDevStudios](http://webdevstudios.com/)<br>
**Where:** [Custom Metaboxes and Fields for WordPress](https://github.com/WebDevStudios/Custom-Metaboxes-and-Fields-for-WordPress)<br>
**Why:** Used to build the administrative user interface for the directory listings custom post types.

**Who:** Federico Ulfo<br>
**Where:** [Rain.TPL](http://www.raintpl.com/)<br>
**Why:** This amazing but lightweight template parser helps keep our views sorted away from our programming logic. It hasn't yet been decided whether or not this will be permanent (to capitalize on the caching abilities) or temporary (to run straight PHP templates for speed), it is an amazing parser that has helped me focus on other areas of programming.

**Who:** [yoast](https://yoast.com)<br>
**Where:** [WordPress SEO](https://yoast.com/wordpress/plugins/#wpseo) Usage Tracking<br>
**Why:** Code from the WordPress SEO plugin was integrated into this plugin in order to quickly add anonymous usage tracking. The information supplied by those who opt in to this program allows us a fantastic insight as to what configurations the plugin is being used on, and how to test future releases.

**Who:** [Bootstrap](http://getbootstrap.com/) & [Bootflat](http://bootflat.github.io/)<br>
**Where:** Front end interface.<br>
**Why:** A trimmed down copy of bootstrap (which can be disabled if your theme already uses it) and bootflat were used to rapidly define the front end interface for the plugin. These libraries are amazing feats of CSS prowess.

**Who:** [Viljami S.](http://viljamis.com), [Henrik Joreteg, Adam Brault & Michael Garvin](http://happyjs.com/), [Ryan Heath](http://rpheath.com/)<br>
**Where:** [Responsive Slides](http://responsiveslides.com), [Happy.js](http://happyjs.com/), and [searchbox](https://github.com/rpheath/searchbox)<br>
**Why:** Various javascript elements all around the plugin are built on or with scripts originally coded by the above folk. As of this writing, my own javascript abilities are nowhere near as advanced as my ability to draw circles on my stomach and pat my head at the same time. Until they are, I seek out the knowledge of others.

**Who:** [Evan Solomon](http://evansolomon.me/), [Michel Fortin](http://michelf.ca), & [John Gruber](http://daringfireball.net)<br>
**Where:** [WP Github Flavored Markdown Comments](https://github.com/evansolomon/wp-github-flavored-markdown-comments/blob/master/github-flavored-markdown-comments.php)<br>
**Why:** Because markdown! Markdown is slowly being integrated in to the front end of the plugin, which will allow users to have some measure of control over the way their listings appear (should the site administrator choose to enable it).

**Who:** [Tim Schlechter](https://github.com/TimSchlechter)<br>
**Where:** [Bootstrap Tags Input](https://timschlechter.github.io/bootstrap-tagsinput/examples/)<br>
**Why:** This was added initially on the dashboard to group multiple notification email addresses, but I can see it easily having a future on the front end of the plugin as well.
