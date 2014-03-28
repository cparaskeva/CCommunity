<?php
/**
 * BuddyPress - Groups Loop
 *
 * Querystring is set via AJAX in _inc/ajax.php - bp_dtheme_object_filter()
 *
 * @package BuddyPress
 * @subpackage bp-default
 */
?>

<?php do_action('bp_before_groups_loop'); ?>

<?php if (bp_has_groups(bp_ajax_querystring('groups'))) : ?>

    <?php do_action('bp_before_directory_groups_list'); ?>

    <ul id="groups-list" class="item-list" role="main">

    <?php while (bp_groups()) : bp_the_group(); ?>

            <li>
                <div class="item-avatar">
                    <a href="<?php bp_group_permalink(); ?>"><?php bp_group_avatar('type=thumb&width=50&height=50'); ?></a>
                </div>

                <div class="action pull-right">

        <?php do_action('bp_directory_groups_actions'); ?>

                    <div class="meta">

        <?php bp_group_type(); ?> / <?php bp_group_member_count(); ?>

                    </div>

                </div>

                <div class="item">
                    <div class="item-title"><a href="<?php bp_group_permalink(); ?>"><?php bp_group_name(); ?></a></div>
                    <div class="item-meta"><span class="activity label label-info"><?php printf(__('active %s', 'firmasite'), bp_get_group_last_active()); ?></span></div>

                    <div class="item-desc"><?php bp_group_description_excerpt(); ?></div>

        <?php do_action('bp_directory_groups_item'); ?>

                </div>


                <div class="clear"></div>
            </li>

    <?php endwhile; ?>

    </ul>

    <?php do_action('bp_after_directory_groups_list'); ?>


    <?php //BP_Alert_Factory::isAlertPermited(1, $_POST['search_extras']) ?>

    <div id="pag-bottom" class="pagination text-muted">

        <div class="pag-count" id="group-dir-count-bottom">

    <?php bp_groups_pagination_count(); ?>

        </div>

        <div class="pagination-links lead" id="group-dir-pag-bottom">

    <?php bp_groups_pagination_links(); ?>

        </div>

    </div>

<?php else: ?>

    <div class="clearfix"></div><div id="message" class="info alert alert-info">
        <p><?php _e('There were no organisations found.', 'firmasite'); ?></p>
    </div>

<?php endif; ?>

<?php do_action('bp_after_groups_loop'); ?>
