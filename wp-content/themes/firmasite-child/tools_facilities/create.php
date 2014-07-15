<?php
/**
 * CECommunity - Create Tool/Facility
 *
 */
global $firmasite_settings;
get_header('buddypress');

/* Import JS files */
wp_enqueue_script('bootstrapformhelpers');

/* Import CSS files */
wp_enqueue_style('bootstrapformhelpers-style');
?>


<div id="primary" class="content-area <?php echo $firmasite_settings["layout_primary_class"]; ?>">
    <div class="padder">
        <form action="" method="post" id="tool_facility-form" class="standard-form" enctype="multipart/form-data">
            <h3  id="tools_facilities-header" class="page-header"><?php _e('Create an offer <h4>(Tool/Facility rent)</h4>', 'firmasite'); ?> &nbsp;</h3>
            <?php do_action('template_notices'); ?>
            <div class="item-body" id="group-create-body">
                <!-- Country Hidden Field -->
                <input type="hidden" name="tool-facility-country" id='tool-facility-country'/>
                <label for="tool-facility-description"><?php _e('Describe the tool/facility you want to rent (required)', 'firmasite'); ?></label>
                <textarea rows="3" type="text" name="tool-facility-description" id="tool-facility-description" aria-required="true" ></textarea> 
                <br/>
                <!-- Tool's country field -->
                <label  for="tool-facility-country"><?php _e('Country where the tool is available (required)', 'firmasite'); ?></label>
                <div onchange="setCountryValue()"  class="bfh-selectbox bfh-countries" data-country="" data-flags="true"> </div>
                <br/>
                <label for="tool-facility-location"><?php _e('Location of the tool (required)', 'firmasite'); ?></label>
                <input  name="tool-facility-location" id="tool-facility-location"/>
                <br/>
                <label for="tool-facility-payment"><?php _e('Payment qualification (required)', 'firmasite'); ?></label>
                <select name="tool-facility-payment" id="tool-facility-payment">
                    <option value="none"  selected="selected"> Please select..</option>
                    <?php
                    //Fetch Payment Qualification Types form DB
                    $results = BP_Tool_Facility::getPaymentTypes();
                    if (is_array($results)) {
                        foreach ($results as $tool_facility_payment) {
                            echo "<option value = '{$tool_facility_payment->id }'>{$tool_facility_payment->description}</option>";
                        }
                    }
                    ?>
                </select>
                <br/>
                <label for="tool-facility-operation"><?php _e('How the tool/facility is operated? (required)', 'firmasite'); ?></label>
                <select  class="form-control" name="tool-facility-operation" id="tool-facility-operation" aria-required="false">
                    <option value="none">Please select...</option>
                    <?php
                    //Fetch Operation Types form DB
                    $results = BP_Tool_Facility::getOperationTypes();
                    if (is_array($results)) {

                        foreach ($results as $operation) {
                            echo "<option value = '{$operation->id }'>{$operation->description}</option>";
                        }
                    }
                    ?>
                </select>
                <!-- Submit Div-->
                <div align="right" class="submit" >
                    <hr>
                    <div align="left"><a align="left" href="<?php echo bp_loggedin_user_domain() . bp_get_tools_facilities_root_slug() ?>" title="Are you lost?">&larr; Back to Tools/Facilities</a></div>
                    <input type="submit" class="btn  btn-primary" name="tool_facility_submit" id="tool_facility_submit" value="<?php _e('Publish your proposal', 'firmasite'); ?>" >
                    <?php wp_nonce_field('tools_facilities_create_tool_facility'); ?>
                    <br/><br/>
                </div>

            </div>
        </form>

    </div><!-- .padder -->
</div><!-- #content -->

<script type="text/javascript">


function setCountryValue(){
    jQuery("#tool-facility-country").val(jQuery(".bfh-selectbox").val());
}
    
</script>


<?php
//Load CECommunity Sidebar
get_sidebar('buddypress');
//Load CECommunity Footer
get_footer('buddypress');
?>
