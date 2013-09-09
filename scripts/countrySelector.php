<script type="text/javascript">
function formElemCountry() {
	var selectedCountry = jQuery("#lddbd_address_country").val();
	
	switch( selectedCountry ) {

	case "United States":
		jQuery("#selectedCountryForm").html(
			"<div class='lddbd_input_holder'>" +
			"<label for='name'><?php _e('City / Town:', 'lddbd-text'); ?></label>" +
				"<input type='text' id='lddbd_address_city' name='address_city' /><br />" +
			"</div>" +
			"<div class='lddbd_input_holder'>" +
			"<label for='name'><?php _e('State:', 'lddbd_text'); ?></label>" +
				"<select id='lddbd_address_state' name='address_state'>" +
		// Start the loop that generates the list of US states
			<?php $statesUSA_TextFile = plugin_dir_path( __FILE__ ) . 'states_USA.txt'; ?>
			<?php $statesUSA_List = fopen( $statesUSA_TextFile, 'r' ); ?>
			<?php while( !feof ( $statesUSA_List ) ) { ?>
			<?php $textLine = fgets( $statesUSA_List ); $textLine = trim( $textLine ); ?>
				"<option><?php esc_attr_e($textLine, 'lddbd-text'); ?></option>" +
			<?php } ?>
			<?php fclose( $statesUSA_List ); ?>
		// End the loop that generates the list of US states
				"</select><br />" +
			"</div>" +
			"<div class='lddbd_input_holder'>" +
			"<label for='name'><?php _e('ZIP Code:', 'lddbd-text'); ?></label>" +
				"<input type='text' id='lddbd_address_zip' name='address_zip' />" +
			"</div>"
		);
		break;

	case "Australia":
		jQuery("#selectedCountryForm").html(
			"<div class='lddbd_input_holder'>" +
			"<label for='name'><?php _e('City / Town:', 'lddbd-text'); ?></label>" +
				"<input type='text' id='lddbd_address_city' name='address_city' /><br />" +
			"</div>" +
			"<div class='lddbd_input_holder'>" +
			"<label for='name'><?php _e('State / Territory:', 'lddbd-text'); ?></label>" +
				"<select id='lddbd_address_state' name='address_state'>" +
		// Start the loop that generates the list of Australian states/territories
			<?php $statesAUS_TextFile = plugin_dir_path( __FILE__ ) . 'states_AUS.txt'; ?>
			<?php $statesAUS_List = fopen( $statesAUS_TextFile, 'r' ); ?>
			<?php while( !feof ( $statesAUS_List ) ) { ?>
			<?php $textLine = fgets( $statesAUS_List ); $textLine = trim( $textLine ); ?>
				"<option><?php esc_attr_e($textLine, 'lddbd-text'); ?></option>" +
			<?php } ?>
			<?php fclose( $statesAUS_List ); ?>
		// End the loop that generates the list of Australian states/territories
				"</select><br />" +
			"</div>" +
			"<div class='lddbd_input_holder'>" +
			"<label for='name'><?php _e('Postcode:', 'lddbd-text'); ?></label>" +
				"<input type='text' id='lddbd_address_zip' name='address_zip' />" +
			"</div>"
		);
		break;

	case "Austria":
	case "Belgium":
	case "France":
	case "Spain":
	case "Germany":
	case "Iceland":
	case "Netherlands":
	case "Norway":
	case "Portugal":
	case "Sweden":
	case "Switzerland":
		jQuery("#selectedCountryForm").html(
			"<div class='lddbd_input_holder'>" +
			"<label for='name'><?php _e('Postcode:', 'lddbd-text'); ?></label>" +
				"<input type='text' id='lddbd_address_zip' name='address_zip' /><br />" +
			"</div>" +
			"<div class='lddbd_input_holder'>" +
			"<label for='name'><?php _e('City / Town:', 'lddbd-text'); ?></label>" +
				"<input type='text' id='lddbd_address_city' name='address_city' />" +
			"</div>"
		);
		break;

	case "Canada":
		jQuery("#selectedCountryForm").html(
			"<div class='lddbd_input_holder'>" +
			"<label for='name'><?php _e('City / Town:', 'lddbd-text'); ?></label>" +
				"<input type='text' id='lddbd_address_city' name='address_city' /><br />" +
			"</div>" +
			"<div class='lddbd_input_holder'>" +
			"<label for='name'><?php _e('Province / Territory:', 'lddbd-text'); ?></label>" +
				"<select id='lddbd_address_state' name='address_state'>" +
		// Start the loop that generates the list of Canadian provinces/territories
			<?php $statesCAN_TextFile = plugin_dir_path( __FILE__ ) . 'states_CAN.txt'; ?>
			<?php $statesCAN_List = fopen( $statesCAN_TextFile, 'r' ); ?>
			<?php while( !feof ( $statesCAN_List ) ) { ?>
			<?php $textLine = fgets( $statesCAN_List ); $textLine = trim( $textLine ); ?>
				"<option><?php esc_attr_e($textLine, 'lddbd-text'); ?></option>" +
			<?php } ?>
			<?php fclose( $statesCAN_List ); ?>
		// End the loop that generates the list of Canadian provinces/territories
				"</select><br />" +
			"</div>" +
			"<div class='lddbd_input_holder'>" +
			"<label for='name'><?php _e('Postal Code:', 'lddbd-text'); ?></label>" +
				"<input type='text' id='lddbd_address_zip' name='address_zip' />" +
			"</div>"
		);
		break;

	case "Denmark":
		jQuery("#selectedCountryForm").html(
			"<div class='lddbd_input_holder'>" +
			"<label for='name'><?php _e('Postal Code and District:', 'lddbd-text'); ?></label>" +
				"<input type='text' id='lddbd_address_zip' name='address_zip' /><br />" +
			"</div>" +
			"<div class='lddbd_input_holder'>" +
			"<label for='name'><?php _e('City / Town / Village:', 'lddbd-text'); ?></label>" +
				"<input type='text' id='lddbd_address_city' name='address_city' />" +
			"</div>"
		);
		break;

	case "Finland":
		jQuery("#selectedCountryForm").html(
			"<div class='lddbd_input_holder'>" +
			"<label for='name'><?php _e('Postal Code and District:', 'lddbd-text'); ?></label>" +
				"<input type='text' id='lddbd_address_zip' name='address_zip' />" +
			"</div>"
		);
		break;

	case "Ireland":
		jQuery("#selectedCountryForm").html(
			"<div class='lddbd_input_holder'>" +
			"<label for='name'><?php _e('City / Town:', 'lddbd-text'); ?></label>" +
				"<input type='text' id='lddbd_address_city' name='address_city' /><br />" +
			"</div>"
		);
		break;

	case "Italy":
		jQuery("#selectedCountryForm").html(
			"<div class='lddbd_input_holder'>" +
			"<label for='name'><?php _e('Postcode:', 'lddbd-text'); ?></label>" +
				"<input type='text' id='lddbd_address_zip' name='address_zip' /><br />" +
			"</div>" +
			"<div class='lddbd_input_holder'>" +
			"<label for='name'><?php _e('City / Town:', 'lddbd-text'); ?></label>" +
				"<input type='text' id='lddbd_address_city' name='address_city' /><br />" +
			"</div>" +
			"<div class='lddbd_input_holder'>" +
			"<label for='name'><?php _e('Province:', 'lddbd-text'); ?></label>" +
				"<input type='text' id='lddbd_address_state' name='address_state' />" +
			"</div>"
		);
		break;

	case "Malaysia":
		jQuery("#selectedCountryForm").html(
			"<div class='lddbd_input_holder'>" +
			"<label for='name'><?php _e('City / Town:', 'lddbd-text'); ?></label>" +
				"<input type='text' id='lddbd_address_city' name='address_city' /><br />" +
			"</div>" +
			"<div class='lddbd_input_holder'>" +
			"<label for='name'><?php _e('State / Province:', 'lddbd-text'); ?></label>" +
				"<select id='lddbd_address_state' name='address_state'>" +
		// Start the loop that generates the list of Malaysian states
			<?php $statesMAL_TextFile = plugin_dir_path( __FILE__ ) . 'states_MAL.txt'; ?>
			<?php $statesMAL_List = fopen( $statesMAL_TextFile, 'r' ); ?>
			<?php while( !feof ( $statesMAL_List ) ) { ?>
			<?php $textLine = fgets( $statesMAL_List ); $textLine = trim( $textLine ); ?>
				"<option><?php esc_attr_e($textLine, 'lddbd-text'); ?></option>" +
			<?php } ?>
			<?php fclose( $statesMAL_List ); ?>
		// End the loop that generates the list of Malaysian states
				"</select><br />" +
			"</div>" +
			"<div class='lddbd_input_holder'>" +
			"<label for='name'><?php _e('Postal Code:', 'lddbd-text'); ?></label>" +
				"<input type='text' id='lddbd_address_zip' name='address_zip' />" +
			"</div>"
		);
		break;

	case "Mexico":
		jQuery("#selectedCountryForm").html(
			"<div class='lddbd_input_holder'>" +
			"<label for='name'><?php _e('Postal Code:', 'lddbd-text'); ?></label>" +
				"<input type='text' id='lddbd_address_zip' name='address_zip' /><br />" +
			"</div>" +
			"<div class='lddbd_input_holder'>" +
			"<label for='name'><?php _e('City / Town:', 'lddbd-text'); ?></label>" +
				"<input type='text' id='lddbd_address_city' name='address_city' /><br />" +
			"</div>" +
			"<div class='lddbd_input_holder'>" +
			"<label for='name'><?php _e('Province:', 'lddbd-text'); ?></label>" +
				"<input type='text' id='lddbd_address_state' name='address_state' />" +
			"</div>"
		);
		break;

	case "United Kingdom":
		jQuery("#selectedCountryForm").html(
			"<div class='lddbd_input_holder'>" +
			"<label for='name'><?php _e('City / Town:', 'lddbd-text'); ?></label>" +
				"<input type='text' id='lddbd_address_city' name='address_city' /><br />" +
			"</div>" +
			"<div class='lddbd_input_holder'>" +
			"<label for='name'><?php _e('Postcode:', 'lddbd-text'); ?></label>" +
				"<input type='text' id='lddbd_address_zip' name='address_zip' />" +
			"</div>"
		);
		break;

	default:
		jQuery("#selectedCountryForm").html( "" );
	}
}
jQuery("#lddbd_address_country").change(formElemCountry);
formElemCountry();
</script>