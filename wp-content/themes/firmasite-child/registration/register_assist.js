<script type="text/javascript">



/* Ajax Call handle for registration step1 */
jQuery("#register_step1").submit(function(event) {
    
    /* Stop form from submitting normally */
    event.preventDefault();

    /* Clear errors div*/
    jQuery("#step1_errors").html('');

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
           for (var i=0; i< errors.length;i++){
              jQuery("#step1_errors").append("<br>"+ errors[i]);  
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
        
        
        document.getElementById("nav-main").style.setProperty("visibility", "hidden")
        if (jQuery('div#blog-details').length && !jQuery('div#blog-details').hasClass('show'))
            jQuery('div#blog-details').toggle();

        jQuery('input#signup_with_blog').click(function() {
            jQuery('div#blog-details').fadeOut().toggle();
        });
    });
</script>
