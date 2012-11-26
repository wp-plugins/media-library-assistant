<!-- template="page" -->
<a name="backtotop"></a><div class="wrap">
<div id="icon-options-general" class="icon32"><br/></div>
<h2>Media Library Assistant [+version+] Settings</h2>
[+messages+]
[+tablist+]
[+tab_content+]
</div><!-- wrap -->

<!-- template="checkbox" -->
        <tr valign="top"><td style="text-align:right;">
            <input type="checkbox" name="[+key+]" id="[+key+]" [+checked+] value="[+value+]" />
        </td><td>
		    &nbsp;<strong>[+value+]</strong>
            <div style="font-size:8pt;padding-bottom:10px;">&nbsp;&nbsp;[+help+]</div>
		</td></tr>
<!-- template="header" -->
        <tr><td colspan="2">
            <a href="#backtotop">Go to Top</a>
        </td></tr>
        <tr><td colspan="2">
            <h3 id="[+key+]">[+value+]</h3>
        </td></tr>
<!-- template="radio" -->
        <tr valign="top"><th scope="row" style="text-align:right;">
            [+value+]
        </th><td style="text-align:left;">
            <span style="font-size:8pt;padding-bottom:10px;">&nbsp;&nbsp;[+help+]</span>
        </td></tr>
[+options+]
        <tr valign="top"><td colspan="2" style="padding-bottom:10px;">
        </td></tr>
<!-- template="radio-option" -->
        <tr valign="top"><td style="text-align:right;">
            <input type="radio" name="[+key+]" [+checked+] value="[+option+]" />
        </td><td>
            &nbsp;[+value+]
        </td></tr>
<!-- template="select" -->
        <tr valign="top"><th scope="row" style="text-align:right;">
            [+value+]
        </th><td style="text-align:left;">
            <select name="[+key+]" id="[+key+]">
[+options+]
            </select><div style="font-size:8pt;padding-bottom:10px;">&nbsp;&nbsp;[+help+]</div>
        </td></tr>
<!-- template="select-option" -->
                <option [+selected+] value="[+value+]">[+text+]</option>
<!-- template="text" -->
        <tr valign="top"><th scope="row" style="text-align:right;">
            [+value+]
        </th><td style="text-align:left;">
            <input name="[+key+]" id="[+key+]" type="text" size="[+size+]" value="[+text+]" />
            <div style="font-size:8pt;padding-bottom:10px;">&nbsp;&nbsp;[+help+]</div>
        </td></tr>
<!-- template="textarea" -->
        <tr valign="top"><th scope="row" style="text-align:right;">
            [+value+]
        </th><td style="text-align:left;">
            <textarea name="[+key+]" id="[+key+]" rows="[+rows+]" cols="[+cols+]">
            [+text+]
            </textarea>
            <div style="font-size:8pt;padding-bottom:10px;">&nbsp;&nbsp;[+help+]</div>
        </td></tr>
<!-- template="messages" -->
<div class="mla_messages">
<p>
[+messages+]
</p></div>
<!-- template="shortcode-list" -->
<div id="mla-shortcode-list" style="width: 90%; padding-left: 5%; ">
<p>Shortcodes made available by this plugin:</p>
<ol>
[+shortcode_list+]
</ol>
</div>
<!-- template="shortcode-item" -->
<li><code>[[+name+]]</code> - [+description+]</li>
<!-- template="taxonomy-table" -->
        <tr valign="top">
		<td colspan="2" style="text-align:left;">
          <table class="taxonomytable">
		  <thead>
		  <tr>
		    <th scope="col" style="text-align:center">
			Support
			</th>
		    <th scope="col" style="text-align:center">
			Inline Edit
			</th>
		    <th scope="col" style="text-align:center">
			List Filter
			</th>
		    <th scope="col" style="text-align:left">
			Taxonomy
			</th>
			</tr>
			</thead>
			<tbody>
[+taxonomy_rows+]
			</tbody>
          </table>
          <div style="font-size:8pt;padding-bottom:10px;">[+help+]</div>
        </td></tr>
<!-- template="taxonomy-row" -->
        <tr valign="top">
		<td style="text-align:center;">
            <input type="checkbox" name="tax_support[[+key+]]" id="tax_support_[+key+]" [+support_checked+] value="checked" />
        </td>
		<td style="text-align:center;">
            <input type="checkbox" name="tax_quick_edit[[+key+]]" id="tax_quick_edit_[+key+]" [+quick_edit_checked+] value="checked" />
        </td>
		<td style="text-align:center;">
            <input type="radio" name="tax_filter" id="tax_filter_[+key+]" [+filter_checked+] value="[+key+]" />
        </td>
		<td>
            &nbsp;[+name+]
        </td>
		</tr>
