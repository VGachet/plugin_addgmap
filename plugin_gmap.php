<?php

/*

Plugin Name: Vincent Gachet - Plugin

*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

function addgmap_table_install() {
	global $wpdb;

	$table_name = $wpdb->prefix . 'addgmap';
	
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		name tinytext NOT NULL,
		lat mediumint(9) NOT NULL,
		lon mediumint(9) NOT NULL,
		itineraire BOOLEAN NOT NULL,
		allow_it BOOLEAN NOT NULL,
		image_url VARCHAR(55) DEFAULT '' NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
}

register_activation_hook( __FILE__, 'addgmap_table_install' );

function addgmap_table_drop()
{
	global $wpdb;

    $table_name = $wpdb->prefix . 'addgmap';

    $sql = "DROP TABLE IF EXISTS $table_name";

    $wpdb->query($sql);
}

register_deactivation_hook( __FILE__, 'addgmap_table_drop' );

function plugin_setup_menu()
{
        add_menu_page( 'AddGmap', 'AddGmap', 'manage_options', 'plugin', 'my_plugin_options' );
}
add_action('admin_menu', 'plugin_setup_menu');

function my_plugin_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	require('view_addgmap.php');
}
function gmap_enqueue_script()
{
	wp_enqueue_script('jquery', plugin_dir_url('gmap_plugin').'/gmap_plugin/jquery.js', false);
	wp_enqueue_script('gmap', plugin_dir_url('gmap_plugin').'/gmap_plugin/gmap.js', false);
}
add_action('wp_enqueue_scripts', 'gmap_enqueue_script');

?>