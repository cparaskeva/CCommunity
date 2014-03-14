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
class BP_Offer {

    var $id;
    var $uid; //User ID
    var $gid; //Group ID
    var $type_id; //Offer type ID
    var $collaboration_id;
    var $description;
    var $finance_stage_id;
    var $partner_type_id;
    var $country_id;
    var $program_id;
    var $date;
    var $sectors;
    var $query;

    /**
     * bp_offers_tablename()
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
            'type_id' => 0, //Offer type ID
            'collaboration_id' => 0,
            'description' => "",
            'finance_stage_id' => Null,
            'partner_type_id' => Null,
            'country_id' => Null,
            'program_id' => Null,
            'date' => date('Y-m-d H:i:s'),
            'sectors' => ''
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

        $offer = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$bp->offers->table_name} WHERE id = %d", $this->id));

        //If query returned a result assign the values 
        if ($offer) {
            $this->id = $offer->id;
            $this->uid = $offer->uid; //User ID
            $this->gid = $offer->gid; //Group ID
            $this->type_id = $offer->type_id; //Offer type ID
            $this->collaboration_id = $offer->collaboration_id;
            $this->description = $offer->description;
            $this->finance_stage_id = $offer->finance_stage_id;
            $this->partner_type_id = $offer->partner_type_id;
            $this->country_id = $offer->country_id;
            $this->program_id = $offer->program_id;
            $this->date = $offer->date;
        }

        //Fetch offer metadata from DB
        if ($offer->id)
            $offer_meta = $wpdb->get_results("SELECT s.id,s.color,s.description from ext_offer_meta m,ext_organization_sector s where m.mkey='sector' and m.mvalue = s.id and oid=$offer->id", ARRAY_A);

        //Save the data to an array
        if ($offer_meta)
            $this->sectors = $offer_meta;
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
         * filter to 'cecom-offers-filters.php'
         */

        /* Filter all values before saving to DB */

