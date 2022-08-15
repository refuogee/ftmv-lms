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
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/ftmv-lms-admin.css', array(), $this->version, 'all' );

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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/toptal-save-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Register the settings page for the admin area.
	 *
	 * @since    1.0.0
	 */

    public function register_settings_page() {

        add_menu_page(
            'FTMV LMS',
            __( 'FTMV LMS', 'ftmv-lms-overview' ),            
            'manage_options',
            'ftmv-lms-overview',
            array( $this, 'display_admin_overview_screen'),
            'dashicons-forms',3
        );

        /* if ( isset( get_option('ftmv-lms-settings')['institution-name'] ) ) {
            
            add_menu_page(
                get_option('ftmv-lms-settings')['institution-name'] . ' Admin',
                __( get_option('ftmv-lms-settings')['institution-name'] . ' Admin', 'ftmv-lms' ),            
                'manage_options',
                'ftmv-lms',
                array( $this, 'display_general_settings_page'),
                'dashicons-forms',3
            );   
            
        } else {
            add_menu_page(
                'FTMV Test Plugin',
                __( 'Learner Management Plugin', 'ftmv-lms' ),            
                'manage_options',
                'ftmv-lms',
                array( $this, 'display_general_settings_page'),
                'dashicons-forms',3
            );
        } */

        add_submenu_page(
			'ftmv-lms-overview',
			__( 'Overview', 'ftmv-lms-overview' ),
			__( 'Overview', 'ftmv-lms-overview' ),
			'manage_options',
			'ftmv-lms-overview',
			array( $this, 'display_admin_overview_screen' )
		);

        add_submenu_page(
			'ftmv-lms-overview',
			__( 'Programmes', 'ftmv-lms-programmes' ),
			__( 'Programmes', 'ftmv-lms-programmes' ),
			'manage_options',
			'ftmv-lms-programmes',
			array( $this, 'display_programmes' )
		);

        add_submenu_page(
			'ftmv-lms-overview',
			__( 'Add a Programme', 'ftmv-lms-add-programme' ),
			__( 'Add a Programme', 'ftmv-lms-add-programme' ),
			'manage_options',
			'ftmv-lms-add-programme',
			array( $this, 'display_add_programme' )
		);
        
        add_submenu_page(
			'ftmv-lms-overview',
			__( 'Programme Details', 'ftmv-lms-programme-overview' ),
			__( 'Programme Details', 'ftmv-lms-programme-overview' ),
			'manage_options',
			'ftmv-lms-programme-overview',
			array( $this, 'display_programme_overview' )
		);

        add_submenu_page(
			'ftmv-lms-overview',
			__( 'Add a Course Page', 'ftmv-lms-add-course' ),
			__( 'Add a Course Page', 'ftmv-lms-add-course' ),
			'manage_options',
			'ftmv-lms-add-course',
			array( $this, 'display_add_course' )
		);

	}

    public function hide_pages( $submenu_file ) {
        global $plugin_page;

        $hidden_submenus = array(
            'ftmv-lms-add-course' => true,
        );

        // Select another submenu item to highlight (optional).
        if ( $plugin_page && isset( $hidden_submenus[ $plugin_page ] ) ) {
            $submenu_file = 'ftmv-lms';
        }

        // Hide the submenu.
        foreach ( $hidden_submenus as $submenu => $unused ) {
            remove_submenu_page( 'ftmv-lms', $submenu );
        }

        return $submenu_file;
    }

	/**
	 * Display the settings page content for the page we have created.
	 *
	 * @since    1.0.0
	 */

    public function display_add_course() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/ftmv-lms-add-course.php';

	}
    
    public function display_programme_overview() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/ftmv-lms-programme-overview.php';

	}

    public function display_add_programme() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/ftmv-lms-add-programme.php';

	}    

    public function display_admin_overview_screen() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/ftmv-lms-admin-overview.php';

	}    

    public function display_programmes() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/ftmv-lms-programmes.php';

	}

    public function display_general_settings_page() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/ftmv-lms-admin-display.php';

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
			__( get_option('ftmv-lms-settings')['institution-name'] . ' Settings', 'ftmv-lms' ),
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

    public function add_course () {
        error_log('called in add course');
        $user_id = wp_get_current_user()->ID;

        if(isset($_POST['ftmv_add_course_nonce'])) {
            
            if(wp_verify_nonce($_POST['ftmv_add_course_nonce'], 'ftmv_add_course_nonce')) {

                echo 'nonce verified';
                
                $new_course = sanitize_text_field( $_POST['course-name'] );
                $programme_id = sanitize_text_field( $_POST['programme-id'] );

                date_default_timezone_set('Africa/Johannesburg');

                $time_stamp = date("Y-m-d H:i:s"); 
                $start_date = sanitize_text_field( $_POST['course-start-date'] );
                $end_date = sanitize_text_field( $_POST['course-end-date'] );

                //error_log('TIME = ' . $today . ' We are going to save course name = ' . $new_top_level_course . ' and user Id = ' . $user_id . 'to the database');
                
                global $wpdb;
                $course_table = $wpdb->prefix.'ftmv_lms_course_table';
                $programme_table = $wpdb->prefix.'ftmv_lms_main_programme_table';
                
                $data = array('name' => $new_course, 'timecreated' => $time_stamp, 'created_user_id' => $user_id, 'main_programme_id' => $programme_id, 'startdate' => $start_date,  'enddate' => $end_date);
                
                $format = array('%s', '%s', '%d', '%d', '%s', '%s');
                
                $wpdb->insert($course_table, $data, $format);

                $programme_query = "UPDATE {$programme_table} SET course_count = course_count + 1 WHERE id = {$programme_id}";

                $programme_result = $wpdb->get_results( $programme_query, ARRAY_A );

                error_log($programme_query);

                error_log($programme_result);

                wp_redirect( admin_url("/admin.php?page=ftmv-lms-programme-overview&id=" . $programme_id) ); 
                 

            } else {
                exit;
            }
        }
    } 

    public function add_top_level_programme () {
        error_log('this worked');
        $user_id = wp_get_current_user()->ID;

        if(isset($_POST['ftmv_add_programme_nonce'])) {
            if(wp_verify_nonce($_POST['ftmv_add_programme_nonce'], 'ftmv_add_programme_nonce')) {
                
                $new_top_level_programme = sanitize_text_field( $_POST['programme-name'] );
                date_default_timezone_set('Africa/Johannesburg');
                $time_stamp = date("Y-m-d H:i:s");

                //error_log('TIME = ' . $today . ' We are going to save course name = ' . $new_top_level_course . ' and user Id = ' . $user_id . 'to the database');
                
                global $wpdb;
                $table = $wpdb->prefix.'ftmv_lms_main_programme_table';
                
                $data = array('name' => $new_top_level_programme, 'timecreated' => $time_stamp, 'created_user_id' => $user_id);
                
                $format = array('%s', '%s', '%d');
                
                $wpdb->insert($table,$data,$format);

                wp_redirect( admin_url("/admin.php?page=ftmv-lms-programmes") );
                 

            } else {
                exit;
            }
        }
    }

}
