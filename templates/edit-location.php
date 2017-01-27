<?php $geo = ldl_get_value('geo'); ?>

<div class="directory-lite edit-location">

    <?php ldl_get_header(); ?>

    <h2><?php printf( __( 'Edit location for &ldquo;%s&rdquo;', 'ldd-directory-lite' ), ldl_get_value('title') ); ?>&rdquo;</h2>

    <form id="submit-listing" name="submit-listing" method="post" enctype="multipart/form-data" novalidate>
        <input type="hidden" name="action" value="edit-location">
        <?php echo wp_nonce_field('edit-location', 'nonce_field', 0, 0); ?>

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="control-label" for="f_address_one"><?php _e('Address Line One', 'ldd-directory-lite'); ?></label>
                        <input type="text" id="f_address_one" class="form-control" name="n_address_one" value="<?php echo ldl_get_value('address_one'); ?>" placeholder="<?php _e('Address Line 1', 'ldd-directory-lite'); ?>">
                        <?php echo ldl_get_error('address_one'); ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="control-label" for="f_address_two"><?php _e('Address Line Two', 'ldd-directory-lite'); ?></label>
                        <input type="text" id="f_address_two" class="form-control bump-down" name="n_address_two" value="<?php echo ldl_get_value('address_two'); ?>" placeholder="<?php _e('Address Line Two', 'ldd-directory-lite'); ?>">
                        <?php echo ldl_get_error('address_two'); ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label" for="f_city"><?php _e('City', 'ldd-directory-lite'); ?></label>
                        <input type="text" id="f_city" class="form-control" name="n_city" value="<?php echo ldl_get_value('city'); ?>" placeholder="<?php _e('City or Town', 'ldd-directory-lite'); ?>">
                        <?php echo ldl_get_error('city'); ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label" for="f_state"><?php _e('State / Province', 'ldd-directory-lite'); ?></label>
                        <input type="text" id="f_state" class="form-control" name="n_state" value="<?php echo ldl_get_value('state'); ?>" placeholder="<?php _e('State, Province or Region', 'ldd-directory-lite'); ?>">
                        <?php echo ldl_get_error('state'); ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label" for="f_postal_code"><?php _e('Zip / Postal Code', 'ldd-directory-lite'); ?></label>
                        <input type="text" id="f_postal_code" class="form-control" name="n_postal_code" value="<?php echo ldl_get_value('postal_code'); ?>" placeholder="<?php _e('Zip or Postal Code', 'ldd-directory-lite'); ?>">
                        <?php echo ldl_get_error('postal_code'); ?>
                    </div>
                </div>            
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label" for="f_country"><?php _e('Country', 'ldd-directory-lite'); ?></label>
                        <input type="text" id="f_country" class="form-control" name="n_country" value="<?php echo ldl_get_value('country'); ?>" placeholder="<?php _e('Country or Region', 'ldd-directory-lite'); ?>">
                        <?php echo ldl_get_error('country'); ?>
                    </div>
                </div>
            </div>
            <?php if (ldl_use_google_maps()): ?>
              <!--  <div class="row bump-down">
                    <div class="col-md-12">
                        <p><?php /*_e('To set a marker, use the location field to search the address. If the search is unable to find the exact address, you can drag the marker anywhere on the map.', 'ldd-directory-lite'); */?></p>
                        <a href="#" id="clear-marker" class="btn btn-default btn-sm" role="button" disabled="disabled">Clear Marker</a>
                        <a href="#" id="center-marker" class="btn btn-default btn-sm" role="button">Center Marker</a>
                    </div>
                </div>-->
                <div class="row bump-down">
                    <div class="col-md-12">
                        <label class="control-label" for="geo"><?php _e('Location:', 'ldd-directory-lite'); ?></label>
                        <i class="full_address_i"></i>
                        <input type="text" id="geo" style="display:none;" class="autocomplete full_address_geo form-control" >
                        <div class="map-canvas"  id="map_canvas"></div>
                        <input type="hidden" class="lat" id="lat" name="n_geo[lat]" value="<?php echo $geo['lat']; ?>">
                        <input type="hidden" class="lng" id="lng" name="n_geo[lng]" value="<?php echo $geo['lng']; ?>">
                        <?php echo ldl_get_error('geo'); ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <?php ldl_get_template_part('edit', 'submit'); ?>
    </form>

</div>

<script>

    var btn = jQuery("#clear-marker");
    var lat = jQuery("#lat");
    var lng = jQuery("#lng");

    (function(){

        if ('' != lat.val() && '' != lng.val) {
            btn.removeAttr('disabled');
        }

        btn.on("click", function(e) {
            e.preventDefault();
            lat.val('');
            lng.val('');
            jQuery(this).attr('disabled','disabled');
        })
    }(jQuery))

</script>