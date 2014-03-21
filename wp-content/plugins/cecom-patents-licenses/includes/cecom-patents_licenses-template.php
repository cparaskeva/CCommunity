<?php

/**
 * In this file you should define template tag functions that end users can add to their template
 * files.
 *
 * It's a general practice in WordPress that template tag functions have two versions, one that
 * returns the requested value, and one that echoes the value of the first function. The naming
 * convention is usually something like 'bp_patents_licenses_get_item_name()' for the function that returns
 * the value, and 'bp_patents_licenses_item_name()' for the function that echoes.
 */
/*
 * Echo "Viewing x of y pages"
 */
function bp_patents_licenses_pagination_count() {
    echo bp_patents_licenses_get_pagination_count();
}

function bp_patents_licenses_get_pagination_count() {
    global $patents_licenses_template;

    $start_num = intval(( $patents_licenses_template->pag_page - 1 ) * $patents_licenses_template->pag_num) + 1;
    $from_num = bp_core_number_format($start_num);
    $to_num = bp_core_number_format(( $start_num + ( $patents_licenses_template->pag_num - 1 ) > $patents_licenses_template->total_patent_license_count ) ? $patents_licenses_template->total_patent_license_count : $start_num + ( $patents_licenses_template->pag_num - 1 ) );
    $total = bp_core_number_format($patents_licenses_template->total_patent_license_count);

    return apply_filters('bp_get_patents_licenses_pagination_count', sprintf(_n('Viewing patent_license %1$s to %2$s (of %3$s patent_license)', 'Viewing patent_license %1$s to %2$s (of %3$s patents_licenses)', $total, 'buddypress'), $from_num, $to_num, $total), $from_num, $to_num, $total);
}

function bp_patents_licenses_pagination_links() {
    echo bp_get_patents_licenses_pagination_links();
}

function bp_get_patents_licenses_pagination_links() {
    global $patents_licenses_template;

    return apply_filters('bp_get_patents_licenses_pagination_links', $patents_licenses_template->pag_links);
}

function bp_patents_licenses_owner_avatar($args = array()) {
    echo bp_patents_licenses_get_owner_avatar($args);
}

function bp_patents_licenses_get_owner_avatar($args = array()) {


    global $patents_licenses_template;
    $defaults = array(
        'item_id' => $patents_licenses_template->patent_license->uid,
        'object' => 'member'
    );

    $r = wp_parse_args($args, $defaults);

    return bp_core_fetch_avatar($r);
}

function bp_patent_license_permalink() {
    echo bp_patent_license_get_permalink();
}

function bp_patent_license_posted_date() {
    echo bp_patent_license_get_posted_date();
}

function bp_patent_license_get_posted_date() {
    global $bp, $patents_licenses_template;
    $posted_date = $bp->patents_licenses->current_patent_license->date;
    if (!$posted_date)
        $posted_date = $patents_licenses_template->patent_license->date;
    return ($posted_date ? substr($posted_date, 0, 10) : "Unknown");
}

function bp_patent_license_type() {
    echo bp_patent_license_get_type();
}

function bp_patent_license_get_type() {
    global $patents_licenses_template;
    return $patents_licenses_template->patent_license->tdesc;
}

function bp_patent_license_get_permalink() {
    global $patents_licenses_template, $bp;

    if ($patents_licenses_template->patent_license->id)
        $slug = $bp->patents_licenses->patents_licenses_subdomain . $patents_licenses_template->patent_license->id;
    else
        $slug = $bp->patents_licenses->current_patent_license->slug;

    //return bloginfo("url") . "/" . $bp->patents_licenses->root_slug . "/" . $bp->patents_licenses->patents_licenses_subdomain . $patents_licenses_template->patent_license->id;
    return apply_filters("bp_patent_license_get_permalink", trailingslashit(bp_get_root_domain() . "/" . $bp->patents_licenses->root_slug . "/" . $slug));
}

function bp_patents_licenses_owner_name() {
    echo bp_patents_licenses_get_owner_name();
}

function bp_patents_licenses_get_owner_name() {
    global $patents_licenses_template;
    echo bp_core_get_user_displayname($patents_licenses_template->patent_license->uid);
}

function bp_patents_licenses_content() {
    echo bp_patents_licenses_get_content();
}

function bp_patents_licenses_get_content() {
    global $patents_licenses_template;
    return $patents_licenses_template->patent_license->description;
}

function bp_patents_licenses_owner_permalink($userd_id = 0) {
    echo bp_patents_licenses_get_owner_permalink($userd_id);
}

