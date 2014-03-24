<?php

/**
 * In this file you should define template tag functions that end users can add to their template
 * files.
 *
 * It's a general practice in WordPress that template tag functions have two versions, one that
 * returns the requested value, and one that echoes the value of the first function. The naming
 * convention is usually something like 'bp_tools_facilities_get_item_name()' for the function that returns
 * the value, and 'bp_tools_facilities_item_name()' for the function that echoes.
 */
/*
 * Echo "Viewing x of y pages"
 */
function bp_tools_facilities_pagination_count() {
    echo bp_tools_facilities_get_pagination_count();
}

function bp_tools_facilities_get_pagination_count() {
    global $tools_facilities_template;

    $start_num = intval(( $tools_facilities_template->pag_page - 1 ) * $tools_facilities_template->pag_num) + 1;
    $from_num = bp_core_number_format($start_num);
    $to_num = bp_core_number_format(( $start_num + ( $tools_facilities_template->pag_num - 1 ) > $tools_facilities_template->total_tool_facility_count ) ? $tools_facilities_template->total_tool_facility_count : $start_num + ( $tools_facilities_template->pag_num - 1 ) );
    $total = bp_core_number_format($tools_facilities_template->total_tool_facility_count);

    return apply_filters('bp_get_tools_facilities_pagination_count', sprintf(_n('Viewing Patent/License %1$s to %2$s (of %3$s Patents/Licenses)', 'Viewing Patent/License %1$s to %2$s (of %3$s Patents/Licenses)', $total, 'buddypress'), $from_num, $to_num, $total), $from_num, $to_num, $total);
}

function bp_tools_facilities_pagination_links() {
    echo bp_get_tools_facilities_pagination_links();
}

function bp_get_tools_facilities_pagination_links() {
    global $tools_facilities_template;

    return apply_filters('bp_get_tools_facilities_pagination_links', $tools_facilities_template->pag_links);
}

function bp_tools_facilities_owner_avatar($args = array()) {
    echo bp_tools_facilities_get_owner_avatar($args);
}

function bp_tools_facilities_get_owner_avatar($args = array()) {


    global $tools_facilities_template;
    $defaults = array(
        'item_id' => $tools_facilities_template->tool_facility->uid,
        'object' => 'member'
    );

    $r = wp_parse_args($args, $defaults);

    return bp_core_fetch_avatar($r);
}

function bp_tool_facility_permalink() {
    echo bp_tool_facility_get_permalink();
}

function bp_tool_facility_posted_date() {
    echo bp_tool_facility_get_posted_date();
}

function bp_tool_facility_get_posted_date() {
    global $bp, $tools_facilities_template;
    $posted_date = $bp->tools_facilities->current_tool_facility->date;
    if (!$posted_date)
        $posted_date = $tools_facilities_template->tool_facility->date;
    return ($posted_date ? substr($posted_date, 0, 10) : "Unknown");
}

function bp_tool_facility_type() {
    echo bp_tool_facility_get_type();
}

function bp_tool_facility_get_type() {
    global $tools_facilities_template;
    return $tools_facilities_template->tool_facility->tdesc;
}

function bp_tool_facility_get_permalink() {
    global $tools_facilities_template, $bp;

    if ($tools_facilities_template->tool_facility->id)
        $slug = $bp->tools_facilities->tools_facilities_subdomain . $tools_facilities_template->tool_facility->id;
    else
        $slug = $bp->tools_facilities->current_tool_facility->slug;

    //return bloginfo("url") . "/" . $bp->tools_facilities->root_slug . "/" . $bp->tools_facilities->tools_facilities_subdomain . $tools_facilities_template->tool_facility->id;
    return apply_filters("bp_tool_facility_get_permalink", trailingslashit(bp_get_root_domain() . "/" . $bp->tools_facilities->root_slug . "/" . $slug));
}

function bp_tools_facilities_owner_name() {
    echo bp_tools_facilities_get_owner_name();
}

function bp_tools_facilities_get_owner_name() {
    global $tools_facilities_template;
    echo bp_core_get_user_displayname($tools_facilities_template->tool_facility->uid);
}

function bp_tools_facilities_content() {
    echo bp_tools_facilities_get_content();
}

function bp_tools_facilities_get_content() {
    global $tools_facilities_template;
    return $tools_facilities_template->tool_facility->description;
}

function bp_tools_facilities_owner_permalink($userd_id = 0) {
    echo bp_tools_facilities_get_owner_permalink($userd_id);
}

function bp_tools_facilities_get_owner_permalink($userd_id = 0) {
    global $tools_facilities_template;
    if (!$userd_id)
        $userd_id = $tools_facilities_template->tool_facility->uid;

    return bp_core_get_user_domain($userd_id);
}

