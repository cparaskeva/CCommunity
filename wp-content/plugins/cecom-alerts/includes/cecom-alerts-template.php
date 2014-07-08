<?php

/**
 * In this file you should define template tag functions that end users can add to their template
 * files.
 *
 * It's a general practice in WordPress that template tag functions have two versions, one that
 * returns the requested value, and one that echoes the value of the first function. The naming
 * convention is usually something like 'bp_alerts_get_item_name()' for the function that returns
 * the value, and 'bp_alerts_item_name()' for the function that echoes.
 */
/*
 * Echo "Viewing x of y pages"
 */
function bp_alerts_pagination_count() {
    echo bp_alerts_get_pagination_count();
}

function bp_alerts_get_pagination_count() {
    global $alerts_template;

    $start_num = intval(( $alerts_template->pag_page - 1 ) * $alerts_template->pag_num) + 1;
    $from_num = bp_core_number_format($start_num);
    $to_num = bp_core_number_format(( $start_num + ( $alerts_template->pag_num - 1 ) > $alerts_template->total_alert_count ) ? $alerts_template->total_alert_count : $start_num + ( $alerts_template->pag_num - 1 ) );
    $total = bp_core_number_format($alerts_template->total_alert_count);

    return apply_filters('bp_get_alerts_pagination_count', sprintf(_n('Viewing Alert %1$s to %2$s (of %3$s Alerts)', 'Viewing Alert %1$s to %2$s (of %3$s Alerts)', $total, 'buddypress'), $from_num, $to_num, $total), $from_num, $to_num, $total);
}

function bp_alerts_pagination_links() {
    echo bp_get_alerts_pagination_links();
}

function bp_get_alerts_pagination_links() {
    global $alerts_template;

    return apply_filters('bp_get_alerts_pagination_links', $alerts_template->pag_links);
}

function bp_alerts_owner_avatar($args = array()) {
    echo bp_alerts_get_owner_avatar($args);
}

function bp_alerts_get_owner_avatar($args = array()) {


    global $alerts_template;
    $defaults = array(
        'item_id' => $alerts_template->alert->uid,
        'object' => 'member'
    );

    $r = wp_parse_args($args, $defaults);

    return bp_core_fetch_avatar($r);
}

function bp_alert_permalink() {
    echo bp_alert_get_permalink();
}

function bp_alert_posted_date() {
    echo bp_alert_get_posted_date();
}

function bp_alert_triggered_times() {
    global $alerts_template;
    return $alerts_template->alert->triggered_num;
}

function bp_alert_active() {
    global $alerts_template;
    return $alerts_template->alert->active;
}

function bp_alert_get_posted_date() {
    global $bp, $alerts_template;
    $posted_date = $bp->alerts->current_alert->date;
    if (!$posted_date)
        $posted_date = $alerts_template->alert->date;
    return ($posted_date ? substr($posted_date, 0, 10) : "Unknown");
}

function bp_alert_type() {
    echo bp_alert_get_type();
}

function bp_alert_get_type() {
    global $alerts_template;
    return $alerts_template->alert->tdesc;
}

function bp_alert_get_permalink() {
    global $alerts_template, $bp;

    if ($alerts_template->alert->id)
        $slug = $bp->alerts->alerts_subdomain . $alerts_template->alert->id;
    else
        $slug = $bp->alerts->current_alert->slug;

    //return bloginfo("url") . "/" . $bp->alerts->root_slug . "/" . $bp->alerts->alerts_subdomain . $alerts_template->alert->id;
    return apply_filters("bp_alert_get_permalink", trailingslashit(bp_get_root_domain() . "/" . $bp->alerts->root_slug . "/" . $slug));
}

