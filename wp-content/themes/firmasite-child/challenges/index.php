<?php
/**
 * CECommunity - Challenges Directory
 *
 * @package CECommunity Patents_Licenses Component
 */
?>

<?php
global $firmasite_settings;
get_header('buddypress');

/* Import JS files */
wp_enqueue_script('bootstrapformhelpers');
wp_enqueue_script('bootstrap-multiselect');

/* Import CSS files */
wp_enqueue_style('bootstrapformhelpers-style');
wp_enqueue_style('bootstrap-multiselect-style');

global $bp;
?>


<div id="primary" class="content-area <?php echo $firmasite_settings["layout_primary_class"]; ?>">
    <div class="padder">

<?php do_action('bp_before_directory_challenges_page'); ?>

        <form action="" method="post" id="challenges-directory-form" class="dir-form">
            <h3  id="challenges-header" class="page-header"><?php _e('Challenges Directory ', 'firmasite'); ?></h3>

<?php do_action('bp_before_directory_challenges_content'); ?>

<?php do_action('template_notices'); ?>
            <!-- Quick solution to fix the selected tab using $_Cookie[scope]-->
            <div class="item-list-tabs tabs-top" role="navigation">
                <ul class="nav nav-pills">
                    <li  class="<?php echo ($_COOKIE['bp-challenges-scope'] == "all" || (empty($_COOKIE['bp-challenges-scope']) && empty($_COOKIE['bp-challenges-scope']) ) ? "selected" : "") ?>" id="challenges-all"><a href="<?php echo trailingslashit(bp_get_root_domain() . '/' . bp_get_challenges_root_slug()); ?>"><?php printf(__('All Challenges<span>%s</span>', 'buddypress'), bp_get_total_challenges_count()); ?></a></li>


<?php if (is_user_logged_in() && bp_get_total_challenges_count_for_user(bp_loggedin_user_id())) : ?>
                        <li class="<?php echo ($_COOKIE['bp-challenges-scope'] == "personal" ? "selected" : "") ?>" id="challenges-personal"><a href="<?php echo trailingslashit(bp_loggedin_user_domain() . bp_get_challenges_slug() . '/my-challenges'); ?>"><?php printf(__('My Challenges <span>%s</span>', 'firmasite'), bp_get_total_challenges_count_for_user(bp_loggedin_user_id())); ?></a></li>

                    <?php endif; ?>

<?php do_action('bp_challenges_directory_challenge_filter'); ?>

                </ul>
            </div><!-- .item-list-tabs -->
            <div id="challenge-dir-search" class="dir-search" role="search">

                <br>  <?php bp_directory_challenges_search_form(); ?> 

            </div><!-- #challenge-dir-search -->

            <!-- Include the UI for the search form -->
<?php include(get_stylesheet_directory() . "/challenges/search.php"); ?>





            <div class="item-list-tabs" id="subnav" role="navigation">
                <ul class="nav nav-pills">
<?php do_action('bp_challenges_directory_challenge_types'); ?>

                    <li id="challenges-order-select" class="last pull-right filter">
                        <label for="challenges-order-by"><?php _e('Order By:', 'firmasite'); ?></label>
                        <select id="challenges-order-by">
                            <option <?php echo ($_COOKIE['bp-challenges-filter'] == "newest" ? "selected='selected'" : "") ?> value="newest"><?php _e('Newly Created', 'firmasite'); ?></option>
                            <option <?php echo ($_COOKIE['bp-challenges-filter'] == "oldest" ? "selected='selected'" : "") ?> value="oldest"><?php _e('Oldest Created', 'firmasite'); ?></option>
                            <option <?php echo ($_COOKIE['bp-challenges-filter'] == "challengetype" ? "selected='selected'" : "") ?> value="challengetype"><?php _e('Deadline', 'firmasite'); ?></option> 

<?php do_action('bp_challenges_directory_order_options'); ?>

                        </select>
                    </li>
                </ul>
            </div><!-- .item-list-tabs -->

            <div id="challenges-dir-list" class="challenges dir-list">
<?php locate_template(array('/challenges/challenges-loop.php'), true); ?>
            </div><!-- #challenges-dir-list -->

                <?php do_action('bp_directory_challenges_content'); ?>

<?php wp_nonce_field('directory_challenges', '_wpnonce-challenges-filter'); ?>


            <?php do_action('bp_after_directory_challenges_content'); ?>

        </form><!-- #challenges-directory-form -->

            <?php do_action('bp_after_directory_challenges'); ?>

    </div><!-- .padder -->
</div><!-- #content -->

<?php do_action('bp_after_directory_challenges_page'); ?>

<?php get_sidebar('buddypress'); ?>
<?php get_footer('buddypress'); ?>