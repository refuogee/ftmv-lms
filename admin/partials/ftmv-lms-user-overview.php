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



    $uid = $_GET['uid'];
    

    global $wpdb;

    $ftmv_edit_user_nonce = wp_create_nonce('ftmv_edit_user_nonce');
    $ftmv_delete_user_nonce = wp_create_nonce('ftmv_delete_user_nonce');

    // $query = "SELECT course_info.main_programme_id AS programme_id, course_info.id, course_info.timecreated, course_info.name, course_info.startdate, course_info.enddate, course_info.student_count, user_info.display_name AS 'created_user', programme_info.name AS 'main_programme' FROM ".$wpdb->prefix."ftmv_lms_course_table AS course_info LEFT JOIN ".$wpdb->prefix."users AS user_info ON course_info.created_user_id = user_info.ID LEFT JOIN ".$wpdb->prefix."ftmv_lms_main_programme_table AS programme_info ON course_info.main_programme_id = programme_info.id WHERE course_info.id =".$course_id. " AND programme_info.id = course_info.main_programme_id";
    $user_data_query = "SELECT wp_user.user_login AS user_name, programme.id AS programme_id, programme.name AS programme, course.id AS course_id, course.name AS course, role.role_type, lms_user.timecreated, lms_user.id, wp_user.user_email AS user_email, role.role_display_name, concat(wp_user_name_created.meta_value,' ' , wp_user_surname_created.meta_value) AS created_user_name, wp_user_name.meta_value AS user_fname, wp_user_surname.meta_value AS user_lname FROM wp_ftmv_lms_user_table AS lms_user LEFT JOIN wp_users AS wp_user ON lms_user.wp_user_id = wp_user.ID LEFT JOIN wp_usermeta AS wp_user_name_created ON lms_user.created_user_id = wp_user_name_created.user_id LEFT JOIN wp_usermeta AS wp_user_surname_created ON lms_user.created_user_id = wp_user_surname_created.user_id LEFT JOIN wp_usermeta AS wp_user_name ON lms_user.wp_user_id = wp_user_name.user_id LEFT JOIN wp_usermeta AS wp_user_surname ON lms_user.wp_user_id = wp_user_surname.user_id LEFT JOIN wp_ftmv_lms_roles_table AS role ON role.id = lms_user.assigned_role_id LEFT JOIN wp_ftmv_lms_main_programme_table AS programme ON programme.id = lms_user.main_programme_id LEFT JOIN wp_ftmv_lms_course_table AS course ON course.id = lms_user.course_id WHERE wp_user_name.meta_key = 'first_name' AND wp_user_surname.meta_key = 'last_name' AND wp_user_name.user_id = lms_user.wp_user_id AND wp_user_name_created.meta_key = 'first_name' AND wp_user_surname_created.meta_key = 'last_name' AND wp_user_name_created.user_id = lms_user.created_user_id AND lms_user.id = {$uid};";
    $user_data_result = $wpdb->get_results( $user_data_query, ARRAY_A );

    $user_data_result[0]['timecreated'] = date('j M Y', strtotime($user_data_result[0]['timecreated']));
    $user_type = $user_data_result[0]['role_type'];
    
    $course_id = $user_data_result[0]['course_id'];
    $programme_id = $user_data_result[0]['programme_id'];
    

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
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div id="wrap">    

    <?php 
        if ($student)
        {
    ?>
            <h2>Student Details:</h2>    
            <p>
                On this page you can see the details for a specific user. <br> 
                You can edit any editable details or delete the user.
            </p>   
            <hr>
            <h3>Student Details:</h3>    
                <p>            
                    You can edit the student's name here or delete the student entirely.<br>
            </p>    
    
    <?php  
        } 
        if ($facilitator)
        {
    ?>
            <h2>Facilitator Details:</h2>    
            <p>
                On this page you can see the details for a specific user. <br> 
                You can edit any editable details or delete the user.
            </p>
            <hr>
            <h3>Facilitator Details:</h3>    
                <p>            
                    You can edit the facilitator's name here or delete the facilitator entirely.<br>
            </p>    
    <?php } ?>    

    
    
    <form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" id="ftmv_edit_user" class="ftmv-lms-details-form">        
        <input type="hidden" name="action" value="ftmv_edit_user">  
		    <input type="hidden" name="ftmv_edit_user_nonce" value="<?php echo $ftmv_edit_user_nonce ?>" />			    			  
        
        <input type="hidden" id="user-id" name="uid" value="<?php echo esc_attr($uid); ?>">
        
        
        <!-- ftmv-lms-form-layout-container -->

        <div class="ftmv-lms-form-layout-container user-created-date">
            <div class="ftmv-lms-form-label-container">
                <label for="user-created-date">Date Created:</label>
            </div>            
            <div class="ftmv-lms-input-container">
                <input type="text" name="user-created-date" id="user-created-date" value="<?php echo esc_attr($user_data_result[0]['timecreated']); ?>" readonly>
            </div>
        </div>

        <div class="ftmv-lms-form-layout-container course-created-user">
            <div class="ftmv-lms-form-label-container">
                <label for="course-created-user">Created by:</label>
            </div>
            <div class="ftmv-lms-input-container">
                <input type="text" name="course-created-user" id="course-created-user" value="<?php echo esc_attr($user_data_result[0]['created_user_name']); ?>" readonly>
            </div>
        </div>

        <div class="ftmv-lms-form-layout-container programme-name">
            <div class="ftmv-lms-form-label-container">
                <label for="programme-name">Programme:</label>
            </div>
            <div class="ftmv-lms-input-container">
                <input type="text" name="programme-name" id="programme-name" value="<?php echo esc_attr($user_data_result[0]['programme']); ?>" readonly> <br>                
            </div>
        </div>


        <?php 
        if ($student)
        {
        ?>
                <div class="ftmv-lms-form-layout-container course-name">
                <div class="ftmv-lms-form-label-container">
                    <label for="course-name">Course:</label>
                </div>
                <div class="ftmv-lms-input-container">
                    <input type="text" name="course-name" id="course-name" value="<?php echo esc_attr($user_data_result[0]['course']); ?>" readonly> <br>
                </div>
            </div>           
        <?php  
            } 
        ?>

        <div class="ftmv-lms-form-layout-container user-username">
            <div class="ftmv-lms-form-label-container">
                <label for="user-username">Username: (Is Email Address)</label>
            </div>
            <div class="ftmv-lms-input-container">
                <input type="text" name="user-username" id="user-username" value="<?php echo esc_attr($user_data_result[0]['user_name']); ?>" readonly>
            </div>
        </div>        

        <div class="ftmv-lms-form-layout-container user-role">
            <div class="ftmv-lms-form-label-container">
                <label for="user-role">Role: (Can't be changed)</label>
            </div>
            <div class="ftmv-lms-input-container">
                <input type="text" name="user-role" id="user-role" value="<?php echo esc_attr($user_data_result[0]['role_display_name']); ?>" readonly>
            </div>
        </div>        

        <div class="ftmv-lms-form-layout-container user-fname">
            <div class="ftmv-lms-form-label-container">
                <label for="user-fname">First Name:</label>
            </div>
            <div class="ftmv-lms-input-container">
                <input type="text" name="user-fname" id="user-fname" placeholder="<?php echo esc_attr($user_data_result[0]['user_fname']); ?>" required> <br>
                
            </div>
        </div>        

        <div class="ftmv-lms-form-layout-container user-lname">
            <div class="ftmv-lms-form-label-container">
                <label for="user-lname">Last Name:</label>
            </div>
            <div class="ftmv-lms-input-container">
                <input type="text" name="user-lname" id="user-lname" placeholder="<?php echo esc_attr($user_data_result[0]['user_lname']); ?>" required> <br>
            </div>
        </div>        

        <div class="ftmv-lms-form-layout-container user-email">
            <div class="ftmv-lms-form-label-container">
                <label for="user-email">Email Address:</label>
            </div>
            <div class="ftmv-lms-input-container">
                <input type="email" name="user-email" id="user-email" placeholder="<?php echo esc_attr($user_data_result[0]['user_email']); ?>" required> <br>
            </div>
        </div>        
        

        <div class="ftmv-lms-form-button-container">            
            <div class="ftmv-lms-form-save-button-container">
                <button type="submit" class="button button-primary">Save Changes</button>            
            </div>
        </div>
    </form> 
    

    <form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" id="ftmv_delete_user" class="ftmv-lms-delete-user-form">        
        <input type="hidden" name="action" value="ftmv_delete_user">  
		<input type="hidden" name="ftmv_delete_user_nonce" value="<?php echo $ftmv_delete_user_nonce ?>" />			    
        
        <input type="hidden" id="user-id" name="uid" value="<?php echo esc_attr($uid); ?>">
        <input type="hidden" id="user-type" name="user-type" value="<?php echo esc_attr($user_type); ?>">        
        <input type="hidden" id="course-id" name="course-id" value="<?php echo esc_attr($course_id); ?>">        
        <input type="hidden" id="programme-id" name="programme-id" value="<?php echo esc_attr($programme_id); ?>">        
        
        <?php 
            if ($student)
            {
        ?>
                <div class="ftmv-lms-form-delete-button-container">            
                    <button type="submit" class="button button-primary delete-btn">Delete Student</button>            
                </div>    
        
        <?php  
            } 
            if ($facilitator)
            {
        ?>
               <div class="ftmv-lms-form-delete-button-container">            
                <button type="submit" class="button button-primary delete-btn">Delete Facilitator</button>            
            </div>    

        <?php } ?>   
        
        <!-- ftmv-lms-form-layout-container -->
        
    </form>

    <hr>
    <div class="ftmv-lms-back-button-container">  
        <?php 
            if ($student)
            {
        ?>
            <a href="<?php echo admin_url('admin.php?page=ftmv-lms-course-overview&course-id='. esc_attr($course_id) . '&programme-id=' . esc_attr($programme_id)) ?>"><button type="button" class="button button-primary">Back to Course Overview</button></a>            

        <?php  
            } 
                if ($facilitator)
                {
        ?>
                    <a href="<?php echo admin_url('admin.php?page=ftmv-lms-programme-overview&id=' . esc_attr($programme_id)) ?>"><button type="button" class="button button-primary">Back to Programme Overview</button></a>

        <?php 
            } 
        ?>  

    </div>
    
    <hr>
    
    

</div>