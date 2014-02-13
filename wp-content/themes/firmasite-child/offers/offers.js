<script type = "text/javascript" >
        /* Help functions used for various actions of the Offering Collaboration Component */

        jQuery("#offer-type").change(function() {
    var offer_type = jQuery("#offer-type").val();
    //Handle the different type of offers

    //None is selected
    if (offer_type == "none") {
        jQuery("#collaboration-commons").hide();
        jQuery("#collaboration-develop").hide();
        jQuery("#collaboration-participate").hide();
    }//Show common fields
    else {
        jQuery("#collaboration-commons").show();

    }

    //Offer type: Develop products and services
    if (offer_type == "1") {
        jQuery("#collaboration-participate").hide();
        jQuery("#collaboration-develop").show();

    }//Offer type: Participate to funded projects
    else if (offer_type == "2") {
        jQuery("#collaboration-develop").hide();
        jQuery("#collaboration-participate").show();

    }

});


/*
 *  AJAX CALL FUCNTIONS  
 */

/* Ajax Call Implementation for posting an offer */
jQuery("#offer-collaboration-formaaa").submit(function(event) {
  
  //alert("is ok");
  
   /* Stop form from submitting normally */
   event.preventDefault();
  
  
  var values = "action=create_offer&" + jQuery(this).serialize();

    /* Send the data using post and put the results in a div */
    jQuery.ajax({
            url: ajaxurl,
            type: "get",
            data: values,
            success: function(response) {

                alert(response);


            },
            error: function() {
            alert("Unresolved error happened. Please try again!");
            }
    });
});
</script>
