<?php
/**
 *
 * @package CECommunity Patents_Licenses Component
 * 
 */
?>

<?php if (bp_has_patents_licenses(bp_ajax_querystring('patents_licenses'))) : ?>
    <?php do_action('bp_before_directory_patents_licenses_list'); ?>

    <ul id="patents_licenses-list" class="item-list" role="main">

        <?php while (bp_patents_licenses()) : bp_the_patent_license(); ?>

            <li>
                <div class="item-avatar">               
                    <a href="<?php bp_patents_licenses_owner_permalink(); ?>"><?php bp_patents_licenses_owner_avatar('type=thumb&width=50&height=50'); ?></a>
                </div>

                <div class="item">
                    <div class="item-title">
                        <?php if (!bp_patents_licenses_get_is_owner()): ?>
                            Offer published by <a href="<?php bp_patents_licenses_owner_permalink(); ?>"><?php bp_patents_licenses_owner_name(); ?></a>
                            &nbsp;&nbsp;
                        <?php endif; ?>
                        View patent & license <a href="<?php bp_patent_license_permalink(); ?>">details</a>&nbsp;&nbsp; 
                        <span class="highlight label label-default"><?php bp_patent_license_type(); ?></span> 
                        <span class="activity label label-info"><?php printf(__('Posted: %s', 'firmasite'), bp_patent_license_get_posted_date()); ?></span></div>

                    <div class="item-content"> 
                        <p><b> <?php echo bp_patents_licenses_content(); ?></b></p>

                    </div>

                </div>

                <div class="clear"></div>
            </li>

        <?php endwhile; ?>

    </ul>

    <?php BP_Alert_Factory::getAlertBox(); ?>

    <div id="pag-bottom" class="pagination text-muted">

        <div class="pag-count" id="patents_licenses-dir-count-bottom">

            <?php bp_patents_licenses_pagination_count(); ?>

        </div>

        <div class="pagination-links lead" id="example-dir-pag-bottom">

            <?php bp_patents_licenses_pagination_links(); ?>

        </div>

    </div>

<?php else: ?>

    <div class="clearfix"></div><div id="message" class="info alert alert-info">
        <p><?php _e('There were no patents & licenses found.', 'firmasite'); ?></p>
    </div>

<?php endif; ?>

