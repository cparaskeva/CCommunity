<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<?php $group_id = bp_get_group_id(); ?>
<h4> Collaboration Offers</h4>
<h5> Develop products and services</h5>
<?php 
if (bp_has_offers("group_id=" . $group_id . "&offer_type=1")) : ?>
    <ul id="offers-list" class="item-list" role="main">
        <?php while (bp_offers()) : bp_the_offer(); ?>
            <li>
                <!--  <div class="item-avatar">   
                <?php $organisation = bp_offers_get_organization(); ?>
                      <a href="<?php bp_offers_owner_permalink(); ?>"><?php bp_offers_owner_avatar('type=thumb&width=50&height=50'); ?></a> 
                  </div> -->
                <div class="item">
                    <div class="item-title">
                        <?php if (bp_offers_get_is_owner()): ?>
                            Offer proposed by  <a href="<?php bp_offers_owner_permalink(); ?>"><?php bp_offers_owner_name(); ?></a> 
                            &nbsp;&nbsp;
                        <?php endif; ?>
                        View offer <a href="<?php bp_offer_permalink(); ?>">details</a>&nbsp;&nbsp; 

                        <span class="label label-default"><?php bp_offer_type(); ?></span> 
                        <span class="label label-info"><?php printf(__('Posted: %s', 'firmasite'), bp_offer_get_posted_date()); ?></span></div>

                    <div class="item-content"> 
                        <!-- <p><b> <?php //echo bp_offers_content();      ?></b></p> -->
                        <p><b>Description:</b><?php
                            echo substr(bp_offers_get_content(), 0, 70);
                            echo (strlen(bp_offers_get_content()) > 70 ? "..." : "" );
                            ?></p>

                    </div>
                </div>

                <div class="clear"></div>
            </li>

        <?php endwhile; ?>

    </ul>
    <?php
else: echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>No <b>Develop products and services</b> offers has been published yet!</i>";
endif;
?>

<h5> Funded Projects</h5>
<?php if (bp_has_offers("group_id=" . $group_id . "&offer_type=2")) : ?>
    <ul id="offers-list" class="item-list" role="main">
        <?php while (bp_offers()) : bp_the_offer(); ?>
            <li>
                <!--  <div class="item-avatar">   
                <?php $organisation = bp_offers_get_organization(); ?>
                      <a href="<?php bp_offers_owner_permalink(); ?>"><?php bp_offers_owner_avatar('type=thumb&width=50&height=50'); ?></a> 
                  </div> -->
                <div class="item">
                    <div class="item-title">
                        <?php if (bp_offers_get_is_owner()): ?>
                            Offer proposed by  <a href="<?php bp_offers_owner_permalink(); ?>"><?php bp_offers_owner_name(); ?></a> 
                            &nbsp;&nbsp;
                        <?php endif; ?>
                        View offer <a href="<?php bp_offer_permalink(); ?>">details</a>&nbsp;&nbsp; 

                        <span class="label label-default"><?php bp_offer_type(); ?></span> 
                        <span class="label label-info"><?php printf(__('Posted: %s', 'firmasite'), bp_offer_get_posted_date()); ?></span></div>

                    <div class="item-content"> 
                        <!-- <p><b> <?php //echo bp_offers_content();     ?></b></p> -->
                        <p><b>Description:</b><?php
                            echo substr(bp_offers_get_content(), 0, 70);
                            echo (strlen(bp_offers_get_content()) > 70 ? "..." : "" );
                            ?></p>

                    </div>
                </div>

                <div class="clear"></div>
            </li>

        <?php endwhile; ?>

    </ul>
    <?php
else: echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>No <b>Funded Projects</b> offers has been published yet!</i>";
endif;
?>
<h4> Funding Offers</h4>
<?php if (bp_has_offers("group_id=" . $group_id . "&offer_type=3")) : ?>
    <ul id="offers-list" class="item-list" role="main">
        <?php while (bp_offers()) : bp_the_offer(); ?>
            <li>
                <!--  <div class="item-avatar">   
                <?php $organisation = bp_offers_get_organization(); ?>
                      <a href="<?php bp_offers_owner_permalink(); ?>"><?php bp_offers_owner_avatar('type=thumb&width=50&height=50'); ?></a> 
                  </div> -->
                <div class="item">
                    <div class="item-title">
                        <?php if (bp_offers_get_is_owner()): ?>
                            Offer proposed by  <a href="<?php bp_offers_owner_permalink(); ?>"><?php bp_offers_owner_name(); ?></a> 
                            &nbsp;&nbsp;
                        <?php endif; ?>
                        View offer <a href="<?php bp_offer_permalink(); ?>">details</a>&nbsp;&nbsp; 

                        <span class="label label-default"><?php bp_offer_type(); ?></span> 
                        <span class="label label-info"><?php printf(__('Posted: %s', 'firmasite'), bp_offer_get_posted_date()); ?></span></div>

                    <div class="item-content"> 
                      <!--  <p><b> <?php //echo bp_offers_content();     ?></b></p> -->
                        <p><b>Description:</b><?php
                            echo substr(bp_offers_get_content(), 0, 70);
                            echo (strlen(bp_offers_get_content()) > 70 ? "..." : "" );
                            ?></p>
                    </div>
                </div>

                <div class="clear"></div>
            </li>

        <?php endwhile; ?>

    </ul>
    <?php
