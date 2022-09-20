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

$ftmv_add_student_nonce = wp_create_nonce('ftmv_add_student_nonce');

$course_id = $_GET['course-id'];

global $wpdb;
$query = "SELECT course_info.name, course_info.main_programme_id FROM ".$wpdb->prefix."ftmv_lms_course_table AS course_info WHERE course_info.id =".$course_id."";
$result = $wpdb->get_results( $query, ARRAY_A );


?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div id="wrap">
    <h2>Add Student Page:</h2>
    <p>
        This is where you add a student to the upcoming course. <br>
        Once a student is created they will be able to view associated course content at the determined dates and times.
    </p>            
    <hr>
    <form class="ftmv-lms-details-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" id="ftmv_add_student">
            <input type="hidden" name="action" value="ftmv_add_student">
            <input type="hidden" name="ftmv_add_student_nonce" value="<?php echo esc_attr($ftmv_add_student_nonce) ?>" />            			    			

            <div class="wrap-test">        
                <input type="hidden" id="course-id" name="course-id" value="<?php echo esc_attr($course_id); ?>">
                <input type="hidden" id="programme-id" name="programme-id" value="<?php echo esc_attr($result[0]['main_programme_id']); ?>">
                <input type="hidden" id="user-type" name="user-type" value="student">

                <div class="ftmv-lms-form-layout-container course-name">
                    <div class="ftmv-lms-form-label-container">
                        <label for="course-name">Course Student Created For:</label>
                    </div>            
                    <div class="ftmv-lms-input-container">
                        <input type="text" name="course-name" id="course-name" value="<?php echo esc_attr($result[0]['name'])?>" disabled>
                    </div>
                </div>

                <div class="ftmv-lms-form-layout-container student-name">
                    <div class="ftmv-lms-form-label-container">
                        <label for="student-name">Name:</label>
                    </div>                
                    <div class="ftmv-lms-input-container">
                        <input type="text" name="student-name" id="student-name" class="regular-text" autofocus required />            
                    </div>
                </div>

                <div class="ftmv-lms-form-layout-container student-surname">
                    <div class="ftmv-lms-form-label-container">
                        <label for="student-surname">Surname:</label>
                    </div>
                
                    <div class="ftmv-lms-input-container">
                        <input type="text" name="student-surname" id="student-surname" class="regular-text" required />            
                    </div>
                </div>

                <div class="ftmv-lms-form-layout-container student-email">
                    <div class="ftmv-lms-form-label-container">
                        <label for="student-email">Email Address:</label>
                    </div>
                    <div class="ftmv-lms-input-container">
                        <input type="email" name="student-email" id="student-email" class="regular-email" required />            
                    </div>
                </div>

                <div class="ftmv-lms-form-button-container">
                    <button type="submit" class="button button-primary">Create New Student</button>            
                    <a href="<?php echo admin_url('admin.php?page=ftmv-lms-course-overview&course-id=' . esc_attr($course_id)); ?>"><button type="button" class="button button-primary">Cancel</button></a>
                </div>

            </div>
        </form>
</div>