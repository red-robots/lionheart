<?php
$sidebar_title = get_field('sidebar_title');
$sidebar_text = get_field('sidebar_text');
$sidebar_button_text = get_field('sidebar_button_text');
$sidebar_button_link = get_field('sidebar_button_link');

$sidebar_testimonial_title = get_field('sidebar_testimonial_title');
$selected_testimonials = get_field('selected_testimonials');

if($sidebar_text || $selected_testimonials) { ?>
<aside id="secondary" class="widget-area" role="complementary">

	<?php if($sidebar_text) { ?>
		<section class="widget widget_text acf_widget">
			<?php if($sidebar_title) { ?><h2 class="widget-title clear"><?php echo $sidebar_title; ?></h2><?php } ?>
			<div class="textwidget clear">
				<?php echo $sidebar_text; ?>
			</div>
			<?php if($sidebar_button_text && $sidebar_button_link) { ?>
			<div class="widget-button button"><a class="btn" href="<?php echo $sidebar_button_link; ?>"><?php echo $sidebar_button_text; ?></a></div>
			<?php } ?>
		</section>
	<?php } ?>

	<?php if($selected_testimonials) { ?>
		<section class="widget widget_text acf_widget">
			<?php if($sidebar_testimonial_title) { ?><h2 class="widget-title clear"><?php echo $sidebar_testimonial_title; ?></h2><?php } ?>
			<div id="testimonial_widget" class="textwidget clear">
				<div class="widget-testimonial">
					<?php foreach ($selected_testimonials as $st) { 
						$content = $st->post_content; 
						$content = apply_filters('the_content', $content);
						?>
						<div class="entry clear">
							<div class="text"><?php echo $content; ?></div>
							<div class="author"><?php echo $st->post_title; ?></div>
						</div>
					<?php } ?>
				</div>
			</div>
		</section>
	<?php } ?>

</aside>
<?php } ?>
