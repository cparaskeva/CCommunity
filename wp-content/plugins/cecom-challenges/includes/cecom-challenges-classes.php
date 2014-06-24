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
class BP_Challenge {

    var $id;
    var $uid; //User ID
    var $gid; //Group ID
    var $title;
    var $description;
    var $deadline;
    var $reward;
    var $sectors;
    var $right_id;
    var $date;
    var $query;

    /**
     * bp_challenges_tablename()
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
            'title' => "",
            'description' => "",
            'deadline' => "",
            'reawrd' => "",
            'right_id' => Null,
            'date' => date('Y-m-d H:i:s'),
            'sectors' => '',
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

        $challenge = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$bp->challenges->table_name} WHERE id = %d", $this->id));

        //If query returned a result assign the values 
        if ($challenge) {
            $this->id = $challenge->id;
            $this->uid = $challenge->uid; //User ID
            $this->gid = $challenge->gid; //Group ID
            $this->title = $challenge->title; //Patent_License type ID
            $this->description = $challenge->description;
            $this->deadline = $challenge->deadline;
            $this->reward = $challenge->reward;
            $this->right_id = $challenge->right_id;
            $this->date = $challenge->date;
        }

        //Fetch challenge metadata from DB
        if ($challenge->id) {
            $this->sectors = $wpdb->get_results("SELECT s.id,s.color,s.description from ext_challenge_meta m,ext_organization_sector s where m.mkey='sector' and m.mvalue = s.id and cid=$challenge->id", ARRAY_A);
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
         * filter to 'cecom-challenges-filters.php'
         */

        /* Filter all values before saving to DB */

        $this->id = apply_filters('bp_challenges_data_before_save', $this->id);
        $this->uid = apply_filters('bp_challenges_data_before_save', $this->uid);
        $this->gid = apply_filters('bp_challenges_data_before_save', $this->gid);
        $this->title = apply_filters('bp_challenges_data_before_save', $this->title);
        $this->description = apply_filters('bp_challenges_data_before_save', $this->description);
        $this->deadline = apply_filters('bp_challenges_data_before_save', $this->deadline);
        $this->reward = apply_filters('bp_challenges_data_before_save', $this->reward);
        $this->right_id = apply_filters('bp_challenges_data_before_save', $this->right_id);
        $this->date = apply_filters('bp_challenges_data_before_save', $this->date);


