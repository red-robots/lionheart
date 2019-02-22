<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page();

 *
 * @package ACStarter
 */

get_header(); ?>

	<div id="primary" class="content-area-sidebar no-banner clear">
		<main id="main" class="site-main wrapper content-inner text-center" role="main">

			<header class="page-header">
				<h1 class="entry-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'acstarter' ); ?></h1>
			</header><!-- .page-header -->

			<div class="page-content">
				<p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below?', 'acstarter' ); ?></p>
				<?php get_template_part( 'template-parts/content', 'sitemap' ); ?>
			</div>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
