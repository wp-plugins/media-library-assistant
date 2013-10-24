=== Plugin Name ===
Contributors: dglingren
Donate link: http://fairtradejudaica.org/make-a-difference/donate/
Tags: attachment, attachments, documents, gallery, image, images, media, library, media library, media-tags, media tags, tags, media categories, categories, IPTC, EXIF, GPS, PDF, meta, metadata, photo, photos, photograph, photographs, photoblog, photo albums, lightroom, photoshop, MIME, mime-type, icon, upload, file extensions
Requires at least: 3.3
Tested up to: 3.7
Stable tag: 1.52
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Enhances the Media Library; powerful [mla_gallery], taxonomy support, IPTC/EXIF/PDF processing, bulk/quick edit actions and where-used reporting.

== Description ==

The Media Library Assistant provides several enhancements for managing the Media Library, including:

* The **`[mla_gallery]` shortcode**, used in a post, page or custom post type to add a gallery of images and/or other Media Library items (such as PDF documents). MLA Gallery is a superset of the WordPress `[gallery]` shortcode; it is compatible with `[gallery]` and provides many enhancements. These include: 1) full query and display support for WordPress categories, tags, custom taxonomies and custom fields, 2) support for all post_mime_type values, not just images 3) media Library items need not be "attached" to the post, and 4) control over the styles, markup and content of each gallery using Style and Markup Templates. **Twenty-five hooks** provided for complete gallery customization from your theme or plugin code.

* Powerful **Content Templates**, which let you compose a value from multiple data sources, mix literal text with data values, test for empty values and choose among two or more alternatives or suppress output entirely.

* **Attachment metadata** such as file size, image dimensions and where-used information can be assigned to WordPress custom fields. You can then use the custom fields in your `[mla_gallery]` display and you can add custom fields as sortable, searchable columns in the Media/Assistant submenu table. You can also **modify the WordPress `_wp_attachment_metadata` contents** to suit your needs.

* **IPTC**, **EXIF (including GPS)** and **PDF** metadata can be assigned to standard WordPress fields, taxonomy terms and custom fields. You can update all existing attachments from the Settings page IPTC/EXIF tab, groups of existing attachments with a Bulk Action or one existing attachment from the Edit Media/Edit Single Item screen. Display **IPTC**, **EXIF** and **PDF** metadata with `[mla_gallery]` custom templates.

* Complete control over **Post MIME Types, File Upload extensions/MIME Types and file type icon images**. Fifty four (54) additional upload types, 112 file type icon images and a searchable list of over 1,500 file extension/MIME type associations.

* **Integrates with Photonic Gallery, Jetpack and other plugins**, so you can add slideshows, thumbnail strips and special effects to your `[mla_gallery]` galleries.

* **Enhanced Search Media box**. Search can be extended to the name/slug, ALT text and caption fields. The connector between search terms can be "and" or "or". Search by attachment ID is supported.

* **Where-used reporting** shows which posts use a media item as the "featured image", an inserted image or link, an entry in a `[gallery]` and/or an entry in an `[mla_gallery]`.
* **Complete support for ALL taxonomies**, including the standard Categories and Tags, your custom taxonomies and the Assistant's pre-defined Att. Categories and Att. Tags. You can add taxonomy columns to the Assistant listing, filter on any taxonomy, assign terms and list the attachments for a term.
* An inline **"Bulk Edit"** area; update author, parent and custom fields, add, remove or replace taxonomy terms for several attachments at once
* An inline **"Quick Edit"** action for many common fields and for custom fields
* Displays more attachment information such as parent information, file URL and image metadata. Uses and enhances the new Edit Media screen for WordPress 3.5 and above.
* Allows you to edit the post_parent, the menu_order and to "unattach" items
* Provides additional view filters for MIME types and taxonomies
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

Some of the MLA features such as where-used reporting and ALT Text sorting/searching require a lot of database processing. If this is an issue for you, go to the Settings page and adjust the "Where-used database access tuning" settings. For any where-used category you can enable or disable processing. For the "Gallery in" and "MLA Gallery in" you can also choose to update the results on every page load or to cache the results for fifteen minutes between updates. The cache is also flushed automatically when posts, pages or attachments are inserted or updated.

= Are other language versions available? =

Not at this time; I don't have working knowledge of anything but English. If you'd like to volunteer to produce another version, I'll rework the code to internationalize it and work with you to localize it.

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
9. The Media Manager popup modal window showing additional filters for date and taxonomy terms. Also shows the enhanced Search Media box.

