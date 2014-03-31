<?php
/**
 *
 * @package CECommunity Offers Component
 * 
 */
?>

<?php do_action('bp_before_example_loop'); ?>
<?php if (bp_has_offers(bp_ajax_querystring('offers'))) : ?>
    <?php do_action('bp_before_directory_offers_list'); ?>

    <ul id="offers-list" class="item-list" role="main">

        <?php while (bp_offers()) : bp_the_offer(); ?>

            <li>
                <div class="item-avatar">               
                    <a href="<?php bp_offers_owner_permalink(); ?>"><?php bp_offers_owner_avatar('type=thumb&width=50&height=50'); ?></a>
                </div>

                <div class="item">
                    <div class="item-title">
                        <?php if (!bp_offers_get_is_owner()): ?>
                            Offer published by <a href="<?php bp_offers_owner_permalink(); ?>"><?php bp_offers_owner_name(); ?></a>
                            &nbsp;&nbsp;
                        <?php endif; ?>
                        View offer <a href="<?php bp_offer_permalink(); ?>">details</a>&nbsp;&nbsp; 
                        <span class="highlight label label-default"><?php bp_offer_type(); ?></span> 
                        <span class="activity label label-info"><?php printf(__('Posted: %s', 'firmasite'), bp_offer_get_posted_date()); ?></span></div>

                    <div class="item-content"> 
                        <p><b> <?php echo bp_offers_content(); ?></b></p>




                    </div>

                    <?php do_action('bp_directory_example_item'); ?>

                </div>

                <div class="clear"></div>
            </li>

        <?php endwhile; ?>

    </ul>

    <?php do_action('bp_after_directory_offers_list'); ?>

        <?php if (bp_offers_current_category() == 3) BP_Alert_Factory::getAlertBox(); ?>


    <div id="pag-bottom" class="pagination text-muted">

        <div class="pag-count" id="offers-dir-count-bottom">

            <?php bp_offers_pagination_count(); ?>

        </div>

        <div class="pagination-links lead" id="example-dir-pag-bottom">

            <?php bp_offers_pagination_links(); ?>

        </div>

    </div>

<?php else: ?>

    <div class="clearfix"></div><div id="message" class="info alert alert-info">
        <p><?php _e('There were no offers found.', 'firmasite'); ?></p>
    </div>

<?php endif; ?>

<?php do_action('bp_after_example_loop'); ?>
