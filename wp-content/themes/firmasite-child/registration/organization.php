<div id="primary" class="content-area-register <?php echo $firmasite_settings["layout_register_class"]; ?>">
 <div class="padder">
     <div class="panel panel-default">
       <div class="panel-body">
		<div class="page" id="register-page">

			<form action="" name="register_organization_form" id="register_organization_form" class="standard-form form-horizontal" method="post" enctype="multipart/form-data">


			<?php if ( 'request-details' == bp_get_current_signup_step() ) : ?>

				<h2>                        <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/img/step2.jpg" height="60" width="60">        
<?php _e( 'Register an Organization', 'firmasite' ); ?></h2>

				<?php do_action( 'template_notices' ); ?>

				<p><?php _e( 'In the case tha the organization tha you belong is not listed, you can register it manually or get your organization info using your linked in account.', 'firmasite' ); ?></p>

				<?php do_action( 'bp_before_account_details_fields' ); ?>

				<div class="register-section" id="basic-details-section">

					<?php /***** Basic Account Details ******/ ?>

					<h4 class="page-header"><?php _e( 'Organization Details', 'firmasite' ); ?></h4>

                    <div class="form-group">
                        <label class="control-label col-xs-12 col-md-3" for="signup_username"><?php _e( 'Username', 'firmasite' ); ?> <?php _e( '(required)', 'firmasite' ); ?></label>
                        <div class="col-xs-12 col-md-9">
                            <?php do_action( 'bp_signup_username_errors' ); ?>
                            <input type="text" class="form-control" name="signup_username" id="signup_username" value="<?php bp_signup_username_value(); ?>" <?php if ( bp_get_the_profile_field_is_required() ) : ?>aria-required="true"<?php endif; ?>/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-xs-12 col-md-3" for="signup_email"><?php _e( 'Email Address', 'firmasite' ); ?> <?php _e( '(required)', 'firmasite' ); ?></label>
                        <div class="col-xs-12 col-md-9">
                            <?php do_action( 'bp_signup_email_errors' ); ?>
                            <input type="text" class="form-control" name="signup_email" id="signup_email" value="<?php bp_signup_email_value(); ?>" <?php if ( bp_get_the_profile_field_is_required() ) : ?>aria-required="true"<?php endif; ?>/>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="control-label col-xs-12 col-md-3" for="signup_password"><?php _e( 'Choose a Password', 'firmasite' ); ?> <?php _e( '(required)', 'firmasite' ); ?></label>
                        <div class="col-xs-12 col-md-9">
                            <?php do_action( 'bp_signup_password_errors' ); ?>
                            <input type="password" class="form-control" name="signup_password" id="signup_password" value="" <?php if ( bp_get_the_profile_field_is_required() ) : ?>aria-required="true"<?php endif; ?>/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-xs-12 col-md-3" for="signup_password_confirm"><?php _e( 'Confirm Password', 'firmasite' ); ?> <?php _e( '(required)', 'firmasite' ); ?></label>
                        <div class="col-xs-12 col-md-9">
                            <?php do_action( 'bp_signup_password_confirm_errors' ); ?>
                            <input type="password" class="form-control" name="signup_password_confirm" id="signup_password_confirm" value="" <?php if ( bp_get_the_profile_field_is_required() ) : ?>aria-required="true"<?php endif; ?>/>
                        </div>
                    </div>

				</div><!-- #basic-details-section -->

				<?php do_action( 'bp_after_account_details_fields' ); ?>

				<?php /***** Extra Profile Details ******/ ?>

				<?php if ( bp_is_active( 'xprofile' ) ) : ?>

					<?php do_action( 'bp_before_signup_profile_fields' ); ?>

					<div class="register-section" id="profile-details-section">

						<h4 class="page-header"><?php _e( 'Profile Details', 'firmasite' ); ?></h4>

						<?php /* Use the profile field loop to render input fields for the 'base' profile field group */ ?>
						<?php if ( bp_is_active( 'xprofile' ) ) : if ( bp_has_profile( 'profile_group_id=1' ) ) : while ( bp_profile_groups() ) : bp_the_profile_group(); ?>

						<?php while ( bp_profile_fields() ) : bp_the_profile_field(); ?>

							<div class="editfield">

								<?php if ( 'textbox' == bp_get_the_profile_field_type() ) : ?>
                                    <div class="form-group">
                                        <label class="control-label col-xs-12 col-md-3" for="<?php bp_the_profile_field_input_name(); ?>"><?php bp_the_profile_field_name(); ?> <?php if ( bp_get_the_profile_field_is_required() ) : ?><?php _e( '(required)', 'firmasite' ); ?><?php endif; ?></label>
                                        <div class="col-xs-12 col-md-9">
                                            <?php do_action( 'bp_' . bp_get_the_profile_field_input_name() . '_errors' ); ?>
                                            <input type="text" class="form-control" name="<?php bp_the_profile_field_input_name(); ?>" id="<?php bp_the_profile_field_input_name(); ?>" value="<?php bp_the_profile_field_edit_value(); ?>" <?php if ( bp_get_the_profile_field_is_required() ) : ?>aria-required="true"<?php endif; ?>/>
                                            <?php firmasite_profile_field_custom_change_field_visibility(); ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                
                                <?php if ( 'textarea' == bp_get_the_profile_field_type() ) : ?>
                                    <div class="form-group">
                                        <label class="control-label col-xs-12 col-md-3" for="<?php bp_the_profile_field_input_name(); ?>"><?php bp_the_profile_field_name(); ?> <?php if ( bp_get_the_profile_field_is_required() ) : ?><?php _e( '(required)', 'firmasite' ); ?><?php endif; ?></label>
                                        <div class="col-xs-12 col-md-9">
                                            <?php do_action( 'bp_' . bp_get_the_profile_field_input_name() . '_errors' ); ?>
											<?php echo firmasite_wp_editor(bp_get_the_profile_field_edit_value(), bp_get_the_profile_field_input_name(), bp_get_the_profile_field_input_name()); ?>
                                            <?php /*<textarea rows="5" cols="40" name="<?php bp_the_profile_field_input_name(); ?>" id="<?php bp_the_profile_field_input_name(); ?>" <?php if ( bp_get_the_profile_field_is_required() ) : ?>aria-required="true"<?php endif; ?>><?php bp_the_profile_field_edit_value(); ?></textarea>*/ ?> 
                                            <?php firmasite_profile_field_custom_change_field_visibility(); ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                
                                <?php if ( 'selectbox' == bp_get_the_profile_field_type() ) : ?>
                                    <div class="form-group">
                                        <label class="control-label col-xs-12 col-md-3" for="<?php bp_the_profile_field_input_name(); ?>"><?php bp_the_profile_field_name(); ?> <?php if ( bp_get_the_profile_field_is_required() ) : ?><?php _e( '(required)', 'firmasite' ); ?><?php endif; ?></label>
                                        <div class="col-xs-12 col-md-9">
                                            <?php do_action( 'bp_' . bp_get_the_profile_field_input_name() . '_errors' ); ?>
                                            <select name="<?php bp_the_profile_field_input_name(); ?>" id="<?php bp_the_profile_field_input_name(); ?>" <?php if ( bp_get_the_profile_field_is_required() ) : ?>aria-required="true"<?php endif; ?>>
                                                <?php bp_the_profile_field_options(); ?>
                                            </select>
                                            <?php firmasite_profile_field_custom_change_field_visibility(); ?>
                                        </div>
                                    </div>
                
                                <?php endif; ?>
                
                                <?php if ( 'multiselectbox' == bp_get_the_profile_field_type() ) : ?>
                                    <div class="form-group">
                                        <label class="control-label col-xs-12 col-md-3" for="<?php bp_the_profile_field_input_name(); ?>"><?php bp_the_profile_field_name(); ?> <?php if ( bp_get_the_profile_field_is_required() ) : ?><?php _e( '(required)', 'firmasite' ); ?><?php endif; ?></label>
                                        <div class="col-xs-12 col-md-9">
                                            <?php do_action( 'bp_' . bp_get_the_profile_field_input_name() . '_errors' ); ?>
                                            <select name="<?php bp_the_profile_field_input_name(); ?>" id="<?php bp_the_profile_field_input_name(); ?>" multiple="multiple" <?php if ( bp_get_the_profile_field_is_required() ) : ?>aria-required="true"<?php endif; ?>>
                        
                                                <?php bp_the_profile_field_options(); ?>
                        
                                            </select>
                        
                                            <?php if ( !bp_get_the_profile_field_is_required() ) : ?>
                        
                                                <a class="clear-value" href="javascript:clear( '<?php bp_the_profile_field_input_name(); ?>' );"><?php _e( 'Clear', 'firmasite' ); ?></a>
                        
                                            <?php endif; ?>
                                            
                                            <?php firmasite_profile_field_custom_change_field_visibility(); ?>
                                        </div>
                                    </div>
                
                                <?php endif; ?>
                
                                <?php if ( 'radio' == bp_get_the_profile_field_type() ) : ?>
                
                                    <div class="form-group">
                                        <label class="control-label col-xs-12 col-md-3"><?php bp_the_profile_field_name(); ?> <?php if ( bp_get_the_profile_field_is_required() ) : ?><?php _e( '(required)', 'firmasite' ); ?><?php endif; ?></label>
                                        <div class="col-xs-12 col-md-9">
                                            <?php do_action( 'bp_' . bp_get_the_profile_field_input_name() . '_errors' ); ?>
											<?php bp_the_profile_field_options(); ?>
                    
                                            <?php if ( !bp_get_the_profile_field_is_required() ) : ?>
                    
                                                <a class="clear-value" href="javascript:clear( '<?php bp_the_profile_field_input_name(); ?>' );"><?php _e( 'Clear', 'firmasite' ); ?></a>
                    
                                            <?php endif; ?>
                                            <?php firmasite_profile_field_custom_change_field_visibility(); ?>
                                        </div>
                                    </div>
                
                                <?php endif; ?>
                
                                <?php if ( 'checkbox' == bp_get_the_profile_field_type() ) : ?>
                
                                    <div class="form-group">
                                        <label class="control-label col-xs-12 col-md-3"><?php bp_the_profile_field_name(); ?> <?php if ( bp_get_the_profile_field_is_required() ) : ?><?php _e( '(required)', 'firmasite' ); ?><?php endif; ?></label>
                                        <div class="col-xs-12 col-md-9">
                                            <?php do_action( 'bp_' . bp_get_the_profile_field_input_name() . '_errors' ); ?>
                                            <?php bp_the_profile_field_options(); ?>
                                            <?php firmasite_profile_field_custom_change_field_visibility(); ?>
                                        </div>
                                    </div>
                
                                <?php endif; ?>
                
                                <?php if ( 'datebox' == bp_get_the_profile_field_type() ) : ?>
                                    <div class="form-group datebox">
                                        <label class="control-label col-xs-12 col-md-3" for="<?php bp_the_profile_field_input_name(); ?>_day"><?php bp_the_profile_field_name(); ?> <?php if ( bp_get_the_profile_field_is_required() ) : ?><?php _e( '(required)', 'firmasite' ); ?><?php endif; ?></label>
                                        <div class="col-xs-12 col-md-9 form-inline">
                                        <?php do_action( 'bp_' . bp_get_the_profile_field_input_name() . '_errors' ); ?>
                                        <div class="row">
                                            <div class="col-md-4">
                                            <select name="<?php bp_the_profile_field_input_name(); ?>_day" id="<?php bp_the_profile_field_input_name(); ?>_day" <?php if ( bp_get_the_profile_field_is_required() ) : ?>aria-required="true"<?php endif; ?>>
                    
                                                <?php bp_the_profile_field_options( 'type=day' ); ?>
                    
                                            </select>
                                            </div>
                                            <div class="col-md-4">
                                            <select name="<?php bp_the_profile_field_input_name(); ?>_month" id="<?php bp_the_profile_field_input_name(); ?>_month" <?php if ( bp_get_the_profile_field_is_required() ) : ?>aria-required="true"<?php endif; ?>>
                    
                                                <?php bp_the_profile_field_options( 'type=month' ); ?>
                    
                                            </select>
                                            </div>
                                            <div class="col-md-4">
                                            <select name="<?php bp_the_profile_field_input_name(); ?>_year" id="<?php bp_the_profile_field_input_name(); ?>_year" <?php if ( bp_get_the_profile_field_is_required() ) : ?>aria-required="true"<?php endif; ?>>
                    
                                                <?php bp_the_profile_field_options( 'type=year' ); ?>
                    
                                            </select>
                                            </div>
                                       </div>
                                            <?php firmasite_profile_field_custom_change_field_visibility(); ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                
							</div>

						<?php endwhile; ?>

												<?php endwhile; endif; endif; ?>

					</div><!-- #profile-details-section -->

					<?php do_action( 'bp_after_signup_profile_fields' ); ?>

				<?php endif; ?>


			<?php endif; // request-details signup step ?>


			</form>

		</div>
       </div>
               
     </div>
                
		</div><!-- .padder -->
  
	</div><!-- #content -->

