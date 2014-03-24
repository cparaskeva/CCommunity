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
class BP_Tool_Facility{

    var $id;
    var $uid; //User ID
    var $gid; //Group ID
    var $type_id; //Tool_Facilitytype ID
    var $description;
    var $country_id;
    var $exchange_id;
    var $date;
    var $sectors;
    var $subsectors;
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
            'type_id' => 0, //Tool_Facilitytype ID
            'description' => "",
            'country_id' => Null,
            'exchange_id' => Null,
            'date' => date('Y-m-d H:i:s'),
            'sectors' => '',
            'subsectors' => ''
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
            $this->type_id = $tool_facility->type_id; //Tool_Facilitytype ID
            $this->description = $tool_facility->description;
            $this->country_id = $tool_facility->country_id;
            $this->exchange_id = $tool_facility->exchange_id;
            $this->date = $tool_facility->date;
        }

        //Fetch tool_facility metadata from DB
        if ($tool_facility->id) {
            $this->sectors = $wpdb->get_results("SELECT s.id,s.color,s.description from ext_tool_facility_meta m,ext_organization_sector s where m.mkey='sector' and m.mvalue = s.id and pid=$tool_facility->id", ARRAY_A);
            $this->subsectors = $wpdb->get_results("SELECT s.id,s.description from ext_tool_facility_meta m,ext_organization_subsector s where m.mkey='subsector' and m.mvalue = s.id and pid=$tool_facility->id", ARRAY_A);
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
        $this->type_id = apply_filters('bp_tools_facilities_data_before_save', $this->type_id);
        $this->description = apply_filters('bp_tools_facilities_data_before_save', $this->description);
        $this->country_id = apply_filters('bp_tools_facilities_data_before_save', $this->country_id);
        $this->exchange_id = apply_filters('bp_tools_facilities_data_before_save', $this->exchange_id);
        $this->date = apply_filters('bp_tools_facilities_data_before_save', $this->date);
        //Tool_Facilityalready exist, Update the current tool_facility
        if ($this->id) {

            //Dfeault fields
            $query_args_default = array(
                'type_id' => $this->type_id,
                'description' => $this->description,
                'country_id' => $this->country_id,
                'exchange_id' => $this->exchange_id,
                'date' => $this->date);

            //Default fields format
            $query_args_format = array('%d', '%s', '%s', '%d', '%s');

            $query_where = array('ID' => $this->id);

            //Update the the tool_facility in the DB
            $result = $wpdb->update($bp->tools_facilities->table_name, $query_args_default, $query_where, $query_args_format);

            //Clear the old metadata
            $wpdb->get_results("DELETE  FROM `ext_tool_facility_meta` where pid= $this->id");

            //If insertion is success store the meta
            if ($this->id && !($this->sectors == "null" && $this->subsectors == "null")) {

                if ($this->sectors != "null")
                    $meta['sector'] = $this->sectors;

                if ($this->subsectors != "null")
                    $meta["subsector"] = $this->subsectors;

                BP_Patent_License::saveMetadata($this->id, $meta);
            }
        } else {//Insert the new tool_facility in the database 
            //Dfeault fields
            $query_args_default = array(
                'uid' => $this->uid, //User ID
                'gid' => $this->gid, //Group ID
                'type_id' => $this->type_id, //Tool_Facility type ID
                'description' => $this->description,
                'country_id' => $this->country_id,
                'exchange_id' => $this->exchange_id,
                'date' => $this->date);

            //Default fields format
            $query_args_format = array('%d', '%d', '%d', '%s', '%s', '%d', '%s');

            //Insert the data to DB
            $result = $wpdb->insert($bp->tools_facilities->table_name, $query_args_default, $query_args_format);

            //If insertion is success store the meta
            if ($wpdb->insert_id && !($this->sectors == "null" && $this->subsectors == "null")) {

                if ($this->sectors != "null")
                    $meta['sector'] = $this->sectors;

                if ($this->subsectors != "null")
                    $meta["subsector"] = $this->subsectors;

                BP_Patent_License::saveMetadata($wpdb->insert_id, $meta);
            }
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

        $sql_query_select = "SELECT t.description tdesc,e.description edesc,c.name cname";
        $sql_query_from = " FROM ext_tool_facility pl, ext_tool_facility_type t,ext_tool_facility_exchange e,ext_organization_country c ";
        $sql_query_where = " WHERE pl.id=$tool_facility_id AND pl.type_id=t.id AND pl.exchange_id=e.id AND pl.country_id=c.id ";
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

    //Fetch the available exchange types
    public static function getExchangeTypes() {
        global $wpdb;
        $tool_facility_exchange = $wpdb->get_results("SELECT * FROM ext_tool_facility_exchange order by description asc");
        if (!is_array($tool_facility_exchange))
            return nil;
        return $tool_facility_exchange;
    }

    //Fetch the available partner sought types
    public static function getPartnerTypes() {
        global $wpdb;
        $tool_facility_partner_type = $wpdb->get_results("SELECT * FROM ext_tool_facility_partner_type");
        if (!is_array($tool_facility_partner_type))
            return nil;
        return $tool_facility_partner_type;
    }

    //Save meta data to ext_tool_facility_meta table
    public static function saveMetadata($tool_facilityID, $metadata) {
        global $wpdb;
        //Check if a valid tool_facilityID is given
        if ($tool_facilityID) {
            $query = "INSERT INTO ext_tool_facility_meta (pid,mkey,mvalue) VALUES ";
            //$metadata ($key => Array) Two dimensions array
            foreach ($metadata as $mkey => $mvalue) {
                foreach ($mvalue as $key => $value) {
                    $query .= "($tool_facilityID,'$mkey','$value') ,";
                }
            }
            //Remove last ","
            $query = substr($query, 0, -1);

            //Execute Query
            $wpdb->get_results($query);
        }
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
        $sql['select'] = "SELECT tool_facility.*,type.description tdesc";

        //Main table to fetch the information
        $sql['from'] = " FROM {$bp->tools_facilities->table_name} as tool_facility,`ext_tool_facility_type` as type";


        //Query Where clause 
        $sql['where'] = "WHERE tool_facility.type_id = type.id ";

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
        echo " Paged Query: " . $paged_tools_facilities_sql . "<br> Results count:" . $wpdb->num_rows;


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
        echo "<br>Count query: " . $total_tools_facilities_sql;

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
            print_r($search_extras_args);
            //If calculation is success continue
            if (!empty($search_extras_args)) {
                //Take into account patent/license type
                $serach_extras_query = ($search_extras_args['patent-license-type'] != "none" ? " AND type_id =' {$search_extras_args['patent-license-type']}' " : "");
                //Take into account type of exchange
                $serach_extras_query.= ($search_extras_args['patent-license-exchange'] != 'none' ? "AND exchange_id='{$search_extras_args['patent-license-exchange']}' " : "");
                //Take into account graphical coverage
                $serach_extras_query.= ($search_extras_args['patent-license-countries'] != 'none' ? "AND country_id='{$search_extras_args['patent-license-countries']}' " : "");

                //Handle sectors and subsectors fields
                $search_extras_subquery = '';
                if (!empty($search_extras_args['patent-license-sectors'])) {
                    $sectors_query = "(mkey='sector' and mvalue in ({$search_extras_args['patent-license-sectors']})";
                    $sectors_query = (!empty($search_extras_args['patent-license-subsectors']) ? " (mkey='subsector' and mvalue in ({$search_extras_args['patent-license-subsectors']})" : $sectors_query);
                    $search_extras_subquery = " AND tool_facility.id in (select pid from ext_tool_facility_meta where {$sectors_query}))";
                }
                
                
                //Handle organisation fileds query
                $search_organization_query='';
                $search_organization_query.= (strlen($search_extras_args['organization-name'])>0  ? " AND name LIKE'%%{$search_extras_args['organization-name']}%%' " : "");
                $search_organization_query.= ($search_extras_args['organization-country']!= ""?" AND country_id='{$search_extras_args['organization-country']}'"  :"" );
                $search_organization_query.= ($search_extras_args['organization-type']!= "none"?" AND type_id='{$search_extras_args['organization-type']}'"  :"" );
                
                if (!empty($search_organization_query))
                    $search_organization_query= " AND tool_facility.gid in (SELECT gid from ext_organization WHERE ".substr($search_organization_query,4). ") ";

            }
        }
        echo "Meta Query: " . $serach_extras_query.$search_extras_subquery.$search_organization_query;
        return $serach_extras_query.$search_extras_subquery.$search_organization_query;
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

            case 'tool_facilitytype' :
                $order = 'DESC';
                $orderby = 'tool_facilitytype';
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

            case 'tool_facilitytype' :
                $order_by_term = 'type_id, date';
                break;

            case 'name' :
                $order_by_term = 'g.name';
                break;
        }

        return $order_by_term;
    }

}

?>