<?php
function wpb_load_widget() {
    register_widget( 'featlisting_widget' );
    register_widget( 'mywp_widget' );
    register_widget( 'myinsta_widget' );
}
add_action( 'widgets_init', 'wpb_load_widget' );

//FEATURED LISTING
class featlisting_widget extends WP_Widget {
    function __construct() {
        parent::__construct(
        'featlisting_widget', 
        __('Featured Listings', 'featlisting_widget_domain'), 
        array( 'description' => __( 'Display featured listing', 'featlisting_widget_domain' ), ) 
        );
    }
 
    // Creating widget front-end 
    public function widget( $args, $instance ) {
        $title = apply_filters( 'widget_title', $instance['title'] );
         
        // before and after widget arguments are defined by themes
        echo $args['before_widget'];
        if ( ! empty( $title ) )
        echo $args['before_title'] . $title . $args['after_title'];
        
        //Output
        $html_content = '';
        $property_page_ids = ( isset( $instance[ 'property_page_id' ] ) ) ? $instance[ 'property_page_id' ] : '';
        $selected_ids = ($property_page_ids) ? explode(",",$property_page_ids) : array();
        if($selected_ids) {
            foreach($selected_ids as $id) {
                if( $post = get_post($id) ) {
                    $post_id = $post->ID;
                    $post_title = $post->post_title;
                    $img = get_field('photo',$post_id);
                    $description = get_field('description',$post_id);
                    $link = get_field('property_link',$post_id);
                    $site_url = get_site_url();
                    $parts_a = parse_url($site_url);
                    $host_a = $parts_a['host'];
                    $target = '';
                    if($link) {
                        $parts_b = parse_url($link);
                        $host_b = $parts_b['host'];
                        if($host_a!=$host_b) {
                            $target = ' target="_blank"';
                        }
                    }

                    $html_content .= '<div class="textwidget"><div class="listing-info">';
                        if($img) {
                            $html_content .= '<div class="imagewrap"><img src="'.$img['url'].'" alt="'.$img['title'].'" /></div>';
                        }
                        $html_content .= '<div class="textwrap clear">';
                        $html_content .= '<h3 class="property-name">'.$post_title.'</h3>';
                        if($description) {
                            $html_content .= '<div class="description">'.$description.'</div>';
                        }
                        if($link) {
                            $html_content .= '<div class="button"><a href="'.$link.'"'.$target.'>Read More</a></div>';
                        }
                        $html_content .= '</div>';
                    $html_content .= '</div></div>';
                }
            }
        }

        
        echo $html_content;
        echo $args['after_widget'];
    }
             
    // Widget Backend 
    public function form( $instance ) {
        global $wpdb;
        $title = ( isset( $instance[ 'title' ] ) ) ? $instance[ 'title' ] : '';
        $property_page_ids = ( isset( $instance[ 'property_page_id' ] ) ) ? $instance[ 'property_page_id' ] : '';
        $selected_ids = ($property_page_ids) ? explode(",",$property_page_ids) : array();
        /* Query Pages */
        $result = $wpdb->get_results( "SELECT ID,post_title FROM $wpdb->posts WHERE post_type = 'properties' AND post_status='publish' ORDER BY post_title ASC" );
        
        $the_options = array();
        if($selected_ids) {
            if($result) {
                $array1 = array();
                $array2 = array();
                foreach($result as $k=>$row) {
                    $v_id = $row->ID;
                    if(!in_array($v_id, $selected_ids)) {
                        $array2[$v_id] = $row;
                    }
                }

                foreach($selected_ids as $id) {
                    foreach($result as $row) {
                        $v_id = $row->ID;
                        if($id==$v_id) {
                            $array1[$id] = $row;
                        }
                    }
                }

                $the_options = array_merge($array1,$array2);
            }
        } else {
            $the_options = $result;
        }
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'property_page_id' ); ?>"><?php _e( 'Featured Listing:' ); ?></label> 
            <input type="hidden" id="hidden_<?php echo $this->get_field_id( 'property_page_id' ); ?>" class="page_ids_input" name="<?php echo $this->get_field_name( 'property_page_id' ); ?>" value="<?php echo esc_attr( $property_page_id ); ?>" />
            <select id="<?php echo $this->get_field_id( 'property_page_id' ); ?>" class="jsselect2 select-property-page" multiple="multiple" style="width:100%;">
                <option value="-1">Select...</option>
                <?php if($the_options) { ?>
                    <?php foreach($the_options as $row) { 
                        $page_id = $row->ID; $page_name = $row->post_title; 
                        $is_selected = ( $selected_ids && in_array($page_id,$selected_ids) ) ? ' selected':'';
                        ?>
                        <option value="<?php echo $page_id; ?>"<?php echo $is_selected; ?>><?php echo $page_name; ?></option>
                    <?php } ?>
                <?php } ?>
            </select>
        </p>
        <?php
    }
         
    // Updating widget replacing old instances with new
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['property_page_id'] = ( ! empty( $new_instance['property_page_id'] ) ) ? strip_tags( $new_instance['property_page_id'] ) : '';
        return $instance;
    }
}
 
