/*
 * 
 * Helpful Functions used for CECommunity Platform
 * Author Chris P.
 * 
 */

/* Dynmically change the values of subsector list based on the choosen sector(s) */

//Required sectors,selectedTexts, Optional subsectors
function setSubsctorValues(sectors,selectedTexts,subsectors) {

    //Clear all values from subsector list and set the default one, not yet a sector is choosen   
    if (!sectors)
        jQuery("#organization_subsector").multiselect('dataprovider', [{label: "optgroup", value: "(select sector first)"}]);
    else {
        //Ajax Call to retrieve the respectively subsectors based on the choosen sector(s)
        
        jQuery.ajax({
            url: ajaxurl,
            dataType: "jsonp",
            data: {
                action: "custom_register_user",
                format: "json",
                sectors: sectors,
                operation: "getSubsectors"
            },
            success: function(data) {
                //Results Size + Sectors Description Size
                resultsSize = data.length + sectors.length;
                results= new Array(resultsSize);
                tmp=0;
                iter=0;
                jQuery.each(data, function(i, item) {
                
                if (item.sid !==tmp){
                    results[i+iter] = {"label":"optgroup","value":selectedTexts[iter].substring(0,25)+"..." };
                     tmp=item.sid;
                     iter++;
                }
                results[i+iter] = {"label":item.description,"value":item.id }; 

                });
                jQuery("#organization_subsector").multiselect('dataprovider', results);
                
                //Preselect subsectors if given
                if(subsectors){
                    jQuery("#organization_subsector").multiselect('select', subsectors);
                    //Set the values to hidden fields
                    jQuery("#organization_sectors").val(jQuery("#organization_sector").val());
                    jQuery("#organization_subsectors").val(jQuery("#organization_subsector").val());
                }
            },
            error: function() {
                alert("Unresolved error happened. Please try again!");

            }
        });

    }


    /*function setSubsctorValues(sectors) {
     
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
     
     }*/

}







