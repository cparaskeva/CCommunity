<?php

/**
 * In this file you should define template tag functions that end users can add to their template
 * files.
 *
 * It's a general practice in WordPress that template tag functions have two versions, one that
 * returns the requested value, and one that echoes the value of the first function. The naming
 * convention is usually something like 'bp_offers_get_item_name()' for the function that returns
 * the value, and 'bp_offers_item_name()' for the function that echoes.
 */
/*
 * Echo "Viewing x of y pages"
 */
function bp_offers_pagination_count() {
    echo bp_offers_get_pagination_count();
}

function bp_offers_get_pagination_count() {
    global $offers_template;

    $start_num = intval(( $offers_template->pag_page - 1 ) * $offers_template->pag_num) + 1;
    $from_num = bp_core_number_format($start_num);
    $to_num = bp_core_number_format(( $start_num + ( $offers_template->pag_num - 1 ) > $offers_template->total_offer_count ) ? $offers_template->total_offer_count : $start_num + ( $offers_template->pag_num - 1 ) );
    $total = bp_core_number_format($offers_template->total_offer_count);

    return apply_filters('bp_get_offers_pagination_count', sprintf(_n('Viewing offer %1$s to %2$s (of %3$s offer)', 'Viewing offer %1$s to %2$s (of %3$s offers)', $total, 'buddypress'), $from_num, $to_num, $total), $from_num, $to_num, $total);
}

function bp_offers_pagination_links() {
    echo bp_get_offers_pagination_links();
}

function bp_get_offers_pagination_links() {
    global $offers_template;

    return apply_filters('bp_get_offers_pagination_links', $offers_template->pag_links);
}

function bp_offers_owner_avatar($args = array()) {
    echo bp_offers_get_owner_avatar($args);
}

function bp_offers_get_owner_avatar($args = array()) {


    global $offers_template;
    $defaults = array(
        'item_id' => $offers_template->offer->uid,
        'object' => 'member'
    );

    $r = wp_parse_args($args, $defaults);

    return bp_core_fetch_avatar($r);
}

function bp_offer_permalink() {
    echo bp_offer_get_permalink();
}

function bp_offer_posted_date() {
    echo bp_offer_get_posted_date();
}

function bp_offer_get_posted_date() {
    global $bp, $offers_template;
    $posted_date = $bp->offers->current_offer->date;
    if (!$posted_date)
        $posted_date = $offers_template->offer->date;
    return ($posted_date ? substr($posted_date, 0, 10) : "Unknown");
}

function bp_offer_type() {
    echo bp_offer_get_type();
}

function bp_offer_get_type() {
    global $offers_template;
    return $offers_template->offer->tdesc;
}

function bp_offer_get_permalink() {
    global $offers_template, $bp;

    if ($offers_template->offer->id)
        $slug = $bp->offers->offers_subdomain . $offers_template->offer->id;
    else
        $slug = $bp->offers->current_offer->slug;

    //return bloginfo("url") . "/" . $bp->offers->root_slug . "/" . $bp->offers->offers_subdomain . $offers_template->offer->id;
    return apply_filters("bp_offer_get_permalink", trailingslashit(bp_get_root_domain() . "/" . $bp->offers->root_slug . "/" . $slug));
}

function bp_offers_owner_name() {
    echo bp_offers_get_owner_name();
}

function bp_offers_get_owner_name() {
    global $offers_template;
    echo bp_core_get_user_displayname($offers_template->offer->uid);
}

function bp_offers_content() {
    echo bp_offers_get_content();
}

function bp_offers_get_content() {
    global $offers_template;
    return $offers_template->offer->description;
}

function bp_offers_owner_permalink($userd_id = 0) {
    echo bp_offers_get_owner_permalink($userd_id);
}

function bp_offers_get_owner_permalink($userd_id = 0) {
    global $offers_template;
    if (!$userd_id)
        $userd_id = $offers_template->offer->uid;

    return bp_core_get_user_domain($userd_id);
}

function bp_offers_get_organization() {
    global $offers_template, $bp;
    $group_id = ($offers_template->offer ) ? $offers_template->offer->gid : $bp->offers->current_offer->gid;
    return CECOM_Organization::getOrganizationOfferDetails($group_id);
}

function bp_offers_is_owner() {
    echo bp_offers_get_is_owner();
}

function bp_offers_get_is_owner() {
    global $offers_template;
    return (bp_loggedin_user_id() == $offers_template->offer->uid);
}

function bp_is_offer_admin_page() {
    if (bp_is_single_item() && bp_is_offer_component() && bp_is_current_action('admin'))
        return true;

    return false;
}

