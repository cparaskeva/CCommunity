<?php
/**
 * Implemantation of search functionality for CECommunity Patents & Licenses Component
 *
 * @author Chris P.
 */
?>

<br>
<div class="panel panel-default">
    <div class="panel-body">
        <form id="patent_license_serach_extras" class="page">

            <div class="col-xs-12 col-md-4" name="offer-funding" id="offer-funding"  >

                <!-- Hidden Fields for Organization Sectors and Subsectors covered-->   
                <input  type="hidden" class="form-control" name="patent-license-sectors" id="patent-license-sectors" value=""/>
                <input  type="hidden" class="form-control" name="patent-license-subsectors" id="patent-license-subsectors" value=""/>
                <!-- End of Hidden Fields -->
                <label for="patent-license-type"><?php _e('What kind of license are you looking for?', 'firmasite'); ?></label>
                <select  name="patent-license-type" id="patent-license-type">
                    <option value="none"  selected="selected">(Any)</option>
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
                <label for="patent-license-exchange"><?php _e('Type of exchange', 'firmasite'); ?></label>
                <select name="patent-license-exchange" id="patent-license-exchange">
                    <option value="none" selected="selected"> (Any)</option>
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
                <label for="patent-license-countries"><?php _e('Geographical coverage', 'firmasite'); ?></label>
                <select  class="form-control" name="patent-license-countries" id="patent-license-countries" aria-required="false">
                    <option value="none">(Any)</option>
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
		<br/><label  for="organization-country"><?php _e('Organisation country', 'firmasite'); ?></label>
                <div id="organization-country" class="bfh-selectbox bfh-countries" data-country="" data-flags="true"> </div>
              
            </div>

	    <div class="col-xs-12 col-md-4">
		<div style="height:36px;"></div>
                <label  for="organization_sector"><?php _e('Sector', 'firmasite'); ?>&nbsp;&nbsp;&nbsp;</label>
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
      		<div style="height:43px;"></div>
          
                <label for="organization_subsector"><?php _e('Subsector', 'firmasite'); ?> &nbsp;&nbsp;&nbsp;</label>
                <select  class="multiselect" name="organization_subsector" id="organization_subsector" multiple="multiple">
                </select>
		 
            </div>


            <div class="col-xs-12 col-md-4">
		<div style="height:12px;"></div>

                <label for="organization-name"><?php _e('Organisation name', 'firmasite'); ?></label>
                <input placeholder="Type in keywords.." type="text" name="organization-name" id="organization-name"  />
                <!-- Organization country field -->
                <!-- Organization type field -->
                <br/><label  for="organization-type"><?php _e('Type of Organisation', 'firmasite'); ?> </label>
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
	<div style="height:22px;"></div>
 		 
            </div>
        </form>
    </div>
</div>

<?php //Load JavaScript Files
include(get_stylesheet_directory() . "/patents_licenses/patents_licenses.php"); ?>
