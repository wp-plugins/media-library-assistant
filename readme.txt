=== Media Library Assistant ===
Contributors: dglingren
Donate link: http://fairtradejudaica.org/make-a-difference/donate/
Tags: attachment, attachments, documents, gallery, image, images, media, library, media library, tag cloud, media-tags, media tags, tags, media categories, categories, IPTC, EXIF, GPS, PDF, meta, metadata, photo, photos, photograph, photographs, photoblog, photo albums, lightroom, photoshop, MIME, mime-type, icon, upload, file extensions
Requires at least: 3.5.0
Tested up to: 4.2
Stable tag: 2.02
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Enhances the Media Library; powerful [mla_gallery], taxonomy support, IPTC/EXIF/PDF processing, bulk/quick edit actions and where-used reporting.

== Description ==

The Media Library Assistant provides several enhancements for managing the Media Library, including:

* The **`[mla_gallery]` shortcode**, used in a post, page or custom post type to add a gallery of images and/or other Media Library items (such as PDF documents). MLA Gallery is a superset of the WordPress `[gallery]` shortcode; it is compatible with `[gallery]` and provides many enhancements. These include: 1) full query and display support for WordPress categories, tags, custom taxonomies and custom fields, 2) support for all post_mime_type values, not just images 3) media Library items need not be "attached" to the post, and 4) control over the styles, markup and content of each gallery using Style and Markup Templates. **Twenty-eight hooks** provided for complete gallery customization from your theme or plugin code.

* The **`[mla_tag_cloud]` shortcode**, used in a post, page, custom post type or widget to display the "most used" terms in your Media Library where the size of each term is determined by how many times that particular term has been assigned to Media Library items. **Twenty-five hooks** provided for complete cloud customization from your theme or plugin code.

* Powerful **Content Templates**, which let you compose a value from multiple data sources, mix literal text with data values, test for empty values and choose among two or more alternatives or suppress output entirely.

* **Attachment metadata** such as file size, image dimensions and where-used information can be assigned to WordPress custom fields. You can then use the custom fields in your `[mla_gallery]` display and you can add custom fields as sortable, searchable columns in the Media/Assistant submenu table. You can also **modify the WordPress `_wp_attachment_metadata` contents** to suit your needs.

* **IPTC**, **EXIF (including GPS)** and **PDF** metadata can be assigned to standard WordPress fields, taxonomy terms and custom fields. You can update all existing attachments from the Settings page IPTC/EXIF tab, groups of existing attachments with a Bulk Action or one existing attachment from the Edit Media/Edit Single Item screen. Display **IPTC**, **EXIF** and **PDF** metadata with `[mla_gallery]` custom templates. **Twelve hooks** provided for complete mapping customization from your theme or plugin code.

* Complete control over **Post MIME Types, File Upload extensions/MIME Types and file type icon images**. Fifty four (54) additional upload types, 112 file type icon images and a searchable list of over 1,500 file extension/MIME type associations.

* **Integrates with Photonic Gallery, Jetpack and other plugins**, so you can add slideshows, thumbnail strips and special effects to your `[mla_gallery]` galleries.

* **Enhanced Search Media box**. Search can be extended to the name/slug, ALT text and caption fields. The connector between search terms can be "and" or "or". Search by attachment ID or Parent ID is supported, and you can search on keywords in the taxonomy terms assigned to Media Library items. Works in the Media Manager Modal Window, too.

* **Where-used reporting** shows which posts use a media item as the "featured image", an inserted image or link, an entry in a `[gallery]` and/or an entry in an `[mla_gallery]`.

* **Complete support for ALL taxonomies**, including the standard Categories and Tags, your custom taxonomies and the Assistant's pre-defined Att. Categories and Att. Tags. You can add taxonomy columns to the Assistant listing, filter on any taxonomy, assign terms and list the attachments for a term.

* Taxonomy and custom field support in the ATTACHMENT DETAILS pane of the Media Manager Modal Window.

* An inline **"Bulk Edit"** area; update author, parent and custom fields, add, remove or replace taxonomy terms for several attachments at once. Works on the Media/Add New screen as well.

* An inline **"Quick Edit"** action for many common fields and for custom fields

* Displays more attachment information such as parent information, file URL and image metadata.

* Allows you to edit the post_parent, the menu_order and to "unattach" items

* Provides additional view filters for MIME types and taxonomies

* Provides many more listing columns (more than 20) to choose from

The Assistant is designed to work like the standard Media Library pages, so the learning curve is short and gentle. Contextual help is provided on every new screen to highlight new features.

