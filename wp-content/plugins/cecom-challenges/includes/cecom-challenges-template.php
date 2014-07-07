<?php

/**
 * In this file you should define template tag functions that end users can add to their template
 * files.
 *
 * It's a general practice in WordPress that template tag functions have two versions, one that
 * returns the requested value, and one that echoes the value of the first function. The naming
 * convention is usually something like 'bp_challenges_get_item_name()' for the function that returns
 * the value, and 'bp_challenges_item_name()' for the function that echoes.
 */
/*
 * Echo "Viewing x of y pages"
 */
function bp_challenges_pagination_count() {
    echo bp_challenges_get_pagination_count();
}

function bp_challenges_get_pagination_count() {
    global $challenges_template;

    $start_num = intval(( $challenges_template->pag_page - 1 ) * $challenges_template->pag_num) + 1;
    $from_num = bp_core_number_format($start_num);
    $to_num = bp_core_number_format(( $start_num + ( $challenges_template->pag_num - 1 ) > $challenges_template->total_challenge_count ) ? $challenges_template->total_challenge_count : $start_num + ( $challenges_template->pag_num - 1 ) );
    $total = bp_core_number_format($challenges_template->total_challenge_count);

    return apply_filters('bp_get_challenges_pagination_count', sprintf(_n('Viewing Challenge %1$s to %2$s (of %3$s Challenges)', 'Viewing Challenge %1$s to %2$s (of %3$s Challenges)', $total, 'buddypress'), $from_num, $to_num, $total), $from_num, $to_num, $total);
}

function bp_challenges_pagination_links() {
    echo bp_get_challenges_pagination_links();
}

function bp_get_challenges_pagination_links() {
    global $challenges_template;

    return apply_filters('bp_get_challenges_pagination_links', $challenges_template->pag_links);
}

function bp_challenges_owner_avatar($args = array()) {
    echo bp_challenges_get_owner_avatar($args);
}

function bp_challenges_get_owner_avatar($args = array()) {


    global $challenges_template;
    $defaults = array(
        'item_id' => $challenges_template->challenge->uid,
        'object' => 'member'
    );

    $r = wp_parse_args($args, $defaults);

    return bp_core_fetch_avatar($r);
}

function bp_challenge_permalink() {
    echo bp_challenge_get_permalink();
}

function bp_challenge_posted_date() {
    echo bp_challenge_get_posted_date();
}

function bp_challenge_get_posted_date() {
    global $bp, $challenges_template;
    $posted_date = $bp->challenges->current_challenge->date;
    if (!$posted_date)
        $posted_date = $challenges_template->challenge->date;
    return ($posted_date ? substr($posted_date, 0, 10) : "Unknown");
}

function bp_challenge_deadline() {
    echo bp_challenge_get_deadline();
}

function bp_challenge_get_deadline() {
    global $challenges_template;
    return $challenges_template->challenge->deadline;
}

function bp_challenge_get_permalink() {
    global $challenges_template, $bp;

    if ($challenges_template->challenge->id)
        $slug = $bp->challenges->challenges_subdomain . $challenges_template->challenge->id;
    else
        $slug = $bp->challenges->current_challenge->slug;

    //return bloginfo("url") . "/" . $bp->challenges->root_slug . "/" . $bp->challenges->challenges_subdomain . $challenges_template->challenge->id;
    return apply_filters("bp_challenge_get_permalink", trailingslashit(bp_get_root_domain() . "/" . $bp->challenges->root_slug . "/" . $slug));
}

function bp_challenges_owner_name() {
    echo bp_challenges_get_owner_name();
}

function bp_challenges_get_owner_name() {
    global $challenges_template;
    echo bp_core_get_user_displayname($challenges_template->challenge->uid);
}

function bp_challenges_content() {
    echo bp_challenges_get_content();
}

function bp_challenges_get_content() {
    global $challenges_template;
    return $challenges_template->challenge->description;
}

function bp_challenges_owner_permalink($userd_id = 0) {
    echo bp_challenges_get_owner_permalink($userd_id);
}

function bp_challenges_get_owner_permalink($userd_id = 0) {
    global $challenges_template;
    if (!$userd_id)
        $userd_id = $challenges_template->challenge->uid;

    return bp_core_get_user_domain($userd_id);
}

