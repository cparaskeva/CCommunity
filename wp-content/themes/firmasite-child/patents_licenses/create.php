<?php
/**
 * CECommunity - Create Patent & License
 *
 */
global $firmasite_settings;
get_header('buddypress');


/* Import JS files */
wp_enqueue_script('bootstrap-multiselect');

/* Import CSS files */
wp_enqueue_style('bootstrap-multiselect-style');
?>


<div id="primary" class="content-area <?php echo $firmasite_settings["layout_primary_class"]; ?>">
    <div class="padder">
        <form action="" method="post" id="patent_license-form" class="standard-form" enctype="multipart/form-data">
            <h3  id="patents_licenses-header" class="page-header"><?php _e('Create an offer <h4>(Patent or License)</h4> ', 'firmasite'); ?> &nbsp;</h3>
            <p>Creating this kind of offer, you can propose a license or a patent to interested organisations. You can also search for a patent or license sold by other organisations.</p>
	    <br>
	    <?php do_action('template_notices'); ?>
            <div class="item-body" id="group-create-body">
                <!-- Hidden Fields for Organization Sectors and Subsectors covered-->   
                <input  type="hidden" class="form-control" name="patent-license-sectors" id="patent-license-sectors" value=""/>
                <input  type="hidden" class="form-control" name="patent-license-subsectors" id="patent-license-subsectors" value=""/>
                <!-- End of Hidden Fields -->
                <label for="patent-license-type"><?php _e('What are you offering? (required)', 'firmasite'); ?><span data-toggle="tooltip" data-placement="left" 
			title="Choose 'Licensing in' if you want to buy or get rights of a patent or licence.
			Choose 'Licensing out' if you want to sell or rent rights on a patent or licence belonging to your organisation" 									class="glyphicon glyphicon-question-sign"></span></label>
                <select  name="patent-license-type" id="patent-license-type">
                    <option value="none"  selected="selected">Please select..</option>
                    <?php
                    //Fetch Offer Types form DB
                    $results = BP_Patent_License::getPatent_LicenseTypes();
                    if (is_array($results)) {
                        foreach ($results as $patent_license_type) {
                            echo "<option value = '{$patent_license_type->id }'>{$patent_license_type->description}</option>";
                        }
                    }
                    ?>
                </select>
                <br/>
                <label for="patent-license-description"><?php _e('Describe the patent/licence you want to offer (required)', 'firmasite'); ?></label>
                <textarea rows="3" type="text" name="patent-license-description" id="patent-license-description" aria-required="true" ></textarea> 
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
                <label for="organization_subsector"><?php _e('Subsector', 'firmasite'); ?> </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <select  class="multiselect" name="organization_subsector" id="organization_subsector" multiple="multiple">
                </select>
                <br/><br/>
                <label for="patent-license-exchange"><?php _e('Type of exchange (required)', 'firmasite'); ?></label>
                <select name="patent-license-exchange" id="patent-license-exchange">
                    <option value="none"  selected="selected"> Please select..</option>
                    <?php
                    //Fetch Collaboration Types form DB
                    $results = BP_Patent_License::getExchangeTypes();
                    if (is_array($results)) {
                        foreach ($results as $patent_license_exchange) {
                            echo "<option value = '{$patent_license_exchange->id }'>{$patent_license_exchange->description}</option>";
                        }
                    }
                    ?>
                </select>
                <br/>
                <label for="patent-license-countries"><?php _e('Geographical coverage (required)', 'firmasite'); ?></label>
                <select  class="form-control" name="patent-license-countries" id="patent-license-countries" aria-required="false">
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
                <!-- Submit Div-->
                <div align="right" class="submit" >
                    <hr>
                    <div align="left"><a align="left" href="<?php echo bp_loggedin_user_domain() . bp_get_patents_licenses_root_slug() ?>" title="Are you lost?">&larr; Back to Patents & Licenses</a></div>
                    <input type="submit" class="btn  btn-primary" name="patent_license_submit" id="patent_license_submit" value="<?php _e('Publish your proposal', 'firmasite'); ?>" >
                    <?php wp_nonce_field('patents_licenses_create_patent_license'); ?>
                    <br/><br/>
                </div>

            </div>
        </form>

    </div><!-- .padder -->
</div><!-- #content -->

<?php
//Load JavaScript Files
include(get_stylesheet_directory() . "/patents_licenses/patents_licenses.php");
//Load CECommunity Sidebar
get_sidebar('buddypress');
//Load CECommunity Footer
get_footer('buddypress');
?>
