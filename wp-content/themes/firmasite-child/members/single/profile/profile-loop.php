<?php do_action( 'bp_before_profile_loop_content' ); ?>

<?php if ( bp_has_profile() ) : ?>

	<?php while ( bp_profile_groups() ) : bp_the_profile_group(); ?>

		<?php if ( bp_profile_group_has_fields() ) : ?>

			<?php do_action( 'bp_before_profile_field_content' ); ?>

			<div class="bp-widget <?php bp_the_profile_group_slug(); ?>">

				<h4><?php echo bp_get_displayed_user_mentionname(); ?></h4>

				<table class="table table-hover  profile-fields">

					<?php while ( bp_profile_fields() ) : bp_the_profile_field(); ?>

						<?php if ( bp_field_has_data() ) : ?>

							<tr<?php bp_field_css_class(); ?>>

								<td class=""><?php bp_the_profile_field_name(); ?></td>

								<td class="data"><?php bp_the_profile_field_value(); ?></td>

							</tr>

						<?php endif; ?>

						<?php do_action( 'bp_profile_field_item' ); ?>

					<?php endwhile; ?>

					 <!-- Custom field: password of user -->
                                              
 						<tr<?php bp_field_css_class(); ?>>
						       <td class="">Password</td>
						       <td class="data"><p>******</p></td>
						</tr>
                                                        
                                      <!-- Custom field: email of user -->
                                                <tr<?php bp_field_css_class(); ?>>
							<td class="">Email</td>
							<td class="data"><?php echo bp_get_displayed_user_email(); ?></td>
						</tr>
                                                        

				</table>
			</div>

			<?php do_action( 'bp_after_profile_field_content' ); ?>

		<?php endif; ?>

	<?php endwhile; ?>

	<?php do_action( 'bp_profile_field_buttons' ); ?>

<?php endif; ?>

<?php do_action( 'bp_after_profile_loop_content' ); ?>
