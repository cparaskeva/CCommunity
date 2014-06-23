<?php

/* This class contains all custom-made filters for various actions */

/**
 * Some WP filters you may want to use:
 *  - wp_filter_kses() VERY IMPORTANT see below.
 *  - wptexturize()
 *  - convert_smilies()
 *  - convert_chars()
 *  - wpautop()
 *  - stripslashes_deep()
 *  - make_clickable()
 */
/**
 * --- NOTE ----
 * It's very very important that we use the wp_filter_kses() function to filter all
 * input AND output in our plugin. This will stop users adding malicious scripts and other
 * bad things onto any page.
 */
/**
 * In all your template tags that output data, you should have an apply_filters() call, you can
 * then use those filters to automatically add the wp_filter_kses() call.
 * The third parameter "1" adds the highest priority to the filter call.
 */
add_filter('bp_alerts_get_item_name', 'wp_filter_kses', 1);


/* Used before storing the data of an alert to be created , in the DB*/
add_filter('bp_alerts_data_before_save', 'wp_filter_kses', 1);
?>