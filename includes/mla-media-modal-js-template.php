<?php
/**
 * Backbone/JavaScript template for Media Library Assistant Media Manager enhancements
 *
 * @package Media Library Assistant
 * @since 1.80
 */

/**
 * Harmless declaration to suppress phpDocumentor "No page-level DocBlock" error
 *
 * @global $post
 */
global $post;
?>
<script type="text/html" id="tmpl-mla-search-box">
    <label class="screen-reader-text" for="media-search-input"><?php _e( 'Search Media', 'media-library-assistant' ); ?>:</label>
    
    <input type="search" id="media-search-input" name="s[mla_search_value]" class="search" value="<?php echo $search_value ?>" placeholder="{{ data.searchBoxPlaceholder }}" size="22" />
	<input type="submit" name="mla_search_submit" id="mla-search-submit" class="button search" value="<?php _e( 'Search', 'media-library-assistant' ); ?>"  /><br>
    <ul class="mla-search-options">
        <li>
            <input type="radio" name="s[mla_search_connector]" value="AND" <?php echo ( 'OR' === $search_connector ) ? '' : 'checked'; ?> />
            <?php _e( 'and', 'media-library-assistant' ); ?>
        </li>
        <li>
            <input type="radio" name="s[mla_search_connector]" value="OR" <?php echo ( 'OR' === $search_connector ) ? 'checked' : ''; ?> />
            <?php _e( 'or', 'media-library-assistant' ); ?>
        </li>
        <li>
            <input type="checkbox" name="s[mla_search_title]" id="search-title" value="title" <?php echo ( in_array( 'title', $search_fields ) ) ? 'checked' : ''; ?> />
            <?php _e( 'Title', 'media-library-assistant' ); ?>
        </li>
        <li>
            <input type="checkbox" name="s[mla_search_name]" id="search-name" value="name" <?php echo ( in_array( 'name', $search_fields ) ) ? 'checked' : ''; ?> />
            <?php _e( 'Name', 'media-library-assistant' ); ?>
        </li>
		<br>
        <li>
            <input type="checkbox" name="s[mla_search_alt_text]" id="search-alt-text" value="alt-text" <?php echo ( in_array( 'alt-text', $search_fields ) ) ? 'checked' : ''; ?> />
            <?php _e( 'ALT Text', 'media-library-assistant' ); ?>
        </li>
        <li>
            <input type="checkbox" name="s[mla_search_excerpt]" id="search-excerpt" value="excerpt" <?php echo ( in_array( 'excerpt', $search_fields ) ) ? 'checked' : ''; ?> />
            <?php _e( 'Caption', 'media-library-assistant' ); ?>
        </li>
        <li>
            <input type="checkbox" name="s[mla_search_content]" id="search-content" value="content" <?php echo ( in_array( 'content', $search_fields ) ) ? 'checked' : ''; ?> />
            <?php _e( 'Description', 'media-library-assistant' ); ?>
        </li>
    </ul>
</script>