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

/**
 * Echo "Viewing x of y pages"
 *
 * @package BuddyPress_Skeleton_Component
 * @since 1.6
 */
function bp_offers_pagination_count() {
    echo bp_offers_get_pagination_count();
}

/**
 * Return "Viewing x of y pages"
 *
 * @package BuddyPress_Skeleton_Component
 * @since 1.6
 */
function bp_offers_get_pagination_count() {
    global $offers_template;

   $start_num = intval( ( $offers_template->pag_page - 1 ) * $offers_template->pag_num ) + 1;
		$from_num  = bp_core_number_format( $start_num );
		$to_num    = bp_core_number_format( ( $start_num + ( $offers_template->pag_num - 1 ) > $offers_template->total_offer_count ) ? $offers_template->total_offer_count : $start_num + ( $offers_template->pag_num - 1 ) );
		$total     = bp_core_number_format( $offers_template->total_offer_count );

		return apply_filters( 'bp_get_offers_pagination_count', sprintf( _n( 'Viewing offer %1$s to %2$s (of %3$s offer)', 'Viewing offer %1$s to %2$s (of %3$s offers)', $total, 'buddypress' ), $from_num, $to_num, $total ), $from_num, $to_num, $total );
}



function bp_offers_pagination_links() {
	echo bp_get_offers_pagination_links();
}
	function bp_get_offers_pagination_links() {
		global $offers_template;

		return apply_filters( 'bp_get_offers_pagination_links', $offers_template->pag_links );
	}





/**
 * Echo the high-fiver avatar (post author)
 *
 * @package BuddyPress_Skeleton_Component
 * @since 1.6
 */
function bp_offers_high_fiver_avatar($args = array()) {
    echo bp_offers_get_high_fiver_avatar($args);
}

/**
 * Return the high-fiver avatar (the post author)
 *
 * @package BuddyPress_Skeleton_Component
 * @since 1.6
 *
 * @param mixed $args Accepts WP style arguments - either a string of URL params, or an array
 * @return str The HTML for a user avatar
 */
function bp_offers_get_high_fiver_avatar($args = array()) {
    $defaults = array(
        'item_id' => get_the_author_meta('ID'),
        'object' => 'user'
    );

    $r = wp_parse_args($args, $defaults);

    return bp_core_fetch_avatar($r);
}

/**
 * Echo the "title" of the high-five
 *
 * @package BuddyPress_Skeleton_Component
 * @since 1.6
 */
function bp_offers_high_five_title() {
    echo bp_offers_get_high_five_title();
}

/**
 * Return the "title" of the high-five
 *
 * We'll assemble the title out of the available information. This way, we can insert
 * fancy stuff link links, and secondary avatars.
 *
 * @package BuddyPress_Skeleton_Component
 * @since 1.6
 */
function bp_offers_get_high_five_title() {
    // First, set up the high fiver's information
    $high_fiver_link = bp_core_get_userlink(get_the_author_meta('ID'));

    // Next, get the information for the high five recipient
    $recipient_id = get_post_meta(get_the_ID(), 'bp_offers_recipient_id', true);
    $recipient_link = bp_core_get_userlink($recipient_id);

    // Use sprintf() to make a translatable message
    $title = sprintf(__('%1$s gave %2$s a high-five!', 'bp-example'), $high_fiver_link, $recipient_link);

    return apply_filters('bp_offers_get_high_five_title', $title, $high_fiver_link, $recipient_link);
}

/**
 * Is this page part of the Example component?
 *
 * Having a special function just for this purpose makes our code more readable elsewhere, and also
 * allows us to place filter 'bp_is_offer_component' for other components to interact with.
 *
 * @package BuddyPress_Skeleton_Component
 * @since 1.6
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
 *
 * @package BuddyPress_Skeleton_Component
 * @since 1.6
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
 * @package BuddyPress_Skeleton_Component
 * @since 1.6
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
 *
 * @package BuddyPress_Skeleton_Component
 * @since 1.6
 */
