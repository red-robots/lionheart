/**
 *	Custom jQuery Scripts
 *	
 *	Developed by: Austin Crane	
 *	Designed by: Austin Crane
 */

jQuery(document).ready(function ($) {
	
	/*
	*
	*	Responsive iFrames
	*
	------------------------------------*/
	var $all_oembed_videos = $("iframe[src*='youtube']");
	
	$all_oembed_videos.each(function() {
	
		$(this).removeAttr('height').removeAttr('width').wrap( "<div class='embed-container'></div>" );
 	
 	});
	
	/*
	*
	*	Flexslider
	*
	------------------------------------*/
	$('.flexslider').flexslider({
		animation: "slide",
	}); // end register flexslider
	
	$('.post-items-wrapper').flexslider({
    	selector: '.post-items > .p_item',
    	animation: "fade",
    	smoothHeight: false,
        controlNav: true,               
        directionNav: false,
        slideshowSpeed: 10000
	});

	$('#testimonial_widget').flexslider({
    	selector: '.widget-testimonial > .slide-item',
    	animation: "fade",
    	smoothHeight: false,
        controlNav: false,               
        directionNav: false,
        slideshowSpeed: 10000
	});

	$(".phone.desktopview").on("click",function(e){
		e.preventDefault();
		var staff_name = $(this).attr('data-staff');
		var number = $(this).attr('data-phone');
		var html_content = '<div class="phoneLabel">Phone</div>';
		html_content += '<div class="phoneNumtx">'+number+'</div>';
		$.alert({
		    title: staff_name,
		    content: html_content,
		    boxWidth: '500px',
    		useBootstrap: false,
    		buttons: {
    			ok: {
    				text: 'CLOSE'
    			}
    		},
    		onOpenBefore: function () {
    			$('.jconfirm').addClass('phone_col');
    		},
    		onClose: function () {
    			$('.jconfirm').removeClass('phone_col');
    		}
		});
	});

	/*
	*
	*	Colorbox
	*
	------------------------------------*/
	$('a.gallery').colorbox({
		rel:'gal',
		width: '80%', 
		height: '80%'
	});
	
	/*
	*
	*	Isotope with Images Loaded
	*
	------------------------------------*/
	var $container = $('#container,.grid').imagesLoaded( function() {
  	$container.isotope({
    // options
	 itemSelector: '.grid__item',
		  masonry: {
				gutter: 0,
				percentPosition: true,
				visibleStyle: { transform: 'translateY(0)', opacity: 1 },
				hiddenStyle: { transform: 'translateY(100px)', opacity: 0 },
			}
 		 });
	});
	
	/*
	*
	*	Equal Heights Divs
	*
	------------------------------------*/
	$('.js-blocks').matchHeight();
	$('.home-bottom-content .widget .textwidget').matchHeight();

	/*
	*
	*	Wow Animation
	*
	------------------------------------*/
	new WOW().init();


	$(document).on("click","#toggleMenu",function(e){
		e.preventDefault();
		$(this).toggleClass('open');
		$('.mobile-navigation').toggleClass('open');
		$('body').toggleClass('open-mobile-menu');
		$('.site-header .logo').toggleClass('fixed');
		var parentdiv = $(".mobile-navigation").outerHeight();
		var mobile_nav_height = $(".mobile-main-nav").outerHeight();
		if(mobile_nav_height>parentdiv) {
			$('.mobile-navigation').addClass("overflow-height");
		}
	});

	$("#primary-menu li").hover(
		function() {
			$(this).children('.sub-menu').stop().slideDown();
		},
		function () {
			$(this).children('.sub-menu').stop().slideUp();
		}
	);

});// END #####################################    END