// TESTIMONIAL WIDGET
class mywp_widget extends WP_Widget {
 
    function __construct() {
        parent::__construct(
         
        // Base ID of your widget
        'mywp_widget', 
         
        // Widget name will appear in UI
        __('Testimonial Feeds', 'mywp_widget_domain'), 
         
        // Widget description
        array( 'description' => __( 'Display testimonial feeds', 'mywp_widget_domain' ), ) 
        );
    }
 
    // Creating widget front-end 
    public function widget( $args, $instance ) {
        $title = apply_filters( 'widget_title', $instance['title'] );
         
        // before and after widget arguments are defined by themes
        echo $args['before_widget'];
        if ( ! empty( $title ) )
        echo $args['before_title'] . $title . $args['after_title'];
         
        //Output
        $num_items = ( isset( $instance[ 'num_items' ] ) ) ? $instance[ 'num_items' ] : 5;
        $num_words = ( isset( $instance[ 'num_words' ] ) ) ? $instance[ 'num_words' ] : 300;
        $view_button_name = ( isset( $instance[ 'view_button_name' ] ) ) ? $instance[ 'view_button_name' ] : '';
        $view_button_link = ( isset( $instance[ 'view_button_link' ] ) ) ? $instance[ 'view_button_link' ] : '';
        $page_id = '';
        if($view_button_link>0) {
            $page_id = $view_button_link;
        }
        $shortcode_content = do_shortcode('[testimonial-feed  words='.$num_words.' display='.$num_items.']');
        $html_content = '';
        if($shortcode_content) {
            $html_content = '<div class="textwidget"><div class="testimonial_widget_text">';
            $html_content .= $shortcode_content;
                if($view_button_name && $page_id) {
                    $page_link = get_permalink($page_id);
                    $html_content .= '<div class="button clear"><a class="btn" href="'.$page_link.'">'.$view_button_name.'</a></div>';
                }
            $html_content .= '</div></div>';
        }
        echo $html_content;
        echo $args['after_widget'];
    }
             
    // Widget Backend 
    public function form( $instance ) {
        global $wpdb;
        $title = ( isset( $instance[ 'title' ] ) ) ? $instance[ 'title' ] : '';
        $num_items = ( isset( $instance[ 'num_items' ] ) ) ? $instance[ 'num_items' ] : '';
        $num_words = ( isset( $instance[ 'num_words' ] ) ) ? $instance[ 'num_words' ] : '';
        $view_button_name = ( isset( $instance[ 'view_button_name' ] ) ) ? $instance[ 'view_button_name' ] : '';
        $view_button_link = ( isset( $instance[ 'view_button_link' ] ) ) ? $instance[ 'view_button_link' ] : '';

        /* Query Pages */
        $result = $wpdb->get_results( "SELECT ID,post_title FROM $wpdb->posts WHERE post_type = 'page' AND post_status='publish' ORDER BY post_title ASC" );
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'num_items' ); ?>"><?php _e( 'Number of items:' ); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'num_items' ); ?>" name="<?php echo $this->get_field_name( 'num_items' ); ?>" type="text" value="<?php echo esc_attr( $num_items ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'num_words' ); ?>"><?php _e( '(Truncate) Number of words :' ); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'num_words' ); ?>" name="<?php echo $this->get_field_name( 'num_words' ); ?>" type="text" value="<?php echo esc_attr( $num_words ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'view_button_name' ); ?>"><?php _e( 'Button Name:' ); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'view_button_name' ); ?>" name="<?php echo $this->get_field_name( 'view_button_name' ); ?>" type="text" value="<?php echo esc_attr( $view_button_name ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'view_button_link' ); ?>"><?php _e( 'Button Link:' ); ?></label> 
            <select id="<?php echo $this->get_field_id( 'view_button_link' ); ?>" class="jsselect2 tt-select-page" name="<?php echo $this->get_field_name( 'view_button_link' ); ?>" data-ui="1" data-multiple="0" data-placeholder="Select" data-allow_null="0" aria-hidden="true" style="width:100%;">
                <option value="-1">Select...</option>
                <?php if($result) { ?>
                    <?php foreach($result as $row) { $page_id = $row->ID; $page_name = $row->post_title; ?>
                        <option value="<?php echo $page_id; ?>"<?php echo ($view_button_link==$page_id) ? ' selected':''?>><?php echo $page_name; ?></option>
                    <?php } ?>
                <?php } ?>
            </select>
        </p>
        <?php 
    }
         
    // Updating widget replacing old instances with new
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['num_items'] = ( ! empty( $new_instance['num_items'] ) ) ? strip_tags( $new_instance['num_items'] ) : '';
        $instance['num_words'] = ( ! empty( $new_instance['num_words'] ) ) ? strip_tags( $new_instance['num_words'] ) : '';
        $instance['view_button_name'] = ( ! empty( $new_instance['view_button_name'] ) ) ? strip_tags( $new_instance['view_button_name'] ) : '';
        $instance['view_button_link'] = ( ! empty( $new_instance['view_button_link'] ) ) ? strip_tags( $new_instance['view_button_link'] ) : '';
        return $instance;
    }
} 

