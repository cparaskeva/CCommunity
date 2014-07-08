<script type = "text/javascript" >
    /* Help functions used for various actions of the Offering Collaboration Component */

    jQuery("#offer-type").change(function() {
        var offer_type = jQuery("#offer-type").val();
        //Handle the different type of offers

        //None is selected
        if (offer_type == "none") {
            jQuery("#collaboration-commons").hide();
            jQuery("#collaboration-commons-extra").hide();
            jQuery("#collaboration-develop").hide();
            jQuery("#collaboration-participate").hide();
        }//Show common fields
        else {
            jQuery("#collaboration-commons").show();

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


    /*
     * Offer sectors covered
     */


    jQuery("#offer_sector").change(function() {

        var selectedTexts = [];

        jQuery(this).find("option:selected").each(function(i) {
            var val = jQuery(this).val();
            var txt = jQuery(this).text();
            selectedTexts[i] = txt;
        });

        //Set the values to a hidden field
        jQuery("#offer-sectors").val(jQuery(this).val());

    });


    //Initialize the sectors multiselect object
    jQuery(document).ready(function() {
        jQuery("#offer_sector").multiselect({numberDisplayed: 1});
        jQuery("#offer-type").val(<?php echo (($_GET['offer_type'] != "all") ? trim($_GET['offer_type'], "/") : "'none'" ) ?>).change();

        if (jQuery("#offer-type").val() == "none")
            jQuery("#offer-type-div").show();
        else
            jQuery("#offers-header").append("<h4>(" + jQuery("#offer-type option:selected").text() + ")</h4>");

    });


</script>
