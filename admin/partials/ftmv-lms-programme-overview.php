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


// $arg = get_query_var('id');
// $arg = $_GET['id'];
$progamme_id = $_GET['id'];
// $url_components = parse_url($url);

global $wpdb;

// error_log(gettype($arg));

$query = "SELECT programme_info.id, programme_info.timecreated, programme_info.name, user_info.display_name AS 'created_user' FROM ".$wpdb->prefix."ftmv_lms_main_programme_table AS programme_info LEFT JOIN ".$wpdb->prefix."users AS user_info ON programme_info.created_user_id = user_info.ID WHERE programme_info.id =".$progamme_id."";

// error_log($query);

$result = $wpdb->get_results( $query, ARRAY_A );

// error_log( print_r($result, true) );


if( is_admin() && !class_exists( 'WP_List_Table' ) )
        require_once( ABSPATH . 'wp-admin\includes\list-table.php');
        
        class List_Table extends WP_List_Table
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
                    'startdate'         => 'Start Date',
                    'enddate'           => 'End Date',
                    'created_user'      => 'Course Created By',
                    'timecreated'       => 'Date Created',
                    'student_count'     => 'Students'
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
                $query = "SELECT course.id, course.timecreated, course.name, course.startdate, course.enddate, course.student_count, user_info.display_name AS 'created_user' FROM ".$wpdb->prefix."ftmv_lms_course_table AS course LEFT JOIN ".$wpdb->prefix."users AS user_info ON course.created_user_id = user_info.ID LEFT JOIN ".$wpdb->prefix."ftmv_lms_main_programme_table AS programme_info ON programme_info.id = course.main_programme_id WHERE course.main_programme_id = " . $progamme_id . "";
                
                $results = $wpdb->get_results( $query, ARRAY_A );
                
                foreach ($results as $key => $result) {
                    $start_date = strtotime($result['startdate']);                    
                    $end_date = strtotime($result['enddate']);                    
                    $results[$key]['startdate'] = date('d/M/Y', $start_date); 
                    $results[$key]['enddate'] = date('d/M/Y', $end_date); 
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
                /* error_log( print_r($item, true));
                error_log( $item['id'] ); */
                $edit_link = admin_url( 'admin.php?page=ftmv-lms-programme-overview&id=' .  $item['id']  );
                $view_link = get_permalink( $item['name'] ); 
                $output    = '';
        
                // Title.
                $output .= '<strong><a href="' . esc_url( $edit_link ) . '" class="row-title">' . esc_html(  $item['name']   ) . '</a></strong>';
        
                // Get actions.
                $actions = array(
                    'edit'   => '<a target="_blank" href="' . esc_url( $edit_link ) . '">' . esc_html__( 'Edit', 'my_plugin' ) . '</a>',
                    'delete'   => '<a target="_blank" href="' . esc_url( $view_link ) . '">' . esc_html__( 'Delete', 'my_plugin' ) . '</a>',
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
    <form class="ftmv-lms-programme-details-form">        
        
        
        <!-- ftmv-lms-form-layout-container -->

        <div class="ftmv-lms-form-layout-container programme-created-date">
            <div class="ftmv-lms-form-label-container">
                <label for="programme-created-date">Date Created:</label>
            </div>
            
            <div class="ftmv-lms-input-container">
                <input type="text" name="programme-created-date" id="programme-created-date" value="<?php echo $result[0]['timecreated']?>" disabled>
            </div>
        </div>

        <div class="ftmv-lms-form-layout-container programme-created-user">
            <div class="ftmv-lms-form-label-container">
                <label for="programme-created-user">Created by:</label>
            </div>
            <div class="ftmv-lms-input-container">
                <input type="text" name="programme-created-user" id="programme-created-user" value="<?php echo $result[0]['created_user']; ?>" disabled>
            </div>
        </div>


        <div class="ftmv-lms-form-layout-container programme-name">
            <div class="ftmv-lms-form-label-container">
                <label for="programme-name">Programme Name:</label>
            </div>
            <div class="ftmv-lms-input-container">
                <input type="text" name="programme-name" id="programme-name" placeholder="<?php echo $result[0]['name']?>"> <br>
            </div>
        </div>


        <div class="ftmv-lms-form-button-container">
            <button type="submit" class="button button-primary">Save Changes</button>            
            <button type="submit" class="button button-primary">Delete Programme</button>            
        </div>
    </form>
    <hr>
    <div class="programme-courses-table">
        <h3>Course List:</h3>    
        <p>            
            This is a list of all the courses associated with the program and the dates they are scheduled for. <br>
            If you wish to edit a course or add students to a course click on the course name or click edit. <br>
            To delete click delete. <br>
        </p>
        <?php
            $table = new List_Table();
            $table->prepare_items();
            $table->display();            
        ?>
    </div>
    <a href="<?php echo admin_url('admin.php?page=ftmv-lms-add-course&id=' . $result[0]['id']) . '' ?>"> <button type="button" class="button button-primary">Add New Course</button></a>

</div>