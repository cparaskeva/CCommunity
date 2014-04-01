<?php
/**
 *
 * @package CECommunity Patents_Licenses Component
 * 
 */
?>

<?php if (bp_has_tools_facilities(bp_ajax_querystring('tools_facilities'))) : ?>
    <?php do_action('bp_before_directory_tools_facilities_list'); ?>

    <ul id="tools_facilities-list" class="item-list" role="main">

        <?php while (bp_tools_facilities()) : bp_the_tool_facility(); ?>

            <li>
                <div class="item-avatar">
                    <?php $organisation = bp_tools_facilities_get_organization(); ?>
                    <a href="<?php echo bp_group_permalink() . $organisation['slug'] ?>"><?php echo bp_core_fetch_avatar('item_id=' . $organisation['id'] . '&type=thumb&width=50&height=50&object=group'); ?></a>                   
                 <!--  <a href="<?php //bp_tools_facilities_owner_permalink();   ?>"><?php //bp_tools_facilities_owner_avatar('type=thumb&width=50&height=50');   ?></a>-->
                </div>

                <div class="item">
                    <div class="item-title">
                        <?php if (!bp_tools_facilities_get_is_owner()): ?>
                            Offer published by <a href="<?php echo bp_group_permalink() . $organisation['slug'] ?>"><?php echo $organisation['name']; ?></a>
            <!-- <a href="<?php //bp_tools_facilities_owner_permalink();  ?>"><?php //bp_tools_facilities_owner_name();  ?></a> -->
                            &nbsp;&nbsp;
                        <?php endif; ?>
                        View tool & facility <a href="<?php bp_tool_facility_permalink(); ?>">details</a>&nbsp;&nbsp; 
                        <!-- <span class="highlight label label-default"><?php //bp_tool_facility_type();     ?></span> -->
                        <span class="activity label label-info"><?php printf(__('Posted: %s', 'firmasite'), bp_tool_facility_get_posted_date()); ?></span></div>

                    <div class="item-content"> 
                        <p><b> <?php echo bp_tools_facilities_content(); ?></b></p>

                    </div>

                </div>

                <div class="clear"></div>
            </li>

        <?php endwhile; ?>

    </ul>


    <div id="pag-bottom" class="pagination text-muted">

        <div class="pag-count" id="tools_facilities-dir-count-bottom">

            <?php bp_tools_facilities_pagination_count(); ?>

        </div>

        <div class="pagination-links lead" id="example-dir-pag-bottom">

            <?php bp_tools_facilities_pagination_links(); ?>

        </div>

    </div>

<?php else: ?>

    <div class="clearfix"></div><div id="message" class="info alert alert-info">
        <p><?php _e('There were no tools/facilities found.', 'firmasite'); ?></p>
    </div>

<?php endif; ?>

