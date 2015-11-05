<?php
$geo = ldl_get_value('geo');
if (!is_array($geo)) {
    $geo = array('lat'=>'','lng'=>'');
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <p class="section"><?php _e('Providing an address for your listing is optional.', 'ldd-directory-lite'); ?></p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label" for="f_title"><?php _e('Address', 'ldd-directory-lite'); ?></label>
                <input type="text" id="f_address_one" class="form-control" name="n_address_one" value="<?php echo ldl_get_value('address_one'); ?>" placeholder="<?php _e('2101 Massachusetts Ave, NW', 'ldd-directory-lite'); ?>">
                <input type="text" id="f_address_two" class="form-control bump-down" name="n_address_two" value="<?php echo ldl_get_value('address_two'); ?>" placeholder="<?php _e('Washington, DC', 'ldd-directory-lite'); ?>">
                <?php echo ldl_get_error('address_one'); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label" for="f_city"><?php _e('City', 'ldd-directory-lite'); ?></label>
                <input type="text" id="f_city" class="form-control" name="n_city" value="<?php echo ldl_get_value('city'); ?>" placeholder="<?php _e('London', 'ldd-directory-lite'); ?>">
                <?php echo ldl_get_error('city'); ?>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label" for="f_postal_code"><?php _e('Zip / Postal Code', 'ldd-directory-lite'); ?></label>
                <input type="text" id="f_postal_code" class="form-control" name="n_postal_code" value="<?php echo ldl_get_value('postal_code'); ?>" placeholder="<?php _e('20008', 'ldd-directory-lite'); ?>">
                <?php echo ldl_get_error('postal_code'); ?>
            </div>
        </div>
        </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label" for="f_country"><?php _e('Country', 'ldd-directory-lite'); ?></label>
                <input type="text" id="f_country" class="form-control" name="n_country" value="<?php echo ldl_get_value('country'); ?>" placeholder="<?php _e('United States', 'ldd-directory-lite'); ?>">
                <?php echo ldl_get_error('country'); ?>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label" for="f_state"><?php _e('State/province', 'ldd-directory-lite'); ?></label>
                <input type="text" id="f_state" class="form-control" name="n_state" value="<?php echo ldl_get_value('state'); ?>" placeholder="<?php _e('United Kingdom', 'ldd-directory-lite'); ?>">
                <?php echo ldl_get_error('state'); ?>
            </div>
        </div>
    </div>
    <?php if (ldl_use_google_maps()): ?>
    <div class="row bump-down">
		<div class="col-md-12">
			<p><?php _e('If you would like to include a Google map with your listing, set a marker on this map for your address. Type in part of your address to use the autocomplete feature, or drag the marker on the map directly to your location.', 'ldd-directory-lite'); ?></p>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<label class="control-label" for="geo"><?php _e('Location:', 'ldd-directory-lite'); ?></label>
			<input type="text" id="geo" style="display: none;" class="form-control full_address_geo autocomplete-control">
            <i class="full_address_i"></i>
            <div class="map-canvas"  id="map_canvas"></div>
			    <input type="hidden" class="lat" id="lat" name="n_geo[lat]" value="<?php echo $geo['lat']; ?>">
			    <input type="hidden" class="lng" id="lng" name="n_geo[lng]" value="<?php echo $geo['lng']; ?>">
            <?php echo ldl_get_error('geo'); ?>
		</div>
	</div>
    <?php endif; ?>
</div>
