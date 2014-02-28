<?php  
	/* 
	Plugin Name: MapQuestThat 
	Plugin URI: http://www.ibotpro.com/wp-plugins/MapQuestThat
	Description: Plugin for displaying venues and local points of interest
	Author: Stephen L. Rouse 
	Version: 1.0 
	Author URI: http://www.ibotpro.com 
	*/
define('IBOTPRO_MAPQUESTTHAT_PLUGIN_VERSION', '1.0');
define('IBOTPRO_MAPQUESTTHAT_PLUGIN_FILE', basename(__FILE__));
define('IBOTPRO_MAPQUESTTHAT_PLUGIN_NAME', str_replace('.php', '', IBOTPRO_MAPQUESTTHAT_PLUGIN_FILE));
define('IBOTPRO_MAPQUESTTHAT_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('IBOTPRO_MAPQUESTTHAT_PLUGIN_URL', plugin_dir_url(__FILE__));
define('IBOTPRO_MAPQUESTTHAT_MAP_TABLE', 'ibotpro_mapquestthat_maps');
define('IBOTPRO_MAPQUESTTHAT_POI_TABLE', 'ibotpro_mapquestthat_pois');
define('IBOTPRO_MAPQUESTTHAT_CATEGORY_TABLE', 'ibotpro_mapquestthat_categories');
			
if (!class_exists('ibotpro_mapquestthat')){
	class ibotpro_mapquestthat{
		function ibotpro_mapquestthat_install() {
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			global $wpdb;
			global $ibotpro_mapquestthat_db_version;
			$ibotpro_mapquestthat_db_version = "1.0";
			
			if ($wpdb->get_var('SHOW TABLES LIKE "' . $wpdb->prefix . IBOTPRO_MAPQUESTTHAT_MAP_TABLE . '"') != $wpdb->prefix . IBOTPRO_MAPQUESTTHAT_MAP_TABLE) {
				$sql = 'CREATE TABLE ' . $wpdb->prefix . IBOTPRO_MAPQUESTTHAT_MAP_TABLE . ' (
					id BIGINT(20) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
					title VARCHAR(100) NOT NULL,
					rolloverContent VARCHAR(1000),
					infoContent VARCHAR(1000),
					latitude DECIMAL(18,9) NOT NULL,
					longitude DECIMAL(18,9) NOT NULL,
					active INT(10) NOT NULL)';
				
				dbDelta($sql);
				
				$example_map_name = "Verizon Center";
				$example_map_rolloverContent = "Verizon Center";
				$example_map_infoContent = "Home of the Washington Capitals";
				$example_map_latitude = "38.8981";
				$example_map_longitude = "-77.020874";
				$example_map_active = "1";
				
				$rows_affected = $wpdb->insert($wpdb->prefix . IBOTPRO_MAPQUESTTHAT_MAP_TABLE, array( 'title' => $example_map_name, 'rolloverContent' => $example_map_rolloverContent, 'infoContent' => $example_map_infoContent, 'latitude' => $example_map_latitude, 'longitude' => $example_map_longitude, 'active' => $example_map_active ) );
			}
			
			if ($wpdb->get_var('SHOW TABLES LIKE "' . $wpdb->prefix . IBOTPRO_MAPQUESTTHAT_POI_TABLE . '"') != $wpdb->prefix . IBOTPRO_MAPQUESTTHAT_POI_TABLE) {
				$sql = 'CREATE TABLE ' . $wpdb->prefix . IBOTPRO_MAPQUESTTHAT_POI_TABLE . ' (
					id BIGINT(20) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
					mapID MEDIUMINT(9) NOT NULL,
					title VARCHAR(100) NOT NULL,
					rolloverContent VARCHAR(1000),
					infoContent VARCHAR(1000),
					latitude DECIMAL(18,9) NOT NULL,
					longitude DECIMAL(18,9) NOT NULL,
					active INT(10) NOT NULL)';
				
				dbDelta($sql);
				
				$example_poi_name = "Clyde's Gallery Place";
				$example_poi_rolloverContent = "Clyde's Gallery Place";
				$example_poi_infoContent = "A great eatery.";
				$example_poi_latitude = "38.899203000";
				$example_poi_longitude = "-77.021947000";
				$example_poi_active = "1";
				
				$rows_affected = $wpdb->insert($wpdb->prefix . IBOTPRO_MAPQUESTTHAT_POI_TABLE, array( 'title' => $example_poi_name, 'mapID' => '1', 'rolloverContent' => $example_poi_rolloverContent, 'infoContent' => $example_poi_infoContent, 'latitude' => $example_poi_latitude, 'longitude' => $example_poi_longitude, 'active' => $example_poi_active ) );
			}
			
			if ($wpdb->get_var('SHOW TABLES LIKE "' . $wpdb->prefix . IBOTPRO_MAPQUESTTHAT_CATEGORY_TABLE . '"') != $wpdb->prefix . IBOTPRO_MAPQUESTTHAT_CATEGORY_TABLE) {
				$sql = 'CREATE TABLE ' . $wpdb->prefix . IBOTPRO_MAPQUESTTHAT_CATEGORY_TABLE . ' (
					id BIGINT(20) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
					title VARCHAR(100) NOT NULL,
					slug VARCHAR(1000),
					imageURI VARCHAR(1000))';
				
				dbDelta($sql);
				
				$example_category_name = "Default";
				$example_category_slug = "default";
				$example_category_imageURI = IBOTPRO_MAPQUESTTHAT_PLUGIN_URL."images/star.png";
				
				$rows_affected = $wpdb->insert($wpdb->prefix . IBOTPRO_MAPQUESTTHAT_CATEGORY_TABLE, array( 'title' => $example_category_name, 'slug' => $example_category_slug, 'imageURI' => $example_category_imageURI ) );
			}
			
			// add new role
			add_role('map_contributor', 'Cartographer (Mapmaker)', array(
				'read' 				=> 1,
				'ibotpro_mapquestthat_add_map' 	=> 1
			));

			// add map capabilities to administrator
			$role = get_role('administrator');
			$role->add_cap('ibotpro_mapquestthat_manage_maps');
			
			add_option("ibotpro_mapquestthat_db_version", $ibotpro_mapquestthat_db_version);
		}
		
		function ibotpro_mapquestthat_render_admin_options() {
			include('inc/ibotpro_mapquestthat_admin_options.php');
		}
		
		function ibotpro_mapquestthat_manage_maps() {
			if (($_REQUEST['action'] == "manage_pois") || ($_REQUEST['action'] == "edit_poi") || ($_REQUEST['action'] == "delete_poi")) {
				include('inc/ibotpro_mapquestthat_manage_pois.php');
			} elseif (($_REQUEST['action'] == "manage_categories") || ($_REQUEST['action'] == "edit_category") || ($_REQUEST['action'] == "delete_category") || ($_REQUEST['action'] == "add_category")) {
				include('inc/ibotpro_mapquestthat_manage_categories.php');
			} else {
				include('inc/ibotpro_mapquestthat_manage_maps.php');
			}
		}
		
		function ibotpro_mapquestthat_manage_categories() {
			include('inc/ibotpro_mapquestthat_manage_categories.php');
		}
		
		function ibotpro_mapquestthat_render_key() {
			$retval = "<script src=\"http://www.mapquestapi.com/sdk/js/v7.0.s/mqa.toolkit.js?key=".get_option('mapquestthat_apikey')."\"></script>\n";
			echo $retval;
		}
		  
		function ibotpro_mapquestthat_render_admin_menu() {  
			if (current_user_can('ibotpro_mapquestthat_manage_maps')) {
				add_options_page("MapquestThat", "MapquestThat", 1, "ibotpro_mapquest-that_admin", array('ibotpro_mapquestthat', 'ibotpro_mapquestthat_render_admin_options'));
				//add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position ); 
				add_menu_page('MapQuestThat',  __('Maps', IBOTPRO_MAPQUESTTHAT_PLUGIN_NAME), 'ibotpro_mapquestthat_manage_maps', IBOTPRO_MAPQUESTTHAT_PLUGIN_FILE, array('ibotpro_mapquestthat', 'ibotpro_mapquestthat_manage_maps'), 'dashicons-location-alt', 40);
				//add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
				add_submenu_page(IBOTPRO_MAPQUESTTHAT_PLUGIN_FILE, 'Manage Categories', __('Categories', IBOTPRO_MAPQUESTTHAT_PLUGIN_NAME), 'ibotpro_mapquestthat_manage_maps', 'ibotpro_mapquestthat_manage_categories', array('ibotpro_mapquestthat', 'ibotpro_mapquestthat_manage_categories'));
			}
		}
	}
}

register_activation_hook(__FILE__, array('ibotpro_mapquestthat', 'ibotpro_mapquestthat_install'));

add_action('wp_head', array('ibotpro_mapquestthat', 'ibotpro_mapquestthat_render_key'));
add_action('admin_menu', array('ibotpro_mapquestthat', 'ibotpro_mapquestthat_render_admin_menu'));
?>