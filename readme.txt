=== Plugin Name ===
Contributors: dglingren
Donate link: http://fairtradejudaica.org/make-a-difference/donate/
Tags: attachment, attachments, documents, gallery, image, images, media, library, media library, media-tags, media tags, tags, media categories, categories, IPTC, EXIF, GPS, PDF, meta, metadata, photo, photos, photograph, photographs, photoblog, photo albums, lightroom, photoshop, MIME, mime-type, icon, upload, file extensions
Requires at least: 3.3
Tested up to: 3.7.1
Stable tag: 1.60
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

= 1.60 =
* New: **`[mla_tag_cloud]` shortcode**. Enhanced tag cloud for any taxonomy, with "grid" format, full Display Content and custom template support along the lines of `[mla_gallery]`. Paginated clouds are also supported. Full details in the [Other Notes section](http://wordpress.org/extend/plugins/media-library-assistant/other_notes/ "Click here, then scroll down") and on the Settings/Media Library Assistant Documentation tab.
* New: **`MLA Text Widget`**. You can add widgets containing `[mla_gallery]`, `[mla_tag_cloud]` or **any shortcode** to the sidebars on your site. More information in the [Other Notes section](http://wordpress.org/extend/plugins/media-library-assistant/other_notes/ "Click here, then scroll down").
* New: For `[mla_gallery]`, **new 'page_ID' and 'page_url'** substitution parameters for easier 'mla_link_href' composition.
* New: For `[mla_gallery]`, **link=span and link=none** let you replace the hyperlink enclosing each gallery thumbnail with non-link content. 
* New: For `[mla_gallery]`, **default Style and Markup templates conform to WordPress 3.7 conventions**. The "orientation" attribute has been added to Attachment-specific substitution parameters and as a Data Source for custom field mapping. 
* New: **Enhanced IPTC/EXIF Taxonomy term mapping.** You can now separate multiple terms contained in a single IPTC/EXIF value by specifying one or more delimiters. For example, specify ";" to separate values like "tag1; tag2" into separate terms.
* New: For `[mla_gallery]`, **Support for Other Gallery-generating Shortcodes now includes the "enclosing shortcode" form**. You can pass content to the alternate shortcode by coding something like `[mla_gallery ids="1230,1227" mla_alt_shortcode=fsg_link mla_alt_ids_name=include class=button]View the gallery[/mla_gallery]`. New filters are provided for inspecting/modifying the content.
* Fix: On the Media/Assistant submenu, **where-used errors are no longer returned for** XHTML-style self-closing shortcodes, i.e., ending with "/]", and for tax_query and meta_query parameters containing substitution parameters.
* Fix: **Hiding the Media/Library submenu is now more reliable**. The Media/Library submenu is hidden with a CSS style, but is still available for use by plugins such as Enable Media Replace. The Media/Assistant submenu is automatically moved up to the top of the submenu list. Attempts to display the Media/Library submenu, e.g., after deleting an item from the Edit Media screen, are redirected to the Media/Assistant submenu.
* Fix: For the Settings/Media Library Assistant "Custom Fields" and "IPTC/EXIF" tabs, the **"Add Rule/Add Field and Map All Attachments" buttons now map values correctly**. In previous MLA versions, the rule was added but the attachment values were not mapped.
* Fix: For `[mla_gallery]`, **array values are now accepted in [+request:+] substitution parameters**, and the `,export` option is supported as well. Array values can be passed from the URL or HTML forms to parameters that accept a list of values, such as taxonomy queries.

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

= 1.60 =
New [mla_tag_cloud] shortcode and shortcode-enabled MLA Text Widget. Five other enhancements, four fixes.

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

== NEW! MLA Tag Cloud Shortcode ==

The `[mla_tag_cloud]` shortcode displays a list of taxonomy terms in what is called a 'tag cloud', where the size of each term is determined by how many times that particular term has been assigned to Media Library items (attachments). The cloud works with both flat (e.g., Att. Tags) and hierarchical taxonomies (e.g., Att. Categories) MLA Tag Cloud provides many enhancements to the basic "cloud" display. These include:

* Full support for WordPress categories, tags and custom taxonomies. You can select from any taxonomy or list of taxonomies defined in your site.
* Several display formats, including "flat","list" and "grid" (modeled after the `[mla_gallery]` display).
* Complete support for paginated clouds; display hundreds or thousands of terms in managable groups.
* Control over the styles, markup and content of each cloud using Style and Markup Templates. You can customize the "list" and "grid" formats to suit any need.
* Access to a wide range of content using the term-specific and Field-level Substitution parameters. A powerful Content Template facility lets you assemble content from multiple sources and vary the results depending on which data elements contain non-empty values for a given term.
* Display Style and Display Content parameters for easy customization of the cloud display and the destination of the links behind each term.
* A comprehensive set of filters gives you access to each step of the cloud generation process from PHP code in your theme or other plugins.

Many of the `[mla_tag_cloud]` concepts and shortcode parameters are modeled after the `[mla_gallery]` shortcode, so the learning curve is short. Differences and parameters unique to the cloud are given in the sections below.

<h4>Tag Cloud Output Formats</h4>

The traditional tag cloud output is a "heat map" of term names where larger names are associated with more attachments than smaller names. The terms' display format is determined by the "mla_output" parameter:

* `flat` - Returns a sequence of hypelink tags without further HTML markup. The "separator" parameter content (default, one newline character) is inserted between each hyperlink.

* `list` - Returns hyperlinks enclosed by one of the HTML list tags; unordered (&lt;ul&gt;&lt;/ul&gt;), ordered (&lt;ol&gt;&lt;/ol&gt;) or definitions (&lt;dl&gt;&lt;/dl&gt;), which allow for each term to have a "caption". The "itemtag", "termtag" and "captiontag" parameters customize the list markup.

* `grid` - Modeled on the galleries produced by `[mla_gallery]`; a rectangular display with rows and columns. The tag parameters listed above, the "columns" parameter and the Display Style parameters customize the display.

* `array` - Returns a PHP array of cloud hyperlinks. This output format is not available through the shortcode; it is allowed when the `MLAShortcodes::mla_tag_cloud()` function is called directly from your theme or plugin PHP code.

The "list" and "grid" formats can be extensively customized by using custom Style and Markup Templates. The `[mla_tag_cloud]` shortcode also supports pagination with "previous_link", "current_link", "next_link", "previous_page", "next_page" and "paginate_links" formats. These are essentially the same as those for the `[mla_gallery]` shortcode.

<h4>Tag Cloud Item Parameters</h4>

Each item in the tag cloud comprises a term name of varying size, a hyperlink surrounding the term name and a "title" attribute (Rollover Text) displayed when the cursor hovers over the term name hyperlink. The following parameters customize item content and markup:

* `smallest`
* `largest`
* `unit`
* `separator`
* `single_text`
* `multiple_text`
* `link`

The Item parameters are an easy way to customize the content and markup for each cloud item. For the list and grid formats you can also use the Tag Cloud Display Content parameters and/or Style and Markup Templates for even greater flexibility.

<h4>Tag Cloud Item Link</h4>

The Link parameter specifies the target and type of link from the tag cloud term/item to the item's archive page, edit page or other destination. You can also specify a non-hyperlink treatment for each item.

* `view` - Link to the term's "archive page"
* `edit` - Link to the term's "edit tag/category" admin screen
* `(mla_link_href)` - Link to a custom destination, typically another post/page
* `span` - Substitutes a `&lt;span&gt;&lt;/span&gt;` tag for the hyperlink tag
* `none` - Eliminates the hyperlink tag

Using the "mla_link_href" parameter to completely replace the link destination URL is a common and useful choice. With this parameter us can use the tag cloud to select a term and then go to another post/page that uses that selection as part of an `[mla_gallery]` shortcode.

<h4>Tag Cloud Display Style (list and grid)</h4>

These parameters provide a way to apply custom style and markup templates to your `[mla_tag_cloud]` display.

* `mla_style` - replaces the default style template
* `mla_markup` - replaces the default markup template
* `mla_float` - specifies the CSS float attribute of the ".tag-cloud-item" style
* `mla_margin` - specifies the CSS margin property of the ".tag-cloud-item" style
* `mla_itemwidth` - specifies the CSS width attribute of the ".tag-cloud-item" style

<h4>Tag Cloud Display Content</h4>

Eight parameters provide an easy way to control the contents of tag cloud items without requiring the use of custom Markup templates.  

* `mla_link_attributes`
* `mla_link_class`
* `mla_link_href`
* `mla_link_text`
* `mla_nolink_text`
* `mla_rollover_text`
* `mla_caption`
* `mla_target`

<h4>Tag Cloud Data Selection Parameters</h4>

The data selection parameters specify which taxonomy (or taxonomies) the terms are taken from, which terms are returned for the cloud and the order in which the terms are returned:

* `taxonomy` - The taxonomy or taxonomies to retrieve terms from
* `include` - A comma-separated list of term ids to include
* `exclude` - A comma-separated list of term ids to exclude from the returned values
* `parent` - Get direct children of this term
* `minimum` - The minimum number of attachments required
* `number` - The maximum number of "most popular" terms to return
* `orderby` - The sort order of the retrieved terms
* `order` - "ASC" or "DESC"
* `preserve_case` - Preserve upper- and lower-case distinctions when sorting by name
* `limit` - The number of terms to return
* `offset` - The number of terms to skip before returning the results

<h4>Tag Cloud Substitution Parameters</h4>

Style and Markup templates give you great flexibility for the content and format of each [mla_tag_cloud] when you use the "list" and "grid" output formats. The following <strong>field-level substitution parameters</strong> are available in the Style template and any of the Markup template sections:

* `request` - The parameters defined in the `$_REQUEST` array; the "query strings" sent from the browser.
* `query` - The parameters defined in the `[mla_tag_cloud]` shortcode.
* `template` - A Content Template, which lets you compose a value from multiple substitution parameters and test for empty values, choosing among two or more alternatives or suppressing output entirely.

Twenty-eight tag cloud substitution parameters are available for the <strong>Style template</strong>. Tag cloud substitution parameters for the <strong>Markup template</strong> are available in all of the template sections. They include all of the parameters defined above (for the Style template) and three additional markup-specific parameters. Tag cloud <strong>item-specific substitution parameters</strong> for the Markup template are available in the "Item" section of the template. They include all of the parameters defined above (for the Style and Markup templates) and twenty-five additional item-specific parameters.

<h4>Tag Cloud Pagination Parameters</h4>

If you have a large number of terms in your cloud taxonomy you may want to paginate the cloud display, i.e., divide the cloud into two or more pages of a reasonable size. Pagination support for `[mla_tag_cloud]` is modeled on similar functions for`[mla_gallery]`, and you can find more explaination of the ideas behind pagination in the <strong>Support for Alternative Gallery Output, e.g., Pagination</strong> documentation section. Five parameters are supplied for this purpose:

* `limit` - the maximum number of terms to display in one cloud "page"
* `offset` - the number of terms to skip over before starting the current cloud page
* `mla_page_parameter` - the name of the parameter containing the current page number
* `mla_cloud_current` - the default current cloud page number
* `term_id` - the id of the current term within the cloud

The `[mla_tag_cloud]` shortcode can be used to provide "Previous" and "Next" links that support moving among the individual items in a cloud or among cloud "pages". For example, if you have many terms in your Att. Category or Att. Tag taxonomies you can build a term-specific `[mla_gallery]` page with links to the previous/next term in the taxonomy (a complete pagination example is included below). You can also build a page that shows a large taxonomy in groups, or "cloud pages", of ten terms with links to the previous/next ten terms or links to all of the cloud pages of terms in the taxonomy.

The <strong>"mla_output"</strong> parameter determines the type of output the shortcode will return. For pagination output, you can choose from six values: 

* `next_link` - returns a link to the next cloud item, based on the "term_id" parameter value
* `current_link` - returns a link to the current cloud item, based on the "term_id" parameter value
* `previous_link` - returns a link to the previous cloud item, based on the "term_id" parameter value
* `next_page` - returns a link to the next "page" of cloud items, based on the "mla_cloud_current" parameter value
* `previous_page` - returns a link to the previous "page" of cloud items, based on the "mla_cloud_current" parameter value
* `paginate_links` - returns a link to cloud items at the start and end of the list and to pages around the current "cloud page" ( e.g.: &laquo; Previous 1 … 3 4 5 6 7 … 9 Next &raquo; ), based on the "mla_cloud_current" parameter value

The best way to understand cloud pagination is by example, as in the next section below.

<h4>Tag Cloud Pagination Example</h4>

This section takes you through several of the `[mla_tag_cloud]` features, step by step. Let's start with a very simple cloud showing all of the terms in the "Att. Category" taxonomy:

`
[mla_tag_cloud taxonomy=attachment_category number=0]
`

The "number=0" parameter overrides the default maximum of 45 terms, showing all of the terms in the taxonomy. Let's paginate the cloud and limit the terms display to ten terms per "page":

`
[mla_tag_cloud taxonomy=attachment_category number=0 limit=10]
[mla_tag_cloud taxonomy=attachment_category number=0 limit=10  mla_output="paginate_links,prev_next"]
`

The "limit=10" parameter (on <strong>both</strong> shortcodes) limits the term display to ten terms. The second `[mla_tag_cloud]` shortcode, adding the 'mla_output="paginate_links,prev_next"' parameter, displays a line of pagination links below the cloud page. Coordination between the two shortcodes is automatic, using the "mla_cloud_current" parameter added to the URLs by the shortcode.

Now we'll make the cloud a convenient way to control a term-specific `[mla_gallery]`. The next step uses the "mla_link_href" parameter to change the link destination of each cloud term, returning to the current page with the term id of the selected term. We also add the "mla_cloud_current" parameter to each of these new links, so the tag cloud page is retained when a term is selected:

`
[mla_tag_cloud taxonomy=attachment_category number=0 limit=10 mla_link_href="{+page_url+}?current_id={+term_id+}&amp;amp;mla_cloud_current={+request:mla_cloud_current+}]
[mla_tag_cloud taxonomy=attachment_category number=0 limit=10  mla_output="paginate_links,prev_next"]
`

The "&amp;amp;" before the "mla_cloud_current" parameter is required to get by the WordPress Visual Editor. The "{+request:mla_cloud_current+}" value copies the current page number from the URL ($_REQUEST array) and adds it to each term's link. Now, let's use the "current_id={+term_id+}" information in the link to compose a term-specific `[mla_gallery]`: 

`
[mla_tag_cloud taxonomy=attachment_category number=0 limit=10 mla_link_href="{+page_url+}?current_id={+term_id+}&amp;amp;mla_cloud_current={+request:mla_cloud_current+}]<br />
[mla_tag_cloud taxonomy=attachment_category number=0 limit=10  mla_output="paginate_links,prev_next"]

[mla_gallery post_mime_type=all tax_query="array ( 0 => array ( 'taxonomy' => 'attachment_category', 'field' => 'id', 'terms' => array( {+request:current_id+} ), 'include_children' => false ) )" mla_caption="{+title+}" columns=5 size=icon link=file]
`

The most complicated part of the new shortcode is the "tax_query" parameter, which we're using to ensure that the gallery items displayed match the count displayed for each term in the tag cloud. The tag cloud count does not contain items associated with any "child terms", or sub-categories, of the cloud item. To match this count we must use the "include_children=false" and "field=id" parameters of the "tax_query".

We can easily paginate the term-specific gallery by adding a second `[mla_gallery]` shortcode and a "posts_per_page" parameter to both shortcodes:

`
[mla_tag_cloud taxonomy=attachment_category number=0 limit=10 mla_link_href="{+page_url+}?current_id={+term_id+}&amp;amp;mla_cloud_current={+request:mla_cloud_current+}]<br />
[mla_tag_cloud taxonomy=attachment_category number=0 limit=10  mla_output="paginate_links,prev_next"]

[mla_gallery post_mime_type=all tax_query="array ( 0 => array ( 'taxonomy' => 'attachment_category', 'field' => 'id', 'terms' => array( {+request:current_id+} ), 'include_children' => false ) )" mla_caption="{+title+}" columns=5 posts_per_page=5 size=icon link=file]

[mla_gallery post_mime_type=all tax_query="array ( 0 => array ( 'taxonomy' => 'attachment_category', 'field' => 'id', 'terms' => array( {+request:current_id+} ), 'include_children' => false ) )" columns=5 posts_per_page=5 mla_output="paginate_links,prev_next"]
`

The pagination controls for the tag cloud and the gallery operate independently because by default they use different names for their respective "_current" page parameters. Our page now has a lot of functionality without requiring any WordPress templates or PHP code.

For extra credit, let's add some more navigation options to the page. We'll build previous, current and next term links at the bottom of the page. These are enclosed in an HTML table so they all appear on one line of the page. Here is just the additional content; the table of three link navigation controls:

`
&lt;table width=99%&gt;&lt;tr&gt;
&lt;td width=33% style="text-align: left"&gt;[mla_tag_cloud taxonomy=attachment_category number=0 term_id="{+request:current_id+}" mla_output="previous_link" smallest=12 largest=12 mla_link_href="{+page_url+}?current_id={+term_id+}" mla_link_text="Previous: {+name+}"]&lt;/td&gt;

&lt;td width=33% style="text-align: center; font-weight: bold:"&gt;[mla_tag_cloud taxonomy=attachment_category number=0 term_id="{+request:current_id+}" mla_output=current_link smallest=12 largest=12 mla_link_text="Current: {+name+}" link=span]&lt;/td&gt;

&lt;td width=33% style="text-align: right"&gt;[mla_tag_cloud taxonomy=attachment_category number=0 term_id="{+request:current_id+}" mla_output="next_link" smallest=12 largest=12 mla_link_href="{+page_url+}?current_id={+term_id+}" mla_link_text="Next: {+name+}"]&lt;/td&gt;
&lt;/tr&gt;&lt;/table&gt;
`

The "smallest=12" and "largest=12" parameters make "font-size" the same for all of the term names regardless of how many items are associated with the term. The "mla_link_text" parameters add labels to each of the three navigation links. Finally, the "link=span" parameter in the middle ("mla_output=current_link") shortcode removes the hyperlink behind the term name, since it would just take you back to the page you're already on.

<h4>MLA Tag Cloud Filters (Hooks)</h4>

The `[mla_tag_cloud]` shortcode supports a comprehensive set of filters that give you complete control over cloud composition from PHP code in your theme or in another plugin. An example of using the hooks from a simple, stand-alone plugin can be found in the MLA `/examples` directory.

The example code documents each hook with comments in the filter/action function that intercepts each hook. Generally, each part of the gallery supports three hooks: 1) a "<strong>values</strong>" hook, which lets you record or update the substitution values for that gallery part, 2) a "<strong>template</strong>" hook, which lets you record/update the template used to generate the HTML markup, and 3) a "<strong>parse</strong>" hook which lets you modify or replace the markup generated for a gallery part. Hooks are also provided to inspect/modify the shortcode attributes and the array of tag objects returned by the data selection function.

