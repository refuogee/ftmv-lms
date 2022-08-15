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
 * @package           ftmv_lms
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
 * Text Domain:       ftmv-lms
 * Domain Path:       /languages
 */



// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ftmv-lms-activator.php
 */
function activate_ftmv_lms() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ftmv-lms-activator.php';
	ftmv_lms_Activator::activate();
}

/* function activate_ftmv_lms() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-ftmv-lms-activator.php';
    ftmv_lms_Activator::activate();
}
 */
/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ftmv-lms-deactivator.php
 */
function deactivate_ftmv_lms() {
    error_log('inside deactivate function ftmv-lms.php');
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ftmv-lms-deactivator.php';
	ftmv_lms_Deactivator::deactivate();
}

function uninstall_ftmv_lms() {
    error_log('inside function uninstall call ftmv-lms.php');

	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ftmv-lms-uninstaller.php';
	ftmv_lms_Uninstaller::uninstall();
}


register_activation_hook( __FILE__, 'activate_ftmv_lms' );
register_deactivation_hook( __FILE__, 'deactivate_ftmv_lms' );

error_log('just before uninstall call in ftmv-lms.php');
register_uninstall_hook( __FILE__, 'uninstall_ftmv_lms' );


/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-ftmv-lms.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_ftmv_lms() {

	$plugin = new ftmv_lms();
	$plugin->run();

}
run_ftmv_lms();
