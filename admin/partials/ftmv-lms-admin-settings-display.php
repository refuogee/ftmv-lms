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


?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div id="wrap">
	<form method="post" action="options.php">
        <div class="wrap-test">        
        
            <?php
                settings_fields( 'ftmv-lms-settings' );
                do_settings_sections( 'ftmv-lms-settings' );
                submit_button();
            ?>
        </div>
	</form>
</div>