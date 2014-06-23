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
                    <div class="item-meta">
                    	<span class="activity label label-info"><?php printf(__('active %s', 'firmasite'), bp_get_group_last_active()); ?></span>
                    	<span class="extra">
                    		<?php 
                    		$gid = bp_get_group_id();
                    	
                    		$org = CECOM_Organization::instance();
                    		$org->setOrganizationDetails($gid);
                    		
                    		$country = '';
                    		$countries = CECOM_Organization::getAllCountries();
                    		foreach ($countries as $c) {
								if ($c->id == $org->details['country']) {
									$country = $c->name; 									
									break;
								}                    			
                    		}
                    		
                    		$smax = $org->details['size_max'];
                    		$size = $org->details['size_min'].($smax > 0 ? '-'.$smax : '+').' Employees';
                    		
                    		$sectors = '...';
                    		//<span style=\"background-color:" . $sector['color']. "\">
                    		if (count($org->details['sectors'])) {
                    			$sectors = '';
                    			foreach ($org->details['sectors'] as $s) {
                    				$sectors .= "<span style=\"background-color:" . $s['color']. "\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> "; //. trim($s['description']);
                    			}
                    		}
                    		/*
                    		foreach ($org->details['sectors'] as $s) {
                    			$s_a[] = trim($s['description']);
                    		}
                    		if (count($s_a) > 0)
                    			$sectors = join(', ', $s_a);
                    		*/
                    		/*$subsectors = '...';
                    		foreach ($org->details['subsectors'] as $s) {
                    			$ss_a[] = trim($s['description']);
                    		}
                    		if (count($ss_a) > 0)
                    			$subsectors = join(', ', $ss_a);
                    		*/
                    		$type = $org->details['type'];
                    		
                    		
                    		$gr_creator_id = bp_get_group_creator_id();
                    		                    		
                    		/* dirty :-( */
                    		global $wpdb;
                    		$admins = $wpdb->get_results("SELECT user_login, user_email  FROM wp_users WHERE ID = $gr_creator_id");
                    		$adm = $admins[0];
                    		$admin_name = $adm->user_login;
                    		$admin_email = '<a href="mailto:'.$adm->user_email.'">'.$adm->user_email.'</a>';
                    		
                         	echo "<img width=\"20\" src=\"/cecommunity/wp-content/uploads/2014/03/country.png\"></img>$country " .
                    		     "<img width=\"20\" src=\"/cecommunity/wp-content/uploads/2014/03/size.png\"></img>$size " .
                    		     "<img width=\"20\" src=\"/cecommunity/wp-content/uploads/2014/03/sectors.png\"></img>$sectors " . 
                    		     "<img width=\"20\" src=\"/cecommunity/wp-content/uploads/2014/03/type.png\"></img>$type <b>/ Administrator</b>: $admin_name $admin_email<hr>";
                          	?>
                    	</span>
                    </div>

                    <div class="item-desc"><?php bp_group_description_excerpt(); ?></div>

        <?php do_action('bp_directory_groups_item'); ?>

                </div>


                <div class="clear"></div>
            </li>

    <?php endwhile; ?>

    </ul>

    <?php do_action('bp_after_directory_groups_list'); ?>


    <?php BP_Alert_Factory::getAlertBox(); ?>

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
