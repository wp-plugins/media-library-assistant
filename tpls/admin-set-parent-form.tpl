<!-- template="mla-set-parent-div" -->
	<div id="mla-set-parent-div" style="display: none;">
		<input name="parent" id="mla-set-parent-parent" type="hidden" value="">
		<input name="children[]" id="mla-set-parent-children" type="hidden" value="">
		[+mla_find_posts_nonce+]
		<div id="mla-set-parent-head-div"> [+Select Parent+]
			<div id="mla-set-parent-close-div"></div>
		</div>
		<div id="mla-set-parent-inside-div">
			<div id="mla-set-parent-search-div">
				<label class="screen-reader-text" for="mla-set-parent-input">[+Search+]</label>
				<input name="ps" id="mla-set-parent-input" type="text" value="">
				<span class="spinner"></span>
				<input class="button" id="mla-set-parent-search" type="button" value="[+Search+]">
				<div class="clear"></div>
			</div>
			<div id="mla-set-parent-titles-div"> [+For+]: <span id="mla-set-parent-titles"></span> </div>
			<div class="clear"></div>
			<div id="mla-set-parent-response-div"></div>
		</div>
		<div id="mla-set-parent-buttons-div">
			[+mla_set_parent_cancel+]
			[+mla_set_parent_update+]
			<div class="clear"></div>
		</div>
	</div>
	<!-- mla-set-parent-div -->
	<table id="found-0-table" style="display: none">
		<tbody>
			<tr id="found-0-row" class="found-posts">
				<td class="found-radio">
					<input name="found_post_id" id="found-0" type="radio" value="0">
				</td>
				<td>
					<label for="found-0">([+Unattached+])</label>
				</td>
				<td class="no-break">&mdash;</td>
				<td class="no-break">&mdash;</td>
				<td class="no-break">&mdash;</td>
			</tr>
		</tbody>
	</table>
<!-- template="mla-set-parent-form" -->
<form id="mla-set-parent-form" action="[+mla_set_parent_url+]" method="post">
	<input name="mla_admin_action" id="mla-set-parent-action" type="hidden" value="[+mla_set_parent_action+]">
	[+wpnonce+]
	[+mla_set_parent_div+]
</form>
