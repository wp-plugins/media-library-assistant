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

<!-- template="iptc-exif-standard-table" -->
        <tr valign="top">
		<td colspan="2" style="text-align:left;">
          <table class="iptc-exif-standard-table">
		  <thead>
		  <tr>
		    <th scope="col" style="text-align:center">
			Field Title
			</th>
		    <th scope="col" style="text-align:center">
			IPTC Value
			</th>
		    <th scope="col" style="text-align:center">
			EXIF Value
			</th>
		    <th scope="col" style="text-align:left">
			Priority
			</th>
		    <th scope="col" style="text-align:left">
			Existing Text
			</th>
			</tr>
			</thead>
			<tbody>
[+table_rows+]
			</tbody>
          </table>
          <div style="font-size:8pt;padding-bottom:10px;">[+help+]</div>
        </td></tr>

<!-- template="iptc-exif-taxonomy-table" -->
        <tr valign="top">
		<td colspan="2" style="text-align:left;">
          <table class="iptc-exif-taxonomy-table">
		  <thead>
		  <tr>
		    <th scope="col" style="text-align:center">
			Field Title
			</th>
		    <th scope="col" style="text-align:center">
			IPTC Value
			</th>
		    <th scope="col" style="text-align:center">
			EXIF Value
			</th>
		    <th scope="col" style="text-align:center">
			Priority
			</th>
		    <th scope="col" style="text-align:center">
			Existing Text
			</th>
		    <th scope="col" style="text-align:center">
			Parent
			</th>
			</tr>
			</thead>
			<tbody>
[+table_rows+]
			</tbody>
          </table>
          <div style="font-size:8pt;padding-bottom:10px;">[+help+]</div>
        </td></tr>

<!-- template="iptc-exif-custom-table" -->
        <tr valign="top">
		<td colspan="2" style="text-align:left;">
          <table class="iptc-exif-custom-table">
		  <thead>
		  <tr>
		    <th scope="col" style="text-align:center">
			Field Title
			</th>
		    <th scope="col" style="text-align:center">
			IPTC Value
			</th>
		    <th scope="col" style="text-align:center">
			EXIF Value
			</th>
		    <th scope="col" style="text-align:left">
			Priority
			</th>
		    <th scope="col" style="text-align:left">
			Existing Text
			</th>
			</tr>
			</thead>
			<tbody>
[+table_rows+]
			</tbody>
          </table>
          <div style="font-size:8pt;padding-bottom:10px;">[+help+]</div>
        </td></tr>

<!-- template="iptc-exif-select-option" -->
                <option [+selected+] value="[+value+]">[+text+]</option>

<!-- template="iptc-exif-select" -->
            <select name="iptc_exif_mapping[[+array+]][[+key+]][[+element+]]" id="iptc_exif_taxonomy_parent_[+key+]">
[+options+]
            </select>

<!-- template="iptc-exif-standard-row" -->
        <tr valign="top">
		<td>
            [+name+]&nbsp;
        </td>
		<td style="text-align:left;">
            <select name="iptc_exif_mapping[standard][[+key+]][iptc_value]" id="iptc_exif_standard_iptc_field_[+key+]">
[+iptc_field_options+]
            </select>
        </td>
		<td style="text-align:center;">
            <input name="iptc_exif_mapping[standard][[+key+]][exif_value]" id="iptc_exif_standard_exif_field_[+key+]" type="text" size="[+exif_size+]" value="[+exif_text+]" />
        </td>
		<td style="text-align:left;">
            <select name="iptc_exif_mapping[standard][[+key+]][iptc_first]" id="iptc_exif_standard_priority_[+key+]">
                <option [+iptc_selected+] value="1">IPTC</option>
                <option [+exif_selected+] value="">EXIF</option>
            </select>
        </td>
		<td style="text-align:left;">
            <select name="iptc_exif_mapping[standard][[+key+]][keep_existing]" id="iptc_exif_standard_existing_[+key+]">
                <option [+keep_selected+] value="1">Keep</option>
                <option [+replace_selected+] value="">Replace</option>
            </select>
        </td>
		</tr>

