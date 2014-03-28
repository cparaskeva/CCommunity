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
class BP_Alert {

    var $id;
    var $uid; //User ID
    var $gid; //Group ID
    var $action_id;
    var $action_query;
    var $active;
    var $date;
    var $triggered_num;

    /**
     * bp_alerts_tablename()
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
            'action_id' => "0",
            'action_query' => "",
            'active' => "",
            'date' => date('Y-m-d H:i:s'),
            'triggered_num' => 0,
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

        $alert = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$bp->alerts->table_name} WHERE id = %d", $this->id));

        //If query returned a result assign the values 
        if ($alert) {
            $this->id = $alert->id;
            $this->uid = $alert->uid; //User ID
            $this->gid = $alert->gid; //Group ID
            $this->action_id = $alert->action_id;
            $this->action_query = $alert->action_query;
            $this->active = $alert->active;
            $this->date = $alert->date;
            $this->triggered_num = $alert->triggered_num;
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
         * filter to 'cecom-alerts-filters.php'
         */

        /* Filter all values before saving to DB */

        $this->id = apply_filters('bp_alerts_data_before_save', $this->id);
        $this->uid = apply_filters('bp_alerts_data_before_save', $this->uid);
        $this->gid = apply_filters('bp_alerts_data_before_save', $this->gid);
        $this->action_id = apply_filters('bp_alerts_data_before_save', $this->action_id);
        $this->action_query = apply_filters('bp_alerts_data_before_save', $this->action_query);
        $this->active = apply_filters('bp_alerts_data_before_save', $this->active);
        $this->date = apply_filters('bp_alerts_data_before_save', $this->date);
        $this->triggered_num = apply_filters('bp_alerts_data_before_save', $this->triggered_num);

        //Alert already exist, Update the current alert
        if ($this->id) {

            //Dfeault fields
            $query_args_default = array(
                'active' => $this->active,
                'date' => $this->date,
                'triggered_num' => $this->triggered_num,);

            //Default fields format
            $query_args_format = array('%d', '%s', '%d');

            $query_where = array('ID' => $this->id);

            //Update the the alert in the DB
            $result = $wpdb->update($bp->alerts->table_name, $query_args_default, $query_where, $query_args_format);
        } else {//Insert the new alert in the database 
            //Dfeault fields
            $query_args_default = array(
                'uid' => $this->uid, //User ID
                'gid' => $this->gid, //Group ID
                'action_id' => $this->action_id,
                'action_query' => $this->action_query,
                'active' => $this->active,
                'date' => $this->date,
                'triggered_num' => $this->triggered_num,);

            //Default fields format
            $query_args_format = array('%d', '%d', '%d', '%s', '%d', '%s', '%d');

            //Insert the data to DB
            $result = $wpdb->insert($bp->alerts->table_name, $query_args_default, $query_args_format);
        }
        return $result;
    }

    /**
     * Delete the current alert.
     *
     * @return bool True on success, false on failure.
     */
    public function delete() {
        global $wpdb, $bp;

        wp_cache_delete('bp_alerts_alert_' . $this->id, 'bp');

        // Remove the alert entry from the DB
        if (!$wpdb->query($wpdb->prepare("DELETE FROM {$bp->alerts->table_name} WHERE id = %d", $this->id)))
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

    //Delete an alert from the DB based on it's ID
    static function delete_by_alert_id($alert_id) {
        if (!$alert_id)
            return false;

        global $wpdb, $bp;

        wp_cache_delete('bp_alerts_alert_' . $alert_id, 'bp');

        // Remove the alert entry from the DB
        if (!$wpdb->query($wpdb->prepare("DELETE FROM {$bp->alerts->table_name} WHERE id = %d", $alert_id)))
            return false;

        return true;
    }

    //Change status of a specific alert to be active or not
    static function modify_alert_status_by_id($alert_id, $status) {
        if (!$alert_id || ($status != 0 && $status != 1 ))
            return false;
        global $wpdb, $bp;
        if (!$wpdb->update($bp->alerts->table_name, array('active' => $status), array('ID' => $alert_id), array('%d')))
            return false;
        return true;
    }

    function get_alert_details($alert_id = 0) {
        global $wpdb;

        if (!$alert_id)
            $alert_id = $this->id;

        $sql_query_select = "SELECT p.description pdesc,o.description odesc,c.name cname";
        $sql_query_from = " FROM ext_alert tf, ext_alert_payment p,ext_alert_operation o,ext_organization_country c ";
        $sql_query_where = " WHERE tf.id=$alert_id AND tf.payment_id=p.id AND tf.operation_id=o.id AND tf.country_id=c.id ";
        $sql_query = $sql_query_select . $sql_query_from . $sql_query_where;

        return $wpdb->get_row($sql_query, ARRAY_A);
    }

    /** Static Methods *************************************************** */

    /**
     * Get whether an alert exists for a given slug.
     *
     * @param string $slug Slug to check.
     * @param string $table_name Optional. Name of the table to check
     *        against. Default: $bp->alerts->table_name.
     * @return string|null ID of the alert, if one is found, else null.
     */
    public static function alert_exists($slug, $table_name = false) {
        global $wpdb, $bp;

        if (empty($table_name))
            $table_name = $bp->alerts->table_name;

        if (empty($slug))
            return false;
        $alert_id = filter_var($slug, FILTER_SANITIZE_NUMBER_INT);

        if ($slug != $bp->alerts->alerts_subdomain . ($wpdb->get_var($wpdb->prepare("SELECT id FROM {$table_name} WHERE id = %d", $alert_id))))
            $alert_id = 0;
        return $alert_id;
    }

    /**
     * Get a total alerts count for the CECommunity Platform.
     *
     * @return int Patent_Licenses count.
     */
    public static function alerts_get_total_alerts_count() {
        global $wpdb, $bp;
        $count = $wpdb->get_var("SELECT COUNT(id) FROM {$bp->alerts->table_name}");

        return $count;
    }

    /**
     * Get the count of alerts of which the specified user has.
     *
     * @param int $user_id Optional. Default: ID of the displayed user.
     * @return int Patent_Licenses count.
     */
    public static function alerts_total_alerts_count($user_id = 0) {

        global $bp, $wpdb;

        if (empty($user_id))
            $user_id = bp_displayed_user_id();

        if (!bp_loggedin_user_id()) {
            return null;
        } else {
            return $wpdb->get_var($wpdb->prepare("SELECT COUNT(DISTINCT id) FROM {$bp->alerts->table_name} WHERE uid = %d", $user_id));
        }
    }

    /* Queries staff */

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
        $sql['select'] = "SELECT alert.*";

        //Main table to fetch the information
        $sql['from'] = " FROM {$bp->alerts->table_name} as alert";


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
            $sql['search'] .= " AND (alert.description LIKE '%%{$search_terms}%%')";
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
        $paged_alerts_sql = apply_filters('bp_alerts_get_paged_alerts_sql', join(' ', (array) $sql), $sql, $r);
        $paged_alerts = $wpdb->get_results($paged_alerts_sql);
        //echo " Paged Query: " . $paged_alerts_sql . "<br> Results count:" . $wpdb->num_rows;


        $total_sql['select'] = "SELECT COUNT(DISTINCT id) FROM {$bp->alerts->table_name} as alert";

        //Where clause for search box
        if (!empty($sql['search'])) {
            $total_sql['where'][] = substr($sql['search'], 4);
        }

        //True: All Patent_Licenses / False: My alerts tab
        if (!empty($r['user_id'])) {
            $total_sql['where'][] = $wpdb->prepare(" uid = %d", $r['user_id']);
        }

        $t_sql = $total_sql['select'];

        if (!empty($total_sql['where'])) {
            $t_sql .= " WHERE " . join(' AND ', (array) $total_sql['where']);
        }


        // Get total alert results
        $total_alerts_sql = apply_filters('bp_alerts_get_total_alerts_sql', $t_sql, $total_sql, $r);
        $total_alerts = $wpdb->get_var($total_alerts_sql);
        //echo "<br>Count query: " . $total_alerts_sql;

        $alert_ids = array();
        foreach ((array) $paged_alerts as $alert) {
            $alert_ids[] = $alert->id;
        }

        unset($sql, $total_sql);

        return array('alerts' => $paged_alerts, 'total' => $total_alerts);
    }

    //Build the meta query based on the arguments given in the alerts search form 
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
                //Take into account alert activation state
                $serach_extras_query.= ($search_extras_args['alert-status'] != 'none' ? "AND active='{$search_extras_args['alert-status']}' " : "");
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

}

