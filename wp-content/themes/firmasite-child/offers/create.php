<?php
/**
 * BuddyPress - Create Group
 *
 * @package BuddyPress
 * @subpackage bp-default
 */
global $firmasite_settings;
get_header('buddypress');

 $group = groups_get_user_groups($bp->loggedin_user->id);
 $gid = $group['groups'][0]; 
 echo $gid;

?>


<div id="primary" class="content-area <?php echo $firmasite_settings["layout_primary_class"]; ?>">
    <div class="padder">


        <form action="" method="post" id="offer-collaboration-form" class="standard-form" enctype="multipart/form-data">
            <h3 class="page-header"><?php _e('Offer a Collaboration ', 'firmasite'); ?> &nbsp;</h3>

                   <?php do_action( 'template_notices' ); ?>
            <div class="item-body" id="group-create-body">
                <label for="offer-type"><?php _e('Offer type (required)', 'firmasite'); ?></label>
                <select name="offer-type" id="offer-type">
                    <option value="none"  selected="selected">Please select..</option>
                    <?php
                    //Fetch Grant Programs form DB
                    $results = BP_Offer::getOfferTypes();
                    if (is_array($results)) {
                        foreach ($results as $offer_type) {
                            echo "<option value = '{$offer_type->id }'>{$offer_type->description}</option>";
                        }
                    }
                    ?>
                </select>
                <br/>
                <div hidden="true" id="collaboration-commons">
                    <label for="collaboration-type"><?php _e('Collaboration type (required)', 'firmasite'); ?></label>
                    <select name="collaboration-type" id="collaboration-type">
                        <option value="none"  selected="selected"> Please select..</option>
                        <?php
                        //Fetch Collaboration Types form DB
                        $results = BP_Offer::getCollaborationTypes();
                        if (is_array($results)) {
                            foreach ($results as $offer_collaboration) {
                                echo "<option value = '{$offer_collaboration->id }'>{$offer_collaboration->description}</option>";
                            }
                        }
                        ?>
                    </select>
                    <br/>
                    <label for="collaboration-description"><?php _e('Description of the collaboration (required)', 'firmasite'); ?></label>
                    <textarea rows="3" type="text" name="collaboration-description" id="collaboration-description" aria-required="true" ></textarea> 
                    <br/>
                </div>

                <!-- Offer type: Collaboration to develop products and services -->
                <div name="collaboration-develop" id="collaboration-develop" hidden="true">
                    <label for="collaboration-partner-sought"><?php _e('Type of partner sought (required)', 'firmasite'); ?></label>
                    <select name="collaboration-partner-sought" id="collaboration-partner-sought">
                        <option value="none"  selected="selected"> Please select..</option>
                        <?php
                        //Fetch Partner sought Types form DB
                        $results = BP_Offer::getPartnerTypes();
                        if (is_array($results)) {
                            foreach ($results as $offer_partner_type) {
                                echo "<option value = '{$offer_partner_type->id }'>{$offer_partner_type->description}</option>";
                            }
                        }
                        ?>
                    </select>
                    <br/>
                    <label for="collaboration-countries"><?php _e('Applyable countries (required)', 'firmasite'); ?></label>
                    <select  class="form-control" name="collaboration-countries" id="collaboration-countries" aria-required="false">
                        <option value="none">Please select...</option>
                        <?php
                        //Fetch All Countries form DB
                        $results = CECOM_Organization::getAllCountries();
                        if (is_array($results)) {

                            foreach ($results as $country) {
                                echo "<option value = '{$country->id }'>{$country->name}</option>";
                            }
                        }
                        ?>
                    </select>
                </div>


                <!-- Offer type: Collaboration to participate to funded projects -->
                <div hidden="true" id="collaboration-participate">
                    <label for="collaboration-countries"><?php _e('Grant Programms (required)', 'firmasite'); ?></label>
                    <select name="collaboration-programs" id="collaboration-programs">
                        <option value="undefined">Please select..</option>
                        <?php
                        //Fetch Grant Programs form DB
                        $results = BP_Offer::getGrantPrograms();
                        if (is_array($results)) {

                            foreach ($results as $program) {
                                echo "<option value = '{$program->id }'>{$program->description}</option>";
                            }
                        }
                        ?>
                    </select>

                </div>


                <?php
                //$content = bp_get_new_group_description();
                //echo firmasite_wp_editor($content, 'group-desc');
                ?>
<!-- <textarea name="group-desc" id="group-desc" aria-required="true"><?php //bp_new_group_description();                   ?></textarea> -->

                <div align="right" class="submit" >
                    <hr>
                    <div align="left"><a align="left" href="<?php echo bp_loggedin_user_domain() . bp_get_offers_root_slug() ?>" title="Are you lost?">&larr; Back to Collaboration Offers</a></div>
                    <input type="submit" class="btn  btn-primary" name="offer_submit" id="offer_submit" value="<?php _e('Publish your proposal', 'firmasite'); ?>" >
                    <br/><br/>
                </div>

            </div>
        </form>

    </div><!-- .padder -->
</div><!-- #content -->


<?php
//Load JavaScript Files
include(get_stylesheet_directory() . "/offers/offers.js");
//Load CECommunity Sidebar
get_sidebar('buddypress');
//Load CECommunity Footer
get_footer('buddypress');
?>
