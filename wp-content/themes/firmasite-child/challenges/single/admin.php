<?php
global $bp;
$details = $bp->challenges->current_challenge->get_challenge_details();
?>
<div class="item-list-tabs no-ajax tabs-top" id="subnav" role="navigation">
    <ul class="nav nav-pills">
        <?php bp_challenge_admin_tabs(); ?>
    </ul>
</div><!-- .item-list-tabs -->

<form action="<?php bp_challenge_admin_form_action(); ?>" name="challenge-settings-form" id="challenge-settings-form" class="standard-form" method="post" enctype="multipart/form-data" role="main">
    <?php if (bp_is_challenge_admin_screen('edit-details')) : ?>

        <?php do_action('bp_before_group_details_admin'); ?>

        <!-- Hidden Fields for Organization Sectors and Subsectors covered-->   
        <input  type="hidden" class="form-control" name="challenge-sectors" id="challenge-sectors" value=""/>
        <!-- End of Hidden Fields -->
        <br>


        <label for="challenge-description"><?php _e('Title of the challenge you propose (required)', 'firmasite'); ?></label>
        <input class="form-control" name="challenge-title" id="challenge-title" value="<?php echo $bp->challenges->current_challenge->title ?>" />
        <br/>
        <label for="challenge-description"><?php _e('Describe the challenge you want to publish (required)', 'firmasite'); ?></label>
        <?php
        $content = $bp->challenges->current_challenge->description;
        echo firmasite_wp_editor($content, 'challenge-description');
        ?>
        <br/>
        <label  for="organization_sector"><?php _e('Sector', 'firmasite'); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
        <select name="organization_sector" id="organization_sector"  class="multiselect" multiple="multiple">
            <?php
            //Fetch Organization Sectos form DB
            $results = CECOM_Organization::getOrganizationSector();
            if (is_array($results)) {

                foreach ($results as $org_sector) {
                    echo "<option value = '{$org_sector->id }'>{$org_sector->description}</option>";
                }
            }
            ?>
        </select>
        <br><br>
        <label for="challenge-deadline"><?php _e('Deadline of answering in YYYY/MM/DD format (required)', 'firmasite'); ?></label>
        <input class="form-control" name="challenge-deadline" id="challenge-deadline" value="<?php echo $bp->challenges->current_challenge->deadline ?>"/>
        <br><br>
        <label for="challenge-reward"><?php _e('Amount of the reward in Euro (required)', 'firmasite'); ?></label>
        <input class="form-control" name="challenge-reward" id="challenge-reward" value="<?php echo $bp->challenges->current_challenge->reward ?>"/>
        <br/><br/>
        <label for="challenge-exchange"><?php _e('Type of exchange (required)', 'firmasite'); ?></label>



        <select  class="form-control" name="challenge-rights" id="challenge-rights" aria-required="false">
            <?php
            //Fetch All Countries form DB
            $results_r = BP_Challenge::getRights();
            if (is_array($results_r)) {
                foreach ($results_r as $right) {
                    if ($bp->challenges->current_challenge->right_id == $right->id)
                        echo "<option selected='selected' value = '{$right->id }'>{$right->description}</option>";
                    else
                        echo "<option value = '{$right->id }'>{$right->description}</option>";
                }
            }
            ?>
        </select>
        <br/>
        <br/>
        <p>
        <hr/>
    </p>
    <?php do_action('bp_after_group_details_admin'); ?>

    <p><input type="submit" class="btn  btn-primary" value="<?php _e('Save Changes', 'firmasite'); ?>" id="save" name="save" /></p>
    <?php wp_nonce_field('challenges_edit_challenge_details'); ?>
<?php endif; ?>

<?php /* Delete Patent/License Option */ ?>
<?php if (bp_is_challenge_admin_screen('delete-challenge')) : ?>

    <?php do_action('bp_before_group_delete_admin'); ?>

    <br/>
    <div class="clearfix"></div><div id="message" class="info alert alert-info">
        <p><?php _e('WARNING: Deleting this challenge will completely remove ALL content associated with it. There is no way back, please be careful with this option.', 'firmasite'); ?></p>
    </div>

    <label><input type="checkbox" name="delete-challenge-understand" id="delete-group-understand" value="1" onclick="if (this.checked) {
                    document.getElementById('delete-challenge-button').disabled = '';
                } else {
                    document.getElementById('delete-challenge-button').disabled = 'disabled';
                }" /> <?php _e('I understand the consequences of deleting this challenge.', 'firmasite'); ?></label>


    <div class="submit">
        <input type="submit" class="btn  btn-primary" disabled="disabled" value="<?php _e('Delete Challenge', 'firmasite'); ?>" id="delete-challenge-button" name="delete-challenge-button" />
        <br/><br/>
    </div>

    <?php wp_nonce_field('challenges_delete_challenge'); ?>

<?php endif; ?>

<?php /* This is important, don't forget it */ ?>
<input type="hidden" name="challenge-id" id="challenge-id" value="<?php echo $bp->challenges->current_challenge->id; ?>" />

<?php do_action('bp_after_group_admin_content'); ?>
</form><!-- #challenge-settings-form -->



<script type="text/javascript">

    /*
     * Organization sector
     */


    jQuery("#organization_sector").change(function() {

        var selectedTexts = [];

        jQuery(this).find("option:selected").each(function(i) {
            var val = jQuery(this).val();
            var txt = jQuery(this).text();
            selectedTexts[i] = txt;
        });

        setSubsctorValues(jQuery('.multiselect').val(), selectedTexts);

        //Set the values to hidden field
        jQuery("#challenge-sectors").val(jQuery(this).val());
        jQuery("#challenge-subsectors").val("");

    });

    /*
     * Organization subsector
     */


    jQuery("#organization_subsector").change(function() {
        //Set the values to hidden field
        jQuery("#challenge-subsectors").val(jQuery("#organization_subsector").val());
    });



    jQuery(document).ready(function() {
        jQuery("#organization_sector").multiselect({numberDisplayed: 1});
        jQuery("#organization_subsector").multiselect({numberDisplayed: 5, maxHeight: 300, enableFiltering: true});

<?php
//Load current sectors of the patent/license only if exist
if (bp_challenge_has_sectors()):
    $sector_values = "[";
    $sector_txt = "[";
    foreach ($bp->challenges->current_challenge->sectors as $sector) {
        $sector_values .= "'" . $sector['id'] . "',";
        $sector_txt .= "'" . $sector['description'] . "',";
    }
    $sector_values = substr($sector_values, 0, -1) . "]";
    $sector_txt = substr($sector_txt, 0, -1) . "]";
    echo 'jQuery("#organization_sector").multiselect("select",' . $sector_values . ');';
    echo 'jQuery("#challenge-sectors").val(jQuery("#organization_sector").val());';
endif;
//Load current subsectors of the patent/license only if exist
if (bp_challenge_has_subsectors()):
    $subsector_values = "[";
    foreach ($bp->challenges->current_challenge->subsectors as $subsector) {
        $subsector_values .= "'" . $subsector['id'] . "',";
    }
    $subsector_values = substr($subsector_values, 0, -1) . "]";
    echo 'setSubsctorValues(' . $sector_values . ',' . $sector_txt . ',' . $subsector_values . ');';
else: {
        echo 'jQuery("#organization_subsector").multiselect("dataprovider", [{label: "optgroup", value: "(select sector first)"}]);';
        if (bp_challenge_has_sectors())
            echo 'setSubsctorValues(' . $sector_values . ',' . $sector_txt . ',"");';
    }
endif;
?>

    });

</script>