<!-- template="iptc-exif-taxonomy-row" -->
        <tr valign="top">
		<td>
            [+name+]&nbsp;
			<input type="hidden" id="iptc_exif_taxonomy_name_field_[+key+]" name="iptc_exif_mapping[taxonomy][[+key+]][name]" value="[+name+]" />
			<input type="hidden" id="iptc_exif_taxonomy_hierarchical_field_[+key+]" name="iptc_exif_mapping[taxonomy][[+key+]][hierarchical]" value="[+hierarchical+]" />
        </td>
		<td style="text-align:left;">
            <select name="iptc_exif_mapping[taxonomy][[+key+]][iptc_value]" id="iptc_exif_taxonomy_iptc_field_[+key+]">
[+iptc_field_options+]
            </select>
        </td>
		<td style="text-align:center;">
            <input name="iptc_exif_mapping[taxonomy][[+key+]][exif_value]" id="iptc_exif_taxonomy_exif_field_[+key+]" type="text" size="[+exif_size+]" value="[+exif_text+]" />
        </td>
		<td style="text-align:left;">
            <select name="iptc_exif_mapping[taxonomy][[+key+]][iptc_first]" id="iptc_exif_taxonomy_priority_[+key+]">
                <option [+iptc_selected+] value="1">IPTC</option>
                <option [+exif_selected+] value="">EXIF</option>
            </select>
        </td>
		<td style="text-align:left;">
            <select name="iptc_exif_mapping[taxonomy][[+key+]][keep_existing]" id="iptc_exif_taxonomy_existing_[+key+]">
                <option [+keep_selected+] value="1">Keep</option>
                <option [+replace_selected+] value="">Replace</option>
            </select>
        </td>
		<td style="text-align:left;">
[+parent_select+]
        </td>
		</tr>

<!-- template="iptc-exif-custom-row" -->
        <tr valign="top">
		<td>
            [+name+]&nbsp;
        </td>
		<td style="text-align:left;">
            <select name="iptc_exif_mapping[custom][[+key+]][iptc_value]" id="iptc_exif_custom_iptc_field_[+key+]">
[+iptc_field_options+]
            </select>
        </td>
		<td style="text-align:center;">
            <input name="iptc_exif_mapping[custom][[+key+]][exif_value]" id="iptc_exif_custom_exif_field_[+key+]" type="text" size="[+exif_size+]" value="[+exif_text+]" />
        </td>
		<td style="text-align:left;">
            <select name="iptc_exif_mapping[custom][[+key+]][iptc_first]" id="iptc_exif_custom_priority_[+key+]">
                <option [+iptc_selected+] value="1">IPTC</option>
                <option [+exif_selected+] value="">EXIF</option>
            </select>
        </td>
		<td style="text-align:left;">
            <select name="iptc_exif_mapping[custom][[+key+]][keep_existing]" id="iptc_exif_custom_existing_[+key+]">
                <option [+keep_selected+] value="1">Keep</option>
                <option [+replace_selected+] value="">Replace</option>
            </select>
        </td>
		</tr>

<!-- template="default-style" -->
<style type='text/css'>
	#[+selector+] {
		margin: auto;
		width: 100%;
	}
	#[+selector+] .gallery-item {
		float: [+float+];
		margin: [+margin+]%;
		text-align: center;
		width: [+itemwidth+]%;
	}
	#[+selector+] .gallery-item .gallery-icon img {
		border: 2px solid #cfcfcf;
	}
	#[+selector+] .gallery-caption { vertical-align:
		margin-left: 0;
	}
</style>
<!-- see mla_gallery_shortcode() in media-library-assistant/includes/class-mla-shortcodes.php -->

<!-- template="default-open-markup" -->
<div id='[+selector+]' class='gallery galleryid-[+id+] gallery-columns-[+columns+] gallery-size-[+size_class+]'>

<!-- template="default-row-open-markup" -->
<!-- row-open -->

<!-- template="default-item-markup" -->
<[+itemtag+] class='gallery-item'>
	<[+icontag+] class='gallery-icon'>
		[+link+]
	</[+icontag+]>
	<[+captiontag+] class='wp-caption-text gallery-caption'>
		[+caption+]
	</[+captiontag+]>
</[+itemtag+]>

<!-- template="default-row-close-markup" -->
<br style="clear: both" />

<!-- template="default-close-markup" -->
</div>
