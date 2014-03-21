<script type = "text/javascript" >
    /* Help functions used for various actions of the Patents & Licenses Component */

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
        jQuery("#patent-license-sectors").val(jQuery(this).val());
        jQuery("#patent-license-subsectors").val("");
    });
    /*
     * Organization subsector
     */
    jQuery("#organization_subsector").change(function() {
        //Set the values to hidden field
        jQuery("#patent-license-subsectors").val(jQuery("#organization_subsector").val());
    });


    //Initialize the sectors multiselect object
    jQuery(document).ready(function() {
        jQuery("#organization_sector").multiselect({numberDisplayed: 0});
        jQuery("#organization_subsector").multiselect({numberDisplayed: 0, maxHeight: 400, enableFiltering: true});
        //Set subsector list values
        setSubsctorValues();
    });


</script>
