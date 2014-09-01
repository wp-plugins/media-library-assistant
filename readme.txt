=== Plugin Name ===
Contributors: dglingren
Donate link: http://fairtradejudaica.org/make-a-difference/donate/
Tags: attachment, attachments, documents, gallery, image, images, media, library, media library, tag cloud, media-tags, media tags, tags, media categories, categories, IPTC, EXIF, GPS, PDF, meta, metadata, photo, photos, photograph, photographs, photoblog, photo albums, lightroom, photoshop, MIME, mime-type, icon, upload, file extensions
Requires at least: 3.3
Tested up to: 4.0
Stable tag: 1.93
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
9. The Media Manager popup modal window showing additional filters for date and taxonomy terms. Also shows the enhanced Search Media box and the full-function taxonomy support in the ATTACHMENT DETAILS area.

== Changelog ==

= 1.93 =

* New: MLA enhancements have been added to the new WordPress 4.0 Media/Library Media Grid submenu. An option has been added to the Settings/Media Library Assistant General tab to disable them if they are not wanted.
* New: An option has been added to the Settings/Media Library Assistant General tab to automatically populate taxonomy metaboxes in the Media Manager Details pane when the item is selected. The option is disabled by default.
* New: A new mla-simple-mapping-hooks-example.php.txt example plugin has been added to the /media-library-assistant/examples directory.
* Fix: Completed WordPress 4.0 compatibility work.
* Fix: Media Manager toolbar controls for different modes, e.g., Insert Media, Set Featured Image, are now separated so each mode retains its own settings.
* Fix: Media Manager MLA enhanced toolbar controls for MIME type, date and terms now work when the enhanced Search Media control is disabled.
* Fix: The buddypress-hooks-example.php.txt example plugin now handles cover art for the most recent rtMedia releases.

= 1.92 =
* Fix: PHP "Fatal error: Call to undefined function get_current_screen ()" has been fixed.
* Fix: Enhanced taxonomy support for drag & drop file uploads has been restored.
* Fix: JavaScript "undefined or null object" reference when all Media Manager toolbar enhancements are disabled has been fixed.

= 1.91 =

