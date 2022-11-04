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


$progamme_id = $_GET['id'];
$ftmv_edit_programme_nonce = wp_create_nonce('ftmv_edit_programme_nonce');
$ftmv_delete_programme_nonce = wp_create_nonce('ftmv_delete_programme_nonce');

global $wpdb;

// error_log(gettype($arg));

$query = "SELECT programme_info.id AS id, programme_info.timecreated, programme_info.name, user_info.display_name AS 'created_user' FROM ".$wpdb->prefix."ftmv_lms_main_programme_table AS programme_info LEFT JOIN ".$wpdb->prefix."users AS user_info ON programme_info.created_user_id = user_info.ID WHERE programme_info.id =".$progamme_id."";

// error_log($query);

$result = $wpdb->get_results( $query, ARRAY_A );

$user_type = 'facilitator';

// error_log( print_r($result, true) );


if( is_admin() && !class_exists( 'WP_List_Table' ) )
        require_once( ABSPATH . 'wp-admin\includes\list-table.php');
        
        class Facilitator_List_Table extends WP_List_Table        
        {
            /**
             * Prepare the items for the table to process
             *
             * @return Void
             */
            public function prepare_items()
            {
                $columns = $this->get_columns();
                $hidden = $this->get_hidden_columns();
                $sortable = $this->get_sortable_columns();
        
                $data = $this->table_data();
                usort( $data, array( &$this, 'sort_data' ) );
        
                $perPage = 10;
                $currentPage = $this->get_pagenum();
                $totalItems = count($data);
        
                $this->set_pagination_args( array(
                    'total_items' => $totalItems,
                    'per_page'    => $perPage
                ) );
        
                $data = array_slice($data,(($currentPage-1)*$perPage),$perPage);
        
                $this->_column_headers = array($columns, $hidden, $sortable);
                $this->items = $data;
            }
        
            /**
             * Override the parent columns method. Defines the columns to use in your listing table
             *
             * @return Array
             */
            public function get_columns()
            {
                $columns = array(
                    'id'                => 'ID',            
                    'user_name'         => 'Facilitator',            
                    'created_user_name' => 'Created By',
                    'timecreated'       => 'Date Created'
                );
        
                return $columns;
            }

            /**
             * Define which columns are hidden
             *
             * @return Array
             */
            public function get_hidden_columns()
            {
                return array();
            }
        
            /**
             * Define the sortable columns
             *
             * @return Array
             */
            public function get_sortable_columns()
            {
                return array('title' => array('title', false));
            }
        
            /**
             * Get the table data
             *
             * @return Array
             */
            private function table_data()
            {
                global $wpdb;
                
                // $query = "SELECT main_course_info.id, main_course_info.timecreated, main_course_info.name, concat(first_meta.meta_value,' ' , last_meta.meta_value) AS created_user FROM ".$wpdb->prefix."ftmv_lms_main_course_table AS main_course_info LEFT JOIN wp_usermeta AS first_meta ON main_course_info.ID = first_meta.user_id LEFT JOIN wp_usermeta AS last_meta ON main_course_info.ID = last_meta.user_id `WHERE` first_meta.meta_key = 'first_name' AND first_meta.user_id = 1 AND last_meta.meta_key = 'last_name' AND last_meta.user_id = 1";

                // $query = "SELECT course.id, course.timecreated, course.name, user_info.display_name AS 'created_user' FROM ".$wpdb->prefix."ftmv_lms_main_programme_table AS programme_info LEFT JOIN ".$wpdb->prefix."users AS user_info ON programme_info.created_user_id = user_info.ID LEFT JOIN ".$wpdb->prefix."ftmv_lms_main_programme_table AS programme_info ON programme_info.created_user_id = user_info.ID" ;
                $progamme_id = $_GET['id'];
                // $query = "SELECT course.id, course.timecreated, course.name, course.startdate, course.enddate, course.student_count, user_info.display_name AS 'created_user' FROM ".$wpdb->prefix."ftmv_lms_course_table AS course LEFT JOIN ".$wpdb->prefix."users AS user_info ON course.created_user_id = user_info.ID LEFT JOIN ".$wpdb->prefix."ftmv_lms_main_programme_table AS programme_info ON programme_info.id = course.main_programme_id WHERE course.main_programme_id = " . $progamme_id . "";
                
                $facilitator_query = "SELECT lms_user.id, lms_user.timecreated, wp_user.user_email AS user_email, role.role_display_name, concat(wp_user_name_created.meta_value,' ' , wp_user_surname_created.meta_value) AS created_user_name, concat(wp_user_name.meta_value,' ' , wp_user_surname.meta_value) AS user_name FROM wp_ftmv_lms_user_table AS lms_user LEFT JOIN wp_users AS wp_user ON lms_user.wp_user_id = wp_user.ID LEFT JOIN wp_usermeta AS wp_user_name_created ON lms_user.created_user_id = wp_user_name_created.user_id LEFT JOIN wp_usermeta AS wp_user_surname_created ON lms_user.created_user_id = wp_user_surname_created.user_id LEFT JOIN wp_usermeta AS wp_user_name ON lms_user.wp_user_id = wp_user_name.user_id LEFT JOIN wp_usermeta AS wp_user_surname ON lms_user.wp_user_id = wp_user_surname.user_id LEFT JOIN wp_ftmv_lms_roles_table AS role ON role.id = lms_user.assigned_role_id WHERE wp_user_name.meta_key = 'first_name' AND wp_user_surname.meta_key = 'last_name' AND wp_user_name.user_id = lms_user.wp_user_id AND wp_user_name_created.meta_key = 'first_name' AND wp_user_surname_created.meta_key = 'last_name' AND wp_user_name_created.user_id = lms_user.created_user_id AND lms_user.main_programme_id = {$progamme_id} AND role.role_type = 'facilitator';";
                
                $results = $wpdb->get_results( $facilitator_query, ARRAY_A );

                foreach ($results as $key => $result) {                 
                    $date_created = strtotime($result['timecreated']);                    
                    $results[$key]['timecreated'] = date('j M Y', $date_created); 
                }

                return $results;
             
            }
        
            /**
             * Define what data to show on each column of the table
             *
             * @param  Array $item        Data
             * @param  String $column_name - Current column name
             *
             * @return Mixed
             */

            
            public function column_name( $item)
            {
                $progamme_id = $_GET['id'];
                /* error_log( print_r($item, true));
                error_log( $item['id'] ); */
                $edit_link = admin_url( 'admin.php?page=ftmv-lms-course-overview&course-id=' .  $item['id'] . '&programme-id=' . $progamme_id);
                $view_link = get_permalink( $item['name'] ); 
                $output    = '';
        
                // Title.
                $output .= '<strong><a href="' . esc_url( $edit_link ) . '" class="row-title">' . esc_html(  $item['name']   ) . '</a></strong>';
        
                // Get actions.
                $actions = array(
                    'edit'   => '<a target="_blank" href="' . esc_url( $edit_link ) . '">' . esc_html__( 'Edit', 'my_plugin' ) . '</a>'
                    // This is for the links that appear below the content - commented out because I don't want users to delete from this screen.
                    /* 'delete'   => '<a href="' . esc_url( $view_link ) . '">' . esc_html__( 'Delete', 'my_plugin' ) . '</a>', */
                    
                );
        
                $row_actions = array();
        
                foreach ( $actions as $action => $link ) {
                    $row_actions[] = '<span class="' . esc_attr( $action ) . '">' . $link . '</span>';
                }
        
                $output .= '<div class="row-actions">' . implode( ' | ', $row_actions ) . '</div>';
        
                return $output;
            }
            
            public function column_default( $item, $column_name )
            {
                switch( $column_name ) {
                    case 'id':                     
                    case 'user_name':                     
                    case 'created_user_name':                    
                    case 'timecreated':                    
                        return $item[ $column_name ];
                    default:
                        return print_r( $item, true ) ;
                }
            }

            //<div class="row-actions"><span class="edit"><a href="http://192.168.56.1/wc_course/wp-admin/post.php?post=2&amp;action=edit" aria-label="Edit “Sample Page”">Edit</a> | </span><span class="inline hide-if-no-js"><button type="button" class="button-link editinline" aria-label="Quick edit “Sample Page” inline" aria-expanded="false">Quick&nbsp;Edit</button> | </span><span class="trash"><a href="http://192.168.56.1/wc_course/wp-admin/post.php?post=2&amp;action=trash&amp;_wpnonce=f5b5c2885f" class="submitdelete" aria-label="Move “Sample Page” to the Bin">Trash</a> | </span><span class="view"><a href="http://192.168.56.1/wc_course/sample-page/" rel="bookmark" aria-label="View “Sample Page”">View</a></span></div>

            /**
             * Allows you to sort the data by the variables set in the $_GET
             *
             * @return Mixed
             */
            private function sort_data( $a, $b )
            {
                // Set defaults
                $orderby = 'timecreated';
                $order = 'asc';
        
                // If orderby is set, use this as the sort column
                if(!empty($_GET['orderby']))
                {
                    $orderby = $_GET['orderby'];
                }
        
                // If order is set use this as the order
                if(!empty($_GET['order']))
                {
                    $order = $_GET['order'];
                }
        
        
                $result = strcmp( $a[$orderby], $b[$orderby] );
        
                if($order === 'asc')
                {
                    return $result;
                }
        
                return -$result;
            }
        }


        class Course_List_Table extends WP_List_Table        
        {
            /**
             * Prepare the items for the table to process
             *
             * @return Void
             */
            public function prepare_items()
            {
                $columns = $this->get_columns();
                $hidden = $this->get_hidden_columns();
                $sortable = $this->get_sortable_columns();
        
                $data = $this->table_data();
                usort( $data, array( &$this, 'sort_data' ) );
        
                $perPage = 10;
                $currentPage = $this->get_pagenum();
                $totalItems = count($data);
        
                $this->set_pagination_args( array(
                    'total_items' => $totalItems,
                    'per_page'    => $perPage
                ) );
        
                $data = array_slice($data,(($currentPage-1)*$perPage),$perPage);
        
                $this->_column_headers = array($columns, $hidden, $sortable);
                $this->items = $data;
            }
        
            /**
             * Override the parent columns method. Defines the columns to use in your listing table
             *
             * @return Array
             */
            public function get_columns()
            {
                $columns = array(
                    'id'                => 'ID',
                    'name'              => 'Course Name',                    
                    'created_user'      => 'Created By',
                    'timecreated'       => 'Date Created',                    
                    'startdate'         => 'Start Date',                    
                    'enddate'           => 'End ',                    
                    'student_count'     => 'Number Of Students'                    
                );

                return $columns;
            }

            /**
             * Define which columns are hidden
             *
             * @return Array
             */
            public function get_hidden_columns()
            {
                return array();
            }
        
            /**
             * Define the sortable columns
             *
             * @return Array
             */
            public function get_sortable_columns()
            {
                return array('title' => array('title', false));
            }
        
            /**
             * Get the table data
             *
             * @return Array
             */
            private function table_data()
            {
                global $wpdb;
                
                // $query = "SELECT main_course_info.id, main_course_info.timecreated, main_course_info.name, concat(first_meta.meta_value,' ' , last_meta.meta_value) AS created_user FROM ".$wpdb->prefix."ftmv_lms_main_course_table AS main_course_info LEFT JOIN wp_usermeta AS first_meta ON main_course_info.ID = first_meta.user_id LEFT JOIN wp_usermeta AS last_meta ON main_course_info.ID = last_meta.user_id `WHERE` first_meta.meta_key = 'first_name' AND first_meta.user_id = 1 AND last_meta.meta_key = 'last_name' AND last_meta.user_id = 1";

                // $query = "SELECT course.id, course.timecreated, course.name, user_info.display_name AS 'created_user' FROM ".$wpdb->prefix."ftmv_lms_main_programme_table AS programme_info LEFT JOIN ".$wpdb->prefix."users AS user_info ON programme_info.created_user_id = user_info.ID LEFT JOIN ".$wpdb->prefix."ftmv_lms_main_programme_table AS programme_info ON programme_info.created_user_id = user_info.ID" ;
                $progamme_id = $_GET['id'];
                $course_query = "SELECT course.id, course.timecreated, course.name, course.startdate, course.enddate, course.student_count, user_info.display_name AS 'created_user' FROM ".$wpdb->prefix."ftmv_lms_course_table AS course LEFT JOIN ".$wpdb->prefix."users AS user_info ON course.created_user_id = user_info.ID LEFT JOIN ".$wpdb->prefix."ftmv_lms_main_programme_table AS programme_info ON programme_info.id = course.main_programme_id WHERE course.main_programme_id = " . $progamme_id . "";
                
               $results = $wpdb->get_results( $course_query, ARRAY_A );
                
                foreach ($results as $key => $result) {
                    $start_date = strtotime($result['startdate']);                    
                    $end_date = strtotime($result['enddate']);                    
                    $date_created = strtotime($result['timecreated']);                    
                    $results[$key]['startdate'] = date('j M Y', $start_date); 
                    $results[$key]['enddate'] = date('j M Y', $end_date); 
                    $results[$key]['timecreated'] = date('j M Y', $date_created); 
                }
                return $results; 
            }
        
            /**
             * Define what data to show on each column of the table
             *
             * @param  Array $item        Data
             * @param  String $column_name - Current column name
             *
             * @return Mixed
             */

            
            public function column_name( $item)
            {
                $progamme_id = $_GET['id'];
                /* error_log( print_r($item, true));
                error_log( $item['id'] ); */
                $edit_link = admin_url( 'admin.php?page=ftmv-lms-course-overview&course-id=' .  $item['id'] . '&programme-id=' . $progamme_id);
                $view_link = get_permalink( $item['name'] ); 
                $output    = '';
        
                // Title.
                $output .= '<strong><a href="' . esc_url( $edit_link ) . '" class="row-title">' . esc_html(  $item['name']   ) . '</a></strong>';
        
                // Get actions.
                $actions = array(
                    'edit'   => '<a target="_blank" href="' . esc_url( $edit_link ) . '">' . esc_html__( 'Edit', 'my_plugin' ) . '</a>'
                    // This is for the links that appear below the content - commented out because I don't want users to delete from this screen.
                    /* 'delete'   => '<a href="' . esc_url( $view_link ) . '">' . esc_html__( 'Delete', 'my_plugin' ) . '</a>', */
                    
                );
        
                $row_actions = array();
        
                foreach ( $actions as $action => $link ) {
                    $row_actions[] = '<span class="' . esc_attr( $action ) . '">' . $link . '</span>';
                }
        
                $output .= '<div class="row-actions">' . implode( ' | ', $row_actions ) . '</div>';
        
                return $output;
            }
             
            public function column_default( $item, $column_name )
            {
                switch( $column_name ) {
                    case 'id': 
                    case 'timecreated':
                    case 'name': 
                    case 'created_user':
                    case 'main_programme_id':
                    case 'startdate':
                    case 'enddate':
                    case 'student_count':
                        return $item[ $column_name ];
                    default:
                        return print_r( $item, true ) ;
                }
            }

            //<div class="row-actions"><span class="edit"><a href="http://192.168.56.1/wc_course/wp-admin/post.php?post=2&amp;action=edit" aria-label="Edit “Sample Page”">Edit</a> | </span><span class="inline hide-if-no-js"><button type="button" class="button-link editinline" aria-label="Quick edit “Sample Page” inline" aria-expanded="false">Quick&nbsp;Edit</button> | </span><span class="trash"><a href="http://192.168.56.1/wc_course/wp-admin/post.php?post=2&amp;action=trash&amp;_wpnonce=f5b5c2885f" class="submitdelete" aria-label="Move “Sample Page” to the Bin">Trash</a> | </span><span class="view"><a href="http://192.168.56.1/wc_course/sample-page/" rel="bookmark" aria-label="View “Sample Page”">View</a></span></div>

            /**
             * Allows you to sort the data by the variables set in the $_GET
             *
             * @return Mixed
             */
            private function sort_data( $a, $b )
            {
                // Set defaults
                $orderby = 'timecreated';
                $order = 'asc';
        
                // If orderby is set, use this as the sort column
                if(!empty($_GET['orderby']))
                {
                    $orderby = $_GET['orderby'];
                }
        
                // If order is set use this as the order
                if(!empty($_GET['order']))
                {
                    $order = $_GET['order'];
                }
        
        
                $result = strcmp( $a[$orderby], $b[$orderby] );
        
                if($order === 'asc')
                {
                    return $result;
                }
        
                return -$result;
            }
        }

