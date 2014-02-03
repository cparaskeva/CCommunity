<?php
global $cecom;
/* $group = groups_get_user_groups($bp->loggedin_user->id);
  $gid = $group['groups'][0]; */
$cecom->organization->getOrganizationDetails(bp_get_current_group_id());


do_action('bp_before_group_header');
?>

<script src='<?php echo get_stylesheet_directory_uri() . "/assets/bootstrapformhelpers/js/bootstrap-formhelpers.js" ?>'></script>
<link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_directory_uri() . "/assets/bootstrapformhelpers/css/bootstrap-formhelpers.css"; ?>"/>
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
    <a href="<?php bp_group_permalink(); ?>" title="<?php bp_group_name(); ?>">

        <?php bp_group_avatar(); ?>

    </a>
    <!-- Organization Details Area-->
    <div class="well" style="float:left;width:160px;margin-top:10px">
        <p>
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
            echo $min.$minus.$max." Employees";
            ?>
            </br></br>
            <strong>Website</strong></br><a target="_blank" href="<?php echo $cecom->organization->details['website'] ?>" ><?php echo substr($cecom->organization->details['website'], 0,21);  ?></a></br></br>
            <strong>Location</strong></br><span class="bfh-countries" data-country="<?php echo $cecom->organization->details['country'] ?>" data-flags="true"></span>
        </p>

        <?php ?>

    </div>
</div><!-- #item-header-avatar -->

<div id="item-header-content" class="fs-have-thumbnail">
    <h2><a href="<?php bp_group_permalink(); ?>" title="<?php bp_group_name(); ?>"><?php bp_group_name(); ?></a></h2>
    <span class="highlight label label-default"><?php bp_group_type(); ?></span> <span class="activity label label-info"><?php printf(__('active %s', 'firmasite'), bp_get_group_last_active()); ?></span>

    <?php do_action('bp_before_group_header_meta'); ?>

    <div id="item-meta">

        <?php bp_group_description(); ?>
        <div>
            <strong>Specialties</strong> <br/><?php echo $cecom->organization->details['specialties'] ?><br/><br/>
            <b>Sector</b> <?php echo "<br/><span style=\"background-color:";echo $cecom->organization->details['sector_color']."\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>"; echo "&nbsp;&nbsp;".$cecom->organization->details['sector_desc']?><br/><br/>
            <i>Organization is available for collaboration</i>&nbsp;  <?php if ($cecom->organization->details['collaboration']) : echo "<span class=\"glyphicon glyphicon-ok\"></span>";
        else : echo "<span class=\"glyphicon glyphicon-remove\"></span>";
        endif; ?></br>
            <i>Organization is available for transaction</i>&nbsp; <?php if ($cecom->organization->details['transaction']) : echo "<span class=\"glyphicon glyphicon-ok\"></span>";
        else : echo "<span class=\"glyphicon glyphicon-remove\"></span>";
        endif; ?> <br/><br/>
        </div>
        <div id="item-buttons">

        <?php //do_action('bp_group_header_actions'); ?>

        </div><!-- #item-buttons -->

<?php do_action('bp_group_header_meta'); ?>

    </div>
</div><!-- #item-header-content -->

<?php
do_action('bp_after_group_header');
do_action('template_notices');
?>