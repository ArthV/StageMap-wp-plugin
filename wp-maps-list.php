<?php

/*
Plugin Name: My maps list
 */

/* Global Variable */

global $currentDns = 'http://localhost';
global $APIkey = "AIzaSyB1nKCopxGIQq8aqnL_3l0AiFp488_gYMQ";
/* End Global Variable */


/* Make sure that database function will be called when the plungin is
    * activated */
register_activation_hook(__DIR__."/init.php", 'insert_database');



// Ajax part
add_action('wp_enqueue_scripts', 'enqueue_javascripts');





function enqueue_javascripts() {
    wp_enqueue_script('jquery');
    wp_enqueue_script('readmore',$currentDns."/wp-content/plugins/wp-maps-list/lib/readmore.js", array());
    wp_enqueue_script('fittext', $currentDns."wp-content/plugins/wp-maps-list/lib/FitText.js/jquery.fittext.js", array());

}

/*
 * Function to read the database
 */
function list_placemarker(){
        global $wpdb;

        $object = $wpdb->get_results("SELECT i.title, i.description, i.position FROM intern AS i");
        $table = create_table($object);

        return $table;
}
add_shortcode( 'placemarker', 'list_placemarker');

/*
    @return html string
*/
// Function to add javascript library
function create_table() {
    global $currentDns;

    $html = "";
    $html .= "<center><div id=\"map\" style=\"width: 500px;height: 500px;position: fixed;top: 0;\"> </div></center>";
    $html .= "<script type=\"text/javascript\" src=\"" . $currentDns . "/wp-content/plugins/wp-maps-list/wp-maps-list.js\"> </script>";
    $html .= "<script type=\"text/javascript\" src=\"https://maps.googleapis.com/maps/api/js?key=" .$APIkey . "&signed_in=true&callback=initMap\"> </script>";


    return $html;
}


?>
