<script type="text/javascript">
    jQuery(document).ready(function() {
        
        
        document.getElementById("nav-main").style.setProperty("visibility", "hidden")
        if (jQuery('div#blog-details').length && !jQuery('div#blog-details').hasClass('show'))
            jQuery('div#blog-details').toggle();

        jQuery('input#signup_with_blog').click(function() {
            jQuery('div#blog-details').fadeOut().toggle();
        });
    });
</script>