        $this->id = apply_filters('bp_offers_data_before_save', $this->id);
        $this->uid = apply_filters('bp_offers_data_before_save', $this->uid);
        $this->gid = apply_filters('bp_offers_data_before_save', $this->gid);
        $this->type_id = apply_filters('bp_offers_data_before_save', $this->type_id);
        $this->collaboration_id = apply_filters('bp_offers_data_before_save', $this->collaboration_id);
        $this->description = apply_filters('bp_offers_data_before_save', $this->description);
        $this->finance_stage_id = apply_filters('bp_offers_data_before_save', $this->finance_stage_id);
        $this->partner_type_id = apply_filters('bp_offers_data_before_save', $this->partner_type_id);
        $this->country_id = apply_filters('bp_offers_data_before_save', $this->country_id);
        $this->program_id = apply_filters('bp_offers_data_before_save', $this->program_id);
        $this->date = apply_filters('bp_offers_data_before_save', $this->date);
        //$this->sectors=apply_filters('bp_offers_data_before_save', $this->sectors); //Sectors cannot be checked because is array
        //Offer already exist, Update the current offer
        if ($this->id) {

            //Dfeault fields
            $query_args_default = array(
                'description' => $this->description,
                'date' => $this->date);

            //Default fields format
            $query_args_format = array('%s', '%s');


            //Set the proper arguments for each offer based on offer type
            switch ($this->type_id) {

                case 1://Offer Type: Collaboration to develop product and services
                    $query_args_extra = array('collaboration_id' => $this->collaboration_id, 'partner_type_id' => $this->partner_type_id);
                    $query_args_extra_format = array('%d', '%d');
                    break;
                case 2://Offer Type: Collaboration to participate to funded projects
                    $query_args_extra = array('collaboration_id' => $this->collaboration_id, 'program_id' => $this->program_id);
                    $query_args_extra_format = array('%d', '%d');
                    break;
                case 3://Offer Type: Offering Funding
                    $query_args_extra = array('country_id' => $this->country_id, 'finance_stage_id' => $this->finance_stage_id);
                    $query_args_extra_format = array('%s', '%d');
                    break;
            }


            //Merge default args with the extra one
            $query_args_default = array_merge($query_args_default, $query_args_extra);
            $query_args_format = array_merge($query_args_format, $query_args_extra_format);

            $query_where = array('ID' => $this->id);

            //Update the the offer in the DB
            $result = $wpdb->update($bp->offers->table_name, $query_args_default, $query_where, $query_args_format);

            //Clear the old metadata
            $wpdb->get_results("DELETE  FROM `ext_offer_meta` where oid= $this->id");

            //Store the updated metadata only if offer has at least one sector
            if ($this->sectors != "null")
                BP_Offer::saveMetadata($this->id, $this->sectors);
        } else {//Insert the new offer in the database 
            //Dfeault fields
            $query_args_default = array(
                'uid' => $this->uid, //User ID
                'gid' => $this->gid, //Group ID
                'type_id' => $this->type_id, //Offer type ID
                'description' => $this->description,
                'date' => $this->date);

            //Default fields format
            $query_args_format = array('%d', '%d', '%d', '%s', '%s');

            //Set the proper arguments for each offer based on offer type
            switch ($this->type_id) {

                case 1://Offer Type: Collaboration to develop product and services
                    $query_args_extra = array('collaboration_id' => $this->collaboration_id, 'partner_type_id' => $this->partner_type_id);
                    $query_args_extra_format = array('%d', '%d');
                    break;
                case 2://Offer Type: Collaboration to participate to funded projects
                    $query_args_extra = array('collaboration_id' => $this->collaboration_id, 'program_id' => $this->program_id);
                    $query_args_extra_format = array('%d', '%d');
                    break;
                case 3://Offer Type: Offering Funding
                    $query_args_extra = array('country_id' => $this->country_id, 'finance_stage_id' => $this->finance_stage_id);
                    $query_args_extra_format = array('%s', '%d');
                    break;
            }

            //Merge default args with the extra one
            $query_args_default = array_merge($query_args_default, $query_args_extra);
            $query_args_format = array_merge($query_args_format, $query_args_extra_format);

            //Insert the data to DB
            $result = $wpdb->insert($bp->offers->table_name, $query_args_default, $query_args_format);

            //If insertion if success store the meta
            if ($wpdb->insert_id && $this->sectors != "null") {
                BP_Offer::saveMetadata($wpdb->insert_id, $this->sectors);
            }
        }
        return $result;
    }


    /**
     * Delete the current offer.
     *
     * @return bool True on success, false on failure.
     */
    public function delete() {
        global $wpdb, $bp;

        wp_cache_delete('bp_offers_offer_' . $this->id, 'bp');

        // Remove the offer entry from the DB
        if (!$wpdb->query($wpdb->prepare("DELETE FROM {$bp->offers->table_name} WHERE id = %d", $this->id)))
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

    function get_offer_details($offer_id = 0) {
        global $wpdb;

        if (!$offer_id)
            $offer_id = $this->id;

        $sql_query_select = "SELECT t.description tdesc";
        $sql_query_from = " FROM ext_offer o, ext_offer_type t ";
        $sql_query_where = " WHERE o.id=$offer_id AND o.type_id=t.id ";


        switch ($this->type_id) {
            //Offer Type: 1-Develop product and services
            case 1:
                $sql_query_select .= ",p.description pdesc,c.description cdesc";
                $sql_query_from .=",ext_offer_partner_type p,ext_offer_collaboration c";
                $sql_query_where .=" AND o.partner_type_id=p.id AND o.collaboration_id=c.id";
                break;
            //Offer Type: 2-Participate to funded projects
            case 2:
                $sql_query_select .= ",p.description pdesc,c.description cdesc";
                $sql_query_from .=",ext_offer_program p,ext_offer_collaboration c";
                $sql_query_where .=" AND o.program_id=p.id AND o.collaboration_id=c.id";
                break;
            //Offer Type: Funding
            case 3:
                $sql_query_select .= ",f.description fdesc,c.name cname";
                $sql_query_from .=",ext_offer_finance_stage f,ext_organization_country c";
                $sql_query_where .=" AND o.finance_stage_id=f.id AND o.country_id=c.id";
                break;
        }

        $sql_query = $sql_query_select . $sql_query_from . $sql_query_where;

        return $wpdb->get_row($sql_query, ARRAY_A);
    }

    /** Static Methods *************************************************** */

    /**
     * Get whether an offer exists for a given slug.
     *
     * @param string $slug Slug to check.
     * @param string $table_name Optional. Name of the table to check
     *        against. Default: $bp->offers->table_name.
     * @return string|null ID of the offer, if one is found, else null.
     */
    public static function offer_exists($slug, $table_name = false) {
        global $wpdb, $bp;

        if (empty($table_name))
            $table_name = $bp->offers->table_name;

        if (empty($slug))
            return false;
        $offer_id = filter_var($slug, FILTER_SANITIZE_NUMBER_INT);

        if ($slug != $bp->offers->offers_subdomain . ($wpdb->get_var($wpdb->prepare("SELECT id FROM {$table_name} WHERE id = %d", $offer_id))))
            $offer_id = 0;
        return $offer_id;
    }

    /**
     * Get a total offers count for the CECommunity Platform.
     *
     * @return int Offers count.
     */
    public static function offers_get_total_offers_count() {
        global $wpdb, $bp;
        $count = $wpdb->get_var("SELECT COUNT(id) FROM {$bp->offers->table_name}");

        return $count;
    }

    /**
     * Get the count of offers of which the specified user has.
     *
     * @param int $user_id Optional. Default: ID of the displayed user.
     * @return int Offers count.
     */
    public static function offers_total_offers_count($user_id = 0) {

        global $bp, $wpdb;

        if (empty($user_id))
            $user_id = bp_displayed_user_id();

        if (!bp_loggedin_user_id()) {
            return null;
        } else {
            return $wpdb->get_var($wpdb->prepare("SELECT COUNT(DISTINCT id) FROM {$bp->offers->table_name} WHERE uid = %d", $user_id));
        }
    }

    //Fetch the available financing stages of an offer
    public static function getFinanceStages() {
        global $wpdb;
        $offer_finance_stages = $wpdb->get_results("SELECT * FROM ext_offer_finance_stage");
        if (!is_array($offer_finance_stages))
            return nil;
        return $offer_finance_stages;
    }

    //Fetch the available grant programs of an offer
    public static function getGrantPrograms() {
        global $wpdb;
        $offer_program = $wpdb->get_results("SELECT * FROM ext_offer_program");
        if (!is_array($offer_program))
            return nil;
        return $offer_program;
    }

    //Fetch the available offer types
    public static function getOfferTypes() {
        global $wpdb;
        $offer_types = $wpdb->get_results("SELECT * FROM ext_offer_type order by description asc");
        if (!is_array($offer_types))
            return nil;
        return $offer_types;
    }

    //Fetch the available collaboration types
    public static function getCollaborationTypes() {
        global $wpdb;
        $offer_collaboration = $wpdb->get_results("SELECT * FROM ext_offer_collaboration");
        if (!is_array($offer_collaboration))
            return nil;
        return $offer_collaboration;
    }

    //Fetch the available partner sought types
    public static function getPartnerTypes() {
        global $wpdb;
        $offer_partner_type = $wpdb->get_results("SELECT * FROM ext_offer_partner_type");
        if (!is_array($offer_partner_type))
            return nil;
        return $offer_partner_type;
    }

    //Save meta data to ext_offer_meta table
    public static function saveMetadata($offerID, $metadata) {
        global $wpdb;
        //Check if a valid offerID is given
        if ($offerID) {
            $query = "INSERT INTO ext_offer_meta (oid,mkey,mvalue) VALUES ";
            //$metadata ($key => Array) Two dimensions array
            foreach ($metadata as $mkey => $mvalue) {
                foreach ($mvalue as $key => $value) {
                    $query .= "($offerID,'$mkey','$value') ,";
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
     * Query for offers.
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
     *     @type string $search_terms Optional. If provided, only offers
     *           whose names or descriptions match the search terms will be
     *           returned. Default: false.
     *     @type string $search_extras Optional. If provided, serach fileds will be
     *           taken under consideration. Default: false.
     * }
     * @return array {
     *     @type array $offers Array of group objects returned by the
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
        $sql['select'] = "SELECT offer.*,type.description tdesc";

        //Main table to fetch the information
        $sql['from'] = " FROM {$bp->offers->table_name} as offer,`ext_offer_type` as type";


        //Query Where clause 
        $sql['where'] = "WHERE offer.type_id = type.id ";

        if (!empty($r['user_id'])) {
            $sql['user'] = " AND uid = {$r['user_id']}";
        }


        //Calculate serach metaquery
        $sql['search'] = self::build_search_meta_query($r['search_extras']);

        //Actuall serach terms text-based
        if (!empty($r['search_terms'])) {
            $search_terms = esc_sql(like_escape($r['search_terms']));
            $sql['search'] .= " AND (offer.description LIKE '%%{$search_terms}%%')";
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
        $paged_offers_sql = apply_filters('bp_offers_get_paged_offers_sql', join(' ', (array) $sql), $sql, $r);
        $paged_offers = $wpdb->get_results($paged_offers_sql);
        echo " Paged Query: " . $paged_offers_sql. "<br> Results count:" . $wpdb->num_rows;


        $total_sql['select'] = "SELECT COUNT(DISTINCT id) FROM {$bp->offers->table_name} as offer";

        //Where clause for search box
        if (!empty($sql['search'])) {
            $total_sql['where'][] = substr($sql['search'], 4);
        }


        //True: All Offers / False: My offers tab
        if (!empty($r['user_id'])) {
            $total_sql['where'][] = $wpdb->prepare(" uid = %d", $r['user_id']);
        }

        $t_sql = $total_sql['select'];

        if (!empty($total_sql['where'])) {
            $t_sql .= " WHERE " . join(' AND ', (array) $total_sql['where']);
        }

        // Get total offer results
        $total_offers_sql = apply_filters('bp_offers_get_total_offers_sql', $t_sql, $total_sql, $r);
        $total_offers = $wpdb->get_var($total_offers_sql);
        echo "<br>Count query: " . $total_offers_sql;

        $offer_ids = array();
        foreach ((array) $paged_offers as $offer) {
            $offer_ids[] = $offer->id;
        }

        // Grab all groupmeta
        //bp_groups_update_meta_cache($offer_ids);

        unset($sql, $total_sql);

        return array('offers' => $paged_offers, 'total' => $total_offers);
    }

    //Build the meta query based on the arguments given in the offers search form 
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

                if ($search_extras_args['offer-type'] != "none")
                    $serach_extras_query = " AND type_id = {$search_extras_args['offer-type']} ";

                switch ($search_extras_args['offer-type']) {

                    //Offer Type: 1-Develop product and services
                    case 1:
                        //Take into account type of collaboration
                        $serach_extras_query.= ($search_extras_args['collaboration-type'] != 'none' ? "AND collaboration_id='{$search_extras_args['collaboration-type']}' " : "");
                        //Take into account type of partner-sought field
                        $serach_extras_query.= ($search_extras_args['collaboration-partner-sought'] != 'none' ? "AND partner_type_id={$search_extras_args['collaboration-partner-sought']} " : "");
                        break;
                    //Offer Type: 2-Participate to funded projects
                    case 2:
                        //Take into account type of collaboration
                        $serach_extras_query.= ($search_extras_args['collaboration-type'] != 'none' ? "AND collaboration_id='{$search_extras_args['collaboration-type']}' " : "");
                        //Take into account grant programs
                        $serach_extras_query.= ($search_extras_args['collaboration-programs'] != 'none' ? "AND program_id='{$search_extras_args['collaboration-programs']}' " : "");
                        break;
                    //Offer Type: Funding
                    case 3:
                        //Take into account applyable countries field
                        $serach_extras_query.= ($search_extras_args['applyable-countries'] != 'none' ? "AND country_id='{$search_extras_args['applyable-countries']}' " : "");
                        //Take into account finance stage field
                        $serach_extras_query.= ($search_extras_args['finance-stage'] != 'none' ? "AND finance_stage_id={$search_extras_args['finance-stage']} " : "");
                        //Take into accont sector fiedls
                        $serach_extras_query.= ($search_extras_args['offer-sectors'] != '' ? "AND offer.id in (select oid from ext_offer_meta where mkey='sector' and mvalue in ({$search_extras_args['offer-sectors']})) " : "");

                        break;
                }
            }
        }
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

            case 'offertype' :
                $order = 'DESC';
                $orderby = 'offertype';
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

            case 'offertype' :
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