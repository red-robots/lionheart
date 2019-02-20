<?php
$setup = get_instagram_setup();
if($setup) {
    $num_photos = ($setup['sb_instagram_num']) ? $setup['sb_instagram_num'] : 4;
    $follow_btn_text = $setup['sb_instagram_follow_btn_text'];
    $opt = $setup['connected_accounts'];
    $instaData = array();
    if($opt) {
        foreach($opt as $insta_id => $data) {
            $instaData = $data;
        }
    }
    $instagram_access_token = ( isset($instaData['access_token']) && $instaData['access_token'] ) ? $instaData['access_token'] : '';
    $instagram_username = ( isset($instaData['username']) && $instaData['username'] ) ? $instaData['username'] : '';
    $instagram_user_id = ( isset($instaData['user_id']) && $instaData['user_id'] ) ? $instaData['user_id'] : '';
    $instagram_link = 'https://www.instagram.com/'.$instagram_username.'/';
    $clean_token = preg_replace("/[^a-zA-Z0-9\.]+/", "", sbi_maybe_clean( $instagram_access_token ) );
    $split_token = explode( '.', $clean_token );
    $uid = $split_token[0];
    ?>
    <script type="text/javascript">
        var insta_u_id = '<?php echo $instagram_user_id; ?>';
        var insta_token = '<?php echo $clean_token; ?>';
        var section_title = 'Instagram';
        var instagram_link = '<?php echo $instagram_link; ?>';
        var follow_btn_text = '<?php echo $follow_btn_text; ?>';
        jQuery(document).ready(function ($) {
            /* ==== INSTAGRAM POSTS ==== */

            if( $("#insta_widget_info").length>0 ) {
                var $instagram_container = $("#insta_widget_info");
                var display_num = $instagram_container.attr('data-num');
                var show_caption = $instagram_container.attr('data-showcaption');
                var num_photos = 4;
                if(display_num) {
                	num_photos = display_num;
                }

                if(insta_u_id && insta_token) {
                    var token = insta_token, userid = insta_u_id;
                    var api_call = 'https://api.instagram.com/v1/users/'+userid+'/media/recent?access_token=' + token;
                    $.ajax({
                        url: api_call, 
                        dataType: 'jsonp',
                        type: 'GET',
                        data: {access_token: token, count: num_photos},
                        success: function(response){
                            if(response.data!=undefined) {
                                var obj = response.data;
                                var content = '<div class="instagram-list"><div class="insta-slide">';
                                    $(obj).each(function(k,v){
                                        var img = v.images;
                                        var img_src = img.standard_resolution.url;
                                        var caption = v.caption.text;
                                        var caption_excerpt = stringTruncate(caption,150);
                                        var instalink = v.link;
                                        content += '<div class="insta-item clear"><a class="instalink" href="'+instalink+'" target="_blank" style="background-image:url('+img_src+')"><img src="'+img_src+'" alt="" />';
                                        if(show_caption && caption) {
                                            content += '<span class="caption"><span class="txtwrap">'+caption_excerpt+'</span></span>';
                                        }
                                        content += '</a></div>';
                                    });
                                    content += '</div></div>';
                                $instagram_container.html(content);
                            }  
                        },
                        complete: function(){
                        	$('.instagram-list').flexslider({
    					    	selector: '.insta-slide > .insta-item',
    					    	animation: "fade",
    					    	smoothHeight: false,
    					        controlNav: false,               
    					        directionNav: false,
    					        slideshowSpeed: 9000
    						});
                        },
                        error: function(data){
                        }
                    });
                }

                var stringTruncate = function(str, length){
                  var dots = str.length > length ? '...' : '';
                  return str.substring(0, length)+dots;
                };

            }
        });
    </script>
<?php } ?>