<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.toptal.com/resume/ratko-solaja
 * @since      1.0.0
 *
 * @package    ftmv_tp
 * @subpackage ftmv_tp/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    ftmv_tp
 * @subpackage ftmv_tp/admin
 * @author     FTMV
 */
class ftmv_tp_Admin {

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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/toptal-save-admin.css', array(), $this->version, 'all' );

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
            __( 'FTMV LMS', 'ftmv-tp' ),            
            'manage_options',
            'ftmv-tp',
            array( $this, 'display_top_level_courses'),
            'dashicons-forms',3
        );

        /* if ( isset( get_option('ftmv-tp-settings')['institution-name'] ) ) {
            
            add_menu_page(
                get_option('ftmv-tp-settings')['institution-name'] . ' Admin',
                __( get_option('ftmv-tp-settings')['institution-name'] . ' Admin', 'ftmv-tp' ),            
                'manage_options',
                'ftmv-tp',
                array( $this, 'display_general_settings_page'),
                'dashicons-forms',3
            );   
            
        } else {
            add_menu_page(
                'FTMV Test Plugin',
                __( 'Learner Management Plugin', 'ftmv-tp' ),            
                'manage_options',
                'ftmv-tp',
                array( $this, 'display_general_settings_page'),
                'dashicons-forms',3
            );
        } */

        add_submenu_page(
			'ftmv-tp',
			__( 'Top Level Courses', 'ftmv-tp' ),
			__( 'Top Level Courses', 'ftmv-tp' ),
			'manage_options',
			'ftmv-tp-general',
			array( $this, 'display_top_level_courses' )
		);

        add_submenu_page(
			'ftmv-tp',
			__( 'Add A Top Level Course', 'ftmv-tp' ),
			__( 'Add A Top Level Course', 'ftmv-tp' ),
			'manage_options',
			'ftmv-tp-add-course',
			array( $this, 'display_add_level_courses' )
		);

		// Create our settings page as a submenu page.
		/* add_submenu_page(
			'tools.php',
			__( 'FTMV Test Plugin', 'ftmv-tp' ),
			__( 'FTMV Test Plugin', 'ftmv-tp' ),
			'manage_options',
			'ftmv-tp',
			array( $this, 'display_settings_page' )
		); */

	}

    public function hide_pages( $submenu_file ) {
        global $plugin_page;

        $hidden_submenus = array(
            'ftmv-tp-add-course' => true,
        );

        // Select another submenu item to highlight (optional).
        if ( $plugin_page && isset( $hidden_submenus[ $plugin_page ] ) ) {
            $submenu_file = 'ftmv-tp-general';
        }

        // Hide the submenu.
        foreach ( $hidden_submenus as $submenu => $unused ) {
            remove_submenu_page( 'ftmv-tp', $submenu );
        }

        return $submenu_file;
    }

	/**
	 * Display the settings page content for the page we have created.
	 *
	 * @since    1.0.0
	 */

    public function display_add_level_courses() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/ftmv-tp-add-top-level-courses.php';

	}

    public function display_top_level_courses() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/ftmv-tp-top-level-courses.php';

	}

    public function display_general_settings_page() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/ftmv-tp-admin-display.php';

	}

	public function display_settings_page() {

		//require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/ftmv-tp-admin-display.php';

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
			__( get_option('ftmv-tp-settings')['institution-name'] . ' Settings', 'ftmv-tp' ),
			array( $this, 'sandbox_add_settings_section' ),
			$this->plugin_name . '-settings'
		);

		// Here we are going to add fields to our section.
		/* add_settings_field(
			'post-types',
			__( 'Post Types', 'ftmv-tp' ),
			array( $this, 'sandbox_add_settings_field_multiple_checkbox' ),
			$this->plugin_name . '-settings',
			$this->plugin_name . '-settings-section',
			array(
				'label_for' => 'post-types',
				'description' => __( 'Save button will be added only to the checked post types.', 'ftmv-tp' )
			)
		); */
		/* add_settings_field(
			'toggle-content-override',
			__( 'Append Button', 'toptal-save' ),
			array( $this, 'sandbox_add_settings_field_single_checkbox' ),
			$this->plugin_name . '-settings',
			$this->plugin_name . '-settings-section',
			array(
				'label_for' => 'toggle-content-override',
				'description' => __( 'If checked, it will append save button to the content.', 'toptal-save' )
			)
		); */
		/* add_settings_field(
			'toggle-status-override',
			__( 'Membership', 'toptal-save' ),
			array( $this, 'sandbox_add_settings_field_single_checkbox' ),
			$this->plugin_name . '-settings',
			$this->plugin_name . '-settings-section',
			array(
				'label_for' => 'toggle-status-override',
				'description' => __( 'If checked, this feature will be available only to logged in users. ', 'toptal-save' )
			)
		); */
		/* add_settings_field(
			'toggle-css-override',
			__( 'Our Styles', 'toptal-save' ),
			array( $this, 'sandbox_add_settings_field_single_checkbox' ),
			$this->plugin_name . '-settings',
			$this->plugin_name . '-settings-section',
			array(
				'label_for' => 'toggle-css-override',
				'description' => __( 'If checked, our style will be used.', 'toptal-save' )
			)
		); */
		/* add_settings_field(
			'text-save',
			__( 'Save Item', 'toptal-save' ),
			array( $this, 'sandbox_add_settings_field_input_text' ),
			$this->plugin_name . '-settings',
			$this->plugin_name . '-settings-section',
			array(
				'label_for' => 'text-save',
				'default'   => __( 'Save Item', 'toptal-save' )
			)
		);
		add_settings_field(
			'text-unsave',
			__( 'Unsave Item', 'toptal-save' ),
			array( $this, 'sandbox_add_settings_field_input_text' ),
			$this->plugin_name . '-settings',
			$this->plugin_name . '-settings-section',
			array(
				'label_for' => 'text-unsave',
				'default'   => __( 'Unsave Item', 'toptal-save' )
			)
		);
		add_settings_field(
			'text-saved',
			__( 'Saved. See saved items.', 'toptal-save' ),
			array( $this, 'sandbox_add_settings_field_input_text' ),
			$this->plugin_name . '-settings',
			$this->plugin_name . '-settings-section',
			array(
				'label_for' => 'text-saved',
				'default'   => __( 'Saved. See saved items.', 'toptal-save' )
			)
		); */
        
        /* add_settings_field(
            'institution-name',
            __( 'Institution Name:', 'ftmv-tp' ),
            array( $this, 'sandbox_add_settings_field_input_text' ),
            $this->plugin_name . '-settings',
            $this->plugin_name . '-settings-section',
            array(
                'label_for' => 'institution-name',
                'default'   => __( 'The name of the institution making use of this plugin', 'ftmv-tp')
            )
        ); */

        /* if ( isset( get_option('ftmv-tp-settings')['institution-name'] ) ) {

            add_settings_field(
                'course-name',
                __( 'Name of the course you:', 'ftmv-tp' ),
                array( $this, 'sandbox_add_settings_field_input_text' ),
                $this->plugin_name . '-settings',
                $this->plugin_name . '-settings-section',
                array(
                    'label_for' => 'course-name',
                    'default'   => __( 'The name of the institution making use of this plugin', 'ftmv-tp' )
                )
            );
        } */

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

	/**
	 * Sandbox our single checkboxes.
	 *
	 * @since    1.0.0
	 */
	/* public function sandbox_add_settings_field_single_checkbox( $args ) {

		$field_id = $args['label_for'];
		$field_description = $args['description'];

		$options = get_option( $this->plugin_name . '-settings' );
		$option = 0;

		if ( ! empty( $options[ $field_id ] ) ) {

			$option = $options[ $field_id ];

		}

		?>

			<label for="<?php echo $this->plugin_name . '-settings[' . $field_id . ']'; ?>">
				<input type="checkbox" name="<?php echo $this->plugin_name . '-settings[' . $field_id . ']'; ?>" id="<?php echo $this->plugin_name . '-settings[' . $field_id . ']'; ?>" <?php checked( $option, true, 1 ); ?> value="1" />
				<span class="description"><?php echo esc_html( $field_description ); ?></span>
			</label>

		<?php

	} */

	/**
	 * Sandbox our multiple checkboxes
	 *
	 * @since    1.0.0
	 */
	/* public function sandbox_add_settings_field_multiple_checkbox( $args ) {

		$field_id = $args['label_for'];
		$field_description = $args['description'];

		$options = get_option( $this->plugin_name . '-settings' );
		$option = array();

		if ( ! empty( $options[ $field_id ] ) ) {
			$option = $options[ $field_id ];
		}

		if ( $field_id == 'post-types' ) {

			$args = array(
				'public' => true
			);
			$post_types = get_post_types( $args, 'objects' );

			foreach ( $post_types as $post_type ) {

				if ( $post_type->name != 'attachment' ) {

					if ( in_array( $post_type->name, $option ) ) {
						$checked = 'checked="checked"';
					} else {
						$checked = '';
					}

					?>

						<fieldset>
							<label for="<?php echo $this->plugin_name . '-settings[' . $field_id . '][' . $post_type->name . ']'; ?>">
								<input type="checkbox" name="<?php echo $this->plugin_name . '-settings[' . $field_id . '][]'; ?>" id="<?php echo $this->plugin_name . '-settings[' . $field_id . '][' . $post_type->name . ']'; ?>" value="<?php echo esc_attr( $post_type->name ); ?>" <?php echo $checked; ?> />
								<span class="description"><?php echo esc_html( $post_type->label ); ?></span>
							</label>
						</fieldset>

					<?php

				}

			}

		} else {

			$field_args = $args['options'];

			foreach ( $field_args as $field_arg_key => $field_arg_value ) {

				if ( in_array( $field_arg_key, $option ) ) {
					$checked = 'checked="checked"';
				} else {
					$checked = '';
				}

				?>

					<fieldset>
						<label for="<?php echo $this->plugin_name . '-settings[' . $field_id . '][' . $field_arg_key . ']'; ?>">
							<input type="checkbox" name="<?php echo $this->plugin_name . '-settings[' . $field_id . '][]'; ?>" id="<?php echo $this->plugin_name . '-settings[' . $field_id . '][' . $field_arg_key . ']'; ?>" value="<?php echo esc_attr( $field_arg_key ); ?>" <?php echo $checked; ?> />
							<span class="description"><?php echo esc_html( $field_arg_value ); ?></span>
						</label>
					</fieldset>

				<?php

			}

		}

		?>

			<p class="description"><?php echo esc_html( $field_description ); ?></p>

		<?php

	} */

	/**
	 * Sandbox our inputs with text
	 *
	 * @since    1.0.0
	 */
	public function sandbox_add_settings_field_input_text( $args ) {

		$field_id = $args['label_for'];
		$field_default = $args['default'];

		$options = get_option( $this->plugin_name . '-settings' );
		$option = $field_default;

		if ( ! empty( $options[ $field_id ] ) ) {

			$option = $options[ $field_id ];

		}

		?>
		
			<input type="text" name="<?php echo $this->plugin_name . '-settings[' . $field_id . ']'; ?>" id="<?php echo $this->plugin_name . '-settings[' . $field_id . ']'; ?>" value="<?php echo esc_attr( $option ); ?>" class="regular-text" /><div class="inst-name-btn"></div>

		<?php

	}

    public function add_top_level_course () {
        error_log('this worked');
        $user_id = wp_get_current_user()->ID;

        if(isset($_POST['ftmv_add_course_nonce'])) {
            if(wp_verify_nonce($_POST['ftmv_add_course_nonce'], 'ftmv_add_course_nonce')) {
                
                $new_top_level_course = sanitize_text_field( $_POST['course-name'] );
                date_default_timezone_set('Africa/Johannesburg');
                $time_stamp = date("Y-m-d H:i:s");

                //error_log('TIME = ' . $today . ' We are going to save course name = ' . $new_top_level_course . ' and user Id = ' . $user_id . 'to the database');
                
                global $wpdb;
                $table = $wpdb->prefix.'ftmv_main_course_table';
                
                $data = array('name' => $new_top_level_course, 'timecreated' => $time_stamp, 'created_user_id' => $user_id);
                
                $format = array('%s', '%s', '%d');
                
                $wpdb->insert($table,$data,$format);

                wp_redirect( admin_url("/admin.php?page=ftmv-tp-general") );
                 

            } else {
                exit;
            }
        }
    }

}
