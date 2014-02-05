<div class="page" id="register-page-step2">

    <form action="" name="organization_form" id="register_step2" class="standard-form form-horizontal" method="post" enctype="multipart/form-data">

        <h2>                        <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/img/step2.jpg" height="60" width="60">        
            <?php _e('Register an Organization', 'firmasite'); ?></h2>

        <?php do_action('template_notices'); ?>

        <p><?php _e('In the case that the organization tha you belong is not listed, you can register it manually or get your organization info using your linked in account.', 'firmasite'); ?></p>

        <?php do_action('bp_before_account_details_fields'); ?>

        <div class="register-section" id="basic-details-section">
            <?php /*             * *** Organization Details ***** */ ?>

            <h4 class="page-header"><?php _e('Organization Details', 'firmasite'); ?></h4>

            <div hidden="true" id="organization_exist_div">
                <span class="label label-warning">Organisation Exist!</span>
                <div id="organization_exist_warning" class="alert alert-warning"></div>
            </div>
            <div class="form-group">
                <label class="control-label col-xs-12 col-md-3" for="registered_organizations"><?php _e('Registered Organisations', 'firmasite'); ?> </label>
                <div class="col-xs-12 col-md-9">
                    <select  class="form-control" name="cecom_organization_id" id="cecom_organization_id" aria-required="false">
                        <option value="undefined">Select your organisation...</option>
                        <?php
                        //Fetch registered Organizations form DB
                        $results = CECOM_Organization::getRegisteredOrganizations();
                        if (is_array($results)) {

                            foreach ($results as $organization) {
                                echo "<option value = '{$organization->gid }'>{$organization->name}&nbsp;&nbsp;({$organization->website})</option>";
                            }
                        }
                        ?>
                    </select>
                    <p style="margin:5px" class="field-visibility-settings-toggle text-muted" id="">
                        <label>
                            <input id="organization_details_checkbox" type="checkbox" value="">
                            My Organisation is not listed!
                        </label>
                    </p>    
                </div>
            </div>

            <div id="organization_details" hidden="true"> 


                <div class="form-group">
                    <label class="control-label col-xs-12 col-md-3" for="organization_name"><?php _e('Company Name', 'firmasite'); ?> <?php _e('(required)', 'firmasite'); ?></label>
                    <div class="col-xs-12 col-md-9">
                        <!-- <input type="hidden" class="form-control" name="cecom_organization_id" id="cecom_organization_id" value="undefined"/> -->
                        <input type="hidden" class="form-control" name="organization_id" id="organization_id" value=""/>
                        <input type="text" placeholder="Type the name of your organization" class="form-control" name="organization_name" id="organization_name" value="" aria-required="true"/>
                        <p style="margin:5px" class="field-visibility-settings-toggle text-muted" id="">
                            <input id="organization_import"  data-loading-text="Importing..." type="hidden" value = "Import!"class="btn  btn-primary">&nbsp;
                            <a hidden="true" id="organization_link" href="" target="_blank" >Visit!</a>&nbsp;&nbsp;
                            <label>
                                <input id="linkedin" type="checkbox" value="">
                                Use your oganisation information as present on LinkedIn
                            </label>
                        </p>    
                    </div>
                </div>


                <div class="form-group">
                    <label class="control-label col-xs-12 col-md-3" for="organization_description"><?php _e('Company Description', 'firmasite'); ?> </label>
                    <div class="col-xs-12 col-md-9">
                        <input type="text" class="form-control" name="organization_description" id="organization_description" value="" aria-required="false"/>
                    </div>
                </div>


                <div class="form-group">
                    <label class="control-label col-xs-12 col-md-3" for="organization_specialties"><?php _e('Specialities', 'firmasite'); ?> </label>
                    <div class="col-xs-12 col-md-9">
                        <input type="text" placeholder="Type the specialties of your organization" class="form-control" name="organization_specialties" id="organization_specialties" value="" aria-required="false"/>
                    </div>
                </div>


                <div class="form-group">
                    <label class="control-label col-xs-12 col-md-3" for="organization_website"><?php _e('Organization Website', 'firmasite'); ?><?php _e('(required)', 'firmasite'); ?> </label>
                    <div class="col-xs-12 col-md-9">
                        <input  class="form-control" name="organization_website" id="organization_website" value="" aria-required="false"/>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-xs-12 col-md-3" for="organization_country"><?php _e('Country', 'firmasite'); ?> <?php _e('(required)', 'firmasite'); ?></label>
                    <div class="col-xs-12 col-md-9">
                        <div id="organization_country" class="bfh-selectbox bfh-countries" data-country="GR" data-flags="true"> </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-xs-12 col-md-3" for="organization_size"><?php _e('Organization Size', 'firmasite'); ?> <?php _e('(required)', 'firmasite'); ?></label>
                    <div class="col-xs-12 col-md-9">
                        <select  class="form-control" name="organization_size" id="organization_size" value="select" aria-required="false">
                            <option value="none">Please select...</option>
                            <?php
                            //Fetch Organization Size form DB
                            $results = CECOM_Organization::getOrganizationSize();
                            if (is_array($results)) {
                                foreach ($results as $org_size) {
                                    $minus = "-";
                                    $max = $org_size->max;
                                    $min = $org_size->min;
                                    if ($max == "0") {
                                        $max = $max - 1;
                                        $max = "+";
                                        $minus = "";
                                    } elseif ($min == $max) {
                                        $minus = "";
                                        $max = "";
                                    }
                                    echo "<option value = '{$org_size->id }'>$min$minus$max</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-xs-12 col-md-3" for="organization_type"><?php _e('Type of Organization', 'firmasite'); ?> <?php _e('(required)', 'firmasite'); ?></label>
                    <div class="col-xs-12 col-md-9">
                        <select  class="form-control" name="organization_type" id="organization_type" aria-required="false">
                            <option value="none">Please select...</option>
                            <?php
                            //Fetch Organization Types form DB
                            $results = CECOM_Organization::getOrganizationType();
                            if (is_array($results)) {

                                foreach ($results as $org_type) {
                                    echo "<option value = '{$org_type->id }'>{$org_type->description}</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>


                <div class="form-group">
                    <label class="control-label col-xs-12 col-md-3" for="organization_sector"><?php _e('Sector', 'firmasite'); ?> <?php _e('(required)', 'firmasite'); ?></label>
                    <div class="col-xs-12 col-md-9">
                        <select  class="form-control" name="organization_sector" id="organization_sector" value="select" aria-required="false">
                            <option value="none">Please select...</option>
                            <?php
                            //Fetch Organization Sectos form DB
                            $results = CECOM_Organization::getOrganizationSector();
                            if (is_array($results)) {

                                foreach ($results as $org_sector) {
                                    echo "<option style='background-color:{$org_sector->color}'  value = '{$org_sector->id }'>{$org_sector->description}</option>";
                                }
                            }
                            ?>

                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-xs-12 col-md-3" for="organization_collaboration"><?php _e('Available for collaboration', 'firmasite'); ?> </label>
                    <div class="col-xs-12 col-md-9">
                        <input type="radio" checked="yes"  name="organization_collaboration_y" id="organization_collaboration_y" aria-required="false"> &nbsp;<strong>Yes</strong>&nbsp;&nbsp;
                        <input type="radio"  name="organization_collaboration_n" id="organization_collaboration_n"  aria-required="false"> &nbsp;<strong>No</strong>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-xs-12 col-md-3" for="organization_transaction"><?php _e('Available for transaction', 'firmasite'); ?> </label>
                    <div class="col-xs-12 col-md-9">
                        <input type="radio" name="organization_transaction_y" id="organization_transaction_y" aria-required="false"> &nbsp;<strong>Yes</strong>&nbsp;&nbsp;
                        <input type="radio" checked="yes" name="organization_transaction_n" id="organization_transaction_n" ria-required="false"> &nbsp;<strong>No</strong>
                        </select>
                    </div>
                </div>
            </div>

            <div align="right" class="submit" >
                <hr>
                <div align="left"><a align="left" href="<?php bloginfo('wpurl'); ?>" title="Are you lost?">&larr; Back to CECommunity</a></div>
                <input type="submit" class="btn  btn-primary" name="organization_submit" id="organization_submit" value="<?php _e('Submit', 'firmasite'); ?>" >
            </div>

        </div>
    </form>
</div>



