<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.ftmv.digital
 * @since      1.0.0
 *
 * @package    ftmv_lms
 * @subpackage ftmv_lms/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    ftmv_lms
 * @subpackage ftmv_lms/includes
 * @author     FTMV
 */
class ftmv_lms_Activator {

	/**
	 * On activation create a page and remember it.
	 *
	 * Create a page named "Saved", add a shortcode that will show the saved items
	 * and remember page id in our database.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
        
        global $wpdb;

        $first_table_name = $wpdb->prefix . 'ftmv_lms_main_programme_table';
        $second_table_name = $wpdb->prefix . 'ftmv_lms_course_table';

        $charset_collate = $wpdb->get_charset_collate();

        $first_table_sql = "CREATE TABLE $first_table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        timecreated datetime DEFAULT '0000-00-00' NOT NULL,
        name tinytext NOT NULL,
        created_user_id mediumint(9) NOT NULL,
        PRIMARY KEY  (id),
        course_count int DEFAULT 0
        ) $charset_collate;";

        $second_table_sql = "CREATE TABLE $second_table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            timecreated datetime DEFAULT '0000-00-00' NOT NULL,
            name tinytext NOT NULL,            
            startdate datetime DEFAULT '0000-00-00' NOT NULL,
            enddate datetime DEFAULT '0000-00-00' NOT NULL,
            created_user_id mediumint(9) NOT NULL,
            main_programme_id mediumint(9) NOT NULL,
            student_count int DEFAULT 0,
            PRIMARY KEY  (id)
            ) $charset_collate;";
        
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

        dbDelta( $first_table_sql );
        dbDelta( $second_table_sql );

	}

}