<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.ftmv.digital
 * @since      1.0.0
 *
 * @package    ftmv_lms
 * @subpackage ftmv_lms/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    ftmv_lms
 * @subpackage ftmv_lms/admin
 * @author     FTMV
 */
class ftmv_lms_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

    /* The ID of this plugin.
    *
    * @since    1.0.0
    * @access   private
    * @var      string    $plugin_name_underscore the name but with underscores for certain situations
    */
   private $plugin_name_underscore;

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $plugin_name_underscore, $version ) {

		$this->plugin_name = $plugin_name;
        $this->plugin_name_underscore = $plugin_name_underscore;
		$this->version = $version;
        $this->load_dependencies();
	}
    private function load_dependencies() {

		/** 
		* The class responsible for defining all actions that occur in the admin area.
		*/
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/ftmv-lms-admin-helpers.php';

	}
	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . "css/{$this->plugin_name}-admin.css", array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . "js/{$this->plugin_name}-admin-scripts.js", array( 'jquery' ), $this->version, true );

	}

	/**
	 * Register the settings page for the admin area.
	 *
	 * @since    1.0.0
	 */

    public function register_settings_page() {

        add_menu_page(
            'FTMV LMS',
            __( 'FTMV LMS', "{$this->plugin_name}-overview" ),            
            'manage_options',
            "{$this->plugin_name}-overview",
            array( $this, 'display_admin_overview_screen'),
            'dashicons-forms',3
        );        

        add_submenu_page(
			"{$this->plugin_name}-overview",
			__( 'Overview', "{$this->plugin_name}-overview" ),
			__( 'Overview', "{$this->plugin_name}-overview" ),
			'manage_options',
			"{$this->plugin_name}-overview",
			array( $this, 'display_admin_overview_screen' )
		);

        add_submenu_page(
			"{$this->plugin_name}-overview",
			__( 'Programmes', "{$this->plugin_name}-programmes" ),
			__( 'Programmes', "{$this->plugin_name}-programmes" ),
			'manage_options',
			"{$this->plugin_name}-programmes",
			array( $this, 'display_programmes' )
		);

        add_submenu_page(
			"{$this->plugin_name}-overview",
			__( 'Add a Programme', "{$this->plugin_name}-add-programme" ),
			__( 'Add a Programme', "{$this->plugin_name}-add-programme" ),
			'manage_options',
			"{$this->plugin_name}-add-programme",
			array( $this, 'display_add_programme' )
		);
        
        add_submenu_page(
			"{$this->plugin_name}-overview",
			__( 'Programme Details', "{$this->plugin_name}-programme-overview" ),
			__( 'Programme Details', "{$this->plugin_name}-programme-overview" ),
			'manage_options',
			"{$this->plugin_name}-programme-overview",
			array( $this, 'display_programme_overview' )
		);

        add_submenu_page(
			"{$this->plugin_name}-overview",
			__( 'Course Details', "{$this->plugin_name}-course-overview" ),
			__( 'Course Details', "{$this->plugin_name}-course-overview" ),
			'manage_options',
			"{$this->plugin_name}-course-overview",
			array( $this, 'display_course_overview' )
		);

        add_submenu_page(
			"{$this->plugin_name}-overview",
			__( 'Add a Course', "{$this->plugin_name}-add-course" ),
			__( 'Add a Course', "{$this->plugin_name}-add-course" ),
			'manage_options',
			"{$this->plugin_name}-add-course",
			array( $this, 'display_add_course' )
		);

        add_submenu_page(
			"{$this->plugin_name}-overview",
			__( 'Add User', "{$this->plugin_name}-add-user" ),
			__( 'Add User', "{$this->plugin_name}-add-user" ),
			'manage_options',
			"{$this->plugin_name}-add-user",
			array( $this, 'display_add_user' )
		);

        add_submenu_page(
			"{$this->plugin_name}-overview",
			__( 'User Details', "{$this->plugin_name}-edit-user" ),
			__( 'User Details', "{$this->plugin_name}-edit-user" ),
			'manage_options',
			"{$this->plugin_name}-edit-user",
			array( $this, 'display_edit_user' )
		);

	}

    public function hide_pages( $submenu_file ) {
        global $plugin_page;

        $hidden_submenus = array(
            "{$this->plugin_name}-add-course" => true,
            "{$this->plugin_name}-course-overview" => true,
            "{$this->plugin_name}-programme-overview" => true,
            "{$this->plugin_name}-add-programme" => true,
            "{$this->plugin_name}-add-user" => true
        );

        // Select another submenu item to highlight (optional).
        if ( $plugin_page && isset( $hidden_submenus[ $plugin_page ] ) ) {
            $submenu_file = "{$this->plugin_name}-overview";
        }

        // Hide the submenu.
        foreach ( $hidden_submenus as $submenu => $unused ) {
            remove_submenu_page( "{$this->plugin_name}-overview", $submenu );
        }

        return $submenu_file;
    }

	/**
	 * Display the settings page content for the page we have created.
	 *
	 * @since    1.0.0
	 */
    public function display_edit_user() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . "admin/partials/{$this->plugin_name}-user-overview.php";

	}

    public function display_add_user() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . "admin/partials/{$this->plugin_name}-add-user.php";

	}

    public function display_add_course() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . "admin/partials/{$this->plugin_name}-add-course.php";

	}

    public function display_course_overview() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . "admin/partials/{$this->plugin_name}-course-overview.php";

	}
    
    public function display_programme_overview() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . "admin/partials/{$this->plugin_name}-programme-overview.php";

	}

    public function display_add_programme() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . "admin/partials/{$this->plugin_name}-add-programme.php";

	}    

    public function display_admin_overview_screen() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . "admin/partials/{$this->plugin_name}-admin-overview.php";

	}    

    public function display_programmes() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . "admin/partials/{$this->plugin_name}-programmes.php";

	}

    public function display_general_settings_page() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . "admin/partials/{$this->plugin_name}-admin-display.php";

	}

	public function display_settings_page() {

		//require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/ftmv-lms-admin-display.php';

	}

	/**
	 * Register the settings for our settings page.
	 *
	 * @since    1.0.0
	 */
	public function register_settings() {

		// Here we are going to register our setting.
		register_setting(
			$this->plugin_name . '-settings',
			$this->plugin_name . '-settings',
			array( $this, 'sandbox_register_setting' )
		);

		// Here we are going to add a section for our setting.
        //echo '<div><p>Test</p></div>';
		add_settings_section(
			$this->plugin_name . '-settings-section',
			__( get_option("{$plugin_name}-settings")['institution-name'] . ' Settings', "{$plugin_name}" ),
			array( $this, 'sandbox_add_settings_section' ),
			$this->plugin_name . '-settings'
		);
	}

	/**
	 * Sandbox our settings.
	 *
	 * @since    1.0.0
	 */
	public function sandbox_register_setting( $input ) {

		$new_input = array();

		if ( isset( $input ) ) {
			// Loop trough each input and sanitize the value if the input id isn't post-types
			foreach ( $input as $key => $value ) {
				if ( $key == 'post-types' ) {
					$new_input[ $key ] = $value;
				} else {
					$new_input[ $key ] = sanitize_text_field( $value );
				}
			}
		}

		return $new_input;

	}

	/**
	 * Sandbox our section for the settings.
	 *
	 * @since    1.0.0
	 */
	public function sandbox_add_settings_section() {

		return;

	}


    public function add_user () {
        
        $created_user_id = wp_get_current_user()->ID;

        if(isset($_POST['ftmv_add_user_nonce'])) {
            
            if(wp_verify_nonce($_POST['ftmv_add_user_nonce'], 'ftmv_add_user_nonce')) {

                $course_id = sanitize_text_field( $_POST['course-id'] );
                $programme_id = sanitize_text_field( $_POST['programme-id'] );
                $user_type = sanitize_text_field( $_POST['user-type'] );
                $user_name = sanitize_text_field( $_POST['user-name'] );
                $user_surname = sanitize_text_field( $_POST['user-surname'] );
                $user_email = sanitize_email( $_POST['user-email'] );
                $form_url = sanitize_url( $_POST['current-url'] );
                
                manage_user_creation($created_user_id, $user_type, $course_id, $programme_id, $user_name, $user_surname, $user_email);
                
                $transient_message = get_transient( 'user_creation_form_transient' );       
                       
                if ($transient_message['message_type'] =='success' )
                {
                    if ($user_type == "student")
                    {
                        wp_redirect( admin_url("/admin.php?page=ftmv-lms-course-overview&course-id={$course_id}&programme-id={$programme_id}") );
                    }

                    if ($user_type == "facilitator")
                    {
                        wp_redirect( admin_url("/admin.php?page=ftmv-lms-programme-overview&id={$programme_id}") );
                    }
                    
                }                         
                else 
                {
                    wp_redirect( $form_url );
                }
                
                
                // wp_redirect( $form_url );

            } else {
                exit;
            }
        }
    }
    
    public function add_course () {
        
        $user_id = wp_get_current_user()->ID;

        if(isset($_POST['ftmv_add_course_nonce'])) {
            
            if(wp_verify_nonce($_POST['ftmv_add_course_nonce'], 'ftmv_add_course_nonce')) {
                
                $new_course = sanitize_text_field( $_POST['course-name'] );
                $programme_id = sanitize_text_field( $_POST['programme-id'] );

                // date_default_timezone_set('Africa/Johannesburg');

                $time_stamp = date("Y-m-d H:i:s"); 
                $start_date = sanitize_text_field( $_POST['course-start-date'] );
                $end_date = sanitize_text_field( $_POST['course-end-date'] );
                
                global $wpdb;
                $course_table = $wpdb->prefix.'ftmv_lms_course_table';
                $programme_table = $wpdb->prefix.'ftmv_lms_main_programme_table';
                
                $data = array('name' => $new_course, 'timecreated' => $time_stamp, 'created_user_id' => $user_id, 'main_programme_id' => $programme_id, 'startdate' => $start_date,  'enddate' => $end_date);
                
                $format = array('%s', '%s', '%d', '%d', '%s', '%s');
                
                $wpdb->insert($course_table, $data, $format);

                $programme_query = "UPDATE {$programme_table} SET course_count = course_count + 1 WHERE id = {$programme_id}";

                $programme_result = $wpdb->get_results( $programme_query, ARRAY_A );
                
                $current_user_id = wp_get_current_user()->ID;
                $course_success_details = array('user_id' => $current_user_id, 'message' => 'Course Created Successfully');
                set_transient( 'course_creation_form_message_transient', $course_success_details, 60 );

                wp_redirect( admin_url("/admin.php?page=ftmv-lms-programme-overview&id=" . $programme_id) );                  

            } else {
                exit;
            }
        }
    } 

    public function add_programme () {
        
        $user_id = wp_get_current_user()->ID;

        if(isset($_POST['ftmv_add_programme_nonce'])) {
            if(wp_verify_nonce($_POST['ftmv_add_programme_nonce'], 'ftmv_add_programme_nonce')) {
                
                $new_top_level_programme = sanitize_text_field( $_POST['programme-name'] );
                
                $programme_id = create_programme($user_id, $new_top_level_programme);               

                create_programme_student_role($this->plugin_name, $programme_id, $new_top_level_programme);               
                create_programme_facilitator_role($this->plugin_name, $programme_id, $new_top_level_programme);               

                wp_redirect( admin_url("/admin.php?page=ftmv-lms-programmes") );
                 

            } else {
                exit;
            }
        }
    }

    public function delete_course () {        
        
        $user_id = wp_get_current_user()->ID;

        if(isset($_POST['ftmv_delete_course_nonce'])) {
            
            if(wp_verify_nonce($_POST['ftmv_delete_course_nonce'], 'ftmv_delete_course_nonce')) {

                $course_id = sanitize_text_field( $_GET['course-id'] );
                $programme_id = sanitize_text_field( $_GET['programme-id'] );                
                handle_course_deletion($programme_id, $course_id, $this->plugin_name_underscore);
                wp_redirect( admin_url("/admin.php?page=ftmv-lms-programme-overview&id={$programme_id}" ) );
            } else {
                error_log('nonce NOT verified');
                exit;
            }
        }
    }

    

    public function delete_programme () {        
        
        $user_id = wp_get_current_user()->ID;

        if(isset($_POST['ftmv_delete_programme_nonce'])) {
            
            if(wp_verify_nonce($_POST['ftmv_delete_programme_nonce'], 'ftmv_delete_programme_nonce')) {
                   
                $programme_id = sanitize_text_field( $_GET['programme-id'] );
                handle_programme_deletion($programme_id, $this->plugin_name_underscore);
                wp_redirect( admin_url("/admin.php?page=ftmv-lms-programmes") );
            } else {
                error_log('nonce NOT verified');
                exit;
            }
        }
    }

    public function edit_programme () {        
        
        $user_id = wp_get_current_user()->ID;

        if(isset($_POST['ftmv_edit_programme_nonce'])) {
            
            if(wp_verify_nonce($_POST['ftmv_edit_programme_nonce'], 'ftmv_edit_programme_nonce')) {

                // echo 'nonce verified';
                
                $new_course = sanitize_text_field( $_POST['programme-name'] );
                $programme_id = sanitize_text_field( $_GET['programme-id'] );
                
                global $wpdb;
                
                $programme_table = $wpdb->prefix.'ftmv_lms_main_programme_table';

                $programme_update_query = "UPDATE {$programme_table} SET name = '{$new_course}' WHERE id = {$programme_id}";

                $programme_result = $wpdb->get_results( $programme_update_query, ARRAY_A );

                wp_redirect( admin_url("/admin.php?page=ftmv-lms-programme-overview&id=" . $programme_id) ); 
                 

            } else {
                echo 'nonce NOT verified';
                exit;
            }
        }
    } 

    public function edit_course () {        
        
        $user_id = wp_get_current_user()->ID;

        if(isset($_POST['ftmv_edit_course_nonce'])) {
            
            if(wp_verify_nonce($_POST['ftmv_edit_course_nonce'], 'ftmv_edit_course_nonce')) 
            {
                $update_name = false;
                // echo 'nonce verified';
                if (isset($_POST['course-name']) && strlen($_POST['course-name']) > 0 )
                {
                    $new_course_name = strtolower(sanitize_text_field( $_POST['course-name'] ));
                    $update_name = true;
                }

                $course_id = sanitize_text_field( $_GET['course-id'] );

                $course_start_date = sanitize_text_field( $_POST['course-startdate'] );
                $course_end_date = sanitize_text_field( $_POST['course-enddate'] );

                // echo $course_start_date;
                // echo $course_end_date;

                var_dump($_POST);
                
                global $wpdb;
                
                $course_table = $wpdb->prefix.'ftmv_lms_course_table';
 
                if ($update_name) 
                {
                    $course_update_query = "UPDATE {$course_table} SET name = '{$new_course_name}', startdate = '{$course_start_date}', enddate = '{$course_end_date}' WHERE id = {$course_id}";
                }
                else
                {
                    $course_update_query = "UPDATE {$course_table} SET startdate = '{$course_start_date}', enddate = '{$course_end_date}' WHERE id = {$course_id}";
                }
                
                $course_result = $wpdb->get_results( $course_update_query, ARRAY_A );
                wp_redirect( admin_url("/admin.php?page=ftmv-lms-course-overview&course-id=" . $course_id) );
            } 
            else 
            {
                error_log('nonce NOT verified');
                exit;
            }
        }
    } 

    public function edit_user () {        
        $created_user_id = wp_get_current_user()->ID;

        if(isset($_POST['ftmv_edit_user_nonce'])) {
            
            if(wp_verify_nonce($_POST['ftmv_edit_user_nonce'], 'ftmv_edit_user_nonce')) 
            {
                var_dump($_POST);
                /* $course_id = sanitize_text_field( $_POST['course-id'] );
                $programme_id = sanitize_text_field( $_POST['programme-id'] );
                $user_type = sanitize_text_field( $_POST['user-type'] );
                $user_name = sanitize_text_field( $_POST['user-name'] );
                $user_surname = sanitize_text_field( $_POST['user-surname'] );
                $user_email = sanitize_email( $_POST['user-email'] );
                $form_url = sanitize_url( $_POST['current-url'] );
                
                manage_user_creation($created_user_id, $user_type, $course_id, $programme_id, $user_name, $user_surname, $user_email);
                
                $transient_message = get_transient( 'user_creation_form_transient' );       
                       
                if ($transient_message['message_type'] =='success' )
                {
                    if ($user_type == "student")
                    {
                        wp_redirect( admin_url("/admin.php?page=ftmv-lms-course-overview&course-id={$course_id}&programme-id={$programme_id}") );
                    }

                    if ($user_type == "facilitator")
                    {
                        wp_redirect( admin_url("/admin.php?page=ftmv-lms-programme-overview&id={$programme_id}") );
                    }
                    
                }                         
                else 
                {
                    wp_redirect( $form_url );
                } */
                
                
                // wp_redirect( $form_url );

            } else {
                echo 'broke';
                exit;
            }
        }
    }

}
