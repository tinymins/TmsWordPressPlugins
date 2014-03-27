=== Simple Relative Content ===
Contributors: Semio Design, pabois
Tags: uri, relative, relative uri, relative path, basepath, site url, WP_SITEURL, environment, staging, production
Tested up to: 3.5
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin makes all attachments urls stored in database relative to the WP_SITEURL url.

== Description ==

When you use the WordPress editor to insert a media in the content field it stores a reference to the absolute path. 
So if you change the location of your site (by switching from staging to production environment for example) all your paths will be incorrect.
This plugin stores relative pathes in the db instead of an absolute path.
Beware: this plugin must be activated BEFORE inserting a media into the content field. It has no effect on the medias stored before the activation.
It's mostly based on the WP_SITEURL constant which should be defined in the wp_config.php file. If this constant isn't defined it uses the "siteurl" option value instead.
If the plugin detects that you access the website from another location than the "siteurl" defined initially it can correct most of the wrong urls in the database.


Localisation of the plugin into french.

== Installation ==

You can use the built in installer and upgrader, or you can install the plugin manually.

1. You can either use the automatic plugin installer or your FTP program to upload it to your wp-content/plugins directory
the top-level folder. Don't just upload all the php files and put them in `/wp-content/plugins/`.
1. Activate the plugin through the 'Plugins' menu in WordPress
1. That's it!

If you have to upgrade manually simply repeat the installation steps.
