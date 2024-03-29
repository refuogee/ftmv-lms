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

        $main_programme_table_name = $wpdb->prefix . 'ftmv_lms_main_programme_table';
        $course_table_name = $wpdb->prefix . 'ftmv_lms_course_table';

        $user_table_name = $wpdb->prefix . 'ftmv_lms_user_table';

        $roles_table_name = $wpdb->prefix . 'ftmv_lms_roles_table';

        $charset_collate = $wpdb->get_charset_collate();

        
        $main_programme_table_sql = "CREATE TABLE $main_programme_table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        timecreated datetime DEFAULT '0000-00-00' NOT NULL,
        name tinytext NOT NULL,
        created_user_id mediumint(9) NOT NULL,
        PRIMARY KEY  (id),
        course_count int DEFAULT 0,
        facilitator_count int DEFAULT 0
        ) $charset_collate;";

        
        $course_table_sql = "CREATE TABLE $course_table_name (
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

        $user_table_sql = "CREATE TABLE $user_table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            wp_user_id mediumint(9) NOT NULL,
            timecreated datetime DEFAULT '0000-00-00' NOT NULL,                        
            created_user_id mediumint(9) NOT NULL,
            main_programme_id mediumint(9) NOT NULL,                        
            assigned_role_id mediumint(9) NOT NULL,                        
            course_id mediumint(9) NOT NULL,                        
            PRIMARY KEY  (id)
            ) $charset_collate;";
        

        $roles_table_sql = "CREATE TABLE $roles_table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,            
            main_programme_id mediumint(9) NOT NULL,            
            role_name varchar(191) NOT NULL,            
            role_type varchar(191) NOT NULL,            
            role_display_name varchar(191) NOT NULL,                        
            PRIMARY KEY  (id)
            ) $charset_collate;";
        
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

        dbDelta( $main_programme_table_sql );
        dbDelta( $course_table_sql );
        dbDelta( $user_table_sql );
        dbDelta( $roles_table_sql );
        
        $roleToAddManagementCapabilities = get_role( 'administrator' );
        $roleToAddManagementCapabilities->add_cap( 'manage-ftmv-lms' );

        $editor_role_set = get_role( 'editor' )->capabilities;
        $role = "ftmv-lms-manager";
        $display_name = "LMS Plugin Manager";
        add_role( $role, $display_name, $editor_role_set );

        $capability_name = 'manage-ftmv-lms';
        $role_object = get_role( $role );                
        $role_object->add_cap( $capability_name );     
            
        $role_type = 'ftmv-lms-manager';
        add_role_to_role_table($role, $programme_id, $role_type, $display_name);
        
	}

}