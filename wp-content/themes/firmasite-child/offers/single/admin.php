<?php
global $bp;
$details = $bp->offers->current_offer->get_offer_details();
//print_r($details);
?>
<div class="item-list-tabs no-ajax tabs-top" id="subnav" role="navigation">
    <ul class="nav nav-pills">
        <?php bp_offer_admin_tabs(); ?>
    </ul>
</div><!-- .item-list-tabs -->

<form action="<?php bp_offer_admin_form_action(); ?>" name="offer-settings-form" id="offer-settings-form" class="standard-form" method="post" enctype="multipart/form-data" role="main">
    <?php if (bp_is_offer_admin_screen('edit-details')) : ?>

        <?php do_action('bp_before_group_details_admin'); ?>
        <!-- Hidden Fields for Offer Sectors covered-->   
        <input type="hidden" class="form-control" name="offer-sectors" id="offer-sectors" value=""/>
        <!-- End of Hidden Fields -->
        <br>
        <label for="offer-type"><?php _e('Offer Type (required)', 'firmasite'); ?></label>
        <input type="text"  readonly="true" name="offer-type" id="offer-type" value="<?php echo $details['tdesc']; ?>" aria-required="true" /> <br/>

        <?php if ($bp->offers->current_offer->type_id == 1 || $bp->offers->current_offer->type_id == 2): ?>
            <label for="collaboration-type"><?php _e('Type of collaboration (required)', 'firmasite'); ?></label>
            <select name="collaboration-type" id="collaboration-type">
                <?php
                //Fetch Collaboration Types form DB
                $results = BP_Offer::getCollaborationTypes();
                if (is_array($results)) {
                    foreach ($results as $offer_collaboration) {
                        if ($bp->offers->current_offer->collaboration_id == $offer_collaboration->id)
                            echo "<option selected='selected' value = '{$offer_collaboration->id }'>{$offer_collaboration->description}</option>";
                        else
                            echo "<option value = '{$offer_collaboration->id }'>{$offer_collaboration->description}</option>";
                    }
                }
                ?>
            </select>
            <br/>
        <?php endif; ?>
        <label style="margin:0px" for="collaboration-description"><?php _e('Offer Description (required)', 'firmasite'); ?></label>
        <?php
        $content = $bp->offers->current_offer->description;
        echo firmasite_wp_editor($content, 'collaboration-description');
        ?>
        <br>
        <?php
        //Offer Type: 1-Develop product and services
        if ($bp->offers->current_offer->type_id == 1):
            ?>
            <!-- Offer type: Collaboration to develop products and services -->
            <label for = "collaboration-partner-sought"><?php _e('Type of partner sought (required)', 'firmasite'); ?></label>
            <select name="collaboration-partner-sought" id="collaboration-partner-sought">
                <?php
                //Fetch Partner sought Types form DB
                $results = BP_Offer::getPartnerTypes();
                if (is_array($results)) {
                    foreach ($results as $offer_partner_type) {

                        if ($bp->offers->current_offer->partner_type_id == $offer_partner_type->id)
                            echo "<option selected='selected' value = '{$offer_partner_type->id }'>{$offer_partner_type->description}</option>";
                        else
                            echo "<option value = '{$offer_partner_type->id }'>{$offer_partner_type->description}</option>";
                    }
                }
                ?>
            </select>
        <?php endif; ?>


        <?php
        //Offer Type: 2-Participate to funded projects
        if ($bp->offers->current_offer->type_id == 2):
            ?>


            <label for="collaboration-countries"><?php _e('Grant Programms (required)', 'firmasite'); ?></label>
            <select name="collaboration-programs" id="collaboration-programs">
                <?php
                //Fetch Grant Programs form DB
                $results = BP_Offer::getGrantPrograms();
                if (is_array($results)) {

                    foreach ($results as $program) {

                        if ($bp->offers->current_offer->program_id == $program->id)
                            echo "<option selected='selected' value = '{$program->id }'>{$program->description}</option>";
                        else
                            echo "<option value = '{$program->id }'>{$program->description}</option>";
                    }
                }
                ?>
            </select>
        <?php endif; ?>

        <?php
        //Offer Type: 3-Funding
        if ($bp->offers->current_offer->type_id == 3):
            ?>
            <label for="applyable-countries"><?php _e('Applyable countries (required)', 'firmasite'); ?></label>
            <select  class="form-control" name="applyable-countries" id="applyable-countries" aria-required="false">
                <?php
                //Fetch All Countries form DB
                $results = CECOM_Organization::getAllCountries();
                if (is_array($results)) {
                    foreach ($results as $country) {
                        if ($bp->offers->current_offer->country_id == $country->id)
                            echo "<option selected='selected' value = '{$country->id }'>{$country->name}</option>";
                        else
                            echo "<option value = '{$country->id }'>{$country->name}</option>";
                    }
                }
                ?>
            </select>
            <br/>
            <label for="finance-stage"><?php _e('Financing Stage (required)', 'firmasite'); ?></label>
            <select  class="form-control" name="finance-stage" id="finance-stage" aria-required="false">
                <?php
                //Fetch Financing stages form DB
                $results = BP_Offer::getFinanceStages();
                if (is_array($results)) {
                    foreach ($results as $finance_stage) {
                        if ($bp->offers->current_offer->finance_stage_id == $finance_stage->id)
                            echo "<option selected='selected' value = '{$finance_stage->id }'>{$finance_stage->description}</option>";
                        else
                            echo "<option value = '{$finance_stage->id }'>{$finance_stage->description}</option>";
                    }
                }
                ?>
            </select>
            <br/>
            <label  for="offer_sector"><?php _e('Sectors', 'firmasite'); ?> </label>
            <select name="offer_sector" id="offer_sector"  class="multiselect" multiple="multiple">
                <?php
                //Fetch Organization Sectors form DB
                $results = CECOM_Organization::getOrganizationSector();
                if (is_array($results)) {
                    foreach ($results as $org_sector) {
                        echo "<option value = '{$org_sector->id }'>{$org_sector->description}</option>";
                    }
                }
                ?>
            </select>
        <?php endif; ?>
        <br/>
        <p>
        <hr/>

    </p>

    <?php do_action('bp_after_group_details_admin'); ?>

    <p><input type="submit" class="btn  btn-primary" value="<?php _e('Save Changes', 'firmasite'); ?>" id="save" name="save" /></p>
    <?php wp_nonce_field('offers_edit_offer_details'); ?>