function bp_patents_licenses_get_owner_permalink($userd_id = 0) {
    global $patents_licenses_template;
    if (!$userd_id)
        $userd_id = $patents_licenses_template->patent_license->uid;

    return bp_core_get_user_domain($userd_id);
}

function bp_patents_licenses_is_owner() {
    echo bp_patents_licenses_get_is_owner();
}

function bp_patents_licenses_get_is_owner() {
    global $patents_licenses_template;
    return (bp_loggedin_user_id() == $patents_licenses_template->patent_license->uid);
}

function bp_is_patent_license_admin_page() {
    if (bp_is_single_item() && bp_is_patent_license_component() && bp_is_current_action('admin'))
        return true;

    return false;
}

function bp_is_patent_license_admin_screen($slug) {
    if (!bp_is_patent_license_component() || !bp_is_current_action('admin'))
        return false;

    if (bp_is_action_variable($slug))
        return true;
    return false;
}

function bp_get_patent_license_current_admin_tab() {
    if (bp_is_patent_license_component() && bp_is_current_action('admin')) {
        $tab = bp_action_variable(0);
    } else {
        $tab = '';
    }

    return apply_filters('bp_get_current_group_admin_tab', $tab);
}

function bp_patent_license_admin_tabs($patent_license = false) {
    global $bp, $patents_licenses_template;

    if (empty($patent_license))
        $patent_license = ( $patents_licenses_template->patent_license ) ? $patents_licenses_template->patent_license : $bp->patents_licenses->current_patent_license;

    $current_tab = bp_get_patent_license_current_admin_tab();

    if (bp_is_item_admin()) :
        ?>
        <li<?php if ('edit-details' == $current_tab || empty($current_tab)) : ?> class="current"<?php endif; ?>><a href="<?php echo trailingslashit(bp_get_patent_license_permalink($patent_license) . 'admin/edit-details') ?>"><?php _e('Details', 'buddypress'); ?></a></li>
    <?php endif; ?>
    <?php
    if (!bp_is_item_admin())
        return false;
    ?>
    <?php do_action('patents_licenses_admin_tabs', $current_tab, $patent_license->slug) ?>
    <li<?php if ('delete-patent_license' == $current_tab) : ?> class="current"<?php endif; ?>><a href="<?php echo trailingslashit(bp_get_patent_license_permalink($patent_license) . 'admin/delete-patent_license') ?>"><?php _e('Delete', 'buddypress'); ?></a></li>

    <?php
}

function bp_patent_license_admin_form_action($page = false) {
    echo bp_get_patent_license_admin_form_action($page);
}

function bp_get_patent_license_admin_form_action($page = false, $patent_license = false) {
    global $bp;

    if (empty($patent_license))
        $patent_license = $bp->patents_licenses->current_patent_license;

    if (empty($page))
        $page = bp_action_variable(0);

    return apply_filters('bp_patent_license_admin_form_action', bp_get_patent_license_permalink($patent_license) . 'admin/' . $page);
}

function bp_get_patent_license_permalink($patent_license = false) {
    global $patents_licenses_template;

    if (empty($patent_license))
        $patent_license = & $patents_licenses_template->patent_license;

    return apply_filters('bp_get_patent_license_permalink', trailingslashit(bp_get_root_domain() . '/' . bp_get_patents_licenses_root_slug() . '/' . $patent_license->slug . '/'));
}

//Return true only if the current patent_license has sectors
function bp_patent_license_has_sectors() {
    global $bp;
    return (!empty($bp->patents_licenses->current_patent_license->sectors));
}

//Return true only if the current patent_license and has subsectors
function bp_patent_license_has_subsectors() {
    global $bp;
    return (!empty($bp->patents_licenses->current_patent_license->subsectors));
}


//Offers Index Page - Search Form
function bp_directory_patents_licenses_search_form() {

    $default_search_value = bp_get_search_default_text('patents_licenses');
    $search_value = !empty($_REQUEST['s']) ? stripslashes($_REQUEST['s']) : $default_search_value;

    $search_form_html = '<form action="" method="get" id="search-patents_licenses-form"> 
        <span data-toggle="tooltip" data-placement="left" title="Fill in the description of the patent_license you are looking for..." class="glyphicon glyphicon-question-sign"></span>
		<label style="vertical-align:middle"><input type="text" name="s" id="patents_licenses_search" placeholder="' . esc_attr($search_value) . '" /></label>
		<input type="submit" id="patents_licenses_search_submit" name="patents_licenses_search_submit" value="' . __('Search', 'buddypress') . '" />
	</form>';

    echo apply_filters('bp_directory_patents_licenses_search_form', $search_form_html);
}