== Changelog ==

= 1.52 =
* Fix: **Corrected serious defect in `[mla_gallery]`** that incorrectly limited the number of items returned for non-paginated galleries.
* Fix: Eliminated "Strict Standards" messages issued from MLAModal functions.

= 1.51 =
* New: For `[mla_gallery]`, **twenty-five new `apply_filters` hooks** let you modify gallery output with PHP code in your theme or another plugin. More information in the "Other Notes" section here. A complete, working example is provided in the Settings/Media Library Assistant Documentation tab.
* New: **Attachment Metadata mapping**. Add or change values in the WordPress `_wp_attachment_metadata` array. For example, add GPS data to the `image_meta` array. Full details in the "Other Notes" section and in the Settings/Media Library Assistant Documentation tab.
* New: **GPS Metadata fields added**: LatitudeSDM, LatitudeSDD, LongitudeSDM, LongitudeSDD with leading "-" sign for southern and western values.
* New: A new `[mla_gallery]` parameter, `mla_page_parameter` supports **multiple paginated galleries on the same post/page**.
* New: On the Media/Assistant submenu, the Description field has been added to the Quick Edit area.
* New: Support for **"searchable category/tag metaboxes"** added to the ATTACHMENT DETAILS pane of the Media Manager Modal Window. This feature requires download and activation of the "Media Categories" plugin (by Eddie Moya).
* New: The **`[+custom:ALL_CUSTOM+]` pseudo value** lets you easily display the names and values of all custom fields associated with an item. You can use it in an `[mla_gallery]` or in a custom field mapping rule.
* Fix: Media Manager Modal Window support has been re-worked to avoid adding additional parameters to the Attachments object. This improves the handling of "drag & drop" uploading of new Media Library items.
* Fix: Sorting the Media/Assistant submenu table by a column which no longer exists does not cause database errors. The table sort reverts to the built-in default value. In addition, the dropdown list of sortable columns is now alphabetized.
* Fix: The "Inserted in" reporting with the "Base" option setting more reliably handles the case where one item filename is a subset of another filename. For example, file "abc.jpg" no longer matches "abcd.jpg".
* Fix: Handling of empty `query:` and `request:` substitution parameters has been restored to the pre-v1.50 logic.
* Fix: Custom field mapping for fields with array values is more reliably handled.
* Fix: Test elements in Content Templates returning array results more accurately test for substitution parameters having no value. For example, `([+iptc:2#020,array+][+iptc:2#025,array+])` will be empty unless **both** of the substitution parameters have values. 

= 1.50 =
* New: **PDF metadata support**, including the traditional Document Information Dictionary and the newer, more extensive XMP metadata. Include this information in your `[mla_gallery]` display and map it to standard fields, taxonomy terms and custom fields.
* New: **Content Templates**, which let you compose a value from multiple substitution parameters, combine text and data values, test for empty values and choose among two or more alternatives or suppress output entirely.
* New: **GPS Metadata** is extracted from EXIF metadata and converted to a variety of convenient formats. Include this information in your `[mla_gallery]` display and map it to standard fields, taxonomy terms and custom fields.
* New: You can **customize the position, label and title** of the Media/Assistant submenu screen. You can also **remove the default Media/Library** submenu screen.
* New: On the Settings/Media Library Assistant General tab you can **export and import ALL of your MLA settings** to a simple text file. Backup your settings before making big changes, move settings between sites, etc.
* New: On the Settings/Media Library Assistant General tab you can **set the depth and child handling for the filter-by taxonomy** on the Media/Assistant submenu.
* New: On the Media/Assistant submenu, **search, filter and sort values are retained when a "Filter by" value is set** by clicking on an active link in the table.
* New: On the Media/Assistant submenu, a **"Clear Filter by" button** allows you to clear the filter-by value while retaining other search, filter and sort values.
* Fix: On the Media/Assistant submenu, the **"Filter by" values are retained** when the Bulk Actions Apply button and the Filter button are clicked.
* Fix: On the Media/Assistant submenu, **bulk edit of `post_parent`, `post_author` and custom fields** now updates every item in the list, not just the first item.
* Fix: On the Media/Assistant submenu, "where-used" information for files whose name is a subset of another file's name has been corrected. For example, where-used values for file "abc.jpg" was previously reported in the results for file "bc.jpg" in certain cases.
* Fix: On the Media/Assistant submenu, "Empty Trash" button/function has been implemented.
* Fix: PHP Warnings are no longer issued when plugins such as "Codepress Admin Columns" use the HTTP "page" query variable in unexpected ways.
* Fix: Initialization functions now have a higher priority value, so they run later. This improves features such as discovery of custom taxonomies created in theme `functions.php` files that use the `init` hook.
* Fix: Hyperlinks to Document tab from other Settings/MediaLibrary Assistant tabs have been changed to more reliable absolute href values.

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

