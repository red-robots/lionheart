<?php
$banner_image = get_field('subpage_banner');
$post_type = get_post_type();
get_header(); ?>

	<div id="primary" class="content-area-sidebar single-team-content clear <?php echo ($banner_image) ? 'has-banner':'no-banner';?>">
			<main id="main" class="site-main wrapper content-inner" role="main">
				<?php while ( have_posts() ) : the_post(); ?>

					<?php
						$photo = get_field('photo'); 
						$team_title = get_field('title'); 
						$phone = get_field('phone'); 
						$email = get_field('email'); 
					?>

					<div class="content-left">
						<header class="entry-header<?php echo ($team_title) ? ' has-jobtitle':''; ?>">
							<h1 class="entry-title"><?php the_title(); ?></h1>
							<?php if($team_title) { ?>
							<div class="jobtitle"><?php echo $team_title; ?></div>
							<?php } ?>
						</header>
						<?php the_content(); ?>
					</div>

					<div class="content-right">
						<div class="imagediv">
						<?php if ($photo) { ?>
							<img src="<?php echo $photo['url'];?>" alt="<?php echo $photo['title'];?>" />
						<?php } else { ?>
							<img src="<?php echo get_bloginfo('template_url')?>/images/no-image.gif" alt="" />
						<?php } ?>
						</div>
						<?php if($phone || $email) { ?>
						<div class="contact-info clear">
							<?php if($email) { ?>
								<div class="data">
									<span class="icon email"><i class="fas fa-envelope"></i></span>
									<a class="email" href="mailto:<?php echo $email?>"><?php echo $email?></a>
								</div>
								
							<?php } ?>
							<?php if($phone) { ?>
								<div class="data">
									<span class="icon phone"><i class="fas fa-phone"></i></span>
									<a class="phone" href="tel:<?php echo format_phone_number($phone)?>"><?php echo $phone?></a>
								</div>
							<?php } ?>
						</div>
						<?php } ?>
					</div>
						

				<?php endwhile;  ?>
			</main><!-- #main -->
		</div>
	</div><!-- #primary -->

<?php
get_footer();
