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
    var $partner_type_id;
    //var $country_id;
    var $program_id;
    var $date;
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
            'partner_type_id' => Null,
            //'country_id' => Null,
            'program_id' => Null,
            'date' => date('Y-m-d H:i:s')
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

        $row = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$bp->offers->table_name} WHERE id = %d", $this->id));

        //If query returned a result assign the values 
        if ($row) {
            $this->id = $row->id;
            $this->uid = $row->uid; //User ID
            $this->gid = $row->gid; //Group ID
            $this->type_id = $row->type_id; //Offer type ID
            $this->collaboration_id = $row->collaboration_id;
            $this->description = $row->description;
            $this->partner_type_id = $row->partner_type_id;
            //$this->country_id = $row->country_id;
            $this->program_id = $row->program_id;
            $this->date = $row->date;
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

        /*         * *
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
         * filter to 'bp-example-filters.php'
         */

        /* Filter all values before saving to DB */

        $this->id = apply_filters('bp_offers_data_before_save', $this->id);
        $this->uid = apply_filters('bp_offers_data_before_save', $this->uid);
        $this->gid = apply_filters('bp_offers_data_before_save', $this->gid);
        $this->type_id = apply_filters('bp_offers_data_before_save', $this->type_id);
        $this->collaboration_id = apply_filters('bp_offers_data_before_save', $this->collaboration_id);
        $this->description = apply_filters('bp_offers_data_before_save', $this->description);
        $this->partner_type_id = apply_filters('bp_offers_data_before_save', $this->partner_type_id);
        //$this->country_id = apply_filters('bp_offers_data_before_save', $this->country_id);
        $this->program_id = apply_filters('bp_offers_data_before_save', $this->program_id);
        $this->date = apply_filters('bp_offers_data_before_save', $this->date);

        //$wpdb->show_errors(); /* <=== Uncomment for query debug */
        // Call a before save action here
        //do_action('bp_offers_data_before_save', $this);
        //Offer already exist, Update the current offer
        if ($this->id) {

            $status = $wpdb->update($bp->offers->table_name, array(
                'type_id' => $this->type_id, //Offer type ID
                'collaboration_id' => $this->collaboration_id,
                'description' => $this->description,
                'partner_type_id' => $this->partner_type_id,
                //'country_id' => ($this->country_id == 0 ? Null : $this->country_id),
                'program_id' => ($this->program_id == 0 ? Null : $this->program_id),
                'date' => $this->date), array('%d', '%d', '%s', '%d', '%d', '%d', '%s')
            );


            echo "update";
            die();
            // Save the post
            $result = wp_update_post($wp_update_post_args);

            // We'll store the reciever's ID as postmeta
            if ($result) {
                update_post_meta($result, 'bp_offers_recipient_id', $this->recipient_id);
            }
        } else {//Insert the new offer in the database 
            //Dfeault fields
            $db_args_default = array(
                'uid' => $this->uid, //User ID
                'gid' => $this->gid, //Group ID
                'type_id' => $this->type_id, //Offer type ID
                'collaboration_id' => $this->collaboration_id,
                'description' => $this->description,
                'date' => $this->date);

            //Default fields format
            $db_args_format = array('%d', '%d', '%d', '%d', '%s', '%s');

            /*
             * TODO: Optimize the following batch of code
             */

            if ($this->type_id == 1) {
                $db_args_extra = array(
                    'partner_type_id' => $this->partner_type_id);
                //'country_id' => $this->country_id);
                $db_args_extra_format = array('%d');
            } else {
                $db_args_extra = array('program_id' => $this->program_id);
                $db_args_extra_format = array('%d');
            }

            //Merge default args with the extra one
            $db_args_default = array_merge($db_args_default, $db_args_extra);
            $db_args_format = array_merge($db_args_format, $db_args_extra_format);

            //Insert the data to DB
            $result = $wpdb->insert($bp->offers->table_name, $db_args_default, $db_args_format);
        }

        /* Add an after save action here */
        // do_action('bp_offers_data_after_save', $this);
        return $result;
    }

    /**
     * Part of our bp_offers_has_high_fives() loop
     *
     * @package BuddyPress_Skeleton_Component
     * @since 1.6
     */
    function have_posts() {
        return $this->query->have_posts();
    }

    /**
     * Part of our bp_offers_has_high_fives() loop
     *
     * @package BuddyPress_Skeleton_Component
     * @since 1.6
     */
    function the_post() {
        return $this->query->the_post();
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

        $sql_query_select = "SELECT t.description tdesc,c.description cdesc";
        $sql_query_from = " FROM ext_offer o, ext_offer_type t,ext_offer_collaboration c ";
        $sql_query_where = " WHERE o.id=$offer_id AND o.type_id=t.id AND o.collaboration_id=c.id ";


        switch ($this->type_id) {
            //Offer Type: 1-Develop product and services
            case 1:
                $sql_query_select .= ",p.description pdesc";
                $sql_query_from .=",ext_offer_partner_type p";
                $sql_query_where .=" AND o.partner_type_id=p.id";
                break;
            //Offer Type: 2-Participate to funded projects
            case 2:
                $sql_query_select .= ",p.description pdesc";
                $sql_query_from .=",ext_offer_program p";
                $sql_query_where .=" AND o.program_id=p.id";
                break;
            case 3:
                echo "i equals 2";
                break;
        }

        $sql_query = $sql_query_select . $sql_query_from . $sql_query_where;

        return $wpdb->get_row($sql_query, ARRAY_A);
        //print_r($results);
    }

    /** Static Methods *************************************************** */

    /**
     * Get whether an offer exists for a given slug.
     *
     * @param string $slug Slug to check.
     * @param string $table_name Optional. Name of the table to check
     *        against. Default: $bp->offers->table_name.
     * @return string|null ID of the group, if one is found, else null.
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

        if ($user_id != bp_loggedin_user_id() && !bp_current_user_can('bp_moderate')) {
            return null; //return $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(DISTINCT m.group_id) FROM {$bp->groups->table_name_members} m, {$bp->groups->table_name} g WHERE m.group_id = g.id AND g.status != 'hidden' AND m.user_id = %d AND m.is_confirmed = 1 AND m.is_banned = 0", $user_id ) );
        } else {
            return $wpdb->get_var($wpdb->prepare("SELECT COUNT(DISTINCT id) FROM {$bp->offers->table_name} WHERE uid = %d", $user_id));
        }
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
        $offer_types = $wpdb->get_results("SELECT * FROM ext_offer_type");
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

    /* Queries staff */

    /**
     * Query for groups.
     *
     * @see WP_Meta_Query::queries for a description of the 'meta_query'
     *      parameter format.
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
     *     @type string $search_terms Optional. If provided, only groups
     *           whose names or descriptions match the search terms will be
     *           returned. Default: false.
     *     @type array $meta_query Optional. An array of meta_query
     *           conditions. See {@link WP_Meta_Query::queries} for
     *           description.
     *     @type array|string Optional. Array or comma-separated list of
     *           group IDs. Results will be limited to groups within the
     *           list. Default: false.
     *     @type bool $populate_extras Whether to fetch additional
     *           information (such as member count) about groups. Default:
     *           true.
     *     @type array|string Optional. Array or comma-separated list of
     *           group IDs. Results will exclude the listed groups.
     *           Default: false.
     *     @type bool $show_hidden Whether to include hidden groups in
     *           results. Default: false.
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
            'meta_query' => false,
            'include' => false,
            'populate_extras' => true,
            'exclude' => false,
            'show_hidden' => false,
        );

        $r = wp_parse_args($args, $defaults);

        $sql = array();
        $total_sql = array();

        $sql['select'] = "SELECT *";
        $sql['from'] = " FROM {$bp->offers->table_name}";

        /* if (!empty($r['user_id'])) {
          $sql['members_from'] = " {$bp->groups->table_name_members} m,";
          }
         */

        if (!empty($r['user_id'])) {

            $sql['user'] = " WHERE uid = {$r['user_id']}";
        }


        /*
          if (!empty($r['search_terms'])) {
          $search_terms = esc_sql(like_escape($r['search_terms']));
          $sql['search'] = " AND ( g.name LIKE '%%{$search_terms}%%' OR g.description LIKE '%%{$search_terms}%%' )";
          }

          //$meta_query_sql = self::get_meta_query_sql($r['meta_query']);

          if (!empty($meta_query_sql['join'])) {
          $sql['from'] .= $meta_query_sql['join'];
          }

          if (!empty($meta_query_sql['where'])) {
          $sql['meta'] = $meta_query_sql['where'];
          }
         */


        if (!empty($r['per_page']) && !empty($r['page'])) {
            $sql['pagination'] = $wpdb->prepare("LIMIT %d, %d", intval(( $r['page'] - 1 ) * $r['per_page']), intval($r['per_page']));
        }

        // Get paginated results
        $paged_offers_sql = apply_filters('bp_groups_get_paged_groups_sql', join(' ', (array) $sql), $sql, $r);
        $paged_offers = $wpdb->get_results($paged_offers_sql);

        $total_sql['select'] = "SELECT COUNT(DISTINCT id) FROM {$bp->offers->table_name}";

        /*
          if (!empty($r['user_id'])) {
          $total_sql['select'] .= ", {$bp->groups->table_name_members} m";
          }


          if (!empty($sql['search'])) {
          $total_sql['where'][] = "( g.name LIKE '%%{$search_terms}%%' OR g.description LIKE '%%{$search_terms}%%' )";
          }
         */
        if (!empty($r['user_id'])) {
            $total_sql['where'][] = $wpdb->prepare(" uid = %d", $r['user_id']);
        }

        // Temporary implementation of meta_query for total count
        // See #5099
        if (!empty($meta_query_sql['where'])) {
            // Join the groupmeta table
            $total_sql['select'] .= ", " . substr($meta_query_sql['join'], 0, -2);

            // Modify the meta_query clause from paged_sql for our syntax
            $meta_query_clause = preg_replace('/^\s*AND/', '', $meta_query_sql['where']);
            $total_sql['where'][] = $meta_query_clause;
        }


        $t_sql = $total_sql['select'];

        if (!empty($total_sql['where'])) {
            $t_sql .= " WHERE " . join(' AND ', (array) $total_sql['where']);
        }

        // Get total group results
        $total_offers_sql = apply_filters('bp_groups_get_total_groups_sql', $t_sql, $total_sql, $r);
        $total_offers = $wpdb->get_var($total_offers_sql);

        $offer_ids = array();
        foreach ((array) $paged_offers as $offer) {
            $offer_ids[] = $offer->id;
        }

        // Populate some extra information instead of querying each time in the loop
        /* if (!empty($r['populate_extras'])) {
          $paged_offers = BP_Groups_Group::get_group_extras($paged_offers, $offer_ids, $r['type']);
          } */

        // Grab all groupmeta
        bp_groups_update_meta_cache($offer_ids);

        unset($sql, $total_sql);

        return array('offers' => $paged_offers, 'total' => $total_offers);
    }

    /**
     * Get the SQL for the 'meta_query' param in BP_Activity_Activity::get()
     *
     * We use WP_Meta_Query to do the heavy lifting of parsing the
     * meta_query array and creating the necessary SQL clauses. However,
     * since BP_Activity_Activity::get() builds its SQL differently than
     * WP_Query, we have to alter the return value (stripping the leading
     * AND keyword from the 'where' clause).
     *
     * @since BuddyPress (1.8.0)
     * @access protected
     *
     * @param array $meta_query An array of meta_query filters. See the
     *        documentation for {@link WP_Meta_Query} for details.
     * @return array $sql_array 'join' and 'where' clauses.
     */
    protected static function get_meta_query_sql($meta_query = array()) {
        global $wpdb;

        $sql_array = array(
            'join' => '',
            'where' => '',
        );

        if (!empty($meta_query)) {
            $offers_meta_query = new WP_Meta_Query($meta_query);

            // WP_Meta_Query expects the table name at
            // $wpdb->group
            $wpdb->groupmeta = buddypress()->groups->table_name_groupmeta;

            $meta_sql = $offers_meta_query->get_sql('group', 'g', 'id');

            // BP_Groups_Group::get uses the comma syntax for table
            // joins, which means that we have to do some regex to
            // convert the INNER JOIN and move the ON clause to a
            // WHERE condition
            //
			// @todo It may be better in the long run to refactor
            // the more general query syntax to accord better with
            // BP/WP convention
            preg_match_all('/INNER JOIN (.*) ON/', $meta_sql['join'], $matches_a);
            preg_match_all('/ON \((.*)\)/', $meta_sql['join'], $matches_b);

            if (!empty($matches_a[1]) && !empty($matches_b[1])) {
                $sql_array['join'] = implode(',', $matches_a[1]) . ', ';

                $sql_array['where'] = '';

                $meta_query_where_clauses = explode("\n", $meta_sql['where']);
                foreach ($matches_b[1] as $key => $offer_id_clause) {
                    $sql_array['where'] .= ' ' . preg_replace('/^(AND\s+[\(\s]+)/', '$1' . $offer_id_clause . ' AND ', ltrim($meta_query_where_clauses[$key]));
                }
            }
        }

        return $sql_array;
    }

    /**
     * Convert the 'type' parameter to 'order' and 'orderby'.
     *
     * @since BuddyPress (1.8.0)
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
                $orderby = 'date_created';
                break;

            case 'active' :
                $order = 'DESC';
                $orderby = 'last_activity';
                break;

            case 'popular' :
                $order = 'DESC';
                $orderby = 'total_member_count';
                break;

            case 'alphabetical' :
                $order = 'ASC';
                $orderby = 'name';
                break;

            case 'random' :
                $order = '';
                $orderby = 'random';
                break;
        }

        return array('order' => $order, 'orderby' => $orderby);
    }

    /**
     * Convert the 'orderby' param into a proper SQL term/column.
     *
     * @since BuddyPress (1.8.0)
     * @access protected
     *
     * @param string $orderby Orderby term as passed to get().
     * @return string $order_by_term SQL-friendly orderby term.
     */
    protected static function convert_orderby_to_order_by_term($orderby) {
        $order_by_term = '';

        switch ($orderby) {
            case 'date_created' :
            default :
                $order_by_term = 'g.date_created';
                break;

            case 'last_activity' :
                $order_by_term = 'last_activity';
                break;

            case 'total_member_count' :
                $order_by_term = 'CONVERT(gm1.meta_value, SIGNED)';
                break;

            case 'name' :
                $order_by_term = 'g.name';
                break;

            case 'random' :
                $order_by_term = 'rand()';
                break;
        }

        return $order_by_term;
    }

}

?>