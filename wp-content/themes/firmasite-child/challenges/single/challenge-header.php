<?php
global $bp;
$details = $bp->challenges->current_challenge->get_challenge_details();
$organisation = bp_challenges_get_organization();
?>
<div id="item-actions" class="pull-right">
    <strong><?php _e('Challenge Owner', 'firmasite'); ?></strong>
    <div class="item-avatar">               
        <a href="<?php bp_challenges_owner_permalink($bp->challenges->current_challenge->uid); ?>"><?php bp_challenges_owner_avatar('item_id=' . $bp->challenges->current_challenge->uid . '&type=thumb&width=50&height=50'); ?></a>
    </div>

</div><!-- #item-actions -->

<div id="item-header-avatar" class="col-sm-2 fs-content-thumbnail">

    <strong><?php _e('Organisation', 'firmasite'); ?></strong>

    <div class="item-avatar" >
        <a href="<?php echo bp_group_permalink() . "organization" . $bp->challenges->current_challenge->uid ?>"><?php echo bp_core_fetch_avatar('item_id=' . $bp->challenges->current_challenge->gid . '&type=thumb&width=50&height=50&object=group'); ?></a>
    </div>
    <!-- Organization Details Area-->
    <div class="well" style="float:left;width:140px;margin-top:5px">
        <p>
            
            
            <strong>Organisation</strong><br><a href="<?php echo bp_group_permalink() . $organisation['slug'] ?>"><?php echo $organisation['name']; ?></a><br><br>
            <strong>Contact Person</strong><br><a href="<?php bp_challenges_owner_permalink($bp->challenges->current_challenge->uid); ?>"><?php echo xprofile_get_field_data('name', $bp->challenges->current_challenge->uid) . " " . xprofile_get_field_data('surname', $bp->challenges->current_challenge->uid); ?></a></br></br>
            <strong>Email</strong><br><a target="_blank" href="<?php echo "mailto:" . $user_email = get_userdata($bp->challenges->current_challenge->uid)->user_email ?>" ><?php echo ( strlen($user_email) < 18 ? $user_email : substr($user_email, 0, 16) . "..."); ?></a>
        </p>
    </div>
</div><!-- #item-header-avatar -->


<div class="col-lg-8">
    <span class="highlight label label-default"><?php echo "Deadline: ". $details['deadline']; ?></span> 
    <span class="highlight label label-primary"><?php echo "License: " . $details['rdesc']; ?></span>&nbsp;
    <span class="highlight label label-warning"><?php echo "Reward ammount: " . $details['reward']; ?></span>

    <span class="activity label label-info"><?php printf(__('Posted: %s', 'firmasite'), substr($bp->challenges->current_challenge->date, 0, 10)); ?></span>

    <div id="item-meta">
        
        <h3>Title: "<?php echo " ".$bp->challenges->current_challenge->title." "; ?> " </h3>
        <p align="justify"> <?php echo $bp->challenges->current_challenge->description; ?> </p>
    </div><!-- #item-meta -->
    <!-- Show meta of challenge -->
    <?php if (bp_challenge_has_sectors()): ?>
        <div id="item-meta"> 
            <div><br>
                <b>Sectors Covered</b> 
                <?php
                foreach ($bp->challenges->current_challenge->sectors as $sector) {
                    echo "<br/><span style=\"background-color:" . $sector['color']
                    . "\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>"
                    . "&nbsp;&nbsp;" . $sector['description'];
                }
                ?>
            </div>
        </div><!-- #item-meta -->
    <?php endif; ?>
</div><!-- #item-header-content -->