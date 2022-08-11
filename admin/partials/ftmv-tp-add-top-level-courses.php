<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.toptal.com/resume/ratko-solaja
 * @since      1.0.0
 *
 * @package    Toptal_Save
 * @subpackage Toptal_Save/admin/partials
 */

$ftmv_add_course_nonce = wp_create_nonce('ftmv_add_course_nonce');

?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div id="wrap">
<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" id="ftmv_add_course">
        <input type="hidden" name="action" value="ftmv_add_course">
		<input type="hidden" name="ftmv_add_course_nonce" value="<?php echo $ftmv_add_course_nonce ?>" />			    			
        <div class="wrap-test">        
            <h2>Add a Course Page:</h2>
            <p>
                This is where you create a top level course. <br>
                Once a top level course is created the ability to create or add a facilitator to that course, create courses that run during specific dates and add students to courses will become available.
            </p>            
            <input type="text" name="course-name" id="course-name" class="regular-text" />            
            <button type="submit" class="button button-primary">Add Course</button>            
            <a href="<?php echo admin_url('admin.php?page=ftmv-tp-general'); ?>"><button type="button" class="button button-primary">Cancel</button></a>
        </div>
	</form>
</div>