//Check for alert actions (delete-activate/deactivate an alert)
function bp_alerts_check_modifications() {
    
    $alert_action = (isset($_GET['delete']) ? "delete" : "");
    $alert_action = (isset($_GET['activate']) ? "activate" : $alert_action);
    $alert_action = (isset($_GET['action_id']) ? "new_alert" : $alert_action );
    switch ($alert_action) {

        //Delete the current alert
        case("delete") :
            if (BP_Alert::delete_by_alert_id($_GET['delete']))
                bp_core_add_message(__('Alert has been successfuly deleted!', 'bp-alerts'), 'success');
            else
                bp_core_add_message(__('An error was occured!Alert was not able to be deleted...', 'bp-alerts'), 'error');
            break;
        //Activate deactivate current alert
        case("activate"):
            if (BP_Alert::modify_alert_status_by_id($_GET['alert'], $_GET['activate']))
                bp_core_add_message(__('Alert state has been changed!', 'bp-alerts'), 'success');
            else
                bp_core_add_message(__('An error was occured!Alert state was not able to be changed...', 'bp-alerts'), 'error');
            break;

        //Create a new alert   
        case("new_alert"):

            global $bp;
            $group_id = CECOM_Organization::getUserGroupID();
            $user_id = $bp->loggedin_user->id;
            $alert_new = array(
                'id' => 0,
                'uid' => $user_id, //User ID
                'gid' => $group_id, //Group ID
                'action_id' => $_GET['action_id'], //Action ID
                'action_query' => urldecode($_GET['query']),
                'active' => 1,
                'date' => date('Y-m-d H:i:s')
            ); 
             if (bp_alerts_publish_alert($alert_new))
                    bp_core_add_message(__('Your alert has been succesfuly set!', 'bp-alerts'), 'success');
                else
                    bp_core_add_message(__('Unable to insert infromation to database..', 'bp-alerts'), 'error');

            break;

        default:
            return;
    }
    bp_core_redirect(bp_alert_get_permalink());
}

function bp_alerts_owner_name() {
    echo bp_alerts_get_owner_name();
}

function bp_alerts_get_owner_name() {
    global $alerts_template;
    echo bp_core_get_user_displayname($alerts_template->alert->uid);
}

function bp_alerts_content() {
    echo bp_alerts_get_content();
}

function bp_alerts_get_content() {
    global $alerts_template;
    return $alerts_template->alert->description;
}

function bp_alerts_owner_permalink($userd_id = 0) {
    echo bp_alerts_get_owner_permalink($userd_id);
}

function bp_alerts_get_owner_permalink($userd_id = 0) {
    global $alerts_template;
    if (!$userd_id)
        $userd_id = $alerts_template->alert->uid;

    return bp_core_get_user_domain($userd_id);
}

function bp_alerts_is_owner() {
    echo bp_alerts_get_is_owner();
}

function bp_alerts_get_is_owner() {
    global $alerts_template;
    return (bp_loggedin_user_id() == $alerts_template->alert->uid);
}

function bp_is_alert_admin_page() {
    if (bp_is_single_item() && bp_is_alert_component() && bp_is_current_action('admin'))
        return true;

    return false;
}

function bp_is_alert_admin_screen($slug) {
    if (!bp_is_alert_component() || !bp_is_current_action('admin'))
        return false;

    if (bp_is_action_variable($slug))
        return true;
    return false;
}

function bp_get_alert_current_admin_tab() {
    if (bp_is_alert_component() && bp_is_current_action('admin')) {
        $tab = bp_action_variable(0);
    } else {
        $tab = '';
    }

    return apply_filters('bp_get_current_group_admin_tab', $tab);
}

function bp_alert_admin_tabs($alert = false) {
    global $bp, $alerts_template;

    if (empty($alert))
        $alert = ( $alerts_template->alert ) ? $alerts_template->alert : $bp->alerts->current_alert;

    $current_tab = bp_get_alert_current_admin_tab();

    if (bp_is_item_admin()) :
        ?>
        <li<?php if ('edit-details' == $current_tab || empty($current_tab)) : ?> class="current"<?php endif; ?>><a href="<?php echo trailingslashit(bp_get_alert_permalink($alert) . 'admin/edit-details') ?>"><?php _e('Details', 'buddypress'); ?></a></li>
    <?php endif; ?>
    <?php
    if (!bp_is_item_admin())
        return false;
    ?>
    <?php do_action('alerts_admin_tabs', $current_tab, $alert->slug) ?>
    <li<?php if ('delete-alert' == $current_tab) : ?> class="current"<?php endif; ?>><a href="<?php echo trailingslashit(bp_get_alert_permalink($alert) . 'admin/delete-alert') ?>"><?php _e('Delete', 'buddypress'); ?></a></li>

    <?php
}