== Content Templates ==

Content Templates (templates) are one of the Field-level Markup Substitution Parameters, indicated by a prefix value ( `[+template: ... +]` ). Within a template you can have any combination of four elements:

* `String`: text and/or field-level substitution parameters, e.g., `[+template: Base File - [+base_file+] +]`
* `Conditional`: text and/or field-level substitution parameters that will be tested for missing values. Any field-level substitution parameter that is not valid, is empty or contains only whitespace will cause the entire conditional to be eliminated. Conditional elements are enclosed in parentheses. For example, `[+template: (ITPC Title: [+iptc:object-name+] ) +]`. If the IPTC field is missing or blank both it and the preceding "ITPC Title: " literal are eliminated.
* `Choice`: two or more alternatives from which the first valid, non-empty value will be taken. Choice elements are separated by vertical bars ("|"), e.g., `[+template: Summary: ([+caption+]|[+description+]|[+title+]) +]`
* `Template`: another template. There is no particular advantage to nesting templates, but it works.

The conditional and choice elements are the key to templates' power, particularly with custom fields and metadata such as ITPC and EXIF. With the conditional element you can combine literal text with a substitution value and eliminate the text if the value is missing. With the choice element you can specify multiple sources for a value and decide the order in which they are tested. In the choice example above the text "Description: " will always be used, followed by the attachment's caption (if present) or the description value or the literal "none" if both of the other values are missing.

