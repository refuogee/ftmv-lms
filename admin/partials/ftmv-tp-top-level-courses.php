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
                    'timecreated'       => 'Date Created',
                    'created_user'   => 'Who Created The Course'
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
                
                $query = "SELECT main_course_info.id, main_course_info.timecreated, main_course_info.name, concat(first_meta.meta_value,' ' , last_meta.meta_value) AS created_user FROM wp_ftmv_main_course_table AS main_course_info LEFT JOIN wp_usermeta AS first_meta ON main_course_info.ID = first_meta.user_id LEFT JOIN wp_usermeta AS last_meta ON main_course_info.ID = last_meta.user_id WHERE first_meta.meta_key = 'first_name' AND first_meta.user_id = 1 AND last_meta.meta_key = 'last_name' AND last_meta.user_id = 1";

                $result = $wpdb->get_results( $query, ARRAY_A );
                
                $data = $result;

                return $data;
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
                $edit_link = admin_url( 'post.php?action=edit&amp;post=' .  $item->name  );
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
	<form method="post" action="options.php">
        <div class="wrap-test">        
            <h2>Top Level Courses Page:</h2>
            <p>
                This is the top level course page. <br>
                Start by creating a top level course. <br>
                Once that has been done you will then be able to create facilitators and the dates for courses to run.
            </p>
            <div class="main-course-list-table">
                <?php
                    $table = new List_Table();
                    $table->prepare_items();
                    $table->display();            
                ?>
            </div>            
            <a href="<?php echo admin_url('admin.php?page=ftmv-tp-add-course'); ?>"> <button type="button" class="button button-primary">Add Course</button></a>
        </div>
	</form>
</div>