<?php
global $bp;
$details = $bp->tools_facilities->current_tool_facility->get_tool_facility_details();
//print_r($details);
?>
<div class="item-list-tabs no-ajax tabs-top" id="subnav" role="navigation">
    <ul class="nav nav-pills">
        <?php bp_tool_facility_admin_tabs(); ?>
    </ul>
</div><!-- .item-list-tabs -->

<form action="<?php bp_tool_facility_admin_form_action(); ?>" name="tool_facility-settings-form" id="tool_facility-settings-form" class="standard-form" method="post" enctype="multipart/form-data" role="main">
    <?php if (bp_is_tool_facility_admin_screen('edit-details')) : ?>

        <?php do_action('bp_before_group_details_admin'); ?>

        <!-- Hidden Fields for Organization Sectors and Subsectors covered-->   
        <input  type="hidden" class="form-control" name="patent-license-sectors" id="patent-license-sectors" value=""/>
        <input  type="hidden" class="form-control" name="patent-license-subsectors" id="patent-license-subsectors" value=""/>
        <!-- End of Hidden Fields -->
        <br>
        <label for="patent-license-type"><?php _e('What are you offering? (required)', 'firmasite'); ?></label>
        <select  name="patent-license-type" id="patent-license-type">
            <?php
            //Fetch Patent/License Types form DB
            $results = BP_Patent_License::getPatent_LicenseTypes();
            if (is_array($results)) {
                foreach ($results as $tool_facility_type) {
                    if ($bp->tools_facilities->current_tool_facility->type_id == $tool_facility_type->id)
                        echo "<option selected='selected' value = '{$tool_facility_type->id }'>{$tool_facility_type->description}</option>";
                    else
                        echo "<option value = '{$tool_facility_type->id }'>{$tool_facility_type->description}</option>";
                }
            }
            ?>
        </select>
        <br/>
        <label for="patent-license-description"><?php _e('Describe the patent/licence you want to offer (required)', 'firmasite'); ?></label>
        <?php
        $content = $bp->tools_facilities->current_tool_facility->description;
        echo firmasite_wp_editor($content, 'patent-license-description');
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
        <label for="organization_subsector"><?php _e('Subsector', 'firmasite'); ?> </label>
        <select  class="multiselect" name="organization_subsector" id="organization_subsector" multiple="multiple">
        </select>
        <br/><br/>
        <label for="patent-license-exchange"><?php _e('Type of exchange (required)', 'firmasite'); ?></label>
        <select name="patent-license-exchange" id="patent-license-exchange">
            <?php
            //Fetch Collaboration Types form DB
            $results = BP_Patent_License::getExchangeTypes();
            if (is_array($results)) {
                foreach ($results as $tool_facility_exchange) {
                    if ($bp->tools_facilities->current_tool_facility->exchange_id == $tool_facility_exchange->id)
                        echo "<option selected='selected' value = '{$tool_facility_exchange->id }'>{$tool_facility_exchange->description}</option>";
                    else
                        echo "<option value = '{$tool_facility_exchange->id }'>{$tool_facility_exchange->description}</option>";
                }
            }
            ?>
        </select>
        <br/>
        <label for="patent-license-countries"><?php _e('Geographical coverage (required)', 'firmasite'); ?></label>
        <select  class="form-control" name="patent-license-countries" id="patent-license-countries" aria-required="false">
            <?php
            //Fetch All Countries form DB
            $results = CECOM_Organization::getAllCountries();
            if (is_array($results)) {
                foreach ($results as $country) {
                    if ($bp->tools_facilities->current_tool_facility->country_id == $country->id)
                        echo "<option selected='selected' value = '{$country->id }'>{$country->name}</option>";
                    else
                        echo "<option value = '{$country->id }'>{$country->name}</option>";
                }
            }
            ?>
        </select>    
        <br/>
        <p>
        <hr/>
    </p>
    <?php do_action('bp_after_group_details_admin'); ?>

    <p><input type="submit" class="btn  btn-primary" value="<?php _e('Save Changes', 'firmasite'); ?>" id="save" name="save" /></p>
    <?php wp_nonce_field('tools_facilities_edit_tool_facility_details'); ?>