function bp_is_offer_admin_screen($slug) {
    if (!bp_is_offer_component() || !bp_is_current_action('admin'))
        return false;

    if (bp_is_action_variable($slug))
        return true;
    return false;
}

function bp_get_offer_current_admin_tab() {
    if (bp_is_offer_component() && bp_is_current_action('admin')) {
        $tab = bp_action_variable(0);
    } else {
        $tab = '';
    }

    return apply_filters('bp_get_current_group_admin_tab', $tab);
}

function bp_offer_admin_tabs($offer = false) {
    global $bp, $offers_template;

    if (empty($offer))
        $offer = ( $offers_template->offer ) ? $offers_template->offer : $bp->offers->current_offer;

    $current_tab = bp_get_offer_current_admin_tab();

    if (bp_is_item_admin()) :
        ?>
        <li style="font-size:200%" <?php if ('edit-details' == $current_tab ) : ?> class="current"<?php endif; ?>><a href="<?php echo trailingslashit(bp_get_offer_permalink($offer) . 'admin/edit-details') ?>"><?php _e('Edit', 'buddypress'); ?></a></li>
    <?php endif; ?>
    <?php
    if (!bp_is_item_admin())
        return false;
    ?>
    <?php do_action('offers_admin_tabs', $current_tab, $offer->slug) ?>
    <li style="font-size:200%" <?php if ('delete-offer' == $current_tab) : ?> class="current"<?php endif; ?>><a href="<?php echo trailingslashit(bp_get_offer_permalink($offer) . 'admin/delete-offer') ?>"><?php _e('Delete', 'buddypress'); ?></a></li>

    <?php
}

function bp_offer_admin_form_action($page = false) {
    echo bp_get_offer_admin_form_action($page);
}

function bp_get_offer_admin_form_action($page = false, $offer = false) {
    global $bp;

    if (empty($offer))
        $offer = $bp->offers->current_offer;

    if (empty($page))
        $page = bp_action_variable(0);

    return apply_filters('bp_offer_admin_form_action', bp_get_offer_permalink($offer) . 'admin/' . $page);
}

function bp_get_offer_permalink($offer = false) {
    global $offers_template;

    if (empty($offer))
        $offer = & $offers_template->offer;

    return apply_filters('bp_get_offer_permalink', trailingslashit(bp_get_root_domain() . '/' . bp_get_offers_root_slug() . '/' . $offer->slug . '/'));
}

//Return true only if the current offer is type of "funding offer" and has sectors
function bp_offer_has_sectors() {
    global $bp;
    return ($bp->offers->current_offer->type_id == 3 && !empty($bp->offers->current_offer->sectors));
}

//Offers Index Page - Search Form
function bp_directory_offers_search_form() {

    $default_search_value = bp_get_search_default_text('offers');
    $search_value = !empty($_REQUEST['s']) ? stripslashes($_REQUEST['s']) : $default_search_value;

    $search_form_html = '<form action="" method="get" id="search-offers-form"> 
       		<label style="margin-right:130px; margin-top:20px; width:220px;">Description keywords<input type="text" name="s" id="offers_search" placeholder="' . esc_attr($search_value) . '" /></label>
		<input type="submit" style="width:150px; height:120px; margin-top:28px; margin-right:30px;" id="offers_search_submit" name="offers_search_submit" value="' . __('Show results', 'buddypress') . '" />
	</form>';

    echo apply_filters('bp_directory_offers_search_form', $search_form_html);
}

/**
 * Is this page part of the Offer component?
 *
 * Having a special function just for this purpose makes our code more readable elsewhere, and also
 * allows us to place filter 'bp_is_offer_component' for other components to interact with.
 *
 * @uses bp_is_current_component()
 * @uses apply_filters() to allow this value to be filtered
 * @return bool True if it's the example component, false otherwise
 */
function bp_is_offer_component() {
    if (bp_is_current_component('offers'))
        return true;

    return false;
}

/**
 * Echo the component's slug
 */
function bp_offers_slug() {
    echo bp_get_offers_slug();
}

/**
 * Return the component's slug
 *
 * Having a template function for this purpose is not absolutely necessary, but it helps to
 * avoid too-frequent direct calls to the $bp global.
 *
 * @uses apply_filters() Filter 'bp_get_offers_slug' to change the output
 * @return str $example_slug The slug from $bp->example->slug, if it exists
 */
function bp_get_offers_slug() {
    global $bp;

    // Avoid PHP warnings, in case the value is not set for some reason
    $offers_slug = isset($bp->offers->slug) ? $bp->offers->slug : '';

    return apply_filters('bp_get_offers_slug', $offers_slug);
}

