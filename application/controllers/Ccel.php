<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly;

class Ccel_index extends Winter_MVC_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        global $wpdb;

        // Change colors if sent in post

        $color_changes = array();

        foreach($_POST as $post_name_color => $post_color)
        {
            if(substr($post_name_color, 0, 6) == 'color_' && substr($post_name_color, -3) != '_or')
            {
                $source_color = sanitize_text_field($_POST[$post_name_color.'_or']);

                $new_color = sanitize_hex_color(substr($post_color,0,7));

                if(substr($source_color, 0, 4) == 'rgba')
                {
                    $hex_color_v = $this->rgba2hex($source_color);
                    $trans_part = substr($hex_color_v, -2);
                    $new_color.=$trans_part;
                }
                elseif(strlen($source_color) > 7) // transparency detected
                {
                    $trans_part = substr($source_color, -2);
                    $new_color.=$trans_part;
                }

                if($source_color != $new_color && !empty($new_color))
                {
                    // change color
                    $color_changes[$source_color] = $new_color;

                    $sql = "SELECT * FROM $wpdb->posts 
                    JOIN $wpdb->postmeta on $wpdb->posts.ID = $wpdb->postmeta.post_id
                    WHERE $wpdb->posts.post_status = 'publish' AND $wpdb->postmeta.meta_key = '_elementor_data' 
                    ORDER BY $wpdb->posts.ID
                    LIMIT 1000"; 

                    $posts = $wpdb->get_results($sql);

                    $colors_detected = array();

                    foreach ( $posts as $key => $page ) 
                    {
                        // check if post contain elementor data

                        $elementor_data_value = $page->meta_value;

                        $elementor_data_value = str_replace( '"'.$source_color.'"', '"'.$new_color.'"',$elementor_data_value);

                        $meta_info_arr = array();
                        $meta_info_arr['post_id'] = $page->ID;
                        $meta_info_arr['meta_key'] = $page->meta_key;
                        $meta_info_arr['meta_value'] = $elementor_data_value;

                        $wpdb->update($wpdb->postmeta, $meta_info_arr, array('meta_id'=>$page->meta_id));

                        unset($elementor_data_value, $meta_info_arr);
                    }

                    unset($posts);
                }
            }
        }

        if(count($_POST) > 0 && class_exists('Elementor\Plugin'))
            Elementor\Plugin::$instance->files_manager->clear_cache();

        // Parse colors used
        $sql = "SELECT * FROM $wpdb->posts 
                            JOIN $wpdb->postmeta on $wpdb->posts.ID = $wpdb->postmeta.post_id
                            WHERE $wpdb->posts.post_status = 'publish' AND $wpdb->postmeta.meta_key = '_elementor_data' 
                            ORDER BY $wpdb->posts.ID
                            LIMIT 1000"; 

        $posts = $wpdb->get_results($sql);

        $colors_detected = array();

        foreach ( $posts as $key => $page ) {

            $elementor_data = $page->meta_value;

            if(isset($elementor_data))
            {
                $regExp = '/"#([^"]*)/i';

                $outputArray = array();
                
                if ( preg_match_all($regExp, $elementor_data, $outputArray, PREG_SET_ORDER) ) {
                }
            
                foreach($outputArray as $found)
                {
                    if(!isset($found[1]) || empty($found[1]))continue;

                    $color = '#'.$found[1];

                    $colors_detected[$color] = $color;
                }

                $regExp = '/"rgba([^"]*)/i';

                $outputArray = array();
                
                if ( preg_match_all($regExp, $elementor_data, $outputArray, PREG_SET_ORDER) ) {
                }
            
                foreach($outputArray as $found)
                {
                    if(!isset($found[1]) || empty($found[1]))continue;

                    $color = 'rgba'.$found[1];

                    $colors_detected[$color] = $this->rgba2hex($color);
                }
            }

            unset($elementor_data, $outputArray);
        }

        $this->data['colors_detected'] = $colors_detected;
        $this->data['color_changes'] = $color_changes;

        $this->load->view('ccel/index', $this->data);
    }

    public function rgba2hex($string, $enable_transparency = FALSE) {
        $rgba  = array();
        $hex   = '';
        $regex = '#\((([^()]+|(?R))*)\)#';
        if (preg_match_all($regex, $string ,$matches)) {
            $rgba = explode(',', implode(' ', $matches[1]));
        } else {
            $rgba = explode(',', $string);
        }
        
        $rr = dechex($rgba['0']);
        $gg = dechex($rgba['1']);
        $bb = dechex($rgba['2']);
        $aa = '';
        
        if (array_key_exists('3', $rgba) && $enable_transparency) {
            $aa = dechex($rgba['3'] * 255);
        }
        
        return strtoupper("#$rr$gg$bb$aa");
    }

}
