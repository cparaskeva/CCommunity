
<div class="item-list-tabs no-ajax tabs-top" id="subnav" role="navigation">
    <ul class="nav nav-pills">
        <?php bp_group_admin_tabs(); ?>
    </ul>
</div><!-- .item-list-tabs -->

<form action="<?php bp_group_admin_form_action(); ?>" name="group-settings-form" id="group-settings-form" class="standard-form" method="post" enctype="multipart/form-data" role="main">

    <?php do_action('bp_before_group_admin_content'); ?>

    <?php /* Edit Group Details */ ?>
    <?php if (bp_is_group_admin_screen('edit-details')) : ?>

        <?php do_action('bp_before_group_details_admin'); ?>
        <?php global $cecom; ?>
        <!-- Set Organization ID--> 
        <input type="hidden" class="form-control" name="organization_id" id="organization_id" value="<?php echo $cecom->organization->details['id']; ?>"/>
        <!-- Hidden Fields for Sector/Subsector-->   
        <input type="hidden" class="form-control" name="organization_sectors" id="organization_sectors" value=""/>
        <input type="hidden" class="form-control" name="organization_subsectors" id="organization_subsectors" value=""/>
        <!-- End of Hidden Fields -->
        <label for="group-name"><?php _e('Organisation Name (required)', 'firmasite'); ?></label>
        <input type="text" name="group-name" id="group-name" value="<?php bp_group_name(); ?>" aria-required="true" /><br/>

        <label style="margin:0px" for="group-desc"><?php _e('Organisation Description (required)', 'firmasite'); ?></label>
        <?php
        $content = bp_get_group_description_editable();
        echo firmasite_wp_editor($content, 'group-desc');
        /*
          <textarea name="group-desc" id="group-desc" aria-required="true"><?php bp_group_description_editable(); ?></textarea>
         */
        ?>

        <?php do_action('groups_custom_group_fields_editable'); ?>

        <!-- Start of CECOM Organization Custom Fields --> 
        <div>
            <!-- Hold the ID of the selected country from the organization_country list -->
            <input type="hidden"  name="organization_countryID" id="organization_countryID" value="<?php echo $cecom->organization->details['country'] ?>" />
            <br/>
            <label for="organization_specialties"><?php _e('Specialities', 'firmasite'); ?> </label>
            <input type="text"  name="organization_specialties" id="organization_specialties" value="<?php echo $cecom->organization->details['specialties'] ?>" aria-required="false"/>
            <br/>
            <label for="organization_website"><?php _e('Organisation Website', 'firmasite'); ?> </label>
            <input type="text"  name="organization_website" id="organization_specialties" value="<?php echo $cecom->organization->details['website'] ?>" aria-required="false"/>
            <br/>
            <label  for="organization_country"><?php _e('Country', 'firmasite'); ?></label>
            <div  onchange="setOrganizationCountryID()" id="organization_country" class="bfh-selectbox bfh-countries" data-country="<?php echo $cecom->organization->details['country'] ?>" data-flags="true"> </div>
            <br/>
            <label  for="organization_size"><?php _e('Organization Size', 'firmasite'); ?> </label>
            <select   name="organization_size" id="organization_size" >
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

                        if ($cecom->organization->details['size_min'] == $org_size->min && $cecom->organization->details['size_max'] == $org_size->max)
                            echo "<option selected=\"selected\" value = '{$org_size->id }'>$min$minus$max</option>";
                        else
                            echo "<option value = '{$org_size->id }'>$min$minus$max</option>";
                    }
                }
                ?>
            </select>
            <br/>
            <label  for="organization_type"><?php _e('Type of Organization', 'firmasite'); ?> </label>
            <select   name="organization_type" id="organization_type" aria-required="false">
                <?php
                //Fetch Organization Types form DB
                $results = CECOM_Organization::getOrganizationType();
                if (is_array($results)) {

                    foreach ($results as $org_type) {
                        if ($cecom->organization->details['type'] == $org_type->description)
                            echo "<option selected=\"selected\" value = '{$org_type->id }'>{$org_type->description}</option>";
                        else
                            echo "<option value = '{$org_type->id }'>{$org_type->description}</option>";
                    }
                }
                ?>
            </select>
            <br/>
            <label  for="organization_sector"><?php _e('Sector', 'firmasite'); ?> </label>
            <select  name="organization_sector" id="organization_sector" class="multiselect" multiple="multiple" >
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
            <br/><br/>
            <label  for="organization_subsector"><?php _e('Subector', 'firmasite'); ?> </label>
            <select name="organization_subsector" id="organization_subsector" class="multiselect" multiple="multiple">
                <?php
                //Fetch Organization Subsectors form DB
                $results = CECOM_Organization::getOrganizationSubsector($cecom->organization->details['sector_id']);
                if (is_array($results)) {

                    foreach ($results as $org_subsector) {
                        if ($cecom->organization->details['subsector_id'] == $org_subsector->id)
                            echo "<option selected=\"selected\" value = '{$org_subsector->id }'>{$org_subsector->description}</option>";
                        else
                            echo "<option value = '{$org_subsector->id }'>{$org_subsector->description}</option>";
                    }
                }
                ?>

            </select><br/>
            <label  for="organization_collaboration"><?php _e('Available for collaboration', 'firmasite'); ?> </label>
            <input type="radio" <?php if ($cecom->organization->details['collaboration']) echo "checked=\"yes\""; ?>  name="organization_collaboration_y" id="organization_collaboration_y" aria-required="false"> &nbsp;<strong>Yes</strong>&nbsp;&nbsp;
            <input type="radio" <?php if (!$cecom->organization->details['collaboration']) echo "checked=\"yes\""; ?> name="organization_collaboration_n" id="organization_collaboration_n"  aria-required="false"> &nbsp;<strong>No</strong>
            <br/>
            <label  for="organization_transaction"><?php _e('Available for transaction', 'firmasite'); ?> </label>&nbsp;&nbsp;&nbsp;
            <input type="radio" <?php if ($cecom->organization->details['transaction']) echo "checked=\"yes\""; ?> name="organization_transaction_y" id="organization_transaction_y" aria-required="false"> &nbsp;<strong>Yes</strong>&nbsp;&nbsp;
            <input type="radio" <?php if (!$cecom->organization->details['transaction']) echo "checked=\"yes\""; ?>name="organization_transaction_n" id="organization_transaction_n" ria-required="false"> &nbsp;<strong>No</strong>

        </div>
        <!-- End of CECOM Organization Custom Fields -->



        <p>
        <hr/>
        <label for="group-notifiy-members"><?php _e('Notify group members of changes via email', 'firmasite'); ?></label>
        <input type="radio" name="group-notify-members" value="1" /> <?php _e('Yes', 'firmasite'); ?>&nbsp;
        <input type="radio" name="group-notify-members" value="0" checked="checked" /> <?php _e('No', 'firmasite'); ?>&nbsp;
    </p>

    <?php do_action('bp_after_group_details_admin'); ?>

    <p><input type="submit" class="btn  btn-primary" value="<?php _e('Save Changes', 'firmasite'); ?>" id="save" name="save" /></p>
    <?php wp_nonce_field('groups_edit_group_details'); ?>

