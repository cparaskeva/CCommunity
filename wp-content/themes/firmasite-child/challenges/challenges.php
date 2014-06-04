<script type = "text/javascript" >
    /* Help functions used for various actions of the Challenges Component */

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
        jQuery("#challenge-sectors").val(jQuery(this).val());
    });

    //Initialize the sectors multiselect object
    jQuery(document).ready(function() {
        jQuery("#organization_sector").multiselect({numberDisplayed: 0});
    });


</script>
