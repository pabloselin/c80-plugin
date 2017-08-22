<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://c80.cl
 * @since             1.0.0
 * @package           c80
 *
 * @wordpress-plugin
 * Plugin Name:       C80
 * Plugin URI:        http://apie.cl
 * Description:       Plugin de funciones para aplicación de Constitución
 * Version:           1.0.1
 * Author:            A Pie
 * Author URI:        http://apie.cl/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       c80
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-c80-activator.php
 */
function activate_c80() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-c80-activator.php';
	c80_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-c80-deactivator.php
 */
function deactivate_c80() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-c80-deactivator.php';
	c80_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_c80' );
register_deactivation_hook( __FILE__, 'deactivate_c80' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-c80.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_c80() {

	$plugin = new c80();
	$plugin->run();

}
run_c80();
