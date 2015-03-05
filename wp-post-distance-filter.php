<?php
/**
 * Plugin Name: WordPress Post Distance Filter
 * Plugin URI: https://github.com/NikSudan/wp-post-distance-filter/
 * Description: Filter posts by distance
 * Version: 1.1.1
 * Author: Nik Sudan
 * Author URI: http://niksudan.com
 */

// -------------------------------------------------
// Plugin Options
// -------------------------------------------------

define( 'WPDF_DEFAULT_ENABLED', 'TRUE' );				// Enabled internally or not
define( 'WPDF_DEFAULT_POSTMETA', 'location' );			// Postmeta meta_key for location string
define( 'WPDF_DEFAULT_LAT_POSTMETA', 'lat' );			// Postmeta meta_key for lat value
define( 'WPDF_DEFAULT_LNG_POSTMETA', 'lng' );			// Postmeta meta_key for lng value
define( 'WPDF_DEFAULT_MEASUREMENT', 'miles' );			// Distance measured in
define( 'WPDF_DEFAULT_GET_LOC', 'distanceSearch' );		// URL GET parameter for the location string
define( 'WPDF_DEFAULT_GET_RAD', 'distanceRadius' );		// URL GET parameter for the radius int

global $wpdf_measurements;
$wpdf_measurements = array(
	'miles' => 3959,
	'km' => 6371,
	'm' => 6371000,
);

// -------------------------------------------------
// Static Functionality
// -------------------------------------------------

class Wpdf
{
	/**
	 * Gets geolocation data from location
	 *
	 * @param string $location
	 * @return object
	 */
	public static function get_geolocation( $location )
	{
		return simplexml_load_file( 'http://maps.googleapis.com/maps/api/geocode/xml?address=' . $location . '&sensor=false' );
	}

	/**
	 * Gets lat/lng from location
	 *
	 * @param string $location
	 * @return array
	 */
	public static function get_lat_lng( $location )
	{
		$result = Wpdf::get_geolocation( $location );
		return array(
			'lat' => ( string ) $result->result->geometry->location->lat,
			'lng' => ( string ) $result->result->geometry->location->lng,
		);
	}

	/**
	 * Gets lat/lng from url specified
	 *
	 * @return array
	 */
	public static function get_url_lat_lng()
	{
		return Wpdf::get_lat_lng( urldecode( $_GET[Wpdf::get_loc()] ) );
	}

	/**
	 * Returns distance between two lat/lngs
	 *
	 * @param float/int/string lat1
	 * @param float/int/string lng1
	 * @param float/int/string lat2
	 * @param float/int/string lng2
	 * @return array
	 */
	public static function distance( $lat1, $lng1, $lat2, $lng2 )
	{
	    $lat1 = deg2rad( floatval( $lat1 ) );
	    $lng1 = deg2rad( floatval( $lng1 ) );
	    $lat2 = deg2rad( floatval( $lat2 ) );
	    $lng2 = deg2rad( floatval( $lng2 ) );
	    $distance = acos( sin( $lat1 ) * sin( $lat2 ) + cos( $lat1 ) * cos( $lat2 ) * cos( $lng1 - $lng2 ) );
	    return Wpdf::get_measurement() * $distance;
	}

	/**
	 * Returns whether URL parameters are valid for usre
	 *
	 * @return boolean
	 */
	public static function validate_url_params()
	{
		$loc = Wpdf::get_loc();
		$rad = Wpdf::get_rad();
		return isset( $_GET[$loc] ) && urldecode( $_GET[$loc] ) != '';
	}

	/**
	 * Returns whether plugin functionality is enabled
	 *
	 * @return boolean
	 */
	public static function is_enabled()
	{
		return get_option( 'wpdf_enabled', WPDF_DEFAULT_ENABLED ) == 'TRUE';
	}

	/**
	 * Returns url location param name
	 *
	 * @return string
	 */
	public static function get_loc()
	{
		return get_option( 'wpdf_get_loc', WPDF_DEFAULT_GET_LOC );
	}

	/**
	 * Returns url radius param name
	 *
	 * @return string
	 */
	public static function get_rad()
	{
		return get_option( 'wpdf_get_rad', WPDF_DEFAULT_GET_RAD );
	}

	/**
	 * Returns chosen measurement
	 *
	 * @param boolean $key
	 * @return float
	 */
	public static function get_measurement( $key = false )
	{
		global $wpdf_measurements;
		$measurement = get_option( 'wpdf_measurement', WPDF_DEFAULT_MEASUREMENT );
		if ( array_key_exists( $measurement, $wpdf_measurements ) ) {
			return $key ? $measurement : $wpdf_measurements[$measurement];
		} else {
			return $key ? '' : 1;
		}
	}
}

// -------------------------------------------------
// Functionality
// -------------------------------------------------

// Set the URL location parameter
if ( Wpdf::validate_url_params() ) {
	global $wpdf_url_loc;
	$wpdf_url_loc = Wpdf::get_url_lat_lng();
}

/**
 * Stores post lat and lng to db on update
 *
 * @param int $post_id
 * @param object $post
 * @return array
 */
function wpdf_store_lat_lng( $post_id, $post )
{
	if ( Wpdf::is_enabled() ) {
		$location = get_post_meta( $post_id, get_option( 'wpdf_postmeta', WPDF_DEFAULT_POSTMETA ) );
		if ( is_array( $location ) ) {
			$location = $location[0];
		}
		if ( $location ) {
			$loc = Wpdf::get_lat_lng( $location );
			update_post_meta( $post_id, get_option( 'wpdf_lat_postmeta', WPDF_DEFAULT_LAT_POSTMETA ), $loc['lat'] );
			update_post_meta( $post_id, get_option( 'wpdf_lng_postmeta', WPDF_DEFAULT_LNG_POSTMETA ), $loc['lng'] );
		}
	}
}
add_action( 'save_post', 'wpdf_store_lat_lng', 10, 2 );

