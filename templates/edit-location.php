<?php $geo = ldl_get_value('geo'); ?>

<div class="directory-lite edit-location">

    <?php ldl_get_header(); ?>

    <h2>Edit location for &ldquo;<?php echo ldl_get_value('title'); ?>&rdquo;</h2>

    <form id="submit-listing" name="submit-listing" method="post" enctype="multipart/form-data" novalidate>
        <input type="hidden" name="action" value="submit_form">
        <?php echo wp_nonce_field('edit-location', 'nonce_field', 0, 0); ?>

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="control-label" for="f_title"><?php _e('Address', 'lddlite'); ?></label>
                        <input type="text" id="f_address_one" class="form-control" name="n_address_one" value="<?php echo ldl_get_value('address_one'); ?>" placeholder="<?php _e('2101 Massachusetts Ave, NW', 'lddlite'); ?>">
                        <input type="text" id="f_address_two" class="form-control bump-down" name="n_address_two" value="<?php echo ldl_get_value('address_two'); ?>" placeholder="<?php _e('Washington, DC', 'lddlite'); ?>">
                        <?php echo ldl_get_error('address_one'); ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label" for="f_postal_code"><?php _e('Zip / Postal Code', 'lddlite'); ?></label>
                        <input type="text" id="f_postal_code" class="form-control" name="n_postal_code" value="<?php echo ldl_get_value('postal_code'); ?>" placeholder="<?php _e('20008', 'lddlite'); ?>">
                        <?php echo ldl_get_error('postal_code'); ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label" for="f_country"><?php _e('Country', 'lddlite'); ?></label>
                        <input type="text" id="f_country" class="form-control" name="n_country" value="<?php echo ldl_get_value('country'); ?>" placeholder="<?php _e('United States', 'lddlite'); ?>">
                        <?php echo ldl_get_error('country'); ?>
                    </div>
                </div>
            </div>
            <?php if (ldl_use_google_maps()): ?>
                <div class="row bump-down">
                    <div class="col-md-12">
                        <p><?php _e('To set a marker, use the location field to search the address. If the search is unable to find the exact address, you can drag the marker anywhere on the map.', 'lddlite'); ?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label class="control-label" for="geo"><?php _e('Set Marker', 'lddlite'); ?></label>
                        <input type="text" id="geo" class="form-control autocomplete-control">
                        <div id="map-canvas"></div>
                        <input type="hidden" id="lat" name="n_geo[lat]" value="<?php echo $geo['lat']; ?>">
                        <input type="hidden" id="lng" name="n_geo[lng]" value="<?php echo $geo['lng']; ?>">
                        <?php echo ldl_get_error('geo'); ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <?php ldl_get_template_part('edit', 'submit'); ?>
    </form>

</div>
