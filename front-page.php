<?php
get_header(); ?>

<div id="primary" class="fiull-content-area clear">

	<?php while ( have_posts() ) : the_post(); ?>
		<?php /* SERVICES */ ?>
		<?php if( $services = get_field('services') ) { ?>
			<section class="section clear services-list">
				<div class="wrapper">
					<div class="row clear flex-container">
					<?php foreach ($services as $sv) { 
						$s_title = $sv['services_title'];
						$s_caption = $sv['services_description'];
						$s_btn_name = $sv['services_button_text'];
						$s_btn_link = $sv['services_button_link'];

						if($s_title && $s_caption) { ?> 
						<div class="col col-4">
							<div class="wrap clear">
								<div class="inside clear">
									<h3 class="box-title"><?php echo $s_title; ?></h3>
									<div class="caption"><?php echo $s_caption; ?></div>
								</div>
								<?php if($s_btn_name && $s_btn_link) { ?> 
								<div class="button"><a class="btn-grey" href="<?php echo $s_btn_link;?>"><?php echo $s_btn_name;?></a></div>
								<?php } ?>
							</div>
						</div>	
						<?php } ?>

					<?php } ?>
					</div>
				</div>
			</section>
		<?php } ?>
	<?php endwhile;  ?>

	<?php /* LISTING/INSTAGRAM/TESTIMONIALS */ ?>
	<section class="section clear home-bottom-content">
		<div class="wrapper text-center">
			<div class="row clear flex-container">
				<?php get_template_part('template-parts/home-bottom-content');  ?>
			</div>
		</div>
	</section>

</div><!-- #primary -->

<?php
get_footer();