<?php endif; ?>

<?php /* Manage Group Settings */ ?>
<?php if (bp_is_group_admin_screen('group-settings')) : ?>

    <?php do_action('bp_before_group_settings_admin'); ?>

    <?php if (bp_is_active('forums')) : ?>

        <?php if (bp_forums_is_installed_correctly()) : ?>

            <div class="checkbox">
                <label><input type="checkbox" name="group-show-forum" id="group-show-forum" value="1"<?php bp_group_show_forum_setting(); ?> /> <?php _e('Enable discussion forum', 'firmasite'); ?></label>
            </div>

            <hr />

        <?php endif; ?>

    <?php endif; ?>

    <h4 class="page-header"><?php _e('Privacy Options', 'firmasite'); ?></h4>

    <div class="radio">
        <label>
            <input type="radio" name="group-status" value="public"<?php bp_group_show_status_setting('public'); ?> />
            <strong><?php _e('This is a public group', 'firmasite'); ?></strong>
            <ul class="nav nav-pills">
                <li><?php _e('Any site member can join this group.', 'firmasite'); ?></li>
                <li><?php _e('This group will be listed in the groups directory and in search results.', 'firmasite'); ?></li>
                <li><?php _e('Group content and activity will be visible to any site member.', 'firmasite'); ?></li>
            </ul>
        </label>

        <label>
            <input type="radio" name="group-status" value="private"<?php bp_group_show_status_setting('private'); ?> />
            <strong><?php _e('This is a private group', 'firmasite'); ?></strong>
            <ul class="nav nav-pills">
                <li><?php _e('Only users who request membership and are accepted can join the group.', 'firmasite'); ?></li>
                <li><?php _e('This group will be listed in the groups directory and in search results.', 'firmasite'); ?></li>
                <li><?php _e('Group content and activity will only be visible to members of the group.', 'firmasite'); ?></li>
            </ul>
        </label>

        <label>
            <input type="radio" name="group-status" value="hidden"<?php bp_group_show_status_setting('hidden'); ?> />
            <strong><?php _e('This is a hidden group', 'firmasite'); ?></strong>
            <ul class="nav nav-pills">
                <li><?php _e('Only users who are invited can join the group.', 'firmasite'); ?></li>
                <li><?php _e('This group will not be listed in the groups directory or search results.', 'firmasite'); ?></li>
                <li><?php _e('Group content and activity will only be visible to members of the group.', 'firmasite'); ?></li>
            </ul>
        </label>
    </div>

    <hr /> 

    <h4 class="page-header"><?php _e('Group Invitations', 'firmasite'); ?></h4> 

    <p><?php _e('Which members of this group are allowed to invite others?', 'firmasite'); ?></p> 

    <div class="radio"> 
        <label> 
            <input type="radio" name="group-invite-status" value="members"<?php bp_group_show_invite_status_setting('members'); ?> /> 
            <strong><?php _e('All group members', 'firmasite'); ?></strong> 
        </label> 

        <label> 
            <input type="radio" name="group-invite-status" value="mods"<?php bp_group_show_invite_status_setting('mods'); ?> /> 
            <strong><?php _e('Group admins and mods only', 'firmasite'); ?></strong> 
        </label>

        <label> 
            <input type="radio" name="group-invite-status" value="admins"<?php bp_group_show_invite_status_setting('admins'); ?> /> 
            <strong><?php _e('Group admins only', 'firmasite'); ?></strong> 
        </label> 
    </div> 

    <hr /> 

    <?php do_action('bp_after_group_settings_admin'); ?>

    <p><input type="submit" class="btn  btn-primary" value="<?php _e('Save Changes', 'firmasite'); ?>" id="save" name="save" /></p>
    <?php wp_nonce_field('groups_edit_group_settings'); ?>

