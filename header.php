<?php
/**
 * The header for theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package ACStarter
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet">
<script defer src="<?php bloginfo( 'template_url' ); ?>/assets/svg-with-js/js/fontawesome-all.js"></script>

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="site clear">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'acstarter' ); ?></a>
	<?php
		$home_logo = get_field('home_logo','option');
		$subpage_logo = get_field('subpage_logo','option');
		$the_logo = '';
		if( is_home() || is_front_page() ) {
			$home_class = 'home';
			$the_logo = $home_logo['url'];
		} else {
			$home_class = 'sub';
			$the_logo = $subpage_logo['url'];
		}

		$social = array();
		// $social['phone'] = array(
		// 				'icon'=>'fas fa-phone',
		// 				'url'=> get_field('phone','option')
		// 			);
		// $social['facebook'] = array(
		// 				'icon'=>'fab fa-facebook-f',
		// 				'url'=> get_field('facebook_url','option')
		// 			);
		// $social['instagram'] = array(
		// 				'icon'=>'fab fa-instagram',
		// 				'url'=> get_field('instagram_url','option')
		// 			);
		
	?>

	<header id="masthead" class="site-header" role="banner">
		<div class="wrapper">
			<div class="logo-section clear">
				<div class="logo">
					<?php if($the_logo) { ?>
						<a class="img" href="<?php echo get_site_url()?>">
							<img class="desktop-logo" src="<?php echo $the_logo; ?>" alt="<?php echo get_bloginfo('name'); ?>" />
							<?php if($home_logo) { ?>
							<img class="mobile-only" src="<?php echo $home_logo['url']; ?>" alt="<?php echo get_bloginfo('name'); ?>" />
							<?php } ?>
						</a>
					<?php } else { ?>
						<a class="text" href="<?php echo get_site_url()?>"><?php echo get_bloginfo('name'); ?></a>
					<?php } ?>
		        </div>
		        <div class="social-media">
		        	<?php if($social) { ?>
			        	<?php foreach($social as $type=>$s) { ?>
			        		<?php if($s['url']) { ?>
			        			<?php if($type=='phone') { ?>
			        				<a class="<?php echo $type;?>" href="tel:<?php echo format_phone_number($s['url'])?>"><span class="icon"><i class="<?php echo $s['icon']?>"></i></span></a>
			        			<?php } else { ?>
			        				<a class="<?php echo $type;?>" href="<?php echo $s['url']?>" target="_blank"><i class="<?php echo $s['icon']?>"></i></a>
			        			<?php } ?>
			        		<?php } ?>
			        	<?php } ?>
		        	<?php } ?>
		        	<a href="#" id="toggleMenu" class="toggleMenu"><span></span></a>
		        </div>
			</div>
	
			<nav id="site-navigation" class="main-navigation clear <?php echo $home_class;?>" role="navigation">
				<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_id' => 'primary-menu','container'=>false ) ); ?>
			</nav><!-- #site-navigation -->

			<nav id="mobile-navigation" class="mobile-main-navigation <?php echo $home_class;?>">
				<div class="mobile-nav-inner clear"><?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_id' => 'mobile-primary-menu','container_class'=>'mobile_nav' ) ); ?></div>
			</nav>
		</div><!-- wrapper -->
	</header><!-- #masthead -->

	<?php if( is_home() || is_front_page() ) { 
		get_template_part('template-parts/banner','home'); 
	} else { 
		get_template_part('template-parts/banner','subpage'); 
	} ?>


	<div id="content" class="site-content clear">
