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

 $ftmv_add_user_nonce = wp_create_nonce('ftmv_add_user_nonce');


if(isset($_GET['programme-id'])) {
    $programme_id = $_GET['programme-id'];
}

if(isset($_GET['course-id'])) {
    $course_id = $_GET['course-id'];
}


if(isset($_GET['user-type'])) {
    $user_type = $_GET['user-type'];
}

$student = false;
$facilitator = false;

if ($user_type == 'facilitator')
{
    $facilitator = true;
}
else if ($user_type == 'student')
{
    $student = true;
}

global $wpdb;
if(isset($course_id)) {
    $course_table_query = "SELECT course_info.name FROM ".$wpdb->prefix."ftmv_lms_course_table AS course_info WHERE course_info.id ={$course_id}";
    $course_result = $wpdb->get_results( $course_table_query, ARRAY_A );
    $course_name = $course_result[0]['name'];
}



$programme_table_query = "SELECT programme_info.name FROM ".$wpdb->prefix."ftmv_lms_main_programme_table AS programme_info WHERE programme_info.id ={$programme_id}";
$programme_result = $wpdb->get_results( $programme_table_query, ARRAY_A );
$programme_name = $programme_result[0]['name'];

$current_page = admin_url(sprintf('admin.php?%s', http_build_query($_GET)));

?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div id="wrap">

    <?php 
        if ($student)
        {
    ?>
            <h2>Add Student Page:</h2>
            <p>
                This is where you add a student to the upcoming course. <br>
                Once a student is created they will be able to view associated course content at the prescribed dates and times.
            </p>   
    
    <?php  
        } 
        if ($facilitator)
        {
    ?>
            <h2>Add Facilitator Page:</h2>
            <p>
                This is where you add a facilitator to a programme.<br>
                Once a facilitator is created they will be able to create new courses for their assigned programmes as well as students for their created courses.
            </p>   
    <?php } ?>

    <?php 
        $transient_message = get_transient( 'user_creation_form_transient' );              

        if( ! empty( $transient_message ) ) 
        {   
            if ($transient_message['user_id'] == wp_get_current_user()->ID)
            {                        
                if ($transient_message['message_type'] == 'error' );
                echo ("<div class='notice notice-error is-dismissible'><p>{$transient_message['message']}</p></div>");
            } 
            
        }
    ?>

    <hr>
    <form class="ftmv-lms-details-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" id="ftmv_add_student">
        <input type="hidden" name="action" value="ftmv_add_user">
        <input type="hidden" name="ftmv_add_user_nonce" value="<?php echo esc_attr($ftmv_add_user_nonce) ?>" />            			    			

        <div class="wrap-test">        
            <input type="hidden" id="course-id" name="course-id" value="<?php echo esc_attr($course_id); ?>">
            <input type="hidden" id="programme-id" name="programme-id" value="<?php echo esc_attr($programme_id); ?>">
            <input type="hidden" id="user-type" name="user-type" value="<?php echo esc_attr($user_type); ?>">
            <input type="hidden" id="current-url" name="current-url" value="<?php echo ($current_page); ?>">

            
            <div class="ftmv-lms-form-layout-container course-name">
                <?php 
                    if ($student)
                    {
                ?>  
                        <div class="ftmv-lms-form-label-container">
                            <label for="course-name">Course Student Created For:</label>
                        </div>
                        <div class="ftmv-lms-input-container">
                            <input type="text" name="course-name" id="course-name" value="<?php echo esc_attr($course_name)?>" disabled>
                        </div>                              

                <?php 
                    }
                    if ($facilitator)
                    { 
                ?>
                        <div class="ftmv-lms-form-label-container">
                            <label for="programme-name">Programme Facilitator Created For:</label>
                        </div>
                        <div class="ftmv-lms-input-container">
                            <input type="text" name="programme-name" id="programme-name" value="<?php echo esc_attr($programme_name)?>" disabled>
                        </div>

                <?php } ?>                        
            </div>

            <div class="ftmv-lms-form-layout-container user-name">
                <div class="ftmv-lms-form-label-container">
                    <label for="user-name">Name:</label>
                </div>                
                <div class="ftmv-lms-input-container">
                    <input type="text" name="user-name" id="user-name" class="regular-text" autofocus required />            
                </div>
            </div>

            <div class="ftmv-lms-form-layout-container user-surname">
                <div class="ftmv-lms-form-label-container">
                    <label for="user-surname">Surname:</label>
                </div>
            
                <div class="ftmv-lms-input-container">
                    <input type="text" name="user-surname" id="user-surname" class="regular-text" required />            
                </div>
            </div>

            <div class="ftmv-lms-form-layout-container user-email">
                <div class="ftmv-lms-form-label-container">
                    <label for="user-email">Email Address:</label>
                </div>
                <div class="ftmv-lms-input-container">
                    <input type="email" name="user-email" id="user-email" class="regular-email" required />            
                </div>
            </div>
            
            <?php 
                if ($student)
                {
            ?>  
                    <div class="ftmv-lms-form-button-container">
                        <button type="submit" class="button button-primary">Create New Student</button>            
                        <a href="<?php echo admin_url('admin.php?page=ftmv-lms-course-overview&course-id='. esc_attr($course_id) .'&programme-id='. esc_attr($programme_id)); ?>"><button type="button" class="button button-primary">Cancel</button></a>
                    </div>
            <?php 
                }
                if ($facilitator)
                { 
            ?>
                    <div class="ftmv-lms-form-button-container">
                        <button type="submit" class="button button-primary">Create New Facilitator</button>            
                        <a href="<?php echo admin_url('admin.php?page=ftmv-lms-programme-overview&id='. esc_attr($programme_id)); ?>"><button type="button" class="button button-primary">Cancel</button></a>
                    </div>
            <?php } ?>
        </div>
    </form>
</div>