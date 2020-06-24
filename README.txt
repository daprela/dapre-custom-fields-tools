=== Dapre Custom Fields Tools ===
Contributors: daprela
Donate link: http://giuliodaprela.com/
Tags: utility, debug
Requires at least: 3.0.1
Tested up to: 4.9.7
Stable tag: 3.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin is the 'swiss army knife' of custom fields.
It allows you to manipulate them from the dashboard to make it easy testing and debugging applications.

== Description ==

This plugin is a 'swiss army knife' of custom fields. It allows you to manipulate them to make it easy the testing and debugging of applications

This plugin was built in a moment of desperation when I couldn't access phpMyAdmin in a mu website.
I absolutely needed to manipulate the custom fields for testing and debugging, version 1.0 was up in 4 hours.
In the following months I realized how useful the plugin was and couldn't work anymore without it. It had become part of my standard toolset.

== Installation ==

1. Download the master github repo.
2. Install the zip file. No settings needed.
2a. If you have Gulp you can generate a zip installable file.
3. Activate the plugin through the 'Plugins' menu in WordPress.

== Changelog ==

= 4.6.1 =
* Updated npm packages versions
* Built distributable file

= 4.6.0 =
* Refactored CSS to use grid and flexbox instead of tables
* Refactored CSS to use BEVM coding principles
* Refactored templates to comply with changes in CSS

= 4.5.0 =
* Added Gulp workflow
* Refactored code and created new plugin structure to meet the new workflow needs
* Refactored JavaScript and removed all inline events left

= 4.4.0 =
* refactored JavaScript to remove all inline events
* bugfix: plugin left the delete option enabled with non existing meta fields

= 4.3.0 =
* made all strings translatable and escaped output

= 4.2.3 =
* added filter input to avoid accessing $_POST directly

= 4.2.2 =
* Initialized variables containing CSS classes to remove warnings

= 4.2.1 =
* Renamed constant PLUGIN_PATH with PLUGIN_DIRPATH for better clarity

= 4.2.0 =
* Minor code refactoring. Abstracted some common methods.

= 4.1.1 =
* Moved conditionals from templates to classes

= 4.1.0 =
* Added PHP version check. Minimum version required is PHP 7.x

= 4.0.0 =
* Introduced classes for option fields, user fields and post fields
* Refactored the previous options array. Now there are three arrays instead of one
* It is now possible to also read/write base fields for user fields and post fields (fields that are in the wp_users table and wp_posts table)
* Improved error handling where the user get an error after trying to write on a meta field
* Added colorbox library

= 3.4.0 =
* Refactored settings page to make html code cleaner and get ready for the next evolution

= 3.3 =
* Added copy boxes to allow copy any field to any field even of a different type

= 3.2 =
* Added rename option box

= 3.1 =
* Moved the three boxes into tabs
* Switch tabs through JS

= 3.0 =
* converted plugin to AJAX

= 2.0 =
* Added actions
* Added checkbox to toggle date string/timestamp
* Possible to add an empty array

= 1.0 =
* First version, very basic.