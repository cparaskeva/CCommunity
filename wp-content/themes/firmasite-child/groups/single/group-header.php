<?php
do_action("wp_enqueue_cecom_scripts");

/* Import JS files */
wp_enqueue_script('bootstrapformhelpers');
wp_enqueue_script('bootstrap-multiselect');

/* Import CSS files */
wp_enqueue_style('bootstrapformhelpers-style');
wp_enqueue_style('bootstrap-multiselect-style');

global $cecom;
/* $group = groups_get_user_groups($bp->loggedin_user->id);
  $gid = $group['groups'][0]; */
$cecom->organization->setOrganizationDetails(bp_get_current_group_id());

?>
<div id="item-actions" class="pull-right">

    <?php if (bp_group_is_visible()) : ?>

        <strong><?php _e('Group Admins', 'firmasite'); ?></strong>

        <?php
        bp_group_list_admins();

        do_action('bp_after_group_menu_admins');

        if (bp_group_has_moderators()) :
            do_action('bp_before_group_menu_mods');
            ?>

            <strong><?php _e('Group Mods', 'firmasite'); ?></strong>

            <?php
            bp_group_list_mods();

            do_action('bp_after_group_menu_mods');

        endif;

    endif;
    ?>

</div><!-- #item-actions -->

<div id="item-header-avatar" class="col-xs-4 col-md-4 fs-content-thumbnail">
    
    <!-- Organization Details Area-->
    <div class="well" style="float:left;margin-top:10px">
     <span>
	<a href="<?php bp_group_permalink(); ?>" title="<?php bp_group_name(); ?>">

        <?php bp_group_avatar(); ?>

    	</a>
     </span><br/><br/><br/>
        <div>
            <strong>Type</strong></br> <?php echo $cecom->organization->details['type'] ?></br></br>
            <strong>Organization Size</strong></br>
            <?php
            $minus = "-";
            $max = $cecom->organization->details['size_max'];
            $min = $cecom->organization->details['size_min'];
            if ($max == "0") {
                $max = $max - 1;
                $max = "+";
                $minus = "";
            } elseif ($min == $max) {
                $minus = "";
                $max = "";
            }
            echo $min . $minus . $max . " Employees";
            
            $gr_creator_id = bp_get_group_creator_id();
            /* dirty :-( */
            global $wpdb;
            $admins = $wpdb->get_results("SELECT user_login, user_email  FROM wp_users WHERE ID = $gr_creator_id");
            $adm = $admins[0];
            $admin_name = $adm->user_login;
            $admin_email = '<a href="mailto:'.$adm->user_email.'">'.$adm->user_email.'</a>';
            
            ?>
            </br></br>
            <strong>Website</strong></br><a target="_blank" href="<?php echo $cecom->organization->details['website'] ?>" ><?php echo $cecom->organization->details['website'] /*substr($cecom->organization->details['website'], 0, 21);*/ ?></a></br></br>
            <strong>Location</strong></br><span class="bfh-countries" data-country="<?php echo $cecom->organization->details['country'] ?>" data-flags="true"></span><br><br>
            
            <strong>Administrator</strong></br><span><?php echo $admin_name." ".$admin_email  ?></span>
        </div>

        <?php ?>

    </div>
</div><!-- #item-header-avatar -->

<div id="item-header-content" class="fs-have-thumbnail">
    <h2><a href="<?php bp_group_permalink(); ?>" title="<?php bp_group_name(); ?>"><?php bp_group_name(); ?></a></h2>
    <span class="highlight label label-default"><?php bp_group_type(); ?></span> <span class="activity label label-info"><?php printf(__('active %s', 'firmasite'), bp_get_group_last_active()); ?></span>

    <?php do_action('bp_before_group_header_meta'); ?>

    <div id="item-meta">

	<div style="height:10px;"></div>
        <?php bp_group_description(); ?>
	<div style="height:10px;"></div>
        <div>
            <strong>Specialties</strong> <br/><?php echo $cecom->organization->details['specialties'] ?><br/><br/>
            <b>Sectors</b> 
            <?php
            foreach ($cecom->organization->details['sectors'] as $sector) {
                echo "<br/><span style=\"background-color:" . $sector['color'] 
                        . "\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>"
                        ."&nbsp;&nbsp;" . $sector['description'];
            }
            ?><br/><br/>
            <b>Subsectors</b><br/>
            <?php
            $subsectors = "";
            foreach ($cecom->organization->details['subsectors'] as $subsector) {
                $subsectors .= $subsector['description'].", ";
            }
            echo substr($subsectors, 0,-2);
            ?><br/><br/>
            <i>Organization is available for collaboration</i>&nbsp;  <?php
            if ($cecom->organization->details['collaboration']) : echo "<span class=\"glyphicon glyphicon-ok\"></span>";
            else : echo "<span class=\"glyphicon glyphicon-remove\"></span>";
            endif;
            ?></br>
            <i>Organization is available for transaction</i>&nbsp; <?php
            if ($cecom->organization->details['transaction']) : echo "<span class=\"glyphicon glyphicon-ok\"></span>";
            else : echo "<span class=\"glyphicon glyphicon-remove\"></span>";
            endif;
            ?> <br/><br/>
        </div>

        <?php //do_action('bp_group_header_meta');     ?>

    </div><!-- #item-meta -->
</div><!-- #item-header-content -->
<?php
do_action('bp_after_group_header');
do_action('template_notices');
?>
                  