function bp_tools_facilities_is_owner() {
    echo bp_tools_facilities_get_is_owner();
}

function bp_tools_facilities_get_is_owner() {
    global $tools_facilities_template;
    return (bp_loggedin_user_id() == $tools_facilities_template->tool_facility->uid);
}

function bp_is_tool_facility_admin_page() {
    if (bp_is_single_item() && bp_is_tool_facility_component() && bp_is_current_action('admin'))
        return true;

    return false;
}

function bp_is_tool_facility_admin_screen($slug) {
    if (!bp_is_tool_facility_component() || !bp_is_current_action('admin'))
        return false;

    if (bp_is_action_variable($slug))
        return true;
    return false;
}

function bp_get_tool_facility_current_admin_tab() {
    if (bp_is_tool_facility_component() && bp_is_current_action('admin')) {
        $tab = bp_action_variable(0);
    } else {
        $tab = '';
    }

    return apply_filters('bp_get_current_group_admin_tab', $tab);
}

function bp_tool_facility_admin_tabs($tool_facility = false) {
    global $bp, $tools_facilities_template;

    if (empty($tool_facility))
        $tool_facility = ( $tools_facilities_template->tool_facility ) ? $tools_facilities_template->tool_facility : $bp->tools_facilities->current_tool_facility;

    $current_tab = bp_get_tool_facility_current_admin_tab();

    if (bp_is_item_admin()) :
        ?>
        <li<?php if ('edit-details' == $current_tab || empty($current_tab)) : ?> class="current"<?php endif; ?>><a href="<?php echo trailingslashit(bp_get_tool_facility_permalink($tool_facility) . 'admin/edit-details') ?>"><?php _e('Details', 'buddypress'); ?></a></li>
    <?php endif; ?>
    <?php
    if (!bp_is_item_admin())
        return false;
    ?>
    <?php do_action('tools_facilities_admin_tabs', $current_tab, $tool_facility->slug) ?>
    <li<?php if ('delete-tool_facility' == $current_tab) : ?> class="current"<?php endif; ?>><a href="<?php echo trailingslashit(bp_get_tool_facility_permalink($tool_facility) . 'admin/delete-tool_facility') ?>"><?php _e('Delete', 'buddypress'); ?></a></li>

    <?php
}

function bp_tool_facility_admin_form_action($page = false) {
    echo bp_get_tool_facility_admin_form_action($page);
}

function bp_get_tool_facility_admin_form_action($page = false, $tool_facility = false) {
    global $bp;

    if (empty($tool_facility))
        $tool_facility = $bp->tools_facilities->current_tool_facility;

    if (empty($page))
        $page = bp_action_variable(0);

    return apply_filters('bp_tool_facility_admin_form_action', bp_get_tool_facility_permalink($tool_facility) . 'admin/' . $page);
}

function bp_get_tool_facility_permalink($tool_facility = false) {
    global $tools_facilities_template;

    if (empty($tool_facility))
        $tool_facility = & $tools_facilities_template->tool_facility;

    return apply_filters('bp_get_tool_facility_permalink', trailingslashit(bp_get_root_domain() . '/' . bp_get_tools_facilities_root_slug() . '/' . $tool_facility->slug . '/'));
}

//Return true only if the current tool_facility has sectors
function bp_tool_facility_has_sectors() {
    global $bp;
    return (!empty($bp->tools_facilities->current_tool_facility->sectors));
}

//Return true only if the current tool_facility and has subsectors
function bp_tool_facility_has_subsectors() {
    global $bp;
    return (!empty($bp->tools_facilities->current_tool_facility->subsectors));
}


//Offers Index Page - Search Form
function bp_directory_tools_facilities_search_form() {

    $default_search_value = bp_get_search_default_text('tools_facilities');
    $search_value = !empty($_REQUEST['s']) ? stripslashes($_REQUEST['s']) : $default_search_value;

    $search_form_html = '<form action="" method="get" id="search-tools_facilities-form"> 
        <span data-toggle="tooltip" data-placement="left" title="Fill in the description of the tool_facility you are looking for..." class="glyphicon glyphicon-question-sign"></span>
		<label style="vertical-align:middle"><input type="text" name="s" id="tools_facilities_search" placeholder="' . esc_attr($search_value) . '" /></label>
		<input type="submit" id="tools_facilities_search_submit" name="tools_facilities_search_submit" value="' . __('Search', 'buddypress') . '" />
	</form>';

    echo apply_filters('bp_directory_tools_facilities_search_form', $search_form_html);
}

/**
 * Is this page part of the Offer component?
 *
 * Having a special function just for this purpose makes our code more readable elsewhere, and also
 * allows us to place filter 'bp_is_tool_facility_component' for other components to interact with.
 *
 * @uses bp_is_current_component()
 * @uses apply_filters() to allow this value to be filtered
 * @return bool True if it's the example component, false otherwise
 */
