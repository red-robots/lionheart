<?php
$banner_image = get_field('subpage_banner');
$banner_tagline = get_field('banner_tagline');
$button_text = get_field('button_text');
$banner_link = get_field('banner_image_button_link');
$bg_style = '';
if($banner_image) {
	$bg_style = ' style="background-image:url('.$banner_image['url'].')"'; ?>

<div class="subpage-banner">
	<div class="banner-image"<?php echo $bg_style;?>>
		<div class="banner-caption">
			<h2 class="tagline"><?php echo get_the_title();?></h2>
		</div>
	</div>
</div>

<?php } ?>