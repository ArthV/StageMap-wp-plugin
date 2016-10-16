<?php

/*
Plugin Name: My maps list
 */

/* Global Variable */
require_once(__DIR__."/config.php");

/* Make sure that database function will be called when the plungin is
 * activated
 */
register_activation_hook(__DIR__."/init.php", 'insert_database');

// Wordpress's function to put javascript library in html's header
add_action('wp_enqueue_scripts', 'enqueue_javascripts');

function enqueue_javascripts() {
    wp_enqueue_script('jquery');
    wp_enqueue_script('readmore',$currentDns."/wp-content/plugins/wp-maps-list/lib/readmore.js", array());
    wp_enqueue_script('fittext', $currentDns."wp-content/plugins/wp-maps-list/lib/FitText.js/jquery.fittext.js", array());
}

/*
 * @return table with marker information
 */
function list_placemarker(){
        global $wpdb;

        $table_name = $wpdb->prefix . "map_market";
        $object = $wpdb->get_results("SELECT i.title, i.description, i.position FROM $table_name AS i");
        $table = create_table($object);

        return $table;
}

/*
 *   @description: Function to insert html code in place of mapmarker
 *   @return html string
 */
function html_map() {
    global $currentDns;
    global $APIkey;

    $html = "";
    $html .= "<center><div id=\"map\" style=\"width: 500px;height: 500px;position: fixed;top: 0;\"> </div></center>";
    $html .= "<script type=\"text/javascript\" src=\"". $currentDns ."/wp-content/plugins/wp-maps-list/wp-maps-list.js\"> </script>";
    $html .= "<script type=\"text/javascript\" src=\"https://maps.googleapis.com/maps/api/js?key=". $APIkey ."&signed_in=true&callback=initMap\"> </script>";

    return $html;
}

add_shortcode( 'mapmarker', 'html_map');// add shortcode to use map in wordpress

?>