= 1.52 =
Corrects serious defect in [mla_gallery] that incorrectly limits the number of items returned for non-paginated galleries. One other fix.

== Other Notes ==

In this section, scroll down to see:

* Acknowledgements
* Highlights from the documentation, including new and unique plugin features

**NOTE:** Complete documentation is included in the Documentation tab on the Settings/Media Library Assistant admin screen and the drop-down "Help" content in the admin screens.

== Acknowledgements ==

I have used and learned much from the following books (among many):

* Professional WordPress; Design and Development, by Hal Stern, David Damstra and Brad Williams (Apr 5, 2010) ISBN-13: 978-0470560549
* Professional WordPress Plugin Development, by Brad Williams, Ozh Richard and Justin Tadlock (Mar 15, 2011) ISBN-13: 978-0470916223
* WordPress 3 Plugin Development Essentials, by Brian Bondari and Everett Griffiths (Mar 24, 2011) ISBN-13: 978-1849513524
* WordPress and Ajax, by Ronald Huereca (Jan 13, 2011) ISBN-13: 978-1451598650

Media Library Assistant includes many images drawn (with permission) from the [Crystal Project Icons](http://www.softicons.com/free-icons/system-icons/crystal-project-icons-by-everaldo-coelho), created by [Everaldo Coelho](http://www.everaldo.com), founder of [Yellowicon](http://www.yellowicon.com).

== MLA Gallery Shortcode ==

The `[mla_gallery]` shortcode is used in a post, page or custom post type to add a gallery of images and/or other Media Library items (such as PDF documents). MLA Gallery is a superset of the `[gallery]` shortcode in the WordPress core; it is compatible with `[gallery]` and provides many enhancements. These include:

* Full support for WordPress categories, tags and custom taxonomies. You can select items with any of the taxonomy parameters documented in the WP_Query class.
* Support for all post_mime_type values, not just images.
* Media Library items need not be "attached" to the post. You can build a gallery with any combination of items in the Library using taxonomy terms, custom fields and more.
* Control over the styles, markup and content of each gallery using the Style and Markup Templates documented below.
* Access to a wide range of content using the Attachment-specific and Field-level Substitution parameters documented below. A powerful Content Template facility lets you assemble content from multiple sources and vary the results depending on which data elements contain non-empty values for a given gallery item.
* Combine [mla_gallery] data selection with other popular gallery-generating plugins to get the best of both.

All of the options/parameters documented for the `[gallery]` shortcode are supported by the `[mla_gallery]` shortcode; you can find them in the WordPress Codex. Most of the parameters documented for the WP_Query class are also supported; see the WordPress Codex. Because the `[mla_gallery]` shortcode is designed to work with Media Library items, there are some parameter differences and extensions; full documentation comes on the Settings/Media Library Assistant screen of the plugin.

<h4>Gallery Display Style</h4>

Two parameters provide a way to apply custom style and markup templates to your `[mla_gallery]` display: These parameters replace the default style and/or markup templates with templates you define on the "MLA Gallery" tab of the Settings page.

Three parameters provide control over the placement, size and spacing of gallery items without requiring the use of custom Style templates.

<h4>Gallery Display Content</h4>

Twelve parameters provide pagination support and an easy way to control the contents of gallery items without requiring the use of custom Markup templates.  

<h4>NEW! MLA Gallery Filters and Actions (Hooks)</h4>

The `[mla_gallery]` shortcode supports a comprehensive set of filters and actions (twenty-five in all) that give you complete control over gallery composition from PHP code in your theme or in another plugin. An example of using the hooks from a simple, stand-alone plugin can be found in mla-hooks-example.php.txt.

The example code documents each hook with comments in the filter/action function that intercepts it. Generally, each part of the gallery supports three hooks: 1) a "values" hook, which lets you record or update the substitution values for that gallery part, 2) a "template" hook, which lets you record/update the template used to generate the HTML markup, and 3) a "parse" hook which lets you modify or replace the markup generated for a gallery part. There are also hooks for manipulating shortcode and data selection attributes and for inspecting query results. 

