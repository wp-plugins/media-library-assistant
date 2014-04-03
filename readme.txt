=== Plugin Name ===
Contributors: dglingren
Donate link: http://fairtradejudaica.org/make-a-difference/donate/
Tags: attachment, attachments, documents, gallery, image, images, media, library, media library, tag cloud, media-tags, media tags, tags, media categories, categories, IPTC, EXIF, GPS, PDF, meta, metadata, photo, photos, photograph, photographs, photoblog, photo albums, lightroom, photoshop, MIME, mime-type, icon, upload, file extensions
Requires at least: 3.3
Tested up to: 3.9
Stable tag: 1.81
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

You can add support for many attachment metadata values such as file size by visiting the Custom Fields tab on the Settings page. There you can define a rule that maps the data to a WordPress custom field and check the "MLA Column" box to make that field a sortable column in the Media/Assistant submenu table. You can also use the field in your `[mla_gallery]` shortcodes.

= How can I use Categories, Tags and custom taxonomies to select images for display in my posts and pages? =

The powerful `[mla_gallery]` shortcode supports almost all of the query flexibility provided by the WP_Query class. You can find complete documentation in the Settings/Media Library Assistant Documentation tab.

= Can I use `[mla_gallery]` for attachments other than images? =

Yes! The `[mla_gallery]` shortcode supports all MIME types when you add the post_mime_type parameter to your query. You can build a gallery of your PDF documents, plain text files and other attachments. You can mix images and other MIME types in the same gallery, too.

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

Not yet, but all of the internationalization work in the plugin source code has been completed and there is a Portable Object Template (.POT) available in the "/languages" directory. I don't have working knowledge of anything but English, but if you'd like to volunteer to produce a translation, I would be delighted to work with you to make it happen. Have a look at the "MLA Internationalization Guide.php" file in the languages directory and get in touch.

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
9. The Media Manager popup modal window showing additional filters for date and taxonomy terms. Also shows the enhanced Search Media box and the **full-function taxonomy support in the ATTACHMENT DETAILS area**.

== Changelog ==

= 1.81 =
* Important Fix: A **serious defect in the Media Libarary Modal Window has been corrected.** The defect caused drag & drop file uploading to fail under many circumstances.
* New: For `[mla_tag_cloud]`, **the "ids" parameter has been added** to support item-specific term clouds, i.e., a cloud containing only those terms assigned to one (or more) items.
* New: A **"single image with tag list"** page template has been added to the **Mla Child Theme** in the /examples directory.
* Fix: A Load Text Domain function has been added to the /examples/twentytwelve-mla child theme.
* Fix: If the Settings/Media Library Assistant General tab "Page Title" and/or "Menu Title" fields are empty, the default values are now used, including translated values if applicable.
* Fix: Failure to load translation file from the /plugins/media-library-assistant/languages directory has been fixed. Note that the translation file must include the plugin slug, e.g., media-library-assistant-en_US.mo
* Fix: PHP (5.4.x) Strict Standards warning for MLAData::mla_get_attachment_by_id() has been resolved.
* Fix: For `[mla_gallery]`, `$wp_filter` debug display with `mla_debug=true` is more reliable.

