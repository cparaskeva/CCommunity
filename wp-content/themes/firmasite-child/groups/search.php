<?php
/**
 * Implemantation of search functionality for CECommunity Organisation Component
 *
 * @author Chris P.
 */
?>

<br>
<div class="panel panel-default">
    <div class="panel-body">
        <!-- Main Organization Search Fields Area-->
        <form id="organization_serach_extras" class="page">
            <!-- Hidden Fields for Organization Sectors and Subsectors covered-->   
            <input type="hidden" class="form-control" name="organization-sectors" id="organization-sectors" value=""/>
            <input type="hidden" class="form-control" name="organization-subsectors" id="organization-subsectors" value=""/>
            <!-- End of Hidden Fields -->

            <!-- Search form first column -->
            <div class="col-xs-12 col-md-3">
                <!-- Organization country field -->
                <label  for="organization-country"><?php _e('Organisation country', 'firmasite'); ?></label>
                <div id="organization-country" class="bfh-selectbox bfh-countries" data-country="" data-flags="true"> </div>
                <br/>
                <!-- Organization size field -->
                <label  for="organization-size"><?php _e('Organization Size', 'firmasite'); ?> </label>
                <select  class="form-control" name="organization-size" id="organization-size">
                    <option value="none">(Anyone)</option>
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
                            echo "<option value = '{$org_size->id }'>$min$minus$max</option>";
                        }
                    }
                    ?>
                </select>
                <br/>
                <!-- Organization type field -->
                <label  for="organization-type"><?php _e('Type of Organization', 'firmasite'); ?> </label>
                <select  class="form-control" name="organization-type" id="organization-type" aria-required="false">
                    <option value="none">(Anyone)</option>
                    <?php
                    //Fetch Organization Types form DB
                    $results = CECOM_Organization::getOrganizationType();
                    if (is_array($results)) {

                        foreach ($results as $org_type) {
                            echo "<option value = '{$org_type->id }'>{$org_type->description}</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <!-- Search form second column -->
            <div class="col-xs-12 col-md-4" >
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
            </div>
        </form>
    </div>
</div>


<!-- Inline CSS code -->
<style>
    .multiselect-group {text-decoration: underline; }        
</style>

<!-- Inline Javascript code -->
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
        jQuery("#organization-sectors").val(jQuery(this).val());
        jQuery("#organization-subsectors").val("");

    });

    /*
     * Organization subsector
     */
    jQuery("#organization_subsector").change(function() {
        //Set the values to hidden field
        jQuery("#organization-subsectors").val(jQuery("#organization_subsector").val());

    });

    //Initialize the sectors multiselect object
    jQuery(document).ready(function() {
        jQuery("#organization_sector").multiselect({numberDisplayed: 0});
        jQuery("#organization_subsector").multiselect({numberDisplayed: 0, maxHeight: 400, enableFiltering: true});
        //Set subsector list values
        setSubsctorValues();
    });
</script>