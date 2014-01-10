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
        //dataType: "json",
        success: function(response) {
            //alert(response);

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
            //jQuery("#result").html('There is error while submit');
        }
    });
});


/* Ajax Call Implementation for linkedin companies autocomplete field*/
/*jQuery('#organization_name').on('keypress', function() {
    
    //Get the length of the input
    var value = jQuery(this).val(),
    nameLength = value.length+1;
    
    //Fire an ajax call when are enough characters to make a serach
    if (nameLength >=3){
        console.log('change');
*/


         
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
                    //alert(data.companies.values);
                    response( jQuery.map( jQuery.makeArray(data.companies.values), function( item ) {
                        
                       // if (item != null && item !== undefined)
                            document.getElementById("organization_link").style.setProperty("visibility", "visible"); 
                        
                       return {
                         label: item.name,
                         value: item.name
                       }
                     }));
                    
                      
              },
                
               error: function() {
                    alert("Unresolved error happened. Please try again!");                
               }
            });
        }
              
    }); 

   

/*jQuery("#organization_name").autocomplete({
      source: function( request, response ) {
        jQuery.ajax({
          url: "http://ws.geonames.org/searchJSON",
          dataType: "jsonp",
          data: {
            featureClass: "P",
            style: "full",
            maxRows: 12,
            name_startsWith: request.term
          },
          success: function( data ) {
            response( jQuery.map( data.geonames, function( val ) {
              return {
                label: val.name + (val.adminName1 ? ", " + val.adminName1 : "") + ", " + val.countryName,
                value: val.name
              }
            }));
          }
        });
      }
});*/
/*
 * Hide the forms of registration step2 & step3 when document is loaded.
 */
jQuery(document).ready(function() {


    document.getElementById("nav-main").style.setProperty("visibility", "hidden");
    document.getElementById("organization_link").style.setProperty("visibility", "hidden");
    
    //document.getElementById("register-page-step2").style.setProperty("display", "none");


});

</script>
