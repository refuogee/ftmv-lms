<?php    
    
    function create_wp_user($programme_id, $user_name, $user_surname, $user_type, $role_name, $user_email)
    {
        //  $password = wp_generate_password( 6, false );
        
        $password = ('testpass123');
           
        echo '<br>';
        
        
        $wp_user_id = wp_create_user( $user_email, $password, $user_email );
        
        $current_user_id = wp_get_current_user()->ID;
        
        if ( is_wp_error( $wp_user_id ) ) 
        {
            error_log('ERROR: ' . $wp_user_id->get_error_message());
            $message_type = 'error';
            $user_creation_details = array('user_id' => $current_user_id, 'message_type' => $message_type, 'message' => $wp_user_id->get_error_message(), 'user_name' => $user_name, 'user_surname' => $user_surname, 'user_email' => $user_email);            
            set_transient( 'user_creation_form_transient', $user_creation_details, 60 );
        } 
        else 
        {
            wp_update_user(
                array(
                  'ID'          =>    $wp_user_id,
                  'nickname'    =>    $user_email,
                  'first_name'  =>    $user_name,
                  'last_name'   =>    $user_surname
                )
              );      
              $new_wp_user = new WP_User( $wp_user_id );
              error_log('About to create new WP user');
              error_log("Role name = {$role_name}");

              $new_wp_user->set_role( $role_name );      
              $message_type = 'success';
              $user_creation_details = array('user_id' => $current_user_id, 'message_type' => $message_type, 'message' => 'User Successfuly Created', 'user_name' => $user_name, 'user_surname' => $user_surname, 'user_email' => $user_email);
              set_transient( 'user_creation_form_transient', $user_creation_details, 60 );
              
              return $wp_user_id;
        }
    }

    function add_user_to_database($wp_user_id, $created_user_id, $programme_id, $course_id, $role_id, $user_type)
    {
        $time_stamp = date("Y-m-d H:i:s"); 
        global $wpdb;
        $user_table = $wpdb->prefix.'ftmv_lms_user_table';
        $user_data = array('wp_user_id' => $wp_user_id, 'timecreated' => $time_stamp, 'created_user_id' => $created_user_id, 'main_programme_id' => $programme_id, 'course_id' => $course_id, 'assigned_role_id' => $role_id);
        $format = array('%d', '%s', '%d', '%d', '%d');
        $wpdb->insert($user_table, $user_data, $format);
        $course_table = $wpdb->prefix.'ftmv_lms_course_table';
        if ($user_type == "student") 
        {
            $wpdb->query("UPDATE {$course_table} SET student_count = student_count + 1 WHERE id = {$course_id}");
        }
        
    }

    function manage_user_creation($created_user_id, $user_type, $course_id, $programme_id, $user_name, $user_surname, $user_email)    
    {
        
        global $wpdb;
        $roles_table = $wpdb->prefix.'ftmv_lms_roles_table';        
        $role_query = "SELECT id, role_name FROM {$roles_table} WHERE main_programme_id = {$programme_id} AND role_type = '{$user_type}'";        
        
        error_log("Role query: {$role_query} ");

        $role_data = $wpdb->get_results( $role_query, ARRAY_A );        

        error_log("Role data");
        error_log(print_r($role_data, true));
        

        $role_name = $role_data[0]['role_name'];

        error_log("inside manage user creation and the role name here is: {$role_name}");

        $role_id = $role_data[0]['id'];
        
        $wp_user_id = create_wp_user($programme_id, $user_name, $user_surname, $user_type, $role_name, $user_email);
        add_user_to_database($wp_user_id, $created_user_id, $programme_id, $course_id, $role_id, $user_type);     
         
    }

    // As the name says this helper function creates a programme and adds it to the programmes table

    function create_programme($user_id, $new_top_level_programme) 
    {
        // date_default_timezone_set('Africa/Johannesburg');
        $time_stamp = date("Y-m-d H:i:s");
        
        global $wpdb;
        $table = $wpdb->prefix.'ftmv_lms_main_programme_table';
        
        $data = array('name' => $new_top_level_programme, 'timecreated' => $time_stamp, 'created_user_id' => $user_id);
        
        $format = array('%s', '%s', '%d');
        
        $wpdb->insert($table,$data,$format);
        $programme_id = $wpdb->insert_id;
        
        $current_user_id = wp_get_current_user()->ID;

        $programme_success_details = array('user_id' => $current_user_id, 'message' => 'Programme Created Successfully');
        set_transient( 'programme_creation_form_message_transient', $programme_success_details, 60 );

        return $programme_id;
    }

    // As the name says this helper function creates a role and adds it to the role table

    function add_role_to_role_table($programme_id, $role_slug, $role_type, $role_display_name, $role_capability)    
    {
        global $wpdb;

        $table = $wpdb->prefix.'ftmv_lms_roles_table';
        
        $data = array('main_programme_id' => $programme_id, 'role_name' => $role_slug, 'role_type' => $role_type, 'role_display_name' => $role_display_name, 'role_capabilities' => $role_capability);
        
        $format = array('%s', '%s', '%s', '%s', '%s');
        
        $wpdb->insert($table,$data,$format);
        $programme_id = $wpdb->insert_id;

        $capabilities = array($role_capability => true);        
        wp_roles()->add_role( $role_slug, $role_display_name, $capabilities );
    }


    /* 
        The following functions handle the deletion of the programme from the database and roles table
    
    */

    function delete_role_from_wp($programme_id, $plugin_name_underscore) 
    {   
        global $wpdb;
        global $wp_roles;
         
        $role_table = $wpdb->prefix."{$plugin_name_underscore}_roles_table";

        $get_role_query = "SELECT * FROM {$role_table} WHERE main_programme_id = {$programme_id}";
        $get_programme_roles = $wpdb->get_results( $get_role_query, ARRAY_A );
        
        if (is_array($get_programme_roles) || is_object($get_programme_roles))
        {   
            foreach($get_programme_roles as $programme_role)
            {   
                if (wp_roles()->is_role( $programme_role['role_name'] ))
                {
                    $wp_roles->remove_role($programme_role['role_name']);
                }
                else 
                {
                    error_log('the programme role does not exist to be removed.');
                }         
            } 
        }
        else
        {
            error_log('something went wrong');
        }
    }

    function delete_role_from_database($programme_id, $plugin_name_underscore) 
    {
        global $wpdb;
         
        $role_table = $wpdb->prefix."{$plugin_name_underscore}_roles_table";
        $wpdb->delete( $role_table,  array( 'main_programme_id' => $programme_id ));
    }

    function delete_programme_courses_from_database($programme_id, $plugin_name_underscore) 
    {   
        
        global $wpdb;
         
        $course_table = $wpdb->prefix."{$plugin_name_underscore}_course_table";
        $wpdb->delete( $course_table,  array( 'main_programme_id' => $programme_id ));
    }

    function delete_programme_from_database($programme_id, $plugin_name_underscore) 
    {
        global $wpdb;         
        $programme_table = $wpdb->prefix."{$plugin_name_underscore}_main_programme_table";
        $wpdb->delete( $programme_table,  array( 'id' => $programme_id ));
    }    

    function handle_programme_deletion($programme_id, $plugin_name_underscore)
    {
        delete_role_from_wp($programme_id, $plugin_name_underscore);

        delete_role_from_database($programme_id, $plugin_name_underscore);
        
        delete_programme_courses_from_database($programme_id, $plugin_name_underscore);
 
        delete_programme_from_database($programme_id, $plugin_name_underscore);
    } 
    
    // Programme deletion functions end


    /* 
        The following functions handle the deletion of a course from the database
    */
    function delete_course_from_database($programme_id, $course_id, $plugin_name_underscore)
    {
        
        global $wpdb;         
        $course_table = $wpdb->prefix."{$plugin_name_underscore}_course_table";
        $wpdb->delete( $course_table,  array( 'id' => $course_id ));        
        $programme_table = $wpdb->prefix."{$plugin_name_underscore}_main_programme_table";
        $wpdb->query("UPDATE {$programme_table} SET course_count = course_count - 1 WHERE id = {$programme_id}");
        
    }

    function handle_course_deletion($programme_id, $course_id, $plugin_name_underscore)
    {
        // echo $programme_id;
        delete_course_from_database($programme_id, $course_id, $plugin_name_underscore);
    }

    // Course deletion end


    function create_programme_student_role($plugin_name, $programme_id, $new_top_level_programme) 
    {   
        
        $role_slug = 'programme-id-[' . $programme_id . ']-student';
        $role_capability = 'can-view-programme-id-[' . $programme_id . ']';
        $role_display_name = $new_top_level_programme . ' Student';

        $returned_role_object = add_role(
            $role_slug,
            __( $role_display_name  ),
            array(
                $role_capability  => true
            )
        );
        $role_type = 'student';        
        add_role_to_role_table($programme_id, $role_slug, $role_type, $role_display_name, $role_capability);

        // error_log( print_r($returned_role_object, true) );
    }

    function create_programme_facilitator_role($plugin_name, $programme_id, $new_top_level_programme) 
    {
        $role_slug = 'programme-id-[' . $programme_id . ']-facilitator';        
        $role_capability = 'can-facilitate-programme-id-[' . $programme_id . ']';
        $role_display_name = $new_top_level_programme . ' Facilitator';

        $returned_role_object = add_role(
            $role_slug,
            __( $role_display_name  ),
            array(                
                $role_capability  => true
            )
        );
        
        $role_type = 'facilitator';        
        add_role_to_role_table($programme_id, $role_slug, $role_type, $role_display_name, $role_capability);
        // error_log( print_r($returned_role_object, true) );
    } 

    