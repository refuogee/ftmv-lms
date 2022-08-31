<?php    
    
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
        
        return $programme_id;
    }

    function add_role_to_role_table($programme_id, $role_slug, $role_type, $role_display_name, $role_capability)    
    {
        global $wpdb;

        $table = $wpdb->prefix.'ftmv_lms_roles_table';
        
        $data = array('main_programme_id' => $programme_id, 'role_name' => $role_slug, 'role_type' => $role_type, 'role_display_name' => $role_display_name, 'role_capabilities' => $role_capability);
        
        $format = array('%s', '%s', '%s', '%s', '%s');
        
        $wpdb->insert($table,$data,$format);
        $programme_id = $wpdb->insert_id;
        
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

        // $wpdb->update( $programme_table,  array( 'id' => $programme_id ));
        // $programme_course_count_update_query = "UPDATE {$programme_table} SET course_count = course_count - 1 WHERE id = {$programme_id}";
        $wpdb->query("UPDATE {$programme_table} SET course_count = course_count - 1 WHERE id = {$programme_id}");
        //$wpdb->get_results( $programme_query, ARRAY_A );
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

    