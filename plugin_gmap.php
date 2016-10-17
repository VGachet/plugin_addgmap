<?php
/*
Plugin Name: Vincent Gachet - Plugin
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

function addgmap_table_pin() {
	global $wpdb;

	$table_name = $wpdb->prefix . 'addgmap_pin';
	
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id_pin mediumint(9) NOT NULL AUTO_INCREMENT,
		name VARCHAR(100) NOT NULL,
		lat FLOAT(9,6) NOT NULL,
		lon FLOAT(9,6) NOT NULL,
		PRIMARY KEY  (id_pin)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
}

register_activation_hook( __FILE__, 'addgmap_table_pin' );

function addgmap_table_maps() {
	global $wpdb;

	$table_name = $wpdb->prefix . 'addgmap_maps';
	
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id_map mediumint(9) NOT NULL AUTO_INCREMENT,
		allow_it BOOLEAN NOT NULL,
		PRIMARY KEY  (id_map)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
}

register_activation_hook( __FILE__, 'addgmap_table_maps' );

function addgmap_table_pins() {
	global $wpdb;

	$table_name = $wpdb->prefix . 'addgmap_pins';
	
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id_pins mediumint(9) NOT NULL AUTO_INCREMENT,
		id_pins_pin mediumint(9) NOT NULL,
		id_pins_map mediumint(9) NOT NULL,
		PRIMARY KEY  (id_pins),
		FOREIGN KEY  (id_pins_pin) REFERENCES addgmap_pin(id_pin),
		FOREIGN KEY  (id_pins_map) REFERENCES addgmap_maps(id_map)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
}

register_activation_hook( __FILE__, 'addgmap_table_pins' );

function addgmap_drop_pin()
{
	global $wpdb;

    $table_name = $wpdb->prefix . 'addgmap_pin';

    $sql = "DROP TABLE IF EXISTS $table_name";

    $wpdb->query($sql);
}

register_deactivation_hook( __FILE__, 'addgmap_drop_pin' );

function addgmap_drop_maps()
{
	global $wpdb;

    $table_name = $wpdb->prefix . 'addgmap_maps';

    $sql = "DROP TABLE IF EXISTS $table_name";

    $wpdb->query($sql);
}

register_deactivation_hook( __FILE__, 'addgmap_drop_maps' );

function addgmap_drop_pins()
{
	global $wpdb;

    $table_name = $wpdb->prefix . 'addgmap_pins';

    $sql = "DROP TABLE IF EXISTS $table_name";

    $wpdb->query($sql);
}

register_deactivation_hook( __FILE__, 'addgmap_drop_pins' );

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

	if(isset($_POST['name']))
	{
		foreach($_POST['lon'] as $longitude)
		{
			var_dump($longitude);
		}
		die;
		$itineraire = false;
		$allow_it = false;

		if(isset($_POST['itineraire']))
		{
			$itineraire = true;
		}

		if(isset($_POST['allow_it']))
		{
			$allow_it = true;
		}

		global $wpdb;

		$table_name = $wpdb->prefix . 'addgmap';

		$data =	array(
						'lat'			=>	$_POST['lat'],
						'lon'			=>	$_POST['lon'],
						'name'			=>	$_POST['name'],
						'itineraire'	=>	$itineraire,
						'allow_it'		=>	$allow_it
				);
		
		$format = array(
						'%s',
						'%s',
						'%s',
						'%d',
						'%d'
				);

		$wpdb->insert( $table_name, $data, $format );
	}
}

function select_locations()
{
	global $wpdb;

	$results = $wpdb->get_results( 'SELECT * FROM wp_addgmap', OBJECT );

	$count_locations = count($results);

	$markers = "";

	$i = 1;

	foreach ($results as $result) {

		$name = "'" . $result->name . "'";

		$markers .= "[" . $name . ",". $result->lat . "," . $result->lon . "]";
		if($i < $count_locations)
		{
			$markers .= ",";
		}
		$i++;
	}
	require('gmap.php');
}
add_action('plugins_loaded', 'select_locations');

function gmap_enqueue_script()
{
	wp_deregister_script('jquery');
	wp_enqueue_script('jquery', plugin_dir_url('gmap_plugin').'gmap_plugin/jquery.js', true);
}
add_action('wp_enqueue_scripts', 'gmap_enqueue_script');


?>