<?php endif; ?>

<?php /* Group Avatar Settings */ ?>
<?php if (bp_is_group_admin_screen('group-avatar')) : ?>

    <?php if ('upload-image' == bp_get_avatar_admin_step()) : ?>

        <p><?php _e("Upload an image to use as an avatar for this group. The image will be shown on the main group page, and in search results.", 'firmasite'); ?></p>

        <p>
            <input type="file" name="file" id="file" />
            <input type="submit" class="btn  btn-primary" name="upload" id="upload" value="<?php _e('Upload Image', 'firmasite'); ?>" />
            <input type="hidden" name="action" id="action" value="bp_avatar_upload" />
        </p>

        <?php if (bp_get_group_has_avatar()) : ?>

            <p><?php _e("If you'd like to remove the existing avatar but not upload a new one, please use the delete avatar button.", 'firmasite'); ?></p>

            <?php bp_button(array('id' => 'delete_group_avatar', 'component' => 'groups', 'wrapper_id' => 'delete-group-avatar-button', 'link_class' => 'edit', 'link_href' => bp_get_group_avatar_delete_link(), 'link_title' => __('Delete Avatar', 'firmasite'), 'link_text' => __('Delete Avatar', 'firmasite'))); ?>

        <?php endif; ?>

        <?php wp_nonce_field('bp_avatar_upload'); ?>

    <?php endif; ?>

    <?php if ('crop-image' == bp_get_avatar_admin_step()) : ?>

        <h3 class="page-header"><?php _e('Crop Avatar', 'firmasite'); ?></h3>

        <img src="<?php bp_avatar_to_crop(); ?>" id="avatar-to-crop" class="" alt="<?php _e('Avatar to crop', 'firmasite'); ?>" />

        <div id="avatar-crop-pane">
            <img src="<?php bp_avatar_to_crop(); ?>" id="avatar-crop-preview" class="" alt="<?php _e('Avatar preview', 'firmasite'); ?>" />
        </div>

        <input type="submit" class="btn  btn-primary" name="avatar-crop-submit" id="avatar-crop-submit" value="<?php _e('Crop Image', 'firmasite'); ?>" />

        <input type="hidden" name="image_src" id="image_src" value="<?php bp_avatar_to_crop_src(); ?>" />
        <input type="hidden" id="x" name="x" />
        <input type="hidden" id="y" name="y" />
        <input type="hidden" id="w" name="w" />
        <input type="hidden" id="h" name="h" />

        <?php wp_nonce_field('bp_avatar_cropstore'); ?>

    <?php endif; ?>

