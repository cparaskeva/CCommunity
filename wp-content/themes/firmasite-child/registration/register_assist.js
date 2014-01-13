<script src="http://code.jquery.com/jquery-1.10.2.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css"> 
<style>
 .ui-autocomplete-loading {
    background: white url('<?php echo get_stylesheet_directory_uri() . "/assets/img/imgloader.gif" ?>') right center no-repeat;    }
</style>
<script type = "text/javascript" >


//Define Global Variables
var ajaxURL = "<?php bloginfo('wpurl'); ?>/wp-admin/admin-ajax.php";

/* Ajax Call Implementation for registration step1 */
jQuery("#register_step1").submit(function(event) {

    /* Stop form from submitting normally */
    event.preventDefault();

    /* Clear errors div*/
    jQuery("#current-step-errors").html('');

    /* Get some values from elements on the page: */
    var values = "action=custom_register_user&" + jQuery(this).serialize();

    /* Send the data using post and put the results in a div */
    jQuery.ajax({
        url: ajaxURL,
        type: "post",
        data: values,
        success: function(response) {

            errors = response.split('|');

            if (errors == "step1_done") {

                //Hide Step1 Form
                document.getElementById("register_step1").style.setProperty("display", "none");
                //Show Step2 Form
                document.getElementById("register-page-step2").style.setProperty("display", "block");
                alert("Done!");
            }
            else {
                jQuery("#current-step-errors").append("<h4 style=\"color:gray\">The following errors were occured: </h4><hr/>");
                for (var i = 0; i < errors.length; i++) {
                    jQuery("#current-step-errors").append("<br>" + errors[i]);
                }
            }
            //jQuery("#step1_errors").html(response);

        },
        error: function() {
            alert("Unresolved error happened. Please try again!");
        }
    });
});



 //Implementation of the autocomplete input for the linkedin companies        
 jQuery("#organization_name").autocomplete({
                                 
        minLength: 3, 
        
        source: function (request, response) {
            jQuery.ajax({
                featureClass: "P",
                style: "full",
                maxRows: 12,
                url: ajaxURL,
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
                     //document.getElementById("organization_link").style.setProperty("visibility", "hidden"); 
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
 */
 jQuery( "#organization_import" ).click(function() {
     var organizationID=jQuery("#organization_id");
     
     //Check if a valid organization id is set
     if(organizationID == null || organizationID <=0)
        alert("Organization could not be impoerted. Please try again!");
    else
    //Implementation of importing a linkedin company
    {
        
    }
});
    
    
    
    
/*
 * Radio buttons check for Yes/No fields
 * 
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
 * Hide the forms of registration step2 & step3 when document is loaded.
 */
jQuery(document).ready(function() {
    




    document.getElementById("nav-main").style.setProperty("visibility", "hidden");
   // document.getElementById("organization_link").style.setProperty("visibility", "hidden");
    
    //document.getElementById("register-page-step2").style.setProperty("display", "none");


});

</script>