else: echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>No <b>Funding</b> offers has been published yet!</i>";
endif;
?>
<h4> Patents/Licenses</h4>
<?php if (bp_has_patents_licenses("group_id=" . $group_id)) : ?>

    <ul id="patents_licenses-list" class="item-list" role="main">

        <?php while (bp_patents_licenses()) : bp_the_patent_license(); ?>

            <li>
                <!-- <div class="item-avatar">   
                <?php $organisation = bp_patents_licenses_get_organization(); ?>
        <a href="<?php //bp_patents_licenses_owner_permalink();       ?>"><?php //bp_patents_licenses_owner_avatar('type=thumb&width=50&height=50');       ?></a> 
                 </div> -->

                <div class="item">
                    <div class="item-title">
                        <?php if (bp_patents_licenses_get_is_owner()): ?>
                            Offer published by 
                            <a href="<?php bp_patents_licenses_owner_permalink();?>"><?php bp_patents_licenses_owner_name();       ?></a>
                            &nbsp;&nbsp;
                        <?php endif; ?>
                        View patent & license <a href="<?php bp_patent_license_permalink(); ?>">details</a>&nbsp;&nbsp; 
                        <span class="label label-default"><?php bp_patent_license_type(); ?></span> 
                        <span class="label label-info"><?php printf(__('Posted: %s', 'firmasite'), bp_patent_license_get_posted_date()); ?></span></div>

                    <div class="item-content"> 
                        <p><b>Description:</b><?php
                            echo substr(bp_patents_licenses_get_content(), 0, 70);
                            echo (strlen(bp_patents_licenses_get_content()) > 70 ? "..." : "" );
                            ?></p>
                    </div>

                </div>

                <div class="clear"></div>
            </li>

        <?php endwhile; ?>

    </ul>
    <?php
else: echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>No <b>Patents/Licenses</b> has been published yet!</i>";
endif;
?>
<h4> Tools/Facilities Rent Offers</h4>

<?php //if (bp_has_tools_facilities(bp_ajax_querystring('tools_facilities') . (!empty(bp_ajax_querystring('tools_facilities')) ? "&" : "") . "group_id=" . $group_id)) : ?>
<?php if (bp_has_tools_facilities("group_id=" . $group_id)) : ?>

    <ul id="tools_facilities-list" class="item-list" role="main">

        <?php while (bp_tools_facilities()) : bp_the_tool_facility(); ?>

            <li>
                <!-- <div class="item-avatar">
                    <?php $organisation = bp_tools_facilities_get_organization(); ?>
                   
                   <a href="<?php //bp_tools_facilities_owner_permalink();      ?>"><?php //bp_tools_facilities_owner_avatar('type=thumb&width=50&height=50');      ?></a>
                </div> -->

                <div class="item">
                    <div class="item-title">
                        <?php if (bp_tools_facilities_get_is_owner()): ?>
                            Offer published by 
                            <a href="<?php bp_tools_facilities_owner_permalink(); ?>"><?php bp_tools_facilities_owner_name(); ?></a> 
                            &nbsp;&nbsp;
                        <?php endif; ?>
                        View tool & facility <a href="<?php bp_tool_facility_permalink(); ?>">details</a>&nbsp;&nbsp; 
                        <!-- <span class="highlight label label-default"><?php //bp_tool_facility_type();        ?></span> -->
                        <span class="label label-info"><?php printf(__('Posted: %s', 'firmasite'), bp_tool_facility_get_posted_date()); ?></span></div>

                    <div class="item-content"> 
                        <!-- <p><b> <?php //echo bp_tools_facilities_content();    ?></b></p> -->
                        <p><b>Description:</b><?php
                            echo substr(bp_patents_licenses_get_content(), 0, 70);
                            echo (strlen(bp_patents_licenses_get_content()) > 70 ? "..." : "" );
                            ?></p>
                    </div>

                </div>

                <div class="clear"></div>
            </li>

        <?php endwhile; ?>

    </ul>
    <?php
else: echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>No <b>Tools/Facilities Rent</b> offers has been published yet!</i>";
endif;