Conditional, choice and template elements can be nested as needed. For example, a conditional element can have a choice element within it or a choice alternative could include a conditional. Here's an example:

`[+template: Terms: (([+terms:category+], [+terms:post_tag+])|[+ terms: category +]|[+terms:post_tag +]|none)+]`

This template has a String, "Terms: " and a Conditional, "(([+terms: … none)". This Conditional separates the "Terms: " literal from the first alternative in the Choice. Within the Conditional is a Choice having four alternatives. The first alternative is a Conditional, which will be empty unless both categories and tags are present.  The second and third alternatives handle the cases where one of the two taxonomies has terms, and the final alternative is used when neither categories nor tags are present.

== The MLA Text Widget ==

The MLA Text Widget lets you add content such as <code>[mla_gallery]</code> and <code>[mla_tag_cloud]</code> displays to your site's sidebars. It is an easy way to add slide shows and navigation features to all your pages. The MLA Text Widget is based on the WordPress Text widget, but adds the ability to include <strong>any</strong> shortcode to widget content. To use the MLA Text Widget:

1. Go to the Appearance/Widgets Administration screen
1. Open the sidebar, footer, or Theme section to which you wish to add the Text Widget
1. Find the Text Widget in the list of Widgets
1. Click and drag the Widget to the spot you wish it to appear

To open and edit the MLA Text Widget: 

1. Click the down arrow to the right of the MLA Text Widget title
1. Set the MLA Text Widget Title (optional)
1. Add the text or HTML code to the box or edit what is currently there
1. If desired, choose the option to Automatically add paragraphs to wrap each block of text in an HTML paragraph tag
1. Click Save to save the Widget
1. Click Close to close the Widget
1. Switch tabs in your browser and review the results; make changes if necessary

To add an <code>[mla_gallery]</code> or <code>[mla_tag_cloud]</code> shortcode to your widget, simply enter the shortcode name and parameters just as you would in the body of a post or page. Aside from the usually more limited area devoted to displaying the widget content, there are no differences in the way shortcodes are processed in the MLA Widget. Also, there is nothing special about the two MLA shortcodes; <strong>any</strong> shortcode can be added to the MLA Widget.