== Support for Alternative Gallery Output, e.g., pagination ==
The `[mla_gallery]` shortcode can be used to provide "Previous" and "Next" links that support moving among the individual items in a gallery or among gallery "pages". For example, if you have many items with a specific Att. Category or Att. Tag value you can build a single-image page with links to the previous/next item having that value. You can also build a page that shows a large gallery in groups, or "gallery pages", of ten items with links to the previous/next ten items or links to all of the gallery pages of items having that value.

The **"mla_output"** parameter determines the type of output the shortcode will return. You can choose from six values:

* `gallery`: The default value; returns the traditional gallery of image thumbnails, captions, etc.
* `next_link`: returns a link to the next gallery item.
* `previous_link`: returns a link to the previous gallery item.
* `next_page`: returns a link to the next "page" of gallery items.
* `previous_page`: returns a link to the previous "page" of gallery items.
* `paginate_links`: returns a link to gallery items at the start and end of the list and to pages around the current "gallery page" ( e.g.: &larr; Prev 1 … 3 4 5 6 7 … 9 Next &rarr; ).

== Support for Other Gallery-generating Shortcodes ==

The [mla_gallery] shortcode can be used in combination with other gallery-generating shortcodes to give you the data selection power of [mla_gallery] and the formatting/display power of popular alternatives such as the WordPress.com Jetpack Carousel and Tiled Galleries modules. Any shortcode that accepts "ids=" or a similar parameter listing the attachment ID values for the gallery can be used. For example, if you want to select images using the MLA Att. Category taxonomy but want to display a Jetpack "Tiled Mosaic" gallery, you can code:

`[mla_gallery attachment_category=vegetable tax_operator="NOT IN" mla_alt_shortcode=gallery type="rectangular" mla_alt_ids_name=include]`

== MLA Gallery Style and Markup Templates ==

The Style and Markup templates give you great flexibility for the content and format of each `[mla_gallery]`. You can define as many templates as you need.

Style templates provide gallery-specific CSS inline styles. Markup templates provide the HTML markup for 1) the beginning of the gallery, 2) the beginning of each row, 3) each gallery item, 4) the end of each row and 5) the end of the gallery. The attachment-specific markup parameters let you choose among most of the attachment fields, not just the caption.

The MLA Gallery tab on the Settings page lets you add, change and delete custom templates. The default templates are also displayed on this tab for easy reference.

<h3>Field-level Markup Substitution Parameters</h3>

Field-level substitution parameters let you access query arguments, custom fields, taxonomy terms, and attachment metadata for display in an MLA gallery. Formatting options let you control what happens when a field has multiple values.

There are nine prefix values for field-level data.

* `request`: The parameters defined in the `$_REQUEST` array; the "query strings" sent from the browser.
* `query`: The parameters defined in the `[mla_gallery]` shortcode.
* `custom`: WordPress custom fields, which you can define and populate on the Edit Media screen.
* `terms`: WordPress Category, tag or custom taxonomy terms.
* `meta`: The WordPress "attachment metadata", if any, embedded in the image/audio/video file. For this category, you can code any of the field names embedded in the `_wp_attachment_metadata` array.
* `pdf`: The Document Information Dictionary (D.I.D.)and XMP metadata, if any, embedded in a PDF file. For this category, you can code any of the nine D.I.D. entries (Title, Author, Subject, Keywords, Creator, Producer, CreationDate, ModDate, Trapped). For many documents there is also a rich collection of additional metadata stored in XMP Metadata Streams.
* `iptc`: The IPTC (International Press Telecommunications Council) metadata, if any, embedded in the image file.
* `exif`: The EXIF (EXchangeable Image File) metadata, if any, embedded in a JPEG DCT or TIFF Rev 6.0 image file.
* `template`: A Content Template, which lets you compose a value from multiple substitution parameters and test for empty values, choosing among two or more alternatives or suppressing output entirely. See the plugin documentation for details.

<h4>NEW! Metadata in PDF documents</h4>

Metadata in PDF documents comes from two sources. Early versions of the PDF specification defined a Document Information Dictionary (D.I.D.) containing up to nine (optional) fields: Title, Author, Subject, Keywords, Creator, Producer, CreationDate, ModDate and Trapped.</td>

