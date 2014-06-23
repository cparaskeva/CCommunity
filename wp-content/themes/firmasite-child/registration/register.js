<link rel="stylesheet" href='<?php echo get_stylesheet_directory_uri()."/assets/css/jquery-ui.css" ?>'> 
<style>
 .ui-autocomplete-loading {
    background: white url('<?php echo get_stylesheet_directory_uri() . "/assets/img/imgloader.gif" ?>') right center no-repeat;    }

.multiselect-group {

text-decoration: underline;
}        
        
</style>
<script src='<?php echo get_stylesheet_directory_uri()."/assets/js/jquery-ui.js" ?>'></script>
<script type = "text/javascript" >

/*
 *  AJAX CALL FUCNTIONS  
 */

/* Ajax Call Implementation for registration step1 */
jQuery("#register_step1").submit(function(event) {

    /* Stop form from submitting normally */
    event.preventDefault();

    /* Clear errors div*/
    jQuery("#current-step-errors").html('');
    jQuery("#current-step-errors").hide();

    /* Get some values from elements on the page: */
    var values = "action=custom_register_user&" + jQuery(this).serialize();

    /* Send the data using post and put the results in a div */
    jQuery.ajax({
        url: ajaxurl,
        type: "post",
        data: values,
        success: function(response) {

            //Clear the spaces of the response (trim)
            errors= response.trim();           
            //Identify each error by "|" charcter
            errors = errors.split('|');

            if (errors[0] == "step1_done") {
                
                //Hide Step1 Form
                document.getElementById("register_step1").style.setProperty("display", "none");
                //Show Step2 Form
                document.getElementById("register-page-step2").style.setProperty("display", "block");
               //Change Progress Bar Status
                jQuery("#progress_bar").css("width","80%");
                document.getElementById("progress_bar").style.setProperty("width", "67%");
               //Change registratio status to Step2
                jQuery("#register_step").val("step2");
                
                //Check if an already registered organization is found
                    //errors[1]-> group ID, errors[2] -> organization name, errors[3] -> organization website
                if (errors[1] > 0 && errors[2].length>0 && errors[3].length>0){
                    jQuery("#organization_exist_div").show();
                    jQuery("#organization_exist_warning").append("The organisation <b>"
                            +errors[2] + "( <a target=\"_blank\" href=\""+ errors[3] +"\">"+ errors[3]+"</a> )" + "</b> is already registered in the platform."
                            +" Therefore if you belong to this organisation click the submit button or choose you organisation from the list below."
                            +"<br/><br/><p><strong><em>Please note that if your organisation is not listed you can registered it either manually or "
                            +"using your LinkedIn Company Profile.</em></strong></p>");
                    jQuery("#cecom_organization_id").val(errors[1]);
                }
                
                
                //Set subsector list values
                setSubsctorValues();
               
            }
            else {
                jQuery("#current-step-errors").show();
                jQuery("#current-step-errors").append("<h4 style=\"color:gray\">The following errors occured: </h4><hr/>");
                for (var i = 0; i < errors.length; i++) {
                    jQuery("#current-step-errors").append(errors[i]+"<br>");
                }
            }

        },
        error: function() {
            alert("Unresolved error happened. Please try again!");
        }
    });
});

