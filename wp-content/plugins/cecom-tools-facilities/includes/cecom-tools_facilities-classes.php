<?php

/**
 * This function include all classes and functions that access the database.
 * In most BuddyPress components the database access classes are treated like a model,
 * where each table has a class that can be used to create an object populated with a row
 * from the corresponding database table.
 *
 * Doing this in order to easily save, update and delete records using the class, also
 * abstracting database access.
 * 
 */
class BP_Tool_Facility {

    var $id;
    var $uid; //User ID
    var $gid; //Group ID
    var $description;
    var $country_id;
    var $location;
    var $payment_id;
    var $operation_id;
    var $date;
    var $query;

    /**
     * bp_tools_facilities_tablename()
     *
     * This is the constructor, it is auto run when the class is instantiated.
     * It will either create a new empty object if no ID is set, or fill the object
     * with a row from the table if an ID is provided.
     */
    function __construct($args = array()) {
        // Set some defaults
        $defaults = array(
            'id' => 0,
            'uid' => 0, //User ID
            'gid' => 0, //Group ID
            'description' => "",
            'country_id' => Null,
            'location' => "",
            'payment_id' => Null,
            'operation_id' => Null,
            'date' => date('Y-m-d H:i:s'),
        );

        // Parse the defaults with the arguments passed
        $r = wp_parse_args($args, $defaults);
        extract($r);

        //An actual ID is given fetch data from DB
        if ($id) {
            $this->id = $id;
            $this->populate($this->id);
        } else {
            foreach ($r as $key => $value) {
                $this->{$key} = $value;
            }
        }
    }

    /**
     * populate()
     *
     * This method will populate the object with a row from the database, based on the
     * ID passed to the constructor.
     */
    function populate() {
        global $wpdb, $bp;

        $tool_facility = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$bp->tools_facilities->table_name} WHERE id = %d", $this->id));

