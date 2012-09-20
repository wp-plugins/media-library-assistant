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
<div style="background:#ECECEC;border:1px solid #CCC;padding:0 10px;margin-top:5px;border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;">
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
			Quick Edit
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
<div class="wrap">
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
</form>
<h3>Plugin Documentation</h3>
<p>
If you are a developer interested in how this plugin is put together, you should
have a look at the <a title="Consult the phpDocs documentation" href="[+phpDocs_url+]" target="_blank">phpDocs documentation</a>.
</p>
</div>
