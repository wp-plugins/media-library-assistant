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
