<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.ftmv.digital
 * @since      1.0.0
 *
 * @package    ftmv_lms
 * @subpackage ftmv_lms/admin/partials
 */

$ftmv_add_course_nonce = wp_create_nonce('ftmv_add_course_nonce');
$programme_id = $_GET['id'];

global $wpdb;
$query = "SELECT programme_info.name FROM ".$wpdb->prefix."ftmv_lms_main_programme_table AS programme_info WHERE programme_info.id =".$programme_id."";
$result = $wpdb->get_results( $query, ARRAY_A );

?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div id="wrap">
    <h2>Add a Course Page:</h2>
    <p>
        This is where you create a course. <br>
        Once a course is created the ability to create and add students to the course will become available.
    </p>            
    <hr>
    <form class="ftmv-lms-course-details-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" id="ftmv_add_course">
            <input type="hidden" name="action" value="ftmv_add_course">
            <input type="hidden" name="ftmv_add_course_nonce" value="<?php echo esc_attr($ftmv_add_course_nonce) ?>" />			    			
            <div class="wrap-test">        
                <input type="hidden" id="programme-id" name="programme-id" value="<?php echo esc_attr($programme_id); ?>">
                <div class="ftmv-lms-form-layout-container programme-name">
                    <div class="ftmv-lms-form-label-container">
                        <label for="programme-name">Programme Name:</label>
                    </div>
                    <div class="ftmv-lms-input-container">
                        <input type="text" name="programme-name" id="programme-name" value="<?php echo esc_attr($result[0]['name']); ?>" class="regular-text" disabled />            
                    </div>
                </div>

                <div class="ftmv-lms-form-layout-container programme-name">
                    <div class="ftmv-lms-form-label-container">
                        <label for="course-name">Course Name:</label>
                    </div>
                    <div class="ftmv-lms-input-container">
                        <input type="text" name="course-name" id="course-name" class="regular-text" required />            
                    </div>
                </div>

                <div class="ftmv-lms-form-layout-container course-start-date">
                    <label for="course-start-date">Course Start Date:</label>
                    <input type="date" name="course-start-date" id="course-start-date" min="" class="course-start-date regular-text" required />            
                </div>

                <div class="ftmv-lms-form-layout-container course-end-date">
                    <label for="course-end-date">Course End Date:</label>
                    <input type="date" name="course-end-date" id="course-end-date" class="course-end-date regular-text" required />            
                </div>

                <div class="ftmv-lms-form-button-container">
                    <button type="" class="button button-primary">Create New Course</button>            
                    <a href="<?php echo admin_url('admin.php?page=ftmv-lms-programme-overview&id=' . esc_attr($programme_id)); ?>"><button type="button" class="button button-primary">Cancel</button></a>
                </div>

            </div>
        </form>
</div>