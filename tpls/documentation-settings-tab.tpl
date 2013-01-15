<!-- template="documentation-tab" -->
<div class="mla-display-settings-page" id="mla-display-settings-documentation-tab" style="width:700px">
<h3>In this tab, jump to:</h3>
<ul style="list-style-position:inside; list-style:disc; line-height: 18px">
<li>
<a href="#mla_gallery"><strong>MLA Gallery Shortcode</strong></a>
</li>
<li>
<a href="#photonic_gallery"><strong>Support for &#8220;Photonic Gallery&#8221;</strong></a>
</li>
<li>
<a href="#mla_gallery_templates"><strong>Style and Markup Templates</strong></a>
</li>
<li>
<a href="#mla_style_parameters"><strong>Substitution parameters for style templates</strong></a>
</li>
<li>
<a href="#mla_markup_parameters"><strong>Substitution parameters for markup templates</strong></a>
</li>
<li>
<a href="#mla_attachment_parameters"><strong>Attachment-specific substitution parameters for markup templates</strong></a>
</li>
<li>
<a href="#mla_variable_parameters"><strong>Field-level markup substitution parameters</strong></a>
</li>
<li>
<a href="#mla_table_example"><strong>A table-based template example</strong></a>
</li>
<li>
<a href="#mla_custom_field_mapping"><strong>Custom Field Processing Options</strong></a>
</li>
<li>
<a href="#mla_custom_field_parameters"><strong>Data sources for custom field mapping</strong></a>
</li>
<li>
<a href="#mla_iptc_exif_mapping"><strong>IPTC &amp; EXIF Processing Options</strong></a>
</li>
<li>
<a href="#mla_iptc_identifiers"><strong>IPTC identifiers and friendly names</strong></a>
</li>
</ul>
<h3>Plugin Code Documentation</h3>
<p>
If you are a developer interested in how this plugin is put together, you should
have a look at the <a title="Consult the phpDocs documentation" href="[+phpDocs_url+]" target="_blank" style="font-size:14px; font-weight:bold">phpDocs documentation</a>.
</p>
<a name="mla_gallery"></a>
&nbsp;
<p>
<a href="#backtotop">Go to Top</a>
</p>
<h3>MLA Gallery Shortcode</h3>
<p>
The [mla_gallery] shortcode is used in a post, page or custom post type to add a gallery of images and/or other Media Library items (such as PDF documents). MLA Gallery is a superset of the [gallery] shortcode in the WordPress core; it is compatible with [gallery] and provides many enhancements. These include:
</p>
<ul class="mla_settings">
<li>Full support for WordPress categories, tags and custom taxonomies. You can select items with any of the taxonomy parameters documented in the WP_Query class.</li>
<li>Support for all post_mime_type values, not just images.</li>
<li>Media Library items need not be "attached" to the post. You can build a gallery with any combination of items in the Library using taxonomy terms, custom fields and more.</li>
<li>Control over the styles, markup and content of each gallery using the Style and Markup Templates documented below.</li>
</ul>
<p>
All of the options/parameters documented for the [gallery] shortcode are supported by the [mla_gallery] shortcode; you can find them in the WordPress Codex. Most of the parameters documented for the WP_Query class are also supported; see the WordPress Codex. Because the [mla_gallery] shortcode is designed to work with Media Library items, there are some parameter differences and extensions; these are documented below.
</p>
<h4>Gallery Display Style</h4>
<p>
Two [mla_gallery] parameters provide a way to apply custom style and markup templates to your [mla_gallery] display. These parameters replace the default style and/or markup templates with templates you define on the "MLA Gallery" tab of the Settings page. On the "MLA Gallery" tab you can also select one of your custom templates to replace the built-in default template for all [mla_gallery] shortcodes the do not contain one of these parameters.
</p>
<table>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">mla_style</td>
<td>replaces the default style template for an [mla_gallery] shortcode</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">mla_markup</td>
<td>replaces the default markup template for an [mla_gallery] shortcode</td>
</tr>
</table>
<p>
Three [mla_gallery] parameters provide control over the placement, size and spacing of gallery items without requiring the use of custom Style templates.
</p>
<table>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">mla_float</td>
<td>specifies the float attribute of the ".gallery-item" style. Acceptable values are "left", "none", "right"; the default value is "right" if current locale is RTL, "left" on LTR (left-to-right inline flow, e.g., English).</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">mla_margin</td>
<td>specifies the margin attribute (in percent) of the ".gallery-item" style. The default value is "1.5" percent.</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">mla_itemwidth</td>
<td>specifies the width attribute (in percent) of the ".gallery-item" style. The default value is calculated by subtracting twice the margin from 100%, then dividing by the number of gallery columns. For example, the default value is "32", or (100 - (2 * 1.5)) / 3.</td>
</tr>
</table>
<p>
These parameters are only important if the gallery thumbnails are too large to fit within the width of the page on which they appear. For example, if you code [mla_gallery size=full], the browser will automatically scale down large images to fit within the width attribute (in percent) of the ".gallery-item" style. The default 1.5% margin will ensure that the images do not overlap; you can increase it to add more space between the gallery items. You can also reduce the itemwidth parameter to increase the left and right space between the items.
</p>
<h4>Gallery Display Content</h4>
<p>
Three [mla_gallery] parameters provide an easy way to control the contents of gallery items without requiring the use of custom Markup templates.  
</p>
<table>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">mla_link_text</td>
<td>replaces the thumbnail image or attachment title text displayed for each gallery item</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">mla_rollover_text</td>
<td>replaces the attachment title text displayed when the mouse rolls or hovers over the gallery thumbnail</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">mla_caption</td>
<td>replaces the attachment caption text displayed beneath the thumbnail of each gallery item</td>
</tr>
</table>
<p>
All three of these parameters support the Markup and Attachment-specific substitution arguments defined for Markup Templates. For example, if you code `mla_rollover_text="{+date+} : {+description+}"`, the rollover text will contain the upload date, a colon, and the full description of each gallery item. Simply add "{+" before the substitution parameter name and add "+}" after the name. Note that the enclosing delimiters are different than those used in the templates, since the shortcode parser reserves square brackets ("[" and "]") for its own use.
</p>
<h4>Order, Orderby</h4>
<p>
To order the gallery randomly, use "orderby=rand". To suppress gallery ordering you can use "orderby=none" or "order=rand".
</p>
<p>The Orderby parameter specifies which database field is used to sort the gallery. You can order the gallery by any of the values documented for the WP_Query class reference in the Codex; you are NOT restricted to the values documented for the [gallery] shortcode.
</p>
<h4>Size</h4>
<p>
The Size parameter specifies the image size to use for the thumbnail display. Valid values include "thumbnail", "medium", "large", "full" and any additional image size that was registered with add_image_size(). The default value is "thumbnail". You can use "none" or "" to suppress thumbnail display and substitute the item title string for the image/icon.
</p>
<p>
The [mla_gallery] shortcode supports an additional Size value, "icon", which shows a 60x60 pixel thumbnail for image items and an appropriate icon for non-image items such as PDF or text files.
</p>
<h4>Link</h4>
<p>
The Link parameter specifies the target for the link from the gallery to the attachment. The default value, "permalink", links to the attachment's media page. The "file" and "full" values link directly to the attachment file.
</p>
<p>
For image attachments you can also specify the size of the image file you want to link to. Valid values include "thumbnail", "medium", "large" and any additional image size that was registered with add_image_size(). If the specified size is not available or if the attachment is not an image, the link will go directly to the attachment file.
</p>
<h4>Include, Exclude</h4>
<p>
You can use "post_parent=all" to include or exclude attachments regardless of which post or page they are attached to. You can use "post_mime_type=all" to include or exclude attachments of all MIME types, not just images.
</p>
<h4>Post ID, "ids", Post Parent</h4>
<p>
The "id" parameter lets you specify a post ID for your query. If the "id" parameter is not specified, the [mla_gallery] behavior differs from the [gallery] behavior. If your query uses taxonomy or custom field parameters, "author", "author_name" or "s" (search term), then the query will NOT be restricted to items attached to the current post. This lets you build a gallery with any combination of Media Library items that match the parameters.
</p>
<p>
For WordPress 3.5 and later, the "ids" parameter lets you specify a list of Post IDs. The attachment(s) matching the "ids" values will be displayed in the order specified by the list.
</p>
<p>
You can use the "post_parent" to override the default behavior. If you set "post_parent" to a specific post ID, only the items attached to that post are displayed. If you set "post_parent" to "current", only the items attached to the current post are displayed. If you set "post_parent" to "all", the query will not have a post ID or post_parent parameter.
</p>
<p>
For example, [mla_gallery tag="artisan"] will display all images having the specified tag value, regardless of which post (if any) they are attached to. If you use [mla_gallery tag="artisan" post_parent="current"] it will display images having the specified tag value only if they are attached to the current post.
</p>
<h4>Author, Author Name</h4>
<p>
You can query by author's id or the "user_nicename" value (not the "display_name" value). Multiple author ID values are allowed, but only one author name value can be entered.
</p>
<h4>Category Parameters</h4>
<p>
Remember to use "post_parent=current" if you want to restrict your query to items attached to the current post.
</p>
<h4>Tag Parameters</h4>
<p>
Remember to use "post_parent=current" if you want to restrict your query to items attached to the current post.
</p>
<p>
Note that the "tag_id" parameter requires exactly one tag ID; multiple IDs are not allowed. You can use the "tag__in" parameter to query for multiple values.
</p>
<h4>Taxonomy Parameters</h4>
<p>
The [mla_gallery] shortcode supports the simple "{tax} (string)" values (deprecated as of WordPress version 3.1) as well as the more powerful "tax_query" value. 
</p>
<p>
For simple queries, enter the taxonomy name and the term(s) that must be matched, e.g.:
</p>
<ul class="mla_settings">
<li>[mla_gallery attachment_category='separate-category,another-category']</li>
</ul>
<p>
Note that you must use the name/slug strings for taxonomy and terms, not the "title" strings.
</p>
<p>
More complex queries can be specified by using "tax_query", e.g.:
</p>
<ul class="mla_settings">
<li>[mla_gallery tax_query="array(array('taxonomy' => 'attachment_tag','field' => 'slug','terms' => 'artisan'))"]</li>
<li>[mla_gallery tax_query="array(array('taxonomy' => 'attachment_category','field' => 'id','terms' => array(11, 12)))" post_parent=current post_mime_type='']</li>
</ul>
<p>
The first example is equivalent to the simple query "attachment_tag=artisan". The second example matches items of all MIME types, attached to the current post, having an attachment_category ID of 11 or 12.
</p>
<p>
When embedding the shortcode in the body of a post, be very careful when coding the tax_query; it must be a valid PHP array specification. In particular, code the query on one line; splitting it across lines can insert HTML &#8249;br&#8250; tags and corrupt your query. 
</p>
<p>
Remember to use "post_parent=current" if you want to restrict your query to items attached to the current post.
</p>
<h4>Post Type, Post Status and Post MIME Type</h4>
<p>
For compatibility with the WordPress [gallery] shortcode, these parameters default to "post_type=attachment", "post_status=inherit" and "post_mime_type=image". You can override the defaults to, for example, display items in the trash ("post_status=trash") or PDF documents ("post_mime_type=application/pdf") or all MIME types ("post_mime_type=all"). I'm not sure why you'd want to override "post_type", but you are welcome to experiment and let me know what you find.
</p>
<h4>Pagination Parameters</h4>
<p>
The [mla_gallery] shortcode supplies "nopaging=true" as a default parameter. If you are working with a template that supports pagination you can replace this with specific values for "posts_per_page", "posts_per_archive_page", "paged" and/or "offset" . You can also pass "paged=current" to suppress the "nopaging" default; "current" will be replaced by the appropriate value (get_query_var('paged')).
</p>
<h4>Time Parameters</h4>
<p>
Support for time parameters is not included in the current version. I may add it later - let me know if you need it.
</p>
<h4>Custom Field Parameters</h4>
<p>
The [mla_gallery] shortcode supports the simple custom field parameters as well as the more powerful "meta_query" parameters made available as of WordPress 3.1.
</p>
<p>
When embedding the shortcode in the body of a post, be very careful when coding the meta_query; it must be a valid PHP array specification. In particular, code the query on one line; splitting it across lines can insert HTML <br> tags and corrupt your query.
</p>
<p>
Remember to use "post_parent=current" if you want to restrict your query to items attached to the current post.
</p>
<h4>Search Keywords</h4>
<p>
The search parameter ("s=keyword") will perform a keyword search. A cursory inspection of the code in /wp-includes/query.php reveals that the search includes the "post_title" and "post_content" (Description) fields but not the "post_excerpt" (Caption) field. An SQL "LIKE" clause is composed and added to the search criteria. I haven't done much testing of this parameter.
</p>
<h4>Debugging Output</h4>
<p>
The "mla_debug" parameter controls the display of information about the query parameters and SQL statements used to retrieve gallery items. If you code `mla_debug=true` you will see a lot of information added to the post or page containing the gallery. Of course, this parameter should <strong><em>ONLY</em></strong> be used in a development/debugging environment; it's quite ugly.
</p>
<a name="photonic_gallery"></a>
&nbsp;
<p>
<a href="#backtotop">Go to Top</a>
</p>
<h3>Support for &#8220;Photonic Gallery for Flickr, Picasa, SmugMug, 500px and Instagram&#8221;</h3>
<p>
The <a href="http://wordpress.org/extend/plugins/photonic/" title="Photonic Gallery plugin directory page" target="_blank">Photonic Gallery for Flickr, Picasa, SmugMug, 500px and Instagram</a> plugin adds several new parameters to the [mla_gallery] shortcode to enhance your galleries. All you have to do is install the plugin, then add a "style=" parameter to your [mla_gallery] shortcode to use the Photonic styling and markup in place of the native [mla_gallery] style and markup templates.
</p>
<p>
You can use the "Photonic" screen of the Insert Media dialog to build the display portion of your shortcode parameters. After you click "Insert into post", change the shortcode name from "gallery" to "mla_gallery" and add the query parameters you need to select the attachments for the gallery. The [mla_gallery] code will compile the list of attachments for your gallery, then hand control over to Photonic to format the results.
</p>
<a name="mla_gallery_templates"></a>
&nbsp;
<p>
<a href="#backtotop">Go to Top</a>
</p>
<h3>MLA Gallery Style and Markup Templates</h3>
<p>
The Style and Markup templates give you great flexibility for the content and format of each [mla_gallery]. You can define as many templates as you need.
</p>
<p>
Style templates provide gallery-specific CSS inline styles. Markup templates provide the HTML markup for 1) the beginning of the gallery, 2) the beginning of each row, 3) each gallery item, 4) the end of each row and 5) the end of the gallery. The attachment-specific markup parameters let you choose among most of the attachment fields, not just the caption.
</p>
<p>
The MLA Gallery tab on the Settings page lets you add, change and delete custom templates. The default tempates are also displayed on this tab for easy reference.
</p>
<p>
In a template, substitution parameters are surrounded by opening ('[+') and closing ('+]') delimiters to separate them from the template text; see the default templates for many examples.
</p>
<a name="mla_style_parameters"></a>
&nbsp;
<p>
<a href="#backtotop">Go to Top</a>
</p>
<h4>Substitution parameters for style templates</h4>
<table>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">mla_style</td>
<td>shortcode parameter, default = 'default'</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">mla_markup</td>
<td>shortcode parameter, default = 'default'</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">instance</td>
<td>starts at '1', incremented for each additional shortcode in the post/page</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">id</td>
<td>post_ID of the post/page in which the gallery appears</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">itemtag</td>
<td>shortcode parameter, default = 'dl'</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">icontag</td>
<td>shortcode parameter, default = 'dt'</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">captiontag</td>
<td>shortcode parameter, default = 'dd'</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">columns</td>
<td>shortcode parameter, default = '3'</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">itemwidth</td>
<td>shortcode parameter, default = '97' if 'columns' is zero, or 97/columns, e.g., '32' if columns is '3'</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">margin</td>
<td>shortcode parameter, default = '1.5' (percent)</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">float</td>
<td>'right' if current locale is RTL, 'left' if not</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">selector</td>
<td>"mla_gallery-{$instance}", e.g., mla_gallery-1</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">size_class</td>
<td>shortcode 'size' parameter, default = 'thumbnail'</td>
</tr>
</table>
<a name="mla_markup_parameters"></a>
&nbsp;
<p>
<a href="#backtotop">Go to Top</a>
</p>
<h4>Substitution parameters for markup templates</h4>
<table>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">mla_style</td>
<td>shortcode parameter, default = 'default'</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">mla_markup</td>
<td>shortcode parameter, default = 'default'</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">instance</td>
<td>starts at '1', incremented for each additional shortcode in the post/page</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">id</td>
<td>post_ID of the post/page in which the gallery appears</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">itemtag</td>
<td>shortcode parameter, default = 'dl'</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">icontag</td>
<td>shortcode parameter, default = 'dt'</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">captiontag</td>
<td>shortcode parameter, default = 'dd'</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">columns</td>
<td>shortcode parameter, default = '3'</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">itemwidth</td>
<td>shortcode parameter, default = '97' if 'columns' is zero, or 97/columns, e.g., '32' if columns is '3'</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">margin</td>
<td>shortcode parameter, default = '1.5' (percent)</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">float</td>
<td>'right' if current locale is RTL, 'left' if not</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">selector</td>
<td>"mla_gallery-{$instance}", e.g., mla_gallery-1</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">size_class</td>
<td>shortcode 'size' parameter, default = "thumbnail". If this parameter contains "none" or an empty string (size="") the attachment title will be displayed instead of the image/icon.</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">base_url</td>
<td>absolute URL to the upload directory, without trailing slash</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">base_dir</td>
<td>full path to the upload directory, without trailing slash</td>
</tr>
</table>
<a name="mla_attachment_parameters"></a>
&nbsp;
<p>
<a href="#backtotop">Go to Top</a>
</p>
<h4>Attachment-specific substitution parameters for markup templates</h4>
<table>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">index</td>
<td>starts at '1', incremented for each attachment in the gallery</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">caption</td>
<td>if captiontag is not empty, contains caption/post_excerpt</td>
</tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">excerpt</td>
<td>always contains post_excerpt</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">attachment_ID</td>
<td>attachment post_ID</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">mime_type</td>
<td>attachment post_mime_type</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">menu_order</td>
<td>attachment menu_order</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">date</td>
<td>attachment post_date</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">modified</td>
<td>attachment post_modified</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">parent</td>
<td>attachment post_parent (ID)</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">parent_title</td>
<td>post_title of the parent, or '(unattached)'</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">parent_type</td>
<td>'post', 'page' or custom post type of the parent</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">parent_date</td>
<td>upload date of the parent</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">title</td>
<td>attachment post_title</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">slug</td>
<td>attachment post_name</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">width</td>
<td>width in pixels, for image types</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">height</td>
<td>height in pixels, for image types</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">image_meta</td>
<td>image metadata, for image types</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">image_alt</td>
<td>ALT text, for image types</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">base_file</td>
<td>path and file name relative to uploads directory</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">path</td>
<td>path portion of base_file</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">file</td>
<td>file name portion of base_file</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">description</td>
<td>attachment post_content</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">file_url</td>
<td>attachment guid</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">author_id</td>
<td>attachment post_author</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">author</td>
<td>author display_name, or 'unknown'</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">link</td>
<td>hyperlink to the attachment page (default) or file (shortcode 'link' parameter = "file")</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">pagelink</td>
<td>always contains a hyperlink to the attachment page</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">filelink</td>
<td>always contains a hyperlink to the attachment file</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">link_url</td>
<td>the URL portion of <em>link</em></td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">pagelink_url</td>
<td>the URL portion of <em>pagelink</em></td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">filelink_url</td>
<td>the URL portion of <em>filelink</em></td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">thumbnail_content</td>
<td>complete content of the gallery item link. This will either be an "&lt;img ... &gt;" tag<br />or a text string for non-image items</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">thumbnail_width</td>
<td>for image/icon items, width of the gallery image/icon</td>
</tr>
<tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">thumbnail_height</td>
<td>for image/icon items, height of the gallery image/icon</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">thumbnail_url</td>
<td>for image/icon items, URL of the gallery image/icon</td>
</tr>
</table>
<a name="mla_variable_parameters"></a>
&nbsp;
<p>
<a href="#backtotop">Go to Top</a>
</p>
<h3>Field-level Markup Substitution Parameters</h3>
<p>
Field-level substitution parameters let you access custom fields, taxonomy terms, IPTC metadata and EXIF metadata for display in an MLA gallery. For these parameters, the value you code within the surrounding the ('[+') and ('+]') delimiters has three parts; the prefix, the field name and the optional ",single" indicator.
</p>
<p>
The <strong>prefix</strong> defines which type of field-level data you are accessing. It must immediately follow the opening ('[+') delimiter and end with a colon (':'). There can be no spaces in this part of the parameter.
</p>
<p>
The <strong>field name</strong> defines which field-level data element you are accessing. It must immediately follow the colon (':'). There can be no spaces between the colon and the field name. Spaces are allowed within the field name to accomodate custom field names that contain them. 
</p>
<p>
The optional <strong>",single" indicator</strong> defines how to handle fields with multiple values. It must immediately follow the field name and end with the closing delimiter ('+]'). There can be no spaces in this part of the parameter. If this part of the parameter is present, only the first value of the field will be returned. Use this indicator to limit the data returned for a custom field, taxonomy or metadata field that can have many values.
</p>
<p>
There are four prefix values for field-level data. Prefix values must be coded as shown; all lowercase letters.
</p>
<table>
	<tr>
		<td style="padding-right: 10px; vertical-align: top; font-weight:bold">custom</td>
		<td>WordPress Custom Fields, which you can define and populate on the Edit Media screen. The field name, or key, can contain spaces and some punctuation characters. You <strong><em>cannot use the plus sign ('+')</em></strong> in a field name you want to use with [mla_gallery]. Custom field names are case-sensitive; "client" and "Client" are not the same.</td>
	</tr>
	<tr>
		<td style="padding-right: 10px; vertical-align: top; font-weight:bold">terms</td>
		<td>WordPress Category, tag or custom taxonomy terms. For this category, you code the name of the taxonomy as the field name. The term(s) associated with the attachment will be displayed in the [mla_gallery]. Note that you must use the name/slug string for taxonomy, not the "title" string. For example, use "attachment-category" or "attachment-tag", not "Att. Category" or "Attachment Category".</td>
	</tr>
	<tr>
		<td style="padding-right: 10px; vertical-align: top; font-weight:bold">iptc</td>
		<td>
		The IPTC (International Press Telecommunications Council) metadata, if any, embedded in the image file. For this category, you can code any of the IPTC DataSet tag and field identifiers, e.g., "2#025" for the Keywords field. You can also use the "friendly name" MLA defines for most of the IPTC fields; see the <a href="#mla_iptc_identifiers">table of identifiers and friendly names</a> below. <br />
		&nbsp;<br />
		You can find more information in the <a href="http://www.iptc.org/std/IIM/4.1/specification/IIMV4.1.pdf" title="IPTC-NAA Information Interchange Model Version No. 4.1 specification" target="_blank">IPTC-NAA Information Interchange Model Version No. 4.1 specification</a>.</td>
	</tr>
	<tr>
		<td style="padding-right: 10px; vertical-align: top; font-weight:bold">exif</td>
		<td>
		The EXIF (EXchangeable Image File) metadata, if any, embedded in a JPEG DCT or TIFF Rev 6.0 image file. 
		Though the specification is not currently maintained by any industry or standards organization, almost all camera manufacturers use it. It is also supported by many image editing programs such as Adobe PhotoShop.
		For this category, you can code any of the field names embedded in the image by the camera or editing software. The is no official list of standard field names, so you just have to know the names your camera and software use; field names are case-sensitive.
		<br />&nbsp;<br />
		You can find more information in the <a href="http://en.wikipedia.org/wiki/Exchangeable_image_file_format" title="IPTC-NAA Information Interchange Model Version No. 4.1 specification" target="_blank">Exchangeable image file format</a> article on Wikipedia.</td>
	</tr>