// INSTAGRAM WIDGET
class myinsta_widget extends WP_Widget {
    function __construct() {
        parent::__construct(
        'myinsta_widget', 
        __('Instagram Feeds', 'mywp_widget_domain'), 
        array( 'description' => __( 'Display Instagram feeds', 'myinsta_widget_domain' ), ) 
        );
    }

    // Creating widget front-end 
    public function widget( $args, $instance ) {
        $title = apply_filters( 'widget_title', $instance['title'] );
         
        // before and after widget arguments are defined by themes
        echo $args['before_widget'];
        if ( ! empty( $title ) )
        echo $args['before_title'] . $title . $args['after_title'];

        $insta_num_items = ( isset( $instance[ 'insta_num_items' ] ) ) ? $instance[ 'insta_num_items' ] : 4;
        $insta_show_caption = ( isset( $instance[ 'insta_show_caption' ] ) ) ? $instance[ 'insta_show_caption' ] : 'No';
        $insta_show_caption = strtolower($insta_show_caption);
        $is_show_caption = false;
        if($insta_show_caption=='yes') {
            $is_show_caption = 1;
        }
        $insta_content = '<div class="textwidget clear"><div id="insta_widget_info" class="insta_widget_info clear" data-num="'.$insta_num_items.'" data-showcaption="'.$is_show_caption.'"></div></div>';
        echo $insta_content;
        echo $args['after_widget'];
    }
             
    // Widget Backend 
    public function form( $instance ) {
        $title = ( isset( $instance[ 'title' ] ) ) ? $instance[ 'title' ] : '';
        $insta_num_items = ( isset( $instance[ 'insta_num_items' ] ) ) ? $instance[ 'insta_num_items' ] : '';
        $insta_show_caption = ( isset( $instance[ 'insta_show_caption' ] ) ) ? $instance[ 'insta_show_caption' ] : '';
        ?>

        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'insta_num_items' ); ?>"><?php _e( 'Number of items:' ); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'insta_num_items' ); ?>" name="<?php echo $this->get_field_name( 'insta_num_items' ); ?>" type="text" value="<?php echo esc_attr( $insta_num_items ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'insta_show_caption' ); ?>"><?php _e( 'Show caption:' ); ?></label> 
            <?php
                $insta_options = array('Yes','No');
                foreach($insta_options as $io) { 
                $is_checked = ($io==$insta_show_caption) ? ' checked':'';  ?>
                <span class="insta_caption_<?php echo $io;?> insta_caption">
                    <input type="radio" id="insta_caption_<?php echo $io;?>" name="<?php echo $this->get_field_name( 'insta_show_caption' ); ?>" value="<?php echo esc_attr( $io ); ?>" <?php echo $is_checked?>/>   
                    <span class="text"><?php echo $io;?></span>
                </span>
            <?php } ?>
        </p>
        <?php 
    }
         
    // Updating widget replacing old instances with new
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['insta_num_items'] = ( ! empty( $new_instance['insta_num_items'] ) ) ? strip_tags( $new_instance['insta_num_items'] ) : '';
        $instance['insta_show_caption'] = ( ! empty( $new_instance['insta_show_caption'] ) ) ? strip_tags( $new_instance['insta_show_caption'] ) : '';
        return $instance;
    }
}


function my_custom_widgets_admin_head() { ?>
    <style type="text/css">
        span.insta_caption {
            position: relative;
            padding-left: 20px;
            margin-right: 15px;
        }
        span.insta_caption input {
            margin: 0 0;
            position: absolute;
            top: 0;
            left: 0;
        }
        .widget .select2-container--default .select2-selection--multiple .select2-selection__choice {
            margin-top: 3px;
            margin-bottom: 0;
        }
        .widget .select2-search.select2-search--inline {
            margin-bottom: 0;
        }
    </style>
<?php
}
add_action( 'admin_head', 'my_custom_widgets_admin_head' );

