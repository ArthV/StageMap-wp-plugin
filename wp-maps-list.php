<?php

/*
Plugin Name: My maps list
 */

/* Global Variable */

$currentDns = "http://localhost";




// Ajax part

add_action('wp_enqueue_scripts', 'enqueue_javascripts');
function enqueue_javascripts() {
    wp_enqueue_script('jquery');
    wp_enqueue_script('readmore',$currentDns."/wp-content/plugins/wp-maps-list/readmore.js", array());
    wp_enqueue_script('fittext', $currentDns."wp-content/plugins/wp-maps-list/FitText.js/jquery.fittext.js", array());

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
function create_table() {

    $html = "";
    $html .= "<center><div id=\"map\" style=\"width: 500px;height: 500px;position: fixed;top: 0;\"> </div></center>";
    $html .= "<script type=\"text/javascript\" src=\"http://stagesolidaire.campus.ecp.fr/wp-content/plugins/wp-maps-list/wp-maps-list.js\"> </script>";
    $html .= "<script type=\"text/javascript\" src=\"https://maps.googleapis.com/maps/api/js?key=AIzaSyB1nKCopxGIQq8aqnL_3l0AiFp488_gYMQ&signed_in=true&callback=initMap\"> </script>";

    // The table below will be filled by the internships which belong to the
    // database
    $html .= "<center>";
    $html .= "<div style=\"width: 500px;height: 200px;\">";
    $html .= "<table>";
    $html .= "<tr>";
    $html .= "<td> Titre </td>";
    $html .= "<td style=\"display: none\"> Description </td>";
    $html .= "<td> Emplacement </td>";
    $html .= "</tr>";
    $html .= "</table>";
    $html .= "<div style=\"height: 100%;overflow-y: scroll;\">";
    $html .= "<table>";
    foreach ($xml_object as $place_value) {
        $html .= "<tr class=\"row\">";
        $html .= "<td class=\"title\">".$place_value->title."</td>";
        $html .= "<td class=\"description\" style=\"display: none\" >".$place_value->description ."</td>";
        $html .= "<td > <div class=\"coordinates\" style=\"display: none\">".$place_value->position."</div>";
        $html .= "<div class=\"reverse_geocode\"></div></td></tr>";
    }
    $html .= "</table>";
    $html .= "</div>";
    $html .= "</div>";
    $html .= "</center>";


    // End of table


    return $html;
}


?>
