<?php
	$banner_image = get_field('banner_image');
	$banner_tagline = get_field('banner_tagline');
	$button_text = get_field('button_text');
	$banner_link = get_field('banner_image_button_link');
	$bg_style = '';
	if($banner_image) {
		$bg_style = ' style="background-image:url('.$banner_image['url'].')"';
	}
?>

<div class="home-banner">
	<div class="banner-image"<?php echo $bg_style;?>>
		<div class="banner-overlay"></div>
		<?php if($banner_tagline) { ?>
			<div class="banner-caption">
				<h2 class="tagline"><?php echo $banner_tagline;?></h2>
				<?php if($button_text && $banner_link) { ?>
				<div class="button">
					<a class="btn-red" href="<?php echo $banner_link; ?>"><?php echo $button_text; ?></a>
				</div>
				<?php } ?>
			</div>
		<?php } ?>
	</div>
</div>