<?php
global $firmasite_settings;
get_header('buddypress');

/* Import JS files */
wp_enqueue_script('bootstrap-multiselect');

/* Import CSS files */
wp_enqueue_style('bootstrap-multiselect-style');
?>

<div id="primary" class="content-area <?php echo $firmasite_settings["layout_primary_class"]; ?>">
    <div class="padder">

        <div id="item-header" class="well well-sm media clearfix" role="complementary">

            <?php locate_template(array('tools_facilities/single/tool_facility-header.php'), true); ?>

        </div><!-- #item-header -->
        <?php do_action('template_notices'); ?>

        <?php
        //Show subnav only if is admin
        if (bp_is_item_admin()) {
            ?>
            <div id="item-nav" class="navbar <?php
            if (isset($firmasite_settings["menu-style"]) && "alternative" == $firmasite_settings["menu-style"]) {
                echo " navbar-default";
            } else {
                echo "  navbar-inverse";
            }
            ?>">


                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".bp-profile-nav-collapse">
                        <i class="icon-bar"></i>
                        <i class="icon-bar"></i>
                        <i class="icon-bar"></i>
                    </button>
                </div>


                <div class="collapse navbar-collapse bp-profile-nav-collapse item-list-tabs no-ajax" id="object-nav" role="navigation">
                    <ul class="nav navbar-nav">
                        <?php bp_get_options_nav(); ?>
                    </ul>
                </div>
            </div><!-- #item-nav -->

        <?php } ?>
        <div id="item-body">

            <?php
            do_action('bp_before_group_body');

            if (bp_is_tool_facility_admin_page() && bp_is_item_admin()) :
                locate_template(array('tools_facilities/single/admin.php'), true);

            elseif (!bp_group_is_visible()) :
                // The group is not visible, show the status message

                do_action('bp_before_group_status_message');
                ?>

                <?php
                do_action('bp_after_group_status_message');

            else :
                // If nothing sticks, just load a group front template if one exists.
                locate_template(array('groups/single/front.php'), true);

            endif;

            ?>

        </div><!-- #item-body -->


    </div><!-- .padder -->
</div><!-- #content -->

<?php get_sidebar('buddypress'); ?>
<?php get_footer('buddypress'); ?>
