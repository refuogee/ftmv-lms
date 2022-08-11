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


?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div id="wrap">
	<form method="post" action="options.php">
        <div class="wrap-test">        
        
            <?php
                settings_fields( 'ftmv-tp-settings' );
                do_settings_sections( 'ftmv-tp-settings' );
                submit_button();
            ?>
        </div>
	</form>
</div>