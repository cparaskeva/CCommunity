<?php
global $bp;

$details = $bp->offers->current_offer->get_offer_details();
$organisation = bp_offers_get_organization();

?>
<div id="item-actions" class="pull-right">
    <strong><?php _e('Offer Owner', 'firmasite'); ?></strong>
    <div class="item-avatar">               
        <a href="<?php bp_offers_owner_permalink($bp->offers->current_offer->uid); ?>"><?php bp_offers_owner_avatar('item_id=' . $bp->offers->current_offer->uid . '&type=thumb&width=50&height=50'); ?></a>
    </div>

</div><!-- #item-actions -->


<div id="item-header-avatar" style="margin-right:30px;"class="col-sm-2 fs-content-thumbnail">

    <strong><?php _e('Offer details', 'firmasite'); ?></strong>

    
    <!-- Organization Details Area-->
    <div class="well" style="float:left;margin-top:15px">
        <p>
            <strong>Organisation</strong><br><a href="<?php echo bp_group_permalink() . $organisation['slug'] ?>"><?php echo $organisation['name']; ?></a><br><br>
            <strong>Contact Person</strong><br><a href="<?php bp_offers_owner_permalink($bp->offers->current_offer->uid); ?>"><?php echo xprofile_get_field_data('name', $bp->offers->current_offer->uid) . " " . xprofile_get_field_data('surname', $bp->offers->current_offer->uid); ?></a></br></br>         
            <strong>Email</strong><br><a target="_blank" href="<?php echo "mailto:" . $user_email = get_userdata($bp->offers->current_offer->uid)->user_email ?>" ><?php echo ( strlen($user_email) < 18 ? $user_email : substr($user_email, 0, 16) . "..."); ?></a>
        </p>
    </div>


</div><!-- #item-header-avatar -->

<div class="col-lg-8">
    <div class="highlight label label-default"><?php echo $details['tdesc']; ?></div> </br>
    <div style="height:5px;"></div>
    <?php if ($bp->offers->current_offer->type_id == 1 || $bp->offers->current_offer->type_id == 2): ?>
        <div class="highlight label label-primary"><?php echo $details['cdesc']; ?></div></br>
        <div style="height:5px;"></div>
        <div class="highlight label label-warning"><?php echo ($bp->offers->current_offer->type_id == 1 ? "Partner sought: " : "Grant programs: ") . $details['pdesc']; ?></div></br>
    <?php else: ?>
        <div class="highlight label label-primary"><?php echo "Finance Stage: " . $details['fdesc']; ?></div></br>
        <div style="height:5px;"></div>
        <div class="highlight label label-warning"><?php echo "Applyable Countries: " . $details['cname']; ?></div></br>
    <?php endif; ?>    
    
    <div style="height:5px;"></div>

    <div class="activity label label-info"><?php printf(__('Posted: %s', 'firmasite'), substr($bp->offers->current_offer->date, 0, 10)); ?></div>

    <div style="height:20px;"></div> 

    <div id="item-meta">
        <p align="justify"> <?php echo $bp->offers->current_offer->description; ?> </p>
    </div><!-- #item-meta -->
    <!-- Show meta of offer -->
    <?php if (bp_offer_has_sectors()): ?>
        <div id="item-meta"> 
            <div>
                <b>Sectors Covered</b> 
                <?php
                foreach ($bp->offers->current_offer->sectors as $sector) {
                    echo "<br/><span style=\"background-color:" . $sector['color']
                    . "\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>"
                    . "&nbsp;&nbsp;" . $sector['description'];
                }
                ?>
            </div>
        </div><!-- #item-meta -->
    <?php endif; ?>


</div><!-- #item-header-content -->