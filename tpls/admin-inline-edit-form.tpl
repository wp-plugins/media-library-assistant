<!-- template="category_fieldset" -->
          <fieldset class="inline-edit-col-center inline-edit-categories"><div class="inline-edit-col">
[+category_blocks+]          </div></fieldset>
<!-- template="category_block" -->
            <span class="title inline-edit-categories-label">[+tax_html+]
              <span class="catshow">[more]</span>
              <span class="cathide" style="display:none;">[less]</span>
            </span>
            <input type="hidden" name="tax_input[[+tax_attr+]][]" value="0" />
            <ul class="cat-checklist [+tax_attr+]-checklist">
[+tax_checklist+]
            </ul>

<!-- template="tag_fieldset" -->
          <fieldset class="inline-edit-col-right"><div class="inline-edit-col">
[+tag_blocks+]          </div></fieldset>
<!-- template="tag_block" -->
            <label class="inline-edit-tags">
              <span class="title">[+tax_html+]</span>
              <textarea cols="22" rows="1" name="tax_input[[+tax_attr+]]" class="tax_input_[+tax_attr+]"></textarea>
            </label>

<!-- template="page" -->
<form method="get" action="">
  <table style="display: none">
    <tbody id="inlineedit">
      <tr id="inline-edit" class="inline-edit-row inline-edit-row-attachment inline-edit-attachment quick-edit-row quick-edit-row-attachment inline-edit-attachment" style="display: none">
        <td colspan="[+colspan+]" class="colspanchange">
          <fieldset class="inline-edit-col-left">
            <div class="inline-edit-col">
              <h4>Quick Edit</h4>
              <label> <span class="title">Title</span> <span class="input-text-wrap">
                <input type="text" name="post_title" class="ptitle" value="" />
                </span> </label>
              <label> <span class="title">Name/Slug</span> <span class="input-text-wrap">
                <input type="text" name="post_name" value="" />
                </span> </label>
              <label class="inline-edit-image-alt"> <span class="title">Alt Text</span> <span class="input-text-wrap">
                <input type="text" name="image_alt" value="" />
                </span> </label>
              <label class="inline-edit-post-parent"> <span class="title">Parent ID</span> <span class="input-text-wrap">
                <input type="text" name="post_parent" value="" />
                </span> </label>
[+authors+]
          </fieldset>
[+middle_column+]
[+right_column+]
          <p class="submit inline-edit-save">
		  	<a accesskey="c" href="#inline-edit" title="Cancel" class="button-secondary cancel alignleft">Cancel</a>
		  	<a accesskey="s" href="#inline-edit" title="Update" class="button-primary save alignright">Update</a>
            <input type="hidden" name="page" value="mla-menu" />
            <input type="hidden" name="screen" value="media_page_mla-menu" />
			<br class="clear" />
            <span class="error" style="display:none"></span>
          </p>
        </td>
      </tr>
    </tbody>
  </table>
</form>

