<?php
/* 
 * Template Name: Content with Sidebar
 */

$banner_image = get_field('subpage_banner');
get_header(); ?>

	<div id="primary" class="content-area-sidebar clear <?php echo ($banner_image) ? 'has-banner':'no-banner';?>">
		<div class="wrapper content-inner">
			<main id="main" class="site-main content-area" role="main">
				<?php while ( have_posts() ) : the_post(); ?>

					<?php if($banner_image) { ?>
						<?php the_content(); ?>
					<?php } else { ?>
						<?php get_template_part( 'template-parts/content', 'page' ); ?>
					<?php } ?>

				<?php endwhile;  ?>
			</main><!-- #main -->

			<?php get_sidebar('custom'); ?>
		</div>
	</div><!-- #primary -->

<?php
get_footer();
