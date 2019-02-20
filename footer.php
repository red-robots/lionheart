	</div><!-- #content -->

	<footer id="colophon" class="site-footer clear" role="contentinfo">
		<div class="wrapper">
			<?php
				$social['facebook'] = array(
								'icon'=>'fab fa-facebook-f',
								'url'=> get_field('facebook_url','option')
							);
				$social['instagram'] = array(
								'icon'=>'fab fa-instagram',
								'url'=> get_field('instagram_url','option')
							);
			?>

			<div class="foot_social_media">
				<div class="foot-social-media text-center">
		        	<?php foreach($social as $type=>$s) { ?>
		        		<?php if($s['url']) { ?>
		        			<a class="<?php echo $type;?>" href="<?php echo $s['url']?>" target="_blank"><i class="<?php echo $s['icon']?>"></i></a>
		        		<?php } ?>
		        	<?php } ?>
		        </div>
			</div>


			<div class="row clear flex-container">
				<div class="col col-2 foot_menu_items">
					<?php wp_nav_menu( array( 'theme_location' => 'footer', 'menu_id' => 'footer-menu','container'=>false ) ); ?>
				</div>

				<?php
					$selected_agents = get_field('agents_contact','option');
				?>
				<div class="col col-2 foot_agents">
					<div class="selected-agents">
						<p class="a_title">Contact Our Agents</p>
			        	<?php $j=1; foreach($selected_agents as $a) { 
			        		$post_agent_id = $a->ID; 
			        		$agent_name = $a->post_title;
			        		$agent_phone = get_field('phone',$post_agent_id); ?>
			        		<?php if($agent_phone) { ?>
			        		<div class="agent-name<?php echo ($j==1) ? ' first':''?>">
			        			<p class="name"><?php echo $agent_name;?></p>
			        			<p class="phone"><a href="tel:<?php echo format_phone_number($agent_phone)?>"><?php echo $agent_phone;?></a></p>
			        		</div>
			        		<div class="clear"></div>
			        		<?php $j++; } ?>
			        	<?php } ?>
			        </div>
				</div>

			</div>
		</div><!-- wrapper -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