function bp_is_tool_facility_component() {
    if (bp_is_current_component('tools_facilities'))
        return true;

    return false;
}

/**
 * Echo the component's slug
 */
function bp_tools_facilities_slug() {
    echo bp_get_tools_facilities_slug();
}

/**
 * Return the component's slug
 *
 * Having a template function for this purpose is not absolutely necessary, but it helps to
 * avoid too-frequent direct calls to the $bp global.
 *
 * @uses apply_filters() Filter 'bp_get_tools_facilities_slug' to change the output
 * @return str $example_slug The slug from $bp->example->slug, if it exists
 */
function bp_get_tools_facilities_slug() {
    global $bp;

    // Avoid PHP warnings, in case the value is not set for some reason
    $tools_facilities_slug = isset($bp->tools_facilities->slug) ? $bp->tools_facilities->slug : '';

    return apply_filters('bp_get_tools_facilities_slug', $tools_facilities_slug);
}

/**
 * Echo the component's root slug
 */
function bp_tools_facilities_root_slug() {
    echo bp_get_tools_facilities_root_slug();
}

/**
 * Return the component's root slug
 *
 * Having a template function for this purpose is not absolutely necessary, but it helps to
 * avoid too-frequent direct calls to the $bp global.
 * @uses apply_filters() Filter 'bp_get_tools_facilities_root_slug' to change the output
 * @return str $example_root_slug The slug from $bp->example->root_slug, if it exists
 */
function bp_get_tools_facilities_root_slug() {
    global $bp;

    // Avoid PHP warnings, in case the value is not set for some reason
    $example_root_slug = isset($bp->tools_facilities->root_slug) ? $bp->tools_facilities->root_slug : '';

    return apply_filters('bp_get_tools_facilities_root_slug', $example_root_slug);
}

//Return the number of total tools_facilities

function bp_total_tools_facilities_count() {
    echo bp_get_total_tools_facilities_count();
}

function bp_get_total_tools_facilities_count() {
    return apply_filters('bp_get_total_tools_facilities_count', tools_facilities_get_total_tools_facilities_count());
}

function bp_total_tools_facilities_count_for_user($user_id = 0) {
    echo bp_get_total_tools_facilities_count_for_user($user_id);
}

/* Return the number of tools_facilities that a member owns */

function bp_get_total_tools_facilities_count_for_user($user_id = 0) {
    return apply_filters('bp_get_total_tools_facilities_count_for_user', tools_facilities_total_tools_facilities_for_user($user_id), $user_id);
}

add_filter('bp_get_total_tools_facilities_count_for_user', 'bp_core_number_format');

/*
 *  Offers Template Class 
 * Used to hold all the results returned from DB based on current users' query
 */

class BP_Tools_Facilities_Template {

    var $current_tool_facility = -1;
    var $tool_facility_count;
    var $tools_facilities;
    var $tool_facility;
    var $in_the_loop;
    var $pag_page;
    var $pag_num;
    var $pag_links;
    var $total_tool_facility_count;
    var $single_tool_facility = false;
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