</table>

<a name="mla_table_example"></a>
&nbsp;
<p>
<a href="#backtotop">Go to Top</a>
</p>
<h3>A Table-based Template Example</h3>
<p>
Here's a small example that shows a gallery using <code>&lt;table&gt;</code> markup.
The Item markup section shows how to use the "terms", "custom", "iptc" and "exif" substitution parameters.
</p>
<h4>Style Template</h4>
<code>
&lt;style type='text/css'&gt;<br />
&nbsp;&nbsp;#[+selector+] {<br />
&nbsp;&nbsp;&nbsp;&nbsp;margin: auto;<br />
&nbsp;&nbsp;}<br />
&nbsp;&nbsp;#[+selector+] .gallery-row {<br />
&nbsp;&nbsp;&nbsp;&nbsp;float: [+float+];<br />
&nbsp;&nbsp;&nbsp;&nbsp;margin-top: 10px;<br />
&nbsp;&nbsp;&nbsp;&nbsp;border-top: 1px solid #ddd;<br />
&nbsp;&nbsp;&nbsp;&nbsp;text-align: center;<br />
&nbsp;&nbsp;&nbsp;&nbsp;width: [+itemwidth+]%;<br />
&nbsp;&nbsp;}<br />
&nbsp;&nbsp;#[+selector+] .gallery-row td.gallery-icon {<br />
&nbsp;&nbsp;&nbsp;&nbsp;width: 60;<br />
&nbsp;&nbsp;&nbsp;&nbsp;height: 60;<br />
&nbsp;&nbsp;&nbsp;&nbsp;vertical-align: top;<br />
&nbsp;&nbsp;}<br />
&nbsp;&nbsp;#[+selector+] .gallery-row .gallery-icon img {<br />
&nbsp;&nbsp;&nbsp;&nbsp;border: 2px solid #cfcfcf;<br />
&nbsp;&nbsp;}<br />
&nbsp;&nbsp;#[+selector+] .gallery-caption {<br />
&nbsp;&nbsp;&nbsp;&nbsp;margin-left: 0;<br />
&nbsp;&nbsp;&nbsp;&nbsp;vertical-align: top;<br />
&nbsp;&nbsp;}<br />
&lt;/style&gt;
</code>
<h4>Markup Template</h4>
<table width="700" border="0" cellpadding="5">
	<tr>
		<td style="vertical-align: top; font-weight:bold">Open</td>
		<td><code>&lt;table id='[+selector+]' class='gallery galleryid-[+id+]<br />gallery-columns-[+columns+] gallery-size-[+size_class+]'&gt;</code></td>
	</tr>
	<tr>
		<td style="vertical-align: top; font-weight:bold">Row Open</td>
		<td><code>&lt;tr class='gallery-row'&gt;</code></td>
	</tr>
	<tr>
		<td style="vertical-align: top; font-weight:bold">Item</td>
		<td><code>&lt;td class='gallery-icon'&gt;<br />
		&nbsp;&nbsp;&nbsp;&nbsp;[+link+]<br />
	&lt;/td&gt;<br />
	&lt;td class='wp-caption-text gallery-caption'&gt;<br />
		&nbsp;&nbsp;&nbsp;&nbsp;&lt;strong&gt;[+title+]&lt;/strong&gt;&lt;br /&gt;<br />
		&nbsp;&nbsp;&nbsp;&nbsp;[+description+]&lt;br /&gt;<br />
		&nbsp;&nbsp;&nbsp;&nbsp;[+date+]<br />
		&nbsp;&nbsp;&nbsp;&nbsp;[+custom:client,single+]<br />
		&nbsp;&nbsp;&nbsp;&nbsp;[+terms:category+]<br />
		&nbsp;&nbsp;&nbsp;&nbsp;[+iptc:caption-or-abstract+]<br />
		&nbsp;&nbsp;&nbsp;&nbsp;[+iptc:2#025,single+]<br />
		&nbsp;&nbsp;&nbsp;&nbsp;[+exif:Artist+]
	&lt;/td&gt;</code>
</td>
	</tr>
	<tr>
		<td style="vertical-align: top; font-weight:bold">Row Close</td>
		<td><code>&lt;/tr&gt;</code></td>
	</tr>
	<tr>
		<td style="vertical-align: top; font-weight:bold">Close</td>
		<td><code>&lt;/table&gt;</code></td>
	</tr>
</table>
<a name="mla_custom_field_mapping"></a>
&nbsp;
<p>
<a href="#backtotop">Go to Top</a>
</p>
<h3>Custom Field Processing Options</h3>
<p>
On the Custom Fields tab of the Settings screen you can define the rules for mapping several types of file and image metadata to WordPress custom fields. Custom field mapping can be applied automatically when an attachment is added to the Media Library. You can refresh the mapping for <strong><em>ALL</em></strong> attachments using the command buttons on the screen. You can selectively apply the mapping in the bulk edit area of the Media/Assistant submenu table and/or on the Edit Media screen for a single attachment.
</p>
<p>
This is a powerful tool, but it comes at the price of additional database storage space processing time to maintain and retrieve the data. <strong><em>Think carefully about your needs before you use this tool.</em></strong> You can disable or delete any rules you create, so you might want to set up some rules for a special project or analysis of your library and then discard them when you're done. That said, the advantages of mapping metadata to custom fields are:
</p>
<ul class="mla_settings">
<li>You can add the data to an [mla_gallery] with a field-level markup substitution parameter. For example, add the image dimensions or a list of all the intermediate sizes available for the image.</li>
<li>You can add the data as a sortable column to the Media/Assistant submenu table. For example, you can find all the "orphans" in your library by adding "reference_issues" and then sorting by that column.</li>
</ul>
<p>
Most of the data elements are static, i.e., they do not change after the attachment is added to the Media Library.
The parent/reference information (parent_type, parent_name, parent_issues, reference_issues) is dynamic; it will change as you define galleries, insert images in posts, define featured images, etc. Because of the database processing required to update this information, <strong><em>parent and reference data are NOT automatically refreshed</em></strong>. If you use these elements, you must manually refresh them with the "map data" buttons on the Settings screen, the bulk edit area or the Edit Media screen.
</p>
<p>
Several of the data elements are sourced from the WordPress "image_meta" array. The credit, caption, copyright and title elements are taken from the IPTC/EXIF metadata (if any), but they go through a number of filtering rules that are not easy to replicate with the MLA IPTC/EXIF processing rules. You may find these "image_meta" elements more useful than the raw IPTC/EXIF metadata.
</p>
<a name="mla_custom_field_parameters"></a>
&nbsp;
<p>
<a href="#backtotop">Go to Top</a>
</p>
<h4>Data sources for custom field mapping</h4>
<p>
<strong>NOTE:</strong> Sorting by custom fields in the Media/Assistant submenu is by string values. For numeric data this can cause odd-looking results, e.g., dimensions of "1200x768" will sort before "640x480". The "file_size", "pixels", "width" and "height" data sources are converted to srtings and padded on the left with spaces if you use the "commas" format. This padding makes them sort more sensibly.
</p>
<table>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">path</td>
<td>path portion of the base_file value, e.g., 2012/11/</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">file_name</td>
<td>file name portion of the base_file value, e.g., image.jpg</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">extension</td>
<td>extension portion of the base_file value, e.g., jpg</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">file_size</td>
<td>file size in bytes</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">dimensions</td>
<td>for image types, width x height, e.g., 1024x768</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">pixels</td>
<td>for image types, size in pixels, e.g., 307200 for a 640x480 image</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">width</td>
<td>for image types, width in pixels</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">height</td>
<td>for image types, height in pixels</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">hwstring_small</td>
<td>HTML dimensions of a "small" image, i.e., 128 or less width, 96 or less height. Not computed for images uploaded in WordPress 3.5 and later.</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">size_keys</td>
<td>image size names for thumbnail versions of the image, e.g., "thumbnail, medium, large"</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">size_names</td>
<td>image file names for thumbnail versions of the image, e.g., "image-150x150.jpg, image-300x225.jpg, image-600x288.jpg"</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">size_bytes</td>
<td>file size in bytes for thumbnail versions of the image, e.g., "5127, 11829, 33968"</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">size_pixels</td>
<td>image size in pixels for thumbnail versions of the image, e.g., "22500, 67500, 172800"</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">size_dimensions</td>
<td>image dimensions for thumbnail versions of the image, e.g., "150x150, 300x225, 600x288"</td>
</tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">size_name[size]</td>
<td>image file name for a specific thumbnail version, e.g., size_name[medium] = image-300x225.jpg; set to empty string if the specified size does not exist. There will be a [size] choice for every thumbnail version registered with WordPress for the site.</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">size_bytes[size]</td>
<td>file size in bytes for a specific thumbnail version, e.g., size_bytes[medium] = "11829"</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">size_pixels[size]</td>
<td>image size in pixels for a specific thumbnail version, e.g., size_pixels[medium] = "67500"</td>
</tr>
<tr>
<tr>
<td style="width: 12em; padding-right: 10px; vertical-align: top; font-weight:bold">size_dimensions[size]</td>
<td>image dimensions for a specific thumbnail version, e.g., size_dimensions[medium] = 300x225; set to empty string if the specified size does not exist. There will be a [size] choice for every thumbnail version registered with WordPress for the site.</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">parent_type</td>
<td>for "attached" (post_parent not zero) objects, post type of the parent object</td>
</tr>
<tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">parent_name</td>
<td>for "attached" (post_parent not zero) objects, post title of the parent object</td>
</tr>
<tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">parent_issues</td>
<td>summary of parent status (only) "issues", e.g., bad parent, invalid parent, unattached</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">reference_issues</td>
<td>summary of all reference and parent status "issues", e.g., orphan, bad parent, invalid parent, unattached</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">aperture</td>
<td>for image types, the value stored in WordPress "image_meta" array</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">credit</td>
<td>for image types, the value stored in WordPress "image_meta" array</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">camera</td>
<td>for image types, the value stored in WordPress "image_meta" array</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">caption</td>
<td>for image types, the value stored in WordPress "image_meta" array</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">created_timestamp</td>
<td>for image types, the value stored in WordPress "image_meta" array</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">copyright</td>
<td>for image types, the value stored in WordPress "image_meta" array</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">focal_length</td>
<td>for image types, the value stored in WordPress "image_meta" array</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">iso</td>
<td>for image types, the value stored in WordPress "image_meta" array</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">shutter_speed</td>
<td>for image types, the value stored in WordPress "image_meta" array</td>
</tr>
<tr>
<td style="padding-right: 10px; vertical-align: top; font-weight:bold">title</td>
<td>for image types, the value stored in WordPress "image_meta" array</td>
</tr>
</table>
<a name="mla_iptc_exif_mapping"></a>
&nbsp;
<p>
<a href="#backtotop">Go to Top</a>
</p>
<h3>IPTC &amp; EXIF Processing Options</h3>
<p>
Some image file formats such as JPEG DCT or TIFF Rev 6.0 support the addition of data about the image, or <em>metadata</em>, in the image file. Many popular image processing programs such as Adobe PhotoShop allow you to populate metadata fields with information such as a copyright notice, caption, the image author and keywords that categorize the image in a larger collection. WordPress uses some of this information to populate the Title, Slug and Description fields when you add an image to the Media Library.
</p>
<p>
The Media Library Assistant has powerful tools for copying image metadata to:
<ul style="line-height: 1em; list-style-type:disc; margin-left: 20px ">
<li>the WordPress standard fields, e.g., the Caption</li>
<li>taxonomy terms, e.g., in categories, tags or custom taxonomies</li>
<li>WordPress Custom Fields</li>
</ul>
You can define the rules for mapping metadata on the "IPTC/EXIF" tab of the Settings page. You can choose to automatically apply the rules when new media are added to the Library (or not). You can click the "Map IPTC/EXIF metadata" button on the Edit Media/Edit Single Item screen or in the bulk edit area to selectivelly apply the rules to one or more images. You can click the "Map All Attachments Now" to apply the rules to <strong><em>ALL of the images in your library</em></strong> at one time.
</p>
<h4>Mapping tables</h4>
<p>
The three mapping tables on the IPTC/EXIF tab have the following columns:
<dl>
<dt>Field Title</dt>
<dd>The standard field title, taxonomy name or Custom Field name. In the Custom Field table you can define a new field by entering its name in the blank box at the bottom of the list; the value will be saved when you click "Save Changes" at the bottom of the screen.
</dd>
<dt>IPTC Value</dt>
<dd>The IPTC (International Press Telecommunications Council) metadata, if any, embedded in the image file. For this category, you can select any of the IPTC DataSet tag and field identifiers, e.g., "2#025" for the Keywords field. The dropdown list has the identifier and the "friendly name" MLA defines for most of the IPTC fields; see the table of identifiers and friendly names in the table below. You can find more information in the <a href="http://www.iptc.org/std/IIM/4.1/specification/IIMV4.1.pdf" title="IPTC-NAA Information Interchange Model Version No. 4.1 specification" target="_blank">IPTC-NAA Information Interchange Model Version No. 4.1 specification</a>.
</dd>
<dt>EXIF Value</dt>
<dd>The EXIF (EXchangeable Image File) metadata, if any, embedded in a JPEG DCT or TIFF Rev 6.0 image file. 
 Though the specification is not currently maintained by any industry or standards organization, almost all camera manufacturers use it. For this category, you can code any of the field names embedded in the image by the camera or editing software. The is no official list of standard field names, so you just have to know the names your camera and software use; field names are case-sensitive. You can find more information in the <a href="http://en.wikipedia.org/wiki/Exchangeable_image_file_format" title="Exchangeable image file format Wikipedia article" target="_blank">Exchangeable image file format</a> article on Wikipedia.
</dd>
<dt>Priority</dt>
<dd>If both the IPTC Value and the EXIF Value are non-blank for a particular image, you can select which of the values will be used for the mapping.
</dd>
<dt>Existing Text</dt>
<dd>Images already in the Media Library will have non-blank values in many fields and may have existing terms in a taxonomy. You can select "Keep" to retain these values or "Replace" to always map a metadata value into the field. For a taxonomy, "Keep" will retain any terms already assigned to the item and "Replace" will delete any existing terms before assigning metadata values as terms.
</dd>
<dt>Parent</dt>
<dd>For hierarchical taxonomies such as Categories you can select one of the existing terms in the taxonomy as the parent term for any terms you are mapping from metadata values. For example, you could define "IPTC Keywords" as a parent and then assign all of the 2#025 values under that parent term.
</dd>
</dl>
<h4>Map All Attachments Now</h4>
<p>
To the right of each table heading is a "Map All Attachments Now" button. When you click one of these buttons, the mapping rules in that table are applied to <strong><em>ALL of the images in the Media Library</em></strong>. This is a great way to bring your media items up to date, but it is <strong><em>NOT REVERSIBLE</em></strong>, so think carefully before you click!
Each button applies the rules in just one category, so you can update taxonomy terms without disturbing standard or custom field values.
</p>
<p>
These buttons <strong><em>DO NOT</em></strong> save any rules changes you've made, so you can make a temporary rule change and process your attachments without disturbing the standing rules.
</p>
<h4>Other mapping techniques</h4>
<p>
There are two other ways you can perform metadata mapping to one or more existing Media Library images:
<dl>
<dt>Single Item Edit/Edit Media screen</dt>
<dd>For WordPress 3.5 and later, you can click the "Map IPTC/EXIF metadata" link in the "Image Metadata" postbox to apply the standing mapping rules to a single attachment.  For WordPress 3.4.x and earlier, you can click the "Map IPTC/EXIF metadata" button on the Single Item Edit screen to apply the standing mapping rules.
</dd>
<dt>Bulk Action edit area</dt>
<dd>To perform mapping for a group of attachments you can use the Bulk Action facility on the main Assistant screen. Check the attachments you want to map, select "edit" from the Bulk Actions dropdown list and click "Apply". The bulk edit area will open with a list of the checked attachments in the left-hand column. You can click the "Map IPTC/EXIF metadata" button in the lower left corner of the area to apply the standing mapping rules to the attachments in the list.
</dd>
</dl>
</p>
<h4>WordPress default title, slug and description mapping</h4>
<p>
When WordPress uploads a new image file that contains IPTC and EXIF metadata it automatically maps metadata values to the title (post_title), name/slug (post_name) and description (post_content) fields. This happens before the MLA mapping rules are applied, so if you want to override the default mapping you must select "Replace" in the "Existing Text" column.
</p>
<p>
The WordPress rules are somewhat complex; consult the source code if you need exact details. Roughly speaking, the priority order for mapping the post_title and post_name values from non-blank IPTC/EXIF metadata is:
<ol style="line-height: 1.25em;  margin-left: 20px ">
<li>EXIF "Title"</li>
<li>EXIF "ImageDescription" (if less than 80 characters)</li>
<li>IPTC 2#105 "headline"</li>
<li>IPTC 2#005 "object-name"</li>
<li>IPTC 2#120 "caption-or-abstract" (if less than 80 characters)</li>
</ol>
The priority order for mapping the post_content value from non-blank IPTC/EXIF metadata is:
<ol style="line-height: 1.25em;  margin-left: 20px ">
<li>EXIF "ImageDescription" (if different from post_title)</li>
<li>IPTC 2#120 "caption-or-abstract" (if different from post_title)</li>
</ol>
</p>
<a name="mla_iptc_identifiers"></a>
&nbsp;
<p>
<a href="#backtotop">Go to Top</a>
</p>
<h3>IPTC Identifiers and Friendly Names</h3>
<table>
<tr><td colspan="3" style="font-weight:bold">Envelope Record</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">model-version</td><td style="padding-right: 10px; vertical-align: top">1#000</td><td style="padding-right: 10px; vertical-align: top">2 octet binary IIM version number</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">destination</td><td style="padding-right: 10px; vertical-align: top">1#005</td><td style="padding-right: 10px; vertical-align: top">Max 1024 characters of Destination (ISO routing information); repeatable</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">file-format</td><td style="padding-right: 10px; vertical-align: top">1#020</td><td style="padding-right: 10px; vertical-align: top">2 octet binary file format number, see IPTC-NAA V4 Appendix A</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">file-format-version</td><td style="padding-right: 10px; vertical-align: top">1#022</td><td style="padding-right: 10px; vertical-align: top">2 octet binary file format version number</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">service-identifier</td><td style="padding-right: 10px; vertical-align: top">1#030</td><td style="padding-right: 10px; vertical-align: top">Max 10 characters of Service Identifier and product</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">envelope-number</td><td style="padding-right: 10px; vertical-align: top">1#040</td><td style="padding-right: 10px; vertical-align: top">8 Character Envelope Number</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">product-id</td><td style="padding-right: 10px; vertical-align: top">1#050</td><td style="padding-right: 10px; vertical-align: top">Max 32 characters subset of provider's overall service; repeatable</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">envelope-priority</td><td style="padding-right: 10px; vertical-align: top">1#060</td><td style="padding-right: 10px; vertical-align: top">1 numeric character of envelope handling priority (not urgency)</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">date-sent</td><td style="padding-right: 10px; vertical-align: top">1#070</td><td style="padding-right: 10px; vertical-align: top">8 numeric characters of Date Sent by service - CCYYMMDD</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">time-sent</td><td style="padding-right: 10px; vertical-align: top">1#080</td><td style="padding-right: 10px; vertical-align: top">11 characters of Time Sent by service - HHMMSS±HHMM</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">coded-character-set</td><td style="padding-right: 10px; vertical-align: top">1#090</td><td style="padding-right: 10px; vertical-align: top">Max 32 characters of control functions, etc.</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">uno</td><td style="padding-right: 10px; vertical-align: top">1#100</td><td style="padding-right: 10px; vertical-align: top">14 to 80 characters of eternal, globally unique identification for objects</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">arm-identifier</td><td style="padding-right: 10px; vertical-align: top">1#120</td><td style="padding-right: 10px; vertical-align: top">2 octet binary Abstract Relationship Model Identifier</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">arm-version</td><td style="padding-right: 10px; vertical-align: top">1#122</td><td style="padding-right: 10px; vertical-align: top">2 octet binary Abstract Relationship Model Version</td></tr>
<tr><td colspan="3" style="font-weight:bold">&nbsp;<br />Application Record</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">record-version</td><td style="padding-right: 10px; vertical-align: top">2#000</td><td style="padding-right: 10px; vertical-align: top">2 octet binary Information Interchange Model, Part II version number</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">object-type-reference</td><td style="padding-right: 10px; vertical-align: top">2#003</td><td style="padding-right: 10px; vertical-align: top">3 to 67 Characters of Object Type Reference number and optional text</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">object-attribute-reference</td><td style="padding-right: 10px; vertical-align: top">2#004</td><td style="padding-right: 10px; vertical-align: top">3 to 67 Characters of Object Attribute Reference number and optional text; repeatable</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">object-name</td><td style="padding-right: 10px; vertical-align: top">2#005</td><td style="padding-right: 10px; vertical-align: top">Max 64 characters of the object name or shorthand reference</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">edit-status</td><td style="padding-right: 10px; vertical-align: top">2#007</td><td style="padding-right: 10px; vertical-align: top">Max 64 characters of the status of the objectdata</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">editorial-update</td><td style="padding-right: 10px; vertical-align: top">2#008</td><td style="padding-right: 10px; vertical-align: top">2 numeric characters of the type of update this object provides</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">urgency</td><td style="padding-right: 10px; vertical-align: top">2#010</td><td style="padding-right: 10px; vertical-align: top">1 numeric character of the editorial urgency of content</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">subject-reference</td><td style="padding-right: 10px; vertical-align: top">2#012</td><td style="padding-right: 10px; vertical-align: top">13 to 236 characters of a structured definition of the subject matter; repeatable</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">category</td><td style="padding-right: 10px; vertical-align: top">2#015</td><td style="padding-right: 10px; vertical-align: top">Max 3 characters of the subject of the objectdata, DEPRECATED</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">supplemental-category</td><td style="padding-right: 10px; vertical-align: top">2#020</td><td style="padding-right: 10px; vertical-align: top">Max 32 characters (each) of further refinement of subject, DEPRECATED; repeatable</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">fixture-identifier</td><td style="padding-right: 10px; vertical-align: top">2#022</td><td style="padding-right: 10px; vertical-align: top">Max 32 characters identifying recurring, predictable content</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">keywords</td><td style="padding-right: 10px; vertical-align: top">2#025</td><td style="padding-right: 10px; vertical-align: top">Max 64 characters (each) of tags; repeatable</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">content-location-code</td><td style="padding-right: 10px; vertical-align: top">2#026</td><td style="padding-right: 10px; vertical-align: top">3 characters of ISO3166 country code or IPTC-assigned code; repeatable</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">content-location-name</td><td style="padding-right: 10px; vertical-align: top">2#027</td><td style="padding-right: 10px; vertical-align: top">Max 64 characters of publishable country/geographical location name; repeatable</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">release-date</td><td style="padding-right: 10px; vertical-align: top">2#030</td><td style="padding-right: 10px; vertical-align: top">8 numeric characters of Release Date (earliest use) - CCYYMMDD</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">release-time</td><td style="padding-right: 10px; vertical-align: top">2#035</td><td style="padding-right: 10px; vertical-align: top">11 characters of Release Time (earliest use) - HHMMSS±HHMM</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">expiration-date</td><td style="padding-right: 10px; vertical-align: top">2#037</td><td style="padding-right: 10px; vertical-align: top">8 numeric characters of Expiration Date (latest use) -  CCYYMDD</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">expiration-time</td><td style="padding-right: 10px; vertical-align: top">2#038</td><td style="padding-right: 10px; vertical-align: top">11 characters of Expiration Time (latest use) - HHMMSS±HHMM</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">special-instructions</td><td style="padding-right: 10px; vertical-align: top">2#040</td><td style="padding-right: 10px; vertical-align: top">Max 256 Characters of editorial instructions, e.g., embargoes and warnings</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">action-advised</td><td style="padding-right: 10px; vertical-align: top">2#042</td><td style="padding-right: 10px; vertical-align: top">2 numeric characters of type of action this object provides to a previous object</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">reference-service</td><td style="padding-right: 10px; vertical-align: top">2#045</td><td style="padding-right: 10px; vertical-align: top">Max 10 characters of the Service ID (1#030) of a prior envelope; repeatable</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">reference-date</td><td style="padding-right: 10px; vertical-align: top">2#047</td><td style="padding-right: 10px; vertical-align: top">8 numeric characters of prior envelope Reference Date (1#070) - CCYYMMDD; repeatable</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">reference-number</td><td style="padding-right: 10px; vertical-align: top">2#050</td><td style="padding-right: 10px; vertical-align: top">8 characters of prior envelope Reference Number (1#040); repeatable</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">date-created</td><td style="padding-right: 10px; vertical-align: top">2#055</td><td style="padding-right: 10px; vertical-align: top">8 numeric characters of intellectual content Date Created - CCYYMMDD</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">time-created</td><td style="padding-right: 10px; vertical-align: top">2#060</td><td style="padding-right: 10px; vertical-align: top">11 characters of intellectual content Time Created - HHMMSS±HHMM</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">digital-creation-date</td><td style="padding-right: 10px; vertical-align: top">2#062</td><td style="padding-right: 10px; vertical-align: top">8 numeric characters of digital representation creation date - CCYYMMDD</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">digital-creation-time</td><td style="padding-right: 10px; vertical-align: top">2#063</td><td style="padding-right: 10px; vertical-align: top">11 characters of digital representation creation time - HHMMSS±HHMM</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">originating-program</td><td style="padding-right: 10px; vertical-align: top">2#065</td><td style="padding-right: 10px; vertical-align: top">Max 32 characters of the program used to create the objectdata</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">program-version</td><td style="padding-right: 10px; vertical-align: top">2#070</td><td style="padding-right: 10px; vertical-align: top">Program Version - Max 10 characters of the version of the program used to create the objectdata</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">object-cycle</td><td style="padding-right: 10px; vertical-align: top">2#075</td><td style="padding-right: 10px; vertical-align: top">1 character where a=morning, p=evening, b=both</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">by-line</td><td style="padding-right: 10px; vertical-align: top">2#080</td><td style="padding-right: 10px; vertical-align: top">Max 32 Characters of the name of the objectdata creator, e.g., the writer, photographer; repeatable</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">by-line-title</td><td style="padding-right: 10px; vertical-align: top">2#085</td><td style="padding-right: 10px; vertical-align: top">Max 32 characters of the title of the objectdata creator; repeatable</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">city</td><td style="padding-right: 10px; vertical-align: top">2#090</td><td style="padding-right: 10px; vertical-align: top">Max 32 Characters of the city of objectdata origin</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">sub-location</td><td style="padding-right: 10px; vertical-align: top">2#092</td><td style="padding-right: 10px; vertical-align: top">Max 32 Characters of the location within the city of objectdata origin</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">province-or-state</td><td style="padding-right: 10px; vertical-align: top">2#095</td><td style="padding-right: 10px; vertical-align: top">Max 32 Characters of the objectdata origin Province or State</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">country-or-primary-location-code</td><td style="padding-right: 10px; vertical-align: top">2#100</td><td style="padding-right: 10px; vertical-align: top">3 characters of ISO3166 or IPTC-assigned code for Country of objectdata origin</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">country-or-primary-location-name</td><td style="padding-right: 10px; vertical-align: top">2#101</td><td style="padding-right: 10px; vertical-align: top">Max 64 characters of publishable country/geographical location name; repeatable</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">original-transmission-reference</td><td style="padding-right: 10px; vertical-align: top">2#103</td><td style="padding-right: 10px; vertical-align: top">Max 32 characters of a code representing the location of original transmission</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">headline</td><td style="padding-right: 10px; vertical-align: top">2#105</td><td style="padding-right: 10px; vertical-align: top">Max 256 Characters of a publishable entry providing a synopsis of the contents of the objectdata</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">credit</td><td style="padding-right: 10px; vertical-align: top">2#110</td><td style="padding-right: 10px; vertical-align: top">Max 32 Characters that identifies the provider of the objectdata (Vs the owner/creator)</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">source</td><td style="padding-right: 10px; vertical-align: top">2#115</td><td style="padding-right: 10px; vertical-align: top">Max 32 Characters that identifies the original owner of the intellectual content</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">copyright-notice</td><td style="padding-right: 10px; vertical-align: top">2#116</td><td style="padding-right: 10px; vertical-align: top">Max 128 Characters that contains any necessary copyright notice</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">contact</td><td style="padding-right: 10px; vertical-align: top">2#118</td><td style="padding-right: 10px; vertical-align: top">Max 128 characters that identifies the person or organisation which can provide further background information; repeatable</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">caption-or-abstract</td><td style="padding-right: 10px; vertical-align: top">2#120</td><td style="padding-right: 10px; vertical-align: top">Max 2000 Characters of a textual description of the objectdata</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">caption-writer-or-editor</td><td style="padding-right: 10px; vertical-align: top">2#122</td><td style="padding-right: 10px; vertical-align: top">Max 32 Characters that the identifies the person involved in the writing, editing or correcting the objectdata or caption/abstract; repeatable</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">rasterized-caption</td><td style="padding-right: 10px; vertical-align: top">2#125</td><td style="padding-right: 10px; vertical-align: top">7360 binary octets of the rasterized caption - 1 bit per pixel, 460x128-pixel image</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">image-type</td><td style="padding-right: 10px; vertical-align: top">2#130</td><td style="padding-right: 10px; vertical-align: top">2 characters of color composition type and information</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">image-orientation</td><td style="padding-right: 10px; vertical-align: top">2#131</td><td style="padding-right: 10px; vertical-align: top">1 alphabetic character indicating the image area layout - P=portrait, L=landscape, S=square</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">language-identifier</td><td style="padding-right: 10px; vertical-align: top">2#135</td><td style="padding-right: 10px; vertical-align: top">2 or 3 aphabetic characters containing the major national language of the object, according to the ISO 639:1988 codes</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">audio-type</td><td style="padding-right: 10px; vertical-align: top">2#150</td><td style="padding-right: 10px; vertical-align: top">2 characters identifying monaural/stereo and exact type of audio content</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">audio-sampling-rate</td><td style="padding-right: 10px; vertical-align: top">2#151</td><td style="padding-right: 10px; vertical-align: top">6 numeric characters representing the audio sampling rate in hertz (Hz)</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">audio-sampling-resolution</td><td style="padding-right: 10px; vertical-align: top">2#152</td><td style="padding-right: 10px; vertical-align: top">2 numeric characters representing the number of bits in each audio sample</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">audio-duration</td><td style="padding-right: 10px; vertical-align: top">2#153</td><td style="padding-right: 10px; vertical-align: top">6 numeric characters of the Audio Duration - HHMMSS</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">audio-outcue</td><td style="padding-right: 10px; vertical-align: top">2#154</td><td style="padding-right: 10px; vertical-align: top">Max 64 characters of the content of the end of an audio objectdata</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">objectdata-preview-file-format</td><td style="padding-right: 10px; vertical-align: top">2#200</td><td style="padding-right: 10px; vertical-align: top">2 octet binary file format of the ObjectData Preview</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">objectdata-preview-file-format-version</td><td style="padding-right: 10px; vertical-align: top">2#201</td><td style="padding-right: 10px; vertical-align: top">2 octet binary particular version of the ObjectData Preview File Format</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">objectdata-preview-data</td><td style="padding-right: 10px; vertical-align: top">2#202</td><td style="padding-right: 10px; vertical-align: top">Max 256000 binary octets containing the ObjectData Preview data</td></tr>
<tr><td colspan="3" style="font-weight:bold">&nbsp;<br />Pre ObjectData Descriptor Record</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">size-mode</td><td style="padding-right: 10px; vertical-align: top">7#010</td><td style="padding-right: 10px; vertical-align: top">1 numeric character - 0=objectdata size not known, 1=objectdata size known at beginning of transfer</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">max-subfile-size</td><td style="padding-right: 10px; vertical-align: top">7#020</td><td style="padding-right: 10px; vertical-align: top">4 octet binary maximum subfile dataset(s) size</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">objectdata-size-announced</td><td style="padding-right: 10px; vertical-align: top">7#090</td><td style="padding-right: 10px; vertical-align: top">4 octet binary objectdata size if known at beginning of transfer</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">maximum-objectdata-size</td><td style="padding-right: 10px; vertical-align: top">7#095</td><td style="padding-right: 10px; vertical-align: top">4 octet binary largest possible objectdata size</td></tr>
<tr><td colspan="3" style="font-weight:bold">&nbsp;<br />ObjectData</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">subfile</td><td style="padding-right: 10px; vertical-align: top">8#010</td><td style="padding-right: 10px; vertical-align: top">Subfile DataSet containing the objectdata itself; repeatable</td></tr>
<tr><td colspan="3" style="font-weight:bold">&nbsp;<br />Post ObjectData Descriptor Record</td></tr>
<tr><td style="padding-right: 10px; vertical-align: top; font-weight:bold">confirmed-objectdata-size</td><td style="padding-right: 10px; vertical-align: top">9#010</td><td style="padding-right: 10px; vertical-align: top">4 octet binary total objectdata size</td></tr>
</table>
<p>
<a href="#backtotop">Go to Top</a>
</p>
</div>