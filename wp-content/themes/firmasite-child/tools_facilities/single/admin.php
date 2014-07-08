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
        <!-- Country Hidden Field -->
        <input type="hidden" name="tool-facility-country" id='tool-facility-country' value="<?php echo $bp->tools_facilities->current_tool_facility->country_id?>"/>
        <br>
        <label for="tool-facility-description"><?php _e('Describe the tool/facility you want to rent (required)', 'firmasite'); ?></label>
        <?php
        $content = $bp->tools_facilities->current_tool_facility->description;
        echo firmasite_wp_editor($content, 'tool-facility-description');
        ?>
        <br/>
        <!-- Tool's country field -->
        <label  for="tool-facility-country"><?php _e('Country where the tool is available (required)', 'firmasite'); ?></label>
        <div onchange="setCountryValue()"  class="bfh-selectbox bfh-countries" data-country="<?php echo $bp->tools_facilities->current_tool_facility->country_id ?>" data-flags="true"> </div>
        <br/>
        <label for="tool-facility-location"><?php _e('Location of the tool (required)', 'firmasite'); ?></label>
        <input  name="tool-facility-location" id="tool-facility-location" value="<?php echo $bp->tools_facilities->current_tool_facility->location ?>"/>
        <br/>
        <label for="tool-facility-payment"><?php _e('Payment qualification (required)', 'firmasite'); ?></label>
        <select name="tool-facility-payment" id="tool-facility-payment">
            <?php
            //Fetch Payment Qualification Types form DB
            $results = BP_Tool_Facility::getPaymentTypes();
            if (is_array($results)) {
                foreach ($results as $tool_facility_payment) {
                    if ($bp->tools_facilities->current_tool_facility->payment_id == $tool_facility_payment->id)
                        echo "<option selected='selected' value = '{$tool_facility_payment->id }'>{$tool_facility_payment->description}</option>";
                    else
                        echo "<option value = '{$tool_facility_payment->id }'>{$tool_facility_payment->description}</option>";
                }
            }
            ?>
        </select>
        <br/>
        <label for="tool-facility-operation"><?php _e('How the tool/facility is operated? (required)', 'firmasite'); ?></label>
        <select  class="form-control" name="tool-facility-operation" id="tool-facility-operation" aria-required="false">
            <?php
            //Fetch Operation Types form DB
            $results = BP_Tool_Facility::getOperationTypes();
            if (is_array($results)) {

                foreach ($results as $tool_facility_operation) {

                    if ($bp->tools_facilities->current_tool_facility->operation_id == $tool_facility_operation->id)
                        echo "<option selected='selected' value = '{$tool_facility_operation->id }'>{$tool_facility_operation->description}</option>";
                    else
                        echo "<option value = '{$tool_facility_operation->id }'>{$tool_facility_operation->description}</option>";
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
        <p><?php _e('WARNING: Deleting this tool/facility will completely remove ALL content associated with it. There is no way back, please be careful with this option.', 'firmasite'); ?></p>
    </div>

    <label><input type="checkbox" name="delete-tool_facility-understand" id="delete-group-understand" value="1" onclick="if (this.checked) {
                document.getElementById('delete-tool_facility-button').disabled = '';
            } else {
                document.getElementById('delete-tool_facility-button').disabled = 'disabled';
            }" /> <?php _e('I understand the consequences of deleting this tool/facility.', 'firmasite'); ?></label>


    <div class="submit">
        <input type="submit" class="btn  btn-primary" disabled="disabled" value="<?php _e('Delete Tool/Facility', 'firmasite'); ?>" id="delete-tool_facility-button" name="delete-tool_facility-button" />
        <br/><br/>
    </div>

    <?php wp_nonce_field('tools_facilities_delete_tool_facility'); ?>

<?php endif; ?>

<?php /* This is important, don't forget it */ ?>
<input type="hidden" name="tool_facility-id" id="tool_facility-id" value="<?php echo $bp->tools_facilities->current_tool_facility->id; ?>" />

<?php do_action('bp_after_group_admin_content'); ?>
</form><!-- #tool_facility-settings-form -->

<script type="text/javascript">


    function setCountryValue() {
        jQuery("#tool-facility-country").val(jQuery(".bfh-selectbox").val());
    }

</script>