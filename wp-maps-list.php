<?php

/*
*Plugin Name: My wordpress map
*Description: This plugin provide a map which can be used by the authenticated users to display, remove and add theirs markers on it.
*Author: Arthur Valingot
*Mail: arth@via.ecp.fr
 */

/* Global Variable */
require_once(__DIR__."/config.php");

/* Make sure that database's function will be called when the plugin is
 * activated
 */
require_once(__DIR__."/init.php");
register_activation_hook(__FILE__, 'create_database');

// Wordpress's function to put javascript library in html's header
add_action('wp_enqueue_scripts', 'enqueue_javascripts');

function enqueue_javascripts() {
    global $currentDns;
    wp_enqueue_script('jquery');
    wp_enqueue_script('wp_map_object', $currentDns."/wp-content/plugins/wp-maps-list/wp-map-object.js");
    wp_enqueue_script('readmore', $currentDns."/wp-content/plugins/wp-maps-list/lib/readmore.js", array());
    wp_enqueue_script('fittext', $currentDns."/wp-content/plugins/wp-maps-list/lib/FitText.js/jquery.fittext.js", array());
    wp_enqueue_script('ajax_functions', $currentDns."/wp-content/plugins/wp-maps-list/ajax_functions.js");
    $map_nonce = wp_create_nonce( 'map_nonce' );
    wp_localize_script( 'ajax_functions', 'my_ajax_obj', array(
    'ajax_url' => admin_url( 'admin-ajax.php' ),
    'nonce'    => $map_nonce));
}

/*
 *   @description: Function to insert html code in place of mapmarker
 *   @return html string
 */
function html_map() {
    global $currentDns;
    global $APIkey;

    $html = "";
    $html .= "<center><div id=\"map\" style=\"width: 500px;height: 400px;position: fixed;top: 0;\"> </div></center>";
    $html .= "<script type=\"text/javascript\" src=\"". $currentDns ."/wp-content/plugins/wp-maps-list/wp-maps-list.js\"> </script>";
    $html .= "<script type=\"text/javascript\" src=\"https://maps.googleapis.com/maps/api/js?key=". $APIkey ."&signed_in=true&callback=initMap\"> </script>";

    return $html;
}

add_shortcode( 'mapmarker', 'html_map');// add shortcode to use map in wordpress


/*
 * Ajax Part : All functions to create, delete, display marker
 */

/*
 * @return get all database markers
 */
add_action( 'wp_ajax_get_markers', 'get_markers');
function get_markers(){
        global $wpdb;
        $table_name = $wpdb->prefix . "map_marker";

        $data = $wpdb->get_results("SELECT i.id, i.user_id, i.title, i.description, i.position FROM $table_name AS i");

        if ($data == null) {
            $data = [];
        }
        wp_send_json_success( $data );
        wp_die();
}
/*
 * Delete one marker
 */

add_action( 'wp_ajax_delete_marker', 'delete_marker');
function delete_marker() {
    global $wpdb;
    global $current_user;
    $table_name = $wpdb->prefix . "map_marker";

    $marker_id = $_POST['id'];
    $marker = $wpdb->get_row($wpdb->prepare("SELECT i.* FROM $table_name i WHERE i.id=%s", $marker_id));

    if ($current_user->ID == $marker->user_id) {
        $wpdb->delete($table_name, array( 'id' => $marker_id));
        wp_send_json_success( "Marker removed !" );
    }
    else {
        wp_send_json_error( "You tried to erase a data which doesn't belong to you" );
    }

    wp_die();
}
/*
 * Create a new marker
 */
add_action( 'wp_ajax_add_marker', 'add_marker' );
function add_marker() {
    global $wpdb;
    global $current_user;
    $table_name = $wpdb->prefix . "map_marker";

    // Handle the ajax request
    // @TODO: test Variable with regex before insert
    $user_id = $current_user->ID;
    $title = $_POST['title'];
    $description = $_POST['description'];
    $position = $_POST['position'];

    $query_string = $wpdb->prepare("INSERT INTO $table_name (user_id, title, description, position) VALUES (%d, %s, %s, %s)", array($user_id, $title, $description, $position));
    $query_result = $wpdb->query($query_string);

    if ($query_result) {
        wp_send_json_success(" Marker put in database ");
    }
    else {
        wp_send_json_error(" An error occured while  ");
    }

    wp_die(); // All ajax handlers die when finished
}
?>
