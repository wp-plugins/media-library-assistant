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
            <input type="radio" name="[+key+]" [+checked+] value="[+value+]" />
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
                <option [+selected+] value="[+value+]">[+value+]</option>
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
<!-- template="page" -->
<div class="wrap">
<div id="icon-options-general" class="icon32"><br/></div>
<h2>Media Library Assistant Settings</h2>
[+messages+]
<form method="post" class="mla-display-settings-page" id="mla-display-settings-page-id">
    <table class="optiontable">
[+options_list+]
	</table>
    <p class="submit" style="padding-bottom: 0;">
        <input name="save" type="submit" class="button-primary" value="Save Changes" />
        <input name="reset" type="submit" class="button-primary" value="Delete all options and restore default settings"  style="float:right;"/>
    </p>
</form>
</div>