function bp_alert_admin_form_action($page = false) {
    echo bp_get_alert_admin_form_action($page);
}

function bp_get_alert_admin_form_action($page = false, $alert = false) {
    global $bp;

    if (empty($alert))
        $alert = $bp->alerts->current_alert;

    if (empty($page))
        $page = bp_action_variable(0);

    return apply_filters('bp_alert_admin_form_action', bp_get_alert_permalink($alert) . 'admin/' . $page);
}

function bp_get_alert_permalink($alert = false) {
    global $alerts_template;

    if (empty($alert))
        $alert = & $alerts_template->alert;

    return apply_filters('bp_get_alert_permalink', trailingslashit(bp_get_root_domain() . '/' . bp_get_alerts_root_slug() . '/' . $alert->slug . '/'));
}

//Tools & Facilities Index Page - Search Form
function bp_directory_alerts_search_form() {

    $default_search_value = bp_get_search_default_text('alerts');
    $search_value = !empty($_REQUEST['s']) ? stripslashes($_REQUEST['s']) : $default_search_value;

    $search_form_html = '<form action="" method="get" id="search-alerts-form"> 
        
		<label style="vertical-align:middle">
       <select name="alert-status" id="alert-status">
            <option value="none"  selected="selected"> (All)</option>
            <option value = "1">Active</option>"
            <option value = "0">Deactive</option>"
        </select>
                    </label>
		<input type="submit" id="alerts_search_submit" name="alerts_search_submit" value="' . __('Filter', 'buddypress') . '" /><br><br>
	</form>';

    echo apply_filters('bp_directory_alerts_search_form', $search_form_html);
}

/**
 * Is this page part of the Alert component?
 *
 * Having a special function just for this purpose makes our code more readable elsewhere, and also
 * allows us to place filter 'bp_is_alert_component' for other components to interact with.
 *
 * @uses bp_is_current_component()
 * @uses apply_filters() to allow this value to be filtered
 * @return bool True if it's the example component, false otherwise
 */
function bp_is_alert_component() {
    if (bp_is_current_component('alerts'))
        return true;

    return false;
}

/**
 * Echo the component's slug
 */
function bp_alerts_slug() {
    echo bp_get_alerts_slug();
}

/**
 * Return the component's slug
 *
 * Having a template function for this purpose is not absolutely necessary, but it helps to
 * avoid too-frequent direct calls to the $bp global.
 *
 * @uses apply_filters() Filter 'bp_get_alerts_slug' to change the output
 * @return str $example_slug The slug from $bp->example->slug, if it exists
 */
function bp_get_alerts_slug() {
    global $bp;

    // Avoid PHP warnings, in case the value is not set for some reason
    $alerts_slug = isset($bp->alerts->slug) ? $bp->alerts->slug : '';

    return apply_filters('bp_get_alerts_slug', $alerts_slug);
}

/**
 * Echo the component's root slug
 */
function bp_alerts_root_slug() {
    echo bp_get_alerts_root_slug();
}

/**
 * Return the component's root slug
 *
 * Having a template function for this purpose is not absolutely necessary, but it helps to
 * avoid too-frequent direct calls to the $bp global.
 * @uses apply_filters() Filter 'bp_get_alerts_root_slug' to change the output
 * @return str $example_root_slug The slug from $bp->example->root_slug, if it exists
 */
function bp_get_alerts_root_slug() {
    global $bp;

    // Avoid PHP warnings, in case the value is not set for some reason
    $example_root_slug = isset($bp->alerts->root_slug) ? $bp->alerts->root_slug : '';

    return apply_filters('bp_get_alerts_root_slug', $example_root_slug);
}

//Return the number of total alerts

function bp_total_alerts_count() {
    echo bp_get_total_alerts_count();
}

function bp_get_total_alerts_count() {
    return apply_filters('bp_get_total_alerts_count', alerts_get_total_alerts_count());
}

function bp_total_alerts_count_for_user($user_id = 0) {
    echo bp_get_total_alerts_count_for_user($user_id);
}

/* Return the number of alerts that a member owns */

function bp_get_total_alerts_count_for_user($user_id = 0) {
    return apply_filters('bp_get_total_alerts_count_for_user', alerts_total_alerts_for_user($user_id), $user_id);
}

