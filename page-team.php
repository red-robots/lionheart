<?php
/* 
 * Template Name: Our Team
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
				<?php endwhile;  ?>

				<?php
				$args = array(
					'posts_per_page'   => -1,
					'orderby'          => 'menu_order',
					'order'            => 'ASC',
					'post_type'        => 'team',
					'post_status'      => 'publish'
				);
				$items = new WP_Query($args);
				if ( $items->have_posts() ) { ?>
				<div class="team-list clear">
					<div class="row clear flex-container">
						<?php while ( $items->have_posts() ) : $items->the_post();
							$team_name = get_the_title();
							$photo = get_field('photo'); 
							$team_title = get_field('title'); 
							$phone = get_field('phone'); 
							$email = get_field('email'); 
							?>
							<div id="team_<?php the_ID();?>" data-id="<?php the_ID();?>" class="col col-3 team <?php echo ($photo) ? 'has-photo':'no-photo';?>">
								<div class="wrap clear">
									<div class="imagediv">
									<?php if($photo) { ?>
										<img src="<?php echo $photo['url'];?>" alt="<?php echo $photo['title'];?>" />
									<?php } else { ?>
										<img src="<?php echo get_bloginfo('template_url')?>/images/no-image.gif" alt="" />
									<?php } ?>
									</div>
									<div class="description clear text-center">
										<div class="top-info clear">
											<h3 class="staffname"><?php echo $team_name; ?></h3>
											<?php if($team_title) { ?>
											<div class="jobtitle"><?php echo $team_title; ?></div>
											<?php } ?>
										</div>
										<?php if($phone || $email) { ?>
										<div class="contact-info clear">
											<?php if($email) { ?>
												<a class="email" href="mailto:<?php echo $email?>"><i class="fas fa-envelope"></i></a>
											<?php } ?>
											<?php if($phone) { ?>
												<a class="phone desktopview" href="#" data-staff="<?php echo $team_name; ?>" data-phone="<?php echo $phone; ?>"><i class="fas fa-phone"></i></a>
												<a class="phone mobileview" href="tel:<?php echo format_phone_number($phone)?>"><i class="fas fa-phone"></i></a>
											<?php } ?>
										</div>
										<?php } ?>

										<div class="button clear">
											<a class="btn" href="<?php echo get_permalink(); ?>">Full Bio</a>
										</div>
									</div>
								</div>
							</div>
						<?php endwhile; wp_reset_postdata(); ?>
					</div>
				</div>
				<?php } ?>

			</main><!-- #main -->
		</div>
	</div><!-- #primary -->

<?php
get_footer();
