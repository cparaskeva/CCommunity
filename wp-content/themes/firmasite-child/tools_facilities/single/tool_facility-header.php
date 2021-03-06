<?php
global $bp;

/* Import JS files */
wp_enqueue_script('bootstrapformhelpers');

/* Import CSS files */
wp_enqueue_style('bootstrapformhelpers-style');
$details = $bp->tools_facilities->current_tool_facility->get_tool_facility_details();
$organisation = bp_tools_facilities_get_organization();
?>
<div id="item-actions" class="pull-right">
    <strong><?php _e('Offer Owner', 'firmasite'); ?></strong>
    <div class="item-avatar">               
        <a href="<?php bp_tools_facilities_owner_permalink($bp->tools_facilities->current_tool_facility->uid); ?>"><?php bp_tools_facilities_owner_avatar('item_id=' . $bp->tools_facilities->current_tool_facility->uid . '&type=thumb&width=50&height=50'); ?></a>
    </div>

</div><!-- #item-actions -->

<div id="item-header-avatar" class="col-sm-2 fs-content-thumbnail">

    <strong><?php _e('Organisation', 'firmasite'); ?></strong>

    <div class="item-avatar" >
        <a href="<?php echo bp_group_permalink() . "organization" . $bp->tools_facilities->current_tool_facility->uid ?>"><?php echo bp_core_fetch_avatar('item_id=' . $bp->tools_facilities->current_tool_facility->gid . '&type=thumb&width=50&height=50&object=group'); ?></a>
    </div>
    <!-- Organization Details Area-->
    <div class="well" style="float:left;width:140px;margin-top:5px">
        <p>
            <strong>Organisation</strong><br><a href="<?php echo bp_group_permalink() . $organisation['slug'] ?>"><?php echo $organisation['name']; ?></a><br><br>
            <strong>Contact Person</strong><br><a href="<?php bp_offers_owner_permalink($bp->tools_facilities->current_tool_facility->uid); ?>"><?php echo xprofile_get_field_data('name', $bp->tools_facilities->current_tool_facility->uid) . " " . xprofile_get_field_data('surname', $bp->tools_facilities->current_tool_facility->uid); ?></a></br></br>         
            <strong>Email</strong><br><a target="_blank" href="<?php echo "mailto:" . $user_email = get_userdata($bp->tools_facilities->current_tool_facility->uid)->user_email ?>" ><?php echo ( strlen($user_email) < 18 ? $user_email : substr($user_email, 0, 16) . "..."); ?></a>
        </p>
    </div>
</div><!-- #item-header-avatar -->


<div class="col-lg-8">
    <span class="bfh-countries" data-country="<?php echo $bp->tools_facilities->current_tool_facility->country_id ?>" data-flags="true"></span>
    <span class="highlight label label-default"><?php echo "Location: " . $bp->tools_facilities->current_tool_facility->location ?></span> 

    <span class="highlight label label-primary"><?php echo "Payment qualification: " . $details['pdesc']; ?></span>
    <span class="highlight label label-warning"><?php echo "Operated: " . $details['odesc']; ?></span>
    <!--<span class="highlight label label-warning"><?php //echo "Geographical Coverage: " . $details['cname'];  ?></span>-->



    <span class="activity label label-info"><?php printf(__('Posted: %s', 'firmasite'), substr($bp->tools_facilities->current_tool_facility->date, 0, 10)); ?></span>

    <div id="item-meta">
        <p align="justify"> <?php echo $bp->tools_facilities->current_tool_facility->description; ?> </p>
    </div><!-- #item-meta -->
</div><!-- #item-header-content -->