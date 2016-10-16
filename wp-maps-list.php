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
    $map_nonce = wp_create_nonce( 'map_nonce' );
    wp_localize_script( 'add-stage', 'my_ajax_obj', array(
    'ajax_url' => admin_url( 'admin-ajax.php' ),
    'nonce'    => $map_nonce));
}

/*
 * @return table with marker information
 */
function list_mapmarker(){
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


/*
 * Ajax Part : All functions to create, delete, display marker
 */

/*
 *
 */

add_action( 'wp_ajax_delete_marker', 'delete_marker');
function delete_marker() {
    global $wpdb;
    global $current_user;
    $table_name = $wpdb->prefix . "map_market";

    $marker_id = $_POST['id'];
    $marker = $wpdb->get_row($wpdb->prepare("SELECT i.* FROM $table_name i WHERE i.id=%s", $marker_id))

    if ($current_user->ID == $marker->user_id) {

        $wpdb->delete( 'intern', array( 'id' => $intern_id));
    }

    wp_die();
}

add_action( 'wp_ajax_add_marker', 'add_marker' );
function add_marker() {
    global $wpdb;
    global $current_user;
    $table_name = $wpdb->prefix . "map_market";

    // Handle the ajax request
    $user_id = $current_user->ID;
    $title = $_POST['title'];
    $description = $_POST['description'];
    $position = $_POST['position'];

    $query_string = $wpdb->prepare("INSERT INTO $table_name (user_id, title, description, position) VALUES (%d, %s, %s, %s)", array($user_id, $title, $description, $position));
    $query_result = $wpdb->query($query_string);
    wp_die(); // All ajax handlers die when finished
}






?>
