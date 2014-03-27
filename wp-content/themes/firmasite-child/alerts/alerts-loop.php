<?php
/**
 *
 * @package CECommunity Patents_Licenses Component
 * 
 */
?>

<?php if (bp_has_alerts(bp_ajax_querystring('alerts'))) : ?>
    <?php do_action('bp_before_directory_alerts_list'); ?>

    <ul id="alerts-list" class="item-list" role="main">

        <?php while (bp_alerts()) : bp_the_alert(); ?>

            <li>
                <div class="item-avatar">  
                    <a href="http://localhost/cecommunity/members/cparaskevas/" class="thumbnail pull-left">
                        <img width="50" height="50" alt="Avatar Image" class="avatar" src="<?php echo get_stylesheet_directory_uri() . '/assets/img/alert.png'; ?>"></a>



        <!--   <a href="<?php //bp_alerts_owner_permalink();  ?>"><?php //bp_alerts_owner_avatar('type=thumb&width=50&height=50');  ?></a> -->
                </div>

                <div class="item">
                    <div class="item-title">
                        <?php if (!bp_alerts_get_is_owner()): ?>
                            Alert set by <a href="<?php bp_alerts_owner_permalink(); ?>"><?php bp_alerts_owner_name(); ?></a>
                            &nbsp;&nbsp;
                        <?php endif; ?>
                        <!-- View tool & facility <a href="<?php bp_alert_permalink(); ?>">details</a>&nbsp;&nbsp; -->
                        <!-- <span class="highlight label label-default"><?php //bp_alert_type();  ?></span> -->
                        <?php if (bp_alert_active()): bp_alert_active(); ?>
                            <span class="activity label label-success">Alert is active!</span>
                        <?php else: ?>
                            <span class="activity label label-warning">Alert is NOT active!</span>
                        <?php endif; ?>
                        <span class="activity label label-info"><?php printf(__('Created: %s', 'firmasite'), bp_alert_get_posted_date()); ?></span></div>
                        The alert has been triggered <span class="badge"><?php echo bp_alert_triggered_times(); ?></span> times!
                        <div class="pull-right">
                        <input name="alert-delete" class="btn-danger" type="submit" value="Delete alert!" />
                        <input onclick="window.location.href='/cecommunity/alerts?delete=109'" name="alert-activate" class="btn-success" type="submit" value="Activate alert!" />
                        </div>
                    <div class="item-content"> 
                        <p><b> <?php echo bp_alerts_content(); ?></b></p>

                    </div>

                </div>

                <div class="clear"></div>
            </li>

        <?php endwhile; ?>

    </ul>


    <div id="pag-bottom" class="pagination text-muted">

        <div class="pag-count" id="alerts-dir-count-bottom">

            <?php bp_alerts_pagination_count(); ?>

        </div>

        <div class="pagination-links lead" id="example-dir-pag-bottom">

            <?php bp_alerts_pagination_links(); ?>

        </div>

    </div>

<?php else: ?>

    <div class="clearfix"></div><div id="message" class="info alert alert-info">
        <p><?php _e('There were no alerts found.', 'firmasite'); ?></p>
    </div>

<?php endif; ?>

