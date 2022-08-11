<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.ftmv.digital
 * @since             1.0.0
 * @package           ftmv_tp
 *
 * @wordpress-plugin
 * Plugin Name:       FTMV Test Plugin
 * Plugin URI:        https://www.ftmv.digital
 * Description:       The beginning of the end.
 * Version:           1.0.0
 * Author:            FTMV
 * Author URI:        https://www.ftmv.digital
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ftmv-tp
 * Domain Path:       /languages
 */



// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ftmv-tp-activator.php
 */
function activate_ftmv_tp() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ftmv-tp-activator.php';
	ftmv_tp_Activator::activate();
}

/* function activate_ftmv_tp() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-ftmv-tp-activator.php';
    ftmv_tp_Activator::activate();
}
 */
/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ftmv-tp-deactivator.php
 */
function deactivate_ftmv_tp() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ftmv-tp-deactivator.php';
	ftmv_tp_Deactivator::deactivate();
}

/* function uninstall_ftmv_tp() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ftmv-tp-uninstaller.php';
	ftmv_tp_Uninstaller::uninstall();
} */

register_activation_hook( __FILE__, 'activate_ftmv_tp' );
register_deactivation_hook( __FILE__, 'deactivate_ftmv_tp' );
//register_uninstall_hook( __FILE__, 'uninstall_ftmv_tp' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-ftmv-tp.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_ftmv_tp() {

	$plugin = new ftmv_tp();
	$plugin->run();

}
run_ftmv_tp();