* New: Fourteen filters have been added to the "Edit Media additional meta boxes". You can customize which meta boxes appear and replace their contents. An example plugin has been added to demonstrate their use. See the [Other Notes section](http://wordpress.org/extend/plugins/media-library-assistant/other_notes/ "Click here, then scroll down") section or the Settings/Media Library Assistant Documentation tab for more information.
* New: A new hook, `mla_media_modal_initial_filters` has been added to the "Media Manager Enhancement filters (Hooks)".
* New: A new "MLA Media Modal Hooks Example" plugin has been created in `/examples/mla-media-modal-hooks-example.php.txt`.
* New: Two new examples have been added to the MLA Gallery Hooks example plugin.
* New: A new "MLA Tax Query Example" plugin has been created in `/examples/mla-tax-query-example.php.txt`.
* New: Two new hooks, `mla_begin_mapping` and `mla_end_mapping` have been added to the "MLA Custom Field and IPTC/EXIF Mapping Actions and Filters (Hooks)".
* New: A new "MLA Meta Box Hooks Example" plugin has been created in `/examples/mla-metabox-hooks-example.php.txt`.
* New: A new, simplified MLA Mapping Hooks example plugin has been created. The older, more complex example is provided as a separate example plugin, `/examples/mla-metadata-mapping-hooks-example.php.txt`.
* Fix: On the Media/Assistant submenu table, the "Set Parent" links now refresh the entire table row, properly updating all of the affected columns and the Quick Edit data.
* Fix: A defect that affected certain "front end" file uploads, e.g., changing an avatar in BuddyPress, has been corrected.
* Fix: All `like_escape()` calls have been changed to `$wpdb->esc_like()` for WordPress 4.0 and later.
* Fix: A defect in multi-word or quoted Search Media box content on the Media/Assistant submenu table has been fixed.
* Fix: A defect in WPML support on the Media/Assistant submenu table has been fixed.
* Fix: A defect in using fields in the "posts" database table as "Data sources for custom field mapping" has been fixed.

= 1.90 =

* New: On the Media/Assistant submenu and Media Manager Modal Window, a **new "Terms Search" popup filter** lets you filter the display by terms whose name contains the keywords and phrases entered in the search box. See the [Other Notes section](http://wordpress.org/extend/plugins/media-library-assistant/other_notes/ "Click here, then scroll down") section for more details.
* New: On the Media/Assistant submenu and Media Manager Modal Window Search Media boxes, a **new "terms" checkbox** lets you extend the search to terms whose name contains the keywords and phrases entered in the search box. See the [Other Notes section](http://wordpress.org/extend/plugins/media-library-assistant/other_notes/ "Click here, then scroll down") section for more details.
* New: On the Media/Assistant submenu and Media Manager Modal Window Search Media boxes, you can **hide the and/or connector and search fields controls** by unchecking the appropriate box in the Settings/Media Library Assistant General tab.
* New: On the Media/Assistant submenu and Media Manager Modal Window Search Media boxes, you can **change defaults for and/or connector and search fields controls** by setting them in the Settings/Media Library Assistant General tab.
* New: The **"Select Parent" popup window** now has a post type filter and pagination controls. See the [Other Notes section](http://wordpress.org/extend/plugins/media-library-assistant/other_notes/ "Click here, then scroll down") section for more details.
* New: The **"Select Parent" popup window** has been added to the Media/Assistant submenu table Bulk Edit area.
* New: **Documentation for "Select Parent" and "Terms Search** has been added to the Media/Assistant submenu table "Help" dropdown area and the Settings/Media Library Assistant Documentation tab.
* New: Most of the fields in the "posts" database table have been added as "Data sources for custom field mapping".
* New: For `[mla_tag_cloud]`, a **new "pad_counts" parameter** lets you add children to their parents' term-specific count(s).
* New: For `[mla_tag_cloud]`, **new "post_type" and "post_status" parameters** give you more control over cloud content when a taxonomy such as `category` is registered for posts/pages as well as attachments.
* New: For `[mla_gallery]`, "category" is accepted as a synonym for "category_name" to make filtering by category more intuitive.
* New: An example using a **custom SQL Query to replace gallery content** has been added to the `mla-hooks-example.php.txt` example plugin.
* New: An example plugin using **BuddyPress and rtMedia** has been created in `/examples/buddypress-hooks-example.php.txt`. The example shows how to replace the WordPress "attachment/media page" links with "BuddyPress/rtMedia page" links. For audio and video files, an option is provided to substitute the "cover_art" thumbnail image for the item Title in the thumbnail_content.
* Fix: In the Media Manager Modal Window, **adding a taxonomy term** now updates the toolbar "terms filter" dropdown list. It also updates the "parent" dropdown list in the toolbar, if the taxonomy is hierarchical.
* Fix: In the Media Manager Modal Window, the **year/month and taxonomy filter controls** now appear for plugins such as WooCommerce, Slider Revolution, Image Widget and SimpleFields.
* Fix: More details added to the "Category Parameters" section of the Settings/Media LIbrary Assistant Documentation tab.
* Fix: For `[mla_gallery]`, the `mla_rollover_text=` parameter has been restored. WordPress 3.7 removed the `title=` attribute from its attachment links, which disabled `mla_rollover_text=` as well.
* Fix: For `[mla_tag_cloud]`, the `number` parameter default is now zero, agreeing with the Documentation.

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

= 1.93 =
WordPress 4.0 Media Grid enhancements (optional) and compatibility fixes. New auto-fill option for Media Manager taxonomy meta boxes. One other enhancement, three other fixes.

== Other Notes ==

In this section, scroll down to see highlights from the documentation, including new and unique plugin features

**NOTE:** Complete documentation is included in the Documentation tab on the Settings/Media Library Assistant admin screen and the drop-down "Help" content in the admin screens.

== Acknowledgements ==

Media Library Assistant includes many images drawn (with permission) from the [Crystal Project Icons](http://www.softicons.com/free-icons/system-icons/crystal-project-icons-by-everaldo-coelho), created by [Everaldo Coelho](http://www.everaldo.com), founder of [Yellowicon](http://www.yellowicon.com).

<h4>NEW! Hooks for the Edit Media additional meta boxes</h4>
Media Library Assistant adds support for the "Custom Fields" meta box to the Media/Edit Media screen. MLA also adds several meta boxes to this screen with more information about the item and where it is  used on your site. You can enable/disable the additional meta boxes with an option on the Settings/Media LIbrary Assistant General tab.

You can also make individual changes in which meta boxes are displayed and in their content by using one or more of the filters MLA provides. An example of using the hooks from a simple, stand-alone plugin can be found at /media-library-assistant/examples/mla-metabox-hooks-example.php.txt. To run the example:

1. Edit the code to, for example, uncomment the <code>error_log()</code> calls so you can see what is passed to the hooks you are interested in.
1. Remove the ".txt" extension and save the "mla-metabox-example.php" file in your plugins directory. You can give the plugin and its file any (unique) name you like.
1. Go to the Plugins/Installed Plugins screen and activate the "MLA Meta Box Hooks Example" plugin.
1. Make any changes or additions you want to in the example plugin source code. For example, you can modify the <code>mla_inserted_in_metabox</code> example to display a simplified version of the "Inserted in" information.
1. View the Media/Edit Media screen for an item to see the effect of your changes.

The example code documents each hook with comments in the filter function that intercepts the hook. Generally, each meta box filter lets you change the size of the text box (if appropriate) and the content that appears in the box. There is also a second filter for each meta box that lets you replace <strong>all</strong> of the HTML content for most boxes; use these with caution. The current hooks are: 

* <strong>mla_edit_media_support</strong> - suppress the addition of Custom Fields to the Edit Media screen. To suppress Custom Fields, return an empty array, i.e., <code>return array();</code>

* <strong>mla_edit_media_meta_boxes</strong> - record the original list of meta box slugs. You can also remove elements from the array to suppress one or more meta boxes. To suppress a box, remove it from the array, e.g., <code>unset( $active_boxes['mla-menu-order'] );</code>

* <strong>mla_parent_info_meta_box</strong> - modify the text portion of the "Parent Info" meta box.

* <strong>mla_menu_order_meta_box</strong> - modify the "Menu Order" meta box.

* <strong>mla_image_metadata_meta_box</strong> - modify the rows, columns and content of the "Attachment Metadata" meta box.<br><strong>mla_image_metadata_meta_box_html</strong>

* <strong>mla_featured_in_meta_box</strong> - modify the rows, columns and content of the "Featured in" meta box.<br><strong>mla_featured_in_meta_box_html</strong>

* <strong>mla_inserted_in_meta_box</strong> - modify the rows, columns and content of the "Inserted in" meta box.<br><strong>mla_inserted_in_meta_box_html</strong>

* <strong>mla_gallery_in_meta_box</strong> - modify the rows, columns and content of the "Gallery in" meta box.<br><strong>mla_gallery_in_meta_box_html</strong>

* <strong>mla_mla_gallery_in_meta_box</strong> - modify the rows, columns and content of the "MLA Gallery in" meta box.<br><strong>mla_mla_gallery_in_meta_box_html</strong>

<h4>Terms Search</h4>
The "Terms Search" features let you filter the Media/Assistant submenu table and the Media Manager Modal Window by matching one or more phrases in the Name field of taxonomy terms. There are two ways to access this feature:

<ol>
<li>Check the "Terms" box under the "Search Media" button on the Media/Assistant submenu table or the Media Manager toolbar. The phrase(s) you enter in the search box will match taxonomy term names as well as any other search fields you have checked.</li>
<li>Click the "Terms Search" button beside the terms filter dropdown. This will bring up the "Search Terms" popup window with several additional controls to refine your search. They are described below.</li>
</ol>

<strong>Entering words and phrases</strong>

You can enter one or more words/phrases in the Search Media or Search Terms text box, separated by spaces. A multi-word phrase is created by surrounding two or more words with double quotes ( " ). For example:

<ul>
<li>' man bites dog ' is three separate one-word phrases</li>
<li>' man "bites dog" ' is a one-word phrase (man) and a two-word phrase (bites dog)</li>
<li>' "man bites dog" ' is one three-word phrase</li>
</ul>

The first example would match each word separately. The second would match "man" and "bites dog" separately, with exactly one space between "bites" and "dog". The search is further defined by the connector used between multiple phrases:

<ul>
<li>'and'/'All phrases' - all of the phrases must appear in the search field/term name.</li>
<li>'or'/'Any phrase' - any one (or more) of the phrases must appear in the search field/term name.</li>
</ul>

For example, if you choose the default 'and'/'All phrases' connector and enter 'man "bites dog"' in the text box:

<ul>
<li>'man that bites dog' will match, but 'man that dog bites' will not match.</li>
<li>'dog bites man' will not match.</li>
<li>'man bites man with dog' will not match.</li>
</ul>

If, however you remove the quotes and enter 'man bites dog' all of the above examples will match, because all three of the phrases appear somewhere in the text. On the other hand 'man bites man' would not match because "dog" does not appear in the text.

If you choose the 'or'/'Any phrase' connector and enter 'man "bites dog"' in the text box:

<ul>
<li>'man that bites dog' will match.</li>
<li>'man that dog bites' will match because "man" is present.</li>
<li>'dog bites man' will match because "man" is present.</li>
<li>'dog bites another dog' will not match.</li>
</ul>

<strong>Entering multiple terms</strong>

The Search Terms popup window has an additional capability and another control to refine it. The additional capability lets you search for multiple terms and the control sets the connector between terms. For example, consider two taxonomies, each with several terms:
 
<ul>
<li>Att. Categories, containing "big animal", "small animal" and "other being"</li>
<li>Att. Tags, containing "male", "female", "cat" and "dog"</li>
</ul>

If you choose 'All phrases' and 'Any term' (the defaults) and enter 'big dog' there are no matches because none of the terms contain both 'big' and 'dog'. If you choose 'Any phrase' and 'Any term' you will get items assigned to the 'big animal' Att. Category or the 'dog' Att. Tag. If you choose 'Any phrase' and 'All terms' you will get only the items assigned to both the 'big animal' Att. Category <strong>and</strong> the 'dog' Att. Tag.

If you enter 'big,dog', separating the two phrases with a comma, the search results will change. Terms will be matched against "big" and "dog" separately. The 'All phrases'/Any phrase' choice will not matter because both of the phrases contain just one word. Choose 'All terms' and you will get any items assigned to 'big animal' <strong>and</strong> to 'dog'. Choose 'Any term' and you will get all of the 'big animal' matches and all of the 'dog' matches; that includes small dogs and big cats.

<strong>Selecting taxonomies</strong>

By default, the Att. Categories and Att. Tags taxonomies are included in the terms search. In the Taxonomy Support section of the Settings/Media Library Assistant General tab you can use the checkboxes in the Terms Search column to add or remove any of the supported taxonomies from the search process.

In the Search Terms popup window you will find a list of all supported taxonomies, with checkboxes reflecting their participation in the search process. You can add or remove taxonomies from the process on a search-by-search basis.

<h4>Select Parent Popup Window</h4>
The "Select Parent" popup window lets you find the parent post/page/custom post type for one or more attachments. You can access the popup window in four ways:

<ol>
<li>Click the "Set Parent" link in the "Attached to" column of the Media/Assistant submenu table.</li>
<li>Click the "Select" button in the Media/Assistant submenu table Quick Edit area.</li>
<li>Click the "Select" button in the Media/Assistant submenu table Bulk Edit area.</li>
<li>Click the "Select" button in the Media/Edit Media submenu "Parent Info" meta box.</li>
</ol>

In all cases the Select Parent popup window will appear and will be filled with up to 50 parent candidates. If the current parent is in the list its radio button will be selected. You can select a new parent, including "(unattached)", by clicking anywhere in the row of the candidate you want.

If you don't see the candidate you want you have three ways of updating the list:

<ol>
<li>Enter one or more keywords in the text box at the top of the window and click "Search". The word(s) you enter will filter the list by searching the Title and Content fields for matches.</li>
<li>Select a post type from the dropdown list at the top of the window. The list will be filtered to show candidates from the post type you choose.</li>
<li>Click the "next page" (" &raquo; ") button in the Media/Assistant submenu table Bulk Edit area. The list will move to the next page of up to 50 additional candidates. You can click the "previous page" (" &laquo; ") button to move back towards the top of the list.</li>
</ol>

Once you have chosen a new parent, click the "Update" button at the lower right of the window to save your choice. You will be returned to your starting point with the new value filled in. Changes made in the "Attached to" column are immediate; changes to the Quick Edit, Bulk Edit and Parent Info meta box are made later, when you click the "Update" button in those areas to save all your changes.

If you change your mind you can close the window without making a change by clicking the "X" in the upper-right corner of the window or the "Cancel" button in the lower-left corner of the window.
