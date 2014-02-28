<?php  
/**
 * Plugin Name: iBotPro Music Database
 * Plugin URI: http://www.ibotpro.com/wordpress/plugins/ibotpro_musicdb/
 * Description: Add a music DB to your site.
 * Version: 1.0
 * Author: Stephen L. Rouse
 * Author URI: http://www.ibotpro.com/stephen/
 * License: GPL2
 */

define('IBOTPRO_MUSICDB_PLUGIN_VERSION', '1.0');
define('IBOTPRO_MUSICDB_PLUGIN_FILE', basename(__FILE__));
define('IBOTPRO_MUSICDB_PLUGIN_NAME', str_replace('.php', '', IBOTPRO_MUSICDB_PLUGIN_FILE));
define('IBOTPRO_MUSICDB_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('IBOTPRO_MUSICDB_PLUGIN_URL', plugin_dir_url(__FILE__));
define('IBOTPRO_MUSICDB_ARTISTS_TABLE', 'ibotpro_musicdb_artists');
define('IBOTPRO_MUSICDB_ALBUMS_TABLE', 'ibotpro_musicdb_albums');
define('IBOTPRO_MUSICDB_SONGS_TABLE', 'ibotpro_musicdb_songs');
define('IBOTPRO_MUSICDB_CATEGORIES_TABLE', 'ibotpro_musicdb_categories');
			
if (!class_exists('ibotpro_musicdb')){
	class ibotpro_musicdb{
		/*************************************************************
		Install functions
		**************************************************************/
		function ibotpro_musicdb_install() {
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			global $wpdb;
			global $ibotpro_musicdb_db_version;
			$ibotpro_musicdb_db_version = "1.0";
			
			if ($wpdb->get_var('SHOW TABLES LIKE "' . $wpdb->prefix . IBOTPRO_MUSICDB_ARTISTS_TABLE . '"') != $wpdb->prefix . IBOTPRO_MUSICDB_ARTISTS_TABLE) {
				$sql = 'CREATE TABLE ' . $wpdb->prefix . IBOTPRO_MUSICDB_ARTISTS_TABLE . ' (
					id MEDIUMINT(9) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
					name VARCHAR(255) NOT NULL,
					url VARCHAR(55) DEFAULT "",
					createDate datetime DEFAULT "0000-00-00 00:00:00" NOT NULL,
					editDate datetime DEFAULT "0000-00-00 00:00:00" NOT NULL,
					approved INT(10) NOT NULL)';
				
				dbDelta($sql);
				
				$example_artist_name = "NEEDTOBREATHE";
				$example_artist_url = "http://needtobreathe.net/";
				$example_artist_approved = "1";
				
				$rows_affected = $wpdb->insert($wpdb->prefix . IBOTPRO_MUSICDB_ARTISTS_TABLE, array( 'name' => $example_artist_name, 'url' => $example_artist_url, 'approved' => $example_artist_approved ) );
			}
			
			if ($wpdb->get_var('SHOW TABLES LIKE "' . $wpdb->prefix . IBOTPRO_MUSICDB_ALBUMS_TABLE . '"') != $wpdb->prefix . IBOTPRO_MUSICDB_ALBUMS_TABLE) {
				$sql = 'CREATE TABLE ' . $wpdb->prefix . IBOTPRO_MUSICDB_ALBUMS_TABLE . ' (
					id MEDIUMINT(9) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
					artistID MEDIUMINT(9) NOT NULL,
					name VARCHAR(255) NOT NULL,
					createDate datetime DEFAULT "0000-00-00 00:00:00" NOT NULL,
					editDate datetime DEFAULT "0000-00-00 00:00:00" NOT NULL,
					approved INT(10) NOT NULL)';
				
				dbDelta($sql);
				
				$example_artist_id = "1";
				$example_album_name = "The Heat";
				$example_album_approved = "1";
				
				$rows_affected = $wpdb->insert($wpdb->prefix . IBOTPRO_MUSICDB_ALBUMS_TABLE, array( 'artistID' => $example_artist_id, 'name' => $example_album_name, 'approved' => $example_album_approved ) );
			}
			
			if ($wpdb->get_var('SHOW TABLES LIKE "' . $wpdb->prefix . IBOTPRO_MUSICDB_SONGS_TABLE . '"') != $wpdb->prefix . IBOTPRO_MUSICDB_SONGS_TABLE) {
				$sql = 'CREATE TABLE ' . $wpdb->prefix . IBOTPRO_MUSICDB_SONGS_TABLE . ' (
					id MEDIUMINT(9) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
					name VARCHAR(255) NOT NULL,
					createDate datetime DEFAULT "0000-00-00 00:00:00" NOT NULL,
					editDate datetime DEFAULT "0000-00-00 00:00:00" NOT NULL,
					approved INT(10) NOT NULL)';
				
				dbDelta($sql);
				
				$example_song_name = "More Time";
				$example_song_approved = "1";
				
				$rows_affected = $wpdb->insert($wpdb->prefix . IBOTPRO_MUSICDB_SONGS_TABLE, array( 'name' => $example_song_name, 'approved' => $example_song_approved ) );
			}
			
			if ($wpdb->get_var('SHOW TABLES LIKE "' . $wpdb->prefix . IBOTPRO_MUSICDB_CATEGORIES_TABLE . '"') != $wpdb->prefix . IBOTPRO_MUSICDB_CATEGORIES_TABLE) {
				$sql = 'CREATE TABLE ' . $wpdb->prefix . IBOTPRO_MUSICDB_CATEGORIES_TABLE . ' (
					id MEDIUMINT(9) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
					name VARCHAR(255) NOT NULL)';
				
				dbDelta($sql);
				
				$example_category_name = "Rock";
				
				$rows_affected = $wpdb->insert($wpdb->prefix . IBOTPRO_MUSICDB_CATEGORIES_TABLE, array( 'name' => $example_category_name ) );
			}
			
			// add new role
			add_role('music_contributor', 'Music Contributor', array(
				'read' 				=> 1,
				'ibotpro_musicdb_add_music' 	=> 1
			));

			// add map capabilities to administrator
			$role = get_role('administrator');
			$role->add_cap('ibotpro_musicdb_manage_music');
			
			add_option("ibotpro_musicdb_db_version", $ibotpro_musicdb_db_version);
		}

		/*************************************************************
		Uninstall functions
		**************************************************************/
		function ibotpro_musicdb_uninstall() {
			global $wpdb;
			
			$wpdb->query("DROP TABLE IF EXISTS " . $wpdb->prefix . IBOTPRO_MUSICDB_ARTISTS_TABLE . "");
			$wpdb->query("DROP TABLE IF EXISTS " . $wpdb->prefix . IBOTPRO_MUSICDB_ALBUMS_TABLE . "");
			$wpdb->query("DROP TABLE IF EXISTS " . $wpdb->prefix . IBOTPRO_MUSICDB_SONGS_TABLE . "");
			$wpdb->query("DROP TABLE IF EXISTS " . $wpdb->prefix . IBOTPRO_MUSICDB_CATEGORIES_TABLE . "");

			delete_option( "ibotpro_music_db_version" );
		}

		/*************************************************************
		Main functions
		**************************************************************/
		function ibotpro_musicdb_manage_music() {
			if (($_REQUEST['action'] == "manage_songs") || ($_REQUEST['action'] == "edit_song") || ($_REQUEST['action'] == "delete_song")) {
				include('inc/ibotpro_musicdb_manage_songs.php');
			} elseif (($_REQUEST['action'] == "manage_categories") || ($_REQUEST['action'] == "edit_category") || ($_REQUEST['action'] == "delete_category") || ($_REQUEST['action'] == "add_category")) {
				include('inc/ibotpro_musicdb_manage_categories.php');
			} elseif (($_REQUEST['action'] == "manage_albums") || ($_REQUEST['action'] == "edit_album") || ($_REQUEST['action'] == "delete_album") || ($_REQUEST['action'] == "add_album")) {
				include('inc/ibotpro_musicdb_manage_albums.php');
			} else {
				include('inc/ibotpro_musicdb_manage_artists.php');
			}
		}
		
		function ibotpro_musicdb_manage_categories() {
			include('inc/ibotpro_musicdb_manage_categories.php');
		}
		
		function ibotpro_musicdb_manage_songs() {
			include('inc/ibotpro_musicdb_manage_songs.php');
		}

		function ibotpro_musicdb_render_admin_menu() {  
			if (current_user_can('ibotpro_musicdb_manage_music')) {
				//add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position ); 
				add_menu_page('iBotPro MusicDB',  __('Music', IBOTPRO_MUSICDB_PLUGIN_NAME), 'ibotpro_musicdb_manage_music', IBOTPRO_MUSICDB_PLUGIN_FILE, array('ibotpro_musicdb', 'ibotpro_musicdb_manage_music'), 'dashicons-format-audio', 41.3);
				//add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
				add_submenu_page(IBOTPRO_MUSICDB_PLUGIN_FILE, 'Manage Artists', __('Artists', IBOTPRO_MUSICDB_PLUGIN_NAME), 'ibotpro_musicdb_manage_music', IBOTPRO_MUSICDB_PLUGIN_FILE, array('ibotpro_musicdb', 'ibotpro_musicdb_manage_artists'));
				add_submenu_page(IBOTPRO_MUSICDB_PLUGIN_FILE, 'Manage Albums', __('Albums', IBOTPRO_MUSICDB_PLUGIN_NAME), 'ibotpro_musicdb_manage_music', 'ibotpro_musicdb_manage_albums', array('ibotpro_musicdb', 'ibotpro_musicdb_manage_albums'));
				add_submenu_page(IBOTPRO_MUSICDB_PLUGIN_FILE, 'Manage Songs', __('Songs', IBOTPRO_MUSICDB_PLUGIN_NAME), 'ibotpro_musicdb_manage_music', 'ibotpro_musicdb_manage_songs', array('ibotpro_musicdb', 'ibotpro_musicdb_manage_songs'));
				add_submenu_page(IBOTPRO_MUSICDB_PLUGIN_FILE, 'Manage Categories', __('Categories', IBOTPRO_MUSICDB_PLUGIN_NAME), 'ibotpro_musicdb_manage_music', 'ibotpro_musicdb_manage_categories', array('ibotpro_musicdb', 'ibotpro_musicdb_manage_categories'));
			}
		}		
	}
}

register_activation_hook(__FILE__, array('ibotpro_musicdb', 'ibotpro_musicdb_install'));
register_deactivation_hook(__FILE__, array('ibotpro_musicdb', 'ibotpro_musicdb_uninstall'));

add_action('admin_menu', array('ibotpro_musicdb', 'ibotpro_musicdb_render_admin_menu'));
?>