<?php endif; ?>

<?php /* Manage Group Members */ ?>
<?php if (bp_is_group_admin_screen('manage-members')) : ?>

    <?php do_action('bp_before_group_manage_members_admin'); ?>

    <div class="bp-widget">
        <h4 class="page-header"><?php _e('Administrators', 'firmasite'); ?></h4>

        <?php if (bp_has_members('&include=' . bp_group_admin_ids())) : ?>

            <ul id="admins-list" class="item-list single-line">

                <?php while (bp_members()) : bp_the_member(); ?>
                    <li>
                        <?php echo bp_core_fetch_avatar(array('item_id' => bp_get_member_user_id(), 'type' => 'thumb', 'width' => 30, 'height' => 30, 'alt' => sprintf(__('Profile picture of %s', 'firmasite'), bp_get_member_name()))); ?>
                        <h5>
                            <a href="<?php bp_member_permalink(); ?>"> <?php bp_member_name(); ?></a>
                            <?php if (count(bp_group_admin_ids(false, 'array')) > 1) : ?>
                                <span class="small">
                                    <a class="button btn btn-default confirm admin-demote-to-member" href="<?php bp_group_member_demote_link(bp_get_member_user_id()); ?>"><?php _e('Demote to Member', 'firmasite'); ?></a>
                                </span>			
                            <?php endif; ?>
                        </h5>		
                    </li>
                <?php endwhile; ?>

            </ul>

        <?php endif; ?>

    </div>

    <?php if (bp_group_has_moderators()) : ?>
        <div class="bp-widget">
            <h4 class="page-header"><?php _e('Moderators', 'firmasite'); ?></h4>		

            <?php if (bp_has_members('&include=' . bp_group_mod_ids())) : ?>
                <ul id="mods-list" class="item-list single-line">

                    <?php while (bp_members()) : bp_the_member(); ?>					
                        <li>
                            <?php echo bp_core_fetch_avatar(array('item_id' => bp_get_member_user_id(), 'type' => 'thumb', 'width' => 30, 'height' => 30, 'alt' => sprintf(__('Profile picture of %s', 'firmasite'), bp_get_member_name()))); ?>
                            <h5>
                                <a href="<?php bp_member_permalink(); ?>"> <?php bp_member_name(); ?></a>
                                <span class="small">
                                    <a href="<?php bp_group_member_promote_admin_link(array('user_id' => bp_get_member_user_id())); ?>" class="button btn btn-default confirm mod-promote-to-admin" title="<?php _e('Promote to Admin', 'firmasite'); ?>"><?php _e('Promote to Admin', 'firmasite'); ?></a>
                                    <a class="button btn  confirm mod-demote-to-member" href="<?php bp_group_member_demote_link(bp_get_member_user_id()); ?>"><?php _e('Demote to Member', 'firmasite'); ?></a>
                                </span>		
                            </h5>		
                        </li>	
                    <?php endwhile; ?>			

                </ul>

            <?php endif; ?>
        </div>
    <?php endif ?>


    <div class="bp-widget">
        <h4 class="page-header"><?php _e("Members", 'firmasite'); ?></h4>

        <?php if (bp_group_has_members('per_page=15&exclude_banned=false')) : ?>

            <?php if (bp_group_member_needs_pagination()) : ?>

                <div class="pagination no-ajax">

                    <div id="member-count" class="pag-count">
                        <?php bp_group_member_pagination_count(); ?>
                    </div>

                    <div id="member-admin-pagination" class="pagination-links lead">
                        <?php bp_group_member_admin_pagination(); ?>
                    </div>

                </div>

            <?php endif; ?>

            <ul id="members-list" class="item-list single-line">
                <?php while (bp_group_members()) : bp_group_the_member(); ?>

                    <li class="<?php bp_group_member_css_class(); ?>">
                        <?php bp_group_member_avatar_mini(); ?>

                        <h5>
                            <?php bp_group_member_link(); ?>

                            <?php if (bp_get_group_member_is_banned()) _e('(banned)', 'firmasite'); ?>

                            <span class="small">

                                <?php if (bp_get_group_member_is_banned()) : ?>

                                    <a href="<?php bp_group_member_unban_link(); ?>" class="button btn btn-default confirm member-unban" title="<?php _e('Unban this member', 'firmasite'); ?>"><?php _e('Remove Ban', 'firmasite'); ?></a>

                                <?php else : ?>

                                    <a href="<?php bp_group_member_ban_link(); ?>" class="button btn btn-default confirm member-ban" title="<?php _e('Kick and ban this member', 'firmasite'); ?>"><?php _e('Kick &amp; Ban', 'firmasite'); ?></a>
                                    <a href="<?php bp_group_member_promote_mod_link(); ?>" class="button btn btn-default confirm member-promote-to-mod" title="<?php _e('Promote to Mod', 'firmasite'); ?>"><?php _e('Promote to Mod', 'firmasite'); ?></a>
                                    <a href="<?php bp_group_member_promote_admin_link(); ?>" class="button btn btn-default confirm member-promote-to-admin" title="<?php _e('Promote to Admin', 'firmasite'); ?>"><?php _e('Promote to Admin', 'firmasite'); ?></a>

                                <?php endif; ?>

                                <a href="<?php bp_group_member_remove_link(); ?>" class="button btn btn-default confirm" title="<?php _e('Remove this member', 'firmasite'); ?>"><?php _e('Remove from group', 'firmasite'); ?></a>

                                <?php do_action('bp_group_manage_members_admin_item'); ?>

                            </span>
                        </h5>
                    </li>

                <?php endwhile; ?>
            </ul>

        <?php else: ?>

            <div class="clearfix"></div><div id="message" class="info alert alert-info">
                <p><?php _e('This group has no members.', 'firmasite'); ?></p>
            </div>

        <?php endif; ?>

    </div>

    <?php do_action('bp_after_group_manage_members_admin'); ?>