/**
 * Echo the component's root slug
 */
function bp_offers_root_slug() {
    echo bp_get_offers_root_slug();
}

/**
 * Return the component's root slug
 *
 * Having a template function for this purpose is not absolutely necessary, but it helps to
 * avoid too-frequent direct calls to the $bp global.
 * @uses apply_filters() Filter 'bp_get_offers_root_slug' to change the output
 * @return str $example_root_slug The slug from $bp->example->root_slug, if it exists
 */
function bp_get_offers_root_slug() {
    global $bp;

    // Avoid PHP warnings, in case the value is not set for some reason
    $example_root_slug = isset($bp->offers->root_slug) ? $bp->offers->root_slug : '';

    return apply_filters('bp_get_offers_root_slug', $example_root_slug);
}

//Return the number of total offers

function bp_total_offers_count() {
    echo bp_get_total_offers_count();
}

function bp_get_total_offers_count() {
    return apply_filters('bp_get_total_offers_count', offers_get_total_offers_count());
}

function bp_total_offers_count_for_user($user_id = 0) {
    echo bp_get_total_offers_count_for_user($user_id);
}

/* Return the number of offers that a member owns */

function bp_get_total_offers_count_for_user($user_id = 0) {
    return apply_filters('bp_get_total_offers_count_for_user', offers_total_offers_for_user($user_id), $user_id);
}

add_filter('bp_get_total_offers_count_for_user', 'bp_core_number_format');

/*
 *  Offers Template Class 
 * Used to hold all the results returned from DB based on current users' query
 */

class BP_Offers_Template {

    var $current_offer = -1;
    var $offer_count;
    var $offers;
    var $offer;
    var $in_the_loop;
    var $pag_page;
    var $pag_num;
    var $pag_links;
    var $total_offer_count;
    var $single_offer = false;
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
            'group_id' => $r['group_id'],
            'offer_type' => $r['offer_type']
        );

        $r = wp_parse_args($args, $defaults);
        extract($r);

        $this->pag_page = isset($_REQUEST[$page_arg]) ? intval($_REQUEST[$page_arg]) : $page;
        $this->pag_num = isset($_REQUEST['num']) ? intval($_REQUEST['num']) : $per_page;

        if ('single-offer' == $type) {
            /*
             * Catch Unsupported operation exception
             * TODO: Handle the exception
             */
            echo "<b>Unsupported operation exception</b><br>single-offer";
            die();
        } else {

            //Store the offers of the user to an array()
            $this->offers = offers_get_offers(array(
                'type' => $type,
                'order' => $order,
                'orderby' => $orderby,
                'per_page' => $this->pag_num,
                'page' => $this->pag_page,
                'search_terms' => $search_terms,
                'search_extras' => $search_extras,
                'user_id' => $user_id,
                'group_id' => $r['group_id'],
                'offer_type' => $r['offer_type']
            ));
        }


        if ('single-offer' == $type) {
            $this->single_offer = true;
            $this->total_offer_count = 1;
            $this->offer_count = 1;
        } else {


            if (empty($max) || $max >= (int) $this->offers['total']) {
                $this->total_offer_count = (int) $this->offers['total'];
            } else {
                $this->total_offer_count = (int) $max;
            }

            $this->offers = $this->offers['offers'];

            if (!empty($max)) {
                if ($max >= count($this->offers)) {
                    $this->offer_count = count($this->offers);
                } else {
                    $this->offer_count = (int) $max;
                }
            } else {
                $this->offer_count = count($this->offers);
            }
        }

        // Build pagination links
        if ((int) $this->total_offer_count && (int) $this->pag_num) {
            $this->pag_links = paginate_links(array(
                'base' => add_query_arg(array($page_arg => '%#%', 'num' => $this->pag_num, 's' => $search_terms, 'sortby' => $this->sort_by, 'order' => $this->order)),
                'format' => '',
                'total' => ceil((int) $this->total_offer_count / (int) $this->pag_num),
                'current' => $this->pag_page,
                'prev_text' => _x('&larr;', 'Offer pagination previous text', 'buddypress'),
                'next_text' => _x('&rarr;', 'Offer pagination next text', 'buddypress'),
                'mid_size' => 1
            ));
        }
    }

    function has_offers() {

        if ($this->offer_count)
            return true;

        return false;
    }

    function next_offer() {
        $this->current_offer++;
        $this->offer = $this->offers[$this->current_offer];

        return $this->offer;
    }

    function rewind_offers() {
        $this->current_offer = -1;
        if ($this->offer_count > 0) {
            $this->offer = $this->offers[0];
        }
    }

    function offers() {
        if ($this->current_offer + 1 < $this->offer_count) {
            return true;
        } elseif ($this->current_offer + 1 == $this->offer_count) {
            do_action('offer_loop_end');
            // Do some cleaning up after the loop
            $this->rewind_offers();
        }

        $this->in_the_loop = false;
        return false;
    }

    function the_offer() {
        $this->in_the_loop = true;
        $this->offer = $this->next_offer();

        if ($this->single_offer)
            $this->offer = offers_get_offer(array('offer_id' => $this->offer->offer_id));

        if (0 == $this->current_offer) // loop has just started
            do_action('offer_loop_start');
    }

}

