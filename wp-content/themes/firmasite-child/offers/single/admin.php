<?php global $bp; ?>
<div class="item-list-tabs no-ajax tabs-top" id="subnav" role="navigation">
    <ul class="nav nav-pills">
        <?php bp_offer_admin_tabs(); ?>
    </ul>
</div><!-- .item-list-tabs -->

<form action="<?php bp_offer_admin_form_action(); ?>" name="group-settings-form" id="group-settings-form" class="standard-form" method="post" enctype="multipart/form-data" role="main">
    <?php if (bp_is_offer_admin_screen('edit-details')) : ?>

        <?php do_action('bp_before_group_details_admin'); ?>
        <?php global $cecom; ?>
        <!-- Set Organization ID--> 
        <input type="hidden" class="form-control" name="organization_id" id="organization_id" value="<?php echo $cecom->organization->details['id']; ?>"/>
        <!-- Hidden Fields for Sector/Subsector-->   
        <input type="hidden" class="form-control" name="organization_sectors" id="organization_sectors" value=""/>
        <input type="hidden" class="form-control" name="organization_subsectors" id="organization_subsectors" value=""/>
        <!-- End of Hidden Fields -->
        <label for="group-name"><?php _e('Organisation Name (required)', 'firmasite'); ?></label>
        <input type="text" name="group-name" id="group-name" value="<?php bp_group_name(); ?>" aria-required="true" /><br/>

        <label style="margin:0px" for="group-desc"><?php _e('Organisation Description (required)', 'firmasite'); ?></label>
        <?php
        $content = bp_get_group_description_editable();
        echo firmasite_wp_editor($content, 'group-desc');
        /*
          <textarea name="group-desc" id="group-desc" aria-required="true"><?php bp_group_description_editable(); ?></textarea>
         */
        ?>

        <?php do_action('groups_custom_group_fields_editable'); ?>

        <!-- Start of CECOM Organization Custom Fields --> 
        <div>
            <!-- Hold the ID of the selected country from the organization_country list -->
            <input type="hidden"  name="organization_countryID" id="organization_countryID" value="<?php echo $cecom->organization->details['country'] ?>" />
            <br/>
            <label for="organization_specialties"><?php _e('Specialities', 'firmasite'); ?> </label>
            <input type="text"  name="organization_specialties" id="organization_specialties" value="<?php echo $cecom->organization->details['specialties'] ?>" aria-required="false"/>
            <br/>
            <label for="organization_website"><?php _e('Organisation Website', 'firmasite'); ?> </label>
            <input type="text"  name="organization_website" id="organization_specialties" value="<?php echo $cecom->organization->details['website'] ?>" aria-required="false"/>
            <br/>
            <label  for="organization_country"><?php _e('Country', 'firmasite'); ?></label>
            <div  onchange="setOrganizationCountryID()" id="organization_country" class="bfh-selectbox bfh-countries" data-country="<?php echo $cecom->organization->details['country'] ?>" data-flags="true"> </div>
            <br/>
            <label  for="organization_size"><?php _e('Organization Size', 'firmasite'); ?> </label>
            <select   name="organization_size" id="organization_size" >
                <?php
                //Fetch Organization Size form DB
                $results = CECOM_Organization::getOrganizationSize();
                if (is_array($results)) {
                    foreach ($results as $org_size) {
                        $minus = "-";
                        $max = $org_size->max;
                        $min = $org_size->min;
                        if ($max == "0") {
                            $max = $max - 1;
                            $max = "+";
                            $minus = "";
                        } elseif ($min == $max) {
                            $minus = "";
                            $max = "";
                        }

                        if ($cecom->organization->details['size_min'] == $org_size->min && $cecom->organization->details['size_max'] == $org_size->max)
                            echo "<option selected=\"selected\" value = '{$org_size->id }'>$min$minus$max</option>";
                        else
                            echo "<option value = '{$org_size->id }'>$min$minus$max</option>";
                    }
                }
                ?>
            </select>
            <br/>
            <label  for="organization_type"><?php _e('Type of Organization', 'firmasite'); ?> </label>
            <select   name="organization_type" id="organization_type" aria-required="false">
                <?php
                //Fetch Organization Types form DB
                $results = CECOM_Organization::getOrganizationType();
                if (is_array($results)) {

                    foreach ($results as $org_type) {
                        if ($cecom->organization->details['type'] == $org_type->description)
                            echo "<option selected=\"selected\" value = '{$org_type->id }'>{$org_type->description}</option>";
                        else
                            echo "<option value = '{$org_type->id }'>{$org_type->description}</option>";
                    }
                }
                ?>
            </select>
            <br/>
            <label  for="organization_sector"><?php _e('Sector', 'firmasite'); ?> </label>
            <select  name="organization_sector" id="organization_sector" class="multiselect" multiple="multiple" >
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
            <br/><br/>
            <label  for="organization_subsector"><?php _e('Subector', 'firmasite'); ?> </label>
            <select name="organization_subsector" id="organization_subsector" class="multiselect" multiple="multiple">
                <?php
                //Fetch Organization Subsectors form DB
                $results = CECOM_Organization::getOrganizationSubsector($cecom->organization->details['sector_id']);
                if (is_array($results)) {

                    foreach ($results as $org_subsector) {
                        if ($cecom->organization->details['subsector_id'] == $org_subsector->id)
                            echo "<option selected=\"selected\" value = '{$org_subsector->id }'>{$org_subsector->description}</option>";
                        else
                            echo "<option value = '{$org_subsector->id }'>{$org_subsector->description}</option>";
                    }
                }
                ?>

            </select><br/>
            <label  for="organization_collaboration"><?php _e('Available for collaboration', 'firmasite'); ?> </label>
            <input type="radio" <?php if ($cecom->organization->details['collaboration']) echo "checked=\"yes\""; ?>  name="organization_collaboration_y" id="organization_collaboration_y" aria-required="false"> &nbsp;<strong>Yes</strong>&nbsp;&nbsp;
            <input type="radio" <?php if (!$cecom->organization->details['collaboration']) echo "checked=\"yes\""; ?> name="organization_collaboration_n" id="organization_collaboration_n"  aria-required="false"> &nbsp;<strong>No</strong>
            <br/>
            <label  for="organization_transaction"><?php _e('Available for transaction', 'firmasite'); ?> </label>&nbsp;&nbsp;&nbsp;
            <input type="radio" <?php if ($cecom->organization->details['transaction']) echo "checked=\"yes\""; ?> name="organization_transaction_y" id="organization_transaction_y" aria-required="false"> &nbsp;<strong>Yes</strong>&nbsp;&nbsp;
            <input type="radio" <?php if (!$cecom->organization->details['transaction']) echo "checked=\"yes\""; ?>name="organization_transaction_n" id="organization_transaction_n" ria-required="false"> &nbsp;<strong>No</strong>

        </div>
        <!-- End of CECOM Organization Custom Fields -->



        <p>
        <hr/>

    </p>

    <?php do_action('bp_after_group_details_admin'); ?>

    <p><input type="submit" class="btn  btn-primary" value="<?php _e('Save Changes', 'firmasite'); ?>" id="save" name="save" /></p>
    <?php wp_nonce_field('groups_edit_group_details'); ?>
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