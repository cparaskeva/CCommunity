<?php
global $firmasite_settings;
get_header('buddypress');
?>

<div id="primary" class="content-area <?php echo $firmasite_settings["layout_primary_class"]; ?>">
    <div class="padder">

        <div id="item-header" class="well well-sm media clearfix" role="complementary">

<?php locate_template(array('offers/single/offer-header.php'), true); ?>

        </div><!-- #item-header -->
<?php do_action('template_notices'); ?>

        <?php //Show subnav only if is admin
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

            if (bp_is_offer_admin_page() && bp_is_item_admin()) :
                locate_template(array('offers/single/admin.php'), true);

            elseif (!bp_group_is_visible()) :
                // The group is not visible, show the status message

                do_action('bp_before_group_status_message');
                ?>

                <!-- <div class="clearfix"></div><div id="message" class="info alert alert-info">
                 <p><?php //bp_group_status_message(); ?></p>
                 </div> -->

                <?php
                do_action('bp_after_group_status_message');

            else :
                // If nothing sticks, just load a group front template if one exists.
                locate_template(array('groups/single/front.php'), true);

            endif;

            do_action('bp_after_group_body');
            ?>

        </div><!-- #item-body -->

<?php //do_action( 'bp_after_group_home_content' );  ?>

<?php //endwhile; endif;  ?>

    </div><!-- .padder -->
</div><!-- #content -->

<?php get_sidebar('buddypress'); ?>
<?php get_footer('buddypress'); ?>