final class BP_Alert_Factory {

    public function __construct() {
        define("SEARCH_ORGANIZATION_ARGS", md5("organization-sectors;|organization-subsectors;|organization-collaboration;|organization-transaction;|organization-size;none|organization-type;none|collaboration-description;|collaboration-type;none|collaboration-partner-sought;none|collaboration-programs;none|offer-type;none|organization-country;"));
        define("SEARCH_ORGANIZATION_READY_TO_COLLABORATE_DEVELOP_ARGS", md5(""));
        define("SEARCH_ORGANIZATION_READY_TO_COLLABORATE_FUNDING_ARGS", md5(""));
        define("SEARCH_OFFER_PATENT_LICENSE_ARGS", md5(""));
        define("SEARCH_OFFER_FUNDING_ARGS", md5(""));
        add_action("cecom_alert_box","cecom_get_alert_div");
    }

    //Check whenever or not an alert can be set in a specific query
    static function isAlertPermited($action, $action_hash) {
        switch ($action) {
            case(1):return $action_hash === SEARCH_ORGANIZATION_ARGS;
            case(2):return $action_hash === SEARCH_ORGANIZATION_READY_TO_COLLABORATE_DEVELOP_ARGS;
            case(3):return $action_hash === SEARCH_ORGANIZATION_READY_TO_COLLABORATE_FUNDING_ARGS;
            case(4):return $action_hash === SEARCH_OFFER_PATENT_LICENSE_ARGS;
            case(5):return $action_hash === SEARCH_OFFER_FUNDING_ARGS;
            default: return false;
        }
    }

    
    function cecom_get_alert_div(){
        die();
        echo '<div style="text-align:center;"><br>
                 <span data-toggle="tooltip" data-placement="left" title="Clicking this button will set an alert on this query. You will receive a notification when a new organisation registration  corresponds to your needs." class="glyphicon glyphicon-question-sign"></span>
                 <input disabled="true" type="submit" class="btn btn-warning" value="Set an alert!"/> 
            </div>';
        
        
        
    }
    
   
    
    
}

?>