/**
 * Is this page part of the Offer component?
 *
 * Having a special function just for this purpose makes our code more readable elsewhere, and also
 * allows us to place filter 'bp_is_patent_license_component' for other components to interact with.
 *
 * @uses bp_is_current_component()
 * @uses apply_filters() to allow this value to be filtered
 * @return bool True if it's the example component, false otherwise
 */
function bp_is_patent_license_component() {
    if (bp_is_current_component('patents_licenses'))
        return true;

    return false;
}

/**
 * Echo the component's slug
 */
function bp_patents_licenses_slug() {
    echo bp_get_patents_licenses_slug();
}

/**
 * Return the component's slug
 *
 * Having a template function for this purpose is not absolutely necessary, but it helps to
 * avoid too-frequent direct calls to the $bp global.
 *
 * @uses apply_filters() Filter 'bp_get_patents_licenses_slug' to change the output
 * @return str $example_slug The slug from $bp->example->slug, if it exists
 */
function bp_get_patents_licenses_slug() {
    global $bp;

    // Avoid PHP warnings, in case the value is not set for some reason
    $patents_licenses_slug = isset($bp->patents_licenses->slug) ? $bp->patents_licenses->slug : '';

    return apply_filters('bp_get_patents_licenses_slug', $patents_licenses_slug);
}

/**
 * Echo the component's root slug
 */
function bp_patents_licenses_root_slug() {
    echo bp_get_patents_licenses_root_slug();
}

/**
 * Return the component's root slug
 *
 * Having a template function for this purpose is not absolutely necessary, but it helps to
 * avoid too-frequent direct calls to the $bp global.
 * @uses apply_filters() Filter 'bp_get_patents_licenses_root_slug' to change the output
 * @return str $example_root_slug The slug from $bp->example->root_slug, if it exists
 */
function bp_get_patents_licenses_root_slug() {
    global $bp;

    // Avoid PHP warnings, in case the value is not set for some reason
    $example_root_slug = isset($bp->patents_licenses->root_slug) ? $bp->patents_licenses->root_slug : '';

    return apply_filters('bp_get_patents_licenses_root_slug', $example_root_slug);
}

//Return the number of total patents_licenses

function bp_total_patents_licenses_count() {
    echo bp_get_total_patents_licenses_count();
}

function bp_get_total_patents_licenses_count() {
    return apply_filters('bp_get_total_patents_licenses_count', patents_licenses_get_total_patents_licenses_count());
}

function bp_total_patents_licenses_count_for_user($user_id = 0) {
    echo bp_get_total_patents_licenses_count_for_user($user_id);
}

/* Return the number of patents_licenses that a member owns */

function bp_get_total_patents_licenses_count_for_user($user_id = 0) {
    return apply_filters('bp_get_total_patents_licenses_count_for_user', patents_licenses_total_patents_licenses_for_user($user_id), $user_id);
}

add_filter('bp_get_total_patents_licenses_count_for_user', 'bp_core_number_format');

/*
 *  Offers Template Class 
 * Used to hold all the results returned from DB based on current users' query
 */

class BP_Patents_Licenses_Template {

    var $current_patent_license = -1;
    var $patent_license_count;
    var $patents_licenses;
    var $patent_license;
    var $in_the_loop;
    var $pag_page;
    var $pag_num;
    var $pag_links;
    var $total_patent_license_count;
    var $single_patent_license = false;
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

