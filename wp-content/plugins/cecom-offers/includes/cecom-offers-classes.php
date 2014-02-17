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
    var $country_id;
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
            'country_id' => Null,
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
                echo "Key: " . $key . " Value: " . $value;
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
            $this->country_id = $row->country_id;
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
        $this->country_id = apply_filters('bp_offers_data_before_save', $this->country_id);
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
                'country_id' => ($this->country_id ==0 ? Null:$this->country_id),
                'program_id' => ($this->program_id == 0? Null: $this->program_id),
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
        } else { //Insert the new offer in the database
            print_r($result);
            $wpdb->show_errors(); /* <=== Uncomment for query debug */
            $wpdb->insert($bp->offers->table_name, array(
                'uid' => $this->uid, //User ID
                'gid' => $this->gid, //Group ID
                'type_id' => $this->type_id, //Offer type ID
                'collaboration_id' => $this->collaboration_id,
                'description' => $this->description,
                'partner_type_id' => "",
                'country_id' => ($this->country_id ==0 ? "Null":$this->country_id),
                'program_id' => ($this->program_id == 0? "Null": $this->program_id),
                'date' => $this->date), array('%d', '%d', '%d', '%d', '%s', '%s', '%d', '%d', '%s')
            );
            die();
        }

        /* Add an after save action here */
        // do_action('bp_offers_data_after_save', $this);

        return $result;
    }

    /**
     * Fire the WP_Query
     *
     * @package BuddyPress_Skeleton_Component
     * @since 1.6
     */
    function get($args = array()) {
        // Only run the query once
        if (empty($this->query)) {
            $defaults = array(
                'high_fiver_id' => 0,
                'recipient_id' => 0,
                'per_page' => 10,
                'paged' => 1
            );

            $r = wp_parse_args($args, $defaults);
            extract($r);

            $query_args = array(
                'post_status' => 'publish',
                'post_type' => 'example',
                'posts_per_page' => $per_page,
                'paged' => $paged,
                'meta_query' => array()
            );

            // Some optional query args
            // Note that some values are cast as arrays. This allows you to query for multiple
            // authors/recipients at a time
            if ($high_fiver_id) {
                $query_args['author'] = (array) $high_fiver_id;
            }

            // We can filter by postmeta by adding a meta_query argument. Note that
            if ($recipient_id) {
                $query_args['meta_query'][] = array(
                    'key' => 'bp_offers_recipient_id',
                    'value' => (array) $recipient_id,
                    'compare' => 'IN' // Allows $recipient_id to be an array
                );
            }

            // Run the query, and store as an object property, so we can access from
            // other methods
            $this->query = new WP_Query($query_args);

            // Let's also set up some pagination
            $this->pag_links = paginate_links(array(
                'base' => add_query_arg('items_page', '%#%'),
                'format' => '',
                'total' => ceil((int) $this->query->found_posts / (int) $this->query->query_vars['posts_per_page']),
                'current' => (int) $paged,
                'prev_text' => '&larr;',
                'next_text' => '&rarr;',
                'mid_size' => 1
            ));
        }
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
     * delete()
     *
     * This method will delete the corresponding row for an object from the database.
     */
    function delete() {
        return wp_trash_post($this->id);
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

    /**
     * Get the count of offers of which the specified user has.
     *
     * @param int $user_id Optional. Default: ID of the displayed user.
     * @return int Offers count.
     */
    public static function total_offers_count($user_id = 0) {
        global $bp, $wpdb;

        if (empty($user_id))
            $user_id = bp_displayed_user_id();

        if ($user_id != bp_loggedin_user_id() && !bp_current_user_can('bp_moderate')) {
            return null; //return $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(DISTINCT m.group_id) FROM {$bp->groups->table_name_members} m, {$bp->groups->table_name} g WHERE m.group_id = g.id AND g.status != 'hidden' AND m.user_id = %d AND m.is_confirmed = 1 AND m.is_banned = 0", $user_id ) );
        } else {
            return $wpdb->get_var($wpdb->prepare("SELECT COUNT(DISTINCT m.group_id) FROM {$bp->groups->table_name_members} m, {$bp->groups->table_name} g WHERE m.group_id = g.id AND m.user_id = %d AND m.is_confirmed = 1 AND m.is_banned = 0", $user_id));
        }
    }

    /* Static Functions */

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

}

?>