        //Challenge already exist, Update the current challenge
        if ($this->id) {

            //Dfeault fields
            $query_args_default = array(
                'title' => $this->title,
                'description' => $this->description,
                'deadline' => $this->deadline,
                'reward' => $this->reward,
                'right_id' => $this->right_id,
                'date' => $this->date);

            //Default fields format
            $query_args_format = array('%s', '%s', '%s', '%d', '%d', '%s');

            $query_where = array('ID' => $this->id);

            //Update the the challenge in the DB
            $result = $wpdb->update($bp->challenges->table_name, $query_args_default, $query_where, $query_args_format);

            //Clear the old metadata
            $wpdb->get_results("DELETE  FROM `ext_challenge_meta` where cid= $this->id");

            //If insertion is success store the meta
            if ($this->id && !($this->sectors == "null")) {

                if ($this->sectors != "null")
                    $meta['sector'] = $this->sectors;


                BP_Challenge::saveMetadata($this->id, $meta);
            }
        } else {//Insert the new challenge in the database 
            //Dfeault fields
            $query_args_default = array(
                'uid' => $this->uid, //User ID
                'gid' => $this->gid, //Group ID
                'title' => $this->title,
                'description' => $this->description,
                'deadline' => $this->deadline,
                'reward' => $this->reward,
                'right_id' => $this->right_id,
                'date' => $this->date);

            //Default fields format
            $query_args_format = array('%d', '%d', '%s', '%s', '%s', '%d', '%d', '%s');

            //Insert the data to DB
            $result = $wpdb->insert($bp->challenges->table_name, $query_args_default, $query_args_format);

            //If insertion is success store the meta
            if ($wpdb->insert_id && !($this->sectors == "null")) {

                if ($this->sectors != "null")
                    $meta['sector'] = $this->sectors;


                BP_Challenge::saveMetadata($wpdb->insert_id, $meta);
            }
        }
        return $result;
    }

    /**
     * Delete the current challenge.
     *
     * @return bool True on success, false on failure.
     */
    public function delete() {
        global $wpdb, $bp;

        wp_cache_delete('bp_challenges_challenge_' . $this->id, 'bp');

        // Remove the challenge entry from the DB
        if (!$wpdb->query($wpdb->prepare("DELETE FROM {$bp->challenges->table_name} WHERE id = %d", $this->id)))
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

    function get_challenge_details($challenge_id = 0) {
        global $wpdb;

        if (!$challenge_id)
            $challenge_id = $this->id;

        $sql_query_select = "SELECT c.* ,r.description rdesc";
        $sql_query_from = " FROM ext_challenge c, ext_challenge_right r ";
        $sql_query_where = " WHERE c.id=$challenge_id AND c.right_id=r.id ";
        $sql_query = $sql_query_select . $sql_query_from . $sql_query_where;

        return $wpdb->get_row($sql_query, ARRAY_A);
    }

    /** Static Methods *************************************************** */

    /**
     * Get whether an challenge exists for a given slug.
     *
     * @param string $slug Slug to check.
     * @param string $table_name Optional. Name of the table to check
     *        against. Default: $bp->challenges->table_name.
     * @return string|null ID of the challenge, if one is found, else null.
     */
    public static function challenge_exists($slug, $table_name = false) {
        global $wpdb, $bp;

        if (empty($table_name))
            $table_name = $bp->challenges->table_name;

        if (empty($slug))
            return false;
        $challenge_id = filter_var($slug, FILTER_SANITIZE_NUMBER_INT);

        if ($slug != $bp->challenges->challenges_subdomain . ($wpdb->get_var($wpdb->prepare("SELECT id FROM {$table_name} WHERE id = %d", $challenge_id))))
            $challenge_id = 0;
        return $challenge_id;
    }

    /**
     * Get a total challenges count for the CECommunity Platform.
     *
     * @return int Patent_Licenses count.
     */
    public static function challenges_get_total_challenges_count() {
        global $wpdb, $bp;
        $challenge_category = (bp_challenges_current_category() != "none" ? " WHERE type_id=" . bp_challenges_current_category() : "");
        $count = $wpdb->get_var("SELECT COUNT(id) FROM {$bp->challenges->table_name} WHERE deadline >= NOW()" . $challenge_category);
        return $count;
    }

    /**
     * Get the count of challenges of which the specified user has.
     *
     * @param int $user_id Optional. Default: ID of the displayed user.
     * @return int Patent_Licenses count.
     */
    public static function challenges_total_challenges_count($user_id = 0) {

        global $bp, $wpdb;

        if (empty($user_id))
            $user_id = bp_displayed_user_id();

        if (!bp_loggedin_user_id()) {
            return null;
        } else {
            $challenge_category = (bp_challenges_current_category() != "none" ? " AND type_id=" . bp_challenges_current_category() : "");
            return $wpdb->get_var($wpdb->prepare("SELECT COUNT(DISTINCT id) FROM {$bp->challenges->table_name} WHERE uid = %d" . $challenge_category, $user_id));
        }
    }

    //Fetch the available exchange types
    public static function getRights() {
        global $wpdb;
        $challenge_exchange = $wpdb->get_results("SELECT * FROM ext_challenge_right order by description asc");
        if (!is_array($challenge_exchange))
            return nil;
        return $challenge_exchange;
    }

    //Save meta data to ext_challenge_meta table
    public static function saveMetadata($challengeID, $metadata) {
        global $wpdb;

        //Check if a valid challengeID is given
        if ($challengeID) {
            $query = "INSERT INTO ext_challenge_meta (cid,mkey,mvalue) VALUES ";
            //$metadata ($key => Array) Two dimensions array
            foreach ($metadata as $mkey => $mvalue) {
                foreach ($mvalue as $key => $value) {
                    $query .= "($challengeID,'$mkey','$value') ,";
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
     * Query for challenges.
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
     *     @type string $search_terms Optional. If provided, only challenges
     *           whose names or descriptions match the search terms will be
     *           returned. Default: false.
     *     @type string $search_extras Optional. If provided, serach fileds will be
     *           taken under consideration. Default: false.
     * }
     * @return array {
     *     @type array $challenges Array of group objects returned by the
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
        $sql['select'] = "SELECT challenge.* ";

        //Main table to fetch the information
        $sql['from'] = " FROM {$bp->challenges->table_name} challenge";


        //Query Where clause 
        $sql['where'] = "WHERE 1" . (empty($r['user_id']) ? " AND deadline >= NOW()" : "");

        if (!empty($r['user_id'])) {
            $sql['user'] = " AND uid = {$r['user_id']}";
        }


        //Calculate serach metaquery
        $sql['search'] = self::build_search_meta_query($r['search_extras']);

        //Actuall serach terms text-based
        if (!empty($r['search_terms'])) {
            $search_terms = esc_sql(like_escape($r['search_terms']));
            $sql['search'] .= " AND (challenge.description LIKE '%%{$search_terms}%%')";
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
        $paged_challenges_sql = apply_filters('bp_challenges_get_paged_challenges_sql', join(' ', (array) $sql), $sql, $r);
        $paged_challenges = $wpdb->get_results($paged_challenges_sql);
        echo " Paged Query: " . $paged_challenges_sql . "<br> Results count:" . $wpdb->num_rows;


        $total_sql['select'] = "SELECT COUNT(DISTINCT id) FROM {$bp->challenges->table_name} as challenge";

        //Where clause for search box
        if (!empty($sql['search'])) {
            $total_sql['where'][] = substr($sql['search'], 4);
        }

        //True: All Patent_Licenses / False: My challenges tab
        if (!empty($r['user_id'])) {
            $total_sql['where'][] = $wpdb->prepare(" uid = %d", $r['user_id']);
        } else
            $total_sql['where'][] = "deadline >= NOW()";

        $t_sql = $total_sql['select'];

        if (!empty($total_sql['where'])) {
            $t_sql .= " WHERE " . join(' AND ', (array) $total_sql['where']);
        }


        // Get total challenge results
        $total_challenges_sql = apply_filters('bp_challenges_get_total_challenges_sql', $t_sql, $total_sql, $r);
        $total_challenges = $wpdb->get_var($total_challenges_sql);
        echo "<br>Count query: " . $total_challenges_sql;

        $challenge_ids = array();
        foreach ((array) $paged_challenges as $challenge) {
            $challenge_ids[] = $challenge->id;
        }

        unset($sql, $total_sql);

        return array('challenges' => $paged_challenges, 'total' => $total_challenges);
    }

    //Build the meta query based on the arguments given in the challenges search form 
    protected static function build_search_meta_query($search_extras) {

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
                //Take into account patent/license type
                $serach_extras_query = ( is_numeric($search_extras_args['challenge-reward']) ? " AND reward >=' {$search_extras_args['challenge-reward']}' " : "");

                //Handle sectors and subsectors fields
                $search_extras_subquery = '';
                if (!empty($search_extras_args['challenge-sectors'])) {
                    $sectors_query = "(mkey='sector' and mvalue in ({$search_extras_args['challenge-sectors']})";
                    $search_extras_subquery = " AND challenge.id in (select cid from ext_challenge_meta where {$sectors_query}))";
                }
            }
        }
        //echo "Meta Query: " . $serach_extras_query.$search_extras_subquery;
        return $serach_extras_query . $search_extras_subquery;
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

            case 'challengetype' :
                $order = 'DESC';
                $orderby = 'challengetype';
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

            case 'challengetype' :
                $order_by_term = 'deadline';
                break;

            case 'name' :
                $order_by_term = 'g.name';
                break;
        }

        return $order_by_term;
    }

    public static function get_organization_challenges($args = array()) {

        //print_r($args);
        global $wpdb, $bp;

        $limit = " limit 10";

        $sql = array();
        $total_sql = array();

        //TODO: Proper handle of selection clause
        $sql['select'] = "SELECT *";

        //Main table to fetch the information
        $sql['from'] = " FROM {$bp->challenges->table_name} as challenge ";


        //Query Where clause 
        $sql['where'] = "WHERE challenge.type_id = type.id AND challenge.gid={$args['group_id']} AND deadline >= NOW()";


        $sql['orderby'] = "ORDER BY date DESC" . $limit;
        ;
        /* End of Order Calculation */


        // Get paginated results
        $paged_challenges_sql = apply_filters('bp_challenges_get_paged_challenges_sql', join(' ', (array) $sql), $sql);
        //echo "Offer query paginates results: " . $paged_challenges_sql;
        $paged_challenges = $wpdb->get_results($paged_challenges_sql);


        $total_challenges_sql = "SELECT COUNT(DISTINCT id) FROM {$bp->challenges->table_name}  as challenge WHERE gid={$args['group_id']} AND deadline >= NOW() ";
        // Get total offer results

        $total_challenges = $wpdb->get_var($total_challenges_sql);
        //echo " <br>Offer count query: " . $total_challenges;

        unset($sql, $total_sql);

        return array('challenges' => $paged_challenges, 'total' => $total_challenges);
    }

}

?>