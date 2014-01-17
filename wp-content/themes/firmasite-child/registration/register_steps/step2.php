<div class="page" id="register-page-step2">

    <form action="" name="register_organization_form" id="register_organization_form" class="standard-form form-horizontal" method="post" enctype="multipart/form-data">


        <h2>                        <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/img/step2.jpg" height="60" width="60">        
            <?php _e('Register an Organization', 'firmasite'); ?></h2>

        <?php do_action('template_notices'); ?>

        <p><?php _e('In the case that the organization tha you belong is not listed, you can register it manually or get your organization info using your linked in account.', 'firmasite'); ?></p>

        <?php do_action('bp_before_account_details_fields'); ?>

        <div class="register-section" id="basic-details-section">

            <?php /*             * *** Basic Account Details ***** */ ?>

            <h4 class="page-header"><?php _e('Organization Details', 'firmasite'); ?></h4>

            <div class="form-group">
                <label class="control-label col-xs-12 col-md-3" for="organization_name"><?php _e('Company Name', 'firmasite'); ?> <?php _e('(required)', 'firmasite'); ?></label>
                <div class="col-xs-12 col-md-9">
                    <input type="hidden" class="form-control" name="organization_id" id="organization_id" value=""/>
                    <input type="text" placeholder="Type the name of your organization" class="form-control" name="organization_name" id="organization_name" value="" aria-required="true"/>
                    <p style="margin:5px" class="field-visibility-settings-toggle text-muted" id="">

                        <input id="organization_import" type="hidden" value = "Import!"class="btn  btn-primary">&nbsp;
                        <a hidden="true" id="organization_link" href="http://www.google.com" target="_blank" >Visit</a>&nbsp;&nbsp;&nbsp;<?php printf(__('Type in the name of your company as registered in LinkedIn')); ?>
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
                <label class="control-label col-xs-12 col-md-3" for="organization_specialities"><?php _e('Specialities', 'firmasite'); ?> </label>
                <div class="col-xs-12 col-md-9">
                    <input type="text" class="form-control" name="organization_specialities" id="organization_specialities" value="" aria-required="false"/>
                </div>
            </div>


            <div class="form-group">
                <label class="control-label col-xs-12 col-md-3" for="organization_website"><?php _e('Organization Website', 'firmasite'); ?> </label>
                <div class="col-xs-12 col-md-9">
                    <input type="url" class="form-control" name="organization_website" id="organization_website" value="" aria-required="false"/>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-xs-12 col-md-3" for="organization_country"><?php _e('Country', 'firmasite'); ?> </label>
                <div class="col-xs-12 col-md-9">
                    <!--<select  class="form-control bfh-countries" name="organization_country" data-flags="true" data-country="US" id="organization_country" value="select" aria-required="false">
                    </select> -->
                    <div id="organization_country" class="bfh-selectbox bfh-countries" data-country="US" data-flags="true"> </div>
                    
                </div>
            </div>



            <!-- <form>
            <select class="form-control bfh-countries" data-country="US" ></select>
            </form> -->


            <div class="form-group">
                <label class="control-label col-xs-12 col-md-3" for="organization_size"><?php _e('Organization Size', 'firmasite'); ?> </label>
                <div class="col-xs-12 col-md-9">
                    <select  class="form-control" name="organization_size" id="organization_size" value="select" aria-required="false">
                        <!-- <option value="aa">aitem1</option>
                        <option>bitem2</option>
                        <option>bitem3</option>
                        <option>ditem4</option>
                        <option>eitem5</option> -->
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-xs-12 col-md-3" for="organization_type"><?php _e('Type of Organization', 'firmasite'); ?> </label>
                <div class="col-xs-12 col-md-9">
                    <select  class="form-control" name="organization_type" id="organization_type" value="select" aria-required="false">
                        <!-- <option value="aa">aitem1</option>
                        <option>bitem2</option>
                        <option>bitem3</option>
                        <option>ditem4</option>
                        <option>eitem5</option> -->
                    </select>
                </div>
            </div>


            <div class="form-group">
                <label class="control-label col-xs-12 col-md-3" for="organization_collaboration"><?php _e('Available for collaboration', 'firmasite'); ?> </label>
                <div class="col-xs-12 col-md-9">
                    <input type="radio" checked="yes"  name="organization_collaboration_y" id="organization_collaboration_y" aria-required="false"> &nbsp;<strong>Yes</strong>&nbsp;&nbsp;
                    <input type="radio"  name="organization_collaboration_n" id="organization_collaboration_n" value="select" aria-required="false"> &nbsp;<strong>No</strong>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-xs-12 col-md-3" for="organization_transaction"><?php _e('Available for transaction', 'firmasite'); ?> </label>
                <div class="col-xs-12 col-md-9">
                    <input type="radio" name="organization_transaction_y" id="organization_transaction_y" aria-required="false"> &nbsp;<strong>Yes</strong>&nbsp;&nbsp;
                    <input type="radio" checked="yes" name="organization_transaction_n" id="organization_transaction_n" value="select" aria-required="false"> &nbsp;<strong>No</strong>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-xs-12 col-md-3" for="organization_sector"><?php _e('Sector', 'firmasite'); ?> </label>
                <div class="col-xs-12 col-md-9">
                    <select  class="form-control" name="organization_sector" id="organization_sector" value="select" aria-required="false">
                        <!-- <option value="aa">aitem1</option>
                        <option>bitem2</option>
                        <option>bitem3</option>
                        <option>ditem4</option>
                        <option>eitem5</option> -->
                    </select>
                </div>
            </div>



        </div>

</div>

</form>

</div>
