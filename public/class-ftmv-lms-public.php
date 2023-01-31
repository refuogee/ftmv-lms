<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.toptal.com/resume/ratko-solaja
 * @since      1.0.0
 *
 * @package    Toptal_Save
 * @subpackage Toptal_Save/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Toptal_Save
 * @subpackage Toptal_Save/public
 * @author     Ratko Solaja <ratko@toptal.com>
 */
class Ftmv_Lms_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Toptal_Save_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Toptal_Save_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		$options = get_option( $this->plugin_name . '-settings' );

		if ( ! empty( $options['toggle-css-override'] ) && $options['toggle-css-override'] == 1 ) {
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/ftmv-lms-public.css', array(), $this->version, 'all' );
		}

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Toptal_Save_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Toptal_Save_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/ftmv-lms-public.js', array( 'jquery' ), $this->version, false );

		/* // Get our options
		$options = get_option( $this->plugin_name . '-settings' );

		// Get our text
		$item_save_text = $options['text-save'];
		$item_unsave_text = $options['text-unsave'];
		$item_saved_text = $options['text-saved'];
		$item_no_saved = $options['text-no-saved'];

		$saved_page_id = get_option( 'toptal_save_saved_page_id' );
		$saved_page_url = get_permalink( $saved_page_id );

		wp_localize_script(
			$this->plugin_name,
			'toptal_save_ajax',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'item_save_text' => $item_save_text,
				'item_unsave_text' => $item_unsave_text,
				'item_saved_text' => $item_saved_text,
				'item_no_saved' => $item_no_saved,
				'saved_page_url' => $saved_page_url
			)
		); */

	}	

	/**
	 * Append the button to the end of the content.
	 *
	 * @since    1.0.0
	 */

    public function km_get_user_capabilities( $user = null ) {

        $user_info = get_userdata($user);

        /* $user = $user ? new WP_User( $user ) : wp_get_current_user();

        $user = wp_get_current_user();
        $roles = ( array ) $user->roles;

        return array_keys( $user->allcaps ); */


    }

	public function append_the_button( $content ) {

        $user_ID = get_current_user_id(); 

        $user_info = get_userdata($user_ID);

        
        if ($user_ID == 0) {
            // The user ID is 0, therefore the current user is not logged in
            return; // escape this function, without making any changes
        }

        // error_log(print_r($this->km_get_user_capabilities($user_ID), true));

        if ( is_page( 'user-capabilites-page' ) ) {
            $custom_content = '';
			ob_start();
			
            echo 'Username: ' . $user_info->user_login . "\n";
            echo 'User roles: ' . implode(', ', $user_info->roles) . "\n";
            echo '<pre>';
            echo print_r($user_info->allcaps);
            echo '</pre>';
			$custom_content .= ob_get_contents();
			ob_end_clean();
			$content = $content . $custom_content;
            return $content;
        }   
        else 
        {
            return $content;
        }
	}

}