add_action('admin_footer', 'my_admin_footer_function', 100);
function my_admin_footer_function() { 
    echo _fire_widget_js(); 
}

function _fire_widget_js() {
    ob_start(); ?>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {           

           jQuery(document).on("change",".jsselect2",function(e){
                var s_id = e.target.id;
                var parent = $('#'+s_id).parent('.widget');
                var options = $(this).val();
                var ids = '';
                if(options) {
                    ids = options.join(',');
                }
                $('#hidden_' + s_id).val(ids);
           });

        });

        ( function( $ ){
            function initJsSelect2( widget ) {
                widget.find( '.jsselect2' ).select2({
                    placeholder: "Select...",
                    allowClear: true,
                    sorter: function(results) {
                        var query = $('.select2-search__field').val().toLowerCase();
                        return results.sort(function(a, b) {
                          return a.text.toLowerCase().indexOf(query) -
                            b.text.toLowerCase().indexOf(query);
                        });
                      }
                });

                var updateIndex = function(e, ui) {
                    var selections = widget.find( 'select.jsselect2' );
                    var ids = '';
                    var new_ids = [];
                    widget.find("li.select2-selection__choice").each(function(){
                        var txt = $(this).attr('title');
                        selections.find('option').each(function(){
                            var option_text = $(this).text();
                            var option_value = $(this).val();
                            if(txt==option_text) {
                                new_ids.push(option_value);
                            }
                        });
                    });
                    if(new_ids.length>0) {
                        ids = new_ids.join(',');
                    }
                    widget.find("input.page_ids_input").val(ids);
                    widget.find("input.page_ids_input").trigger("change");
                };

                widget.find("ul.select2-selection__rendered").sortable({
                  containment: 'parent',
                  stop: updateIndex
                });
            }

            function onFormUpdate( event, widget ) {
                initJsSelect2( widget );
            }

            $( document ).on( 'widget-added widget-updated', onFormUpdate );

            $( document ).ready( function() {
                $( '#widgets-right .widget:has(.jsselect2)' ).each( function () {
                        initJsSelect2( $( this ) );
                } );
            } );
        }( jQuery ) );

    </script>
    <?php
    $js_script = ob_get_contents();
    ob_end_clean();
    return $js_script;
}


function my_instagram_widgets_footer() { 
    get_template_part('inc/instagram_callback');
}
add_action( 'wp_footer', 'my_instagram_widgets_footer',100 );

/* SHORTCODE TESTIMONIAL FEEDS */
function display_testimonials( $atts ) {
    $atts = shortcode_atts( array(
        'display' => 5,
        'words'=>300,
    ), $atts, 'testimonial-feed' );

    $num_items = $atts['display'];
    $num_words = $atts['words'];

    $arg = array(
        'post_type'=>'testimonial',
        'posts_per_page' => $num_items,
        'post_status'    => 'publish'
    );
    $items = new WP_Query($arg);
    $html_content = '';
    if ($items->have_posts())  {
        ob_start();
        echo '<div class="post-items-wrapper"><div class="post-items clear">';
        while ($items->have_posts()) : $items->the_post(); 
            $content = get_the_content(); 
            $excerpt = shortenText($content, $num_words); ?>
            <div class="p_item">
                <div class="p_desc"><?php echo $excerpt; ?></div>
                <div class="p_title"><?php the_title(); ?></div>        
            </div>
        <?php endwhile;
        echo '</div></div>';
        $html_content = ob_get_contents();
        ob_end_clean();
    }

    return $html_content;
}
add_shortcode( 'testimonial-feed', 'display_testimonials' );


/* ACF - Sidebar Widget */
// add_filter('dynamic_sidebar_params', 'my_dynamic_sidebar_params');
// function my_dynamic_sidebar_params( $params ) {
//     $widget_name = $params[0]['widget_name'];
//     $widget_id = $params[0]['widget_id'];
//     if(!$params) return '';
//     foreach($params as $k=>$p) {
//         $widget_id = ( isset($p['widget_id']) && $p['widget_id'] ) ? $p['widget_id'] : '';
//         if($widget_id=='text-3') {
//             $button_name = get_field('cta_button_name', 'widget_' . $widget_id);
//             $button_link = get_field('cta_button_link', 'widget_' . $widget_id);
//             $before_widget = $p['before_widget'];
//             if( $button_name && $button_link ) {
//                 $params[$k]['after_widget'] = '<div class="button"><a class="widget-button" href="'.$button_link.'">' . $button_name . '</a></div>' . $params[$k]['after_widget'];
//             }
//         }
//     }
//     return $params;
// }
