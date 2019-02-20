<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package ACStarter
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function acstarter_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}
    if ( is_home() || is_front_page() ) {
        $classes[] = 'homepage';
    } else {
        $classes[] = 'subpage';
    }

	$browsers = ['is_iphone', 'is_chrome', 'is_safari', 'is_NS4', 'is_opera', 'is_macIE', 'is_winIE', 'is_gecko', 'is_lynx', 'is_IE', 'is_edge'];
    $classes[] = join(' ', array_filter($browsers, function ($browser) {
        return $GLOBALS[$browser];
    }));

	return $classes;
}
add_filter( 'body_class', 'acstarter_body_classes' );

if( function_exists('acf_add_options_page') ) {
    acf_add_options_page();
}

function add_query_vars_filter( $vars ) {
  $vars[] = "pg";
  return $vars;
}
add_filter( 'query_vars', 'add_query_vars_filter' );


/* GENERATE SITEMAP */
function generate_sitemap($menuName='top-menu',$pageWithCats=null,$orderByNavi=null) {
    global $wpdb;
    $lists = array();
    $menus = wp_get_nav_menu_items($menuName);
    $menu_orders = array();
    $menu_with_children = array();
    $navi_order = array();

    if($menus) {
        $i=0;
        foreach($menus as $m) {
            $page_id = $m->object_id;
            $menu_title = $m->title;
            $page_url = $m->url;
            $post_parent = $m->post_parent;
            $submenu = array();
            $navi_order[] = $page_id;
            if($post_parent) {
                $submenu = array(
                        'id'=>$page_id,
                        'title'=>$menu_title,
                        'url'=>$page_url
                    );
                $menu_with_children[$post_parent][$page_id] = $submenu;
            } else {
                $menu_orders[$page_id] = $menu_title;
            } 
            $i++;
        }
    }    
    
    $results = $wpdb->get_results( "SELECT ID,post_title FROM {$wpdb->prefix}posts WHERE post_type = 'page' AND post_status='publish' AND post_parent=0 ORDER BY post_title ASC", OBJECT );
    $childPages = $wpdb->get_results( "SELECT ID,post_title,post_parent as parent_id FROM {$wpdb->prefix}posts WHERE post_type = 'page' AND post_status='publish' AND post_parent>0 ORDER BY post_title ASC", OBJECT );
    $childrenList = array();
    $childrenAll = array();

    /* child pages */
    if($childPages) {
        foreach($childPages as $cp) {
            $pId = $cp->parent_id;
            $iD = $cp->ID;
            $childrenAll[$iD] = array(
                                'id'=>$cp->ID,
                                'title'=>$cp->post_title,
                                'url'=>get_permalink($iD)
                            );
            $childrenList[$pId][] = array(
                                'id'=>$cp->ID,
                                'title'=>$cp->post_title,
                                'url'=>get_permalink($cp->ID)
                            );
        }
    }

    

    if($results) {
        foreach($results as $row) {
            $id = $row->ID;
            $page_title = $row->post_title;
            $page_link = get_permalink($id);
        
            if($menu_orders) {
                $first_menu = array_values($menu_orders)[0];
                if($page_title=='Homepage') {
                    $page_title = $first_menu;
                }
                if(array_key_exists($id,$menu_orders)) {
                    $page_title = $menu_orders[$id];
                }
            }

            $lists[$id]['parent_id'] = $id;
            $lists[$id]['parent_title'] = $page_title;
            $lists[$id]['parent_url'] = $page_link;
            
            if($menu_with_children) {

                $ii_childrens = array();
                if(array_key_exists($id,$menu_with_children)) {
                    $ii_childrens = $menu_with_children[$id];
                    $lists[$id]['children'] = $ii_childrens;
                }

                /* Show children page even if does not exist on Menu dropdown */
                if($childrenList && array_key_exists($id, $childrenList)) {
                    $cc_children = $childrenList[$id];
                    $exist_children = $lists[$id]['children'];
                    
                    foreach($cc_children as $cd) {
                        $x_id = $cd['id'];
                        if(!array_key_exists($x_id, $ii_childrens)) {
                            $addon[$x_id] = $cd;
                            $exist_children[$x_id] = $cd;
                        }
                    } 

                    $lists[$id]['children'] = $exist_children;
                }

            } else {
                if($childrenList && array_key_exists($id, $childrenList)) {
                    $c_obj = $childrenList[$id];
                    $lists[$id]['children'] = $c_obj;
                }
            }


            if($pageWithCats) {
                foreach($pageWithCats as $p) {
                    $pageid = $p['id'];
                    $taxo = (isset($p['taxonomy']) && $p['taxonomy']) ? $p['taxonomy'] : '';
                    $post_type = (isset($p['post_type']) && $p['post_type']) ? $p['post_type'] : '';
                    if($pageid==$id) {
                        if($taxo) {
                            $o_terms = get_terms( array(
                                'taxonomy' => $taxo,
                                'hide_empty' => false,
                            ) );
                            if($o_terms){
                                foreach ($o_terms as $t) {
                                    $term_id = $t->term_id;
                                    $term_name = $t->name;
                                }
                                $lists[$id]['categories'] = $o_terms;
                            }
                        }
                        if($post_type) {
                            $args = array(
                                'posts_per_page'    => -1,
                                'post_type'         => $post_type,
                                'post_status'       => 'publish'  
                            );
                            $p_posts = get_posts($args);
                            if($p_posts) {
                                $p_children = array();
                                foreach($p_posts as $pp) {
                                    $p_children[] = array(
                                            'title'=>$pp->post_title,
                                            'url'=> get_permalink($pp->ID)
                                        );
                                }
                                $lists[$id]['children'] = $p_children;
                            }
                        }
                    }
                }
            }

            // $cat_args = array('hide_empty' => 1, 'parent' => 0, 'exclude'=>array(1));
            // $i_parent_ID = 8; /* Artwork page */
            // $artwork_terms = get_terms( array(
            //     'taxonomy' => 'arttypes',
            //     'hide_empty' => false,
            // ));
            // if($id == $i_parent_ID) {
            //     $lists[$id]['categories'] = $artwork_terms;
            // }
        }   
    }

    $new_list = array();
    if($orderByNavi && $menus && $lists) {
        foreach($navi_order as $x_id) {
            if( array_key_exists($x_id, $lists) ) {
                $new_items = $lists[$x_id];
                $new_list[$x_id] = $new_items;
            } 
        }

        foreach($lists as $k_id=>$k_vars) {
            if( !in_array($k_id, $orderByNavi) ) {
                $new_list[$k_id] = $k_vars;
            }
        }
    }

    if($new_list) {
        return $new_list;   
    } else {
        return $lists;   
    }
}


function shortenText($string, $limit, $break = ".", $pad = "...") {
  if(strlen($string) <= $limit) return $string;
  if(false !== ($breakpoint = strpos($string, $break, $limit))) {
    if($breakpoint < strlen($string) - 1) {
      $string = substr($string, 0, $breakpoint) . $pad;
    }
  }
  return $string;
}

/* Fixed Gravity Form Conflict Js */
add_filter("gform_init_scripts_footer", "init_scripts");
function init_scripts() {
    return true;
}


function format_phone_number($string) {
    if(empty($string)) return '';
    $append = '';
    if (strpos($string, '+') !== false) {
        $append = '+';
    }
    $string = preg_replace("/[^0-9]/", "", "705-666-8888" );
    $string = preg_replace('/\s+/', '', $string);
    return $append.$string;
}

function get_instagram_setup() {
    global $wpdb;
    $result = $wpdb->get_row( "SELECT option_value FROM $wpdb->options WHERE option_name = 'sb_instagram_settings'" );
    if($result) {
        $option = ($result->option_value) ? @unserialize($result->option_value) : false;
    } else {
        $option = '';
    }
    return $option;
}
