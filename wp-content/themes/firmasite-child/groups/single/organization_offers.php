<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<h4> Collaboration Offers</h4>
<h5> Develop products and services</h5>
<h5> Funded Projects</h5>



<?php if (!bp_has_offers(bp_ajax_querystring('offers'))) : ?>

    <ul id="offers-list" class="item-list" role="main">

        <?php while (bp_offers()) : bp_the_offer(); ?>
            <li>
                <div class="item-avatar">   
                    <?php $organisation = bp_offers_get_organization(); ?>
                    <a href="<?php bp_offers_owner_permalink(); ?>"><?php bp_offers_owner_avatar('type=thumb&width=50&height=50'); ?></a> 
                </div>
                <div class="item">
                    <div class="item-title">
                        <?php if (!bp_offers_get_is_owner()): ?>
                            Offer proposed by  <a href="<?php bp_offers_owner_permalink();    ?>"><?php bp_offers_owner_name();    ?></a> 
                            &nbsp;&nbsp;
                        <?php endif; ?>
                        View offer <a href="<?php bp_offer_permalink(); ?>">details</a>&nbsp;&nbsp; 
                        
                        <span class="label label-default"><?php bp_offer_type(); ?></span> 
                        <span class="label label-info"><?php printf(__('Posted: %s', 'firmasite'), bp_offer_get_posted_date()); ?></span></div>

                    <div class="item-content"> 
                        <p><b> <?php echo bp_offers_content(); ?></b></p>

                    </div>
                </div>

                <div class="clear"></div>
            </li>

        <?php endwhile; ?>

    </ul>




<?php endif; ?>



<h4> Funding Offers</h4>
<h4> Patents/Licenses Offers</h4>
<h4> Tools/Facilities Rent Offers</h4>
<?php //echo bp_group_id(); ?>