<!-- template="tablist" -->
<h2 class="nav-tab-wrapper">
[+tablist+]
</h2>
<!-- template="tablist-item" -->
<a data-tab-id="[+data-tab-id+]" class="nav-tab [+nav-tab-active+]" href="?page=[+settings-page+]&amp;mla_tab=[+data-tab-id+]">[+title+]</a>
<!-- template="general-tab" -->
[+shortcode_list+]
<form method="post" class="mla-display-settings-page" id="mla-display-settings-general-tab">
    <table class="optiontable">
[+options_list+]
	</table>
    <p class="submit" style="padding-bottom: 0;">
        <input name="mla-general-options-save" type="submit" class="button-primary" value="Save Changes" />
        <input name="mla-general-options-reset" type="submit" class="button-primary" value="Delete General options and restore default settings"  style="float:right;"/>
    </p>
[+_wpnonce+]
[+_wp_http_referer+]
</form>
<p>
<a href="#backtotop">Go to Top</a>
</p>
<h3>Support Our Work</h3>
<table width="700" border="0" cellpadding="10">
	<tr>
		<td><a href="http://fairtradejudaica.org/make-a-difference/donate/" title="Donate to FTJ" target="_blank" style="border: none;"><img border="0" src="http://fairtradejudaica.org/wp-content/uploads/newsletter/images/DonateButton.jpg" width="100" height="40" alt="Donate"></a></td>
		<td>This plugin was inspired by my work on the WordPress web site for our nonprofit, Fair Trade Judaica. If you find the Media Library Assistant plugin useful and would like to support a great cause, consider a <a href="http://fairtradejudaica.org/make-a-difference/donate/" title="Donate to FTJ" target="_blank" style="font-weight:bold">tax-deductible donation</a> to our work. Thank you!</td>
	</tr>
</table>
<!-- template="mla-gallery-default" -->
		<td colspan="2" width="500">
            <div style="font-size:8pt;padding-bottom:10px;">[+help+]</div>
		</td>
<!-- template="mla-gallery-delete" -->
		<td width="1%" style="text-align:right;">
            <input type="checkbox" name="[+name+]" id="[+id+]" value="[+value+]" />
        </td><td width="500">
		    &nbsp;<strong>[+value+]</strong>
            <div style="font-size:8pt;padding-bottom:10px;">&nbsp;[+help+]</div>
		</td>
<!-- template="mla-gallery-style" -->
<table width="700">
        <tr valign="top"><th width="1%" scope="row" style="text-align:right;">
            Name:
        </th><td width="1%" style="text-align:left;">
            <input name="[+name_name+]" id="[+name_id+]" type="text" size="15" [+readonly+] value="[+name_text+]" />
        </td>
		[+control_cells+]
		</tr>
        <tr valign="top"><th scope="row" style="text-align:right;">
            Styles:
        </th><td colspan="3" style="text-align:left;">
            <textarea name="[+value_name+]" id="[+value_id+]" rows="11" cols="100" [+readonly+]>[+value_text+]</textarea>
            <div style="font-size:8pt;padding-bottom:5px;">&nbsp;&nbsp;[+value_help+]</div>
        </td></tr>
