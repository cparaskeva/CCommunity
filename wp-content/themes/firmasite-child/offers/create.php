<?php
/**
 * CECommunity - Create Offer
 *
 */
global $firmasite_settings;
get_header('buddypress');

//do_action("wp_enqueue_cecom_scripts");

/* Import JS files */
wp_enqueue_script('bootstrap-multiselect');

/* Import CSS files */
wp_enqueue_style('bootstrap-multiselect-style');
?>


<div id="primary" class="content-area <?php echo $firmasite_settings["layout_primary_class"]; ?>">
    <div class="padder">
        <form action="" method="post" id="offer-collaboration-form" class="standard-form" enctype="multipart/form-data">
            <h3 class="page-header"><?php _e('Create an Offer ', 'firmasite'); ?> &nbsp;</h3>
            <?php do_action('template_notices'); ?>
            <div class="item-body" id="group-create-body">
                <!-- Hidden Fields for Offer Sectors covered-->   
                <input type="hidden" class="form-control" name="offer-sectors" id="offer-sectors" value=""/>
                <!-- End of Hidden Fields -->
                <label for="offer-type"><?php _e('Type of offer (required)', 'firmasite'); ?></label>
                <select name="offer-type" id="offer-type">
                    <option value="none"  selected="selected">Please select..</option>
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
                <div hidden="true" id="collaboration-commons">
                    <div hidden="true" id="collaboration-commons-extra">
                        <label for="collaboration-type"><?php _e('Type of collaboration (required)', 'firmasite'); ?></label>
                        <select name="collaboration-type" id="collaboration-type">
                            <option value="none"  selected="selected"> Please select..</option>
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
                    </div>
                    <label for="collaboration-description"><?php _e('Description of the offer (required)', 'firmasite'); ?></label>
                    <textarea rows="3" type="text" name="collaboration-description" id="collaboration-description" aria-required="true" ></textarea> 
                    <br/>
                </div>

                <!-- Offer type: Collaboration to develop products and services -->
                <div name="collaboration-develop" id="collaboration-develop" hidden="true">
                    <label for="collaboration-partner-sought"><?php _e('Type of partner sought (required)', 'firmasite'); ?></label>
                    <select name="collaboration-partner-sought" id="collaboration-partner-sought">
                        <option value="none"  selected="selected"> Please select..</option>
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
                <!-- Offer type: Funding -->
                <div name="offer-funding" id="offer-funding" hidden="true">
                    <label for="applyable-countries"><?php _e('Applyable countries (required)', 'firmasite'); ?></label>
                    <select  class="form-control" name="applyable-countries" id="applyable-countries" aria-required="false">
                        <option value="none">Please select...</option>
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
                    <label for="finance-stage"><?php _e('Financing Stage (required)', 'firmasite'); ?></label>
                    <select  class="form-control" name="finance-stage" id="finance-stage" aria-required="false">
                        <option value="none">Please select...</option>
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
                </div>
                <!-- Offer type: Collaboration to participate to funded projects -->
                <div hidden="true" id="collaboration-participate">
                    <label for="collaboration-programs"><?php _e('Grant Programms (required)', 'firmasite'); ?></label>
                    <select name="collaboration-programs" id="collaboration-programs">
                        <option value="none">Please select..</option>
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
                <!-- Submit Div-->
                <div align="right" class="submit" >
                    <hr>
                    <div align="left"><a align="left" href="<?php echo bp_loggedin_user_domain() . bp_get_offers_root_slug() ?>" title="Are you lost?">&larr; Back to Collaboration Offers</a></div>
                    <input type="submit" class="btn  btn-primary" name="offer_submit" id="offer_submit" value="<?php _e('Publish your proposal', 'firmasite'); ?>" >
                    <?php wp_nonce_field('offers_create_offer'); ?>
                    <br/><br/>
                </div>

            </div>
        </form>

    </div><!-- .padder -->
</div><!-- #content -->


<?php
//Load JavaScript Files
include(get_stylesheet_directory() . "/offers/offers.js");
//Load CECommunity Sidebar
get_sidebar('buddypress');
//Load CECommunity Footer
get_footer('buddypress');
?>
