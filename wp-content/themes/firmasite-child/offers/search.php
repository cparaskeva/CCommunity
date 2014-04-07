<?php
/**
 * Implemantation of search functionality for CECommunity Offers Component
 *
 * @author Chris P.
 */
?>

<br>

<div class="panel panel-default">
    <div class="panel-body">
        <form id="offer_serach_extras" class="page">

            <!-- Hidden Fields for Offer Sectors covered-->   
            <input type="hidden" class="form-control" name="offer-sectors" id="offer-sectors" value=""/>
            <!-- End of Hidden Fields -->

            <div hidden="true" id="offer-type-div" class="col-xs-12 col-md-3">
                <label for="offer-type"><?php _e('Type of offer', 'firmasite'); ?></label>
                <select name="offer-type" id="offer-type">
                    <option value="none"  selected="selected">(Anyone)</option>
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

            <div class="col-xs-12 col-md-3" hidden="true" id="collaboration-commons-extra">
                <label for="collaboration-type"><?php _e('Type of collaboration', 'firmasite'); ?></label>
                <select name="collaboration-type" id="collaboration-type">
                    <option value="none"  selected="selected">(Anyone)</option>
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
                        <option value="none"  selected="selected">(Anyone)</option>
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
                        <option value="none">(Anyone)</option>
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

            <!-- Offer type: Funding -->
            <div class="col-xs-12 col-md-3" name="offer-funding" id="offer-funding" hidden="true" >
                <label for="applyable-countries"><?php _e('Applyable countries', 'firmasite'); ?></label>
                <select  class="form-control" name="applyable-countries" id="applyable-countries" aria-required="false">
                    <option value="none">(Anyone)</option>
                    <?php
                    //Fetch All Countries form DB
                    $results = CECOM_Organization::getAllCountries();
                    if (is_array($results)) {

                        foreach ($results as $country) {
                            echo "<option value = '{$country->id }'>{$country->name}</option>";
                        }
                    }
                    ?>
                </select>
                <br/>
                <label for="finance-stage"><?php _e('Financing Stage', 'firmasite'); ?></label>
                <select  class="form-control" name="finance-stage" id="finance-stage" aria-required="false">
                    <option value="none">(Anyone)</option>
                    <?php
                    //Fetch Financing stages form DB
                    $results = BP_Offer::getFinanceStages();
                    if (is_array($results)) {

                        foreach ($results as $finance_stage) {
                            echo "<option value = '{$finance_stage->id }'>{$finance_stage->description}</option>";
                        }
                    }
                    ?>
                </select>
                <br/>
                <label  for="offer-sector"><?php _e('Sectors', 'firmasite'); ?> </label>
                <select name="offer-sector" id="offer-sector"  class="multiselect" multiple="multiple">
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
            </div>

        </form>
    </div>
</div>


<script type="text/javascript">



//Based on the offer type choosen hide/show respectively offer fields
    jQuery("#offer-type").change(function() {
        var offer_type = jQuery("#offer-type").val();
        //Handle the different type of offers

        //None is selected
        if (offer_type == "none") {
            jQuery("#collaboration-commons-extra").hide();
            jQuery("#collaboration-develop").hide();
            jQuery("#collaboration-participate").hide();
        }


        //Hide all Divs
        jQuery("#collaboration-develop").hide();
        jQuery("#collaboration-participate").hide();
        jQuery("#offer-funding").hide();
        jQuery("#collaboration-commons-extra").hide();


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


    });


    jQuery("#offer-sector").change(function() {

        jQuery(this).find("option:selected").each(function(i) {
            var val = jQuery(this).val();
        });

        //Set the values to a hidden field
        jQuery("#offer-sectors").val(jQuery(this).val());

    });


    //Initialize the sectors multiselect object
    jQuery(document).ready(function() {
        jQuery("#offer-sector").multiselect({numberDisplayed: 1});
        jQuery("#offer-type").val(<?php echo (!empty($_GET['offer_type']) ? $_GET['offer_type'] : "'none'" ) ?>).change();
               
        if (jQuery("#offer-type").val() == "none")
            jQuery("#offer-type-div").show();
        else {
           var offer_type = jQuery("#offer-type option:selected").text();
           if (offer_type == "Offer Funding")
               offer_type = "Find fundings"; 
                   
           jQuery("#offers-header").text(offer_type);
        }

    });
</script>