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

function bp_offers_details_url() {
    echo bp_offers_get_details_url();
}

function bp_offers_get_details_url() {
    global $offers_template, $bp;

    return bloginfo("url") . "/" . $bp->offers->root_slug . "/" . $bp->offers->offers_subdomain . $offers_template->offer->id;
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

        <li<?php if ('edit-details' == $current_tab || empty($current_tab)) : ?> class="current"<?php endif; ?>><a href="<?php echo trailingslashit(bp_get_offer_permalink($offer) . 'admin/edit-details') ?>"><?php _e('Details', 'buddypress'); ?></a></li>

    <?php endif; ?>

    <?php
    if (!bp_is_item_admin())
        return false;
    ?>

    <?php do_action('offers_admin_tabs', $current_tab, $offer->slug) ?>

    <li<?php if ('delete-offer' == $current_tab) : ?> class="current"<?php endif; ?>><a href="<?php echo trailingslashit(bp_get_offer_permalink($offer) . 'admin/delete-offer') ?>"><?php _e('Delete', 'buddypress'); ?></a></li>

    <?php
}


function bp_offer_admin_form_action( $page = false ) {
	echo bp_get_offer_admin_form_action( $page );
}
	function bp_get_offer_admin_form_action( $page = false, $offer = false ) {
		global $bp;

		if ( empty( $offer ) )
			$offer =$bp->offers->current_offer;

		if ( empty( $page ) )
			$page = bp_action_variable( 0 );

                return apply_filters( 'bp_offer_admin_form_action', bp_get_offer_permalink($offer). 'admin/' . $page );
	}