<?php endif; ?>

<?php /* Delete Offer Option */ ?>
<?php if (bp_is_offer_admin_screen('delete-offer')) : ?>

    <?php do_action('bp_before_group_delete_admin'); ?>

    <br/>
    <div class="clearfix"></div><div id="message" class="info alert alert-info">
        <p><?php _e('WARNING: Deleting this offer will completely remove ALL content associated with it. There is no way back, please be careful with this option.', 'firmasite'); ?></p>
    </div>

    <label><input type="checkbox" name="delete-offer-understand" id="delete-group-understand" value="1" onclick="if (this.checked) {
                    document.getElementById('delete-offer-button').disabled = '';
                } else {
                    document.getElementById('delete-offer-button').disabled = 'disabled';
                }" /> <?php _e('I understand the consequences of deleting this offer.', 'firmasite'); ?></label>


    <div class="submit">
        <input type="submit" class="btn  btn-primary" disabled="disabled" value="<?php _e('Delete Offer', 'firmasite'); ?>" id="delete-offer-button" name="delete-offer-button" />
        <br/><br/>
    </div>

    <?php wp_nonce_field('offers_delete_offer'); ?>

<?php endif; ?>

<?php /* This is important, don't forget it */ ?>
<input type="hidden" name="offer-id" id="offer-id" value="<?php echo $bp->offers->current_offer->id; ?>" />

<?php do_action('bp_after_group_admin_content'); ?>
</form><!-- #offer-settings-form -->



<script type="text/javascript">

    /*
     * Organization sector
     */


    jQuery("#offer_sector").change(function() {

        var selectedTexts = [];

        jQuery(this).find("option:selected").each(function(i) {
            var val = jQuery(this).val();
        });

        //Set the values to hidden field
        jQuery("#offer-sectors").val(jQuery(this).val());

    });


    //Initialize the sectors multiselect object
    jQuery(document).ready(function() {
        jQuery("#offer_sector").multiselect({numberDisplayed: 1});

//If and only if current offer has sectors and is type of "Funding" enter
<?php
if (bp_offer_has_sectors()):
    $sector_values = "[";
    $sector_txt = "[";
    foreach ($bp->offers->current_offer->sectors as $sector) {
        $sector_values .= "'" . $sector['id'] . "',";
        $sector_txt .= "'" . $sector['description'] . "',";
    }
    $sector_values = substr($sector_values, 0, -1) . "]";
    $sector_txt = substr($sector_txt, 0, -1) . "]";
    ?>


            //Set the selected sector options of the current offer
            jQuery("#offer_sector").multiselect('select', <?php echo $sector_values; ?>);

            //Store the sector values to the hidden field
            jQuery("#offer-sectors").val(jQuery("#offer_sector").val());

<?php endif; ?>
    });
</script>