<?php endif; ?>

<?php /* Delete Patent/License Option */ ?>
<?php if (bp_is_tool_facility_admin_screen('delete-tool_facility')) : ?>

    <?php do_action('bp_before_group_delete_admin'); ?>

    <br/>
    <div class="clearfix"></div><div id="message" class="info alert alert-info">
        <p><?php _e('WARNING: Deleting this patent/license will completely remove ALL content associated with it. There is no way back, please be careful with this option.', 'firmasite'); ?></p>
    </div>

    <label><input type="checkbox" name="delete-tool_facility-understand" id="delete-group-understand" value="1" onclick="if (this.checked) {
                    document.getElementById('delete-tool_facility-button').disabled = '';
                } else {
                    document.getElementById('delete-tool_facility-button').disabled = 'disabled';
                }" /> <?php _e('I understand the consequences of deleting this patent/license.', 'firmasite'); ?></label>


    <div class="submit">
        <input type="submit" class="btn  btn-primary" disabled="disabled" value="<?php _e('Delete Patent/License', 'firmasite'); ?>" id="delete-tool_facility-button" name="delete-tool_facility-button" />
        <br/><br/>
    </div>

    <?php wp_nonce_field('tools_facilities_delete_tool_facility'); ?>

<?php endif; ?>

<?php /* This is important, don't forget it */ ?>
<input type="hidden" name="tool_facility-id" id="tool_facility-id" value="<?php echo $bp->tools_facilities->current_tool_facility->id; ?>" />

<?php do_action('bp_after_group_admin_content'); ?>
</form><!-- #tool_facility-settings-form -->



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
        jQuery("#patent-license-sectors").val(jQuery(this).val());
        jQuery("#patent-license-subsectors").val("");

    });

    /*
     * Organization subsector
     */


    jQuery("#organization_subsector").change(function() {
        //Set the values to hidden field
        jQuery("#patent-license-subsectors").val(jQuery("#organization_subsector").val());
    });

        

    jQuery(document).ready(function() {
        jQuery("#organization_sector").multiselect({numberDisplayed: 1});
        jQuery("#organization_subsector").multiselect({numberDisplayed: 5, maxHeight: 300, enableFiltering: true});

<?php
//Load current sectors of the patent/license only if exist
if (bp_tool_facility_has_sectors()):
    $sector_values = "[";
    $sector_txt = "[";
    foreach ($bp->tools_facilities->current_tool_facility->sectors as $sector) {
        $sector_values .= "'" . $sector['id'] . "',";
        $sector_txt .= "'" . $sector['description'] . "',";
    }
    $sector_values = substr($sector_values, 0, -1) . "]";
    $sector_txt = substr($sector_txt, 0, -1) . "]";
    echo 'jQuery("#organization_sector").multiselect("select",' .$sector_values.');';
    echo 'jQuery("#patent-license-sectors").val(jQuery("#organization_sector").val());';
endif;
//Load current subsectors of the patent/license only if exist
if (bp_tool_facility_has_subsectors()):
    $subsector_values = "[";
    foreach ($bp->tools_facilities->current_tool_facility->subsectors as $subsector) {
        $subsector_values .= "'" . $subsector['id'] . "',";
    }
    $subsector_values = substr($subsector_values, 0, -1) . "]";
    echo 'setSubsctorValues('. $sector_values .','. $sector_txt.','. $subsector_values.');';
    else:{
        echo 'jQuery("#organization_subsector").multiselect("dataprovider", [{label: "optgroup", value: "(select sector first)"}]);';
        if (bp_tool_facility_has_sectors())
            echo 'setSubsctorValues('. $sector_values .','. $sector_txt.',"");';
    }
endif;

?>

    });

</script>