More recent versions of the specification add a second source of metadata, Metadata Streams, holding data defined by the Extensible Metadata Platform (XMP) framework. XMP metadata varies from document to document but is often extensive. MLA provides access to this data in three ways:

1. If a D.I.D. field is not stored in the document, MLA will copy appropriate values from the XMP data into the empty field to populate it as often as possible. For example, the "creator" value(s) in the "dc" namespace ("dc.creator") might be copied to an empty "Author" field, or the "dc.subject" value(s) might be copied to an empty Keywords field.

2. Additional values in the "xmp", "xmpMM", "xmpRights", "xap", "xapMM", "dc", "pdf" and "pdfx" namespaces are copied up to the root level for easier access. For example, the "pdfx.SourceModified" value can be accessed as "SourceModified", without the "pdfx." portion of the compound name.

3. Other namespaces in the document are copied to arrays at the root level. For example, some documents contain information in the "photoshop" namespace, such as "photoshop.CaptionWriter" and "photoshop.AuthorsPosition". The native values of some fields, e.g., "dc.creator", can be an array.

A special "pseudo value", "ALL_PDF", returns a string representation of all the metadata. You can use this pseudo-value to examine the metadata in a document, find field names and see what values are present.

== NEW! Content Templates ==

Content Templates (templates) are one of the Field-level Markup Substitution Parameters, indicated by a prefix value ( `[+template: ... +]` ). Within a template you can have any combination of four elements:

* `String`: text and/or field-level substitution parameters, e.g., `[+template: Base File - [+base_file+] +]`
* `Conditional`: text and/or field-level substitution parameters that will be tested for missing values. Any field-level substitution parameter that is not valid, is empty or contains only whitespace will cause the entire conditional to be eliminated. Conditional elements are enclosed in parentheses. For example, `[+template: (ITPC Title: [+iptc:object-name+] ) +]`. If the IPTC field is missing or blank both it and the preceding "ITPC Title: " literal are eliminated.
* `Choice`: two or more alternatives from which the first valid, non-empty value will be taken. Choice elements are separated by vertical bars ("|"), e.g., `[+template: Summary: ([+caption+]|[+description+]|[+title+]) +]`
* `Template`: another template. There is no particular advantage to nesting templates, but it works.

The conditional and choice elements are the key to templates' power, particularly with custom fields and metadata such as ITPC and EXIF. With the conditional element you can combine literal text with a substitution value and eliminate the text if the value is missing. With the choice element you can specify multiple sources for a value and decide the order in which they are tested. In the choice example above the text "Description: " will always be used, followed by the attachment's caption (if present) or the description value or the literal "none" if both of the other values are missing.

Conditional, choice and template elements can be nested as needed. For example, a conditional element can have a choice element within it or a choice alternative could include a conditional. Here's an example:

`[+template: Terms: (([+terms:category+], [+terms:post_tag+])|[+ terms: category +]|[+terms:post_tag +]|none)+]`

This template has a String, "Terms: " and a Conditional, "(([+terms: … none)". This Conditional separates the "Terms: " literal from the first alternative in the Choice. Within the Conditional is a Choice having four alternatives. The first alternative is a Conditional, which will be empty unless both categories and tags are present.  The second and third alternatives handle the cases where one of the two taxonomies has terms, and the final alternative is used when neither categories nor tags are present.

==Custom Field and Attachment Metadata Processing Options==

On the Custom Fields tab of the Settings screen you can define the rules for mapping several types of file and image metadata to WordPress custom fields. Custom field mapping can be applied automatically when an attachment is added to the Media Library. You can refresh the mapping for <strong><em>ALL</em></strong> attachments using the command buttons on the screen. You can selectively apply the mapping in the bulk edit area of the Media/Assistant submenu table and/or on the Edit Media screen for a single attachment. The advantages of mapping metadata to custom fields are:

* You can add the data to an [mla_gallery] with a field-level markup substitution parameter. For example, add the image dimensions or a list of all the intermediate sizes available for the image.

* You can add the data as a sortable column to the Media/Assistant submenu table. For example, you can find all the "orphans" in your library by adding "reference_issues" and then sorting by that column.

**NEW! Adding or changing Attachment Metadata**