</table>
<!-- template="mla-gallery-markup" -->
<table width="700">
        <tr valign="top"><th width="1%" scope="row" style="text-align:right;">
            Name:
        </th><td width="1%" style="text-align:left;">
            <input name="[+name_name+]" id="[+name_id+]" type="text" size="15" [+readonly+] value="[+name_text+]" />
        </td>
		[+control_cells+]
		</tr>
        <tr valign="top"><th scope="row" style="text-align:right;">
            Open:
        </th><td colspan="3" style="text-align:left;">
            <textarea name="[+open_name+]" id="[+open_id+]" rows="3" cols="100" [+readonly+]>[+open_text+]</textarea>
            <div style="font-size:8pt;padding-bottom:5px;">&nbsp;&nbsp;[+open_help+]</div>
        </td></tr>
        <tr valign="top"><th scope="row" style="text-align:right;">
            Row&nbsp;Open:
        </th><td colspan="3" style="text-align:left;">
            <textarea name="[+row_open_name+]" id="[+row_open_id+]" rows="3" cols="100" [+readonly+]>[+row_open_text+]</textarea>
            <div style="font-size:8pt;padding-bottom:5px;">&nbsp;&nbsp;[+row_open_help+]</div>
        </td></tr>
        <tr valign="top"><th scope="row" style="text-align:right;">
            Item:
        </th><td colspan="3" style="text-align:left;">
            <textarea name="[+item_name+]" id="[+item_id+]" rows="6" cols="100" [+readonly+]>[+item_text+]</textarea>
            <div style="font-size:8pt;padding-bottom:5px;">&nbsp;&nbsp;[+item_help+]</div>
        </td></tr>
        <tr valign="top"><th scope="row" style="text-align:right;">
            Row&nbsp;Close:
        </th><td colspan="3" style="text-align:left;">
            <textarea name="[+row_close_name+]" id="[+row_close_id+]" rows="3" cols="100" [+readonly+]>[+row_close_text+]</textarea>
            <div style="font-size:8pt;padding-bottom:5px;">&nbsp;&nbsp;[+row_close_help+]</div>
        </td></tr>
        <tr valign="top"><th scope="row" style="text-align:right;">
            Close:
        </th><td colspan="3" style="text-align:left;">
            <textarea name="[+close_name+]" id="[+close_id+]" rows="3" cols="100" [+readonly+]>[+close_text+]</textarea>
            <div style="font-size:8pt;padding-bottom:5px;">&nbsp;&nbsp;[+close_help+]</div>
        </td></tr>
</table>
<hr width="650" align="left" />
<!-- template="mla-gallery-tab" -->
<h3>MLA Gallery Options</h3>
<p><a href="#markup">Go to Markup Templates</a></p>
<form method="post" class="mla-display-settings-page" id="mla-display-settings-mla-gallery-tab">
[+options_list+]
<h4>Style Templates</h4>
    <table class="optiontable">
[+style_options_list+]
	</table>
<a name="markup">&nbsp;<br /></a><h4>Markup Templates</h4>
    <table class="optiontable">
[+markup_options_list+]
	</table>
    <p class="submit" style="padding-bottom: 0;">
        <input name="mla-gallery-options-save" type="submit" class="button-primary" value="Save Changes" />
    </p>
[+_wpnonce+]
[+_wp_http_referer+]
</form>
<!-- template="documentation-tab" -->
<div class="mla-display-settings-page" id="mla-display-settings-documentation-tab">
<p>
<a href="#mla_gallery_templates">Go to <strong>Style and Markup Templates</strong></a>
</p>
<h3>Plugin Code Documentation</h3>
<p>
If you are a developer interested in how this plugin is put together, you should
have a look at the <a title="Consult the phpDocs documentation" href="[+phpDocs_url+]" target="_blank" style="font-size:14px; font-weight:bold">phpDocs documentation</a>.
</p>
<a name="mla_gallery"></a>
<p>
<a href="#backtotop">Go to Top</a>
</p>
<div class="mla_gallery_help" style="width:700px">
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
<h4>Gallery Display Style</h4>
<p>
Two [mla_gallery] parameters provide control over the size and spacing of gallery items without requiring the use of custom Style templates.
</p>
<table>
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
The "mla_debug" parameter controls the display of information about the query parameters and SQL statements used to retrieve gallery items. If you code `mla_debug=true` you will see a lot of information added to the post or page containing the gallery. Of course, this parameter should <strong>only</strong> be used in a development/debugging environment; it's quite ugly.
</p>
<a name="mla_gallery_templates"></a>
&nbsp;
<p>
<a href="#backtotop">Go to Top</a>
</p>
<div class="mla_gallery_help" style="width:700px">
<h3>MLA Gallery Style and Markup Templates</h3>
<p>
The Style and Markup templates give you great flexibility for the content and format of each <code>[mla_gallery]</code>. You can define as many templates as you need.
</p>
Style templates provide gallery-specific CSS inline styles. Markup templates provide the HTML markup for 1) the beginning of the gallery, 2) the beginning of each row, 3) each gallery item, 4) the end of each row and 5) the end of the gallery. The attachment-specific markup parameters let you choose among most of the attachment fields, not just the caption.
<p>
The MLA Gallery tab on the Settings page lets you add, change and delete custom templates. The default tempates are also displayed on this tab for easy reference.
</p>
<p>
In a template, substitution parameters are surrounded by opening ('[+') and closing ('+]') tags to separate them from the template text; see the default templates for many examples.
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
<h3>A Table-based Template Example</h3>
<p>
Here's a small example that shows a gallery using <code>&lt;table&gt;</code> markup.
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

</div>