?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div id="wrap">
    <h2>Programme Details:</h2>    
    <p>
        On this page you can see the details for a specific programme as well as the courses that fall under this programme. <br> 
        You can add new courses that will then be associated with the programme.
    </p>
    <hr>
    <h3>Programme Details:</h3>    
        <p>            
            You can edit the programme name here or delete the programme entirely.<br>
    </p>   
    
    <form action="<?php echo esc_url( admin_url( 'admin-post.php?programme-id='. $result[0]['id'] .'' ) ); ?>" method="post" id="ftmv_edit_programme" class="ftmv-lms-details-form">        
        <input type="hidden" name="action" value="ftmv_edit_programme">  
		<input type="hidden" name="ftmv_edit_programme_nonce" value="<?php echo $ftmv_edit_programme_nonce ?>" />			    			  
        
        <!-- ftmv-lms-form-layout-container -->

        <div class="ftmv-lms-form-layout-container programme-created-date">
            <div class="ftmv-lms-form-label-container">
                <label for="programme-created-date">Date Created:</label>
            </div>
            
            <div class="ftmv-lms-input-container">
                <input type="text" name="programme-created-date" id="programme-created-date" value="<?php echo esc_attr($result[0]['timecreated'])?>" disabled>
            </div>
        </div>

        <div class="ftmv-lms-form-layout-container programme-created-user">
            <div class="ftmv-lms-form-label-container">
                <label for="programme-created-user">Created by:</label>
            </div>
            <div class="ftmv-lms-input-container">
                <input type="text" name="programme-created-user" id="programme-created-user" value="<?php echo esc_attr($result[0]['created_user']) ?>" disabled>
            </div>
        </div>


        <div class="ftmv-lms-form-layout-container programme-name">
            <div class="ftmv-lms-form-label-container">
                <label for="programme-name">Programme Name:</label>
            </div>
            <div class="ftmv-lms-input-container">
                <input type="text" name="programme-name" id="programme-name" placeholder="<?php echo esc_attr($result[0]['name']) ?>" required> <br>
            </div>
        </div>

        <div class="ftmv-lms-form-button-container">            
            <div class="ftmv-lms-form-save-button-container">
                <button name="action-type" value="edit" type="submit" class="button button-primary">Save Changes</button>            
            </div>
        </div>  

    </form>

    <form action="<?php echo esc_url( admin_url( 'admin-post.php?programme-id='. $result[0]['id'] .'' ) ); ?>" method="post" id="ftmv_delete_programme" class="ftmv-lms-delete-programme-form">        
        <input type="hidden" name="action" value="ftmv_delete_programme">  
		<input type="hidden" name="ftmv_delete_programme_nonce" value="<?php echo $ftmv_delete_programme_nonce ?>" />			    			  
        
        <!-- ftmv-lms-form-layout-container -->
        <div class="ftmv-lms-form-delete-button-container">
            <button name="action-type" value="delete" type="submit" class="button button-primary delete-btn">Delete Programme</button>                        
        </div>
    </form>
    
    <hr>
    
    <div class="ftmv-lms-back-button-container">            
        <a href="<?php echo esc_url(admin_url('admin.php?page=ftmv-lms-programmes')) ?>"> <button type="button" class="button button-primary">Back to Programmes</button></a>
    </div>
    
    <hr>

    <div class="programme-facilitators-table">
        <?php
            $transient_message = get_transient( 'user_creation_form_transient' );              
            if( ! empty( $transient_message ) ) 
            {   
                if ($transient_message['user_id'] == wp_get_current_user()->ID)
                {                        
                    echo ("<div class='notice notice-success is-dismissible'><p>{$transient_message['message']}</p></div>");
                } 
                
            }
        ?>
        <h3>Facilitators List:</h3>    
        <p>            
            This is a list of all the facilitators assigned to manage this programme.<br>            
        </p>
        <?php
            $facilitator_table = new Facilitator_List_Table();
            $facilitator_table->prepare_items();
            $facilitator_table->display();            
        ?>
        

        <a href="<?php echo esc_url(admin_url('admin.php?page=ftmv-lms-add-user&programme-id='. esc_attr($progamme_id) . '&user-type='. esc_attr($user_type))) ?>"> <button type="button" class="button button-primary">Add New Facilitator</button></a>

    </div>
    
    

    <hr>

    <div class="programme-courses-table">
        <?php
            $transient_message = get_transient( 'course_creation_form_message_transient' );              
            if( ! empty( $transient_message ) ) 
            {   
                if ($transient_message['user_id'] == wp_get_current_user()->ID)
                {                        
                    echo ("<div class='notice notice-success is-dismissible'><p>{$transient_message['message']}</p></div>");
                } 
                
            }
        ?>
        <h3>Course List:</h3>    
        <p>            
            This is a list of all the courses associated with the program and the dates they are scheduled for. <br>            
        </p>
        <?php
            $course_table = new Course_List_Table();
            $course_table->prepare_items();
            $course_table->display();            
        ?>

        <a href="<?php echo esc_url(admin_url('admin.php?page=ftmv-lms-add-course&id=' . $result[0]['id'])) . '' ?>"> <button type="button" class="button button-primary">Add New Course</button></a>
    
    </div>
    

</div>