add_filter('bp_get_total_alerts_count_for_user', 'bp_core_number_format');

/*
 *  Tools & Facilities Template Class 
 * Used to hold all the results returned from DB based on current users' query
 */

class BP_Alerts_Template {

    var $current_alert = -1;
    var $alert_count;
    var $alerts;
    var $alert;
    var $in_the_loop;
    var $pag_page;
    var $pag_num;
    var $pag_links;
    var $total_alert_count;
    var $single_alert = false;
    var $sort_by;
    var $order;

    function __construct($args = array()) {

        $defaults = array(
            'type' => 'active',
            'page' => 1,
            'per_page' => 10,
            'max' => false,
            'page_arg' => 'ofpage',
            'user_id' => 0,
            'slug' => false,
            'search_terms' => '',
            'search_extras' => '',
        );

        $r = wp_parse_args($args, $defaults);
        extract($r);

        $this->pag_page = isset($_REQUEST[$page_arg]) ? intval($_REQUEST[$page_arg]) : $page;
        $this->pag_num = isset($_REQUEST['num']) ? intval($_REQUEST['num']) : $per_page;

        if ('single-alert' == $type) {
            /*
             * Catch Unsupported operation exception
             * TODO: Handle the exception
             */
            echo "<b>Unsupported operation exception</b><br>single-alert";
            die();
        } else {

            //Store the alerts of the user to an array()
            $this->alerts = alerts_get_alerts(array(
                'type' => $type,
                'order' => $order,
                'orderby' => $orderby,
                'per_page' => $this->pag_num,
                'page' => $this->pag_page,
                'search_terms' => $search_terms,
                'search_extras' => $search_extras,
                'user_id' => $user_id,
            ));
        }


        if ('single-alert' == $type) {
            $this->single_alert = true;
            $this->total_alert_count = 1;
            $this->alert_count = 1;
        } else {


            if (empty($max) || $max >= (int) $this->alerts['total']) {
                $this->total_alert_count = (int) $this->alerts['total'];
            } else {
                $this->total_alert_count = (int) $max;
            }

            $this->alerts = $this->alerts['alerts'];

            if (!empty($max)) {
                if ($max >= count($this->alerts)) {
                    $this->alert_count = count($this->alerts);
                } else {
                    $this->alert_count = (int) $max;
                }
            } else {
                $this->alert_count = count($this->alerts);
            }
        }

        // Build pagination links
        if ((int) $this->total_alert_count && (int) $this->pag_num) {
            $this->pag_links = paginate_links(array(
                'base' => add_query_arg(array($page_arg => '%#%', 'num' => $this->pag_num, 's' => $search_terms, 'sortby' => $this->sort_by, 'order' => $this->order)),
                'format' => '',
                'total' => ceil((int) $this->total_alert_count / (int) $this->pag_num),
                'current' => $this->pag_page,
                'prev_text' => _x('&larr;', 'Alert pagination previous text', 'buddypress'),
                'next_text' => _x('&rarr;', 'Alert pagination next text', 'buddypress'),
                'mid_size' => 1
            ));
        }
    }

    function has_alerts() {

        if ($this->alert_count)
            return true;

        return false;
    }

    function next_alert() {
        $this->current_alert++;
        $this->alert = $this->alerts[$this->current_alert];

        return $this->alert;
    }

    function rewind_alerts() {
        $this->current_alert = -1;
        if ($this->alert_count > 0) {
            $this->alert = $this->alerts[0];
        }
    }

    function alerts() {
        if ($this->current_alert + 1 < $this->alert_count) {
            return true;
        } elseif ($this->current_alert + 1 == $this->alert_count) {
            do_action('alert_loop_end');
            // Do some cleaning up after the loop
            $this->rewind_alerts();
        }

        $this->in_the_loop = false;
        return false;
    }

    function the_alert() {
        $this->in_the_loop = true;
        $this->alert = $this->next_alert();

        if ($this->single_alert)
            $this->alert = alerts_get_alert(array('alert_id' => $this->alert->alert_id));

        if (0 == $this->current_alert) // loop has just started
            do_action('alert_loop_start');
    }

}

