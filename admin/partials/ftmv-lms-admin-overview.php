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


    global $wpdb;
    $current_user_id = wp_get_current_user()->ID;
    $admin_type_query = "SELECT roles_table.role_type FROM wp_ftmv_lms_roles_table as roles_table LEFT JOIN wp_ftmv_lms_user_table AS lms_user_table ON roles_table.id = lms_user_table.assigned_role_id WHERE lms_user_table.wp_user_id = {$current_user_id};";

    $admin_type_array = $wpdb->get_results( $admin_type_query, ARRAY_A );
    $admin_type = $admin_type_array[0]['role_type'];

    $admin = false;
    $facilitator = false;

    if ($admin_type == 'facilitator')
    {
        $facilitator = true;
    }
    else 
    {
        $admin = true;
    }

?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div id="wrap">
    <h2>Welcome to the Learner Management System by FTMV</h2>
    <p>
        This plugin gives you the ability to create <strong>Programmes.</strong> <br>
        For each of these programmes <strong>Facilitators</strong> are then created and added to manage the running of scheduled courses under each programme. <br>
        This includes the creation and addition of students associated with each course. 
    </p>

    <?php 
        if ($admin)
        {
    ?> 

        <h3>Admin Level Users:</h3> 
        <p>
            Are the only level of user permitted to create and edit programmes and create and edit facilitators for each of these programmes. <br>
            They can of course create and edit courses for each programme and create, add and edit students that will be associated with the specific courses.
        </p>

    <?php 
        }
    
        if ($facilitator || $admin)
        {
    ?>
        <h3>Facilitators:</h3>
        <p>
            Are only allowed to create, edit and delete scheduled courses. They can also create, add, edit, and delete students associated with each course. <br>
            Facilitators cannot create top level programmes or create facilitators.
        </p>
    <?php 
        }
    ?>
	<div class="option-button-container">
        <a href="<?php echo admin_url('admin.php?page=ftmv-lms-programmes'); ?>"> <button type="button" class="button button-primary">Programmes</button></a>
    </div> 
</div>