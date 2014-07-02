<?php do_action('bp_before_group_details_admin'); ?>
        <!-- Hidden Fields for Offer Sectors covered-->   
        <input type="hidden" class="form-control" name="offer-sectors" id="offer-sectors" value=""/>
        <!-- End of Hidden Fields -->
        <br>
        <label for="offer-type"><?php _e('Offer Type (required)', 'firmasite'); ?></label>
        <input type="text"  readonly="true" name="offer-type" id="offer-type" value="<?php echo $details['tdesc']; ?>" aria-required="true" /> <br/>

        <?php if ($bp->offers->current_offer->type_id == 1 || $bp->offers->current_offer->type_id == 2): ?>
            <label for="collaboration-type"><?php _e('Type of collaboration (required)', 'firmasite'); ?></label>
            <select name="collaboration-type" id="collaboration-type">
                <?php
                //Fetch Collaboration Types form DB
                $results = BP_Offer::getCollaborationTypes();
                if (is_array($results)) {
                    foreach ($results as $offer_collaboration) {
                        if ($bp->offers->current_offer->collaboration_id == $offer_collaboration->id)
                            echo "<option selected='selected' value = '{$offer_collaboration->id }'>{$offer_collaboration->description}</option>";
                        else
                            echo "<option value = '{$offer_collaboration->id }'>{$offer_collaboration->description}</option>";
                    }
                }
                ?>
            </select>
            <br/>
        <?php endif; ?>
        <label style="margin:0px" for="collaboration-description"><?php _e('Offer Description (required)', 'firmasite'); ?></label>
        <?php
        $content = $bp->offers->current_offer->description;
        echo firmasite_wp_editor($content, 'collaboration-description');
        ?>
        <br>
        <?php
        //Offer Type: 1-Develop product and services
        if ($bp->offers->current_offer->type_id == 1):
            ?>
            <!-- Offer type: Collaboration to develop products and services -->
            <label for = "collaboration-partner-sought"><?php _e('Type of partner sought (required)', 'firmasite'); ?></label>
            <select name="collaboration-partner-sought" id="collaboration-partner-sought">
                <?php
                //Fetch Partner sought Types form DB
                $results = BP_Offer::getPartnerTypes();
                if (is_array($results)) {
                    foreach ($results as $offer_partner_type) {

                        if ($bp->offers->current_offer->partner_type_id == $offer_partner_type->id)
                            echo "<option selected='selected' value = '{$offer_partner_type->id }'>{$offer_partner_type->description}</option>";
                        else
                            echo "<option value = '{$offer_partner_type->id }'>{$offer_partner_type->description}</option>";
                    }
                }
                ?>
            </select>
        <?php endif; ?>


        <?php
        //Offer Type: 2-Participate to funded projects
        if ($bp->offers->current_offer->type_id == 2):
            ?>


            <label for="collaboration-countries"><?php _e('Grant Programs (required)', 'firmasite'); ?></label>
            <select name="collaboration-programs" id="collaboration-programs">
                <?php
                //Fetch Grant Programs form DB
                $results = BP_Offer::getGrantPrograms();
                if (is_array($results)) {

                    foreach ($results as $program) {

                        if ($bp->offers->current_offer->program_id == $program->id)
                            echo "<option selected='selected' value = '{$program->id }'>{$program->description}</option>";
                        else
                            echo "<option value = '{$program->id }'>{$program->description}</option>";
                    }
                }
                ?>
            </select>
        <?php endif; ?>

        <?php
        //Offer Type: 3-Funding
        if ($bp->offers->current_offer->type_id == 3):
            ?>
            <label for="applyable-countries"><?php _e('Applyable countries (required)', 'firmasite'); ?></label>
            <select  class="form-control" name="applyable-countries" id="applyable-countries" aria-required="false">
                <?php
                //Fetch All Countries form DB
                $results = CECOM_Organization::getAllCountries();
                if (is_array($results)) {
                    foreach ($results as $country) {
                        if ($bp->offers->current_offer->country_id == $country->id)
                            echo "<option selected='selected' value = '{$country->id }'>{$country->name}</option>";
                        else
                            echo "<option value = '{$country->id }'>{$country->name}</option>";
                    }
                }
                ?>
            </select>
            <br/>
            <label for="finance-stage"><?php _e('Financing Stage (required)', 'firmasite'); ?></label>
            <select  class="form-control" name="finance-stage" id="finance-stage" aria-required="false">
                <?php
                //Fetch Financing stages form DB
                $results = BP_Offer::getFinanceStages();
                if (is_array($results)) {
                    foreach ($results as $finance_stage) {
                        if ($bp->offers->current_offer->finance_stage_id == $finance_stage->id)
                            echo "<option selected='selected' value = '{$finance_stage->id }'>{$finance_stage->description}</option>";
                        else
                            echo "<option value = '{$finance_stage->id }'>{$finance_stage->description}</option>";
                    }
                }
                ?>
            </select>
            <br/>
            <label  for="offer_sector"><?php _e('Sectors', 'firmasite'); ?> </label>
            <select name="offer_sector" id="offer_sector"  class="multiselect" multiple="multiple">
                <?php
                //Fetch Organization Sectors form DB
                $results = CECOM_Organization::getOrganizationSector();
                if (is_array($results)) {
                    foreach ($results as $org_sector) {
                        echo "<option value = '{$org_sector->id }'>{$org_sector->description}</option>";
                    }
                }
                ?>
            </select>
        <?php endif; ?>
        <br/>
        <p>
        <hr/>

    </p>

    <?php do_action('bp_after_group_details_admin'); ?>

    <p><input type="submit" class="btn  btn-primary" value="<?php _e('Save Changes', 'firmasite'); ?>" id="save" name="save" /></p>
    <?php wp_nonce_field('offers_edit_offer_details'); ?>