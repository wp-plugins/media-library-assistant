=== Plugin Name ===
Contributors: dglingren
Donate link: http://fairtradejudaica.org/make-a-difference/donate/
Tags: attachment, attachments, documents, gallery, image, images, media, library, media library, media-tags, media tags, tags, media categories, categories, IPTC, EXIF, meta, metadata, photo, photos, photograph, photographs, photoblog, photo albums
Requires at least: 3.3
Tested up to: 3.5.1
Stable tag: 1.13
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Enhances the Media Library; powerful [mla_gallery], taxonomy support, IPTC/EXIF processing, bulk & quick edit actions and where-used reporting.

== Description ==

The Media Library Assistant provides several enhancements for managing the Media Library, including:

* The **`[mla_gallery]` shortcode**, used in a post, page or custom post type to add a gallery of images and/or other Media Library items (such as PDF documents). [MLA Gallery](http://wordpress.org/extend/plugins/media-library-assistant/other_notes/ "Complete Documentation") is a superset of the WordPress `[gallery]` shortcode; it is compatible with `[gallery]` and provides many enhancements. These include: 1) full query and display support for WordPress categories, tags, custom taxonomies and custom fields, 2) support for all post_mime_type values, not just images 3) media Library items need not be "attached" to the post, and 4) control over the styles, markup and content of each gallery using Style and Markup Templates.

* **Attachment metadata** such as file size, image dimensions and where-used issues can be assigned to WordPress custom fields. You can then use the custom fields in your `[mla_gallery]` display and you can add custom fields as sortable, searchable columns in the Media/Assistant submenu table.

* **IPTC** and **EXIF** metadata can be assigned to standard WordPress fields, taxonomy terms and custom fields. You can update all existing attachments from the Settings page IPTC/EXIF tab, groups of existing attachments with a Bulk Action or one existing attachment from the Edit Media/Edit Single Item screen. Display **IPTC** and **EXIF** metadata with `[mla_gallery]` custom templates.

* **Integrates with Photonic Gallery** (plugin), so you can add slideshows, thumbnail strips and special effects to your `[mla_gallery]` galleries.

* **Enhanced Search Media box**. Search can be extended to the name/slug, ALT text and caption fields. The connector between search terms can be "and" or "or". Search by attachment ID is supported.

* **Where-used reporting** shows which posts use a media item as the "featured image", an inserted image or link, an entry in a `[gallery]` and/or an entry in an `[mla_gallery]`.
* **Complete support for ALL taxonomies**, including the standard Categories and Tags, your custom taxonomies and the Assistant's pre-defined Att. Categories and Att. Tags. You can add taxonomy columns to the Assistant listing, filter on any taxonomy, assign terms and list the attachments for a term.
* An inline **"Bulk Edit"** area; update author, parent and custom fields, add, remove or replace taxonomy terms for several attachments at once
* An inline **"Quick Edit"** action for many common fields and for custom fields
* Displays more attachment information such as parent information, file URL and image metadata. Uses and enhances the new Edit Media screen for WordPress 3.5 and above.
* Allows you to edit the post_parent, the menu_order and to "unattach" items
* Provides additional view filters for mime types and taxonomies
* Provides many more listing columns (more than 20) to choose from

The Assistant is designed to work like the standard Media Library pages, so the learning curve is short and gentle. Contextual help is provided on every new screen to highlight new features.

This plugin was inspired by my work on the WordPress web site for our nonprofit, Fair Trade Judaica. If you find the Media Library Assistant plugin useful and would like to support a great cause, consider a [<strong>tax-deductible</strong> donation](http://fairtradejudaica.org/make-a-difference/donate/ "Support Our Work") to our work. Thank you!

== Installation ==

1. Upload `media-library-assistant` and its subfolders to your `/wp-content/plugins/` directory
1. Activate the plugin through the "Plugins" menu in WordPress
1. Visit the Settings page to customize category and tag support
1. Visit the Settings page Custom Fields and IPTC/EXIF tabs to map metadata to attachment fields
1. Visit the "Assistant" submenu in the Media admin section
1. Click the Screen Options link to customize the display
1. Use the enhanced Edit, Quick Edit and Bulk Edit pages to assign categories and tags
1. Use the `[mla_gallery]` shortcode to add galleries of images, documents and more to your posts and pages

== Frequently Asked Questions ==

= How can I sort the Media/Assistant submenu table on values such as File Size? =

You can add support for many attachment metadata values such as file size by visiting the Custom Fields tab on the Settings page. There you can define a rule that maps the data to a WordPress custom field and check the "MLA Column" box to make that field a sortable column in the Media/Assistant submenu table. You can also use the field in your `[mla_gallery]` shortcodes.

= How can I use Categories, Tags and custom taxonomies to select images for display in my posts and pages? =

The powerful `[mla_gallery]` shortcode supports almost all of the query flexibility provided by the WP_Query class. You can find [complete documentation](http://wordpress.org/extend/plugins/media-library-assistant/other_notes/ "Complete Documentation") in the Other Notes section.

= Can I use `[mla_gallery]` for attachments other than images? =

Yes! The `[mla_gallery]` shortcode supports all MIME types when you add the post_mime_type parameter to your query. You can build a gallery of your PDF documents, plain text files and other attachments. You can mix images and other MIME types in the same gallery, too; check out [the documentation](http://wordpress.org/extend/plugins/media-library-assistant/other_notes/ "Complete Documentation").

= Can I attach an image to more than one post or page? =

No; that's a structural limitation of the WordPress database. However, you can use Categories, Tags and custom taxonomies to organize your images and associate them with posts and pages in any way you like. The `[mla_gallery]` shortcode makes it easy.

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

= The Media/Assistant submenu seems sluggish; is there anything I can do to make it faster? =

Some of the MLA features such as where-used reporting and ALT Text sorting/searching require a lot of database procesing. If this is an issue for you, go to the Settings page and adjust the "Where-used database access tuning" settings. For any where-used category you can enable or disable processing. For the "Gallery in" and "MLA Gallery in" you can also choose to update the results on every page load or to cache the results for fifteen minutes between updates. The cache is also flushed automatically when posts, pages or attachments are inserted or updated.

= Are other language versions available? =

Not at this time; I don't have working knowledge of anything but English. If you'd like to volunteer to produce another version, I'll rework the code to internationalize it and work with you to localize it.

= What's in the "phpDocs" directory and do I need it? =

All of the MLA source code has been annotated with "DocBlocks", a special type of comment used by phpDocumentor to generate API documentation. If you'd like a deeper understanding of the code, click on "index.html" in the phpDocs dorectory and have a look. Note that these pages require JavaScript for much of their functionality.

== Screenshots ==

1. The Media/Assistant submenu table showing the available columns, including "Featured in", "Inserted in", "Att. Categories" and "Att. Tags"; also shows the Quick Edit area.
2. The Media/Assistant submenu table showing the Bulk Edit area with taxonomy Add, Remove and Replace options; also shows the tags suggestion popup.
3. A typical edit taxonomy page, showing the "Attachments" column.
4. The enhanced Edit page showing additional fields, categories and tags.
5. The Settings page General tab, where you can customize support of Att. Categories, Att. Tags and other taxonomies, where-used reporting and the default sort order.
6. The Settings page MLA Gallery tab, where you can add custom style and markup templates for `[mla_gallery]` shortcode output.
7. The Settings page IPTC &amp; EXIF Processing Options screen, where you can map image metadata to standard fields (e.g. caption), taxonomy terms and custom fields.
8. The Settings page Custom Field Processing Options screen, where you can map attachment metadata to custom fields for display in [mla_gallery] shortcodes and as sortable, searchable columns in the Media/Assistant submenu.

== Changelog ==

= 1.14 =
* New: In the `[mla_gallery]` shortcode, a new `mla_target` parameter allows you to specify the HTML `target` attribute in the gallery item links, e.g., `mla_target="_blank"` will open the items in a new window or tab.
* New: In the `[mla_gallery]` shortcode, a new `tax_operator` parameter allows you to specify "AND" or "NOT IN" operators in the simple `tax_name=term(s)` version of taxonomy queries. See the Settings/Media Library Assistant Documentation page for details.
* New: In the `[mla_gallery]` shortcode, `tax_query` corruption caused by the Visual mode of the post/page editor is now cleaned up before the query is submitted; Line breaks, HTML markup and escape sequences added by the Visual editor are removed.
* Fix: IPTC/EXIF values containing an arrray, e.g., "2#025 keywords", will be converted to a comma-separated string before assignment to Standard fields or Custom fields.
* Fix: Custom Field Mapping will always ignore rules with Data Source set to "-- None (select a value) --". 
* Fix: In the `[mla_gallery]` shortcode, the `orderby` parameter will override the explicit order in the `ids` parameter.
* Fix: In the `[mla_gallery]` shortcode, the `ids` and `include` parameters no longer require `post_parent=all` to match items not attached to the current post/page.
* Fix: The `[mla_gallery]' shortcode can now be called without a current post, e.g., from a PHP file that contains  `do_shortcode("[mla_gallery]");`.
* Fix: The value in the Attachments column in the edit taxonomy screen(s) is now correct. In previous versions this value was not correct if a term appeared in more than ten (10) attachments.
* Fix: The Attachments column in the edit taxonomy screen(s) is now updated in response to the WordPress "Quick Edit" action for taxonomy terms. In previous versions the Attachments value was not returned and the Posts/Media value was used instead.
* Fix: The Attachments column in the edit taxonomy screen(s) is now center-justified, following the standard set by the WordPress Posts/Media column. In previous versions it was left-justified.
* Fix: Corrected `vertical-align` attribute in `.gallery-caption` style of the default `mla_style` template.
* Fix: Better handling of minimum PHP and WordPress version violations; removed wp_die() calls.

= 1.13 =
* New: Any custom field can be added as a sortable, searchable (click on a value to filter the table display) column in the Media/Assistant submenu. Custom fields can also be added to the quick edit and bulk edit areas. Use the Settings/Media Library Assistant Custom Field tab to control all three uses.
* New: Access to EXIF data expanded to include the COMPUTED, THUMBNAIL and COMMENT arrays. Pseudo-values `ALL_EXIF` and `ALL_IPTC` added. Details in the [Other Notes section](http://wordpress.org/extend/plugins/media-library-assistant/other_notes/ "Click here, then scroll down") and the Settings/Media Library Assistant Documentation tab.
* New: For the `[mla_gallery]` shortcode, `mla_viewer=true` and related parameters can be coded to supply thumbnail images for non-image file types pdf, txt, doc, xls and ppt using the Google File Viewer.
* New: For the `[mla_gallery]` shortcode, `post_parent=none` or `post_parent=any` can be coded to restrict gallery output to unattached or attached items respectively. 
* New: For the `[mla_gallery]` shortcode, `mla_style=none` parameter can be coded to suppress the inline CSS styles added to gallery output. 
* Fix: Corrected occasional error in field-level markup substitution using the `exif` prefix.
* Fix: Corrected error in Custom Field Mapping of `_wp_attachment_metadata` during Media/Add New processing.

= 1.12 =
* One-off version for a private client.

= 1.11 =
* New: If the search box contains (only) a numeric value it is interpreted as a search by attachment ID. You can search for a numeric value in the text fields, e.g., title, by putting quotes around the value.
* Fix: The edit taxonomy screen "Attachments" column is now computed correctly when adding new terms, avoiding fatal errors and other odd results.
* Fix: Adopted new WordPress standard for JavaScript files, i.e., use ".min.js" for minified (production) files.

= 1.10 =
* New: Attachment metadata such as file size, dimensions and where-used status can be assigned to WordPress custom fields. These custom fields can be added to the Media/Assistant submenu table as sortable columns and displayed in `[mla_gallery]` shortcode output.
* New: Integrates with Photonic Gallery (plugin), so you can add slideshows, thumbnail strips and special effects to your `[mla_gallery]` galleries.
* Fix: Edit Media screen with appropriate message displayed after "Map ... Metadata" actions.
* Fix: SQL View (supporting ALT Text sorting/searching) now created only when required and dropped immediately after use. Avoids conflicts with database backup/restore utilities.
* Fix: "Map IPTC/EXIF Metadata" link moved from Image Metadata box to Save Metadata box.
* Fix: Field-level debug information removed from bulk edit messages.
* Fix: PHP Notice for NULL post metadata keys resolved.
* Fix: PHP Notice for images without "sizes" metadata array resolved.

= 1.00 =
* New: IPTC and EXIF metadata can be assigned to standard WordPress fields, taxonomy terms and custom fields. You can update all existing attachments from the Settings page IPTC/EXIF tab, groups of existing attachments with a Bulk Action or one existing attachment from the Edit Media/Edit Single Item screen.
* New: Where-used processing can be tuned or disabled on the Settings page, General tab.
* New: "Gallery in" and "MLA Gallery in" results are cached for fifteen minutes, avoiding repetitive database access. The cache is automatically flushed when pages, posts or attachments are inserted or updates, and can be manually flushed or disabled on the Settings page, General tab.
* New: Default `[mla_gallery]` style and markup templates can be specified on the Settings page.
* New: `[mla_gallery]` parameter "mla_float" allows control of gallery item "float" attribute.
* Fix: Field-level substitution parameters (custom fields, taxonomy terms, IPTC metadata and EXIF metadata) are now available for mla_link_text, mla_rollover_text and mla_caption parameters.
* Fix: Attachment/Parent relationships are reported consistently on the edit pages and the Media/Assistant submenu table.
* Fix: Defect in generating mla_debug messages has been corrected.
* Fix: Default "Order by" option now includes "None".
* Fix: For WordPress 3.5, Custom Field support for attachments enabled in admin_init action.
 
= 0.90 =
* New: Field-level IPTC and EXIF metadata support for `[mla_gallery]` display using custom markup templates.
* New: Field-level custom field and taxonomy term support for `[mla_gallery]` display using custom markup templates.
* New: Contextual help tabs added to WordPress 3.5+ Edit Media Screen, explaining MLA enhancements.
* Updated for WordPress version 3.5!

= 0.81 =
* New: Improved default Style template, `[mla_gallery]` parameters "mla_itemwidth" and "mla_margin" added to allow control of gallery item spacing.
* Fix: Quick edit support of WordPress standard Categories taxonomy fixed.

= 0.80 =
* New: MLA Gllery Style and Markup Templates, for control over CSS styles, HTML markup and data content of `[mla_gallery]` shortcode output.
* New: The `[mla_gallery]` "mla_link_text", "mla_rollover_text" and "mla_caption", parameters allow easy customization of gallery display.
* New: The `[mla_gallery]` "link" parameter now accepts size values, e.g., "medium", to generate a link to image sizes other than "full".
* New: The `[mla_gallery]` "mla_debug" parameter provides debugging information for query parameters.
* New: Quick Edit area now includes caption field.
* New: Settings page now divided into three tabbed subpages for easier access to settings and documentation.
* New: For WordPress 3.5, custom field support added to attachments and to the WordPress standard Edit Media Screen.
* New: For WordPress version 3.5, the WordPress standard Edit Media screen now includes Last Modified date, Parent Info, Menu Order, Image Metadata and all "where-used" information.
* New: For WordPress versions before 3.5, the MLA Edit Single Item screen now includes "Gallery in" and "MLA Gallery in"  information.
* Fix: Bulk edit now supports "No Change" option for Author.
* Fix: Bulk edit now supports changing Parent ID to "0" (unattached).
* Fix: Where-used reporting corrected for sites without month- and year-based folders.
* Fix: "No Categories" filtering fixed; used to return items with categories in some cases.

= 0.71 =
* Fix: Removed (!) Warning displays for empty Gallery in and MLA Gallery in column entries.

= 0.70 =
* New: "Gallery in" and "MLA Gallery in" columns show where the item appears in `[gallery]` and `[mla_gallery]` shortcode output.
* New: Post titles in the where-used columns contain a link to the Edit Post/Page screen.
* New: Title/Name column distinguishes between "BAD PARENT" (no where-used references to the item) and "INVALID PARENT" (does not exist).
* Fix: `[mla_gallery]` queries are modified to avoid a conflict with the Role Scoper plugin.
* Fix: Undefined taxonomies are now bypassed when defining table columns, avoiding (!) Notice displays after changing taxonomy support settings.

= 0.60 =
* New: Enhanced Search Media box. Search can be extended to the name/slug, ALT text and caption fields. The connector between search terms can be "and" or "or".
* New: The ID/Parent and Parent ID columns now contain a link to a parent-specific search of the Media Library.
* New: Menu Order added as sortable column, to Edit Single Item and to Quick Edit area.
* New: The Author column now contains a link to an author-specific search of the Media Library.
* New: The Attached to column now contains a link to the Edit Post/Page screen for the parent.
* New: For WordPress version 3.5, the WordPress standard Edit Media screen replaces the MLA Edit Single Item screen.
* Fix: HTML markup is no longer escaped in `[mla_gallery]` captions; caption processing now matches the WordPress `[gallery]` shortcode.
* Fix: For WordPress version 3.5, duplicate "edit taxonomy" submenu entries will not appear.

= 0.50 =
* New: `[mla_gallery]` shortcode, a superset of the `[gallery]` shortcode that provides many enhancements. These include taxonomy support and all post_mime_type values (not just images). Media Library items need not be "attached" to the post.
* New: `[mla_gallery]` shortcode documentation added to Settings page
* New: Donate button and link added to Settings page

= 0.41 =
* Fix: SQL View (supporting ALT Text sorting) now created for automatic plugin upgrades

= 0.40 =
* New: Bulk Edit area; update author or parent, add, remove or replace taxonomy terms for several attachments at once
* New: ALT Text is now a sortable column, and shows attachments with no ALT Text value
* New: Activate and deactivate hooks added to create and drop an SQL View supporting ALT Text sorting
* New: Revisions are excluded from the where-used columns; a settings option lets you include them if you wish
* Fix: Better validation/sanitization of data fields on input and display
* Fix: Database query validation/sanitization with wpdb->prepare()
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

= 1.14 =
New [mla_gallery] mla_target and tax_operator parameters, tax_query cleanup and ids/include fix. Attachments column fix. IPTC/EXIF and Custom Field mapping fixes. Three other fixes.

== Other Notes ==

In this section, scroll down to see:

* Acknowledgements
* MLA Gallery Shortcode Documentation
* Support for &ldquo;Photonic Gallery for Flickr, Picasa, SmugMug, 500px and Instagram&rdquo;
* MLA Gallery Style and Markup Template Documentation
* Custom Field Processing Options
* IPTC &amp; EXIF Processing Options

== Acknowledgements ==

I have used and learned much from the following books (among many):

* Professional WordPress; Design and Development, by Hal Stern, David Damstra and Brad Williams (Apr 5, 2010) ISBN-13: 978-0470560549
* Professional WordPress Plugin Development, by Brad Williams, Ozh Richard and Justin Tadlock (Mar 15, 2011) ISBN-13: 978-0470916223
* WordPress 3 Plugin Development Essentials, by Brian Bondari and Everett Griffiths (Mar 24, 2011) ISBN-13: 978-1849513524
* WordPress and Ajax, by Ronald Huereca (Jan 13, 2011) ISBN-13: 978-1451598650

== MLA Gallery Shortcode ==

The `[mla_gallery]` shortcode is used in a post, page or custom post type to add a gallery of images and/or other Media Library items (such as PDF documents). MLA Gallery is a superset of the `[gallery]` shortcode in the WordPress core; it is compatible with `[gallery]` and provides many enhancements. These include:

* Full support for WordPress categories, tags and custom taxonomies. You can select items with any of the taxonomy parameters documented in the WP_Query class.
* Support for all post_mime_type values, not just images.
* Media Library items need not be "attached" to the post. You can build a gallery with any combination of items in the Library using taxonomy terms, custom fields and more.
* Control over the styles, markup and content of each gallery using the Style and Markup Templates documented below.

All of the options/parameters documented for the `[gallery]` shortcode are supported by the `[mla_gallery]` shortcode; you can find them in the WordPress Codex. Most of the parameters documented for the WP_Query class are also supported; see the WordPress Codex. Because the `[mla_gallery]` shortcode is designed to work with Media Library items, there are some parameter differences and extensions; these are documented below.

<h4>Gallery Display Style</h4>

Two `[mla_gallery]` parameters provide a way to apply custom style and markup templates to your `[mla_gallery]` display. These parameters replace the default style and/or markup templates with templates you define on the "MLA Gallery" tab of the Settings page. On the "MLA Gallery" tab you can also select one of your custom templates to replace the built-in default template for all `[mla_gallery`] shortcodes the do not contain one of these parameters.

* `mla_style`: replaces the default style template for an `[mla_gallery]` shortcode

* `mla_markup`: replaces the default markup template for an `[mla_gallery]` shortcode

Three `[mla_gallery]` parameters provide control over the placement, size and spacing of gallery items without requiring the use of custom Style templates.

* `mla_float`: specifies the float attribute of the ".gallery-item" style. Acceptable values are "left", "none", "right"; the default value is "right" if current locale is RTL, "left" on LTR (left-to-right inline flow, e.g., English).

* `mla_margin`: specifies the margin attribute (in percent) of the ".gallery-item" style. The default value is "1.5" percent.

* `mla_itemwidth`: specifies the width attribute (in percent) of the ".gallery-item" style. The default value is calculated by subtracting twice the margin from 100%, then dividing by the number of gallery columns. For example, the default value is "32", or (100 - (2 * 1.5)) / 3.

These parameters are only important if the gallery thumbnails are too large to fit within the width of the page on which they appear. For example, if you code `[mla_gallery size=full]`, the browser will automatically scale down large images to fit within the width attribute (in percent) of the ".gallery-item" style. The default 1.5% margin will ensure that the images do not overlap; you can increase it to add more space between the gallery items. You can also reduce the itemwidth parameter to increase the left and right space between the items.

<h4>Gallery Display Content</h4>

Four `[mla_gallery]` parameters provide an easy way to control the contents of gallery items without requiring the use of custom Markup templates.  

* `mla_link_text`: replaces the thumbnail image or attachment title text displayed for each gallery item.

* `mla_rollover_text`: replaces the attachment title text displayed when the mouse rolls or hovers over the gallery thumbnail.

* `mla_caption`: replaces the attachment caption text displayed beneath the thumbnail of each gallery item.

* `mla_target`: adds an HTML "target" attribute to the hyperlink for each gallery item; see below.

The first three parameters support the Markup and Attachment-specific substitution arguments defined for Markup Templates. For example, if you code `mla_rollover_text='{+date+} : {+description+}'`, the rollover text will contain the upload date, a colon, and the full description of each gallery item. Simply add "{+" before the substitution parameter name and add "+}" after the name. Note that the enclosing delimiters are different than those used in the templates, since the shortcode parser reserves square brackets ("[" and "]") for its own use.

The "mla_target" parameter accepts any value and adds an HTML "target" attribute to the hyperlink with that value. For example, if you code `mla_target="_blank"` the item will open in a new window or tab. You can also use "_self", "_parent", "_top" or the "<em>framename</em>" of a named frame.

<h4>Google File Viewer Support</h4>

Four `[mla_gallery]` parameters provide an easy way to generate thumbnail images for the non-image file types.

* `mla_viewer`: must be "true" to enable thumbnail generation

* `mla_viewer_extensions`: a comma-delimited list of the file extensions to be processed; the default is "pdf,txt,doc,xls,ppt" (do not include the dot (".") preceding the file extension). You may add or remove extensions, but these are known to generate reasonable thumbnail images. Sadly, the newer "docx,xlsx,pptx" extensions do not work well with the Google File Viewer.

* `mla_viewer_page`: the page number (default "1") to be used for the thumbnail image. If you specify a value greater than the number of pages in the file, no image is generated.

* `mla_viewer_width`: the width in pixels (default "150") of the generated thumbnail image. The height will be set automatically and cannot be specified.

When this feature is active, gallery items for which WordPress can generate a thumbnail image are not altered. If WordPress generation fails, the gallery thumbnail is replaced by an "img" html tag whose "src" attribute contains a url reference to the Google File Viewer. The Google File Viewer arguments include the url of the source file, the page number and the width. Note that the source file must be Internet accessible; files behind firewalls and on local servers will not generate a thumbnail image.

<h4>Order, Orderby</h4>

To order the gallery randomly, use "orderby=rand". To suppress gallery ordering you can use "orderby=none" or "order=rand".

The Orderby parameter specifies which database field is used to sort the gallery. You can order the gallery by any of the values documented for the WP_Query class reference in the Codex; you are NOT restricted to the values documented for the `[gallery]` shortcode.

<h4>Size</h4>

The Size parameter specifies the image size to use for the thumbnail display. Valid values include "thumbnail", "medium", "large", "full" and any additional image size that was registered with add_image_size(). The default value is "thumbnail". You can use "none" or "" to suppress thumbnail display and substitute the item title string for the image/icon.

The `[mla_gallery]` shortcode supports an additional Size value, "icon", which shows a 60x60 pixel thumbnail for image items and an appropriate icon for non-image items such as PDF or text files.

<h4>Link</h4>

The Link parameter specifies the target for the link from the gallery to the attachment. The default value, "permalink", links to the attachment's media page. The "file" and "full" values link directly to the attachment file.

For image attachments you can also specify the size of the image file you want to link to. Valid values include "thumbnail", "medium", "large" and any additional image size that was registered with add_image_size(). If the specified size is not available or if the attachment is not an image, the link will go directly to the attachment file.

<h4>Include, Exclude</h4>

You can use "post_parent=all" to include or exclude attachments regardless of which post or page they are attached to. You can use "post_mime_type=all" to include or exclude attachments of all MIME types, not just images.

<h4>Post ID, "ids", Post Parent</h4>

The "id" parameter lets you specify a post ID for your query. If the "id" parameter is not specified, the `[mla_gallery]` behavior differs from the `[gallery]` behavior. If your query uses taxonomy or custom field parameters, "author", "author_name" or "s" (search term), then the query will NOT be restricted to items attached to the current post. This lets you build a gallery with any combination of Media Library items that match the parameters.

For WordPress 3.5 and later, the "ids" parameter lets you specify a list of Post IDs. The attachment(s) matching the "ids" values will be displayed in the order specified by the list.

You can use the "post_parent" to override the default behavior. If you set "post_parent" to a specific post ID, only the items attached to that post are displayed. If you set "post_parent" to "current", only the items attached to the current post are displayed. If you set "post_parent" to "all", the query will not have a post ID or post_parent parameter.

For example, `[mla_gallery tag="artisan"]` will display all images having the specified tag value, regardless of which post (if any) they are attached to. If you use `[mla_gallery tag="artisan" post_parent="current"]` it will display images having the specified tag value only if they are attached to the current post.

<h4>Author, Author Name</h4>

You can query by author's id or the "user_nicename" value (not the "display_name" value). Multiple author ID values are allowed, but only one author name value can be entered.

<h4>Category Parameters</h4>

The Category parameters search in the WordPress core "Categories" taxonomy. Remember to use "post_parent=current" if you want to restrict your query to items attached to the current post.

<h4>Tag Parameters</h4>

The Tag parameters search in the WordPress core "Tags" taxonomy. Remember to use "post_parent=current" if you want to restrict your query to items attached to the current post.

Note that the "tag_id" parameter requires exactly one tag ID; multiple IDs are not allowed. You can use the "tag__in" parameter to query for multiple values.

<h4>Taxonomy Parameters, "tax_operator"</h4>

The `[mla_gallery]` shortcode supports the simple "{tax} (string)" values (deprecated as of WordPress version 3.1) as well as the more powerful "tax_query" value. 

For simple queries, enter the taxonomy name and the term(s) that must be matched, e.g.:

* `[mla_gallery attachment_category='separate-category,another-category']`

Note that you must use the name/slug strings for taxonomy and terms, not the "title" strings. If you are using the "Att. Tag" taxonomy built in to MLA then your shortcode should be something like:

* `[mla_gallery attachment_tag=artisan post_parent=all]`
 
In this example, "attachment_tag" is the WordPress taxonomy name/slug for the taxonomy. If you're using "Att. Category", the slug would be "attachment_category".
 
The default behavior of the simple taxonomy query will match any of the terms in the list. MLA enhances the simple taxonomy query form by providing an additional parameter, "tax_operator", which can be "IN", "NOT IN" or "AND". If you specify a "tax_operator", MLA will convert your query to the more powerful "tax_query" form, searching on the "slug" field and using the operator you specify. For example, a query for two terms in which <strong>both</strong> terms must match would be coded as:

* `[mla_gallery attachment_category='separate-category,another-category' tax_operator=AND]`

More complex queries can be specified by using "tax_query", e.g.:

* `[mla_gallery tax_query="array(array('taxonomy' => 'attachment_tag','field' => 'slug','terms' => 'artisan'))"]`
* `[mla_gallery tax_query="array(array('taxonomy' => 'attachment_category','field' => 'id','terms' => array(11, 12)))" post_parent=current post_mime_type='']`

The first example is equivalent to the simple query "attachment_tag=artisan". The second example matches items of all MIME types, attached to the current post, having an attachment_category ID of 11 or 12.

When embedding the shortcode in the body of a post, be very careful when coding the tax_query; it must be a valid PHP array specification. In particular, code the query on one line; splitting it across lines can insert HTML &#8249;br&#8250; tags and corrupt your query. 

Remember to use "post_parent=current" if you want to restrict your query to items attached to the current post.

<h4>Post Type, Post Status and Post MIME Type</h4>

For compatibility with the WordPress `[gallery]` shortcode, these parameters default to "post_type=attachment", "post_status=inherit" and "post_mime_type=image". You can override the defaults to, for example, display items in the trash ("post_status=trash") or PDF documents ("post_mime_type=application/pdf") or all MIME types ("post_mime_type=all"). I'm not sure why you'd want to override "post_type", but you are welcome to experiment and let me know what you find.

<h4>Pagination Parameters</h4>

The `[mla_gallery]` shortcode supplies "nopaging=true" as a default parameter. If you are working with a template that supports pagination you can replace this with specific values for "posts_per_page", "posts_per_archive_page", "paged" and/or "offset" . You can also pass "paged=current" to suppress the "nopaging" default; "current" will be replaced by the appropriate value (get_query_var('paged')).

<h4>Time Parameters</h4>

Support for time parameters is not included in the current version. I may add it later - let me know if you need it.

<h4>Custom Field Parameters</h4>

The `[mla_gallery]` shortcode supports the simple custom field parameters as well as the more powerful "meta_query" parameters made available as of WordPress 3.1.

When embedding the shortcode in the body of a post, be very careful when coding the meta_query; it must be a valid PHP array specification. In particular, code the query on one line; splitting it across lines can insert HTML <br> tags and corrupt your query.

Remember to use "post_parent=current" if you want to restrict your query to items attached to the current post.

<h4>Search Keywords</h4>

The search parameter ("s=keyword") will perform a keyword search. A cursory inspection of the code in /wp-includes/query.php reveals that the search includes the "post_title" and "post_content" (Description) fields but not the "post_excerpt" (Caption) field. An SQL "LIKE" clause is composed and added to the search criteria. I haven't done much testing of this parameter.

<h4>Debugging Output</h4>

The "mla_debug" parameter controls the display of information about the query parameters and SQL statements used to retrieve gallery items. If you code `mla_debug=true` you will see a lot of information added to the post or page containing the gallery. Of course, this parameter should <strong>only</strong> be used in a development/debugging environment; it's quite ugly.

== Support for &ldquo;Photonic Gallery for Flickr, Picasa, SmugMug, 500px and Instagram&rdquo; ==

The <a href="http://wordpress.org/extend/plugins/photonic/" title="Photonic Gallery plugin directory page" target="_blank">Photonic Gallery for Flickr, Picasa, SmugMug, 500px and Instagram</a> plugin adds several new parameters to the `[mla_gallery]` shortcode to enhance your galleries. All you have to do is install the plugin, then add a "style=" parameter to your `[mla_gallery]` shortcode to use the Photonic styling and markup in place of the native `[mla_gallery]` style and markup templates. 

You can use the "Photonic" screen of the Insert Media dialog to build the display portion of your shortcode parameters. After you click "Insert into post", change the shortcode name from "gallery" to "mla_gallery" and add the query parameters you need to select the attachments for the gallery. The `[mla_gallery]` code will compile the list of attachments for your gallery, then hand control over to Photonic to format the results. 

== MLA Gallery Style and Markup Templates ==

The Style and Markup templates give you great flexibility for the content and format of each `[mla_gallery]`. You can define as many templates as you need.

Style templates provide gallery-specific CSS inline styles. Markup templates provide the HTML markup for 1) the beginning of the gallery, 2) the beginning of each row, 3) each gallery item, 4) the end of each row and 5) the end of the gallery. The attachment-specific markup parameters let you choose among most of the attachment fields, not just the caption.

The MLA Gallery tab on the Settings page lets you add, change and delete custom templates. The default tempates are also displayed on this tab for easy reference.

In a template, substitution parameters are surrounded by opening ('[+') and closing ('+]') tags to separate them from the template text; see the default templates for many examples.

<h4>Substitution parameters for style templates</h4>

A complete list of the <strong>13 style substitution parameters</strong> is on the plugin's Settings page.

<h4>Substitution parameters for markup templates</h4>

A complete list of the <strong>15 markup substitution parameters</strong> is on the plugin's Settings page.

<h4>Attachment-specific substitution parameters for markup templates</h4>

A complete list of the <strong>35 attachment-specific substitution parameters</strong> is on the plugin's Settings page.

<h3>Field-level Markup Substitution Parameters</h3>

Field-level substitution parameters let you access custom fields, taxonomy terms, IPTC metadata and EXIF metadata for display in an MLA gallery. For these parameters, the value you code within the surrounding the ('[+') and ('+]') delimiters has three parts; the prefix, the field name and the optional ",single" indicator.

The <strong>prefix</strong> defines which type of field-level data you are accessing. It must immediately follow the opening ('[+') delimiter and end with a colon (':'). There can be no spaces in this part of the parameter.

The <strong>field name</strong> defines which field-level data element you are accessing. It must immediately follow the colon (':'). There can be no spaces between the colon and the field name. Spaces are allowed within the field name to accomodate custom field names that contain them. 

The optional <strong>",single" indicator</strong> defines how to handle fields with multiple values. It must immediately follow the field name and end with the closing delimiter ('+]'). There can be no spaces in this part of the parameter. If this part of the parameter is present, only the first value of the field will be returned. Use this indicator to limit the data returned for a custom field, taxonomy or metadata field that can have many values.

There are four prefix values for field-level data. Prefix values must be coded as shown; all lowercase letters.

* `custom`: WordPress custom fields, which you can define and populate on the Edit Media screen. The field name, or key, can contain spaces and some punctuation characters. You <strong>cannot use the plus sign ('+')</strong> in a field name you want to use with `[mla_gallery]`. Custom field names are case-sensitive; "client" and "Client" are not the same.
* `terms`: WordPress Category, tag or custom taxonomy terms. For this category, you code the name of the taxonomy as the field name. The term(s) associated with the attachment will be displayed in the `[mla_gallery]`. Note that you must use the name/slug string for taxonomy, not the "title" string. For example, use "attachment-category" or "attachment-tag", not "Att. Category" or "Attachment Category".

* `iptc`: The IPTC (International Press Telecommunications Council) metadata, if any, embedded in the image file. For this category, you can code any of the IPTC DataSet tag and field identifiers, e.g., "2#025" for the Keywords field. You can also use the "friendly name" MLA defines for most of the IPTC fields; see the table of identifiers and friendly names in the MLA documentation. You can find more information in the <a href="http://www.iptc.org/std/IIM/4.1/specification/IIMV4.1.pdf" title="IPTC-NAA Information Interchange Model Version No. 4.1 specification" target="_blank">IPTC-NAA Information Interchange Model Version No. 4.1 specification</a>.

* `exif`: The EXIF (EXchangeable Image File) metadata, if any, embedded in a JPEG DCT or TIFF Rev 6.0 image file. 
 Though the specification is not currently maintained by any industry or standards organization, almost all camera manufacturers use it. It is also supported by many image editing programs such as Adobe PhotoShop.
 For this category, you can code any of the field names embedded in the image by the camera or editing software. The is no official list of standard field names, so you just have to know the names your camera and software use; field names are case-sensitive. You can find more information in the <a href="http://en.wikipedia.org/wiki/Exchangeable_image_file_format" title="IPTC-NAA Information Interchange Model Version No. 4.1 specification" target="_blank">Exchangeable image file format</a> article on Wikipedia. You can find External Links to EXIF standards and tag listings at the end of the Wikipedia article.
		<br />&nbsp;<br />
		MLA uses a standard PHP function, <a href="http://php.net/manual/en/function.exif-read-data.php" title="PHP Manual page for exif_read_data" target="_blank">exif_read_data</a>, to extract EXIF data from images. The function returns three arrays in addition to the raw EXIF data; COMPUTED, THUMBNAIL and COMMENT. You can access the array elements by prefacing the element you want with the array name. For example, the user comment text is available as "COMPUTED.UserComment" and "COMPUTED.UserCommentEncoding". You can also get "COMPUTED.Copyright" and its two parts (if present), "COMPUTED.Copyright.Photographer" and "COMPUTED.Copyright.Editor". The THUMBNAIL and COMMENT arrays work in a similar fashion.
		<br />&nbsp;<br />
		Two special exif "pseudo-values" are available; <strong>ALL_IPTC</strong> and <strong>ALL_EXIF</strong>. These return a string representation of all IPTC or EXIF data respectively. You can use these pseudo-values to examine the metadata in an image, find field names and see what values are embedded in the image.
 
<h3>A Table-based Template Example</h3>
<p>
Here's a small example that shows a gallery using table markup. The Item markup section shows how to use the "terms", "custom", "iptc" and "exif" substitution parameters.

</p>
<h4>Style Template</h4>

	<style type='text/css'>
		#[+selector+] {
			margin: auto;
		}
		#[+selector+] .gallery-row {
			float: [+float+];
			margin-top: 10px;
			border-top: 1px solid #ddd;
			text-align: center;
			width: [+itemwidth+]%;
		}
		#[+selector+] .gallery-row td.gallery-icon {
			width: 60;
			height: 60;
			vertical-align: top;
		}
		#[+selector+] .gallery-row .gallery-icon img {
			border: 2px solid #cfcfcf;
		}
		#[+selector+] .gallery-caption {
			margin-left: 0;
			vertical-align: top;
		}
	</style>

<h4>Markup Template</h4>
<h5>Open</h5>

	<table id='[+selector+]' class='gallery galleryid-[+id+]<br />gallery-columns-[+columns+] gallery-size-[+size_class+]'>

<h5>Row Open</h5>

	<tr class='gallery-row'>

<h5>Item</h5>

	<td class='gallery-icon'>
		[+link+]
	</td>
	<td class='wp-caption-text gallery-caption'>
		<strong>[+title+]</strong><br />
		[+description+]<br />
		[+date+]<br />
		[+custom:client,single+]<br />
		[+terms:category+]<br />
		[+iptc:caption-or-abstract+]<br />
		[+iptc:2#025,single+]<br />
		[+exif:Artist+]
	</td>

<h5>Row Close</h5>

	</tr>
	
<h5>Close</h5>

	</table>

==Custom Field Processing Options==

On the Custom Fields tab of the Settings screen you can define the rules for mapping several types of file and image metadata to WordPress custom fields. Custom field mapping can be applied automatically when an attachment is added to the Media Library. You can refresh the mapping for <strong><em>ALL</em></strong> attachments using the command buttons on the screen. You can selectively apply the mapping in the bulk edit area of the Media/Assistant submenu table and/or on the Edit Media screen for a single attachment.

This is a powerful tool, but it comes at the price of additional database storage space processing time to maintain and retrieve the data. <strong><em>Think carefully about your needs before you use this tool.</em></strong> You can disable or delete any rules you create, so you might want to set up some rules for a special project or analysis of your library and then discard them when you're done. That said, the advantages of mapping metadata to custom fields are:

* You can add the data to an [mla_gallery] with a field-level markup substitution parameter. For example, add the image dimensions or a list of all the intermediate sizes available for the image.

* You can add the data as a sortable column to the Media/Assistant submenu table. For example, you can find all the "orphans" in your library by adding "reference_issues" and then sorting by that column.

Most of the data elements are static, i.e., they do not change after the attachment is added to the Media Library. The parent/reference information (parent_type, parent_name, parent_issues, reference_issues) is dynamic; it will change as you define galleries, insert images in posts, define featured images, etc. Because of the database processing required to update this information, <strong><em>parent and reference data are NOT automatically refreshed</em></strong>. If you use these elements, you must manually refresh them with the "map data" buttons on the Settings screen, the bulk edit area or the Edit Media screen.

Several of the data elements are sourced from the WordPress "image_meta" array. The credit, caption, copyright and title elements are taken from the IPTC/EXIF metadata (if any), but they go through a number of filtering rules that are not easy to replicate with the MLA IPTC/EXIF processing rules. You may find these "image_meta" elements more useful than the raw IPTC/EXIF metadata.

<h4>Data sources for custom field mapping</h4>

<strong>NOTE:</strong> Sorting by custom fields in the Media/Assistant submenu is by string values. For numeric data this can cause odd-looking results, e.g., dimensions of "1200x768" will sort before "640x480". The "file_size", "pixels", "width" and "height" data sources are converted to srtings and padded on the left with spaces if you use the "commas" format. This padding makes them sort more sensibly.

A complete list of the <strong>32 data source elements</strong> is on the plugin's Settings page.

==IPTC &amp; EXIF Processing Options==

Some image file formats such as JPEG DCT or TIFF Rev 6.0 support the addition of data about the image, or <em>metadata</em>, in the image file. Many popular image processing programs such as Adobe PhotoShop allow you to populate metadata fields with information such as a copyright notice, caption, the image author and keywords that categorize the image in a larger collection. WordPress uses some of this information to populate the Title, Slug and Description fields when you add an image to the Media Library.

The Media Library Assistant has powerful tools for copying image metadata to:

* the WordPress standard fields, e.g., the Caption
* taxonomy terms, e.g., in categories, tags or custom taxonomies
* WordPress custom fields

You can define the rules for mapping metadata on the "IPTC/EXIF" tab of the Settings page. You can choose to automatically apply the rules when new media are added to the Library (or not). You can click the "Map IPTC/EXIF metadata" button on the Edit Media/Edit Single Item screen or in the bulk edit area to selectivelly apply the rules to one or more images. You can click the "Map All Attachments Now" to apply the rules to <strong>all of the images in your library</strong> at one time.

<h4>Mapping tables</h4>

The three mapping tables on the IPTC/EXIF tab have the following columns:

* `Field Title`: The standard field title, taxonomy name or custom field name. In the Custom Field table you can define a new field by entering its name in the blank box at the bottom of the list; the value will be saved when you click "Save Changes" at the bottom of the screen.

* `IPTC Value`: The IPTC (International Press Telecommunications Council) metadata, if any, embedded in the image file. For this category, you can select any of the IPTC DataSet tag and field identifiers, e.g., "2#025" for the Keywords field. The dropdown list has the identifier and the "friendly name" MLA defines for most of the IPTC fields; see the table of identifiers and friendly names in the table below. You can find more information in the <a href="http://www.iptc.org/std/IIM/4.1/specification/IIMV4.1.pdf" title="IPTC-NAA Information Interchange Model Version No. 4.1 specification" target="_blank">IPTC-NAA Information Interchange Model Version No. 4.1 specification</a>.

* `EXIF Value`: The EXIF (EXchangeable Image File) metadata, if any, embedded in a JPEG DCT or TIFF Rev 6.0 image file. Though the specification is not currently maintained by any industry or standards organization, almost all camera manufacturers use it. For this category, you can code any of the field names embedded in the image by the camera or editing software. The is no official list of standard field names, so you just have to know the names your camera and software use; field names are case-sensitive. You can find more information in the <a href="http://en.wikipedia.org/wiki/Exchangeable_image_file_format" title="IPTC-NAA Information Interchange Model Version No. 4.1 specification" target="_blank">Exchangeable image file format</a> article on Wikipedia. You can find External Links to EXIF standards and tag listings at the end of the Wikipedia article.
		<br />&nbsp;<br />
		MLA uses a standard PHP function, <a href="http://php.net/manual/en/function.exif-read-data.php" title="PHP Manual page for exif_read_data" target="_blank">exif_read_data</a>, to extract EXIF data from images. The function returns three arrays in addition to the raw EXIF data; COMPUTED, THUMBNAIL and COMMENT. You can access the array elements by prefacing the element you want with the array name. For example, the user comment text is available as "COMPUTED.UserComment" and "COMPUTED.UserCommentEncoding". You can also get "COMPUTED.Copyright" and its two parts (if present), "COMPUTED.Copyright.Photographer" and "COMPUTED.Copyright.Editor". The THUMBNAIL and COMMENT arrays work in a similar fashion.
		<br />&nbsp;<br />
		Two special exif "pseudo-values" are available; <strong>ALL_IPTC</strong> and <strong>ALL_EXIF</strong>. These return a string representation of all IPTC or EXIF data respectively. You can use these pseudo-values to examine the metadata in an image, find field names and see what values are embedded in the image.

* `Priority`:  If both the IPTC Value and the EXIF Value are non-blank for a particular image, you can select which of the values will be used for the mapping.

* `Existing Text`: Images already in the Media Library will have non-blank values in many fields and may have existing terms in a taxonomy. You can select "Keep" to retain these values or "Replace" to always map a metadata value into the field. For a taxonomy, "Keep" will retain any terms already assigned to the item and "Replace" will delete any existing terms before assigning metadata values as terms.

* `Parent`: For hierarchical taxonomies such as Categories you can select one of the existing terms in the taxonomy as the parent term for any terms you are mapping from metadata values. For example, you could define "IPTC Keywords" as a parent and then assign all of the 2#025 values under that parent term.

<h4>Map All Attachments Now</h4>

To the right of each table heading is a "Map All Attachments Now" button. When you click one of these buttons, the mapping rules in that table are applied to <strong>all of the images in the Media Library.</strong> This is a great way to bring your media items up to date, but it is <strong>not reversible</strong>, so think carefully before you click!
Each button applies the rules in just one category, so you can update taxonomy terms without disturbing standard or custom field values.

These buttons <strong>do not</strong> save any rules changes you've made, so you can make a temporary rule change and process your attachments without disturbing the standing rules.

<h4>Other mapping techniques</h4>

There are two other ways you can perform metadata mapping to one or more existing Media Library images:

* `Single Item Edit/Edit Media screen`: For WordPress 3.5 and later, you can click the "Map IPTC/EXIF metadata" link in the "Image Metadata" postbox to apply the standing mapping rules to a single attachment.  For WordPress 3.4.x and earlier, you can click the "Map IPTC/EXIF metadata" button on the Single Item Edit screen to apply the standing mapping rules.

* `Bulk Action edit area`: To perform mapping for a group of attachments you can use the Bulk Action facility on the main Assistant screen. Check the attachments you want to map, select "edit" from the Bulk Actions dropdown list and click "Apply". The bulk edit area will open with a list of the checked attachments in the left-hand column. You can click the "Map IPTC/EXIF metadata" button in the lower left corner of the area to apply the standing mapping rules to the attachments in the list.