function bp_challenges_get_organization() {
    global $challenges_template, $bp;
    $group_id = ($challenges_template->challenge ) ? $challenges_template->challenge->gid : $bp->challenges->current_challenge->gid;
    return CECOM_Organization::getOrganizationOfferDetails($group_id);
}

function bp_challenges_is_owner() {
    echo bp_challenges_get_is_owner();
}

function bp_challenges_get_is_owner() {
    global $challenges_template;
    return (bp_loggedin_user_id() == $challenges_template->challenge->uid);
}

function bp_is_challenge_admin_page() {
    if (bp_is_single_item() && bp_is_challenge_component() && bp_is_current_action('admin'))
        return true;

    return false;
}

function bp_is_challenge_admin_screen($slug) {
    if (!bp_is_challenge_component() || !bp_is_current_action('admin'))
        return false;

    if (bp_is_action_variable($slug))
        return true;
    return false;
}

function bp_get_challenge_current_admin_tab() {
    if (bp_is_challenge_component() && bp_is_current_action('admin')) {
        $tab = bp_action_variable(0);
    } else {
        $tab = '';
    }

    return apply_filters('bp_get_current_group_admin_tab', $tab);
}

function bp_challenge_admin_tabs($challenge = false) {
    global $bp, $challenges_template;

    if (empty($challenge))
        $challenge = ( $challenges_template->challenge ) ? $challenges_template->challenge : $bp->challenges->current_challenge;

    $current_tab = bp_get_challenge_current_admin_tab();

    if (bp_is_item_admin()) :
        ?>
        <li style="font-size:200%" <?php if ('edit-details' == $current_tab) : ?> class="current"<?php endif; ?>><a href="<?php echo trailingslashit(bp_get_challenge_permalink($challenge) . 'admin/edit-details') ?>"><?php _e('Edit', 'buddypress'); ?></a></li>
    <?php endif; ?>
    <?php
    if (!bp_is_item_admin())
        return false;
    ?>
    <?php do_action('challenges_admin_tabs', $current_tab, $challenge->slug) ?>
    <li style="font-size:200%" <?php if ('delete-challenge' == $current_tab) : ?> class="current"<?php endif; ?>><a href="<?php echo trailingslashit(bp_get_challenge_permalink($challenge) . 'admin/delete-challenge') ?>"><?php _e('Delete', 'buddypress'); ?></a></li>

    <?php
}

function bp_challenge_admin_form_action($page = false) {
    echo bp_get_challenge_admin_form_action($page);
}

function bp_get_challenge_admin_form_action($page = false, $challenge = false) {
    global $bp;

    if (empty($challenge))
        $challenge = $bp->challenges->current_challenge;

    if (empty($page))
        $page = bp_action_variable(0);

    return apply_filters('bp_challenge_admin_form_action', bp_get_challenge_permalink($challenge) . 'admin/' . $page);
}

function bp_get_challenge_permalink($challenge = false) {
    global $challenges_template;

    if (empty($challenge))
        $challenge = & $challenges_template->challenge;

    return apply_filters('bp_get_challenge_permalink', trailingslashit(bp_get_root_domain() . '/' . bp_get_challenges_root_slug() . '/' . $challenge->slug . '/'));
}

//Return true only if the current challenge has sectors
function bp_challenge_has_sectors() {
    global $bp;
    return (!empty($bp->challenges->current_challenge->sectors));
}

//Return true only if the current challenge and has subsectors
function bp_challenge_has_subsectors() {
    global $bp;
    return (!empty($bp->challenges->current_challenge->subsectors));
}

//Offers Index Page - Search Form
function bp_directory_challenges_search_form() {

    $default_search_value = bp_get_search_default_text('challenges');
    $search_value = !empty($_REQUEST['s']) ? stripslashes($_REQUEST['s']) : $default_search_value;

    $search_form_html = '<form action="" method="get" id="search-challenges-form"> 
        
		<label style="vertical-align:middle"><input type="text" name="s" id="challenges_search" placeholder="' . esc_attr($search_value) . '" /></label>
		<input type="submit" id="challenges_search_submit" name="challenges_search_submit" value="' . __('Search', 'buddypress') . '" />
	</form>';

    echo apply_filters('bp_directory_challenges_search_form', $search_form_html);
}

