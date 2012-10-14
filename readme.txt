=== Plugin Name ===
Contributors: dglingren
Donate link: http://fairtradejudaica.org/make-a-difference/donate/
Tags: attachments, documents, gallery, image, images, media, library, media library, media-tags, media tags, tags, media categories, categories
Requires at least: 3.3
Tested up to: 3.4.2
Stable tag: 0.50
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Provides enhancements to the Media Library; powerful[mla_gallery], full taxonomy support, bulk & quick edit actions and where-used reporting.

== Description ==

The Media Library Assistant provides several enhancements for managing the Media Library, including:

* The **[mla_gallery] shortcode**, used in a post, page or custom post type to add a gallery of images and/or other Media Library items (such as PDF documents). [MLA Gallery](http://wordpress.org/extend/plugins/media-library-assistant/other_notes/ "Complete Documentation") is a superset of the [gallery] shortcode in the WordPress core; it is compatible with [gallery] and provides many enhancements. These include: 1) full support for WordPress categories, tags and custom taxonomies, 2) support for all post_mime_type values, not just images 3) media Library items need not be "attached" to the post.

* An inline "Bulk Edit" area; update author or parent, add, remove or replace taxonomy terms for several attachments at once.
* An inline "Quick Edit" action for many common fields.
* Complete support for ALL taxonomies, including the standard Categories and Tags, your custom taxonomies and the Assistant's pre-defined Att. Categories and Att. Tags. You can add taxonomy columns to the Assistant listing, filter on any taxonomy, assign terms and list the attachments for a term.
* Shows which posts use a media item as the "featured image"
* Shows which posts use a media item as an inserted image or link
* Displays more attachment information such as parent information, file URL and image metadata
* Allows you to edit the attachment author, the name/slug and to "unattach" items
* Provides additional view filters for mime types and taxonomies
* Provides many more listing columns (more than 15) to choose from

The Assistant is designed to work like the standard Media Library pages, so the learning curve is short and gentle. Contextual help is provided on every new screen to highlight new features. The [Other Notes section](http://wordpress.org/extend/plugins/media-library-assistant/other_notes/ "Click here, then scroll down for Help Summary") contains a summary of the help text following the [mla_gallery] documentation.

This plugin was inspired by my work on the WordPress web site for our nonprofit, Fair Trade Judaica. If you find the Media Library Assistant plugin useful and would like to support a great cause, consider a [<strong>tax-deductible</strong> donation](http://fairtradejudaica.org/make-a-difference/donate/ "Support Our Work") to our work. Thank you!

== Installation ==

1. Upload `media-library-assistant` and its subfolders to your `/wp-content/plugins/` directory
1. Activate the plugin through the "Plugins" menu in WordPress
1. Visit the settings page to customize category and tag support
1. Visit the "Assistant" submenu in the Media admin section
1. Click the Screen Options link to customize the display
1. Use the enhanced Edit page to assign categories and tags
1. Use the [mla_gallery] shortcode to add galleries of images, documents and more to your posts and pages

== Frequently Asked Questions ==

= How can I use Categories, Tags and custom taxonomies to select images for display in my posts and pages? =

The powerful [mla_gallery] shortcode supports almost all of the query flexibility provided by the WP_Query class. You can find [complete documentation](http://wordpress.org/extend/plugins/media-library-assistant/other_notes/ "Complete Documentation") in the Other Notes section.

= Can I use [mla_gallery] for attachments other than images? =

Yes! The [mla_gallery] shortcode supports all MIME types. You can build a gallery of your PDF documents, plain text files and other attachments. You can mix images and other MIME types in the same gallery, too; check out [the documentation](http://wordpress.org/extend/plugins/media-library-assistant/other_notes/ "Complete Documentation").

= Can I attach an image to more than one post or page? =

No; that's a structural limitation of the WordPress database. However, you can use Categories, Tags and custom taxonomies to organize your images and associate them with posts and pages in any way you like. The [mla_gallery] shortcode makes it easy.

= Can the Assistant use the standard WordPress post Categories and Tags? =

Yes! You can activate or deactivate support for Categories and Tags at any time by visiting the Media Library Assistant Settings page.

= Do I have to use the WordPress post Categories and Tags? =

No! The Assistant supplies pre-defined Att. Categories and Att. Tags; these are WordPress custom taxonomies, with all of the API support that implies. You can activate or deactivate the pre-defined taxonomies at any time by visiting the Media Library Assistant Settings page.

= Can I add my own custom taxonomies to the Assistant? =

Yes. Any custom taxonomy you register with the Attachment post type will appear in the Assistant UI. Use the Media Library Assistant Settings page to add support for your taxonomies to the Assistant UI.

= Why don't the "Posts" counts in the taxonomy edit screens match the search results when you click on them? =

This is a known WordPress problem with multiple support tickets already in Trac, e.g., 
Ticket #20708(closed defect (bug): duplicate) Wrong posts count in taxonomy table,
Ticket #14084(assigned defect (bug)) Custom taxonomy count includes draft & trashed posts,
and Ticket #14076(closed defect (bug): duplicate) Misleading post count on taxonomy screen.

For example, if you add Tags support to the Assistant and then assign tag values to your attachments, the "Posts" column in the "Tags" edit screen under the Posts admin section includes attachments in the count. If you click on the number in that column, only posts and pages are displayed. There are similar issues with custom post types and taxonomies (whether you use the Assistant or not). The "Attachments" column in the edit screens added by the Assistant shows the correct count because it works in a different way.

= How do I "unattach" an item? =

Hover over the item you want to modify and click the "Edit" action. On the Edit Single Item page, set the ID portion of the Parent Info field to zero (0), then click "Update" to record your changes. If you change your mind, click "Cancel" to return to the main page without recording any changes.

= Are other language versions available? =

Not at this time; I don't have working knowledge of anything but English. If you'd like to volunteer to produce another version, I'll rework the code to internationalize it and work with you to localize it.

= What's in the "phpDocs" directory and do I need it? =

All of the MLA source code has been annotated with "DocBlocks", a special type of comment used by phpDocumentor to generate API documentation. If you'd like a deeper understanding of the code, click on "index.html" in the phpDocs dorectory and have a look. Note that these pages require JavaScript for much of their functionality.

== Screenshots ==

1. The Media Library Assistant submenu showing the available columns, including "Featured in", "Inserted in", "Att. Categories" and "Att. Tags"; also shows the Quick Edit area.
2. The enhanced Edit page showing additional fields, categories and tags
3. A typical edit taxonomy page, showing the "Attachments" column
4. The Settings page, to customize support of Att. Categories, Att. Tags and other taxonomies and the default sort order
5. The Media Library Assistant submenu showing the Bulk Edit area with taxonomy Add, Remove and Replace options; also shows the tags suggestion popup.

== Changelog ==

= 0.50 =
* New: [mla_gallery] shortcode, a superset of the [gallery] shortcode that provides many enhancements. These include taxonomy support and all post_mime_type values (not just images). Media Library items need not be "attached" to the post.
* New: [mla_gallery] shortcode documentation added to Settings page
* New: Donate button and link added to Settings page

= 0.41 =
* Fix: SQL View (supporting ALT Text sorting) now created for automatic plugin upgrades

= 0.40 =
* New: Bulk Edit area; update author or parent, add, remove or replace taxonomy terms for several attachments at once
* New: ALT Text is now a sortable column, and shows attachments with no ALT Text value
* New: Activate and deactivate hooks added to create and drop an SQL View supporting ALT Text sorting
* New: Revisions are excluded from the where-used columns; a settings option lets you include them if you wish
* Fix: Better validation/sanitization of data fields on input and display
* Fix: Database query validation/sanitization with wpdb_prepare()
* Fix: check_admin_referer added to settings page
* Fix: Inline CSS styles for message DIV moved to style sheet

= 0.30 =
* New: Complete support for all taxonomies registered with WordPress, including the standard Categories and Tags, your custom taxonomies and the Assistant's pre-defined Att. Categories and Att. Tags. You can add taxonomy columns to the Assistant admin screen, filter the listing on any taxonomy, assign terms to attachments and list the attachments for a taxonomy term.
* New: MIME Type and Last Modified Date added to columns listing
* New: Last Modified Date added to single item edit screen
* New: Default column and sort order added to Settings page
* New: Plugin version number added to Settings page header
* Fix: Text fields such as Title, Alternate Text and Caption containing single quotes are no longer truncated on the Edit single item screen
* Fix: Sortable columns and sort order updated.

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

= 0.50 =
Upgrade for the new [mla_gallery] shortcode, a superset of the [gallery] shortcode that provides many enhancements. These include taxonomy support and all post_mime_type values (not just images).

= 0.41 =
Upgrade for the new Bulk Edit area; add, remove or replace taxonomy terms for several attachments at once. Sort your media listing on ALT Text, exclude revisions from where-used reporting.

= 0.40 =
Upgrade for the new Bulk Edit area; add, remove or replace taxonomy terms for several attachments at once. Sort your media listing on ALT Text, exclude revisions from where-used reporting.

= 0.30 =
Upgrade to support ALL taxonomies, including the standard Categories and Tags, your custom taxonomies and the Assistant's pre-defined Att. Categories and Att. Tags. Add taxonomy columns to the Assistant admin screen, filter on any taxonomy, assign terms and list the attachments for a term. 

= 0.20 =
You should upgrade to this version if you need "Quick Edit" functionality.

= 0.11 =
You should upgrade to this version if you are getting "404 Not Found" errors when updating single items.

= 0.1 =
Initial release.

==MLA Gallery Shortcode==

The [mla_gallery] shortcode is used in a post, page or custom post type to add a gallery of images and/or other Media Library items (such as PDF documents). MLA Gallery is a superset of the [gallery] shortcode in the WordPress core; it is compatible with [gallery] and provides many enhancements. These include:

* Full support for WordPress categories, tags and custom taxonomies. You can select items with any of the taxonomy parameters documented in the WP_Query class.
* Support for all post_mime_type values, not just images.
* Media Library items need not be "attached" to the post. You can build a gallery with any combination of items in the Library using taxonomy terms, custom fields and more.

All of the options/parameters documented for the [gallery] shortcode are supported by the [mla_gallery] shortcode; you can find them in the WordPress Codex. Most of the parameters documented for the WP_Query class are also supported; see the WordPress Codex. Because the [mla_gallery] shortcode is designed to work with Media Library items, there are some parameter differences and extensions; these are documented below.

<h4>Include, Exclude</h4>

You can use "post_parent=all" to include or exclude attachments regardless of which post or page they are attached to. You can use "post_mime_type=all" to include or exclude attachments of all MIME types, not just images.

<h4>Size</h4>

The Size parameter specifies the image size to use for the thumbnail display. Valid values include "thumbnail", "medium", "large", "full" and any other additional image size that was registered with add_image_size(). The default value is "thumbnail". You can use "none" to suppress thumbnail display and substitute the item title string for the image.

The [mla_gallery] shortcode supports an additional Size value, "icon", which shows a 60x60 pixel thumbnail for image items and an appropriate icon for non-image items such as PDF or text files.

<h4>Order, Orderby</h4>

To order the gallery randomly, use "orderby=rand". To suppress gallery ordering you can use "orderby=none" or "order=rand".

The Orderby parameter specifies which database field is used to sort the gallery. You can order the gallery by any of the values documented for the WP_Query class reference in the Codex; you are NOT restricted to the values documented for the [gallery] shortcode.

<h4>Post ID, Post Parent</h4>

The "id" parameter lets you specify a post ID for your query. If the "id" parameter is not specified, the [mla_gallery] behavior differs from the [gallery] behavior. If your query uses taxonomy or custom field parameters, "author", "author_name" or "s" (search term), then the query will NOT be restricted to items attached to the current post. This lets you build a gallery with any combination of Media Library items that match the parameters.

You can use the "post_parent" to override the default behavior. If you set "post_parent" to a specific post ID, only the items attached to that post are displayed. If you set "post_parent" to "current", only the items attached to the current post are displayed. If you set "post_parent" to "all", the query will not have a post ID or post_parent parameter.

For example, `[mla_gallery tag="artisan"]` will display all images having the specified tag value, regardless of which post (if any) they are attached to. If you use `[mla_gallery tag="artisan" post_parent="current"]` it will display images having the specified tag value only if they are attached to the current post.

<h4>Author, Author Name</h4>

You can query by author's id or the "user_nicename" value (not the "display_name" value). Multiple author ID values are allowed, but only one author name value can be entered.

<h4>Category Parameters</h4>

Remember to use "post_parent=current" if you want to restrict your query to items attached to the current post.

<h4>Tag Parameters</h4>

Remember to use "post_parent=current" if you want to restrict your query to items attached to the current post.

Note that the "tag_id" parameter requires exactly one tag ID; multiple IDs are not allowed. You can use the "tag__in" parameter to query for multiple values.

<h4>Taxonomy Parameters</h4>

The [mla_gallery] shortcode supports the simple "{tax} (string)" values (deprecated as of WordPress version 3.1) as well as the more powerful "tax_query" value. 

For simple queries, enter the taxonomy name and the term(s) that must be matched, e.g.:

* `[mla_gallery attachment_category='separate-category,another-category']`

Note that you must use the name/slug strings for taxonomy and terms, not the "title" strings.

More complex queries can be specified by using "tax_query", e.g.:

* `[mla_gallery tax_query="array(array('taxonomy' => 'attachment_tag','field' => 'slug','terms' => 'artisan'))"]`
* `[mla_gallery tax_query="array(array('taxonomy' => 'attachment_category','field' => 'id','terms' => array(11, 12)))" post_parent=current post_mime_type='']`

The first example is equivalent to the simple query "attachment_tag=artisan". The second example matches items of all MIME types, attached to the current post, having an attachment_category ID of 11 or 12.

When embedding the shortcode in the body of a post, be very careful when coding the tax_query; it must be a valid PHP array specification. In particular, code the query on one line; splitting it across lines can insert HTML &#8249;br&#8250; tags and corrupt your query. 

Remember to use "post_parent=current" if you want to restrict your query to items attached to the current post.

<h4>Post Type, Post Status and Post MIME Type</h4>

For compatibility with the WordPress [gallery] shortcode, these parameters default to "post_type=attachment", "post_status=inherit" and "post_mime_type=image". You can override the defaults to, for example, display items in the trash ("post_status=trash") or PDF documents ("post_mime_type=application/pdf") or all MIME types ("post_mime_type=all"). I'm not sure why you'd want to override "post_type", but you are welcome to experiment and let me know what you find.

<h4>Pagination Parameters</h4>

The [mla_gallery] shortcode supplies "nopaging=true" as a default parameter. If you are working with a template that supports pagination you can replace this with specific values for "posts_per_page", "posts_per_archive_page", "paged" and/or "offset" . You can also pass "paged=current" to suppress the "nopaging" default; "current" will be replaced by the appropriate value (get_query_var('paged')).

<h4>Time Parameters</h4>

Support for time parameters is not included in the current version. I may add it later - let me know if you need it.

<h4>Custom Field Parameters</h4>

The [mla_gallery] shortcode supports the simple custom field parameters as well as the more powerful "meta_query" parameters made available as of WordPress 3.1.

When embedding the shortcode in the body of a post, be very careful when coding the meta_query; it must be a valid PHP array specification. In particular, code the query on one line; splitting it across lines can insert HTML <br> tags and corrupt your query.

Remember to use "post_parent=current" if you want to restrict your query to items attached to the current post.

<h4>Search Keywords</h4>

The search parameter ("s=keyword") will perform a keyword search. A cursory inspection of the code in /wp-includes/query.php reveals that the search includes the "post_title" and "post_content" (Description) fields but not the "post_excerpt" (Caption) field. An SQL "LIKE" clause is composed and added to the search criteria. I haven't done much testing of this parameter.

== Help Summary ==
<p><strong><em>Assistant Submenu - Attachment List Table</em></strong></p>
<p><strong>Overview</strong></p>
<p>All the files you&#8217;ve uploaded are listed in the Media Library Assistant table, ordered by the Title field. You can change the sort order by clicking on one of the blue column names. You can change the default sort order on the Settings screen.</p>
<p>You can use the Screen Options tab to customize the display of this screen. You can choose any combination of the sixteen (16) columns available for display. You can also choose how many items appear on each page of the display.</p>
<p>You can narrow the list by file type/status using the text link filters at the top of the screen. You also can refine the list by month using the dropdown menu above the media table.</p>
<p>If you have selected &#8220;Attachment Categories&#8221; support, you can filter the list by selecting &#8220;All Categories&#8221;, &#8220;No Categories&#8221; or a specific category from the drop down list. If you select a category that has child categories beneath it, attachments in any of the child categories will also appear in the filtered list.</p>
<p><strong>NOTE:</strong> Month and category filters are &#8220;sticky&#8221;, i.e., they will persist as you resort the display or change the file type/status view.</p>
<p><strong>Featured/Inserted</strong></p>
<p>The &#8220;Featured in&#8221; and &#8220;Inserted in&#8221; columns are a powerful tool for managing your attachments. They show you where each attachment is used in a post or page as a &#8220;Featured Image&#8221; or as an embedded image or link.</p>
<p>You can also use the information in the &#8220;Title/Name&#8221; column to identify &#8220;Orphan&#8221; items that are not used in any post or page and items with a &#8220;Bad Parent&#8221;, i.e., a parent that does not exist.</p>
<p><strong>Taxonomy Support</strong></p>
<p>The &#8220;taxonomy&#8221; columns help you to group attachments by subject and keyword values. The columns list any categories and tags associated with the item. You can click on one of the displayed values to get a list of all items associated with that value.</p>
<p>The Media Library Assistant provides two pre-defined taxonomies, &#8220;Att. Categories&#8221; and &#8220;Att. Tags&#8221; which are enabled by default. You can add or remove support for any registered taxonomy on the Settings screen. The standard WordPress Categories and Tags as well as any custom taxonomies can be supported.</p>
<p>When you add support for a taxonomy it is visible on the main screen. If you want to hide the column simply use the Screen Options to uncheck the Show on screen box.</p>
<p>Supported taxonomies also appear as submenus below the Media menu at the left of the screen. You can edit the taxonomy terms by clicking these submenus. The taxonomy edit screens include an &#8220;Attachments&#8221; column which displays the number of attachment objects for each term. You can display a filtered list of the attachments by clicking on the number in this column.</p>
<p><strong>Bulk Actions</strong></p>
<p>The &#8220;Bulk Actions&#8221; dropdown list works with the check box column to let you make changes to many items at once. Click the check box in the column title row to select all items on the page, or click the check box in a row to select items individually.</p>
<p>Once you&#8217;ve selected the items you want, pick an action from the dropdown list and click Apply to perform the action on the selected items. The available actions will vary depending on the file type/status view you have picked.</p>
<p>If you have enabled Trash support (define MEDIA_TRASH in wp-config.php) you can use bulk actions to move items to and from the Trash or delete them permamently.</p>
<p>When using Bulk Edit, you can change the metadata (author, parent, taxonomy terms) for all selected attachments at once. To remove an attachment from the grouping, just click the x next to its name in the left column of the Bulk Edit area.</p>
<p>Bulk Edit support for taxonomy terms allows you to <strong>add, remove or completely replace</strong> terms for the selected attachments. Below each taxonomy edit box are three radio buttons that let you select the action you&#8217;d like to perform.</p>
<p>The taxonomies that appear in the Bulk Edit area can be a subset of the taxonomies supported on the single item edit screen. You can select which taxonomies appear by entering your choice(s) on the Media Libray Assistant Settings screen.</p>
<p><strong>Available Actions</strong></p>
<p>Hovering over a row reveals action links such as Edit, Quick Edit, Move to Trash and Delete Permanently. Clicking Edit displays a simple screen to edit that individual file&#8217;s metadata. Clicking Move to Trash will assign the file to the Trash pile but will not affect pages or posts to which it is attached. Clicking Delete Permanently will delete the file from the media library (as well as from any posts to which it is currently attached). Clicking Quick Edit displays an inline form to edit the file's metadata without leaving the menu screen.</p>
<p>The taxonomies that appear in the Quick Edit area can be a subset of the taxonomies supported on the single item edit screen. You can select which taxonomies appear by entering your choice(s) on the Media Libray Assistant Settings screen.</p>
<p><strong>Attaching Files</strong></p>
<p>If a media file has not been attached to any post, you will see (unattached) in the Attached To column. You can click on the Edit or Quick Edit action to attach the file by assigning a value to the Parent ID field.</p>
<p><strong><em>Single Item Edit Screen</em></strong></p>
<p><strong>Overview</strong></p>
<p>This screen allows you to view many of the fields associated with an attachment and to edit several of them. Fields that are read-only have a light gray background; fields that may be changes have a white background. Hints and helpful information appear below some fields.</p>
<p>Remember to click the &#8220;Update&#8221; button to save your work. You may instead click the &#8220;Cancel&#8221; button to discard any changes.</p>
<p><strong>Taxonomies</strong></p>
<p>If there are custom taxonomies, such as &#8220;Attachment Categories&#8221; or &#8220;Attachment Tags&#8221;, registered for attachments they will apppear in the right-hand column on this screen. You can add or remove terms from any of the taxonomies displayed. Changes will not be saved until you click the &#8220;Update&#8221; button for the attachment.</p>
<p><strong>Parent Info</strong></p>
<p>The &#8220;Parent Info&#8221; field displays the Post ID, type and title of the post or page to which the item is attached. It will display &#8220;0 (unattached)&#8221; if there is no parent post or page.</p>
<p>You can change the post or page to which the item is attached by changing the Post ID value and clicking the &#8220;Update&#8221; button. Changing the Post ID value to zero (0) will &#8220;unattach&#8221; the item.</p>
<p><strong><em>Edit Hierarchical Taxonomies (Categories)</em></strong></p>
<p><strong>Overview</strong></p>
<p>You can use <strong>categories</strong> to define sections of your site and group related attachments. The default is &#8220;none&#8221;, i.e., the attachment is not associated with any category.</p>
<p>What&#8217;s the difference between categories and tags? Normally, tags are ad-hoc keywords that identify important information about your attachment (names, subjects, etc) that may or may not apply to other attachments, while categories are pre-determined sections. If you think of your site like a book, the categories are like the Table of Contents and the tags are like the terms in the index.</p>
<p>You can change the display of this screen using the Screen Options tab to set how many items are displayed per screen and to display/hide columns in the table.</p>
<p><strong>Adding Categories</strong></p>
<p>When adding a new category on this screen, you&#8217;ll fill in the following fields:</p>
<ul>
<li><strong>Name</strong> - The name is how it appears on your site.</li>
<li><strong>Slug</strong> - The &#8220;slug&#8221; is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.</li>
<li><strong>Parent</strong> - Categories, unlike tags, can have a hierarchy. You might have a Landscape category, and under that have children categories for Mountains and Seashore. Totally optional. To create a subcategory, just choose another category from the Parent dropdown.</li>
<li><strong>Description</strong> - The description is not prominent by default; however, some themes may display it.</li>
</ul>
<p><strong>Attachments Column</strong></p>
<p>The &#8220;Attachments&#8221; colunm at the right of the table gives you the number of attachments associated with each category. You can click on the number to get a list of all the attachments with that category. The heading on the list page(s) will display the category value you&#8217;ve selected.</p>
<p><strong><em>Edit Flat Taxonomies (Tags)</em></strong></p>
<p><strong>Overview</strong></p>
<p>You can assign keywords to your attachments using <strong>tags</strong>. Unlike categories, tags have no hierarchy, meaning there&#8217;s no relationship from one tag to another.</p>
<p>What&#8217;s the difference between categories and tags? Normally, tags are ad-hoc keywords that identify important information about your attachment (names, subjects, etc.) that may or may not apply to other attachments, while categories are pre-determined sections. If you think of your site like a book, the categories are like the Table of Contents and the tags are like the terms in the index.</p>
<p>You can change the display of this screen using the Screen Options tab to set how many items are displayed per screen and to display/hide columns in the table.</p>
<p><strong>Adding Tags</strong></p>
<p>When adding a new tag on this screen, you&#8217;ll fill in the following fields:</p>
<ul>
<li><strong>Name</strong> - The name is how it appears on your site.</li>
<li><strong>Slug</strong> - The &#8220;slug&#8221; is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.</li>
<li><strong>Description</strong> - The description is not prominent by default; however, some themes may display it.</li>
</ul>
<p><strong>Attachments Column</strong></p>
<p>The &#8220;Attachments&#8221; colunm at the right of the table gives you the number of attachments associated with each tag. You can click on the number to get a list of all the attachments with that tag. The heading on the list page(s) will display the tag value you&#8217;ve selected.</p>

== Acknowledgements ==

I have used and learned much from the following books (among many):

* Professional WordPress; Design and Development, by Hal Stern, David Damstra and Brad Williams (Apr 5, 2010) ISBN-13: 978-0470560549
* Professional WordPress Plugin Development, by Brad Williams, Ozh Richard and Justin Tadlock (Mar 15, 2011) ISBN-13: 978-0470916223
* WordPress 3 Plugin Development Essentials, by Brian Bondari and Everett Griffiths (Mar 24, 2011) ISBN-13: 978-1849513524
* WordPress and Ajax, by Ronald Huereca (Jan 13, 2011) ISBN-13: 978-1451598650