function bp_alerts() {
    global $alerts_template;
    return $alerts_template->alerts();
}

function bp_the_alert() {
    global $alerts_template;
    return $alerts_template->the_alert();
}

function bp_alert_id($alert = false) {
    echo bp_get_alert_id($alert);
}

function bp_get_alert_id($alert = false) {
    global $alerts_template;

    if (empty($alert))
        $alert = & $alerts_template->alert;

    return apply_filters('bp_get_alert_id', $alert->id);
}

function bp_has_alerts($args = '') {
    //print_r($args);
    global $alerts_template, $bp;

    /*
     * Set the defaults based on the current page. Any of these will be overridden
     * if arguments are directly passed into the loop. Custom plugins should always
     * pass their parameters directly to the loop.
     */
    $slug = false;
    $type = '';
    $user_id = 0;
    $order = '';

    // User filtering - Tools & Facilities Main Screen
    if (bp_displayed_user_id())
        $user_id = bp_displayed_user_id();


    // Proper handle the screen one presenting the alerts
    // @todo What is $order? At some point it was removed incompletely?
    /* if (bp_is_current_action('screen-one')) {
      if ('most-popular' == $order) {
      $type = 'popular';
      } elseif ('alphabetically' == $order) {
      $type = 'alphabetical';
      }
      } elseif (isset($bp->alerts->current_alert->slug) && $bp->alerts->current_alert->slug) {
      $type = 'single-alert';
      $slug = $bp->alerts->current_alert->slug;
      } */

    $defaults = array(
        'type' => $type, // 'type' is an override for 'order' and 'orderby'. See docblock.
        'order' => 'DESC',
        'orderby' => 'newest',
        'page' => 1,
        'per_page' => 10,
        'max' => false,
        'page_arg' => 'offpage',
        'user_id' => $user_id, // Pass a user ID to limit to groups this user has joined
        'slug' => $slug, // Pass an alert slug to only return that alert
        'search_terms' => '', // Pass search terms to return only matching alerts
        'search_extras' => '',
    );


    $r = wp_parse_args($args, $defaults);

    //print_r($r);

    if (empty($r['search_terms'])) {
        if (isset($_REQUEST['alert-filter-box']) && !empty($_REQUEST['alert-filter-box']))
            $r['search_terms'] = $_REQUEST['alert-filter-box'];
        elseif (isset($_REQUEST['s']) && !empty($_REQUEST['s']))
            $r['search_terms'] = $_REQUEST['s'];
        else
            $r['search_terms'] = false;
    }

    $alerts_template = new BP_Alerts_Template(array(
        'type' => $r['type'],
        'order' => $r['order'],
        'orderby' => $r['orderby'],
        'page' => (int) $r['page'],
        'per_page' => (int) $r['per_page'],
        'max' => (int) $r['max'],
        'page_arg' => $r['page_arg'],
        'user_id' => (int) $r['user_id'],
        'slug' => $r['slug'],
        'search_terms' => $r['search_terms'],
        'search_extras' => $r['search_extras'],
    ));

    return apply_filters('bp_has_alerts', $alerts_template->has_alerts(), $alerts_template, $r);
}

function alerts_get_alerts($args = '') {
    $defaults = array(
        'type' => false, // active, newest, alphabetical, random, popular, most-forum-topics or most-forum-posts
        'order' => 'DESC', // 'ASC' or 'DESC'
        'orderby' => 'newest', // date_created, last_activity, total_member_count, name, random
        'per_page' => 20, // The number of results to return per page
        'page' => 1, // The page to return if limiting per page
        'search_terms' => false, // Limit to groups that match these search terms
        'search_extras' => false,
        'user_id' => false, // Pass a user_id to limit to only groups that this user is a member of
    );

    $r = wp_parse_args($args, $defaults);

    $alerts = BP_Alert::get(array(
                'type' => $r['type'],
                'order' => $r['order'],
                'orderby' => $r['orderby'],
                'per_page' => $r['per_page'],
                'page' => $r['page'],
                'user_id' => $r['user_id'],
                'search_terms' => $r['search_terms'],
                'search_extras' => $r['search_extras'],
    ));


    return apply_filters_ref_array('alerts_get_alerts', array(&$alerts, &$r));
}
?>