/**
 * Filters posts by distance if URL parameters are set
 *
 * @param $query
 * @return void
 */
function wpdf_filter( $query )
{
	if ( Wpdf::validate_url_params() && Wpdf::is_enabled() ) {
		global $wpdf_url_loc;
		$loc = $wpdf_url_loc;
		if ( $loc['lat'] != '' && $loc['lng'] != '' ) {
			global $wpdf_orderString;
			add_filter( 'posts_where', 'wpdf_filter_where' );
			add_filter( 'posts_orderby', 'wpdf_filter_orderby' );
			unset( $wpdf_orderString );
		}
	}
}
add_action( 'pre_get_posts', 'wpdf_filter' );

/**
 * Filter for where
 *
 * @param $query
 * @return string
 */
function wpdf_filter_where( $where )
{
	global $wpdb, $wpdf_orderString;
	$validPosts = array();
	$orderStrings = array();
	$queryStrings = array();
	global $wpdf_url_loc;
	$loc = $wpdf_url_loc;
	$posts = $wpdb->get_results( "SELECT post_id, meta_value FROM $wpdb->postmeta WHERE meta_key = 'lat' OR meta_key = 'lng' ORDER BY post_id" );
	for ( $i = 0; $i < count( $posts ); $i += 2 ) {
		$dis = Wpdf::distance( $posts[$i]->meta_value, $posts[$i + 1]->meta_value, $loc['lat'], $loc['lng'] );
		$rad = Wpdf::get_rad();
		if ( ( isset($_GET[$rad] ) && $dis < $_GET[$rad] ) || ! isset( $_GET[$rad] ) ) {
			array_push( $queryStrings, 'wp_posts.ID = '.$posts[$i]->post_id );
			array_push( $validPosts, array( 'distance' => number_format( $dis, 2 ), 'ID' => $posts[$i]->post_id ) );
		}
	}
	usort( $validPosts, 'wpdf_compare' );
	foreach ( $validPosts as $index => $validPost ) {
		if ( intval( $index ) == count( $validPosts ) - 1 ) {
			array_push( $orderStrings, 'ELSE '.$index );
		} else {
			array_push( $orderStrings, "WHEN '".$validPost['ID']."' THEN ".$index );
		}
	}
	$queryString = ' AND (' . implode( ' OR ', $queryStrings ) . ')';
	if ( count( $validPosts ) > 1 ) {
		$wpdf_orderString = ' CASE wp_posts.ID ' . implode( ' ', $orderStrings ) . ' END';
	} else {
		$wpdf_orderString = '';
	}
	return $where . $queryString;
}

/**
 * Filter for orderby
 *
 * @param $query
 * @return string
 */
function wpdf_filter_orderby( $orderby )
{
	global $wpdb, $wpdf_orderString;
	return $wpdf_orderString;
}

/**
 * Associate array comparison
 *
 * @param query
 * @return positive/negative
 */
function wpdf_compare( $a, $b )
{
	return $a['distance'] == $b['distance'] ? 0 : ($a['distance'] < $b['distance']) ? -1 : 1;
}

/**
 * Creates options page
 *
 * @param $query
 * @return void
 */
function wpdf_register_options_page()
{
	add_submenu_page( 'options-general.php', 'Distance Filter', 'Distance Filter', 'administrator', 'wpdf-options', 'wpdf_register_options_content' );
}
add_action( 'admin_menu', 'wpdf_register_options_page' );

/**
 * Registers settings
 * 
 * @param $query
 * @return void
 */
function wpdf_register_options_settings()
{
	register_setting( 'wpdf', 'wpdf_enabled' );
	register_setting( 'wpdf', 'wpdf_postmeta' );
	register_setting( 'wpdf', 'wpdf_lat_postmeta' );
	register_setting( 'wpdf', 'wpdf_lng_postmeta' );
	register_setting( 'wpdf', 'wpdf_measurement' );
	register_setting( 'wpdf', 'wpdf_get_loc' );
	register_setting( 'wpdf', 'wpdf_get_rad' );
}
add_action( 'admin_init', 'wpdf_register_options_settings' );

/**
 * Generates options page content
 * 
 * @return void
 */
function wpdf_register_options_content()
{
	include 'options-page.php';
}

/**
 * Echos post distance
 *
 * @return void
 */
function the_distance()
{
	echo get_the_distance() ?: '';
}

/**
 * Gets post distance
 *
 * @return float
 */
function get_the_distance()
{
	if ( Wpdf::validate_url_params() ) {
		global $wpdf_url_loc; $loc = $wpdf_url_loc;
		if ( $loc['lat'] != '' && $loc['lng'] != '' ) {
			$lat = get_post_meta( get_the_id(), get_option( 'wpdf_lat_postmeta', WPDF_DEFAULT_LAT_POSTMETA ), true );
			$lng = get_post_meta( get_the_id(), get_option( 'wpdf_lng_postmeta', WPDF_DEFAULT_LNG_POSTMETA ), true );
			$distance = Wpdf::distance( $lat, $lng, $loc['lat'], $loc['lng'] );
			return number_format( $distance, 2 );
		}
	}
}