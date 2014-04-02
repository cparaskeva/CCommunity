<?php
global $bp;
$details = $bp->patents_licenses->current_patent_license->get_patent_license_details();
$organisation = bp_patents_licenses_get_organization();
?>
<div id="item-actions" class="pull-right">
    <strong><?php _e('Offer Owner', 'firmasite'); ?></strong>
    <div class="item-avatar">               
        <a href="<?php bp_patents_licenses_owner_permalink($bp->patents_licenses->current_patent_license->uid); ?>"><?php bp_patents_licenses_owner_avatar('item_id=' . $bp->patents_licenses->current_patent_license->uid . '&type=thumb&width=50&height=50'); ?></a>
    </div>

</div><!-- #item-actions -->

<div id="item-header-avatar" class="col-sm-2 fs-content-thumbnail">

    <strong><?php _e('Organisation', 'firmasite'); ?></strong>

    <div class="item-avatar" >
        <a href="<?php echo bp_group_permalink() . "organization" . $bp->patents_licenses->current_patent_license->uid ?>"><?php echo bp_core_fetch_avatar('item_id=' . $bp->patents_licenses->current_patent_license->gid . '&type=thumb&width=50&height=50&object=group'); ?></a>
    </div>
    <!-- Organization Details Area-->
    <div class="well" style="float:left;width:140px;margin-top:5px">
        <p>
            
            
            <strong>Organisation</strong><br><a href="<?php echo bp_group_permalink() . $organisation['slug'] ?>"><?php echo $organisation['name']; ?></a><br><br>
            <strong>Contact Person</strong><br><a href="<?php bp_patents_licenses_owner_permalink($bp->patents_licenses->current_patent_license->uid); ?>"><?php echo xprofile_get_field_data('name', $bp->patents_licenses->current_patent_license->uid) . " " . xprofile_get_field_data('surname', $bp->patents_licenses->current_patent_license->uid); ?></a></br></br>
            <strong>Email</strong><br><a target="_blank" href="<?php echo "mailto:" . $user_email = get_userdata($bp->patents_licenses->current_patent_license->uid)->user_email ?>" ><?php echo ( strlen($user_email) < 18 ? $user_email : substr($user_email, 0, 16) . "..."); ?></a>
        </p>
    </div>
</div><!-- #item-header-avatar -->


<div class="col-lg-8">
    <span class="highlight label label-default"><?php echo $details['tdesc']; ?></span> 

    <span class="highlight label label-primary"><?php echo "Exchange type: " . $details['edesc']; ?></span>
    <span class="highlight label label-warning"><?php echo "Geographical Coverage: " . $details['cname']; ?></span>



    <span class="activity label label-info"><?php printf(__('Posted: %s', 'firmasite'), substr($bp->patents_licenses->current_patent_license->date, 0, 10)); ?></span>

    <div id="item-meta">
        <p align="justify"> <?php echo $bp->patents_licenses->current_patent_license->description; ?> </p>
    </div><!-- #item-meta -->
    <!-- Show meta of patent_license -->
    <?php if (bp_patent_license_has_sectors()): ?>
        <div id="item-meta"> 
            <div><br>
                <b>Sectors Covered</b> 
                <?php
                foreach ($bp->patents_licenses->current_patent_license->sectors as $sector) {
                    echo "<br/><span style=\"background-color:" . $sector['color']
                    . "\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>"
                    . "&nbsp;&nbsp;" . $sector['description'];
                }
                ?>
            </div>
        </div><!-- #item-meta -->
    <?php endif; ?>
    <!-- Show meta of patent_license -->
    <?php if (bp_patent_license_has_subsectors()): ?>
        <div id="item-meta"> 
            <div>
                <br/><br/>
                <b>Subsectors</b><br/>
                <?php
                $subsectors = "";
                foreach ($bp->patents_licenses->current_patent_license->subsectors  as $subsector) {
                    $subsectors .= $subsector['description'] . ", ";
                }
                echo substr($subsectors, 0, -2);
                ?><br/><br/>

            </div>
        </div><!-- #item-meta -->
    <?php endif; ?>
</div><!-- #item-header-content -->