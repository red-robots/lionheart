<?php
function wpb_load_widget() {
    register_widget( 'mywp_widget' );
    register_widget( 'myinsta_widget' );
}
add_action( 'widgets_init', 'wpb_load_widget' );
 
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
        $html_content = '<div class="textwidget"><div class="testimonial_widget_text">';
        $html_content .= do_shortcode('[testimonial-feed  words='.$num_words.' display='.$num_items.']');
            if($view_button_name && $page_id) {
                $page_link = get_permalink($page_id);
                $html_content .= '<div class="button clear"><a class="btn" href="'.$page_link.'">'.$view_button_name.'</a></div>';
            }
        $html_content .= '</div></div>';
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
        array( 'description' => __( 'Display instagram feeds', 'myinsta_widget_domain' ), ) 
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
    </style>
<?php
}
add_action( 'admin_head', 'my_custom_widgets_admin_head' );


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
