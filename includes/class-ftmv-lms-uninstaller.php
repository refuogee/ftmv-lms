<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 * For more information, see the following discussion:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
 *
 * @link       https://www.toptal.com/resume/ratko-solaja
 * @since      1.0.0
 *
 * @package    Toptal_Save
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}
error_log('inside clas--unisntaller');

class ftmv_lms_Uninstaller {

	/**
	 * On deactivation delete the "Saved" page.
	 *
	 * Get the "Saved" page id, check if it exists and delete the page that has that id.
	 *
	 * @since    1.0.0
	 */
	public static function uninstall() {
        error_log('uninstall called');
		global $wpdb;
        $tableArray = [   
          $wpdb->prefix . "ftmv_lms_main_programme_table",
          $wpdb->prefix . "ftmv_lms_course_table"
       ];

      foreach ($tableArray as $tablename) {
         $wpdb->query("DROP TABLE IF EXISTS $tablename");
         if ($wpdb->last_error) {
            echo 'You done bad! ' . $wpdb->last_error;
          }
      }

	}

}