<?php endif; ?>

<?php /* Manage Membership Requests */ ?>
<?php if (bp_is_group_admin_screen('membership-requests')) : ?>

    <?php do_action('bp_before_group_membership_requests_admin'); ?>

    <?php if (bp_group_has_membership_requests()) : ?>

        <ul id="request-list" class="item-list">
            <?php while (bp_group_membership_requests()) : bp_group_the_membership_request(); ?>

                <li>
                    <?php bp_group_request_user_avatar_thumb(); ?>
                    <h4 class="page-header"><?php bp_group_request_user_link(); ?> <span class="comments"><?php bp_group_request_comment(); ?></span></h4>
                    <span class="activity label label-info"><?php bp_group_request_time_since_requested(); ?></span>

                    <?php do_action('bp_group_membership_requests_admin_item'); ?>

                    <div class="action">

                        <?php bp_button(array('id' => 'group_membership_accept', 'component' => 'groups', 'wrapper_class' => 'accept', 'link_href' => bp_get_group_request_accept_link(), 'link_title' => __('Accept', 'firmasite'), 'link_text' => __('Accept', 'firmasite'))); ?>

                        <?php bp_button(array('id' => 'group_membership_reject', 'component' => 'groups', 'wrapper_class' => 'reject', 'link_href' => bp_get_group_request_reject_link(), 'link_title' => __('Reject', 'firmasite'), 'link_text' => __('Reject', 'firmasite'))); ?>

                        <?php do_action('bp_group_membership_requests_admin_item_action'); ?>

                    </div>
                </li>

            <?php endwhile; ?>
        </ul>

    <?php else: ?>

        <div class="clearfix"></div><div id="message" class="info alert alert-info">
            <p><?php _e('There are no pending membership requests.', 'firmasite'); ?></p>
        </div>

    <?php endif; ?>

    <?php do_action('bp_after_group_membership_requests_admin'); ?>