/**
 * Is this page part of the Offer component?
 *
 * Having a special function just for this purpose makes our code more readable elsewhere, and also
 * allows us to place filter 'bp_is_challenge_component' for other components to interact with.
 *
 * @uses bp_is_current_component()
 * @uses apply_filters() to allow this value to be filtered
 * @return bool True if it's the example component, false otherwise
 */
function bp_is_challenge_component() {
    if (bp_is_current_component('challenges'))
        return true;

    return false;
}

/**
 * Echo the component's slug
 */
function bp_challenges_slug() {
    echo bp_get_challenges_slug();
}

/**
 * Return the component's slug
 *
 * Having a template function for this purpose is not absolutely necessary, but it helps to
 * avoid too-frequent direct calls to the $bp global.
 *
 * @uses apply_filters() Filter 'bp_get_challenges_slug' to change the output
 * @return str $example_slug The slug from $bp->example->slug, if it exists
 */
function bp_get_challenges_slug() {
    global $bp;

    // Avoid PHP warnings, in case the value is not set for some reason
    $challenges_slug = isset($bp->challenges->slug) ? $bp->challenges->slug : '';

    return apply_filters('bp_get_challenges_slug', $challenges_slug);
}

/**
 * Echo the component's root slug
 */
function bp_challenges_root_slug() {
    echo bp_get_challenges_root_slug();
}

/**
 * Return the component's root slug
 *
 * Having a template function for this purpose is not absolutely necessary, but it helps to
 * avoid too-frequent direct calls to the $bp global.
 * @uses apply_filters() Filter 'bp_get_challenges_root_slug' to change the output
 * @return str $example_root_slug The slug from $bp->example->root_slug, if it exists
 */
function bp_get_challenges_root_slug() {
    global $bp;

    // Avoid PHP warnings, in case the value is not set for some reason
    $example_root_slug = isset($bp->challenges->root_slug) ? $bp->challenges->root_slug : '';

    return apply_filters('bp_get_challenges_root_slug', $example_root_slug);
}

//Return the number of total challenges

function bp_total_challenges_count() {
    echo bp_get_total_challenges_count();
}

function bp_get_total_challenges_count() {
    return apply_filters('bp_get_total_challenges_count', challenges_get_total_challenges_count());
}

function bp_total_challenges_count_for_user($user_id = 0) {
    echo bp_get_total_challenges_count_for_user($user_id);
}

/* Return the number of challenges that a member owns */

function bp_get_total_challenges_count_for_user($user_id = 0) {
    return apply_filters('bp_get_total_challenges_count_for_user', challenges_total_challenges_for_user($user_id), $user_id);
}

add_filter('bp_get_total_challenges_count_for_user', 'bp_core_number_format');

/*
 *  Offers Template Class 
 * Used to hold all the results returned from DB based on current users' query
 */

class BP_Challenges_Template {

    var $current_challenge = -1;
    var $challenge_count;
    var $challenges;
    var $challenge;
    var $in_the_loop;
    var $pag_page;
    var $pag_num;
    var $pag_links;
    var $total_challenge_count;
    var $single_challenge = false;
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

        if ('single-challenge' == $type) {
            /*
             * Catch Unsupported operation exception
             * TODO: Handle the exception
             */
            echo "<b>Unsupported operation exception</b><br>single-challenge";
            die();
        } else {

            //Store the challenges of the user to an array()
            $this->challenges = challenges_get_challenges(array(
                'type' => $type,
                'order' => $order,
                'orderby' => $orderby,
                'per_page' => $this->pag_num,
                'page' => $this->pag_page,
                'search_terms' => $search_terms,
                'search_extras' => $search_extras,
                'user_id' => $user_id,
                'group_id' => $r['group_id'],
            ));
        }


        if ('single-challenge' == $type) {
            $this->single_challenge = true;
            $this->total_challenge_count = 1;
            $this->challenge_count = 1;
        } else {


            if (empty($max) || $max >= (int) $this->challenges['total']) {
                $this->total_challenge_count = (int) $this->challenges['total'];
            } else {
                $this->total_challenge_count = (int) $max;
            }

            $this->challenges = $this->challenges['challenges'];

            if (!empty($max)) {
                if ($max >= count($this->challenges)) {
                    $this->challenge_count = count($this->challenges);
                } else {
                    $this->challenge_count = (int) $max;
                }
            } else {
                $this->challenge_count = count($this->challenges);
            }
        }

