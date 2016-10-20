<?php
/*
Plugin Name: addmap
Text Domain: addmap
Domain Path: /languages
Author: Vincent Gachet
Description: Simple plugin to add Google Maps to your website (using shortcodes)
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


define('PATH_ddmap', basename(dirname(__FILE__)));

/******************************

START DATABASES INITIALIZATION

*******************************/

/*Traduction link*/
function addmap_load_textdomain()
{
	load_plugin_textdomain( 'addmap', false, PATH_ddmap.'/languages' );
}
add_action('init', 'addmap_load_textdomain');

function addgmap_table_pin() {
	global $wpdb;

	$table_name = $wpdb->prefix . 'addgmap_pin';
	
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id_pin mediumint(9) NOT NULL AUTO_INCREMENT,
		name VARCHAR(100) NOT NULL,
		lat FLOAT(9,6) NOT NULL,
		lon FLOAT(9,6) NOT NULL,
		map_id mediumint(9) NOT NULL,
		PRIMARY KEY  (id_pin)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
}

register_activation_hook( __FILE__, 'addgmap_table_pin' );

function addgmap_table_info() {
	global $wpdb;

	$table_name = $wpdb->prefix . 'addgmap_info';
	
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		map_shortcode VARCHAR(255),
		api_key VARCHAR(255),
		PRIMARY KEY  (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
}
register_activation_hook( __FILE__, 'addgmap_table_info' );

function addgmap_drop_pin()
{
	global $wpdb;

    $table_name = $wpdb->prefix . 'addgmap_pin';

    $sql = "DROP TABLE IF EXISTS $table_name";

    $wpdb->query($sql);
}

register_deactivation_hook( __FILE__, 'addgmap_drop_pin' );

function addgmap_drop_info()
{
	global $wpdb;

    $table_name = $wpdb->prefix . 'addgmap_info';

    $sql = "DROP TABLE IF EXISTS $table_name";

    $wpdb->query($sql);
}

register_deactivation_hook( __FILE__, 'addgmap_drop_info' );

/******************************

END DATABASES INITIALIZATION

*******************************/

function addmap_setup_menu()
{
        add_menu_page( 'Addmap', 'Addmap', 'manage_options', 'addmap', 'display_admin_view', plugin_dir_url( 'addmap' ).'addmap/loader/pin.png');
}
add_action('admin_menu', 'addmap_setup_menu');

function display_admin_view()
{
	if(count($_POST)>0)
	{
		if(isset($_POST['name']) && isset($_POST['lat']) && isset($_POST['lon']))
		{
			$map_width = htmlspecialchars($_POST['map_width']);
			$map_height = htmlspecialchars($_POST['map_height']);

			global $wpdb;

			/*Select the latest map_id inserted*/
			$table_name = $wpdb->prefix . 'addgmap_pin';

			$map_id_result = $wpdb->get_results( 
				"
				SELECT map_id 
				FROM $table_name
				ORDER BY map_id DESC
				LIMIT 1
				"
	 		);

			if(isset($map_id_result)){

	 			$map_id = ($map_id_result[0]->map_id) + 1;

	 		}
	 		else
	 		{
	 			$map_id = "1";
	 		}

			/*Insert pins into the addgmap_pin table*/

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
							'lon'	=>	$lon,
							'map_id'=>	$map_id
					);
			
				$format = array(
								'%s',
								'%s',
								'%s'
						);

				$wpdb->insert( $table_name, $data, $format );

				$pin_number++;

			}

			$route = "0";
			$allow_it = "0";
			$map_width = htmlspecialchars($_POST['map_width']);
			$map_height = htmlspecialchars($_POST['map_height']);
			$zoom = htmlspecialchars($_POST['zoom']);
			$api_key = htmlspecialchars($_POST['api_key']);

			if(isset($_POST['route']))
			{
				$route = "1";
			}

			if(isset($_POST['allow_it']))
			{
				$allow_it = "1";
			}

			global $wpdb;

	 		$addgmap_shortcode = '[addmap map_id="' . $map_id . '" allow_it="' . $allow_it . '" route="' . $route .'" map_width="' . $map_width . '" map_height="' . $map_height . '" zoom="' . $zoom . '"]';

			$table_name = $wpdb->prefix . 'addgmap_info';

				$data =	array(
							'map_shortcode'	=>	$addgmap_shortcode,
							'api_key'	=>	$api_key
					);
			
				$format = array(
								'%s',
								'%s'
						);

				$wpdb->insert( $table_name, $data, $format );
	 	}
	}

	global $wpdb;
	
	/*Display latest google map shortcodes*/
	$table_name = $wpdb->prefix . 'addgmap_info';

	$latest_map_shortcodes = $wpdb->get_results( 
	"
	SELECT map_shortcode
	FROM $table_name
	ORDER BY id DESC
	LIMIT 10
	"
	);

	/*Display latest registered api_key */
	$table_name = $wpdb->prefix . 'addgmap_info';

	$latest_key_results = $wpdb->get_results( 
			"
			SELECT * 
			FROM $table_name
			ORDER BY id
			LIMIT 1
			"
 		);

 		$api_key = $latest_key_results[0]->api_key;

	require('view_addgmap.php');
}

