<?php
/* 
 * Template Name: Property Search
 */

$banner_image = get_field('subpage_banner');
get_header(); ?>

	<div id="primary" class="content-area-sidebar clear <?php echo ($banner_image) ? 'has-banner':'no-banner';?>">
			<main id="main" class="site-main wrapper" role="main">
				<?php while ( have_posts() ) : the_post(); ?>

					<?php if($banner_image) { ?>
						<?php the_content(); ?>
					<?php } else { ?>
						<?php get_template_part( 'template-parts/content', 'page' ); ?>
					<?php } ?>

					<?php 
					$iframe = get_field('property_search_iframe');
					if($iframe) { ?>
						<div class="property-search-iframe"><?php echo $iframe; ?></div>
					<?php } ?>

				<?php endwhile;  ?>
			</main><!-- #main -->
		</div>
	</div><!-- #primary -->

<?php
get_footer();