<?php endif; ?>

<?php do_action('groups_custom_edit_steps') // Allow plugins to add custom group edit screens     ?>

<?php /* Delete Group Option */ ?>
<?php if (bp_is_group_admin_screen('delete-group')) : ?>

    <?php do_action('bp_before_group_delete_admin'); ?>

    <div class="clearfix"></div><div id="message" class="info alert alert-info">
        <p><?php _e('WARNING: Deleting this group will completely remove ALL content associated with it. There is no way back, please be careful with this option.', 'firmasite'); ?></p>
    </div>

    <label><input type="checkbox" name="delete-group-understand" id="delete-group-understand" value="1" onclick="if (this.checked) {
                document.getElementById('delete-group-button').disabled = '';
            } else {
                document.getElementById('delete-group-button').disabled = 'disabled';
            }" /> <?php _e('I understand the consequences of deleting this group.', 'firmasite'); ?></label>

    <?php do_action('bp_after_group_delete_admin'); ?>

    <div class="submit">
        <input type="submit" class="btn  btn-primary" disabled="disabled" value="<?php _e('Delete Group', 'firmasite'); ?>" id="delete-group-button" name="delete-group-button" />
    </div>

    <?php wp_nonce_field('groups_delete_group'); ?>

<?php endif; ?>

<?php /* This is important, don't forget it */ ?>
<input type="hidden" name="group-id" id="group-id" value="<?php bp_group_id(); ?>" />

<?php do_action('bp_after_group_admin_content'); ?>

</form><!-- #group-settings-form -->



<script type = "text/javascript">

    /*
     * Radio buttons check for Yes/No fields
     */


//Collaboration Radio Buttons
    jQuery("#organization_collaboration_y").click(function() {
        jQuery("#organization_collaboration_n").attr("checked", false);
    });

    jQuery("#organization_collaboration_n").click(function() {
        jQuery("#organization_collaboration_y").attr("checked", false);
    });


//Transaction Radio Buttons
    jQuery("#organization_transaction_y").click(function() {
        jQuery("#organization_transaction_n").attr("checked", false);
    });

    jQuery("#organization_transaction_n").click(function() {
        jQuery("#organization_transaction_y").attr("checked", false);
    });



//Get the ID of the selected country from the organization_country list and save it to the hidden field (organization_countryID)
    function setOrganizationCountryID() {
        jQuery("#organization_countryID").val(jQuery("#organization_country").val());

    }

    /*
     * Organization sector
     */


    jQuery("#organization_sector").change(function() {

        var selectedTexts = [];

        jQuery(this).find("option:selected").each(function(i) {
            var val = jQuery(this).val();
            var txt = jQuery(this).text();
            selectedTexts[i] = txt;
        });

        setSubsctorValues(jQuery('.multiselect').val(), selectedTexts);

        //Set the values to hidden field
        jQuery("#organization_sectors").val(jQuery(this).val());
        jQuery("#organization_subsectors").val("");

    });

    /*
     * Organization subsector
     */


    jQuery("#organization_subsector").change(function() {
        //Set the values to hidden field
        jQuery("#organization_subsectors").val(jQuery("#organization_subsector").val());

    });






    jQuery(document).ready(function() {
        jQuery("#organization_sector").multiselect({numberDisplayed: 1});

<?php
$sector_values = "[";
$sector_txt = "[";
$subsector_values = "[";
foreach ($cecom->organization->details['sectors'] as $sector) {
    $sector_values .= "'" . $sector['id'] . "',";
    $sector_txt .= "'" . $sector['description'] . "',";
}
$sector_values = substr($sector_values, 0, -1) . "]";
$sector_txt = substr($sector_txt, 0, -1) . "]";

foreach ($cecom->organization->details['subsectors'] as $subsector) {
    $subsector_values .= "'" . $subsector['id'] . "',";
}
$subsector_values = substr($subsector_values, 0, -1) . "]";
?>

        jQuery("#organization_sector").multiselect('select', <?php echo $sector_values; ?>);
        jQuery("#organization_subsector").multiselect({numberDisplayed: 5, maxHeight: 300, enableFiltering: true});
        setSubsctorValues(<?php echo $sector_values; ?>,<?php echo $sector_txt; ?>,<?php echo $subsector_values; ?>)
    });



</script>