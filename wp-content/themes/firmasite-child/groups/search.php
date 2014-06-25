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
            <!-- Hidden Fields for Organization collaboration and transaction area-->   
            <input type="hidden" class="form-control" name="organization-collaboration" id="organization-collaboration" value=""/>
            <input type="hidden" class="form-control" name="organization-transaction" id="organization-transaction" value=""/>
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
                    <option value="none">(Any)</option>
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
                    <option value="none">(Any)</option>
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


            <div class="col-md-3 pull-right" hidden="true" id="collaboration-commons-extra">
                <br><br><br>
                <label for="collaboration-description"><?php _e('Description of the offer', 'firmasite'); ?></label>
                <input placeholder="Type in keywords.." type="text" name="collaboration-description" id="collaboration-description"  />
                <br>
                <label for="collaboration-type"><?php _e('Type of collaboration', 'firmasite'); ?></label>
                <select name="collaboration-type" id="collaboration-type">
                    <option value="none"  selected="selected">(Any)</option>
                    <?php
                    //Fetch Collaboration Types form DB
                    $results = BP_Offer::getCollaborationTypes();
                    if (is_array($results)) {
                        foreach ($results as $offer_collaboration) {
                            echo "<option value = '{$offer_collaboration->id }'>{$offer_collaboration->description}</option>";
                        }
                    }
                    ?>
                </select>
                <br/>
                <!-- Offer type: Collaboration to develop products and services -->
                <div name="collaboration-develop" id="collaboration-develop" hidden="true">
                    <label for="collaboration-partner-sought"><?php _e('Type of partner sought', 'firmasite'); ?></label>
                    <select name="collaboration-partner-sought" id="collaboration-partner-sought">
                        <option value="none"  selected="selected">(Any)</option>
                        <?php
                        //Fetch Partner sought Types form DB
                        $results = BP_Offer::getPartnerTypes();
                        if (is_array($results)) {
                            foreach ($results as $offer_partner_type) {
                                echo "<option value = '{$offer_partner_type->id }'>{$offer_partner_type->description}</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <!-- Offer type: Collaboration to participate to funded projects -->
                <div hidden="true" id="collaboration-participate">
                    <label for="collaboration-programs"><?php _e('Grant Programms', 'firmasite'); ?></label>
                    <select name="collaboration-programs" id="collaboration-programs">
                        <option value="none">(Any)</option>
                        <?php
                        //Fetch Grant Programs form DB
                        $results = BP_Offer::getGrantPrograms();
                        if (is_array($results)) {

                            foreach ($results as $program) {
                                echo "<option value = '{$program->id }'>{$program->description}</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>

            <!-- Search checkboxes column -->
            <div hidden="true" id="organization-checkboxes" class="col-lg-7" >
                <br>
                <label  for="organization_collaboration"><?php _e('Is the organisation available for collaboration?', 'firmasite'); ?> </label>
                <input type="radio"   name="organization_collaboration_y" id="organization_collaboration_y" aria-required="false"> &nbsp;<strong>Yes</strong>&nbsp;&nbsp;
                <input type="radio"  name="organization_collaboration_n" id="organization_collaboration_n"  aria-required="false"> &nbsp;<strong>No</strong>
                <br>
                <label  for="organization_transaction"><?php _e('Is the organisation available for transaction?', 'firmasite'); ?>&nbsp;&nbsp;</label>
                <input type="radio" name="organization_transaction_y" id="organization_transaction_y" aria-required="false"> &nbsp;<strong>Yes</strong>&nbsp;&nbsp;
                <input type="radio"  name="organization_transaction_n" id="organization_transaction_n" ria-required="false"> &nbsp;<strong>No</strong>
            </div>

            <div  hidden="true" id="offer-type-div" class="col-xs-12 col-md-3">
                <select name="offer-type" id="offer-type">
                    <option value="none"  selected="selected">(Any)</option>
                    <?php
                    //Fetch Grant Programs form DB
                    $results = BP_Offer::getOfferTypes();
                    if (is_array($results)) {
                        foreach ($results as $offer_type) {
                            echo "<option value = '{$offer_type->id }'>{$offer_type->description}</option>";
                        }
                    }
                    ?>
                </select>
                <br/>
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


    //Based on the offer type choosen hide/show respectively offer fields
    jQuery("#offer-type").change(function() {
        var offer_type = jQuery("#offer-type").val();
        //Handle the different type of offers


        //Hide all Divs
        jQuery("#collaboration-develop").hide();
        jQuery("#collaboration-participate").hide();
        jQuery("#offer-funding").hide();
        jQuery("#collaboration-commons-extra").hide();
        jQuery("#organization-checkboxes").hide();
        


        //Offer type: Develop products and services
        if (offer_type == "1") {
            jQuery("#collaboration-develop").show();
            jQuery("#collaboration-commons-extra").show();
        }//Offer type: Participate to funded projects
        else if (offer_type == "2") {
            jQuery("#collaboration-participate").show();
            jQuery("#collaboration-commons-extra").show();
        }//Offer type: Funding
        else if (offer_type == "3") {
            jQuery("#offer-funding").show();
        }
        //None is selected
        if (offer_type == "none") {
            jQuery("#organization-checkboxes").show();
        }


    });

    /*
     * Radio buttons check for Yes/No fields
     */


    //Collaboration Radio Buttons
    jQuery("#organization_collaboration_y").click(function() {
        jQuery("#organization-collaboration").val("1");
        jQuery("#organization_collaboration_n").attr("checked", false);
    });
    jQuery("#organization_collaboration_n").click(function() {
        jQuery("#organization-collaboration").val("0");
        jQuery("#organization_collaboration_y").attr("checked", false);
    });
    //Transaction Radio Buttons
    jQuery("#organization_transaction_y").click(function() {
        jQuery("#organization-transaction").val("1");
        jQuery("#organization_transaction_n").attr("checked", false);
    });

    jQuery("#organization_transaction_n").click(function() {
        jQuery("#organization-transaction").val("0");
        jQuery("#organization_transaction_y").attr("checked", false);
    });
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
        jQuery("#offer-type").val(<?php echo (!empty($_GET['offer_type']) ? $_GET['offer_type'] : "'none'" ) ?>).change();
        if (jQuery("#offer-type").val() != "none")
            jQuery("#offers-header").append("<h4>(Search orgnisations offering '" + jQuery("#offer-type option:selected").text() + "')</h4>");
    });
</script>