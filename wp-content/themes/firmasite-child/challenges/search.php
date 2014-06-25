<?php
/**
 * Implemantation of search functionality for CECommunity Challenges Component
 *
 * @author Chris P.
 */
?>

<br>
<div class="panel panel-default">
    <div class="panel-body">
        <form id="challenge_serach_extras" class="page">

            <div class="col-xs-12 col-md-4" name="offer-funding" id="offer-funding"  > <!-- class="col-xs-12 col-md-3"-->

                <!-- Hidden Fields for Organization Sectors and Subsectors covered-->   
                <input  type="hidden" class="form-control" name="challenge-sectors" id="challenge-sectors" value=""/>             
                <!-- End of Hidden Fields -->
                <label for="challenge-type"><?php _e('Minimum amount of the reward', 'firmasite'); ?></label>
                <input  class="form-control" name="challenge-reward" id="challenge-reward" value=""/>
                <!--<label for="challenge-type"><?php //_e('What are you looking for?', 'firmasite'); ?></label>
                <select  name="challenge-type" id="challenge-type">
                    <option value="none"  selected="selected">(Any)</option>
                    <?php /*
                    //Fetch Offer Types form DB
                    $results = BP_Patent_License::getPatent_LicenseTypes();
                    if (is_array($results)) {
                        foreach ($results as $challenge_type) {
                            echo "<option value = '{$challenge_type->id }'>{$challenge_type->description}</option>";
                        }
                    }*/
                    ?>
                </select>-->
                <br/>
            </div>

            <div hidden="hidden" class="col-xs-12 col-md-4">
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

            </div>
        </form>
    </div>
</div>

<?php //Load JavaScript Files
include(get_stylesheet_directory() . "/challenges/challenges.php"); ?>
