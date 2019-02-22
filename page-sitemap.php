<?php
/* 
 * Template Name: Sitemap
 */
$banner_image = get_field('subpage_banner');
get_header(); ?>

	<div id="primary" class="content-area-sidebar clear <?php echo ($banner_image) ? 'has-banner':'no-banner';?>">
			<main id="main" class="site-main wrapper content-inner" role="main">
				<?php while ( have_posts() ) : the_post(); ?>

					<?php if($banner_image) { ?>
						<?php get_template_part( 'template-parts/content', 'sitemap' ); ?>
					<?php } else { ?>
						<header class="entry-header">
							<h1 class="entry-title"><?php the_title(); ?></h1>
						</header>
						<?php get_template_part( 'template-parts/content', 'sitemap' ); ?>
					<?php } ?>

				<?php endwhile;  ?>
			</main><!-- #main -->
		</div>
	</div><!-- #primary -->

<?php
get_footer();