This plugin was inspired by my work on the WordPress web site for our nonprofit, Fair Trade Judaica. If you find the Media Library Assistant plugin useful and would like to support a great cause, consider a [<strong>tax-deductible</strong> donation](http://fairtradejudaica.org/make-a-difference/donate/ "Support Our Work") to our work. Thank you!

== Installation ==

1. Upload `media-library-assistant` and its subfolders to your `/wp-content/plugins/` directory, **OR** Visit the Plugins/Add New page and search for "Media Library Assistant"; click "Install Now" to upload it

1. Activate the plugin through the "Plugins" menu in WordPress

1. Visit the Settings/Media Library Assistant page to customize taxonomy (e.g., category and tag) support

1. Visit the Settings/Media Library Assistant Custom Fields and IPTC/EXIF tabs to map metadata to attachment fields

1. Visit the "Assistant" submenu in the Media admin section

1. Click the Screen Options link to customize the display

1. Use the enhanced Edit, Quick Edit and Bulk Edit pages to assign categories and tags

1. Use the `[mla_gallery]` shortcode to add galleries of images, documents and more to your posts and pages

1. Use the `[mla_tagcloud]` shortcode to add clickable lists of taxonomy terms to your posts and pages

== Frequently Asked Questions ==

= How can I sort the Media/Assistant submenu table on values such as File Size? =

You can add support for many attachment metadata values such as file size by visiting the Custom Fields tab on the Settings page. There you can define a rule that maps the data to a WordPress custom field and check the "MLA Column" box to make that field a sortable column in the Media/Assistant submenu table. You can also use the field in your `[mla_gallery]` shortcodes. For example, this shortcode displays a gallery of the ten largest images in the "general" category, with a custom caption:

`
[mla_gallery category="general" mla_caption="{+caption+}<br>{+custom:File Size+}" meta_key="File Size" orderby="meta_value" order="DESC" numberposts=10]
`

= How can I use Categories, Tags and custom taxonomies to select images for display in my posts and pages? =

The powerful `[mla_gallery]` shortcode supports almost all of the query flexibility provided by the WP_Query class. You can find complete documentation in the Settings/Media Library Assistant Documentation tab. A simple example is in the preceding question. Here's an example that displays PDF documents with Att. Category "fauna" or Att. Tag "animal":

`
[mla_gallery post_mime_type="application/pdf" size=icon mla_caption="{+title+}" tax_query="array(array('taxonomy'=>'attachment_category','field'=>'slug','terms'=>'fauna'),array('taxonomy'=>'attachment_tag','field'=>'slug','terms'=>'animal'),'relation'=>'OR')"]
`

= Can I use `[mla_gallery]` for attachments other than images? =

Yes! The `[mla_gallery]` shortcode supports all MIME types when you add the post_mime_type parameter to your query. You can build a gallery of your PDF documents, plain text files and other attachments. You can mix images and other MIME types in the same gallery, too. Here's an example that displays a gallery of PDF documents, using the Google File Viewer to show the first page of each document as a thumbnail:

`
[mla_gallery post_mime_type=application/pdf post_parent=all link=file mla_viewer=true columns=1 orderby=date order=desc]
`

= Can I attach an image to more than one post or page? =

No; that's a structural limitation of the WordPress database. However, you can use Categories, Tags and custom taxonomies to organize your images and associate them with posts and pages in any way you like. The `[mla_gallery]` shortcode makes it easy. You can also use the `ids=` parameter to compose a gallery from a list of specific images.

= Can the Assistant use the standard WordPress post Categories and Tags? =

Yes! You can activate or deactivate support for Categories and Tags at any time by visiting the Media Library Assistant Settings page.

= Do I have to use the WordPress post Categories and Tags? =

No! The Assistant supplies pre-defined Att. Categories and Att. Tags; these are WordPress custom taxonomies, with all of the API support that implies. You can activate or deactivate the pre-defined taxonomies at any time by visiting the Media Library Assistant Settings page.

= Can I add my own custom taxonomies to the Assistant? =

Yes. Any custom taxonomy you register with the Attachment post type will appear in the Assistant UI. Use the Media Library Assistant Settings page to add support for your taxonomies to the Assistant UI.

= Can I use Jetpack Tiled Gallery or a lightbox plugin to display my gallery? =
You can use other gallery-generating shortcodes to give you the data selection power of [mla_gallery] and the formatting/display power of popular alternatives such as the WordPress.com Jetpack Carousel and Tiled Galleries modules. Any shortcode that accepts "ids=" or a similar parameter listing the attachment ID values for the gallery can be used. Here's an example of a Jetpack Tiled gallery for everything except vegetables:

`
[mla_gallery attachment_category=vegetable tax_operator="NOT IN" mla_alt_shortcode=gallery type="rectangular"]
`

Most lightbox plugins use HTML `class=` and/or `rel=` tags to activate their features. `[mla_gallery]` lets you add this tag information to your gallery output. Here's an example that opens PDF documents in a shadowbox using Easy Fancybox:

`
[mla_gallery post_mime_type=application/pdf post_parent=all link=file size=icon mla_caption='<a class="fancybox-iframe fancybox-pdf" href={+filelink_url+} target=_blank>{+title+}</a>' mla_link_attributes='class="fancybox-pdf fancybox-iframe"']
`

In the example, the `mla_caption=` parameter turns the document title into a link to the shadowbox display so you can click on the thumbnail image or the caption to activate the display.

= Why don't the "Posts" counts in the taxonomy edit screens match the search results when you click on them? =

This is a known WordPress problem with multiple support tickets already in Trac, e.g., 
Ticket #20708(closed defect (bug): duplicate) Wrong posts count in taxonomy table,
Ticket #14084(assigned defect (bug)) Custom taxonomy count includes draft & trashed posts,
and Ticket #14076(closed defect (bug): duplicate) Misleading post count on taxonomy screen.

For example, if you add Tags support to the Assistant and then assign tag values to your attachments, the "Posts" column in the "Tags" edit screen under the Posts admin section includes attachments in the count. If you click on the number in that column, only posts and pages are displayed. There are similar issues with custom post types and taxonomies (whether you use the Assistant or not). The "Attachments" column in the edit screens added by the Assistant shows the correct count because it works in a different way.

= How do I "unattach" an item? =

Hover over the item you want to modify and click the "Edit" or "Quick Edit" action. Set the ID portion of the Parent Info field to zero (0), then click "Update" to record your changes. If you change your mind, click "Cancel" to return to the main page without recording any changes. You can also click the "Select" button to bring up a list of posts//pages and select one to be the new parent for the item. The "Set Parent" link in the Media/Assistant submenu table also supports changing the parent and unattaching an item.

= The Media/Assistant submenu seems sluggish; is there anything I can do to make it faster? =

Some of the MLA features such as where-used reporting and ALT Text sorting/searching require a lot of database processing. If this is an issue for you, go to the Settings page and adjust the "Where-used database access tuning" settings. For any where-used category you can enable or disable processing. For the "Gallery in" and "MLA Gallery in" you can also choose to update the results on every page load or to cache the results for fifteen minutes between updates. The cache is also flushed automatically when posts, pages or attachments are inserted or updated.

= Are other language versions available? =

Not yet, but all of the internationalization work in the plugin source code has been completed and there is a Portable Object Template (.POT) available in the "/languages" directory. I don't have working knowledge of anything but English, but if you'd like to volunteer to produce a translation, I would be delighted to work with you to make it happen. Have a look at the "MLA Internationalization Guide.pdf" file in the languages directory and get in touch.

= What's in the "phpDocs" directory and do I need it? =

All of the MLA source code has been annotated with "DocBlocks", a special type of comment used by phpDocumentor to generate API documentation. If you'd like a deeper understanding of the code, click on "index.html" in the phpDocs directory and have a look. Note that these pages require JavaScript for much of their functionality.

== Screenshots ==

1. The Media/Assistant submenu table showing the available columns, including "Featured in", "Inserted in", "Att. Categories" and "Att. Tags"; also shows the Quick Edit area.
2. The Media/Assistant submenu table showing the Bulk Edit area with taxonomy Add, Remove and Replace options; also shows the tags suggestion popup.
3. A typical edit taxonomy page, showing the "Attachments" column.
4. The enhanced Edit page showing additional fields, categories and tags.
5. The Settings page General tab, where you can customize support of Att. Categories, Att. Tags and other taxonomies, where-used reporting and the default sort order.
6. The Settings page MLA Gallery tab, where you can add custom style and markup templates for `[mla_gallery]` shortcode output.
7. The Settings page IPTC &amp; EXIF Processing Options screen, where you can map image metadata to standard fields (e.g. caption), taxonomy terms and custom fields.
8. The Settings page Custom Field Processing Options screen, where you can map attachment metadata to custom fields for display in [mla_gallery] shortcodes and as sortable, searchable columns in the Media/Assistant submenu.
9. The Media Manager popup modal window showing additional filters for date and taxonomy terms. Also shows the enhanced Search Media box and the full-function taxonomy support in the ATTACHMENT DETAILS area.

== Changelog ==

= 2.10 =
* New: For the `[mla_gallery]` shortcode, the **Google File Viewer (mla_viewer) has been replaced** by two new featues. First, **a "Featured Image" can be assigned to Media Library items**; it will replace the MIME type icon as the thumbnail for the item. Second, **PDF documents can generate a thumbnail image** for the item if Imagemagick, Imagick and Ghostscript are available on the server. See the [Other Notes section](http://wordpress.org/extend/plugins/media-library-assistant/other_notes/ "Click here, then scroll down") section or the Settings/Media Library Assistant Documentation tab for more information.
* New: **XMP metadata can be extracted from JPEG and TIFF images** and used in `[mla_gallery]` shortcodes and IPTC/EXIF or Custom Field mapping rules.
* New: Several **simple MLA Gallery examples** have been added to the Documentation tab.
* New: A Media/Assistant submenu table **custom view example plugin**, `mla-custom-view-example.php.txt` has been added to the `/media-library-assistant/examples/` directory. The example adds two custom views for "Attached" items and "Unpublished" items that are attached to a parent whose `post_status` is 'draft', 'future', 'pending' or 'trash' .
* New: Two new filters for the "Media/Assistant Submenu Hooks" allow you to record or modify the new values for Bulk Edit fields. The `/media-library-assistant/examples/mla-list-table-hooks-example.php.txt` example plugin has been updated with the new filters.
* New: A **Terms Search "exact"** option has been added to eliminate false matches such as "man" within "woman".
* New: A new **field-level data source, "alt_text"** is available for use in shortcodes and mapping rules.
* New: A new **field-level option value, "substr(s,l)"** is available for use in shortcodes and mapping rules. It uses the PHP substr() function to extract a portion of a field-level value.
* New: If you add `define( 'MLA_DEBUG_LEVEL', 1 );` to your `wp-config.php` file a new Settings/Media Library Assistant **Debug tab** is available. The new tab lets you view, download and reset (empty) the PHP error log file.
* New: The Development Version date and MLA debug level, if applicable, are now added to the title of the Settings/Media library Assistant submenu.
* Fix: Text and Textarea option settings containing backslashes are now cleaned up with `stripslashes`.
* Fix: In the Media Manager Modal Window, CSS styles have been updated to improve the layout of the toolbar.
* Fix: If 'Enable "bulk edit" area' is checked, bulk edit on Add New uploads will be run even if all four "enable mapping" options are disabled.
* Fix: **Terms Search performance** has been improved by eliminating redundant table joins.
* Fix: For the Media/Assistant submenu table, **sorting on custom fields** now works correctly.
* Fix: For the Media/Assistant submenu table, **Search Media with the terms option** now works correctly.
* Fix: The `/media-library-assistant/examples/mla-acf-checkbox-example.php.txt` example plugin has been re-written to use the new filters and to confrom to changes made in MLA version 2.01.
* Fix: The `/media-library-assistant/examples/mla-image-source-control-example.php.txt` example plugin has been re-written to use the new filters and to confrom to changes made in MLA version 2.01.
* Fix: Where-used reporting now identifies posts and pages in the Trash.
* Fix: For IPTC/EXIF mapping, single/double quotes in the EXIF/Template Value field are now handled correctly, without adding backslash characters to the new values.
* Fix: Field-level option values containing multiple arguments are now parsed correctly.
* Fix: Several changes to the Translation/Localization strings to reduce translation effort.

= 2.02 =
* New: For the **Media/Add New screen, a new Bulk Edit area** lets you assign taxonomy terms and change standard or custom fields as new items are uploaded to the Media Library.
* New: An enhanced array of **"CAMERA"-related fields is provided as part of the EXIF metadata**. They contain more attractive and useful versions of "ExposureBiasValue", "ExposureTime", "Flash", "FNumber", "FocalLength", "ShutterSpeed" and a number of "Other Tags". See the [Other Notes section](http://wordpress.org/extend/plugins/media-library-assistant/other_notes/ "Click here, then scroll down") section or the Settings/Media Library Assistant Documentation tab for more information.
* New: For **IPTC/EXIF and Custom Field mapping**, you can **cancel and then resume mapping activity**. You can also specify a starting offset for the resumes activity, allowing you to skip over previously-processed items or to re-process items.
* New: For mapping rules and `[mla_gallery]`, **"timestamp", "date" and "fraction" format options** can be used to format IPTC/EXIF metadata values, custom fields and other Data Sources. See the [Other Notes section](http://wordpress.org/extend/plugins/media-library-assistant/other_notes/ "Click here, then scroll down") section or the Settings/Media Library Assistant Documentation tab for more information.
* New: For `[mla_gallery]`, `parent_name` (slug) and `parent_permalink` have been added to the Attachment-specific substitution parameters for the markup template Item part.
* New: For `[mla_gallery]`, the `mla_terms_taxonomies` parameter can be used for "Keyword(s) Search" to control which taxonomies are included if `mla_search_fields` includes "terms".
* New: The **"checked on top" option for checklist-style taxonomy meta boxes** can be set or cleared on the Settings/Media Library Assistant General tab.
* New: For **IPTC/EXIF and Custom Field mapping**, `[+iptc:ALL_IPTC+]` is now a synonym for `[+exif:ALL_IPTC+]`.
* New: Coverage of field-level substitution parameters in the Settings/Media Library Assistant Documentation tab has been re-organized, clarified and expanded.
* New: A reference to plugin translation and the MLA Internationalization Guide have been added to the Settings/Media Library Assistant Documentation tab.
* Fix: For the Media Manager/Media Grid Enhancements, PHP notice messages are avoided when the WordPress "current_screen" value is not set by other themes and plugins, such as the "Total theme and Visual Composer".
* Fix: Multiple ALT Text (_wp_attachment_image_alt) values no longer cause PHP Warning messages; only the first value is used for `[mla_gallery]` and the Quick Edit area.
* Fix: Some of the "Creating a new Translation" instructions in the MLA Internationalization Guide have been improved.
* Fix: many of the translation strings have been re-organized to simplify translation efforts.
* Fix: For `[mla_gallery]`, any "alt=" and "class=" attributes coded in the `mla_image_attributes` parameter will override and replace the existing "alt=" and/or "class=" attributes in the "img" tag. This avoids the confusion of having two instances of the attribute(s) in the tag.
* Fix: For `[mla_gallery]`, documentation of a **WordPress 4.0+ change** that affects taxonomy, date and custom field (meta) queries has been added, including a work-around to **avoid "Invalid mla_gallery tax_query"** errors.
* Fix: For `[mla_gallery]`, the **Google File Viewer (mla_viewer) has been replaced**. Recent changes by Google, beyond MLA control, have removed support for the original feature. The interim fix allows you to substitute an appropriate icon for non-image file types. See the Documentation tab for more information.
* Fix: For **IPTC/EXIF and Custom Field mapping**, custom field names with mixed case, spaces and punctuation characters are now properly handled. Custom field names with HTML reserved characters such as quotes and angle brackerts are properly escaped for display purposes.
* Fix: For the Media/Assistant submenu table, column headers containing HTML reserved characters are now properly escaped for display purposes.
* Fix: For the Media/Assistant submenu table, unnecessary processing and database access are avoided when all four "where-used" reporting options are disabled.
* Fix: For `[mla_gallery]` and `[mla_tag_cloud]`, duplicate `mla_page_parameter` query arguments have been eliminated from links in the gallery or cloud.
* Fix: For `[mla_gallery]` and `[mla_tag_cloud]`, damage caused by line-breaks between shortcode parameters is (usually) repaired.

= 2.01 =
* New: For **IPTC/EXIF mapping of taxonomy terms, significant performance improvements.** Explicit handling of special cases and new caching code for "map all" processing eliminates unnecessary database queries.
* New: For the Media/Assistant submenu, the **"where-used" displays have improved**. The post status (Draft, Pending, Future) is now included (it is also included in the "Parent Info" meta box on the Media/Edit Media screen). The parent post/page is moved to the top of the references list. The "Inserted in" file name is no longer displayed for the "base" option to save space.
* New: For `[mla_gallery]` and `[mla_tag_cloud]`, several new **galley-/cloud- substitution values** have been added. You can use these, for example, to add page-level information like Title or Date to data selection parameters.
* New: An example of Media/Assistant submenu support for Advanced Custom Fields Checkbox variables is provided at `/media-library-assistant/examples/mla-acf-checkbox-example.txt`
* New: An example of mapping PDF metadata to Standard Fields and Taxonomy Terms has been added to the "IPTC/EXIF Mapping for PDF Documents" section of the Settings/Media Library Assistant Documentation tab.
* Fix: For `[mla_gallery]`, the **Google File Viewer parameter (mla_viewer) has been disabled**. Recent changes by Google, beyond MLA control, have removed support for this feature.
* Fix: Where-used reference information is no longer computed during file uploads, improving performance.
* Fix: For the Media/Assistant submenu Bulk Edit area, updates to the "Categories" taxonomy are now handled correctly.
* Fix: For `[mla_gallery]`, a defect in the default handling of the `post_parent` parameter has been fixed. The defect was introduced in version 2.00.
* Fix: For `[mla_gallery]`, a defect in the handling of the `exact=true` parameter has been fixed.
* Fix: For the "Select Parent" popup window, a defect in handling invalid post_status values has been fixed.

= 2.00 =
* New: **Requires WordPress v3.5 or greater.**
* New: **Enhanced Keyword(s) Search and Taxonomy term keyword(s) search for the `[mla_gallery]` shortcode**. See the [Other Notes section](http://wordpress.org/extend/plugins/media-library-assistant/other_notes/ "Click here, then scroll down") section or the Settings/Media Library Assistant Documentation tab for more information.
* New: **Ajax-powered Bulk Edit** processing lets you see the progress of large update batches and prevents script timeouts.
* New: **Ajax-powered Custom Field and IPTC/EXIF mapping** lets you see the progress of large update runs and prevents script timeouts.
* New: For custom field mapping rules, the "Raw" Format avoids the conversion of numeric zero values to blanks.
* New: On the Media/Assistant submenu table  **Content Templates, including `template:[+empty+]`,** have been added to the Bulk Edit area processing for custom fields.
* New: On the Media/Assistant submenu table  **the Download rollover action is more secure.** Downloads now require a WordPress admin-mode nonce check to succeed.
* New: For the `[mla_tag_cloud]` shortcode, the `mla_get_terms_clauses` filter lets you inspect or modify the SQL clauses used to retrieve terms for the cloud.
* New: Two (2) new **filters for the Media/Assistant submenu table** let you intercept the beginning and end of Bulk Edit actions.
* Fix: For [mla_gallery], the keyword search parameter ("s") now works properly when the user is not logged in.
* Fix: For IPTC/EXIF mapping of custom fields, field names containing uppercase letters, whitespace and punctuation are now handled correctly.

= 1.90 - 1.95 =
* 1.95: New [mla_gallery] parameters, Download rollover action, Media/Assistant submenu filters. Eleven enhancements, seven fixes.
* 1.94: Media Manager fixes and new "current-item" parameters for [mla_tag_cloud]. Two other enhancements, seven fixes.
* 1.93: WordPress 4.0 Media Grid enhancements (optional) and compatibility fixes. New auto-fill option for Media Manager taxonomy meta boxes. One other enhancement, three other fixes.
* 1.92: Three bug fixes, one serious.
* 1.91: WordPress 4.0 support! New "Edit Media meta box" and "Media Modal Initial Values" filters and example plugins. Four other enhancements, six fixes.
* 1.90: New "Terms Search" popup window and Search Media "Terms" checkbox. Post Type filter and pagination for "Select Parent" popup. Ten other enhancements, five fixes.

= 1.80 - 1.83 =
* 1.83: Corrects serious defect, restoring Quick Edit, Bulk Edit and Screen Options to Media/Assistant submenu. Three other fixes.
* 1.82: "Select Parent" popup window (Media/Edit Media, Attached to column, Quick Edit area), SVG support and several new filter examples. Five other enhancements, three other fixes.
* 1.81: Corrects serious defect in Media Manager Modal Window file uploading. Adds item-specific tag clouds. One other enhancement, five other fixes.
* 1.80: Full taxonomy meta box support in the Media Manager Modal Window. Checkbox-style meta box for flat taxonomies. Fourteen other enhancements, nine fixes.

= 1.70 - 1.71 =
* 1.71: Searchable Category meta boxes for the Media/Edit Media screen. Support for the WordPress "Attachment Display Settings". Six fixes.
* 1.70: Internationalization and localization support! Custom Field and IPTC/EXIF Mapping hooks. One other enhancement, six fixes.

= 1.60 - 1.61 =
* 1.61: Three fixes, including one significant fix for item-specific markup substitution parameters. Tested for compatibility with WP 3.8.
* 1.60: New [mla_tag_cloud] shortcode and shortcode-enabled MLA Text Widget. Five other enhancements, four fixes.

= 1.50 - 1.52 =
* 1.52: Corrected serious defect in [mla_gallery] that incorrectly limited the number of items returned for non-paginated galleries. One other fix.
* 1.51: Attachment Metadata mapping/updating, [mla_gallery] "apply_filters" hooks, multiple paginated galleries per page, "ALL_CUSTOM" pseudo value. Three other enhancements, six fixes.
* 1.50: PDF and GPS Metadata support. Content Templates; mix literal text with data values, test for empty values and choose among two or more alternatives for [mla_gallery] and data mapping. Four other enhancements, seven fixes.

= 1.40 - 1.43 =
* 1.43: Generalized pagination support with "mla_output=paginate_links". One other enhancement, four fixes.
* 1.42: Pagination support for [mla_gallery]! Improved CSS width (itemwidth) and margin handling. Eight other enhancements, six fixes.
* 1.41: New [mla_gallery] "previous link" and "next link" output for gallery navigation. New "request" substitution parameter to access $_REQUEST variables. Three other enhancements, seven fixes.
* 1.40: Better performance! New custom table views, Post MIME Type and Upload file/MIMEs control; 112 file type icons to choose from. Four new Gallery Display Content parameters. four other enhancements, twelve fixes.

= 1.00 - 1.30 =
* 1.30: New "mla_alt_shortcode" parameter combines [mla_gallery] with other gallery display shortcodes, e.g., Jetpack Carousel and Tiled Mosaic. Support for new 3.6 audio/video metadata. One other enhancement, eight fixes.
* 1.20: Media Manager (Add Media, etc.) enhancements: filter by more MIME types, date, taxonomy terms; enhanced search box for name/slug, ALT text, caption and attachment ID. New [mla_gallery] sort options. Four other enhancements, four fixes.
* 1.14: New [mla_gallery] mla_target and tax_operator parameters, tax_query cleanup and ids/include fix. Attachments column fix. IPTC/EXIF and Custom Field mapping fixes. Three other fixes.
* 1.13: Add custom fields to the quick and bulk edit areas; sort and search on them in the Media/Assistant submenu. Expanded EXIF data access, including COMPUTED values. Google File Viewer support, two other enhancements and two fixes.
* 1.11: Search by attachment ID, avoid fatal errors and other odd results when adding taxonomy terms. One other fix.
* 1.10: Map attachment metadata to custom fields; add them to [mla_gallery] display and as sortable columns on the Media/Assistant submenu table. Get Photonic Gallery (plugin) integration and six other fixes.
* 1.00: Map IPTC and EXIF metadata to standard fields, taxonomy terms and custom fields. Improved performance for where-used reporting. Specify default `[mla_gallery]` style and markup templates. Five other fixes.

= 0.11 - 0.90 =
* `[mla_gallery]` support for custom fields, taxonomy terms and IPTC/EXIF metadata. Updated for WordPress 3.5!
* Improved default Style template, `[mla_gallery]` parameters "mla_itemwidth" and "mla_margin" for control of gallery item spacing. Quick edit support of WordPress standard Categories taxonomy has been fixed.
* MLA Gallery Style and Markup Templates for control over CSS styles, HTML markup and data content of `[mla_gallery]` shortcode output. Eight other enhancements and four fixes.
* Removed (!) Warning displays for empty Gallery in and MLA Gallery in column entries.
* New "Gallery in" and "MLA Gallery in" where-used reporting to see where items are returned by the `[gallery]` and `[mla_gallery]` shortcodes. Two other enhancements and two fixes.
* Enhanced Search Media box. Extend search to the name/slug, ALT text and caption fields. Connect search terms with "and" or "or". Five other enhancements and two fixes.
* New `[mla_gallery]` shortcode, a superset of the `[gallery]` shortcode that provides many enhancements. These include taxonomy support and all post_mime_type values (not just images). Media Library items need not be "attached" to the post.
* SQL View (supporting ALT Text sorting) now created for automatic plugin upgrades
* Bulk Edit area; add, remove or replace taxonomy terms for several attachments at once. Sort your media listing on ALT Text, exclude revisions from where-used reporting.
* Support ALL taxonomies, including the standard Categories and Tags, your custom taxonomies and the Assistant's pre-defined Att. Categories and Att. Tags. Add taxonomy columns to the Assistant admin screen, filter on any taxonomy, assign terms and list the attachments for a term. 
* Quick Edit action for inline editing of attachment metadata
* Fixed "404 Not Found" errors when updating single items.

= 0.1 =
* Initial release.

== Upgrade Notice ==

= 2.10 =
mla_viewer is back, with a Featured Image option! XMP support for image meta data. Eight other enhancements, twelve fixes.

== Other Notes ==

In this section, scroll down to see highlights from the documentation, including new and unique plugin features

**NOTE:** Complete documentation is included in the Documentation tab on the Settings/Media Library Assistant admin screen and the drop-down "Help" content in the admin screens.

== Acknowledgements ==

Media Library Assistant includes many images drawn (with permission) from the [Crystal Project Icons](http://www.softicons.com/free-icons/system-icons/crystal-project-icons-by-everaldo-coelho), created by [Everaldo Coelho](http://www.everaldo.com), founder of [Yellowicon](http://www.yellowicon.com).

<h4>NEW! Thumbnail Substitution Support, mla_viewer</h4>

<strong>NOTE: Google has discontinued the File Viewer support for thumbnail images.</strong> This solution supports dynamic thumbnail image generation for PDF and Postscript documents on your site's server. You can also assign a "Featured Image" to any Media Library item. For non-image items such as Microsoft Office documents the featured image will replace the MIME-type icon or document title in an <code>[mla_gallery]</code> display. Simply go to the Media/Edit Media screen, scroll down to the "Featured Image" meta box and select an image as you would for a post or page. 

The dynamic thumbnail image generation for PDF and Postscript documents uses the PHP <code>Imagick</code> class, which <strong>requires Imagemagick and Ghostscript</strong> to be installed on your server. If you need help installing them, look at this <a href="https://wordpress.org/support/topic/nothing-but-error-messages" title="Help with installation" target="_blank">PDF Thumbnails support topic</a>. If you don't have them on your server you can still use the Featured Image support to supply thumbnails for your non-image items. 

Ten <code>[mla_gallery]</code> parameters provide an easy way to simulate thumbnail images for the non-image file types.

* <strong>mla_viewer</strong> - must be "true" or "single" to enable thumbnail substitution. Use "true" unless you experience generation failures due to memory limitations on your server. Use "single" to generate one thumbnail at a time, which may be slower but requires less memory.
* <strong>mla_viewer_extensions</strong> - a comma-delimited list of the file extensions to be processed; the default is "ai,eps,pdf,ps" (do not include the dot (".") preceding the file extension). You may add or remove extensions (when support for additional types becomes available).
* <strong>mla_viewer_limit</strong> - the upper limit in megabytes (default none) on the size of the file to be processed. You can set this to avoid processing large documents if performance becomes an issue.
* <strong>mla_viewer_width</strong> - the maxinum width in pixels (default "150") of the thumbnail image. The height (unless also specified) will be adjusted to maintain the page proportions.
* <strong>mla_viewer_height</strong> - the maxinum width in pixels (default "0") of the thumbnail image. The width (unless also specified) will be adjusted to maintain the page proportions.
* <strong>mla_viewer_best_fit</strong> - retain page proportions (default "false") when both height and width are explicitly stated. If "false", the image will be stretched as required to exactly fit the height and width. If "true", the image will be reduced in size to fit within the bounds, but proportions will be preserved. For example, a typical page is 612 pixels wide and 792 pixels tall. If you set width and height to 300 and set best_fit to true, the thumbnail will be reduced to 231 pixels wide by 300 pixels tall.
* <strong>mla_viewer_page</strong> - the page number (default "1") to be used for the thumbnail image. If the page does not exist for a particular document the first page will be used instead.
* <strong>mla_viewer_resolution</strong> - the pixels/inch resolution (default 72) of the page before reduction. If you set this to a higher number, such as 300, you will improve thumbnail quality at the expense of additional processing time.
* <strong>mla_viewer_quality</strong> - the compression quality (default 90) of the final page. You can set this to a value between 1 and 100 to get smaller files at the expense of image quality; 1 is smallest/worst and 100 is largest/best. 
* <strong>mla_viewer_type</strong> - the MIME type (default image/jpeg) of the final thumbnail. You can, for example, set this to "image/png" to retain a transparent background instead of the white jpeg background.</td>

When this feature is active, gallery items for which WordPress can generate a thumbnail image are not altered. If WordPress generation fails, the "Featured Image" will be used, if one is specified for the item. If the item does not have a Featured Image, supported file/MIME types (PDF for now) will have a gallery thumbnail generated dynamically. If all else fails, the thumbnail is replaced by an "img" html tag whose "src" attribute contains a url reference to the appropriate icon for the file/MIME type.

Four options in the Settings/Media Library Assistant MLA Gallery tab allow control over mla_viewer operation:

* <strong>Enable thumbnail substitution</strong><br />
Check this option to allow the "mla_viewer" to generate thumbnail images for PDF documents. Thumbnails are generated dynamically, each time the item appears in an <code>[mla_gallery]</code> display.
* <strong>Enable Featured Images</strong><br />
Check this option to extend Featured Image support to all Media Library items. The Featured Image can be used as a thumbnail image for the item in an <code>[mla_gallery]</code> display.
* <strong>Enable explicit Ghostscript check</strong><br />
Check this option to enable the explicit check for Ghostscript support required for thumbnail generation. If your Ghostscript software is in a non-standard location, unchecking this option bypasses the check. Bad things can happen if Ghostscript is missing but Imagemagick is present, so leave this option checked unless you know it is safe to turn it off.
* <strong>Ghostscript path</strong><br />
If your Ghostscript software is in a non-standard location, enter the full path and name of the executable here. The value you enter will be used as-is and the search for Ghostscript in the usual locations will be bypassed.

<h3>Field-level Substitution Parameters</h3>

Field-level substitution parameters let you access query arguments, custom fields, taxonomy terms and attachment metadata for display in an MLA gallery or in an MLA tag cloud. You can also use them in IPTC/EXIF or Custom Field mapping rules. For field-level parameters, the value you code within the surrounding the ('[+' and '+]' or '{+' and '+}') delimiters has three parts; the prefix, the field name (or template content) and, if desired, an option/format value.

The <strong>prefix</strong> defines which type of field-level data you are accessing. It must immediately follow the opening ('[+' or '{+') delimiter and end with a colon (':'). There can be no spaces in this part of the parameter.

The <strong>field name</strong> defines which field-level data element you are accessing. It must immediately follow the colon (':'). There can be no spaces between the colon and the field name. Spaces are allowed within the field name to accommodate custom field names that contain them. <strong>Compound names</strong> are used to access elements within arrays, e.g., &quot;<strong>sizes.thumbnail.file</strong>&quot; is used to specify the file name for the thumbnail version of an image. For the "template" prefix, the field name is replaced by the template content; see the Content Templates section for details.

The <strong>option/format value</strong>, if present, immediately follows the field name using a comma (,) separator and ends with the closing delimiter ('+]' or '+}'). There can be no spaces in this part of the parameter.
<a name="field_level_prefixes"></a>

<h4>Prefix values</h4>

There are nine prefix values for field-level parameters. Prefix values must be coded as shown; all lowercase letters.

* <strong>request</strong> - The parameters defined in the <code>$_REQUEST</code> array; the "query strings" sent from the browser. The PHP $_REQUEST variable is a superglobal Array that contains the contents of both $_GET, $_POST, and $_COOKIE arrays. It can be used to collect data sent with both the GET and POST methods. For example, if the URL is <code>http://www.mysite.com/mypage?myarg=myvalue</code> you can access the query string as <code>[+request:myarg+]</code>, which has the value "myvalue".
* <strong>query</strong> - The parameters defined in the <code>[mla_gallery]</code> shortcode. For example, if your shortcode is <code>[mla_gallery attachment_tag=my-tag div-class=some_class]</code> you can access the parameters as <code>[+query:attachment_tag+]</code> and <code>[+query:div-class+]</code> respectively. Only the parameters actually present in the shortcode are accessible; default values for parameters not actually present are not available. You can define your own parameters, e.g., "div-class"; they will be accessible as field-level data but will otherwise be ignored.
* <strong>custom</strong> - WordPress Custom Fields, which you can define and populate on the Edit Media screen or map from various sources on the Settings/Media Library Assistant Custom and IPTC/EXIF tabs. The field name, or key, can contain spaces and some punctuation characters. You <strong><em>cannot use the plus sign ('+')</em></strong> in a field name you want to use with <code>[mla_gallery]</code>. Custom field names are case-sensitive; "client" and "Client" are not the same.
		<br />&nbsp;<br />
		For custom fields only, the <strong>",raw" option</strong> bypasses the code to sanitize the returned value. Use this option to allow HTML tags to be returned from a custom field.
		<br />&nbsp;<br />
		One special custom "pseudo-value" is available; <strong>ALL_CUSTOM</strong> (<code>[+custom:ALL_CUSTOM+]</code>). This returns a string representation of all custom field values. You can use this pseudo-values to quickly examine which fields are populated for a given Media Library item and what its values are.
		<br />&nbsp;<br />
		The ALL_CUSTOM value is altered in two ways. First, values of more than 256 characters are truncated to 256 characters. This prevents large fields from dominating the display. Second, array values are shown '(ARRAY)'.
* <strong>terms</strong> - WordPress Category, tag or custom taxonomy terms. For this category, you code the name of the taxonomy as the field name. The term(s) associated with the attachment will be displayed in the <code>[mla_gallery]</code>. Note that you must use the name/slug string for taxonomy, not the "title" string. For example, use "attachment_category" or "attachment_tag", not "Att. Category" or "Attachment Category".
* <strong>meta</strong> - WordPress attachment metadata, if any, embedded in the image/audio/video file. For this category, you can code any of the field names embedded in the _wp_attachment_metadata array. The "Attachment Metadata" display in the Media/Edit Media screen will show you the names and values of these fields. Note that the fields available differ among image, audio and video attachments.<br />
		&nbsp;<br />
		The "image_meta" portion of the attachment metadata is of particular interest. This array contains some "extended image metadata" drawn from IPTC and EXIF fields by WordPress and improved a bit. You can find more information in the Codex <a href="http://codex.wordpress.org/Function_Reference/wp_read_image_metadata" title="Codex information for image_meta" target="_blank">Function Reference/wp read image metadata</a>. For example, to get the ISO speed rating for an image, code <code>[+meta:image_meta.iso+]</code>.
* <strong>pdf</strong> - The Document Information Dictionary (D.I.D.)and XMP metadata, if any, embedded in a PDF file. For this category, you can code any of the nine D.I.D. entries (Title, Author, Subject, Keywords, Creator, Producer, CreationDate, ModDate, Trapped). For many documents there is also a rich collection of additional metadata stored in XMP Metadata Streams; see the section for details on accessing PDF metadata.<br />
		&nbsp;<br />
		You can find more PDF information at the <a href="http://www.adobe.com/devnet/pdf.html" title="Adobe PDF Technology Center" target="_blank">Adobe PDF Technology Center</a>.
* <strong>iptc</strong> - The IPTC (International Press Telecommunications Council) metadata, if any, embedded in the image file. For this category, you can code any of the IPTC DataSet tag and field identifiers, e.g., "2#025" for the Keywords field. You can also use the "friendly name" MLA defines for most of the IPTC fields; see the table of identifiers and friendly names.<br />
		&nbsp;<br />
		You can find more IPTC information in the <a href="http://www.iptc.org/std/IIM/4.1/specification/IIMV4.1.pdf" title="IPTC-NAA Information Interchange Model Version No. 4.1 specification (PDF)" target="_blank">IPTC-NAA Information Interchange Model Version No. 4.1 specification (PDF document)</a>.
		<br />&nbsp;<br />
		A special iptc "pseudo-value" is available; <strong>ALL_IPTC</strong> (<code>[+iptc:ALL_IPTC+]</code>). It returns a string representation of all IPTC data. You can use the pseudo-value to examine the metadata in an image, find field names and see what values are embedded in the image.
		<br />&nbsp;<br />
		The ALL_IPTC value is altered in two ways. First, values of more than 256 characters are truncated to 256 characters. This prevents large fields such as keyword arrays from dominating the display. Second, array values are shown once, at their expanded level.
* <strong>exif</strong> - The EXIF (EXchangeable Image File) metadata, if any, embedded in a JPEG DCT or TIFF Rev 6.0 image file. 
		Though the specification is not currently maintained by any industry or standards organization, almost all camera manufacturers use it. It is also supported by many image editing programs such as Adobe PhotoShop.
		For this category, you can code any of the field names embedded in the image by the camera or editing software. There is no official list of standard field names, so you just have to know the names your camera and software use; field names are case-sensitive.
		<br />&nbsp;<br />
		You can find more information in the <a href="http://en.wikipedia.org/wiki/Exchangeable_image_file_format" title="Exchangeable image file format Wikipedia article" target="_blank">Exchangeable image file format</a> article on Wikipedia. You can find External Links to EXIF standards and tag listings at the end of the Wikipedia article.
		<br />&nbsp;<br />
		MLA uses a standard PHP function, <a href="http://php.net/manual/en/function.exif-read-data.php" title="PHP Manual page for exif_read_data" target="_blank">exif_read_data</a>, to extract EXIF data from images. The function returns three arrays in addition to the raw EXIF data; COMPUTED, THUMBNAIL and COMMENT. You can access the array elements by prefacing the element you want with the array name. For example, the user comment text is available as "COMPUTED.UserComment" and "COMPUTED.UserCommentEncoding". You can also get "COMPUTED.Copyright" and its two parts (if present), "COMPUTED.Copyright.Photographer" and "COMPUTED.Copyright.Editor". The THUMBNAIL and COMMENT arrays work in a similar fashion.
		<br />&nbsp;<br />
		A special exif "pseudo-value" is available; <strong>ALL_EXIF</strong> (<code>[+exif:ALL_EXIF+]</code>). It returns a string representation of all EXIF data. You can use the pseudo-value to examine the metadata in an image, find field names and see what values are embedded in the image.
		<br />&nbsp;<br />
		The ALL_EXIF value is altered in two ways. First, values of more than 256 characters are truncated to 256 characters. This prevents large fields such as image thumbnails from dominating the display. Second, array values are shown once, at their expanded level. For example the "COMPUTED" array is displayed as 'COMPUTED' => '(ARRAY)' and then 'COMPUTED.Width' => "2816", etc.
* <strong>template</strong> - A Content Template, which lets you compose a value from multiple substitution parameters and test for empty values, choosing among two or more alternatives or suppressing output entirely. See the Content Templates section for details. Note that the formatting option is not supported for templates.

<h4>Field-level option/format values</h4>

You can use a field-level option or format value to specify the treatment of fields with multiple values or to change the format of a field for display/mapping purposes. If no option/format value is present, fields with multiple values are formatted as a comma-delimited text list. The option/format value, if present, immediately follows the field name using a comma (,) separator and ends with the closing delimiter ('+]' or '+}'). There can be no spaces in this part of the parameter.

Two "option" values change the treatment of fields with multiple values:

* <strong>,single</strong> - If this option is present, only the first value of the field will be returned. Use this option to limit the data returned for a custom field, taxonomy or metadata field that can have many values. For example, if you code <code>[+meta:sizes.thumbnail,single+]</code> the result will be "20120313-ASK_5605-150x150.jpg".
* <strong>,export</strong> - If this option is present, the PHP <code>var_export</code> function is used to return a string representation of all the elements in an array field. For example, if you code <code>[+meta:sizes.thumbnail,export+]</code> the result will be "array ('file' => '20120313-ASK_5605-150x150.jpg', 'width' => 150, 'height' => 150, 'mime-type' => 'image/jpeg'".

Seven "format" values help you reformat fields or encode them for use in HTML attributes and tags:

* <strong>,raw</strong> - If you want to avoid filtering a value through the WordPress <code>sanitize_text_field()</code> function you can add the ",raw" option. This is helpful when, for example, you are using a field that contains HTML markup such as a hyperlink.
* <strong>,commas</strong> - For numeric data source parameters such as "file_size" you can add the ",commas" option to format the value for display purposes.
* <strong>,attr</strong> - If you use a substitution parameter in an HTML attribute such as the <code>title</code> attribute of a hyperlink (<code>a</code>) or <code>img</code> tag you can add the ",attr" option to encode the <, >, &, " and ' (less than, greater than, ampersand, double quote and single quote) characters.
* <strong>,url</strong> - If you use a substitution parameter in an HTML <code>href</code> attribute such as a hyperlink (<code>a</code>) or <code>img</code> tag you can add the ",url" option to convert special characters such as quotes, spaces and ampersands to their URL-encoded equivalents.
* <strong>,fraction(f,s)</strong> - Many of the EXIF metadata fields are expressed as "rational" quantities, i.e., separate numerator and denominator values separated by a slash. For example, <code>[+exif:ExposureTime+]</code> can be expressed as "1/200" seconds. The "fraction" format converts these to a more useful format.<br />&nbsp;<br />There two optional arguments; "f" (format_string)and "s" (show_fractions). The "format_string" (default "2") can either be the number of decimal places desired or a sprintf()-style format specification. For example, <code>[+exif:ExposureTime,fraction(4)+]</code> will display 7/6 as "+1.1667". A format specification such as '%1$.2f' will display 7/6 as "1.17". Numbers between -1 and +1, i.e. true fractions, will display in their original form, e.g., "1/6". If the optional "show_fractions" (default true) argument is "false" fractional values will convert to a decimal equivalent. For example, fraction(4,false) will display 1/6 as "+0.1667", and <code>[+exif:ExposureTime,fraction( '%1$.2f', false )+]</code> will display 1/6 as "0.17".* <strong>,timestamp(f)</strong> - Many date and time values such as <code>[+meta:image_meta.created_timestamp+]</code> are stored as a UNIX timestamp. The ",timestamp" format converts a timestamp into a variety of date and/or time string formats, using the PHP date() function. Details on the format_string argument can be found at: <a href="http://php.net/manual/en/function.date.php" title="PHP Date format parameters" target="_blank">http://php.net/manual/en/function.date.php</a>.<br />&nbsp;<br />The default format string is "d/m/Y H:i:s", e.g., "31/12/2014 23:59:00" (just before midnight on new year's eve). You could code <code>[+meta:image_meta.created_timestamp,timestamp('j F, Y')+]</code> to display "31 December, 2014".
* <strong>,fraction(f,s)</strong> - Many of the EXIF metadata fields are expressed as "rational" quantities, i.e., separate numerator and denominator values separated by a slash. For example, <code>[+exif:ExposureTime+]</code> can be expressed as "1/200" seconds. The "fraction" format converts these to a more useful format.<br />&nbsp;<br />There two optional arguments; "f" (format_string)and "s" (show_fractions). The "format_string" (default "2") can either be the number of decimal places desired or a sprintf()-style format specification. For example, <code>[+exif:ExposureTime,fraction(4)+]</code> will display 7/6 as "+1.1667". A format specification such as '%1$.2f' will display 7/6 as "1.17". Numbers between -1 and +1, i.e. true fractions, will display in their original form, e.g., "1/6". If the optional "show_fractions" (default true) argument is "false" fractional values will convert to a decimal equivalent. For example, fraction(4,false) will display 1/6 as "+0.1667", and <code>[+exif:ExposureTime,fraction( '%1$.2f', false )+]</code> will display 1/6 as "0.17".
* <strong>,date(f)</strong> - Many EXIF date and time values such as DateTimeOriginal and DateTimeDigitized are stored as strings with a format of "YYYY:MM:DD HH:MM:SS". You can parse this format and just about any English textual datetime description into a Unix timestamp, then format the result by using the ",date" format. This format first uses the PHP strtotime() function, then the date() function. The "Supported Date and Time Formats" can be found at: <a href="http://php.net/manual/en/datetime.formats.php" title="PHP Supported Date and Time Formats" target="_blank">http://php.net/manual/en/datetime.formats.php</a>.<br />&nbsp;<br />The default format string is "d/m/Y H:i:s", e.g., "31/12/2014 23:59:00" (just before midnight on new year's eve). You could code <code>[+exif:DateTimeOriginal,date('j F, Y')+]</code> to display "31 December, 2014".
