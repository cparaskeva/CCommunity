<script type="text/javascript">



/* Ajax Call handle for registration step1 */
jQuery("#register_step1").submit(function(event) {
    
    /* Stop form from submitting normally */
    event.preventDefault();

    /* Clear errors div*/
    jQuery("#current-step-errors").html('');

    /* Get some values from elements on the page: */
    var values =  "action=custom_register_user&"+jQuery(this).serialize();

    /* Send the data using post and put the results in a div */
    jQuery.ajax({
        url: "<?php bloginfo('wpurl'); ?>/wp-admin/admin-ajax.php",
        type: "post",
        data: values,
        //dataType: "json",
        success: function(response){
          //alert(response);
          
           errors = response.split('|');
           
            if (errors == "step1_done"){
                
                //Hide Step1 Form
                document.getElementById("register_step1").style.setProperty("display", "none");
                //Show Step2 Form
                document.getElementById("register-page-step2").style.setProperty("display", "block");
                alert("Done!");
            }
           else{
               jQuery("#current-step-errors").append("<h4 style=\"color:gray\">The following errors were occured: </h4><hr/>"); 
            for (var i=0; i< errors.length;i++){
              jQuery("#current-step-errors").append("<br>"+ errors[i]);  
           }
       }
           //jQuery("#step1_errors").html(response);
    
        },
        error:function(){
            alert("Unresolved error happened. Please try again!");
            //jQuery("#result").html('There is error while submit');
        }
    });
});


    jQuery(document).ready(function() {
        
        
        document.getElementById("nav-main").style.setProperty("visibility", "hidden");
        document.getElementById("register-page-step2").style.setProperty("display", "none");
        
       
    });
</script>
