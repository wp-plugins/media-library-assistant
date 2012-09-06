=== Plugin Name ===
Contributors: dglingren
Donate link: 
Tags: attachments, documents, gallery, image, images, media, library, media library, media-tags, media tags, tags, media categories, categories
Requires at least: 3.3
Tested up to: 3.4.1
Stable tag: 0.20
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Provides admin pages with several enhancements to the handling of images and files held in the WordPress Media Library.

== Description ==

The Media Library Assistant provides several enhancements for managing the Media Library, including:

* Shows which posts use a media item as the "featured image"
* Shows which posts use a media item as an inserted image or link
* Displays more attachment information such as parent information, file URL and image metadata
* Allows you to edit the attachment name/slug and to "unattach" items
* Supports custom taxonomies, including pre-defined support for Attachment Categories and Attachment Tags
* Provides additional view filters for mime types and Attachment Categories
* Has an inline "Quick Edit" action for many common fields.

The Assistant is designed to work like the standard Media Library pages, so the learning curve is short and gentle. Contextual help is provided on every new screen to highlight new features.

== Installation ==

1. Upload `media-library-assistant` and its subfolders to your `/wp-content/plugins/` directory
1. Activate the plugin through the "Plugins" menu in WordPress
1. Visit the settings page to customize category and tag support
1. Visit the "Assistant" submenu in the Media admin section
1. Click the Screen Options link to customize the display
1. Use the enhanced Edit page to assign categories and tags

== Frequently Asked Questions ==

= Does the Assistant use the WordPress post Categories and Tags? =

Not at this time. The pre-defined Attachment Categories and Attachment Tags are WordPress custom taxonomies, with all of the API support that implies. You can activate or deactivate the pre-defined taxonomies at any time by visiting the Media Library Assistant Settings page.

= Can I add my own custom taxonomies to the Assistant? =

Yes. Any custom taxonomy you register with the Attachment post type will appear in the Assistant UI.

= How do I "unattach" an item? =

Hover over the item you want to modify and click the "Edit" action. On the Edit Single Item page, set the ID portion of the Parent Info field to zero (0), then click "Update" to record your changes. If you change your mind, click "Cancel" to return to the main page without recording any changes.

= Are other language versions available? =

Not at this time; I don't have working knowledge of anything but English. If you'd like to volunteer to produce another version, I'll rework the code to internationalize it and work with you to localize it.

= What's in the "phpDocs" directory and do I need it? =

All of the MLA source code has been annotated with "DocBlocks", a special type of comment used by phpDocumentor to generate API documentation. If you'd like a deeper understanding of the code, click on "index.html" in the phpDocs dorectory and have a look. Note that these pages require JavaScript for much of their functionality.

== Screenshots ==

1. The Media Library Assistant submenu showing the available columns, incluging "Featured in", "Inserted in", "Att. Categories" and "Att. Tags"
2. The enhanced Edit page showing additional fields, categories and tags
3. The Attachment Categories edit taxonomy page
4. The Settings page, to customize support of Attachment Categories and Attachment Tags

== Changelog ==

= 0.20 =
* New: Quick Edit action for inline editing of attachment metadata
* New: Post Author can be changed
* New: Hyperlink to phpDocs documentation added to Settings page
* New: Shortcode documentation added to settings page
* New: Some book credits added to the "Other Notes" section
* Change: Minified version of JavaScript files are loaded unless 'SCRIPT_DEBUG' is defined as true in wp-config.php
* Change: Global functions moved into classes to minimize the chance of name conflicts
* Change: All class, function and constant names are now checked for conflicts with other plugins and themes
* Fix: Retain pagination values, e.g., page 3 of 5, when returning from row-level actions
* Fix: Retain orderby and order values, e.g., descending sort on date, when returning from row-level actions

= 0.11 =
* Fix: Changed admin URL references from relative (/wp-admin/...) to absolute, using admin_url().
* Fix: Changed wp_nonce_field() calls to suppress duplicate output of nonce field variables.
* Fix: Changed the minimum WordPress version required to 3.3.

= 0.1 =
* Initial release.

== Upgrade Notice ==

= 0.20 =
You should upgrade to this version if you need "Quick Edit" functionality.

= 0.11 =
You should upgrade to this version if you are getting "404 Not Found" errors when updating single items.

= 0.1 =
Initial release.

= Other Notes =

I have used and learned much from the following books (among many):

* Professional WordPress; Design and Development, by Hal Stern, David Damstra and Brad Williams (Apr 5, 2010) ISBN-13: 978-0470560549
* Professional WordPress Plugin Development, by Brad Williams, Ozh Richard and Justin Tadlock (Mar 15, 2011) ISBN-13: 978-0470916223
* WordPress 3 Plugin Development Essentials, by Brian Bondari and Everett Griffiths (Mar 24, 2011) ISBN-13: 978-1849513524
* WordPress and Ajax, by Ronald Huereca (Jan 13, 2011) ISBN-13: 978-1451598650