<?php


function create_database() {
    global $wpdb;
    global $map_db_version;

    $map_db_version = "1.0";
    $table_name = $wpdb->prefix . "map_marker";

    if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id int(11) NOT NULL AUTO_INCREMENT,
            user_id int(11) NOT NULL,
            title longtext,
            description longtext,
            position char(40),
            PRIMARY KEY (id)
        ) $charset_collate;";

        require_once( ABSPATH . '/wp-admin/includes/upgrade.php');
        dbDelta( $sql );

        add_option('map_db_version', $map_db_version);
    }
}
?>
