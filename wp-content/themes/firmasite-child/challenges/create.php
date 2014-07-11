<?php
/**
 * CECommunity - Create Patent & License
 *
 */
global $firmasite_settings;
get_header('buddypress');


/* Import JS files */
wp_enqueue_script('bootstrap-multiselect');

/* Import CSS files */
wp_enqueue_style('bootstrap-multiselect-style');
?>


<div id="primary" class="content-area <?php echo $firmasite_settings["layout_primary_class"]; ?>">
    <div class="padder">
        <form action="" method="post" id="challenge-form" class="standard-form" enctype="multipart/form-data">
            <h3  id="challenges-header" class="page-header"><?php _e('Launch an Open Innovation Challenge', 'firmasite'); ?> &nbsp;</h3>
            <?php do_action('template_notices'); ?>
            <div class="item-body" id="group-create-body">
                <!-- Hidden Fields for Organization Sectors and Subsectors covered-->   
                <input  type="hidden" class="form-control" name="challenge-sectors" id="challenge-sectors" value=""/>
                <!-- End of Hidden Fields -->
                <br/>
                <label for="challenge-description"><?php _e('Title of the challenge you propose (required)', 'firmasite'); ?></label>
                <input class="form-control" name="challenge-title" id="challenge-title" />
                <br><label for="challenge-description"><?php _e('Describe the challenge you want to publish (required)', 'firmasite'); ?></label>
                <textarea rows="3" type="text" name="challenge-description" id="challenge-description" aria-required="true" ></textarea> 
                <br/>
                <label  for="organization_sector"><?php _e('Sector', 'firmasite'); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                <select name="organization_sector" id="organization_sector"  class="multiselect" multiple="multiple">
                    <?php
                    //Fetch Organization Sectos form DB
                    $results = CECOM_Organization::getOrganizationSector();
                    if (is_array($results)) {

                        foreach ($results as $org_sector) {
                            echo "<option value = '{$org_sector->id }'>{$org_sector->description}</option>";
                        }
                    }
                    ?>
                </select>
                <br><br>
                <label for="challenge-deadline"><?php _e('Deadline for answering in YYYY/MM/DD format (required)', 'firmasite'); ?></label>
                <input class="form-control" name="challenge-deadline" id="challenge-deadline" />
                <br><br>
                <label for="challenge-reward"><?php _e('Amount of the reward in Euro (required)', 'firmasite'); ?></label>
                <input class="form-control" name="challenge-reward" id="challenge-reward" />
                <br/>
                <label for="challenge-rights"><?php _e('Rights (required)', 'firmasite'); ?></label>
                <select  class="form-control" name="challenge-rights" id="challenge-rights" aria-required="false">
                    <option value="none">Please select...</option>
                    <?php
                    //Fetch All Countries form DB
                    $results_r = BP_Challenge::getRights();
                    if (is_array($results_r)) {

                        foreach ($results_r as $right) {
                            echo "<option value = '{$right->id }'>{$right->description}</option>";
                        }
                    }
                    ?>
                </select>
                <!-- Submit Div-->
                <div align="right" class="submit" >
                    <hr>
                    <div align="left"><a align="left" href="<?php echo bp_loggedin_user_domain() . bp_get_challenges_root_slug() ?>" title="Are you lost?">&larr; Back to Challenges</a></div>
                    <input type="submit" class="btn  btn-primary" name="challenge_submit" id="challenge_submit" value="<?php _e('Publish your proposal', 'firmasite'); ?>" >
                    <?php wp_nonce_field('challenges_create_challenge'); ?>
                    <br/><br/>
                </div>

            </div>
        </form>

    </div><!-- .padder -->
</div><!-- #content -->

<?php
//Load JavaScript Files
include(get_stylesheet_directory() . "/challenges/challenges.php");
//Load CECommunity Sidebar
get_sidebar('buddypress');
//Load CECommunity Footer
get_footer('buddypress');
?>
