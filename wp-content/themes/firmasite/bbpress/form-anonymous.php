<?php

/**
 * Anonymous User
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<?php if ( bbp_is_anonymous() || ( bbp_is_topic_edit() && bbp_is_topic_anonymous() ) || ( bbp_is_reply_edit() && bbp_is_reply_anonymous() ) ) : ?>
<div class="row">
    <div class="col-xs-12 col-sm-5 col-md-5">

	<?php do_action( 'bbp_theme_before_anonymous_form' ); ?>

	<fieldset class="bbp-form">
		<legend><?php ( bbp_is_topic_edit() || bbp_is_reply_edit() ) ? _e( 'Author Information', 'firmasite' ) : _e( 'Your information:', 'firmasite' ); ?></legend>

		<?php do_action( 'bbp_theme_anonymous_form_extras_top' ); ?>

		<div class="form-group">
        	<div class="input-group">
                <span class="input-group-addon"><i class="icon-user"></i></span>
                <input type="text" id="bbp_anonymous_author" class="form-control" placeholder="<?php _e( 'Name (required):', 'firmasite' ); ?>"  value="<?php bbp_is_topic_edit() ? bbp_topic_author()       : bbp_is_reply_edit() ? bbp_reply_author()       : bbp_current_anonymous_user_data( 'name' );    ?>" tabindex="<?php bbp_tab_index(); ?>" size="40" name="bbp_anonymous_name" />
			</div>
        </div>

		<div class="form-group">
        	<div class="input-group">
			<span class="input-group-addon"><i class="icon-envelope"></i></span>
			<input type="text" id="bbp_anonymous_email" class="form-control" placeholder="<?php _e( 'Mail (will not be published) (required):', 'firmasite' ); ?>"  value="<?php bbp_is_topic_edit() ? bbp_topic_author_email() : bbp_is_reply_edit() ? bbp_reply_author_email() : bbp_current_anonymous_user_data( 'email' );   ?>" tabindex="<?php bbp_tab_index(); ?>" size="40" name="bbp_anonymous_email" />
			</div>
        </div>

		<div class="form-group">
        	<div class="input-group">
			<span class="input-group-addon"><i class="icon-globe"></i></span>
			<input type="text" id="bbp_anonymous_website" class="form-control" placeholder="<?php _e( 'Website:', 'firmasite' ); ?>" value="<?php bbp_is_topic_edit() ? bbp_topic_author_url()   : bbp_is_reply_edit() ? bbp_reply_author_url()   : bbp_current_anonymous_user_data( 'website' ); ?>" tabindex="<?php bbp_tab_index(); ?>" size="40" name="bbp_anonymous_website" />
			</div>
        </div>

		<?php do_action( 'bbp_theme_anonymous_form_extras_bottom' ); ?>

	</fieldset>

	<?php do_action( 'bbp_theme_after_anonymous_form' ); ?>
    

<?php endif; ?>
