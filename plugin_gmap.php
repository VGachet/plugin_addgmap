<?php
/*
Plugin Name: Vincent Gachet - Plugin
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/******************************

START DATABASES INITIALIZATION

*******************************/

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
		map_shortcode VARCHAR(255),
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

/******************************

END DATABASES INITIALIZATION

*******************************/

function addgmap_setup_menu()
{
        add_menu_page( 'AddGmap', 'AddGmap', 'manage_options', 'addgmap', 'display_admin_view');
}
add_action('admin_menu', 'addgmap_setup_menu');

function display_admin_view()
{
	require('view_addgmap.php');
}

function insert_gmap_datas() {

	if(isset($_POST['name']) && isset($_POST['lat']) && isset($_POST['lon']))
	{
		$map_width = htmlspecialchars($_POST['map_width']);
		$map_height = htmlspecialchars($_POST['map_height']);

		global $wpdb;

		/*Insert a new map into the addgmap_maps table*/
		$table_name = $wpdb->prefix . 'addgmap_maps';

		$data =	array(
						'map_shortcode'	=>	'waiting'
				);
		
		$format = array(
						'%s'
				);

		$wpdb->insert( $table_name, $data, $format );

		/*Select the latest map_id inserted*/
		$map_id_result = $wpdb->get_results( 
			"
			SELECT id_map 
			FROM $table_name
			ORDER BY id_map DESC
			LIMIT 1
			"
 		);

 		$map_id = $map_id_result[0]->id_map;

		/*Insert pins into the addgmap_pin table*/

		/*For each pin*/
		$names = $_POST['name'];
		$pin_number = 0;

		foreach($names as $name)
		{
			$table_name = $wpdb->prefix . 'addgmap_pin';

			$lat = htmlspecialchars($_POST['lat'][$pin_number]);
			$lon = htmlspecialchars($_POST['lon'][$pin_number]);

			$data =	array(
						'name'	=>	$name,
						'lat'	=>	$lat,
						'lon'	=>	$lon
				);
		
			$format = array(
							'%s',
							'%s',
							'%s'
					);

			$wpdb->insert( $table_name, $data, $format );

			$pin_number++;
		}

		/*Select latest pins inserted*/
		$pin_id_results = $wpdb->get_results( 
			"
			SELECT id_pin 
			FROM $table_name
			ORDER BY id_pin DESC
			LIMIT $pin_number;
			"
 		);

 		foreach ($pin_id_results as $pin_id) {
 			/*Insert the new map id for each new pins in the addgmap_pins table*/
			$table_name = $wpdb->prefix . 'addgmap_pins';

			$data =	array(
						'id_pins_pin'	=>	$pin_id->id_pin,
						'id_pins_map'	=>	$map_id
				);
		
			$format = array(
							'%d',
							'%d'
					);

			$wpdb->insert( $table_name, $data, $format );
 		}
	}
}
add_action('init', 'insert_gmap_datas');

function generate_shortcode_string()
{
	if(isset($_POST['name']))
	{

		$route = "0";
		$allow_it = "0";
		$map_width = htmlspecialchars($_POST['map_width']);
		$map_height = htmlspecialchars($_POST['map_height']);

		if(isset($_POST['route']))
		{
			$route = "1";
		}

		if(isset($_POST['allow_it']))
		{
			$allow_it = "1";
		}

		global $wpdb;

		/*Select latest map inserted*/

		$table_name = $wpdb->prefix . 'addgmap_pins';

		$latest_map_results = $wpdb->get_results( 
			"
			SELECT * 
			FROM $table_name
			WHERE id_pins_map = ( SELECT MAX(id_pins_map) FROM $table_name );
			"
 		);

 		$latest_map_id = $latest_map_results[0]->id_pins_map;

 		/*$generated_shortcode = shortcode_atts( array(
 			'map_id'		=> $latest_map_id,
        	'allow_it'		=> $allow_it,
        	'route' 		=> $route,
        	'map_width'		=> $map_width,
        	'map_height'	=> $map_height
    	), $atts );*/

 		$addgmap_shortcode = '[addgmap map_id="' . $latest_map_id . '" allow_it="' . $allow_it . '" route="' . $route .'" map_width="' . $map_width . '" map_height="' . $map_height . '"]';

		$table_name = $wpdb->prefix . 'addgmap_maps';

		$wpdb->update( 
			$table_name, 
			array( 
				'map_shortcode' => $addgmap_shortcode //update the shortcode row in the db
			), 
			array( 'id_map' => $latest_map_id ), 
			array( 
				'%s'
			), 
			array( '%d' ) 
		);

 	}
}
add_action('init', 'generate_shortcode_string');

function generate_gmap_shortcode($params)
{
		$map_id		= $params['map_id'];
		$allow_it 	= $params['allow_it'];
		$route 		= $params['route'];
		$map_width 	= $params['map_width'];
		$map_height = $params['map_height'];

		global $wpdb;

		/*Select shortcode pins*/

		$table_name = $wpdb->prefix . 'addgmap_pins';

		$latest_map_results = $wpdb->get_results( 
			"
			SELECT * 
			FROM $table_name
			WHERE id_pins_map = $map_id
			"
 		);

 		/*Count the number of pins*/
 		$count_pins = count($latest_map_results);

 		/*Initialize the marker variable => included into the gmap js later*/

 		 $markers = "";

 		 $i = 1;

 		foreach($latest_map_results as $latest_result)
 		{
 			$table_name = $wpdb->prefix . 'addgmap_pin';

 			$latest_pin_results = $wpdb->get_results( 
				"
				SELECT *
				FROM $table_name AS pin
				WHERE pin.id_pin = $latest_result->id_pins_pin;
				"
 			);

 			/*add pin name, pin lat and pin long to the markers var*/

 			$markers .= "['" . $latest_pin_results[0]->name . "',". $latest_pin_results[0]->lat . "," . $latest_pin_results[0]->lon . "]";
 			if($i < $count_pins)
			{
				$markers .= ",";
			}
			$i++;
 		}

 		require('gmap.php');
}
add_shortcode( 'addgmap', 'generate_gmap_shortcode' );

/*function afficher_shortcode_map()
{
	extract(shortcode_atts)
}*/

function gmap_enqueue_script()
{
	wp_deregister_script('jquery');
	wp_enqueue_script('jquery', plugin_dir_url('gmap_plugin').'gmap_plugin/jquery.js', true);
}
add_action('wp_enqueue_scripts', 'gmap_enqueue_script');


?>