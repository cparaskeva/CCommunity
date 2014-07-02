<?php do_action('bp_before_group_delete_admin'); ?>

    <br/>
    <div class="clearfix"></div><div id="message" class="info alert alert-info">
        <p><?php _e('WARNING: Deleting this offer will completely remove ALL content associated with it. There is no way back, please be careful with this option.', 'firmasite'); ?></p>
    </div>

    <label><input type="checkbox" name="delete-offer-understand" id="delete-group-understand" value="1" onclick="if (this.checked) {
                    document.getElementById('delete-offer-button').disabled = '';
                } else {
                    document.getElementById('delete-offer-button').disabled = 'disabled';
                }" /> <?php _e('I understand the consequences of deleting this offer.', 'firmasite'); ?></label>


    <div class="submit">
        <input type="submit" class="btn  btn-primary" disabled="disabled" value="<?php _e('Delete Offer', 'firmasite'); ?>" id="delete-offer-button" name="delete-offer-button" />
        <br/><br/>
    </div>

    <?php wp_nonce_field('offers_delete_offer'); ?>