function bp_offers() {
    global $offers_template;
    return $offers_template->offers();
}

function bp_the_offer() {
    global $offers_template;
    return $offers_template->the_offer();
}

function bp_offer_id($offer = false) {
    echo bp_get_offer_id($offer);
}

function bp_get_offer_id($offer = false) {
    global $offers_template;

    if (empty($offer))
        $offer = & $offers_template->offer;

    return apply_filters('bp_get_offer_id', $offer->id);
}

function bp_has_offers($args = '') {
    //print_r($args);
    global $offers_template, $bp;

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


    // Proper handle the screen one presenting the offers
    // @todo What is $order? At some point it was removed incompletely?
    /* if (bp_is_current_action('screen-one')) {
      if ('most-popular' == $order) {
      $type = 'popular';
      } elseif ('alphabetically' == $order) {
      $type = 'alphabetical';
      }
      } elseif (isset($bp->offers->current_offer->slug) && $bp->offers->current_offer->slug) {
      $type = 'single-offer';
      $slug = $bp->offers->current_offer->slug;
      } */

    $defaults = array(
        'type' => $type, // 'type' is an override for 'order' and 'orderby'. See docblock.
        'order' => 'DESC',
        'orderby' => 'newest',
        'page' => 1,
        'per_page' => 20,
        'max' => false,
        'page_arg' => 'offpage',
        'user_id' => $user_id, // Pass a user ID to limit to groups this user has joined
        'slug' => $slug, // Pass an offer slug to only return that offer
        'search_terms' => '', // Pass search terms to return only matching offers
        'search_extras' => '',
        'group_id' => 0,
        'offer_type' => 0
    );


    $r = wp_parse_args($args, $defaults);

    //print_r($r);

    if (empty($r['search_terms'])) {
        if (isset($_REQUEST['offer-filter-box']) && !empty($_REQUEST['offer-filter-box']))
            $r['search_terms'] = $_REQUEST['offer-filter-box'];
        elseif (isset($_REQUEST['s']) && !empty($_REQUEST['s']))
            $r['search_terms'] = $_REQUEST['s'];
        else
            $r['search_terms'] = false;
    }

    $offers_template = new BP_offers_Template(array(
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
        'group_id' => $r['group_id'],
        'offer_type' => $r['offer_type']
    ));

    return apply_filters('bp_has_offers', $offers_template->has_offers(), $offers_template, $r);
}

function offers_get_offers($args = '') {

    $defaults = array(
        'type' => false, // active, newest, alphabetical, random, popular, most-forum-topics or most-forum-posts
        'order' => 'DESC', // 'ASC' or 'DESC'
        'orderby' => 'newest', // date_created, last_activity, total_member_count, name, random
        'per_page' => 20, // The number of results to return per page
        'page' => 1, // The page to return if limiting per page
        'search_terms' => false, // Limit to groups that match these search terms
        'search_extras' => false,
        'user_id' => false, // Pass a user_id to limit to only groups that this user is a member of
        'group_id' => 0,
        'offer_type' => 0
    );

    $r = wp_parse_args($args, $defaults);

    if ($r['group_id'] && $r['offer_type']) {
        $offers = BP_Offer::get_organization_offers(array(
                    'per_page' => 20, // The number of results to return per page
                    'page' => 1, // The page to return if limiting per page
                    'group_id' => $r['group_id'],
                    'offer_type' => $r['offer_type']
        ));
    } else {

        $offers = BP_Offer::get(array(
                    'type' => $r['type'],
                    'order' => $r['order'],
                    'orderby' => $r['orderby'],
                    'per_page' => $r['per_page'],
                    'page' => $r['page'],
                    'user_id' => $r['user_id'],
                    'search_terms' => $r['search_terms'],
                    'search_extras' => $r['search_extras'],
        ));
    }
    return apply_filters_ref_array('offers_get_offers', array(&$offers, &$r));
}
?>



