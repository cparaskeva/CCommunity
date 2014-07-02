<?php
global $bp;
$details = $bp->offers->current_offer->get_offer_details();
//print_r($details);
?>
<div class="item-list-tabs no-ajax tabs-top" id="subnav" role="navigation">
    <ul class="nav nav-pills">
        <?php bp_offer_admin_tabs(); ?>
    </ul>
</div><!-- .item-list-tabs -->



<?php /* This is important, don't forget it */ ?>
<input type="hidden" name="offer-id" id="offer-id" value="<?php echo $bp->offers->current_offer->id; ?>" />

<?php do_action('bp_after_group_admin_content'); ?>
</form><!-- #offer-settings-form -->



<script type="text/javascript">

    /*
     * Organization sector
     */


    jQuery("#offer_sector").change(function() {

        var selectedTexts = [];

        jQuery(this).find("option:selected").each(function(i) {
            var val = jQuery(this).val();
        });

        //Set the values to hidden field
        jQuery("#offer-sectors").val(jQuery(this).val());

    });


    //Initialize the sectors multiselect object
    jQuery(document).ready(function() {
        jQuery("#offer_sector").multiselect({numberDisplayed: 1});

//If and only if current offer has sectors and is type of "Funding" enter
<?php
if (bp_offer_has_sectors()):
    $sector_values = "[";
    $sector_txt = "[";
    foreach ($bp->offers->current_offer->sectors as $sector) {
        $sector_values .= "'" . $sector['id'] . "',";
        $sector_txt .= "'" . $sector['description'] . "',";
    }
    $sector_values = substr($sector_values, 0, -1) . "]";
    $sector_txt = substr($sector_txt, 0, -1) . "]";
    ?>


            //Set the selected sector options of the current offer
            jQuery("#offer_sector").multiselect('select', <?php echo $sector_values; ?>);

            //Store the sector values to the hidden field
            jQuery("#offer-sectors").val(jQuery("#offer_sector").val());

<?php endif; ?>
    });
</script>