        // Build pagination links
        if ((int) $this->total_challenge_count && (int) $this->pag_num) {
            $this->pag_links = paginate_links(array(
                'base' => add_query_arg(array($page_arg => '%#%', 'num' => $this->pag_num, 's' => $search_terms, 'sortby' => $this->sort_by, 'order' => $this->order)),
                'format' => '',
                'total' => ceil((int) $this->total_challenge_count / (int) $this->pag_num),
                'current' => $this->pag_page,
                'prev_text' => _x('&larr;', 'Offer pagination previous text', 'buddypress'),
                'next_text' => _x('&rarr;', 'Offer pagination next text', 'buddypress'),
                'mid_size' => 1
            ));
        }
    }

    function has_challenges() {

        if ($this->challenge_count)
            return true;

        return false;
    }

    function next_challenge() {
        $this->current_challenge++;
        $this->challenge = $this->challenges[$this->current_challenge];

        return $this->challenge;
    }

    function rewind_challenges() {
        $this->current_challenge = -1;
        if ($this->challenge_count > 0) {
            $this->challenge = $this->challenges[0];
        }
    }

    function challenges() {
        if ($this->current_challenge + 1 < $this->challenge_count) {
            return true;
        } elseif ($this->current_challenge + 1 == $this->challenge_count) {
            do_action('challenge_loop_end');
            // Do some cleaning up after the loop
            $this->rewind_challenges();
        }

        $this->in_the_loop = false;
        return false;
    }

    function the_challenge() {
        $this->in_the_loop = true;
        $this->challenge = $this->next_challenge();

        if ($this->single_challenge)
            $this->challenge = challenges_get_challenge(array('challenge_id' => $this->challenge->challenge_id));

        if (0 == $this->current_challenge) // loop has just started
            do_action('challenge_loop_start');
    }

}

function bp_challenges() {
    global $challenges_template;
    return $challenges_template->challenges();
}

function bp_the_challenge() {
    global $challenges_template;
    return $challenges_template->the_challenge();
}

function bp_challenge_id($challenge = false) {
    echo bp_get_challenge_id($challenge);
}

function bp_get_challenge_id($challenge = false) {
    global $challenges_template;

    if (empty($challenge))
        $challenge = & $challenges_template->challenge;

    return apply_filters('bp_get_challenge_id', $challenge->id);
}

function bp_has_challenges($args = '') {
    //print_r($args);
    global $challenges_template, $bp;

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


    // Proper handle the screen one presenting the challenges
    // @todo What is $order? At some point it was removed incompletely?
    /* if (bp_is_current_action('screen-one')) {
      if ('most-popular' == $order) {
      $type = 'popular';
      } elseif ('alphabetically' == $order) {
      $type = 'alphabetical';
      }
      } elseif (isset($bp->challenges->current_challenge->slug) && $bp->challenges->current_challenge->slug) {
      $type = 'single-challenge';
      $slug = $bp->challenges->current_challenge->slug;
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
        'slug' => $slug, // Pass an challenge slug to only return that challenge
        'search_terms' => '', // Pass search terms to return only matching challenges
        'search_extras' => '',
        'group_id' => 0,
    );


    $r = wp_parse_args($args, $defaults);

    //print_r($r);

    if (empty($r['search_terms'])) {
        if (isset($_REQUEST['challenge-filter-box']) && !empty($_REQUEST['challenge-filter-box']))
            $r['search_terms'] = $_REQUEST['challenge-filter-box'];
        elseif (isset($_REQUEST['s']) && !empty($_REQUEST['s']))
            $r['search_terms'] = $_REQUEST['s'];
        else
            $r['search_terms'] = false;
    }

    $challenges_template = new BP_Challenges_Template(array(
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
    ));

    return apply_filters('bp_has_challenges', $challenges_template->has_challenges(), $challenges_template, $r);
}

function challenges_get_challenges($args = '') {
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
    );

    $r = wp_parse_args($args, $defaults);

    if ($r['group_id']) {
        $challenges = BP_Challenge::get_organization_challenges(array(
                    'per_page' => 20, // The number of results to return per page
                    'page' => 1, // The page to return if limiting per page
                    'group_id' => $r['group_id'],
        ));
    } else {

        $challenges = BP_Challenge::get(array(
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
    return apply_filters_ref_array('challenges_get_challenges', array(&$challenges, &$r));
}
?>



