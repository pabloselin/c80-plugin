<?php

/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    c80
 * @subpackage c80/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    c80
 * @subpackage c80/includes
 * @author     Your Name <email@example.com>
 */
class c80_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		global $wpdb;
		$db_ver = '0.3';

		if(get_option( 'c80_db_version') !== $db_ver ):
			$table_name = 'c80_rels';
			$charset_collate = $wpdb->get_charset_collate();

			$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			postid int(10) NOT NULL,
			c80id int(10) NOT NULL,
			c80p int(10) NOT NULL,
			type int(1) NOT NULL,
			PRIMARY KEY  (id)
			) $charset_collate;";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
			update_option( 'c80_db_version', $db_ver );
		endif;

		//Run once through all post meta
	}

}
