/*
 * 
 * Helpful Functions used for CECommunity Platform
 * Author Chris P.
 * 
 */




/* Dynmically change the values of subsector list based on the choosen sector*/

function setSubsctorValues() {
    sector = jQuery("#organization_sector").val();
    //Clear all values from subsector list and set the default one, not yet a sector is choosen
    if (sector == "none")
        jQuery("#organization_subsector").empty().append('<option value="none">(No sector is selected)</option>').val('none');
    else {
        jQuery.ajax({
            url: ajaxurl,
            dataType: "jsonp",
            data: {
                action: "custom_register_user",
                format: "json",
                sector_id: sector,
                operation: "load_subsector"
            },
            success: function(data) {
                var select = jQuery('#organization_subsector').empty();
                jQuery("#organization_subsector").append('<option value="none">Please select...</option>').val('none');
                jQuery.each(data, function(i, item) {
                    select.append('<option value="'
                            + item.id
                            + '">'
                            + item.description
                            + '</option>');
                });
            },
            error: function() {
                alert("Unresolved error happened. Please try again!");

            }
        });

    }

}