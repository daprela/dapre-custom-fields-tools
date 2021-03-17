=== Dapre Custom Fields Tools ===
Contributors: daprela
Donate link: http://giuliodaprela.com/
Tags: utility, debug, developer
Requires at least: 5.0.0
Tested up to: 5.7.0
Stable tag: 5.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin is the 'swiss army knife' of custom fields.
It allows you to manipulate them from the dashboard to make it easy testing and debugging applications.

== Description ==

This plugin is a 'swiss army knife' of custom fields. It allows you to manipulate them to make it easy the testing and debugging of applications.

This plugin was built in a moment of desperation when I couldn't access phpMyAdmin in a mu website.
I absolutely needed to manipulate the custom fields for testing and debugging, version 1.0 was up in 4 hours.
In the following months I realized how useful the plugin was and couldn't work anymore without it. It had become part of my standard toolset.

Features:
* Read/write/delete options, user fields and post fields.
* Populate the field with an empty array.
* Insert a date string and translate it into a timestamp or vice versa to emulate specific dates.
* Change name to a field.
* Copy any field's content to any other field (for example, option to user field) even of a different name.

You can contribute to the plugin or just study the code on the Github repo https://github.com/daprela/dapre-custom-fields-tools/

== Installation ==

1. Visit the plugins page within your dashboard and select ‘Add New’;
2. Search for ‘Dapre Custom Fields Tools’;
3. Activate the plugin from your Plugins page;

== Changelog ==

= 5.3.0 =
* Refactored the UI of the meta fields tables to use REACT.

= 5.2.0 =
* Meta fields rows can now be added and removed dynamically.
* Bug fixing.

= 5.1.1 =
* Fixed visualization bug in first row of meta fields.

= 5.1.0 =
* Improved UI

= 5.0.0 =
* Moved from AJAX to REST API.
* Moved from JQuery to vanilla JavaScript.

= 4.6.1 =
* Updated npm packages versions.
* Built distributable file.

= 4.6.0 =
* Refactored CSS to use grid and flexbox instead of tables.
* Refactored CSS to use BEVM coding principles.
* Refactored templates to comply with changes in CSS.

= 4.5.0 =
* Added Gulp workflow.
* Refactored code and created new plugin structure to meet the new workflow needs.
* Refactored JavaScript and removed all inline events left.

= 4.4.0 =
* refactored JavaScript to remove all inline events.
* bugfix: plugin left the delete option enabled with non existing meta fields.

= 4.3.0 =
* made all strings translatable and escaped output.

= 4.2.3 =
* added filter input to avoid accessing $_POST directly.

= 4.2.2 =
* Initialized variables containing CSS classes to remove warnings.

= 4.2.1 =
* Renamed constant PLUGIN_PATH with PLUGIN_DIRPATH for better clarity.

= 4.2.0 =
* Minor code refactoring. Abstracted some common methods.

= 4.1.1 =
* Moved conditionals from templates to classes.

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