function bp_offers_root_slug() {
    echo bp_get_offers_root_slug();
}

/**
 * Return the component's root slug
 *
 * Having a template function for this purpose is not absolutely necessary, but it helps to
 * avoid too-frequent direct calls to the $bp global.
 *
 * @package BuddyPress_Skeleton_Component
 * @since 1.6
 *
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
 * Echo the total of all high-fives across the site
 *
 * @package BuddyPress_Skeleton_Component
 * @since 1.6
 */
function bp_offers_total_high_five_count() {
    echo bp_offers_get_total_high_five_count();
}

/**
 * Return the total of all high-fives across the site
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
function bp_offers_get_total_high_five_count() {
    $high_fives = new BP_Offer();
    $high_fives->get();

    return apply_filters('bp_offers_get_total_high_five_count', $high_fives->query->found_posts, $high_fives);
}

/**
 * Echo the total of all high-fives given to a particular user
 *
 * @package BuddyPress_Skeleton_Component
 * @since 1.6
 */
function bp_offers_total_high_five_count_for_user($user_id = false) {
    echo bp_offers_get_total_high_five_count_for_user($user_id = false);
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

function bp_total_offers_count_for_user($user_id = 0) {
    echo bp_get_total_offers_count_for_user($user_id);
}

/* Return the number of offers that a member owns */

function bp_get_total_offers_count_for_user($user_id = 0) {
    return apply_filters('bp_get_total_offers_count_for_user', offers_total_offers_for_user($user_id), $user_id);
}

add_filter('bp_get_total_offers_count_for_user', 'bp_core_number_format');

//***************************************************


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

        // Backward compatibility with old method of passing arguments
        if (!is_array($args) || func_num_args() > 1) {
            _deprecated_argument(__METHOD__, '1.7', sprintf(__('Arguments passed to %1$s should be in an associative array. See the inline documentation at %2$s for more details.', 'buddypress'), __METHOD__, __FILE__));

            $old_args_keys = array(
                0 => 'user_id',
                1 => 'type',
                2 => 'page',
                3 => 'per_page',
                4 => 'max',
                5 => 'slug',
                6 => 'search_terms',
                7 => 'populate_extras',
                8 => 'include',
                9 => 'exclude',
                10 => 'show_hidden',
                11 => 'page_arg',
            );

            $func_args = func_get_args();
            $args = bp_core_parse_args_array($old_args_keys, $func_args);
        }

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


        if ('invites' == $type) {
            $this->offers = offers_get_invites_for_user($user_id, $this->pag_num, $this->pag_page, $exclude);
        } else if ('single-offer' == $type) {
            $offer = new stdClass;
            $offer->offer_id = BP_Groups_Group::get_id_from_slug($slug);
            $this->offers = array($offer);
        } else {
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

        //print_r($this->offers);die();
        

        if ('invites' == $type) {
            $this->total_offer_count = (int) $this->offers['total'];
            $this->offer_count = (int) $this->offers['total'];
            $this->offers = $this->offers['groups'];
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

            $this->offers = $this->offers['groups'];

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

    /*     * *
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


    // Type
    // @todo What is $order? At some point it was removed incompletely?
    if (bp_is_current_action('my-groups')) {
        if ('most-popular' == $order) {
            $type = 'popular';
        } elseif ('alphabetically' == $order) {
            $type = 'alphabetical';
        }
    } elseif (bp_is_current_action('invites')) {
        $type = 'invites';
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
        'page_arg' => 'ofpage', // See https://buddypress.trac.wordpress.org/ticket/3679
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

    $temp =array(
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
    );

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
        'meta_query' => false, // Filter by groupmeta. See WP_Meta_Query for syntax
        'show_hidden' => false, // Show hidden groups to non-admins
        'per_page' => 20, // The number of results to return per page
        'page' => 1, // The page to return if limiting per page
    );

    $r = wp_parse_args($args, $defaults);



    $offers = BP_Groups_Group::get(array(
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



