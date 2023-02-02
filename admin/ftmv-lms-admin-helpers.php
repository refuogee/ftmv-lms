<?php    

    function transientHandler($transient_id, $transient_details, $validity_period)
    {
        set_transient( $transient_id, $transient_details, $validity_period );
    }
    
    function create_wp_user($programme_id, $user_name, $user_surname, $user_type, $role_name, $user_email)
    {
        //  $password = wp_generate_password( 6, false );
        
        $password = ('testpass123');
           
        echo '<br>';
        
        // Create a new Wordpress User         
        $wp_user_id = wp_create_user( $user_email, $password, $user_email );
        
        // Error handling and transient message creation        
        if ( is_wp_error( $wp_user_id ) ) 
        {
            $current_user_id = wp_get_current_user()->ID;            
            $message_type = 'error';
            $user_creation_details = array('user_id' => $current_user_id, 'message_type' => $message_type, 'message' => $wp_user_id->get_error_message(), 'user_name' => $user_name, 'user_surname' => $user_surname, 'user_email' => $user_email);            
            transientHandler('user_creation_form_transient', $user_creation_details, 60);
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

            if ($user_type == "facilitator") 
            {   
                $new_wp_user->set_role( $role_name );     
            } 

            if ($user_type == "student") 
            {
                $new_wp_user->set_role( $role_name );     
            }  

            $message_type = 'success';
            $user_creation_details = array('user_id' => $current_user_id, 'message_type' => $message_type, 'message' => "{$user_type} Successfully Created", 'user_name' => $user_name, 'user_surname' => $user_surname, 'user_email' => $user_email, 'user_type' => $user_type);              
            transientHandler('user_creation_form_transient', $user_creation_details, 60);

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
        $programme_table = $wpdb->prefix.'ftmv_lms_main_programme_table';
        if ($user_type == "facilitator") 
        {
            $wpdb->query("UPDATE {$programme_table} SET facilitator_count = facilitator_count + 1 WHERE id = {$programme_id}");
        }
        
    }

    function manage_user_creation($created_user_id, $user_type, $course_id, $programme_id, $user_name, $user_surname, $user_email)    
    {
        
        global $wpdb;
        $roles_table = $wpdb->prefix.'ftmv_lms_roles_table';                
        $programme_table = $wpdb->prefix.'ftmv_lms_main_programme_table';        

        $role_query = "SELECT id, role_name FROM {$roles_table} WHERE main_programme_id = {$programme_id} AND role_type = '{$user_type}'";        
        $role_data = $wpdb->get_results( $role_query, ARRAY_A );        
        $role_name = $role_data[0]['role_name'];
        $role_id = $role_data[0]['id'];
        
        $wp_user_id = create_wp_user($programme_id, $user_name, $user_surname, $user_type, $role_name, $user_email);
        add_user_to_database($wp_user_id, $created_user_id, $programme_id, $course_id, $role_id, $user_type);     
         
    }

    function remove_user_from_lms_tables($uid)
    {
        global $wpdb;
        $lms_user_table = $wpdb->prefix.'ftmv_lms_user_table';               
        return $wpdb->delete( $lms_user_table,  array( 'id' => $uid ));
    }

    function remove_user_from_wp($uid)
    {
        global $wpdb;
        $lms_user_table = $wpdb->prefix.'ftmv_lms_user_table';        
        $lms_user_query = "SELECT wp_user_id FROM {$lms_user_table} WHERE id = {$uid}";        
        $lms_user_data = $wpdb->get_results( $lms_user_query, ARRAY_A );        

        $wp_user_id_to_be_deleted = $lms_user_data[0]['wp_user_id'];

        

        return wp_delete_user( $wp_user_id_to_be_deleted );
    }

    function manage_user_deletion($uid, $user_type)
    {   

        $current_user_id = wp_get_current_user()->ID;

        if (remove_user_from_wp($uid) == 1 && remove_user_from_lms_tables($uid) == 1)
        {
            $message_type = 'success';
            $user_deletion_details = array('user_id' => $current_user_id, 'message_type' => $message_type, 'message' => "{$user_type} Successfully Deleted");
            set_transient( 'user_deletion_transient', $user_deletion_details, 60 );
        }
        else
        {
            $message_type = 'fail';
            $user_deletion_details = array('user_id' => $current_user_id, 'message_type' => $message_type, 'message' => 'Error! Unable to Delete User');
            set_transient( 'user_deletion_transient', $user_deletion_details, 60 );
        }        
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

    function add_role_to_role_table($role, $programme_id, $role_type, $display_name)    
    {
        global $wpdb;

        $table = $wpdb->prefix.'ftmv_lms_roles_table';
        
        $data = array('role_name' => $role, 'main_programme_id' => $programme_id, 'role_type' => $role_type, 'role_display_name' => $display_name);
        
        $format = array('%s', '%d', '%s', '%s');
        
        $wpdb->insert($table, $data, $format);
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
            error_log('removal of roles failed');
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
        $message_type = 'success';
        $current_user_id = wp_get_current_user()->ID;
        $course_deleted_details = array('user_id' => $current_user_id, 'message_type' => $message_type, 'message' => "Course Successfully Deleted", 'user_name' => $user_name);              
        // error_log(print_r($course_deleted_details, true));
        transientHandler('course_deleted_transient', $course_deleted_details, 60);
    }

    // Course deletion end

    function manage_role_creation($programme_id, $new_top_level_programme)
    {
        create_programme_student_role($programme_id, $new_top_level_programme);               
        create_programme_facilitator_role($programme_id, $new_top_level_programme);               
    }

    function create_programme_student_role($programme_id, $new_top_level_programme) 
    {   
        $display_name = "{$new_top_level_programme} Student";
        $program_name_sanitized = sanitize_title( $new_top_level_programme );
        $role = "{$program_name_sanitized}-student";
        $capabilities = array("view-{$program_name_sanitized}" => true);
        add_role( $role, $display_name, $capabilities );
        $role_type = 'student';
        add_role_to_role_table($role, $programme_id, $role_type, $display_name);

        // error_log( print_r($returned_role_object, true) );
    }

    function create_programme_facilitator_role($programme_id, $new_top_level_programme) 
    {

        $editor_role_set = get_role( 'editor' )->capabilities;
        $program_name_sanitized = sanitize_title( $new_top_level_programme );
        $role = "{$program_name_sanitized}-facilitator";
        $display_name = "{$new_top_level_programme} Facilitator";

        add_role( $role, $display_name, $editor_role_set );

        $capability_name = 'manage-ftmv-lms';
        $role_object = get_role( $role );                
        $role_object->add_cap( $capability_name );     
            
        $role_type = 'facilitator';
        add_role_to_role_table($role, $programme_id, $role_type, $display_name);
        // error_log( print_r($returned_role_object, true) );
    } 

    