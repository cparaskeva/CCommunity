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

<form action="<?php bp_offer_admin_form_action(); ?>" name="group-settings-form" id="group-settings-form" class="standard-form" method="post" enctype="multipart/form-data" role="main">
    <?php if (bp_is_offer_admin_screen('edit-details')) : ?>

        <?php do_action('bp_before_group_details_admin'); ?>
        <!-- Hidden Fields for Sector/Subsector-->   
        <input type="hidden" class="form-control" name="organization_sectors" id="organization_sectors" value=""/>
        <!-- End of Hidden Fields -->
        <br>
        <label for="offer-type"><?php _e('Offer Type (required)', 'firmasite'); ?></label>
        <input type="text"  readonly="true" name="offer-type" id="offer-type" value="<?php echo $details['tdesc']; ?>" aria-required="true" /> <br/>
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
        <label style="margin:0px" for="collaboration-description"><?php _e('Offer Description (required)', 'firmasite'); ?></label>
        <?php
        $content = $bp->offers->current_offer->description;
        echo firmasite_wp_editor($content, 'collaboration-description');
        /*
          <textarea name="group-desc" id="group-desc" aria-required="true"><?php bp_group_description_editable(); ?></textarea>
         */
        ?>

        <?php //do_action('groups_custom_group_fields_editable');  ?>
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

        <br/>
        <!-- <label  for="organization_sector"><?php _e('Sector', 'firmasite'); ?> </label>
        <select  name="organization_sector" id="organization_sector" class="multiselect" multiple="multiple" >
        <?php
        //Fetch Organization Sectors form DB
        /* $results = CECOM_Organization::getOrganizationSector();
          if (is_array($results)) {
          foreach ($results as $org_sector) {
          echo "<option value = '{$org_sector->id }'>{$org_sector->description}</option>";
          }
          } */
        ?>

        </select>
        <br/><br/> -->

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



<script type = "text/javascript">

    /*
     * Radio buttons check for Yes/No fields
     */


//Collaboration Radio Buttons
    jQuery("#organization_collaboration_y").click(function() {
        jQuery("#organization_collaboration_n").attr("checked", false);
    });

    jQuery("#organization_collaboration_n").click(function() {
        jQuery("#organization_collaboration_y").attr("checked", false);
    });


//Transaction Radio Buttons
    jQuery("#organization_transaction_y").click(function() {
        jQuery("#organization_transaction_n").attr("checked", false);
    });

    jQuery("#organization_transaction_n").click(function() {
        jQuery("#organization_transaction_y").attr("checked", false);
    });



//Get the ID of the selected country from the organization_country list and save it to the hidden field (organization_countryID)
    function setOrganizationCountryID() {
        jQuery("#organization_countryID").val(jQuery("#organization_country").val());

    }

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
        jQuery("#organization_sectors").val(jQuery(this).val());
        jQuery("#organization_subsectors").val("");

    });

    /*
     * Organization subsector
     */


    jQuery("#organization_subsector").change(function() {
        //Set the values to hidden field
        jQuery("#organization_subsectors").val(jQuery("#organization_subsector").val());

    });






    jQuery(document).ready(function() {
        jQuery("#organization_sector").multiselect({numberDisplayed: 1});

<?php
$sector_values = "[";
$sector_txt = "[";
$subsector_values = "[";
foreach ($cecom->organization->details['sectors'] as $sector) {
    $sector_values .= "'" . $sector['id'] . "',";
    $sector_txt .= "'" . $sector['description'] . "',";
}
$sector_values = substr($sector_values, 0, -1) . "]";
$sector_txt = substr($sector_txt, 0, -1) . "]";

foreach ($cecom->organization->details['subsectors'] as $subsector) {
    $subsector_values .= "'" . $subsector['id'] . "',";
}
$subsector_values = substr($subsector_values, 0, -1) . "]";
?>

        jQuery("#organization_sector").multiselect('select', <?php echo $sector_values; ?>);
        jQuery("#organization_subsector").multiselect({numberDisplayed: 5, maxHeight: 300, enableFiltering: true});
        setSubsctorValues(<?php echo $sector_values; ?>,<?php echo $sector_txt; ?>,<?php echo $subsector_values; ?>)
    });



</script>