function generate_gmap_shortcode($params)
{

		$map_id		= htmlspecialchars($params['map_id']);
		$allow_it 	= htmlspecialchars($params['allow_it']);
		$route 		= htmlspecialchars($params['route']);
		$map_width 	= htmlspecialchars($params['map_width']);
		$map_height = htmlspecialchars($params['map_height']);
		$zoom		= htmlspecialchars($params['zoom']);

		if($zoom == "")
		{
			$zoom = 8;
		}
		if($allow_it  == "")
		{
			$allow_it = "0";
		}
		if($route  == "" || $route > 1)
		{
			$route = "0";
		}
		if($map_width  == "")
		{
			$map_width = "500px";
		}
		if($map_height  == "")
		{
			$map_height = "500px";
		}
		if($map_id  == "")
		{
			$error = "<p class='notif'>Please insert the map id in the shortcode</p>";
			return $error;
		}

		global $wpdb;

		/*Select latest registered api_key */
		$table_name = $wpdb->prefix . 'addgmap_info';

		$latest_key_results = $wpdb->get_results( 
			"
			SELECT * 
			FROM $table_name
			ORDER BY id
			LIMIT 1
			"
 		);

 		$api_key = $latest_key_results[0]->api_key;

		/*Select shortcode pins*/

		$table_name = $wpdb->prefix . 'addgmap_pin';

		$latest_pin_results = $wpdb->get_results( 
			"
			SELECT * 
			FROM $table_name
			WHERE map_id = $map_id
			"
 		);

 		/*Count the number of pins*/
 		$count_pins = count($latest_pin_results);


 		/*Initialize the marker variable => included into the gmap js later*/

 		 $markers = "";

 		 $i = 1;

 		foreach($latest_pin_results as $latest_pin_result)
 		{
 			/*Condition to define the simple gmap if route disable*/

 			if($route == "0")
 			{
	 			/*Get first pin lat and lon*/

	 			if($i == "1")
				{
					$first_pin_lat = $latest_pin_result->lat;
					$first_pin_lon = $latest_pin_result->lon;
				}

	 			/*add pin name, pin lat and pin long to the markers var*/

	 			$markers .= "['" . $latest_pin_result->name . "',". $latest_pin_result->lat . "," . $latest_pin_result->lon . "]";

	 			if($i < $count_pins)
				{
					$markers .= ",";
				}
			}
			/*Condition to define the route if route enable*/
			elseif($route == "1")
			{
				if($i == "1")
				{
					$starting_pin = $latest_pin_result->lat . "," . $latest_pin_result->lon;
				}
				elseif($i == "2")
				{
					$ending_pin = $latest_pin_result->lat . "," . $latest_pin_result->lon;
				}
				else
				{
					return false;
				}
			}
			else
			{
				return false;
			}

			$i++;
 		}

 		if($route == "0")
 		{
 			 require('gmap.php');
 		}
 		else
 		{
 			require('gmap_route.php');
 		}
}
add_shortcode( 'addmap', 'generate_gmap_shortcode' );

/*function to get lat and lng from adress */
function get_locations_ajax()
{
	$adress = htmlspecialchars($_POST['adress']);

    $json_link = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . $adress;
    $json_link = str_replace(' ', '+', $json_link);
    $json_link = file_get_contents($json_link);
    $json = json_decode($json_link,true);
  	$latitude = $json['results'][0]['geometry']['location']['lat'];
	$longitude = $json['results'][0]['geometry']['location']['lng'];

	$display_locations = "<p>Latitude : " . $latitude . "</p><p>Longitude : " . $longitude . "</p>";

	wp_die($display_locations);
}
add_action('wp_ajax_addgmap_location', 'get_locations_ajax');

function csv_gmap_export()
{
	header("Content-type: application/vnd.ms-excel; charset=utf-8");
	header("Content-disposition: attachment; filename=addgmap_export.csv");

	global $wpdb;

	/*Select pins*/

	$table_name = $wpdb->prefix . 'addgmap_pin';

	$get_pins = $wpdb->get_results( 
		"
		SELECT * 
		FROM $table_name
		ORDER BY map_id DESC
		"
 	);

 	echo utf8_decode('Map Id;Latitude;Longitude'."\n");

	 foreach ($get_pins as $pin) {
         $csv_string = utf8_decode($pin->map_id.';'.$pin->lat.';'.$pin->lon."\n");
         echo $csv_string;
       }

}
add_action('admin_post_export_csv', 'csv_gmap_export');

function addmap_enqueue_script()
{
	wp_deregister_script('jquery');
	wp_enqueue_script('jquery', plugin_dir_url( 'addmap' ).'addmap/jquery.js', true);
}
add_action('wp_enqueue_scripts', 'addmap_enqueue_script');

if(is_admin())
{
	function addmap_enqueue_styles()
	{
		wp_enqueue_style('addmap_styles', plugin_dir_url( 'addmap' ).'addmap/stylesheets/style.css', true);
	}
	add_action('admin_print_styles', 'addmap_enqueue_styles');
}