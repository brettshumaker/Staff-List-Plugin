<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.brettshumaker.com
 * @since             1.17
 * @package           Simple_Staff_List
 *
 * @wordpress-plugin
 * Plugin Name:       Simple Staff List
 * Plugin URI:        https://wordpress.org/plugins/simple-staff-list/
 * Description:       A simple plugin to build and display a staff listing for your website.
 * Version:           2.2.1
 * Author:            Brett Shumaker
 * Author URI:        http://www.brettshumaker.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       simple-staff-list
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Define constants for the plugin
 */
define( 'STAFFLIST_PATH', plugin_dir_path( __FILE__ ) );
define( 'STAFFLIST_URI', plugin_dir_url( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-simple-staff-list-activator.php
 */
function activate_simple_staff_list() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-simple-staff-list-activator.php';
	Simple_Staff_List_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-simple-staff-list-deactivator.php
 */
function deactivate_simple_staff_list() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-simple-staff-list-deactivator.php';
	Simple_Staff_List_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_simple_staff_list' );
register_deactivation_hook( __FILE__, 'deactivate_simple_staff_list' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-simple-staff-list.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.17
 */
function run_simple_staff_list() {

	$plugin = new Simple_Staff_List();
	$plugin->run();

}
run_simple_staff_list();
