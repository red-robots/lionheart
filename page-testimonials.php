<?php
/* 
 * Template Name: Testimonials
*/

$banner_image = get_field('subpage_banner');
get_header(); ?>

    <div id="primary" class="content-area-sidebar clear <?php echo ($banner_image) ? 'has-banner':'no-banner';?>">
            <main id="main" class="site-main wrapper content-inner" role="main">
                <?php while ( have_posts() ) : the_post(); ?>
                    <?php if($banner_image) { ?>
                        <?php the_content(); ?>
                    <?php } else { ?>
                        <?php get_template_part( 'template-parts/content', 'page' ); ?>
                    <?php } ?>
                <?php endwhile;  ?>

                <?php
                    $args = array(
                        'post_type'         => 'testimonial',
                        'posts_per_page'    => -1,
                        'post_status'       => 'publish',
                    );
                    $testimonials = new WP_Query($args);
                ?>

                <div class="testimonial-outer-wrapper clear">
                    <?php if ( $testimonials->have_posts() ) { ?>
                    <div class="testimonial-list grid flex-container">
                        <div class="grid__col-sizer"></div>
                        <?php $i=1; while ( $testimonials->have_posts() ) : $testimonials->the_post(); ?>
                            <div id="testimonial_<?php the_ID();?>" class="testimonial-post col col-3 grid__item">
                                <div class="inner clear">
                                    <h4 class="name clear"><?php the_title();?></h4>
                                    <div class="copy clear"><?php the_content();?></div>
                                </div>
                            </div>
                        <?php $i++; endwhile; wp_reset_postdata(); ?>
                    </div>
                    <?php } ?>
                </div>

            </main><!-- #main -->
        </div>
    </div><!-- #primary -->

<?php
get_footer();
