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
            <h3 id="[+key+]" style="text-align:left;padding-bottom:5px;border-bottom:1px solid #ccc;font-family:georgia,times,serif;margin-bottom:10px;font-size:16pt;color:#666;font-weight:normal;">[+value+]</h3>
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
<!-- template="shortcodelist" -->
<div id="mla-shortcode-list" style="width: 90%; padding-left: 5%; ">
<p>Shortcodes made available by this plugin:</p>
<ol>
[+shortcode_list+]
</ol>
</div>
<!-- template="shortcodeitem" -->
<li><code>[[+name+]]</code> - [+description+]</li>
<!-- template="taxonomytable" -->
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
<!-- template="taxonomyrow" -->
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
<!-- template="page" -->
<a name="backtotop"></a><div class="wrap">
<div id="icon-options-general" class="icon32"><br/></div>
<h2>Media Library Assistant [+version+] Settings</h2>
[+messages+]
[+shortcode_list+]
<form method="post" class="mla-display-settings-page" id="mla-display-settings-page-id">
    <table class="optiontable">
[+options_list+]
	</table>
    <p class="submit" style="padding-bottom: 0;">
        <input name="mla-options-save" type="submit" class="button-primary" value="Save Changes" />
        <input name="mla-options-reset" type="submit" class="button-primary" value="Delete all options and restore default settings"  style="float:right;"/>
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
<h3>Plugin Documentation</h3>
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
</ul>
<p>
All of the options/parameters documented for the [gallery] shortcode are supported by the [mla_gallery] shortcode; you can find them in the WordPress Codex. Most of the parameters documented for the WP_Query class are also supported; see the WordPress Codex. Because the [mla_gallery] shortcode is designed to work with Media Library items, there are some parameter differences and extensions; these are documented below.
</p>
<h4>Include, Exclude</h4>
<p>
You can use "post_parent=all" to include or exclude attachments regardless of which post or page they are attached to. You can use "post_mime_type=all" to include or exclude attachments of all MIME types, not just images.
</p>
<h4>Size</h4>
<p>
The Size parameter specifies the image size to use for the thumbnail display. Valid values include "thumbnail", "medium", "large", "full" and any other additional image size that was registered with add_image_size(). The default value is "thumbnail". You can use "none" to suppress thumbnail display and substitute the item title string for the image.
</p>
<p>
The [mla_gallery] shortcode supports an additional Size value, "icon", which shows a 60x60 pixel thumbnail for image items and an appropriate icon for non-image items such as PDF or text files.
</p>
<h4>Order, Orderby</h4>
<p>
To order the gallery randomly, use "orderby=rand". To suppress gallery ordering you can use "orderby=none" or "order=rand".
</p>
<p>The Orderby parameter specifies which database field is used to sort the gallery. You can order the gallery by any of the values documented for the WP_Query class reference in the Codex; you are NOT restricted to the values documented for the [gallery] shortcode.
</p>
<h4>Post ID, Post Parent</h4>
<p>
The "id" parameter lets you specify a post ID for your query. If the "id" parameter is not specified, the [mla_gallery] behavior differs from the [gallery] behavior. If your query uses taxonomy or custom field parameters, "author", "author_name" or "s" (search term), then the query will NOT be restricted to items attached to the current post. This lets you build a gallery with any combination of Media Library items that match the parameters.
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
</div>
</div>