/* Ajax Call Implementation for registration step2 */
jQuery("#register_step2").submit(function(event) {

    /* Stop form from submitting normally */
    event.preventDefault();

    /* Clear errors div*/
    jQuery("#current-step-errors").html('');
    jQuery("#current-step-errors").hide();
    
    
    var organization_sectors= "organization_sectors="+jQuery("#organization_sector").val() +"&";
    var organization_subsectors= "organization_subsectors="+jQuery("#organization_subsector").val() +"&";

    /*  Get the value of the checkbox that defines if user selected an already registered organization or not */
    var isOrganizationListed = "&organization_listed=" +jQuery("#organization_details_checkbox").is(":checked");
    
    
    /* Check if user register an organization which is not listed */
    var organizationFlag="";
    if (jQuery("#cecom_organization_id").is(':disabled'))
        organizationFlag = "&cecom_organization_id="+jQuery("#cecom_organization_id").val();
                
    /* Get some values from elements on the page: */
    var values = "action=custom_register_user&" 
                 +jQuery(this).serialize()+"&organization_country="
                 +jQuery(".bfh-selectbox").val()+"&"
                 +organization_sectors
                 +organization_subsectors
                 +jQuery("#register_step1").serialize()
                 +isOrganizationListed
                 +organizationFlag;
                

    /* Send the data using post and put the results in a div */
    jQuery.ajax({
        url: ajaxurl,
        type: "post",
        data: values,
        success: function(response) {
            
            //Clear the spaces of the response (trim)
            errors= response.trim();     
            //Identify each error by "|" charcter
            errors = errors.split('|');

            //Fatal error 
            if (errors == -1){
                alert("Unresolved error happened. Please repeat the registration process...");
                document.location.reload(true); 
                return;
            }
            //Registration Step2 is success
            if (errors == 1) {
                //Hide Step2 Form
                document.getElementById("register-page-step2").style.setProperty("display", "none");
                //Show Step3 Form
                document.getElementById("register-page-step3").style.setProperty("display", "block");
                //Change Progress Bar Status
                document.getElementById("progress_bar").style.setProperty("width", "100%");
            }
            else {
                jQuery("#current-step-errors").show();
                jQuery("#current-step-errors").append("<h4 style=\"color:gray\">The following errors were occured: </h4><hr/>");
                for (var i = 0; i < errors.length; i++) {
                    jQuery("#current-step-errors").append("<br>" + errors[i]);
                }
            }

        },
        error: function() {
            alert("Unresolved error happened. Please try again!");
        }
    });
});


/* Implementation of the autocomplete input for the linkedin companies */
 jQuery("#organization_name").autocomplete({
        
        disabled: true,
        
        minLength: 3, 
        
        source: function (request, response) {
            jQuery.ajax({
                featureClass: "P",
                style: "full",
                maxRows: 12,
                url: ajaxurl,
                dataType: "jsonp",
                data: {
                    action: "linkedin",
                    format: "json",
                    keyword: request.term,
                    operation: "autocomplete"
                },
                success: function(data) {
                    
                 //Check if the resultset contains any linkedin companies   
                 if (data.companies._total == 0){
                     jQuery("#organization_link").hide();
                     jQuery("#organization_import").hide();
                 }
                 else{
                    jQuery("#organization_link").show();
                    jQuery("#organization_import").attr("type","button");
                    jQuery("#organization_import").show();
                }
                 //TODO: Proper handle of response in case that companies haven't found
                 
                    response( jQuery.map( jQuery.makeArray(data.companies.values), function( item ) {                     
                                             
                       return {
                         label: item.name +" ("+item.websiteUrl+")" ,
                         value: item.name,
                         id: item.id
                       }
                     }));
              },
                
               error: function() {
                    document.getElementById("organization_link").style.setProperty("visibility", "hidden"); 
                    alert("Unresolved error happened. Please try again!");   

               }
            });
        },
        
        select: function( event, ui ) {
           jQuery("#organization_link").attr("href", "http://www.linkedin.com/companies/"+ui.item.id);
           jQuery("#organization_name").val(ui.item.value);
           jQuery("#organization_id").val(ui.item.id);
        return false;
      }
        
              
    }); 
        

/*
 * Retrieve the information of a linkedin company profile 
 * and autocomplete the respectively fields
 * (id,name,description,specialties,website-url,employee-count-range,company-type)
 */
 jQuery( "#organization_import" ).click(function() {
     var organizationID=jQuery("#organization_id").val();

     //Check if a valid organization id is set
     if(organizationID == null || organizationID <=0)
        alert("Organization could not be imported. Please select a proper organization");
    else
    //Implementation of importing a linkedin company
    {
        var btn = jQuery(this);
        btn.button('loading');
        
        /* Ajax call to retrieve the linkedin company information */
        jQuery.ajax({
            url: ajaxurl,
            type: "post",
            data: {
                    action: "linkedin",
                    format: "json",
                    companyID: organizationID,
                    operation: "getCompany"
            },
            
            success: function(response) {
                btn.button('reset');
                //Parse the JSON  response to an Object
                var organization = JSON.parse(response);               
                
                /*Complete the form fields using the imported LinkedIn Company Ptofile*/
                
                //Check if organization description is provided
                if (!(organization.description === undefined))
                    jQuery("#organization_description").val(organization.description);
                else
                    jQuery("#organization_description").val("");
                
                //Check if a website is provided
                if (!(organization.websiteUrl === undefined))
                    jQuery("#organization_website").val(organization.websiteUrl);
                else
                    jQuery("#organization_website").val("");
                
                //Check if specialties are available
                if (!(organization.specialties === undefined)){
                    var specialties= organization.specialties.values.join(",");
                    jQuery("#organization_specialties").val(specialties);                  
                }
                else
                    jQuery("#organization_specialties").val("");    
                        
                //Check if organization size field is provided
                if (!(organization.employeeCountRange === undefined))
                    jQuery("#organization_size").val(organization.employeeCountRange.code);
                else
                    jQuery("#organization_size").val("none");
                                
                //Check if organization type field is provided                
                if (!(organization.companyType === undefined))
                    jQuery("#organization_type").val(organization.companyType.code);  
                else
                    jQuery("#organization_type").val("none");
            },
            error: function() {
                btn.button('reset');
                alert("Unresolved error happened. Please try again!");
            }
        });
        
    }
});
     

