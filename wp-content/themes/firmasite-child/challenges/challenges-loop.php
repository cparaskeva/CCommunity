<?php
/**
 *
 * @package CECommunity Challenges Component
 * 
 */
?>

<?php if (bp_has_challenges(bp_ajax_querystring('challenges'))) : ?>
    <?php do_action('bp_before_directory_challenges_list'); ?>

    <ul id="challenges-list" class="item-list" role="main">

        <?php while (bp_challenges()) : bp_the_challenge(); ?>

            <li>
                <div class="item-avatar">   
                    <?php $organisation = bp_challenges_get_organization(); ?>
                    <a href="<?php echo bp_group_permalink() . $organisation['slug'] ?>"><?php echo bp_core_fetch_avatar('item_id=' . $organisation['id'] . '&type=thumb&width=50&height=50&object=group'); ?></a>                   
                  <!-- <a href="<?php //bp_challenges_owner_permalink();  ?>"><?php //bp_challenges_owner_avatar('type=thumb&width=50&height=50');  ?></a> -->
                </div>

                <div class="item">
                    <div class="item-title">
                        <?php if (!bp_challenges_get_is_owner()): ?>
                            Challenge published by <a href="<?php echo bp_group_permalink() . $organisation['slug'] ?>"><?php echo $organisation['name']; ?></a>
            <!-- <a href="<?php //bp_challenges_owner_permalink();  ?>"><?php //bp_challenges_owner_name();  ?></a>-->
                            &nbsp;&nbsp;
                        <?php endif; ?>
                        View challenge <a href="<?php bp_challenge_permalink(); ?>">details</a>&nbsp;&nbsp; 
                        <span class="highlight label label-default"><?php echo "Deadline:"; bp_challenge_deadline(); ?></span> 
                        <span class="activity label label-info"><?php printf(__('Posted: %s', 'firmasite'), bp_challenge_get_posted_date()); ?></span></div>

                    <div class="item-content"> 
                        <p><b> <?php echo bp_challenges_content(); ?></b></p>

                    </div>

                </div>

                <div class="clear"></div>
            </li>

        <?php endwhile; ?>

    </ul>

    <?php BP_Alert_Factory::getAlertBox(); ?>

    <div id="pag-bottom" class="pagination text-muted">

        <div class="pag-count" id="challenges-dir-count-bottom">

            <?php bp_challenges_pagination_count(); ?>

        </div>

        <div class="pagination-links lead" id="example-dir-pag-bottom">

            <?php bp_challenges_pagination_links(); ?>

        </div>

    </div>

<?php else: ?>

    <div class="clearfix"></div><div id="message" class="info alert alert-info">
        <p><?php _e('There were no challenges found.', 'firmasite'); ?></p>
    </div>

<?php endif; ?>