        //If query returned a result assign the values 
        if ($tool_facility) {
            $this->id = $tool_facility->id;
            $this->uid = $tool_facility->uid; //User ID
            $this->gid = $tool_facility->gid; //Group ID
            $this->description = $tool_facility->description;
            $this->country_id = $tool_facility->country_id;
            $this->location = $tool_facility->location;
            $this->payment_id = $tool_facility->payment_id;
            $this->operation_id = $tool_facility->operation_id;
            $this->date = $tool_facility->date;
        }
    }

    /**
     * save()
     *
     * This method will save an object to the database. It will dynamically switch between
     * INSERT and UPDATE depending on whether or not the object already exists in the database.
     */
    function save() {
        global $wpdb, $bp;

        /*
         * In this save() method, you should add pre-save filters to all the values you are
         * saving to the database. This helps with two things -
         *
         * 1. Blanket filtering of values by plugins (for example if a plugin wanted to
         * force a specific value for all saves)
         *
         * 2. Security - attaching a wp_filter_kses() call to all filters, so you are not
         * saving potentially dangerous values to the database.
         *
         * It's very important that for number 2 above, you add a call like this for each
         * filter to 'cecom-tools_facilities-filters.php'
         */

        /* Filter all values before saving to DB */

        $this->id = apply_filters('bp_tools_facilities_data_before_save', $this->id);
        $this->uid = apply_filters('bp_tools_facilities_data_before_save', $this->uid);
        $this->gid = apply_filters('bp_tools_facilities_data_before_save', $this->gid);
        $this->description = apply_filters('bp_tools_facilities_data_before_save', $this->description);
        $this->country_id = apply_filters('bp_tools_facilities_data_before_save', $this->country_id);
        $this->location = apply_filters('bp_tools_facilities_data_before_save', $this->location);
        $this->payment_id = apply_filters('bp_tools_facilities_data_before_save', $this->payment_id);
        $this->operation_id = apply_filters('bp_tools_facilities_data_before_save', $this->operation_id);
        $this->date = apply_filters('bp_tools_facilities_data_before_save', $this->date);
        //Tool/Facility already exist, Update the current tool_facility
        if ($this->id) {

            //Dfeault fields
            $query_args_default = array(
                'description' => $this->description,
                'country_id' => $this->country_id,
                'location' => $this->location,
                'payment_id' => $this->payment_id,
                'operation_id' => $this->operation_id,
                'date' => $this->date);

            //Default fields format
            $query_args_format = array( '%s', '%s','%s', '%d','%d', '%s');

            $query_where = array('ID' => $this->id);

            //Update the the tool_facility in the DB
            $result = $wpdb->update($bp->tools_facilities->table_name, $query_args_default, $query_where, $query_args_format);

        } else {//Insert the new tool_facility in the database 
            //Dfeault fields
            $query_args_default = array(
                'uid' => $this->uid, //User ID
                'gid' => $this->gid, //Group ID
                'description' => $this->description,
                'country_id' => $this->country_id,
                'location' => $this->location,
                'payment_id' => $this->payment_id,
                'operation_id' => $this->operation_id,
                'date' => $this->date);

            //Default fields format
            $query_args_format = array( '%d','%d', '%s', '%s','%s', '%d','%d', '%s');

            //Insert the data to DB
            $result = $wpdb->insert($bp->tools_facilities->table_name, $query_args_default, $query_args_format);
        }
        return $result;
    }

    /**
     * Delete the current tool_facility.
     *
     * @return bool True on success, false on failure.
     */
    public function delete() {
        global $wpdb, $bp;

        wp_cache_delete('bp_tools_facilities_tool_facility_' . $this->id, 'bp');

        // Remove the tool_facility entry from the DB
        if (!$wpdb->query($wpdb->prepare("DELETE FROM {$bp->tools_facilities->table_name} WHERE id = %d", $this->id)))
            return false;

        return true;
    }

    /* Static Functions */

    /**
     * Static functions can be used to bulk delete items in a table, or do something that
     * doesn't necessarily warrant the instantiation of the class.
     *
     * Look at bp-core-classes.php for examples of mass delete.
     */
    function delete_all() {
        
    }

    function delete_by_user_id() {
        
    }

    function get_tool_facility_details($tool_facility_id = 0) {
        global $wpdb;

        if (!$tool_facility_id)
            $tool_facility_id = $this->id;

        $sql_query_select = "SELECT p.description pdesc,o.description odesc,c.name cname";
        $sql_query_from = " FROM ext_tool_facility tf, ext_tool_facility_payment p,ext_tool_facility_operation o,ext_organization_country c ";
        $sql_query_where = " WHERE tf.id=$tool_facility_id AND tf.payment_id=p.id AND tf.operation_id=o.id AND tf.country_id=c.id ";
        $sql_query = $sql_query_select . $sql_query_from . $sql_query_where;

        return $wpdb->get_row($sql_query, ARRAY_A);
    }

    /** Static Methods *************************************************** */

    /**
     * Get whether an tool_facility exists for a given slug.
     *
     * @param string $slug Slug to check.
     * @param string $table_name Optional. Name of the table to check
     *        against. Default: $bp->tools_facilities->table_name.
     * @return string|null ID of the tool_facility, if one is found, else null.
     */
    public static function tool_facility_exists($slug, $table_name = false) {
        global $wpdb, $bp;

        if (empty($table_name))
            $table_name = $bp->tools_facilities->table_name;

        if (empty($slug))
            return false;
        $tool_facility_id = filter_var($slug, FILTER_SANITIZE_NUMBER_INT);

        if ($slug != $bp->tools_facilities->tools_facilities_subdomain . ($wpdb->get_var($wpdb->prepare("SELECT id FROM {$table_name} WHERE id = %d", $tool_facility_id))))
            $tool_facility_id = 0;
        return $tool_facility_id;
    }

    /**
     * Get a total tools_facilities count for the CECommunity Platform.
     *
     * @return int Patent_Licenses count.
     */
    public static function tools_facilities_get_total_tools_facilities_count() {
        global $wpdb, $bp;
        $tool_facility_category = (bp_tools_facilities_current_category() != "none" ? " WHERE type_id=" . bp_tools_facilities_current_category() : "");
        $count = $wpdb->get_var("SELECT COUNT(id) FROM {$bp->tools_facilities->table_name}" . $tool_facility_category);

        return $count;
    }

    /**
     * Get the count of tools_facilities of which the specified user has.
     *
     * @param int $user_id Optional. Default: ID of the displayed user.
     * @return int Patent_Licenses count.
     */
    public static function tools_facilities_total_tools_facilities_count($user_id = 0) {

        global $bp, $wpdb;

        if (empty($user_id))
            $user_id = bp_displayed_user_id();

        if (!bp_loggedin_user_id()) {
            return null;
        } else {
            $tool_facility_category = (bp_tools_facilities_current_category() != "none" ? " AND type_id=" . bp_tools_facilities_current_category() : "");
            return $wpdb->get_var($wpdb->prepare("SELECT COUNT(DISTINCT id) FROM {$bp->tools_facilities->table_name} WHERE uid = %d" . $tool_facility_category, $user_id));
        }
    }

    //Fetch the available tool_facility types
    public static function getPatent_LicenseTypes() {
        global $wpdb;
        $tool_facility_types = $wpdb->get_results("SELECT * FROM ext_tool_facility_type order by description asc");
        if (!is_array($tool_facility_types))
            return nil;
        return $tool_facility_types;
    }

    //Fetch the available payment qualification types
    public static function getPaymentTypes() {
        global $wpdb;
        $tool_facility_payment = $wpdb->get_results("SELECT * FROM ext_tool_facility_payment order by description asc");
        if (!is_array($tool_facility_payment))
            return nil;
        return $tool_facility_payment;
    }

    //Fetch the available operation types
    public static function getOperationTypes() {
        global $wpdb;
        $tool_facility_partner_type = $wpdb->get_results("SELECT * FROM ext_tool_facility_operation");
        if (!is_array($tool_facility_partner_type))
            return nil;
        return $tool_facility_partner_type;
    }

    /* Queries staff */

    /**
     * Query for tools_facilities.
     *
     * @param array {
     *     Array of parameters. All items are optional.
     *     @type string $type Optional. Shorthand for certain orderby/
     *           order combinations. 'newest', 'active', 'popular',
     *           'alphabetical', 'random'. When present, will override
     *           orderby and order params. Default: null.
     *     @type string $orderby Optional. Property to sort by.
     *           'date_created', 'last_activity', 'total_member_count',
     *           'name', 'random'. Default: 'date_created'.
     *     @type string $order Optional. Sort order. 'ASC' or 'DESC'.
     *           Default: 'DESC'.
     *     @type int $per_page Optional. Number of items to return per page
     *           of results. Default: null (no limit).
     *     @type int $page Optional. Page offset of results to return.
     *           Default: null (no limit).
     *     @type int $user_id Optional. If provided, results will be limited
     *           to groups of which the specified user is a member. Default:
     *           null.
     *     @type string $search_terms Optional. If provided, only tools_facilities
     *           whose names or descriptions match the search terms will be
     *           returned. Default: false.
     *     @type string $search_extras Optional. If provided, serach fileds will be
     *           taken under consideration. Default: false.
     * }
     * @return array {
     *     @type array $tools_facilities Array of group objects returned by the
     *           paginated query.
     *     @type int $total Total count of all groups matching non-
     *           paginated query params.
     * }
     */
    public static function get($args = array()) {

        global $wpdb, $bp;

        $defaults = array(
            'type' => null,
            'orderby' => 'date_created',
            'order' => 'DESC',
            'per_page' => null,
            'page' => null,
            'user_id' => 0,
            'search_terms' => false,
            'search_extras' => false,
        );


        $r = wp_parse_args($args, $defaults);

        $sql = array();
        $total_sql = array();

        //TODO: Proper handle of selection clause
        $sql['select'] = "SELECT tool_facility.*";

        //Main table to fetch the information
        $sql['from'] = " FROM {$bp->tools_facilities->table_name} as tool_facility";


        //Query Where clause 
        $sql['where'] = "WHERE 1";

        if (!empty($r['user_id'])) {
            $sql['user'] = " AND uid = {$r['user_id']}";
        }


        //Calculate serach metaquery
        $sql['search'] = self::build_search_meta_query($r['search_extras']);

        //Actuall serach terms text-based
        if (!empty($r['search_terms'])) {
            $search_terms = esc_sql(like_escape($r['search_terms']));
            $sql['search'] .= " AND (tool_facility.description LIKE '%%{$search_terms}%%')";
        }


        /** Order/orderby ******************************************* */
        $order = $r['order'];
        $orderby = $r['orderby'];

        // If a 'type' parameter was passed, parse it and overwrite
        // 'order' and 'orderby' params passed to the function
        if (!empty($r['type'])) {
            $order_orderby = self::convert_type_to_order_orderby($r['type']);
            // If an invalid type is passed, $order_orderby will be
            // an array with empty values. In this case, we stick
            // with the default values of $order and $orderby
            if (!empty($order_orderby['order'])) {
                $order = $order_orderby['order'];
            }

            if (!empty($order_orderby['orderby'])) {
                $orderby = $order_orderby['orderby'];
            }
        }

        // Sanitize 'order'
        $order = bp_esc_sql_order($order);

        // Convert 'orderby' into the proper ORDER BY term
        $orderby = self::convert_orderby_to_order_by_term($orderby);

        $sql['orderby'] = "ORDER BY {$orderby} {$order}";
        /* End of Order Calculation */

        //Take into consideration pagination
        if (!empty($r['per_page']) && !empty($r['page'])) {
            $sql['pagination'] = $wpdb->prepare("LIMIT %d, %d", intval(( $r['page'] - 1 ) * $r['per_page']), intval($r['per_page']));
        }

        // Get paginated results
        $paged_tools_facilities_sql = apply_filters('bp_tools_facilities_get_paged_tools_facilities_sql', join(' ', (array) $sql), $sql, $r);
        $paged_tools_facilities = $wpdb->get_results($paged_tools_facilities_sql);
        //echo " Paged Query: " . $paged_tools_facilities_sql . "<br> Results count:" . $wpdb->num_rows;


        $total_sql['select'] = "SELECT COUNT(DISTINCT id) FROM {$bp->tools_facilities->table_name} as tool_facility";

        //Where clause for search box
        if (!empty($sql['search'])) {
            $total_sql['where'][] = substr($sql['search'], 4);
        }

        //True: All Patent_Licenses / False: My tools_facilities tab
        if (!empty($r['user_id'])) {
            $total_sql['where'][] = $wpdb->prepare(" uid = %d", $r['user_id']);
        }

        $t_sql = $total_sql['select'];

        if (!empty($total_sql['where'])) {
            $t_sql .= " WHERE " . join(' AND ', (array) $total_sql['where']);
        }


        // Get total tool_facility results
        $total_tools_facilities_sql = apply_filters('bp_tools_facilities_get_total_tools_facilities_sql', $t_sql, $total_sql, $r);
        $total_tools_facilities = $wpdb->get_var($total_tools_facilities_sql);
        //echo "<br>Count query: " . $total_tools_facilities_sql;

        $tool_facility_ids = array();
        foreach ((array) $paged_tools_facilities as $tool_facility) {
            $tool_facility_ids[] = $tool_facility->id;
        }

        unset($sql, $total_sql);

        return array('tools_facilities' => $paged_tools_facilities, 'total' => $total_tools_facilities);
    }

    //Build the meta query based on the arguments given in the tools_facilities search form 
    protected static function build_search_meta_query($search_extras) {

        $serach_extras_query = '';
        //Convert search_extras values to an array of arguments
        if (!empty($search_extras)) {
            $search_extras_args = array();
            $asArr = explode('|', $search_extras);

            foreach ($asArr as $val) {
                $tmp = explode(';', $val);
                $search_extras_args[$tmp[0]] = $tmp[1];
            }
            //print_r($search_extras_args);
            //If calculation is success continue
            if (!empty($search_extras_args)) {
                //Take into account graphical coverage
                $serach_extras_query.= ($search_extras_args['tool-facility-country'] != '' ? "AND country_id='{$search_extras_args['tool-facility-country']}' " : "");
            }
        }
        //echo "Meta Query: " . $serach_extras_query;
        return $serach_extras_query;
    }

    /**
     * Convert the 'type' parameter to 'order' and 'orderby'.
     *
     * @access protected
     *
     * @param string $type The 'type' shorthand param.
     * @return array {
     * 	@type string $order SQL-friendly order string.
     * 	@type string $orderby SQL-friendly orderby column name.
     * }
     */
    protected static function convert_type_to_order_orderby($type = '') {
        $order = $orderby = '';

        switch ($type) {
            case 'newest' :
                $order = 'DESC';
                $orderby = 'date';
                break;

            case 'oldest' :
                $order = 'ASC';
                $orderby = 'date';
                break;
        }

        return array('order' => $order, 'orderby' => $orderby);
    }

    /**
     * Convert the 'orderby' param into a proper SQL term/column.
     *
     * @access protected
     *
     * @param string $orderby Orderby term as passed to get().
     * @return string $order_by_term SQL-friendly orderby term.
     */
    protected static function convert_orderby_to_order_by_term($orderby) {
        $order_by_term = '';

        switch ($orderby) {
            case 'date' :
            default :
                $order_by_term = 'date';
                break;

            case 'name' :
                $order_by_term = 'g.name';
                break;
        }

        return $order_by_term;
    }
    
    
    
        public static function get_organization_tools_facilities($args = array()) {

        //print_r($args);
        global $wpdb, $bp;

        $limit = " limit 10";

        $sql = array();
        $total_sql = array();

        //TODO: Proper handle of selection clause
        $sql['select'] = "SELECT tool_facility.*";

        //Main table to fetch the information
        $sql['from'] = " FROM {$bp->tools_facilities->table_name} as tool_facility";


        //Query Where clause 
        $sql['where'] = "WHERE tool_facility.gid={$args['group_id']} ";


        $sql['orderby'] = "ORDER BY date DESC" . $limit;
        ;
        /* End of Order Calculation */


        // Get paginated results
        $paged_tools_facilities_sql = apply_filters('bp_tools_facilities_get_paged_tools_facilities_sql', join(' ', (array) $sql), $sql);
        //echo "Offer query paginates results: " . $paged_tools_facilities_sql;
        $paged_tools_facilities = $wpdb->get_results($paged_tools_facilities_sql);


        $total_tools_facilities_sql = "SELECT COUNT(DISTINCT id) FROM {$bp->tools_facilities->table_name}  as tool_facility WHERE gid={$args['group_id']} ";
        // Get total offer results

        $total_tools_facilities = $wpdb->get_var($total_tools_facilities_sql);
        //echo " <br>Offer count query: " . $total_tools_facilities;

        unset($sql, $total_sql);

        return array('tools_facilities' => $paged_tools_facilities, 'total' => $total_tools_facilities);
    }

}

?>