/*
 * Checkbox buttons for autocomplete LinkedIn Company Profile Activation
 */

jQuery("#linkedin").click(function(){
    
    //Activate Linked Autocomplete Functionality
    if (jQuery(this).is(":checked")){
        jQuery("#organization_name").attr("placeholder","Type in the name of your company as registered in LinkedIn");
        jQuery("#organization_name" ).autocomplete({ disabled: false });
        jQuery( "#organization_name").autocomplete("search");
    }
    //Deactivate Linked Autocomplete Functionality
    else{
        jQuery("#organization_name").attr("placeholder","Type in the name of your company");
        jQuery("#organization_name").val("");
        jQuery("#organization_link").hide();
        jQuery("#organization_import").hide();
        jQuery("#organization_name" ).autocomplete({ disabled: true });
    }
            
});


/*
 * Checkbox button for showing organization details
 */

jQuery("#organization_details_checkbox").click(function(){
    if (jQuery(this).is(":checked")){
        jQuery("#organization_details").show();
        jQuery("#cecom_organization_id" ).prop("disabled",true)
        jQuery("#cecom_organization_id").val("undefined"); //Reset the value of the organization ID
        jQuery("#organization_exist_div").hide();
    }
    else{
        jQuery("#organization_details").hide();
        jQuery("#cecom_organization_id" ).prop("disabled",false);
    }
            
});
     
    
/*
 * Radio buttons check for Yes/No fields
 */    


//Collaboration Radio Buttons
jQuery("#organization_collaboration_y").click(function(){
    jQuery("#organization_collaboration_n").attr("checked", false);
});

jQuery("#organization_collaboration_n").click(function(){
    jQuery("#organization_collaboration_y").attr("checked", false);
});


//Transaction Radio Buttons
jQuery("#organization_transaction_y").click(function(){
    jQuery("#organization_transaction_n").attr("checked", false);
});

jQuery("#organization_transaction_n").click(function(){
    jQuery("#organization_transaction_y").attr("checked", false);
});



/*
 * Registered organizations list
 */

jQuery("#registered_organizations_list").click(function(){
    event.preventDefault();
    alert("selected");
});


/*
 * Organization sector
 */


jQuery("#organization_sector").change(function() {   
    
var selectedTexts = [];

jQuery(this).find("option:selected").each(function(i){
          var val = jQuery(this).val();
          var txt = jQuery(this).text();
          selectedTexts[i] =   txt;
});

setSubsctorValues(jQuery('.multiselect').val(),selectedTexts);

});


/*
 * Hide the forms of registration step2 & step3 when document is loaded.
 */
jQuery(document).ready(function() {
    document.getElementById("mainmenu").style.setProperty("display", "none");
   // document.getElementById("register-page-step2").style.setProperty("display", "none");
    //document.getElementById("register-page-step3").style.setProperty("display", "none");
    //jQuery("#organization_sector_div").find('.multiselect').multiselect({ numberDisplayed: 1 });
    jQuery("#organization_sector").multiselect({ numberDisplayed: 1 });
    jQuery("#organization_subsector").multiselect({ numberDisplayed: 5 , maxHeight: 300, enableFiltering: true});
    
});

</script>