= 1.80 =
* New: For the Media Manager Modal Window, **Native support for "checkbox-style" and "tag hint-style" taxonomy meta boxes** is available. See the [Other Notes section](http://wordpress.org/extend/plugins/media-library-assistant/other_notes/ "Click here, then scroll down") section for more details.
* New: **For flat taxonomies**, e.g., "Tags" or "Att. Tags", **a "checkbox-style" meta box** is available. See the [Other Notes section](http://wordpress.org/extend/plugins/media-library-assistant/other_notes/ "Click here, then scroll down") section for more details.
* New: An option (General tab) is provided to **disable term-specific counts** in the Attachments column of the taxonomy edit screens.
* New: An option (General tab) is provided to **suppress the MLA-specific metaboxes on the Media/Edit Media screen**. This removes the "Parent Info", "Menu Order", "Attachment Metadata"  and the four "where-used" meta boxes.
* New: Bulk edit area now includes **Title, Caption, Description, ALT Text, Comments and Pings** fields. Text fields may contain a Content Template, allowing conditional replacement of the field value.
* New: **A numeric value in the Media/Assistant search box** will do a text-based search in addition to the post or parent ID search. This eliminates the requirement to add quotes to the value to force a text-based search. You can still add quotes to avoid the parent/post ID part of the search, or avoid the text-based search by unchecking all of the search field boxes.
* New: A new option, **Icon Size**, sets the thumbnail/icon size on the Media/Assistant submenu table. Find it in the Table Defaults section of the Settings/Media Library Assistant General tab.
* New: More debugging information displayed/logged when Media/Assistant search box begins with ">|<" or <|>".
* New: For `[mla_gallery]`, the **Data sources for custom field mapping** are now available as Attachment-specific substitution parameters. A new "commas" option allows better formatting of numeric data source values.
* New: For `[mla_gallery]`, **mla_gallery_raw_attributes filter** allows access to the shortcode parameters before they pass through the logic to handle `mla_page_parameter` and "request:" prefix processing. The `mla-hooks-example.php.txt` example has been updated as well.
* New: For `[mla_gallery]`, **mla_paginate_rows** allows you to avoid redundant database queries just to create pagination controls, if you have some other way of knowing how many items a gallery contains.
* New: For `[mla_gallery]`, **WP_Query caching parameters** allow you to avoid additional database queries just to fill the post, metadata and/or term cache if your application does not require them.
* New: For `[mla_tag_cloud]`, **post_mime_type** allows you to filter the tag cloud counts by MIME type so they will match the results delivered by `[mla_gallery]` and other gallery shortcodes.
* New: For `[mla_tag_cloud]`, a new `no_count` parameter enables or disables the computation of term-specific attachment counts.
* New: For `[mla_gallery]`, the **HTML5 figure, div and figcaption** tags are used for themes that register support for HTML5.
* New: For `[mla_gallery]`, a new `mla_style` setting ("theme") lets the theme control use of the MLA style template by hooking the `use_default_gallery_style` filter.
* Fix: The term-specific counts computation in the Attachments column of the taxonomy edit screens is significantly more efficient.
* Fix: Removed an intermittant PHP Warning message for logged-in users without the "upload_files" capability.
* Fix: The `[mla_tag_cloud]` templates are no longer offered in the default `[mla_gallery]` template dropdown list.
* Fix: Default descriptions for `mla_upload_mime` option values are no longer stored in the options table, saving space.
* Fix: **Support for the "Media Categories" plugin (by Eddie Moya) is no longer required and has been removed.**
* Fix: The where-used term **"BAD PARENT"** has been replaced with the less severe **"UNUSED"** to more clearly indicate that the item has a valid parent but is not used for anything in the parent post/page.
* Fix: The **"Inserted in" reporting with the "Base" option** setting explicitly tests for all registered intermediate sizes, giving more precise results.
* Fix: Peaceful co-existance with **Relevanssi - A Better Search, v3.2+ by Mikko Saari**, using a filter provided by that plugin to disable interference with the Media/Assistant submenu search box and the `[mla_gallery]` shortcode.
* Fix: Removed support for the ancient, bug-ridden and unused `[mla_attachment_list]` shortcode.

= 1.71 =
* New: **Searchable Category meta boxes** have been added to the Media/Edit Media screen. Click the "$ Search" link at the bottom of the meta box and type all or part of a term in the textbox to filter the display.
* New: **Attachment Display Settings** options added to the Settings/Media Library Assistant General tab allowing a convenient way to manage the WordPress 'image_default_link_type', 'image_default_align', and 'image_default_size' options.
* Fix: Drag and Drop re-ordering of WordPress `[gallery]` items is now preserved in the Media Manager "Edit Gallery" Modal Window.
* Fix: Updated data is now properly displayed after Quick Edit changes it, e.g., ALT Text.
* Fix: Some internationalization/initialization logic moved from `admin_init` action to the `init` action to accomodate (rare) "front end" use of table column names, e.g., the "Views" table.
* Fix: Modest database access reductions in the Media/Assistant submenu table preparation.
* Fix: Eliminated PHP Warning and Notice messages from `class-mla-mime-types.php` in WP v3.5.x.
* Fix: Corrected post ID parameter defect in the `mla_mapping_updates` filter during IPTC/EXIF mapping.

= 1.70 =
* New: **Internationalization (i18n) and Localization (l10n) support!** All of the internationalization work in the plugin source code has been completed and there is a Portable Object Template (.POT) available in the "/languages" directory. I don't have working knowledge of anything but English, but if you'd like to volunteer to produce a translation, I would be delighted to work with you to make it happen. Have a look at the "MLA Internationalization Guide.php" file in the languages directory and get in touch.
* New: For Custom Field and IPTC/EXIF mapping, **twelve new `apply_filters/do_action` hooks**  give you complete control over rule execution and value creation from PHP code in your theme or in another plugin. More information in the [Other Notes section](http://wordpress.org/extend/plugins/media-library-assistant/other_notes/ "Click here, then scroll down") section here. A complete, working example is provided in the Settings/Media Library Assistant Documentation tab.
* New: For Settings/Media Library Assistant Custom Fields and IPTC/EXIF tabs **"Enable ... Mapping when updating media metadata" options** allow you to apply mapping rules whenever the media metadata is updated, not just on new uploads.
* Fix: On the Settings/Media Library Assistant IPTC/EXIF tab, **Taxonomy Parent dropdown now reflects term hierarchy**.
* Fix: MLAMime::mla_upload_mimes_filter() returns the MLA updated list of allowed types, not the WordPress default list.
* Fix: MLAMime::mla_upload_mimes_filter() now respects the WordPress per-user 'unfiltered_html' capability.
* Fix: When uploading new attachments, attachment metadata (_wp_attachment_metadata) is now updated from custom field and IPTC/EXIF mapping rules that contain the "meta:" prefix.
* Fix: For WordPress 3.5 and later, a more efficient query is used to compose the "Attachments" column in the Media/taxonomy submenu tables.
* Fix: Documentation for custom field and IPTC/EXIF mapping has been restructured and expanded to better explain these features.

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

= 1.81 =
Corrects serious defect in Media Manager Modal Window file uploading. Adds item-specific tag clouds. One other enhancement, five other fixes.

== Other Notes ==

In this section, scroll down to see highlights from the documentation, including new and unique plugin features

**NOTE:** Complete documentation is included in the Documentation tab on the Settings/Media Library Assistant admin screen and the drop-down "Help" content in the admin screens.

== Acknowledgements ==

Media Library Assistant includes many images drawn (with permission) from the [Crystal Project Icons](http://www.softicons.com/free-icons/system-icons/crystal-project-icons-by-everaldo-coelho), created by [Everaldo Coelho](http://www.everaldo.com), founder of [Yellowicon](http://www.yellowicon.com).

== NEW! For the Media Manager Modal Window, Native support for "checkbox-style" and "tag hint-style" taxonomy meta boxes ==
Now you can have all of the taxonomy management features you're used to in the Media Manager Modal Window; no more slugs and text boxes. Go to the Settings/Media Library Assistant General tab, scroll down to Media Manager Enhancements and check "Media Manager Categories meta boxes" box and/or "Media Manager Tags meta boxes" to add enhanced features in the ATTACHMENT DETAILS area.

**IMPORTANT:** These features replace the interim support for the "Media Categories" plugin. If you use either of these new options, be sure to **deactivate the Media Categories plugin (by Eddie Moya)**.

The checkbox-style meta box lets you select from a complete list of defined terms or the most popular terms. You can define new terms by clicking "+ Add New ..." and you can filter the list by clicking "? Search".

The "tag hint-style" meta box lets you enter a partial value, then select from the "suggested" terms that match what you've entered so far. You can define new terms by entering a value and clicking "Add". You can click "Choose from the most used tags" to select terms from a tag-cloud display.

== NEW! For flat taxonomies, e.g., "Tags" or "Att. Tags", a "checkbox-style" meta box is available. ==
If you want a flat taxonomy to be displayed as a complete list of terms with checkboxes for selecting terms, go to the Settings/Media Library Assistant General tab, scroll down to Taxonomy Support and check the box in the "Checklist" column for that taxonomy. This will affect the Media/Edit Media screen and the Media Manager Modal Window ATTACHMENT DETAILS area.

== NEW! For "checkbox-style" taxonomy meta boxes, click on "? Search" and type part or all of a keyword or phrase to filter the checkbox list for easier access to the term(s) you want. ==

== MLA Custom Field and IPTC/EXIF Mapping Actions and Filters (Hooks) ==
The Custom Field and IPTC/EXIF Mapping tools support a comprehensive set of filters and actions that give you complete control over rule execution and value creation from PHP code in your theme or in another plugin. An example of using the hooks from a simple, stand-alone plugin can be found in the "examples" directory.

The example code documents each hook with comments in the filter/action function that intercepts each hook. There are hooks that run at the beginning and end of the overall mapping operation as well as hooks for each mapping rule. 

In addition, there are hooks that run when attachments are uploaded to the Media Library and when the "attachment metadata" is altered, e.g., when the Media/Edit Media "Edit Image" function is used. Plugins and other image editing code can destroy the attachment metadata or the IPTC/EXIF metadata embedded in an image file. These hooks may give you an opportunity to preserve and repair the metadata you need in spite of such damage.

The current mapping hooks are:

* **mla_mapping_settings** - called before any mapping rules are executed. You can add, change or delete rules from the settings/rules array.

* **mla_mapping_rule** - called once for each mapping rule, before the rule is evaluated. You can change the rule parameters, or prevent rule evaluation.

* **mla_mapping_custom_value** - called once for each custom field mapping rule, after the rule is evaluated. You can change the new value produced by the rule.
* **mla_mapping_iptc_value** - called once for each IPTC/EXIF mapping rule, after the IPTC portion of the rule is evaluated. You can change the new value produced by the rule.

* **mla_mapping_exif_value** - called once for each IPTC/EXIF mapping rule, after the EXIF portion of the rule is evaluated. You can change the new value produced by the rule.

* **mla_mapping_updates** - called AFTER all mapping rules are applied. You can add, change or remove updates for the attachment's standard fields, taxonomies and/or custom fields.

The current insert attachment/update attachment metadata hooks are:

* **mla_upload_prefilter** - gives you an opportunity to record the original IPTC, EXIF and WordPress image_metadata <strong>before</strong> the file is stored in the Media Library. You can also modify the file name that will be used in the Media Library. Many plugins and image editing functions alter or destroy this information,
so this may be your last change to preserve it.

* **mla_upload_filter** - gives you an opportunity to record some additional metadata
for audio and video media <strong>after</strong> the file is stored in the Media Library.

* **mla_add_attachment** - called at the end of the wp_insert_attachment() function,
after the file is in place and the post object has been created in the database. By this time, other plugins have probably run their own 'add_attachment' filters and done their work/damage to metadata, etc.

* **mla_update_attachment_metadata_options** - lets you inspect or change the processing options that will control the MLA mapping rules in the update_attachment_metadata filter.

* **mla_update_attachment_metadata_prefilter** - called at the end of the wp_update_attachment_metadata() function, <strong>before</strong> any MLA mapping rules are applied. The prefilter gives you an opportunity to record or update the metadata before the mapping.

* **mla_update_attachment_metadata_postfilter** - This filter is called <strong>after</strong> MLA mapping rules are applied during wp_update_attachment_metadata() processing. The postfilter gives you an opportunity to record or update the metadata after the mapping.

== MLA Tag Cloud Shortcode ==

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

Each item in the tag cloud comprises a term name of varying size, a hyperlink surrounding the term name and a "title" attribute (Rollover Text) displayed when the cursor hovers over the term name hyperlink. Seven parameters customize item content and markup.

The Item parameters are an easy way to customize the content and markup for each cloud item. For the list and grid formats you can also use the Tag Cloud Display Content parameters and/or Style and Markup Templates for even greater flexibility.

<h4>Tag Cloud Item Link</h4>

The Link parameter specifies the target and type of link from the tag cloud term/item to the item's archive page, edit page or other destination. You can also specify a non-hyperlink treatment for each item.

Using the "mla_link_href" parameter to completely replace the link destination URL is a common and useful choice. With this parameter us can use the tag cloud to select a term and then go to another post/page that uses that selection as part of an `[mla_gallery]` shortcode.

<h4>Tag Cloud Display Style (list and grid)</h4>

Five parameters provide a way to apply custom style and markup templates to your `[mla_tag_cloud]` display.

<h4>Tag Cloud Display Content</h4>

Eight parameters provide an easy way to control the contents of tag cloud items without requiring the use of custom Markup templates.  

<h4>Tag Cloud Data Selection Parameters</h4>

The data selection parameters specify which taxonomy (or taxonomies) the terms are taken from, which terms are returned for the cloud and the order in which the terms are returned.

<h4>Tag Cloud Substitution Parameters</h4>

Style and Markup templates give you great flexibility for the content and format of each [mla_tag_cloud] when you use the "list" and "grid" output formats. The request, query and template <strong>field-level substitution parameters</strong> are available in the Style template and any of the Markup template sections.

Twenty-eight tag cloud substitution parameters are available for the <strong>Style template</strong>. Tag cloud substitution parameters for the <strong>Markup template</strong> are available in all of the template sections. They include all of the parameters defined above (for the Style template) and three additional markup-specific parameters. Tag cloud <strong>item-specific substitution parameters</strong> for the Markup template are available in the "Item" section of the template. They include all of the parameters defined above (for the Style and Markup templates) and twenty-five additional item-specific parameters.

<h4>Tag Cloud Pagination Parameters</h4>

If you have a large number of terms in your cloud taxonomy you may want to paginate the cloud display, i.e., divide the cloud into two or more pages of a reasonable size. Pagination support for `[mla_tag_cloud]` is modeled on similar functions for`[mla_gallery]`, and you can find more explaination of the ideas behind pagination in the <strong>Support for Alternative Gallery Output, e.g., Pagination</strong> documentation section. Five parameters are supplied for this purpose.

The `[mla_tag_cloud]` shortcode can be used to provide "Previous" and "Next" links that support moving among the individual items in a cloud or among cloud "pages". For example, if you have many terms in your Att. Category or Att. Tag taxonomies you can build a term-specific `[mla_gallery]` page with links to the previous/next term in the taxonomy (a complete pagination example is included below). You can also build a page that shows a large taxonomy in groups, or "cloud pages", of ten terms with links to the previous/next ten terms or links to all of the cloud pages of terms in the taxonomy.

The <strong>"mla_output"</strong> parameter determines the type of output the shortcode will return. For pagination output, you can choose from six values.

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

This template has a String, "Terms: " and a Conditional, "(([+terms: ... none)". This Conditional separates the "Terms: " literal from the first alternative in the Choice. Within the Conditional is a Choice having four alternatives. The first alternative is a Conditional, which will be empty unless both categories and tags are present.  The second and third alternatives handle the cases where one of the two taxonomies has terms, and the final alternative is used when neither categories nor tags are present.

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