        if ('single-patent_license' == $type) {
            /*
             * Catch Unsupported operation exception
             * TODO: Handle the exception
             */
            echo "<b>Unsupported operation exception</b><br>single-patent_license";
            die();
        } else {
            
            //Store the patents_licenses of the user to an array()
            $this->patents_licenses = patents_licenses_get_patents_licenses(array(
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


        if ('single-patent_license' == $type) {
            $this->single_patent_license = true;
            $this->total_patent_license_count = 1;
            $this->patent_license_count = 1;
        } else {


            if (empty($max) || $max >= (int) $this->patents_licenses['total']) {
                $this->total_patent_license_count = (int) $this->patents_licenses['total'];
            } else {
                $this->total_patent_license_count = (int) $max;
            }

            $this->patents_licenses = $this->patents_licenses['patents_licenses'];

            if (!empty($max)) {
                if ($max >= count($this->patents_licenses)) {
                    $this->patent_license_count = count($this->patents_licenses);
                } else {
                    $this->patent_license_count = (int) $max;
                }
            } else {
                $this->patent_license_count = count($this->patents_licenses);
            }
        }

        // Build pagination links
        if ((int) $this->total_patent_license_count && (int) $this->pag_num) {
            $this->pag_links = paginate_links(array(
                'base' => add_query_arg(array($page_arg => '%#%', 'num' => $this->pag_num, 's' => $search_terms, 'sortby' => $this->sort_by, 'order' => $this->order)),
                'format' => '',
                'total' => ceil((int) $this->total_patent_license_count / (int) $this->pag_num),
                'current' => $this->pag_page,
                'prev_text' => _x('&larr;', 'Offer pagination previous text', 'buddypress'),
                'next_text' => _x('&rarr;', 'Offer pagination next text', 'buddypress'),
                'mid_size' => 1
            ));
        }
    }

    function has_patents_licenses() {

        if ($this->patent_license_count)
            return true;

        return false;
    }

    function next_patent_license() {
        $this->current_patent_license++;
        $this->patent_license = $this->patents_licenses[$this->current_patent_license];

        return $this->patent_license;
    }

    function rewind_patents_licenses() {
        $this->current_patent_license = -1;
        if ($this->patent_license_count > 0) {
            $this->patent_license = $this->patents_licenses[0];
        }
    }

    function patents_licenses() {
        if ($this->current_patent_license + 1 < $this->patent_license_count) {
            return true;
        } elseif ($this->current_patent_license + 1 == $this->patent_license_count) {
            do_action('patent_license_loop_end');
            // Do some cleaning up after the loop
            $this->rewind_patents_licenses();
        }

        $this->in_the_loop = false;
        return false;
    }

    function the_patent_license() {
        $this->in_the_loop = true;
        $this->patent_license = $this->next_patent_license();

        if ($this->single_patent_license)
            $this->patent_license = patents_licenses_get_patent_license(array('patent_license_id' => $this->patent_license->patent_license_id));

        if (0 == $this->current_patent_license) // loop has just started
            do_action('patent_license_loop_start');
    }

}

function bp_patents_licenses() {
    global $patents_licenses_template;
    return $patents_licenses_template->patents_licenses();
}

function bp_the_patent_license() {
    global $patents_licenses_template;
    return $patents_licenses_template->the_patent_license();
}

function bp_patent_license_id($patent_license = false) {
    echo bp_get_patent_license_id($patent_license);
}

function bp_get_patent_license_id($patent_license = false) {
    global $patents_licenses_template;

    if (empty($patent_license))
        $patent_license = & $patents_licenses_template->patent_license;

    return apply_filters('bp_get_patent_license_id', $patent_license->id);
}

function bp_has_patents_licenses($args = '') {
    //print_r($args);
    global $patents_licenses_template, $bp;

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


    // Proper handle the screen one presenting the patents_licenses
    // @todo What is $order? At some point it was removed incompletely?
    /* if (bp_is_current_action('screen-one')) {
      if ('most-popular' == $order) {
      $type = 'popular';
      } elseif ('alphabetically' == $order) {
      $type = 'alphabetical';
      }
      } elseif (isset($bp->patents_licenses->current_patent_license->slug) && $bp->patents_licenses->current_patent_license->slug) {
      $type = 'single-patent_license';
      $slug = $bp->patents_licenses->current_patent_license->slug;
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
        'slug' => $slug, // Pass an patent_license slug to only return that patent_license
        'search_terms' => '', // Pass search terms to return only matching patents_licenses
        'search_extras' => '',
    );


    $r = wp_parse_args($args, $defaults);

    //print_r($r);

    if (empty($r['search_terms'])) {
        if (isset($_REQUEST['patent_license-filter-box']) && !empty($_REQUEST['patent_license-filter-box']))
            $r['search_terms'] = $_REQUEST['patent_license-filter-box'];
        elseif (isset($_REQUEST['s']) && !empty($_REQUEST['s']))
            $r['search_terms'] = $_REQUEST['s'];
        else
            $r['search_terms'] = false;
    }

    $patents_licenses_template = new BP_Patents_Licenses_Template(array(
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

    return apply_filters('bp_has_patents_licenses', $patents_licenses_template->has_patents_licenses(), $patents_licenses_template, $r);
}

function patents_licenses_get_patents_licenses($args = '') {
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

    $patents_licenses = BP_Patent_License::get(array(
                'type' => $r['type'],
                'order' => $r['order'],
                'orderby' => $r['orderby'],
                'per_page' => $r['per_page'],
                'page' => $r['page'],
                'user_id' => $r['user_id'],
                'search_terms' => $r['search_terms'],
                'search_extras' => $r['search_extras'],
    ));


    return apply_filters_ref_array('patents_licenses_get_patents_licenses', array(&$patents_licenses, &$r));
}
?>