        if ('single-tool_facility' == $type) {
            /*
             * Catch Unsupported operation exception
             * TODO: Handle the exception
             */
            echo "<b>Unsupported operation exception</b><br>single-tool_facility";
            die();
        } else {
            
            //Store the tools_facilities of the user to an array()
            $this->tools_facilities = tools_facilities_get_tools_facilities(array(
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


        if ('single-tool_facility' == $type) {
            $this->single_tool_facility = true;
            $this->total_tool_facility_count = 1;
            $this->tool_facility_count = 1;
        } else {


            if (empty($max) || $max >= (int) $this->tools_facilities['total']) {
                $this->total_tool_facility_count = (int) $this->tools_facilities['total'];
            } else {
                $this->total_tool_facility_count = (int) $max;
            }

            $this->tools_facilities = $this->tools_facilities['tools_facilities'];

            if (!empty($max)) {
                if ($max >= count($this->tools_facilities)) {
                    $this->tool_facility_count = count($this->tools_facilities);
                } else {
                    $this->tool_facility_count = (int) $max;
                }
            } else {
                $this->tool_facility_count = count($this->tools_facilities);
            }
        }

        // Build pagination links
        if ((int) $this->total_tool_facility_count && (int) $this->pag_num) {
            $this->pag_links = paginate_links(array(
                'base' => add_query_arg(array($page_arg => '%#%', 'num' => $this->pag_num, 's' => $search_terms, 'sortby' => $this->sort_by, 'order' => $this->order)),
                'format' => '',
                'total' => ceil((int) $this->total_tool_facility_count / (int) $this->pag_num),
                'current' => $this->pag_page,
                'prev_text' => _x('&larr;', 'Offer pagination previous text', 'buddypress'),
                'next_text' => _x('&rarr;', 'Offer pagination next text', 'buddypress'),
                'mid_size' => 1
            ));
        }
    }

    function has_tools_facilities() {

        if ($this->tool_facility_count)
            return true;

        return false;
    }

    function next_tool_facility() {
        $this->current_tool_facility++;
        $this->tool_facility = $this->tools_facilities[$this->current_tool_facility];

        return $this->tool_facility;
    }

    function rewind_tools_facilities() {
        $this->current_tool_facility = -1;
        if ($this->tool_facility_count > 0) {
            $this->tool_facility = $this->tools_facilities[0];
        }
    }

    function tools_facilities() {
        if ($this->current_tool_facility + 1 < $this->tool_facility_count) {
            return true;
        } elseif ($this->current_tool_facility + 1 == $this->tool_facility_count) {
            do_action('tool_facility_loop_end');
            // Do some cleaning up after the loop
            $this->rewind_tools_facilities();
        }

        $this->in_the_loop = false;
        return false;
    }

    function the_tool_facility() {
        $this->in_the_loop = true;
        $this->tool_facility = $this->next_tool_facility();

        if ($this->single_tool_facility)
            $this->tool_facility = tools_facilities_get_tool_facility(array('tool_facility_id' => $this->tool_facility->tool_facility_id));

        if (0 == $this->current_tool_facility) // loop has just started
            do_action('tool_facility_loop_start');
    }

}

function bp_tools_facilities() {
    global $tools_facilities_template;
    return $tools_facilities_template->tools_facilities();
}

function bp_the_tool_facility() {
    global $tools_facilities_template;
    return $tools_facilities_template->the_tool_facility();
}

function bp_tool_facility_id($tool_facility = false) {
    echo bp_get_tool_facility_id($tool_facility);
}

function bp_get_tool_facility_id($tool_facility = false) {
    global $tools_facilities_template;

    if (empty($tool_facility))
        $tool_facility = & $tools_facilities_template->tool_facility;

    return apply_filters('bp_get_tool_facility_id', $tool_facility->id);
}

function bp_has_tools_facilities($args = '') {
    //print_r($args);
    global $tools_facilities_template, $bp;

    /*
     * Set the defaults based on the current page. Any of these will be overridden
     * if arguments are directly passed into the loop. Custom plugins should always
     * pass their parameters directly to the loop.
     */
    $slug = false;
    $type = '';
    $user_id = 0;
    $order = '';

    // User filtering - Offers Main Screen
    if (bp_displayed_user_id())
        $user_id = bp_displayed_user_id();


    // Proper handle the screen one presenting the tools_facilities
    // @todo What is $order? At some point it was removed incompletely?
    /* if (bp_is_current_action('screen-one')) {
      if ('most-popular' == $order) {
      $type = 'popular';
      } elseif ('alphabetically' == $order) {
      $type = 'alphabetical';
      }
      } elseif (isset($bp->tools_facilities->current_tool_facility->slug) && $bp->tools_facilities->current_tool_facility->slug) {
      $type = 'single-tool_facility';
      $slug = $bp->tools_facilities->current_tool_facility->slug;
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
        'slug' => $slug, // Pass an tool_facility slug to only return that tool_facility
        'search_terms' => '', // Pass search terms to return only matching tools_facilities
        'search_extras' => '',
    );


    $r = wp_parse_args($args, $defaults);

    //print_r($r);

    if (empty($r['search_terms'])) {
        if (isset($_REQUEST['tool_facility-filter-box']) && !empty($_REQUEST['tool_facility-filter-box']))
            $r['search_terms'] = $_REQUEST['tool_facility-filter-box'];
        elseif (isset($_REQUEST['s']) && !empty($_REQUEST['s']))
            $r['search_terms'] = $_REQUEST['s'];
        else
            $r['search_terms'] = false;
    }

    $tools_facilities_template = new BP_Tools_Facilities_Template(array(
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

    return apply_filters('bp_has_tools_facilities', $tools_facilities_template->has_tools_facilities(), $tools_facilities_template, $r);
}

function tools_facilities_get_tools_facilities($args = '') {
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

    $tools_facilities = BP_Patent_License::get(array(
                'type' => $r['type'],
                'order' => $r['order'],
                'orderby' => $r['orderby'],
                'per_page' => $r['per_page'],
                'page' => $r['page'],
                'user_id' => $r['user_id'],
                'search_terms' => $r['search_terms'],
                'search_extras' => $r['search_extras'],
    ));


    return apply_filters_ref_array('tools_facilities_get_tools_facilities', array(&$tools_facilities, &$r));
}
?>