WordPress stores an array of information for image, audio and video items in the "_wp_attachment_metadata" custom field. Plugins such as "Fullscreen Galleria" also use this field to store information like GPS coordinates. Many of the array elements, such as the "sizes" array for images, are in turn arrays of more detailed values. <strong>Compound names</strong> are used to access elements within arrays, e.g., &quot;<strong>sizes.thumbnail.file</strong>&quot; is used to specify the file name for the thumbnail version of an image.

By coding the "meta:" prefix in the Field Title column of a field mapping rule you can add to or update this data, from any of the data sources listed below. You can use a **Content Template** to compose a value from multiple substitution parameters and test for empty values, choosing among two or more alternatives or suppressing the mapping altogether for a given item.

Let's say, for example, that you want to add GPS coordinates to the "image_meta" element of the Attachment Metadata.

1. Open the Settings/Media Library Assistant submenu and select the Custom Fields tab. Scroll down to the "Add a new Field and Mapping Rule" section.

2. In the Field Title text box, enter "meta:image_meta.latitude". The rule will store its results in the "latitude" element of the "image_meta" array within the Attachment Metadata field.

3. In the Data Source dropdown, select "-- Template (see below) --". In the text box below the dropdown, enter "([+exif:GPS.LatitudeSDD+])". This is a Content Template that extracts the "GPS.LatitudeSDD" value from the EXIF data embedded in an image file. The parentheses test the result to eliminate empty values; only non-empty values will be mapped into the image_meta array.

4. Set the other parts of the rule as needed. You can select "Keep" if some of your items already have this information or "Replace" to update all items. "Native" and "Text" are appropriate for this example. You can check "Delete NULL values" to remove any existing, empty values for this element. The "MLA Column", "Quick Edit" and "Bulk Edit" checkboxes have no meaning for Attachment Metadata elements and can be left blank; they will be ignored if checked.

If you are creating an IPTC/EXIF mapping rule the details are a bit different, but the capability is equivalent.

<h4>Data sources for custom field mapping</h4>

A complete list of the <strong>42 data source elements</strong> is on the plugin's Settings page. In addition, you can map any of the fields found in the attachment's WordPress metadata array to a custom field. 

**NEW!** You can use a template to compose a custom field from alternative data sources, depending on which fields are populated for a given attachment. For example, "`[+pdf:Keywords+]|[+iptc:2#025+]|none`" will use the PDF Keywords field, if populated, then the IPTC keywords field, if populated, or the literal "none" if neither field contains a value. With this template you can get keywords from both PDF documents and images in a single field.

==IPTC &amp; EXIF Processing Options==

Some image file formats such as JPEG DCT or TIFF Rev 6.0 support the addition of data about the image, or <em>metadata</em>, in the image file. Many popular image processing programs such as Adobe PhotoShop allow you to populate metadata fields with information such as a copyright notice, caption, the image author and keywords that categorize the image in a larger collection. WordPress uses some of this information to populate the Title, Slug and Description fields when you add an image to the Media Library.

The Media Library Assistant has powerful tools for copying image metadata to:

* the WordPress standard fields, e.g., the Caption
* taxonomy terms, e.g., in categories, tags or custom taxonomies
* WordPress custom fields

You can define the rules for mapping metadata on the "IPTC/EXIF" tab of the Settings page. You can choose to automatically apply the rules when new media are added to the Library (or not). You can click the "Map IPTC/EXIF metadata" button on the Edit Media/Edit Single Item screen or in the bulk edit area to selectively apply the rules to one or more images. You can click the "Map All Attachments Now" to apply the rules to <strong>all of the images in your library</strong> at one time.

You can use a template to compose a value from multiple data sources, e.g., `Taken with [+meta:camera+] at [+dimensions+] using ISO [+exif:ISOSpeedRatings,single+] and [+exif:ExposureTime+] exposure time`.

You can use a template to compose a value from alternative data sources, depending on which fields are populated for a given attachment. For example, `[+iptc:2#020+]|[+iptc:2#025+]|none` will use the IPTC supplemental-category field, if populated, then the IPTC keywords field, if populated, or the literal "none" if neither IPTC field contains a value.

Using a template in the "Standard field mapping" or "Custom field mapping" tables will yield a text result. For example, multiple IPTC keywords would be converted into a comma-delimited list as a string. In the "Taxonomy term mapping" table the template will deliver an array result if the fields inside the template have multiple values. For example, you can code `[+iptc:2#020+][+iptc:2#025+]` to store each of the IPTC supplemental-category <em><strong>and</strong></em> keywords values (there is no "|" in the template) as a separate taxonomy term.
