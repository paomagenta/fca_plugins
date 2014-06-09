<?php
/*
Plugin Name: FCA Eboard Sidebar Menu
Plugin URI: 
Description: FCA Eboard Sidebar Menu
Version: 1.0
Author: Patricia Masigla
Author URI: http://pbmasigla.github.io
License: UMD
*/

class fca_eboard_sidebar extends WP_Widget {

    // constructor
    function fca_eboard_sidebar() {
        parent::WP_Widget(false, $name = __('FCA Eboard Sidebar', 'wp_widget_plugin') );
    }

    // widget form creation
    function form($instance) {  
        // Check values
        if( $instance) {
             $title = esc_attr($instance['title']);
             $textarea = esc_textarea($instance['textarea']);
        } else {
             $title = '';
             $textarea = '';
        }
        ?>

        <?php
    }

    // widget update
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
         $instance['title'] = strip_tags($new_instance['title']);
         $instance['textarea'] = strip_tags($new_instance['textarea']);
         return $instance;
    }

    // widget display
    function widget($args, $instance) {
        extract( $args );
       
       $title = apply_filters('widget_title', $instance['title']);
       $textarea = $instance['textarea'];
       echo $before_widget;
       // Display the widget

       echo '<ul id="filters">';
                $terms = get_terms('eboard_member_years');
                $count = count($terms);
                $rev_array = array_reverse($terms);

                 // echo '<li><a href="javascript:void(0)" title="" data-filter=".all" class="active">All</a></li>';
                echo '<h1 class="past_boards">Past Boards</h1>';
                 if ( $count > 0 ){
                            
                    foreach ( $rev_array as $term ) {
                                    
                    $termname = strtolower($term->name);
                    $termname = str_replace(' ', '-', $termname);

                    echo '<li><a href="javascript:void(0)" title="" data-filter=".'.$termname.'">'.$term->name.'</a></li>';
                                
                    }

                }
            echo '</ul>';
       echo $after_widget;
        }
}

// register widget
add_action('widgets_init', create_function('', 'return register_widget("fca_eboard_sidebar");'));
?>