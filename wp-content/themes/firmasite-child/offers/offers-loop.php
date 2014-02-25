<?php
/**
 *
 * @package CECommunity Offers Component
 * 
 */
?>

<?php do_action('bp_before_example_loop'); ?>

<?php if (bp_has_offers(bp_ajax_querystring('offers'))) : ?>
    <br><br>

    <?php do_action('bp_before_directory_offers_list'); ?>

    <ul id="offers-list" class="item-list" role="main">

        <?php while (bp_offers()) : bp_the_offer(); ?>

            <li>
                <div class="item-avatar">               
                    <a href="<?php bp_offers_owner_permalink(); ?>"><?php bp_offers_owner_avatar('type=thumb&width=50&height=50'); ?></a>
                </div>

                <div class="item">
                    <div class="item-title">
                        Offer published by <a href="<?php bp_offers_owner_permalink(); ?>"><?php bp_offers_owner_name(); ?></a>

                        <?php //bp_offers_high_five_title() ?></div>

                    <div class="item-content"> 
                            <p><i><b> <?php echo bp_offers_content(); ?></b></i></p>




                    </div>

                    <?php do_action('bp_directory_example_item'); ?>

                </div>

                <div class="clear"></div>
            </li>

        <?php endwhile; ?>

    </ul>

    <?php do_action('bp_after_directory_offers_list'); ?>

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
