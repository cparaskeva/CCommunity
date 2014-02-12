<?php
/**
 * BuddyPress - Create Group
 *
 * @package BuddyPress
 * @subpackage bp-default
 */
global $firmasite_settings;
get_header('buddypress');
?>

<div id="primary" class="content-area <?php echo $firmasite_settings["layout_primary_class"]; ?>">
    <div class="padder">


        <form action="<?php bp_group_creation_form_action(); ?>" method="post" id="offer-collaboration-form" class="standard-form" enctype="multipart/form-data">
            <h3 class="page-header"><?php _e('Offer a Collaboration ', 'firmasite'); ?> &nbsp;</h3>

            <div class="item-body" id="group-create-body">
                <label for="offer-type"><?php _e('Offer type (required)', 'firmasite'); ?></label>
                <select name="offer-type" id="offer-type">
                    <option value="none"  selected="selected"> Please select..</option>
                    <option value="a"> Develop products and services</option>
                    <option value="b"  > Participate to funded projects</option>
                </select>
                <br/>
                <label for="collaboration-type"><?php _e('Collaboration type (required)', 'firmasite'); ?></label>
                <select name="collaboration-type" id="collaboration-type">
                    <option value="none"  selected="selected"> Please select..</option>
                    <option value="a"> Val1</option>
                    <option value="b"  >Val2</option>
                </select>
                <br/>

                <!-- Offer type: Collaboration for develop products and services -->

                <label for="collaboration-description"><?php _e('Description of R&D collaboration (required)', 'firmasite'); ?></label>
                <textarea rows="3" type="text" name="collaboration-description" id="collaboration-description" aria-required="true" ></textarea> 
                <br/>
                <label for="collaboration-partner-sought"><?php _e('Type of partner sought (required)', 'firmasite'); ?></label>
                <select name="collaboration-partner-sought" id="collaboration-partner-sought">
                    <option value="none"  selected="selected"> Please select..</option>
                    <option value="a"> Supplier</option>
                    <option value="b"  >Client</option>
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


                <?php
                //$content = bp_get_new_group_description();
                //echo firmasite_wp_editor($content, 'group-desc');
                ?>
<!-- <textarea name="group-desc" id="group-desc" aria-required="true"><?php //bp_new_group_description();        ?></textarea> -->

                <div align="right" class="submit" >
                    <hr>
                    <div align="left"><a align="left" href="<?php echo bp_loggedin_user_domain() . bp_get_offers_root_slug() ?>" title="Are you lost?">&larr; Back to Collaboration Offers</a></div>
                    <input type="submit" class="btn  btn-primary" name="organization_submit" id="organization_submit" value="<?php _e('Publish your proposal', 'firmasite'); ?>" >
                    <br/><br/>
                </div>

            </div>
        </form>


    </div><!-- .padder -->
</div><!-- #content -->

<?php get_sidebar('buddypress'); ?>
<?php get_footer('buddypress'); ?>