function bp_get_offer_permalink($offer = false) {
    global $offers_template;

    if (empty($offer))
        $offer = & $offers_template->offer;

    return apply_filters('bp_get_offer_permalink', trailingslashit(bp_get_root_domain() . '/' . bp_get_offers_root_slug(). '/' . $offer->slug . '/'));
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
/* function bp_is_offer_component() {
  $is_example_component = bp_is_current_component( 'offers' );

  return apply_filters( 'bp_is_offer_component', $is_example_component );
  } */
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
    $example_slug = isset($bp->offers->slug) ? $bp->offers->slug : '';

    return apply_filters('bp_get_offers_slug', $example_slug);
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

/**
 * Return the total of all high-fives given to a particular user
 *
 * The most straightforward way to get a post count is to run a WP_Query. In your own plugin
 * you might consider storing data like this with update_option(), incrementing each time
 * a new item is published.
 *
 * @package BuddyPress_Skeleton_Component
 * @since 1.6
 *
 * @return int
 */
function bp_offers_get_total_high_five_count_for_user($user_id = false) {
    // If no explicit user id is passed, fall back on the loggedin user
    if (!$user_id) {
        $user_id = bp_loggedin_user_id();
    }

    if (!$user_id) {
        return 0;
    }

    $high_fives = new BP_Offer();
    $high_fives->get(array('recipient_id' => $user_id));

    return apply_filters('bp_offers_get_total_high_five_count', $high_fives->query->found_posts, $high_fives);
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
            'per_page' => 1,
            'max' => false,
            'show_hidden' => false,
            'page_arg' => 'ofpage',
            'user_id' => 0,
            'slug' => false,
            'include' => false,
            'exclude' => false,
            'search_terms' => '',
            'meta_query' => false,
        );

        $r = wp_parse_args($args, $defaults);
        extract($r);

        $this->pag_page = isset($_REQUEST[$page_arg]) ? intval($_REQUEST[$page_arg]) : $page;
        $this->pag_num = isset($_REQUEST['num']) ? intval($_REQUEST['num']) : $per_page;

        if ('single-offer' == $type) {
            $offer = new stdClass;
            $offer->offer_id = BP_Groups_Group::get_id_from_slug($slug);
            $this->offers = array($offer);
        } else {

            //Store the offers of the user to an array()
            $this->offers = offers_get_offers(array(
                'type' => $type,
                'order' => $order,
                'orderby' => $orderby,
                'per_page' => $this->pag_num,
                'page' => $this->pag_page,
                'user_id' => $user_id,
                'search_terms' => $search_terms,
                'meta_query' => $meta_query,
                'include' => $include,
                'exclude' => $exclude,
                'show_hidden' => $show_hidden
            ));
        }


        if ('invites' == $type) {
            $this->total_offer_count = (int) $this->offers['total'];
            $this->offer_count = (int) $this->offers['total'];
            $this->offers = $this->offers['offers'];
        } else if ('single-offer' == $type) {
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

function bp_offer_is_visible($offer = false) {
    global $offers_template;

    if (bp_current_user_can('bp_moderate'))
        return true;

    if (empty($offer))
        $offer = & $offers_template->offer;

    if ('public' == $offer->status) {
        return true;
    } else {
        if (offers_is_user_member(bp_loggedin_user_id(), $offer->id)) {
            return true;
        }
    }

    return false;
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



    // User filtering
    if (bp_displayed_user_id())
        $user_id = bp_displayed_user_id();




    // Proper handle the screen one presenting the offers
    // @todo What is $order? At some point it was removed incompletely?
    if (bp_is_current_action('screen-one')) {

        if ('most-popular' == $order) {
            $type = 'popular';
        } elseif ('alphabetically' == $order) {
            $type = 'alphabetical';
        }
    } elseif (isset($bp->groups->current_group->slug) && $bp->groups->current_group->slug) {
        $type = 'single-group';
        $slug = $bp->groups->current_group->slug;
    }

    $defaults = array(
        'type' => $type, // 'type' is an override for 'order' and 'orderby'. See docblock.
        'order' => 'DESC',
        'orderby' => 'last_activity',
        'page' => 1,
        'per_page' => 2,
        'max' => false,
        'show_hidden' => false,
        'page_arg' => 'offpage',
        'user_id' => $user_id, // Pass a user ID to limit to groups this user has joined
        'slug' => $slug, // Pass a group slug to only return that group
        'search_terms' => '', // Pass search terms to return only matching groups
        'meta_query' => false, // Filter by groupmeta. See WP_Meta_Query for format
        'include' => false, // Pass comma separated list or array of group ID's to return only these groups
        'exclude' => false, // Pass comma separated list or array of group ID's to exclude these groups
    );


    $r = wp_parse_args($args, $defaults);

    if (empty($r['search_terms'])) {
        if (isset($_REQUEST['group-filter-box']) && !empty($_REQUEST['group-filter-box']))
            $r['search_terms'] = $_REQUEST['group-filter-box'];
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
        'show_hidden' => $r['show_hidden'],
        'page_arg' => $r['page_arg'],
        'user_id' => (int) $r['user_id'],
        'slug' => $r['slug'],
        'search_terms' => $r['search_terms'],
        'meta_query' => $r['meta_query'],
        'include' => $r['include'],
        'exclude' => $r['exclude'],
    ));


    return apply_filters('bp_has_offers', $offers_template->has_offers(), $offers_template, $r);
}

function offers_get_offers($args = '') {
    $defaults = array(
        'type' => false, // active, newest, alphabetical, random, popular, most-forum-topics or most-forum-posts
        'order' => 'DESC', // 'ASC' or 'DESC'
        'orderby' => 'date_created', // date_created, last_activity, total_member_count, name, random
        'user_id' => false, // Pass a user_id to limit to only groups that this user is a member of
        'include' => false, // Only include these specific groups (group_ids)
        'exclude' => false, // Do not include these specific groups (group_ids)
        'search_terms' => false, // Limit to groups that match these search terms
        'meta_query' => false, // Filter by groupmeta. 
        'show_hidden' => false, // Show hidden groups to non-admins
        'per_page' => 20, // The number of results to return per page
        'page' => 1, // The page to return if limiting per page
    );

    $r = wp_parse_args($args, $defaults);



    $offers = //BP_Groups_Group
            BP_Offer::get(array(
                'type' => $r['type'],
                'user_id' => $r['user_id'],
                'include' => $r['include'],
                'exclude' => $r['exclude'],
                'search_terms' => $r['search_terms'],
                'meta_query' => $r['meta_query'],
                'show_hidden' => $r['show_hidden'],
                'per_page' => $r['per_page'],
                'page' => $r['page'],
                'populate_extras' => $r['populate_extras'],
                'order' => $r['order'],
                'orderby' => $r['orderby'],
    ));


    return apply_filters_ref_array('offers_get_offers', array(&$offers, &$r));
}
?>



