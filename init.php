<?php

global $map_db_version;
$map_db_version = "1.0";


function inser_database() {
    global $wpdb;

    $table_name = $wpdb->prefix . "map_market";

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id int(11) NOT NULL AUTO_INCREMENT,
        user_id int(11) NOT NULL,
        title longtext,
        description longtext,
        position char(40)
    ) $charset_collate;";

    require_once( ABSPATH . '/wp-admin/includes/upgrade.php');
    dbDelta( $sql );

    add_option('map_db_version', $map_db_version);

}
?>
