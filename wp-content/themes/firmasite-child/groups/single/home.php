<?php
global $firmasite_settings;
get_header('buddypress');
?>

<div id="primary" class="content-area <?php echo $firmasite_settings["layout_primary_class"]; ?>">
    <div class="padder">

        <?php if (bp_has_groups()) : while (bp_groups()) : bp_the_group(); ?>

                <?php do_action('bp_before_group_home_content'); ?>
		<div id="item-header" class="well well-sm media clearfix" role="complementary">
                    <?php locate_template(array('groups/single/group-header.php'), true); ?>
                </div><!-- #item-header -->

                <div id="item-header" class="well well-sm media clearfix" role="complementary">
                    <?php locate_template(array('groups/single/organization_offers.php'), true); ?>
                </div><!-- #item-header -->


  
                <div id="item-body">

                    <?php
                    do_action('bp_before_group_body');

                    locate_template(array('groups/single/admin.php'), true);

       
                        do_action('bp_before_group_status_message');
                        ?>

                                        <?php
                        do_action('bp_after_group_status_message');

                
                    do_action('bp_after_group_body');
                    ?>

                </div><!-- #item-body -->

                <?php do_action('bp_after_group_home_content'); ?>

                <?php
            endwhile;
        endif;
        ?>

    </div><!-- .padder -->
</div><!-- #content -->

<?php get_sidebar('buddypress'); ?>
<?php get_footer('buddypress'); ?>
